<?php

use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Support\Facades\Validator;

it('should validate the email field as a valid email address', function () {
    $request = new StoreEmployeeRequest();
    $validator = Validator::make(['email' => 'invalid-email'], $request->rules(), $request->messages());

    $this->assertTrue($validator->fails());
    $this->assertEquals('The email field must be a valid email address', $validator->errors()->first('email'));
});
