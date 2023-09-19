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

        if ($id = request('id')) {
            return [
                $locale . '.slug' => ['required', new ProductCategoryUniqueSlug('category_translations', $id)],
                $locale . '.name' => 'required',
                'image.*'         => 'mimes:bmp,jpeg,jpg,png,webp',
            ];
        }

        return [
            'slug'        => ['required', new ProductCategoryUniqueSlug],
            'name'        => 'required',
            'image.*'     => 'mimes:bmp,jpeg,jpg,png,webp',
            'description' => 'required_if:display_mode,==,description_only,products_and_description',
        ];
    }
}
