<?php

namespace Webkul\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Customer\Facades\Captcha;

class ContactRequest extends FormRequest
{
    /**
     * Define your rules.
     *
     * @var array
     */
    private $rules = [
        'name'    => 'string|required',
        'email'   => 'string|required',
        'contact' => 'integer',
        'message' => 'required',
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
