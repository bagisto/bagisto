<?php

namespace Webkul\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Customer\Rules\VatIdRule;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $trimFields = ['company_name', 'first_name', 'last_name', 'email', 'city', 'postcode', 'phone', 'vat_id'];

        foreach (['billing', 'shipping'] as $addressType) {
            if (! $this->has($addressType)) {
                continue;
            }

            $addressData = $this->input($addressType, []);

            foreach ($trimFields as $field) {
                if (isset($addressData[$field]) && is_string($addressData[$field])) {
                    $addressData[$field] = trim($addressData[$field]);
                }
            }

            $this->merge([$addressType => $addressData]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if ($this->has('billing')) {
            $this->mergeAddressRules('billing');
        }

        if (! $this->input('billing.use_for_shipping')) {
            $this->mergeAddressRules('shipping');
        }

        return $this->rules;
    }

    /**
     * Merge new address rules.
     */
    private function mergeAddressRules(string $addressType): void
    {
        $this->mergeWithRules([
            "{$addressType}.company_name" => ['nullable'],
            "{$addressType}.first_name" => ['required'],
            "{$addressType}.last_name" => ['required'],
            "{$addressType}.email" => ['required'],
            "{$addressType}.address" => ['required', 'array', 'min:1'],
            "{$addressType}.city" => ['required'],
            "{$addressType}.country" => core()->isCountryRequired() ? ['required'] : ['nullable'],
            "{$addressType}.state" => core()->isStateRequired() ? ['required'] : ['nullable'],
            "{$addressType}.postcode" => core()->isPostCodeRequired() ? ['required', new PostCode] : [new PostCode],
            "{$addressType}.phone" => ['required', new PhoneNumber],
        ]);

        if ($addressType == 'billing') {
            $this->mergeWithRules([
                "{$addressType}.vat_id" => [(new VatIdRule)->setCountry($this->input('billing.country'))],
            ]);
        }
    }

    /**
     * Merge additional rules.
     */
    private function mergeWithRules($rules): void
    {
        $this->rules = array_merge($this->rules, $rules);
    }
}
