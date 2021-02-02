<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class ConfigurationForm extends FormRequest
{
    /*
        Added custom validator.
     */
    public function __construct(ValidationFactory $validationFactory)
    {
        /* added custom comma seperated integer validator */
        $validationFactory->extend(
            'comma_seperated_integer',
            function ($attribute, $value, $parameters) {
                $pages = explode(',', $value);
                foreach($pages as $page){
                    if (! is_numeric($page)) {
                        return false;
                    }
                }
                return true;
            }
        );
    }

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

        if (request()->has('catalog.products.storefront.products_per_page')
            && ! empty(request()->input('catalog.products.storefront.products_per_page'))
        ) {
            $this->rules = [
                'catalog.products.storefront.products_per_page'  => 'comma_seperated_integer',
            ];
        }

        if (request()->has('general.design.admin_logo.logo_image')
            && ! request()->input('general.design.admin_logo.logo_image.delete')
        ) {
            $this->rules = array_merge($this->rules, [
                'general.design.admin_logo.logo_image'  => 'required|mimes:bmp,jpeg,jpg,png,webp|max:5000',
            ]);
        }

        if (request()->has('general.design.admin_logo.favicon')
            && ! request()->input('general.design.admin_logo.favicon.delete')
        ) {
            $this->rules = array_merge($this->rules, [
                'general.design.admin_logo.favicon'  => 'required|mimes:bmp,jpeg,jpg,png,webp|max:5000',
            ]);
        }

        if (request()->has('sales.orderSettings.invoice_slip_design.logo')
            && ! request()->input('sales.orderSettings.invoice_slip_design.logo.delete')
        ) {
            $this->rules = array_merge($this->rules, [
                'sales.orderSettings.invoice_slip_design.logo'  => 'required|mimes:bmp,jpeg,jpg,png,webp|max:5000',
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
            'catalog.products.storefront.products_per_page.comma_seperated_integer' => 'The "Product Per Page" field must be numeric and may contain comma.'
        ];
    }

    /**
     * Set the attribute name.
     */
    public function attributes()
    {
        return [
            'general.design.admin_logo.logo_image' => 'Logo Image',
            'general.design.admin_logo.favicon' => 'Favicon Image',
            'sales.orderSettings.invoice_slip_design.logo' => 'Invoice Logo'
        ];
    }
}