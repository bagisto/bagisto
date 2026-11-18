<?php

namespace Webkul\Shop\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\AddressLines;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;
use Webkul\Core\Rules\StateBelongsToCountry;
use Webkul\Customer\Facades\AddressSettings;
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
            'country' => [AddressSettings::isCountryRequired() ? 'required' : 'nullable', new AllowedCountry],
            'state' => [
                AddressSettings::isStateRequired() ? 'required' : 'nullable',
                'max:255',
                new StateBelongsToCountry($this->input('country')),
            ],
            'city' => ['required', 'max:255', 'string'],
            'postcode' => AddressSettings::isPostCodeRequired() ? ['required', 'max:255', new PostCode] : ['max:255', new PostCode],
            'phone' => ['required', 'max:255', new PhoneNumber],
            'vat_id' => ['max:255', (new VatIdRule)->setCountry($this->input('country'))],
            'email' => ['required', 'max:255'],
        ];
    }

    /**
     * Get the names the validation messages use for the fields.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'address.*' => 'address',
        ];
    }
}
