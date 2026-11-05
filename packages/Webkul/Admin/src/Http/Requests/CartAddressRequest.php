<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Core\Rules\AddressLines;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Core\Rules\StateBelongsToCountry;

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
            && app(CartRepository::class)->find($this->route('id'))?->haveStockableItems()
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
            "{$addressType}.country" => ['required'],
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
