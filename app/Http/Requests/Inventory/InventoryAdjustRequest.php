<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAdjustRequest extends FormRequest
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
            'new_quantity' => ['required', 'integer', 'min:0'],
            'reason' => ['required', 'string', 'max:1000'],
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
            'new_quantity.required' => __('validation.required', ['attribute' => 'new quantity']),
            'new_quantity.min' => __('validation.min.numeric', ['attribute' => 'new quantity', 'min' => 0]),
            'reason.required' => __('validation.required', ['attribute' => 'reason']),
            'reason.max' => __('validation.max.string', ['attribute' => 'reason', 'max' => 1000]),
        ];
    }
}
