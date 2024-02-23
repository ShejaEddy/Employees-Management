
<?php

use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Validator;

it('validates the email field as required', function () {
    $forgotPasswordRequest = new ForgotPasswordRequest();
    $validator = Validator::make(['email' => ''], $forgotPasswordRequest->rules(), $forgotPasswordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
    expect($validator->errors()->first('email'))->toBe('The email address field is required');
});

it('validates the email field as a valid email address', function () {
    $forgotPasswordRequest = new ForgotPasswordRequest();
    $validator = Validator::make(['email' => 'invalid-email'], $forgotPasswordRequest->rules(), $forgotPasswordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
    expect($validator->errors()->first('email'))->toBe('The email field must be a valid email address');
});

it('validates the email field as an existing email address in the admins table', function () {
    $forgotPasswordRequest = new ForgotPasswordRequest();
    $validator = Validator::make(['email' => 'incorrect-email@example.com'], $forgotPasswordRequest->rules(), $forgotPasswordRequest->messages());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
    expect($validator->errors()->first('email'))->toBe('The email address does not exist');
});
