<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Admin\Validations\ProductCategoryUniqueSlug;
use Webkul\Core\Helpers\MediaFileName;
use Webkul\Core\Helpers\MediaUpload;
use Webkul\Core\Rules\Slug;

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
    public function rules(MediaUpload $mediaUpload)
    {
        $locale = core()->getRequestedLocaleCode();

        $rules = [
            'position' => 'required|integer',
            'logo_path' => 'array',
            'logo_path.*' => $mediaUpload->imageRules(),
            'logo_meta.*.alt_text' => ['nullable', 'string', 'max:255'],
            'logo_meta.*.file_name' => ['nullable', 'string', 'max:'.MediaFileName::MAX_LENGTH],
            'banner_path' => 'array',
            'banner_path.*' => $mediaUpload->imageRules(),
            'banner_meta.*.alt_text' => ['nullable', 'string', 'max:255'],
            'banner_meta.*.file_name' => ['nullable', 'string', 'max:'.MediaFileName::MAX_LENGTH],
            'attributes' => 'required|array',
            'attributes.*' => 'required',
        ];

        if ($id = $this->id) {
            $rules[$locale.'.slug'] = ['required', new Slug, new ProductCategoryUniqueSlug('category_translations', $id)];
            $rules[$locale.'.name'] = ['required'];
            $rules[$locale.'.description'] = 'required_if:display_mode,==,description_only,products_and_description';

            return $rules;
        }

        $rules['slug'] = ['required', new ProductCategoryUniqueSlug('category_translations')];
        $rules['name'] = 'required';
        $rules['description'] = 'required_if:display_mode,==,description_only,products_and_description';

        return $rules;
    }
}
