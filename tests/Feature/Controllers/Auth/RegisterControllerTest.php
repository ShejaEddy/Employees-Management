<?php

use App\Models\Admin;

use function Pest\Laravel\post;

it('should validate the password confirmation field as matching', function () {
    $response = post('/api/admins/register', [
        'email' => 'email@exmaple.com',
        'password' => 'password',
        'password_confirmation' => 'invalid-password',
        'names' => 'Sheja Eddy'
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'password' => ['Passwords do not match'],
            ],
        ]);
});

it('should return Passwords do not match when password_confirmation is missing', function (Admin $admin) {
    $response = post('/api/admins/reset-password', [
        'email' => $admin->email,
        'password' => 'password',
        'name' => 'Sheja Eddy'
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'password' => ['Passwords do not match']
            ],
        ]);
})->with('admin');

it('should validate the email field as unique', function (Admin $existingUser) {
    $response = post('/api/admins/register', [
        'email' => $existingUser->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'name' => 'Sheja Eddy'
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'email' => ['The email address is already in use'],
            ],
        ]);
})->with('admin');

it('can register a new admin', function () {
    $response = post('/api/admins/register', [
        'name' => 'Sheja Eddy',
        'email' => 'sheja@eddy.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'status' => 201,
            'message' => 'Admin registered successfully',
            'data' => [
                'user' => [
                    'name' => 'Sheja Eddy',
                    'email' => 'sheja@eddy.com',
                ],
            ],
        ]);
});
