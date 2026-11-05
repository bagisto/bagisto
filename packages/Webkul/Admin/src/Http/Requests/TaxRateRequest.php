<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxRateRequest extends FormRequest
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
        $rules = [
            'is_zip' => 'sometimes',
            'zip_code' => 'nullable|max:255',
            'zip_from' => 'nullable|required_with:is_zip|max:255',
            'zip_to' => 'nullable|required_with:is_zip,zip_from|max:255',
            'country' => 'required|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ];

        if ($this->id) {
            $rules['identifier'] = ['required', 'string', 'max:255', Rule::unique('tax_rates', 'identifier')->ignore($this->id)];
        } else {
            $rules['identifier'] = 'required|string|max:255|unique:tax_rates,identifier';
        }

        return $rules;
    }
}
