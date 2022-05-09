<?php

namespace Webkul\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Determine if the product is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'inventories'   => 'required|array',
            'inventories.*' => 'required|integer|min:0',
        ];
    }

    /**
     * Custom message for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'inventories.*.required' => __('admin::app.catalog.products.validations.quantity-required'),
            'inventories.*.integer'  => __('admin::app.catalog.products.validations.quantity-integer'),
            'inventories.*.min'      => __('admin::app.catalog.products.validations.quantity-min-zero'),
        ];
    }
}
