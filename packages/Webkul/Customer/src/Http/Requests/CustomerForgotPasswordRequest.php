<?php

namespace Webkul\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Customer\Facades\Captcha;

class CustomerForgotPasswordRequest extends FormRequest
{
    /**
     * Define your rules.
     *
     * @var array
     */
    private $rules = [
        'email'    => 'required|email',
    ];

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
        return Captcha::getValidations($this->rules);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return Captcha::getValidationMessages();
    }
}
