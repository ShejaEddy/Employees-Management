<?php

use App\Mail\ResetPasswordMail;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\post;

it('should return Passwords do not match when password and password_confirmation do not match', function () {
    $admin = Admin::factory()->create();

    $response = post('/api/admins/reset-password', [
        'email' => $admin->email,
        'password' => 'password',
        'password_confirmation' => 'password1',
        'token' => 'valid-token'
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'password' => ['Passwords do not match']
            ],
        ]);
});

it('should return Passwords do not match when password_confirmation is missing', function () {
    $admin = Admin::factory()->create();

    $response = post('/api/admins/reset-password', [
        'email' => $admin->email,
        'password' => 'password',
        'token' => 'valid-token'
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Validation failed',
            'errors' => [
                'password' => ['Passwords do not match']
            ],
        ]);
});

it('should return Invalid token when token is invalid', function () {
    $admin = Admin::factory()->create();

    $response = post('/api/admins/reset-password', [
        'email' => $admin->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'token' => 'invalid'
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'status' => 403,
            'message' => 'Invalid token',
        ]);
});

it('should return Token has expired if token is more than 2 hours', function () {
    $admin = Admin::factory()->create();

    post('/api/admins/forgot-password', ['email' => $admin->email]);

    $tokens_table = DB::table('password_resets')->where('email', $admin->email)->first(['token']);

    DB::table('password_resets')->where('email', $admin->email)->update([
        'created_at' => Carbon::now()->subHours(3)
    ]);

    $response = post('/api/admins/reset-password', [
        'email' => $admin->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'token' => $tokens_table->token
    ]);

    $token = DB::table('password_resets')->where('email', $admin->email)->first(['token']);

    expect($token)->toBeNull();

    $response->assertStatus(403)
        ->assertJson([
            'status' => 403,
            'message' => 'Token has expired, request a new one',
        ]);
});

it('should reset password and send a successful email to admin', function () {
    $admin = Admin::factory()->create();

    post('/api/admins/forgot-password', ['email' => $admin->email]);

    $tokens_table = DB::table('password_resets')->where('email', $admin->email)->first(['token']);

    Mail::fake();

    $response = post('/api/admins/reset-password', [
        'email' => $admin->email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'token' => $tokens_table->token
    ]);

    Mail::assertQueued(ResetPasswordMail::class, function ($mail) use ($admin) {
        expect($mail->to[0]['address'])->toBe($admin->email);
        expect($mail->build()->subject)->toContain('Password Reset Confirmation');
        expect($mail->build()->view)->toContain('emails.reset_password_mail');
        return true;
    });

    $token = DB::table('password_resets')->where('email', $admin->email)->exists();
    expect($token)->toBeFalse();

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'message' => 'Password reset successfully',
        ]);
});
