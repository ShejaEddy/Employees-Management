
<?php
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Validator;

it('validates the email field', function () {
    $passwordRequest = new ResetPasswordRequest();
    $validator = Validator::make(['email' => ''], $passwordRequest->rules(), $passwordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
    expect($validator->errors()->first('email'))->toBe('The email address field is required');
});

it('validates the email field with invalid email', function () {
    $passwordRequest = new ResetPasswordRequest();
    $validator = Validator::make(['email' => 'invalid'], $passwordRequest->rules(), $passwordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
    expect($validator->errors()->first('email'))->toBe('The email field must be a valid email address');
});

it('validates the password field', function () {
    $passwordRequest = new ResetPasswordRequest();
    $validator = Validator::make(['password' => ''], $passwordRequest->rules(), $passwordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('password'))->toBeTrue();
    expect($validator->errors()->first('password'))->toBe('The password field is required');
});

it('validates the password field with less than 6 characters', function () {
    $passwordRequest = new ResetPasswordRequest();
    $validator = Validator::make(['password' => '12345'], $passwordRequest->rules(), $passwordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('password'))->toBeTrue();
    expect($validator->errors()->first('password'))->toBe('The password must be 6 or more characters');
});

it('validates the password confirmation', function () {
    $passwordRequest = new ResetPasswordRequest();
    $validator = Validator::make(['password' => 'password', 'password_confirmation' => 'different'], $passwordRequest->rules(), $passwordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('password'))->toBeTrue();
    expect($validator->errors()->first('password'))->toBe('Passwords do not match');
});

it('validates the token field', function () {
    $passwordRequest = new ResetPasswordRequest();
    $validator = Validator::make(['token' => ''], $passwordRequest->rules(), $passwordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('token'))->toBeTrue();
    expect($validator->errors()->first('token'))->toBe('The token field is required');
});
