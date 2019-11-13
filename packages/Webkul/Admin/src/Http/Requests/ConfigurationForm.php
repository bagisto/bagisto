<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigurationForm extends FormRequest
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
        $this->rules = [
            'sales.*.*.title'  => 'required',
            'sales.*.*.active'  => 'required',
            'sales.*.*.order_status' => 'required',
            'sales.*.*.file' => 'max:2048',
        ];

        return $this->rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
    */
    public function messages()
    {
        return [
            'sales.*.*.title.required' => 'The title field is required.',
            'sales.*.*.active.required' => 'The status field is required.',
            'sales.*.*.order_status.required' => 'Order Status field is required',
        ];
    }
}
