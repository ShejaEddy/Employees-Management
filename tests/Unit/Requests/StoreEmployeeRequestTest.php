<?php

use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Support\Facades\Validator;

it('should validate the names field as required', function () {
    $request = new StoreEmployeeRequest();
    $validator = Validator::make(['names' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The names field is required', $validator->errors()->first('names'));
});

it('should validate the email field as required', function () {
    $request = new StoreEmployeeRequest();
    $validator = Validator::make(['email' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The email address field is required', $validator->errors()->first('email'));
});

it('should validate the email field as a valid email address', function () {
    $request = new StoreEmployeeRequest();
    $validator = Validator::make(['email' => 'invalid-email'], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The email field must be a valid email address', $validator->errors()->first('email'));
});

it('should validate the phone_number field as required', function () {
    $request = new StoreEmployeeRequest();
    $validator = Validator::make(['phone_number' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The phone number field is required', $validator->errors()->first('phone_number'));
});

it('should validate the badge_id field as required', function () {
    $request = new StoreEmployeeRequest();
    $validator = Validator::make(['badge_id' => null], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The badge ID field is required', $validator->errors()->first('badge_id'));
});
