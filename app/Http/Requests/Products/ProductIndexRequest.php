<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductIndexRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'is_active' => ['nullable', 'boolean'],
            'code_prefix' => ['nullable', 'string', 'max:10'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if (request()->filled('min_price') && $value < request()->input('min_price')) {
                        $fail(__('validation.gte.numeric', [
                            'attribute' => __('validation.attributes.max_price'),
                            'value' => __('validation.attributes.min_price')
                        ]));
                    }
                }
            ],
            'stock_threshold' => ['nullable', 'integer', 'min:0'],
            'expiring_soon_days' => ['nullable', 'integer', 'min:1'],
            'is_expired' => ['nullable', 'boolean'],
            'sort_by' => ['nullable', 'string', 'in:id,name,code,price,stock,created_at,updated_at'],
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
            'category_id.integer' => __('validation.integer', ['attribute' => __('validation.attributes.category')]),
            'category_id.exists' => __('validation.exists', ['attribute' => __('validation.attributes.category')]),
            'min_price.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.min_price')]),
            'min_price.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.min_price'), 'min' => 0]),
            'max_price.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.max_price')]),
            'max_price.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.max_price'), 'min' => 0]),
            'max_price.gte' => __('validation.gte.numeric', ['attribute' => __('validation.attributes.max_price'), 'value' => __('validation.attributes.min_price')]),
            'sort_by.in' => __('validation.in', ['attribute' => __('validation.attributes.sort_by')]),
            'sort_order.in' => __('validation.in', ['attribute' => __('validation.attributes.sort_order')]),
            'page.integer' => __('validation.integer', ['attribute' => __('validation.attributes.page')]),
            'page.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.page'), 'min' => 1]),
            'per_page.integer' => __('validation.integer', ['attribute' => __('validation.attributes.per_page')]),
            'per_page.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.per_page'), 'min' => 1]),
            'per_page.max' => __('validation.max.numeric', ['attribute' => __('validation.attributes.per_page'), 'max' => 100]),
        ];
    }
}
