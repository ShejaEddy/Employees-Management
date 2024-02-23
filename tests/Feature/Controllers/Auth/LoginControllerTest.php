<?php

use App\Models\Admin;

use function Pest\Laravel\post;

it('can validate all missing admin login missing fields', function () {
    $response = post('/api/admins/login', []);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'email' => ['The email address field is required'],
                'password' => ['The password field is required'],
            ],
        ]);
});

it('can validate email correctness on admin login', function () {
    $user = Admin::factory()->create([
        'email' => '1234'
    ]);

    $response = post('/api/admins/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'email' => ['The email field must be a valid email address'],
            ],
        ]);
});

it('can return error on invalid admin credentials', function () {
    $response = post('/api/admins/login', [
        'email' => 'invalid@email.com',
        'password' => 'invalid-password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'status' => 401,
            'message' => 'Invalid credentials, try again',
        ]);
});

it('can login admin with valid credentials', function (Admin $admin) {

    $response = post('/api/admins/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Admin logged in successfully',
            'data' => [
                'user' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ],
                'token' => $response['data']['token'],
            ],
        ]);

})->with('admin');
