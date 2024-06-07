<?php

namespace Webkul\Shop\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\PhoneNumber;
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
            'company_name' => ['nullable'],
            'first_name'   => ['required'],
            'last_name'    => ['required'],
            'address'      => ['required', 'array', 'min:1'],
            'country'      => core()->isCountryRequired() ? ['required'] : ['nullable'],
            'state'        => core()->isStateRequired() ? ['required'] : ['nullable'],
            'city'         => ['required', 'string'],
            'postcode'     => core()->isPostCodeRequired() ? ['required', 'numeric'] : ['numeric'],
            'phone'        => ['required', new PhoneNumber],
            'vat_id'       => [new VatIdRule()],
            'email'        => ['required'],
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
}
