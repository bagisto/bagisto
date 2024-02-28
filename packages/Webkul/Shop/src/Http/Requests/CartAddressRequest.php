<?php

namespace Webkul\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\AlphaNumericSpace;
use Webkul\Core\Rules\PhoneNumber;

class CartAddressRequest extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Determine if the product is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->has('billing')) {
            $this->mergeAddressRules('billing');
        }

        if ($this->has('shipping')) {
            $this->mergeAddressRules('shipping');
        }

        return $this->rules;
    }

    /**
     * Merge new address rules.
     *
     * @return void
     */
    private function mergeAddressRules(string $addressType)
    {
        $customerAddressIds = $this->getCustomerAddressIds();

        if (! empty($this->input("{$addressType}.id"))) {
            $this->mergeWithRules([
                "{$addressType}.id" => ["in:{$customerAddressIds}"],
            ]);
        }

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
     */
    private function mergeWithRules($additionalRules): void
    {
        $this->rules = array_merge($this->rules, $additionalRules);
    }

    /**
     * If customer is placing order then fetching all address ids to check with the request ids.
     */
    private function getCustomerAddressIds(): string
    {
        if ($customer = auth()->guard()->user()) {
            return $customer->addresses->pluck('id')->join(',');
        }

        return '';
    }
}
