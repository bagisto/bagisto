<?php

namespace Webkul\Shop\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\AlphaNumericSpace;
use Webkul\Core\Rules\PhoneNumber;

class CustomerAddressForm extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
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
        if (isset($this->get('billing')['address_id'])) {
            $this->mergeExistingAddressRules('billing');
        } else {
            $this->mergeNewAddressRules('billing');
        }

        if (
            isset($this->get('billing')['use_for_shipping'])
            && ! $this->get('billing')['use_for_shipping']
        ) {
            if (isset($this->get('shipping')['address_id'])) {
                $this->mergeExistingAddressRules('shipping');
            } else {
                $this->mergeNewAddressRules('shipping');
            }
        }

        return $this->rules;
    }

    /**
     * Merge existing address rules.
     *
     * @param  string  $addressType
     * @return void
     */
    private function mergeExistingAddressRules(string $addressType)
    {
        $customerAddressIds = $this->getCustomerAddressIds();

        $addressRules = [
            "{$addressType}.address_id" => ['required'],
        ];

        if (! empty($customerAddressIds)) {
            $addressRules["{$addressType}.address_id"][] = "in:{$customerAddressIds}";
        }

        $this->mergeWithRules($addressRules);
    }

    /**
     * Merge new address rules.
     *
     * @param  string  $addressType
     * @return void
     */
    private function mergeNewAddressRules(string $addressType)
    {
        $this->mergeWithRules([
            "{$addressType}.first_name" => ['required', new AlphaNumericSpace],
            "{$addressType}.last_name"  => ['required', new AlphaNumericSpace],
            "{$addressType}.email"      => ['required'],
            "{$addressType}.address1"   => ['required', 'array', 'min:1'],
            "{$addressType}.address1.*" => ['string'],
            "{$addressType}.city"       => ['required'],
            "{$addressType}.country"    => [new AlphaNumericSpace],
            "{$addressType}.state"      => [new AlphaNumericSpace],
            "{$addressType}.postcode"   => ['numeric'],
            "{$addressType}.phone"      => ['required', new PhoneNumber],
        ]);
    }

    /**
     * Merge additional rules.
     *
     * @return void
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
        if ($customer = auth()->guard()->user()) {
            return $customer->addresses->pluck('id')->join(',');
        }

        return '';
    }
}
