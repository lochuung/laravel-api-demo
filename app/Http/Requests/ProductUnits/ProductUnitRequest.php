<?php

namespace App\Http\Requests\ProductUnits;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class ProductUnitRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $unitId = $this->route('unit');

        return [
            'unit_name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255',
                Rule::unique('product_units')->ignore($unitId)],
            'conversion_rate' => ['required', 'numeric', 'min:0.0001'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'is_base_unit' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'unit_name.required' => __('validation.required', ['attribute' => __('validation.attributes.unit_name')]),
            'unit_name.string' => __('validation.string', ['attribute' => __('validation.attributes.unit_name')]),
            'unit_name.max' => __('validation.max.string', ['attribute' => __('validation.attributes.unit_name'), 'max' => 255]),

            'sku.string' => __('validation.string', ['attribute' => __('validation.attributes.sku')]),
            'sku.max' => __('validation.max.string', ['attribute' => __('validation.attributes.sku'), 'max' => 255]),
            'sku.unique' => __('validation.unique', ['attribute' => __('validation.attributes.sku')]),

            'conversion_rate.required' => __('validation.required', ['attribute' => __('validation.attributes.conversion_rate')]),
            'conversion_rate.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.conversion_rate')]),
            'conversion_rate.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.conversion_rate'), 'min' => 0.0001]),

            'selling_price.required' => __('validation.required', ['attribute' => __('validation.attributes.selling_price')]),
            'selling_price.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.selling_price')]),
            'selling_price.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.selling_price'), 'min' => 0]),

            'is_base_unit.required' => __('validation.required', ['attribute' => __('validation.attributes.is_base_unit')]),
            'is_base_unit.boolean' => __('validation.boolean', ['attribute' => __('validation.attributes.is_base_unit')]),
        ];
    }
}
