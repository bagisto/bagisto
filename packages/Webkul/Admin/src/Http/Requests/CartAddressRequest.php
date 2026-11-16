<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Core\Rules\AddressLines;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Core\Rules\StateBelongsToCountry;
use Webkul\Customer\Rules\AllowedCountry;

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
        $cart = app(CartRepository::class)->find($this->route('id'));

        $channelCode = $cart?->customer?->channel?->code;

        if ($this->has('billing')) {
            $this->mergeAddressRules('billing', $channelCode);
        }

        if (
            ! $this->input('billing.use_for_shipping')
            && $cart?->haveStockableItems()
        ) {
            $this->mergeAddressRules('shipping', $channelCode);
        }

        return $this->rules;
    }

    /**
     * Merge the address rules, checking the country against the channel of the customer the cart is for.
     */
    private function mergeAddressRules(string $addressType, ?string $channelCode): void
    {
        $this->mergeWithRules([
            "{$addressType}.company_name" => ['nullable', 'max:255'],
            "{$addressType}.first_name" => ['required', 'max:255'],
            "{$addressType}.last_name" => ['required', 'max:255'],
            "{$addressType}.email" => ['required', 'max:255'],
            "{$addressType}.address" => ['required', 'array', 'min:1', new AddressLines],
            "{$addressType}.city" => ['required', 'max:255'],
            "{$addressType}.country" => ['required', new AllowedCountry($channelCode)],
            "{$addressType}.state" => ['required', 'max:255', new StateBelongsToCountry($this->input("{$addressType}.country"))],
            "{$addressType}.postcode" => ['required', 'max:255', new PostCode],
            "{$addressType}.phone" => ['required', 'max:255', new PhoneNumber],
        ]);
    }

    /**
     * Merge additional rules.
     */
    private function mergeWithRules(array $rules): void
    {
        $this->rules = array_merge($this->rules, $rules);
    }
}
