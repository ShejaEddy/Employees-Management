<?php

namespace App\Http\Requests;

use App\Traits\BaseTraits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Illuminate\Contracts\Validation\Validator;

class UpdateEmployeeRequest extends FormRequest
{
    use BaseTraits;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'names' => 'sometimes|nullable|string',
            'email' => 'sometimes|nullable|email|unique:employees,email,except,' . $this->id,
            'phone_number' => 'sometimes|nullable|unique:employees,email,except,' . $this->id,
            'badge_id' => 'sometimes|nullable|string|unique:employees,email,except,' . $this->id
        ];
    }

    public function messages()
    {
        return [
            'names.required' => 'The names field is required',
            'email.required' => 'The email address field is required',
            'email.email' => 'The email field must be a valid email address',
            'email.unique' => 'The email address is already in use',
            'phone_number.required' => 'The phone number field is required',
            'phone_number.unique' => 'The phone number is already in use',
            'badge_id.required' => 'The badge ID field is required',
            'badge_id.unique' => 'The badge ID is already in use'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->respondValidationError($validator);
    }
}
