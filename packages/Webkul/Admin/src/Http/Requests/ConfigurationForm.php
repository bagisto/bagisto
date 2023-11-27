<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\CommaSeparatedInteger;

class ConfigurationForm extends FormRequest
{
    /**
     * Determine if the Configuration is authorized to make this request.
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
        $this->rules = [];

        $request = request();

        if (
            request()->has('catalog.products.storefront.products_per_page')
            && ! empty(request()->input('catalog.products.storefront.products_per_page'))
        ) {
            $this->rules = [
                'catalog.products.storefront.products_per_page' => [new CommaSeparatedInteger],
            ];
        }

        $imageFields = [
            'general.design.admin_logo.logo_image',
            'general.design.admin_logo.favicon',
            'sales.invoice_settings.invoice_slip_design.logo',
            'sales.payment_methods.cashondelivery.image',
            'sales.payment_methods.moneytransfer.image',
            'sales.payment_methods.paypal_standard.image',
            'sales.payment_methods.paypal_smart_button.image',
        ];

        foreach ($imageFields as $field) {
            if ($request->has($field) && ! $request->input("$field.delete")) {
                $this->rules = array_merge($this->rules, [
                    $field => 'required|mimes:bmp,jpeg,jpg,png,webp|max:5000',
                ]);
            }
        }

        return $this->rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'general.design.admin_logo.logo_image.mimes' => 'Invalid file format. Use only bmp, jpeg, jpg, png and webp.',
        ];
    }

    /**
     * Set the attribute name.
     */
    public function attributes()
    {
        return [
            'general.design.admin_logo.logo_image'             => 'Logo Image',
            'general.design.admin_logo.favicon'                => 'Favicon Image',
            'sales.invoice_settings.invoice_slip_design.logo'  => 'Invoice Logo',
            'catalog.products.storefront.products_per_page'    => 'Product Per Page',
        ];
    }
}
