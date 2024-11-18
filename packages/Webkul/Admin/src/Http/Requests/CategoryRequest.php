<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Admin\Validations\ProductCategoryUniqueSlug;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the Configuration is authorized to make this request.
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
        $locale = core()->getRequestedLocaleCode();

        $rules = [
            'position'      => 'required|integer',
            'logo_path'     => 'array',
            'logo_path.*'   => 'mimes:bmp,jpeg,jpg,png,webp',
            'banner_path'   => 'array',
            'banner_path.*' => 'mimes:bmp,jpeg,jpg,png,webp',
            'attributes'    => 'required|array',
            'attributes.*'  => 'required',
        ];

        if ($id = $this->id) {
            $rules[$locale.'.slug'] = ['required', new ProductCategoryUniqueSlug('category_translations', $id)];
            $rules[$locale.'.name'] = ['required'];
            $rules[$locale.'.description'] = 'required_if:display_mode,==,description_only,products_and_description';

            return $rules;
        }

        $rules['slug'] = ['required', new ProductCategoryUniqueSlug];
        $rules['name'] = 'required';
        $rules['description'] = 'required_if:display_mode,==,description_only,products_and_description';

        return $rules;
    }
}
