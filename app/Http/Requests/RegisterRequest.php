<?php

namespace App\Http\Requests;

use App\Traits\BaseTraits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use BaseTraits;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required',
            'email.required' => 'The email address field is required',
            'email.email' => 'The email field must be a valid email address',
            'email.unique' => 'The email address is already in use',
            'password.min' => 'The password must be 6 or more characters',
            'password.confirmed' => 'Passwords do not match'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->respondValidationError($validator);
    }
}
