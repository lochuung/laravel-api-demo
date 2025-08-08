<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('validation.attributes.name')]),
            'email.required' => __('validation.required', ['attribute' => __('validation.attributes.email')]),
            'email.email' => __('validation.email', ['attribute' => __('validation.attributes.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('validation.attributes.email')]),
            'password.required' => __('validation.required', ['attribute' => __('validation.attributes.password')]),
            'password.min' => __(
                'validation.min.string',
                ['attribute' => __('validation.attributes.password'), 'min' => 8]
            ),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('validation.attributes.password')]),
            'password_confirmation.required' => __(
                'validation.required',
                ['attribute' => __('validation.attributes.password_confirmation')]
            ),
            'password_confirmation.min' => __(
                'validation.min.string',
                ['attribute' => __('validation.attributes.password_confirmation'), 'min' => 8]
            ),
        ];
    }
}
