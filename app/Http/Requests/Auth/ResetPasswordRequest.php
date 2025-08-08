<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ResetPasswordRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
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
            'token.required' => __('validation.required', ['attribute' => __('validation.attributes.token')]),
            'email.required' => __('validation.required', ['attribute' => __('validation.attributes.email')]),
            'email.email' => __('validation.email', ['attribute' => __('validation.attributes.email')]),
            'email.exists' => __('validation.exists', ['attribute' => __('validation.attributes.email')]),
            'password.required' => __('validation.required', ['attribute' => __('validation.attributes.password')]),
            'password.string' => __('validation.string', ['attribute' => __('validation.attributes.password')]),
            'password.min' => __(
                'validation.min.string',
                ['attribute' => __('validation.attributes.password'), 'min' => 8]
            ),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('validation.attributes.password')]),
            'password_confirmation.required' => __(
                'validation.required',
                ['attribute' => __('validation.attributes.password_confirmation')]
            ),
            'password_confirmation.string' => __(
                'validation.string',
                ['attribute' => __('validation.attributes.password_confirmation')]
            ),
        ];
    }
}
