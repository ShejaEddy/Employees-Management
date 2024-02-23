
<?php

use App\Mail\ForgotPasswordMail;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\post;


it('rejects if 60 seconds has not passed', function (Admin $admin) {
    post('/api/admins/forgot-password', [
        'email' => $admin->email,
    ]);

    $response = post('/api/admins/forgot-password', [
        'email' => $admin->email,
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'status' => 400,
        'message' => 'You can only request a new password reset after 60 seconds',
    ]);
})->with('admin');

it('approves if 60 seconds has passed', function (Admin $admin) {
    post('/api/admins/forgot-password', [
        'email' => $admin->email,
    ]);

    DB::table('password_resets')->where('email', $admin->email)->update([
        'created_at' => now()->subSeconds(60),
    ]);

    $response = post('/api/admins/forgot-password', [
        'email' => $admin->email,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 200,
        'message' => "Password reset link has been sent to your $admin->email. Check your email to reset your password.",
    ]);
})->with('admin');

it('can request a password reset and send email to admin', function (Admin $admin) {
    $email = $admin->email;

    Mail::fake();

    $response = post('/api/admins/forgot-password', [
        'email' => $email,
    ]);

    Mail::assertQueued(ForgotPasswordMail::class, function ($mail) use ($email) {
        expect($mail->to[0]['address'])->toBe($email);

        expect($mail->email)->toBe($email);
        expect($mail->token)->toBeString();
        expect($mail->build()->subject)->toContain('Account Recovery: Reset Password');
        expect($mail->build()->view)->toContain('emails.forgot_password_mail');

        return true;
    });

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 200,
        'message' => "Password reset link has been sent to your $email. Check your email to reset your password.",
    ]);
})->with('admin');
