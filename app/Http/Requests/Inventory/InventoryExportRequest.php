<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryExportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_id' => ['nullable', 'integer', 'exists:product_units,id'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => __('validation.required', ['attribute' => 'product']),
            'product_id.exists' => __('validation.exists', ['attribute' => 'product']),
            'quantity.required' => __('validation.required', ['attribute' => 'quantity']),
            'quantity.min' => __('validation.min.numeric', ['attribute' => 'quantity', 'min' => 1]),
            'unit_id.exists' => __('validation.exists', ['attribute' => 'unit']),
            'order_id.exists' => __('validation.exists', ['attribute' => 'order']),
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate that unit belongs to the product if specified
            if ($this->unit_id && $this->product_id) {
                $unitExists = \App\Models\ProductUnit::where('id', $this->unit_id)
                    ->where('product_id', $this->product_id)
                    ->exists();
                
                if (!$unitExists) {
                    $validator->errors()->add('unit_id', __('validation.unit_not_belongs_to_product'));
                }
            }
        });
    }
}
