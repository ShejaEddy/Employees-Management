<?php

namespace App\Http\Requests;

use App\Traits\BaseTraits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    use BaseTraits;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:admins,email'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'The email address field is required',
            'email.email' => 'The email field must be a valid email address',
            'email.exists' => 'The email address does not exist'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->respondValidationError($validator);
    }
}
