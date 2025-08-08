<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');
        $commonRules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'base_sku' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'base_sku')->whereNull('deleted_at')->ignore($productId)
            ],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'expiry_date' => ['nullable', 'date', 'after:today'],
            'image' => ['nullable', 'url'],
            'is_active' => ['boolean'],
        ];
        if ($this->isMethod('post')) {
            $commonRules['base_unit'] = ['required', 'string', 'max:255'];
        }
        return $commonRules;
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('validation.attributes.name')]),
            'name.string' => __('validation.string', ['attribute' => __('validation.attributes.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('validation.attributes.name'), 'max' => 255]),

            'description.string' => __('validation.string', ['attribute' => __('validation.attributes.description')]),
            'description.max' => __(
                'validation.max.string',
                ['attribute' => __('validation.attributes.description'), 'max' => 1000]
            ),

            'price.required' => __('validation.required', ['attribute' => __('validation.attributes.price')]),
            'price.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.price')]),
            'price.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.price'), 'min' => 0]),

            'cost.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.cost')]),
            'cost.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.cost'), 'min' => 0]),

            'stock.required' => __('validation.required', ['attribute' => __('validation.attributes.stock')]),
            'stock.integer' => __('validation.integer', ['attribute' => __('validation.attributes.stock')]),
            'stock.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.stock'), 'min' => 0]),

            'min_stock.integer' => __('validation.integer', ['attribute' => __('validation.attributes.min_stock')]),
            'min_stock.min' => __(
                'validation.min.numeric',
                ['attribute' => __('validation.attributes.min_stock'), 'min' => 0]
            ),

            'base_sku.string' => __('validation.string', ['attribute' => __('validation.attributes.base_sku')]),
            'base_sku.max' => __(
                'validation.max.string',
                ['attribute' => __('validation.attributes.base_sku'), 'max' => 255]
            ),

            'base_unit.required' => __('validation.required', ['attribute' => __('validation.attributes.base_unit')]),
            'base_unit.string' => __('validation.string', ['attribute' => __('validation.attributes.base_unit')]),
            'base_unit.max' => __(
                'validation.max.string',
                ['attribute' => __('validation.attributes.base_unit'), 'max' => 255]
            ),

            'base_unit_id.required' => __(
                'validation.required',
                ['attribute' => __('validation.attributes.base_unit_id')]
            ),
            'base_unit_id.integer' => __('validation.integer', ['attribute' => __('validation.attributes.base_unit_id')]
            ),
            'base_unit_id.exists' => __('validation.exists', ['attribute' => __('validation.attributes.base_unit_id')]),

            'category_id.required' => __('validation.required', ['attribute' => __('validation.attributes.category')]),
            'category_id.exists' => __('validation.exists', ['attribute' => __('validation.attributes.category')]),

            'expiry_date.after' => __(
                'validation.after',
                ['attribute' => __('validation.attributes.expiry_date'), 'date' => 'today']
            ),
            'image.url' => __('validation.url', ['attribute' => __('validation.attributes.image')]),

            'is_active.boolean' => __('validation.boolean', ['attribute' => __('validation.attributes.is_active')]),
        ];
    }
}
