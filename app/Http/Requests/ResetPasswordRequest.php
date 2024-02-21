<?php

namespace App\Http\Requests;

use App\Traits\BaseTraits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    use BaseTraits;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'The email address field is required',
            'email.email' => 'The email field must be a valid email address',
            'password' => 'The password field is required',
            'password.min' => 'The password must be 6 or more characters',
            'password.confirmed' => 'Passwords do not match',
            'token.required' => 'The token field is required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->respondValidationError($validator);
    }
}
