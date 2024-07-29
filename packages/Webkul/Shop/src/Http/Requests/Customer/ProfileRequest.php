<?php

namespace Webkul\Shop\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\PhoneNumber;

class ProfileRequest extends FormRequest
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
            'first_name'                => ['required'],
            'last_name'                 => ['required'],
            'gender'                    => 'required|in:Other,Male,Female',
            'date_of_birth'             => 'date|before:today',
            'email'                     => 'email|unique:customers,email,'.$id,
            'new_password'              => 'confirmed|min:6|required_with:current_password',
            'new_password_confirmation' => 'required_with:new_password',
            'current_password'          => 'required_with:new_password',
            'image'                     => 'array',
            'image.*'                   => 'mimes:bmp,jpeg,jpg,png,webp',
            'phone'                     => ['required', new PhoneNumber, 'unique:customers,phone,'.$id],
            'subscribed_to_news_letter' => 'nullable',
        ];
    }
}
