<?php

use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;

it('should validate the name field as required', function () {
    $request = new RegisterRequest();
    $validator = Validator::make(['name' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The name field is required', $validator->errors()->first('name'));
});

it('should validate the email field as required', function () {
    $request = new RegisterRequest();
    $validator = Validator::make(['email' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The email address field is required', $validator->errors()->first('email'));
});

it('should validate the email field as a valid email address', function () {
    $request = new RegisterRequest();
    $validator = Validator::make(['email' => 'invalid-email'], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The email field must be a valid email address', $validator->errors()->first('email'));
});

it('should validate the password field as at least 6 characters', function () {
    $request = new RegisterRequest();
    $validator = Validator::make(['password' => '12345'], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The password must be 6 or more characters', $validator->errors()->first('password'));
});



