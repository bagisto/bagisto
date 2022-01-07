<?php

namespace Webkul\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Contracts\Validations\CommaSeperatedInteger;

class MassUpdateRequest extends FormRequest
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
            'indexes'      => ['required', new CommaSeperatedInteger],
            'update_key'   => ['sometimes', 'required', 'string'],
            'update_value' => ['required'],
        ];
    }

    /**
     * Handle passed validations.
     *
     * @return void
     */
    public function passedValidation()
    {
        $this->replace([
            'indexes'      => explode(',', $this->indexes),
            'update_key'   => $this->update_key,
            'update_value' => $this->update_value,
        ]);
    }
}
