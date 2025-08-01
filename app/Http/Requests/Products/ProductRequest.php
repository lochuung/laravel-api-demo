<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');
        $commonRules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'code_prefix' => ['nullable', 'string', 'max:50'],
            'sku' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'expiry_date' => ['nullable', 'date', 'after:today'],
            'image' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];

        if ($this->isMethod('post')) {
            // Validation rules for creating a product
            return array_merge($commonRules, [
                'barcode' => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
            ]);
        } else {
            // Validation rules for updating a product
            return array_merge($commonRules, [
                'barcode' => ['nullable', 'string', 'max:100', Rule::unique('products', 'barcode')->ignore($productId)],
            ]);
        }
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('validation.attributes.name')]),
            'name.string' => __('validation.string', ['attribute' => __('validation.attributes.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('validation.attributes.name'), 'max' => 255]),
            'code.required' => __('validation.required', ['attribute' => __('validation.attributes.code')]),
            'code.unique' => __('validation.unique', ['attribute' => __('validation.attributes.code')]),
            'price.required' => __('validation.required', ['attribute' => __('validation.attributes.price')]),
            'price.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.price')]),
            'price.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.price'), 'min' => 0]),
            'stock.required' => __('validation.required', ['attribute' => __('validation.attributes.stock')]),
            'stock.integer' => __('validation.integer', ['attribute' => __('validation.attributes.stock')]),
            'stock.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.stock'), 'min' => 0]),
            'category_id.required' => __('validation.required', ['attribute' => __('validation.attributes.category')]),
            'category_id.exists' => __('validation.exists', ['attribute' => __('validation.attributes.category')]),
            'expiry_date.after' => __('validation.after', ['attribute' => __('validation.attributes.expiry_date'), 'date' => 'today']),
            'image.url' => __('validation.url', ['attribute' => __('validation.attributes.image')]),
        ];
    }
}
