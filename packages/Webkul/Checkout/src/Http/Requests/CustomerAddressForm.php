<?php

namespace Webkul\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressForm extends FormRequest
{
    /**
     * Rules.
     */
    protected $rules = [];

    /**
     * Determine if the product is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $customerAddressIds = $this->getCustomerAddressIds();

        if (isset($this->get('billing')['address_id'])) {
            $billingAddressRules = [
                'billing.address_id' => ['required'],
            ];

            if (! empty($customerAddressIds)) {
                $billingAddressRules['billing.address_id'][] = "in:{$customerAddressIds}";
            }

            $this->mergeWithRules($billingAddressRules);
        } else {
            $this->mergeWithRules([
                'billing.first_name' => ['required'],
                'billing.last_name'  => ['required'],
                'billing.email'      => ['required'],
                'billing.address1'   => ['required'],
                'billing.city'       => ['required'],
                'billing.state'      => ['required'],
                'billing.postcode'   => ['required'],
                'billing.phone'      => ['required'],
                'billing.country'    => ['required'],
            ]);
        }

        if (isset($this->get('billing')['use_for_shipping']) && ! $this->get('billing')['use_for_shipping']) {
            if (isset($this->get('shipping')['address_id'])) {
                $shippingAddressRules = [
                    'shipping.address_id' => ['required'],
                ];

                if (! empty($customerAddressIds)) {
                    $shippingAddressRules['shipping.address_id'][] = "in:{$customerAddressIds}";
                }

                $this->mergeWithRules($shippingAddressRules);
            } else {
                $this->mergeWithRules([
                    'shipping.first_name' => ['required'],
                    'shipping.last_name'  => ['required'],
                    'shipping.email'      => ['required'],
                    'shipping.address1'   => ['required'],
                    'shipping.city'       => ['required'],
                    'shipping.state'      => ['required'],
                    'shipping.postcode'   => ['required'],
                    'shipping.phone'      => ['required'],
                    'shipping.country'    => ['required'],
                ]);
            }
        }

        return $this->rules;
    }

    /**
     * Merge additional rules.
     *
     * @return string
     */
    private function mergeWithRules($additionalRules): void
    {
        $this->rules = array_merge($this->rules, $additionalRules);
    }

    /**
     * If customer is placing order then fetching all address ids to check with the request ids.
     *
     * @return string
     */
    private function getCustomerAddressIds(): string
    {
        if ($customer = auth('customer')->user()) {
            return $customer->addresses->pluck('id')->join(',');
        }

        return '';
    }
}
