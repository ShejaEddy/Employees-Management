<?php

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;

it('should validate the email field as required', function () {
    $request = new LoginRequest();
    $validator = Validator::make(['email' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The email address field is required', $validator->errors()->first('email'));
});

it('should validate the email field as a valid email address', function () {
    $request = new LoginRequest();
    $validator = Validator::make(['email' => 'invalid-email'], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The email field must be a valid email address', $validator->errors()->first('email'));
});

it('should validate the password field as required', function () {
    $request = new LoginRequest();
    $validator = Validator::make(['password' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The password field is required', $validator->errors()->first('password'));
});

