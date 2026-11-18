<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\AddressLines;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Core\Rules\StateBelongsToCountry;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Rules\AllowedCountry;
use Webkul\Customer\Rules\VatIdRule;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        return [
            'company_name' => ['nullable', 'max:255'],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'address' => ['required', 'array', 'min:1', new AddressLines],
            'country' => ['required', new AllowedCountry($this->customerChannelCode())],
            'state' => ['required', 'max:255', new StateBelongsToCountry($this->input('country'))],
            'city' => ['required', 'max:255'],
            'postcode' => ['required', 'max:255', new PostCode],
            'phone' => ['required', 'max:255', new PhoneNumber],
            'vat_id' => ['max:255', (new VatIdRule)->setCountry($this->input('country'))],
            'email' => ['required', 'max:255'],
            'default_address' => ['sometimes', 'required', 'in:0,1'],
        ];
    }

    /**
     * Attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'address.*' => 'address',
        ];
    }

    /**
     * Retrieve the channel of the customer the address belongs to, whose address settings apply.
     */
    protected function customerChannelCode(): ?string
    {
        $customer = $this->routeIs('admin.customers.customers.addresses.store')
            ? app(CustomerRepository::class)->find($this->route('id'))
            : app(CustomerAddressRepository::class)->find($this->route('id'))?->customer;

        return $customer?->channel?->code;
    }
}
