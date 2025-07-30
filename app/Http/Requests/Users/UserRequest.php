<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');
        $commonRules = [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'role' => ['required', Rule::in(['Admin', 'Moderator', 'User'])],
            'profile_picture' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'email_verified' => ['boolean'],
            'email_verified_at' => ['nullable', 'date'],
        ];

        if ($this->isMethod('post')) {
            // Validation rules for creating a user
            return array_merge($commonRules, [
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
        } else {
            // Validation rules for updating a user
            return array_merge($commonRules, [
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
                'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            ]);
        }
    }

}
