<?php

namespace Webkul\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerAddressForm extends FormRequest
{
    protected $rules;

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
        $this->rules = [
            'billing.first_name' => ['required'],
            'billing.last_name' => ['required'],
            'billing.email' => ['required'],
            'billing.address1' => ['required'],
            'billing.city' => ['required'],
            'billing.state' => ['required'],
            'billing.postcode' => ['required'],
            'billing.phone' => ['required'],
            'billing.country' => ['required']
        ];

        if(isset($this->get('billing')['use_for_shipping']) && !$this->get('billing')['use_for_shipping']) {
            $this->rules = array_merge($this->rules, [
                'shipping.first_name' => ['required'],
                'shipping.last_name' => ['required'],
                'shipping.email' => ['required'],
                'shipping.address1' => ['required'],
                'shipping.city' => ['required'],
                'shipping.state' => ['required'],
                'shipping.postcode' => ['required'],
                'shipping.phone' => ['required'],
                'shipping.country' => ['required']
            ]);
        }

        return $this->rules;
    }
}
