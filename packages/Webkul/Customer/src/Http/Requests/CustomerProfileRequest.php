<?php

namespace Webkul\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Contracts\Validations\AlphaNumericSpace;

class CustomerProfileRequest extends FormRequest
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
        $id = auth()->guard('customer')->user()->id;

        return [
            'first_name'                => ['required', new AlphaNumericSpace()],
            'last_name'                 => ['required', new AlphaNumericSpace()],
            'gender'                    => 'required|in:Other,Male,Female',
            'date_of_birth'             => 'date|before:today',
            'email'                     => 'email|unique:customers,email,' . $id,
            'new_password'              => 'confirmed|min:6',
            'new_password_confirmation' => 'required_with:new_password',
            'current_password'          => 'required_with:new_password',
            'image.*'                   => 'mimes:bmp,jpeg,jpg,png,webp',
            'phone'                     => 'numeric',
        ];
    }
}
