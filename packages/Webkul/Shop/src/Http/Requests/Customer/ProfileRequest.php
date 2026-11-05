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
        $customer = auth()->guard('customer')->user();

        $id = $customer->id;

        return [
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'gender' => 'required|in:Other,Male,Female',
            'date_of_birth' => 'date|before:today',
            'email' => 'max:255|email|unique:customers,email,'.$id.',id,channel_id,'.$customer->channel_id,
            'new_password' => 'confirmed|min:6|required_with:current_password',
            'new_password_confirmation' => 'required_with:new_password',
            'current_password' => 'required_with:new_password',
            'image' => 'array',
            'image.*' => 'mimes:bmp,jpeg,jpg,png,webp',
            'phone' => ['required', 'max:255', new PhoneNumber, 'unique:customers,phone,'.$id],
            'subscribed_to_news_letter' => 'nullable',
        ];
    }

    /**
     * Get the custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'image.*' => trans('shop::app.components.layouts.account.profile-image'),
        ];
    }
}
