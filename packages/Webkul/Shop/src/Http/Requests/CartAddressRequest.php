<?php

namespace Webkul\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Rules\AddressLines;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Core\Rules\StateBelongsToCountry;
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
     * Determine if the user is authorized to make this request.
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

        if (
            ! $this->input('billing.use_for_shipping')
            && Cart::getCart()?->haveStockableItems()
        ) {
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
            "{$addressType}.company_name" => ['nullable', 'max:255'],
            "{$addressType}.first_name" => ['required', 'max:255'],
            "{$addressType}.last_name" => ['required', 'max:255'],
            "{$addressType}.email" => ['required', 'max:255'],
            "{$addressType}.address" => ['required', 'array', 'min:1', new AddressLines],
            "{$addressType}.city" => ['required', 'max:255'],
            "{$addressType}.country" => core()->isCountryRequired() ? ['required'] : ['nullable'],
            "{$addressType}.state" => [
                core()->isStateRequired() ? 'required' : 'nullable',
                'max:255',
                new StateBelongsToCountry($this->input("{$addressType}.country")),
            ],
            "{$addressType}.postcode" => core()->isPostCodeRequired() ? ['required', 'max:255', new PostCode] : ['max:255', new PostCode],
            "{$addressType}.phone" => ['required', 'max:255', new PhoneNumber],
        ]);

        if ($addressType == 'billing') {
            $this->mergeWithRules([
                "{$addressType}.vat_id" => ['max:255', (new VatIdRule)->setCountry($this->input('billing.country'))],
            ]);
        }
    }

    /**
     * Merge additional rules.
     */
    private function mergeWithRules(array $rules): void
    {
        $this->rules = array_merge($this->rules, $rules);
    }
}
