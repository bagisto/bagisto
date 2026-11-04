<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Helpers\MediaUpload;
use Webkul\Core\Rules\Code;

class LocaleRequest extends FormRequest
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
    public function rules(MediaUpload $mediaUpload)
    {
        $rules = [
            'name' => 'required',
            'direction' => 'required|in:ltr,rtl',
            'logo_path' => 'array',
            'logo_path.*' => $mediaUpload->imageRules(['svg']),
        ];

        if (! $this->isMethod('PUT')) {
            $rules['code'] = ['required', 'unique:locales,code', new Code];
        }

        return $rules;
    }

    /**
     * Get the custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'logo_path.*' => trans('admin::app.settings.locales.index.create.locale-logo'),
        ];
    }
}
