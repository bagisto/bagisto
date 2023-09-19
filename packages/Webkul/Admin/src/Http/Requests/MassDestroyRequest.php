<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MassDestroyRequest extends FormRequest
{
    /**
     * Determine if the request is authorized or not.
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
            'indices'   => ['required', 'array'],
            'indices.*' => ['integer'],
        ];
    }
}
