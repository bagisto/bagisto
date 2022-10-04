<?php

namespace Webkul\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Contracts\Validations\CommaSeparatedInteger;

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
            'indexes' => ['required', new CommaSeparatedInteger],
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
            'indexes' => explode(',', $this->indexes),
        ]);
    }
}
