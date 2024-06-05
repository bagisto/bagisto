<?php

namespace Webkul\admin\Http\Requests;

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
            'company_name'    => ['string'],
            'first_name'      => ['required', 'string'],
            'last_name'       => ['required', 'string'],
            'address'         => ['required', 'array', 'min:1'],
            'country'         => ['required', 'string'],
            'state'           => ['required', 'string'],
            'city'            => ['required', 'string'],
            'postcode'        => core()->isPostCodeRequired() ? ['required', 'numeric'] : ['numeric'],
            'phone'           => ['required', new PhoneNumber],
            'vat_id'          => [new VatIdRule()],
            'email'           => ['required'],
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
}
