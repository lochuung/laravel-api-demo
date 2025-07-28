<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends BaseApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'refresh_token.required' => 'The refresh token field is required.',
            'refresh_token.string' => 'The refresh token must be a string.',
        ];
    }


}
