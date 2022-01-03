<?php

namespace Webkul\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Contracts\Validations\Address;
use Webkul\Core\Contracts\Validations\AlphaNumericSpace;
use Webkul\Core\Contracts\Validations\Code;
use Webkul\Core\Contracts\Validations\PhoneNumber;

class InventorySourceRequest extends FormRequest
{
    /**
     * Determine if the Configuraion is authorized to make this request.
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
        $id = request('id');

        $uniqueCode = 'unique:inventory_sources,code,' . $id ?? 'unique:inventory_sources,code';

        return [
            'code'           => ['required', $uniqueCode, new Code],
            'name'           => ['required'],
            'latitude'       => ['numeric', 'between:-90,90'],
            'longitude'      => ['numeric', 'between:-180,180'],
            'priority'       => ['numeric'],
            'contact_name'   => ['required', new AlphaNumericSpace],
            'contact_email'  => ['required', 'email'],
            'contact_number' => ['required', new PhoneNumber],
            'street'         => ['required', new Address],
            'country'        => ['required', new AlphaNumericSpace],
            'state'          => ['required', new AlphaNumericSpace],
            'city'           => ['required', new AlphaNumericSpace],
            'postcode'       => ['required'],
        ];
    }
}
