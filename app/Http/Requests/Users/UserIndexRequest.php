<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseApiRequest;

class UserIndexRequest extends BaseApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'in:admin,user,moderator'],
            'sort_by' => ['nullable', 'string', 'in:id,name,email,created_at,updated_at'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'search.string' => __('validation.string', ['attribute' => __('validation.attributes.search')]),
            'search.max' => __('validation.max.string', ['attribute' => __('validation.attributes.search'), 'max' => 255]),
            'role.string' => __('validation.string', ['attribute' => __('validation.attributes.role')]),
            'role.in' => __('validation.in', ['attribute' => __('validation.attributes.role')]),
            'sort_by.string' => __('validation.string', ['attribute' => __('validation.attributes.sort_by')]),
            'sort_by.in' => __('validation.in', ['attribute' => __('validation.attributes.sort_by')]),
            'sort_order.string' => __('validation.string', ['attribute' => __('validation.attributes.sort_order')]),
            'sort_order.in' => __('validation.in', ['attribute' => __('validation.attributes.sort_order')]),
            'page.integer' => __('validation.integer', ['attribute' => __('validation.attributes.page')]),
            'page.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.page'), 'min' => 1]),
            'per_page.integer' => __('validation.integer', ['attribute' => __('validation.attributes.per_page')]),
            'per_page.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.per_page'), 'min' => 1]),
            'per_page.max' => __('validation.max.numeric', ['attribute' => __('validation.attributes.per_page'), 'max' => 100]),
        ];
    }


}
