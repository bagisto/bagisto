<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Contracts\Validations\CommaSeperatedInteger;

class ConfigurationForm extends FormRequest
{
    /**
     * Determine if the Configuraion is authorized to make this request.
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

        if (
            request()->has('catalog.products.storefront.products_per_page')
            && ! empty(request()->input('catalog.products.storefront.products_per_page'))
        ) {
            $this->rules = [
                'catalog.products.storefront.products_per_page' => [new CommaSeperatedInteger],
            ];
        }

        if (
            request()->has('general.design.admin_logo.logo_image')
            && ! request()->input('general.design.admin_logo.logo_image.delete')
        ) {
            $this->rules = array_merge($this->rules, [
                'general.design.admin_logo.logo_image' => 'required|mimes:bmp,jpeg,jpg,png,webp|max:5000',
            ]);
        }

        if (
            request()->has('general.design.admin_logo.favicon')
            && ! request()->input('general.design.admin_logo.favicon.delete')
        ) {
            $this->rules = array_merge($this->rules, [
                'general.design.admin_logo.favicon' => 'required|mimes:bmp,jpeg,jpg,png,webp|max:5000',
            ]);
        }

        if (
            request()->has('sales.invoice_setttings.invoice_slip_design.logo')
            && ! request()->input('sales.invoice_setttings.invoice_slip_design.logo.delete')
        ) {
            $this->rules = array_merge($this->rules, [
                'sales.invoice_setttings.invoice_slip_design.logo' => 'required|mimes:bmp,jpeg,jpg,png,webp|max:5000',
            ]);
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
            'sales.invoice_setttings.invoice_slip_design.logo' => 'Invoice Logo',
            'catalog.products.storefront.products_per_page'    => 'Product Per Page',
        ];
    }
}
