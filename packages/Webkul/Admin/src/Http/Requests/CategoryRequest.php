<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Admin\Validations\ProductCategoryUniqueSlug;
use Webkul\Core\Helpers\MediaFileName;
use Webkul\Core\Rules\Slug;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request. The name is a text column, so only the
     * slug, a 255 character column, is held to a length.
     */
    public function rules(): array
    {
        $locale = core()->getRequestedLocaleCode();

        $rules = [
            'position' => 'required|integer',
            'logo_path' => 'array',
            'logo_path.*' => 'mimes:bmp,jpeg,jpg,png,webp',
            'logo_meta.*.alt_text' => ['nullable', 'string', 'max:255'],
            'logo_meta.*.file_name' => ['nullable', 'string', 'max:'.MediaFileName::MAX_LENGTH],
            'banner_path' => 'array',
            'banner_path.*' => 'mimes:bmp,jpeg,jpg,png,webp',
            'banner_meta.*.alt_text' => ['nullable', 'string', 'max:255'],
            'banner_meta.*.file_name' => ['nullable', 'string', 'max:'.MediaFileName::MAX_LENGTH],
            'attributes' => 'required|array',
            'attributes.*' => 'required',
        ];

        if ($id = $this->id) {
            $rules[$locale.'.slug'] = ['required', 'max:255', new Slug, new ProductCategoryUniqueSlug('category_translations', $id)];
            $rules[$locale.'.name'] = ['required'];
            $rules[$locale.'.description'] = 'required_if:display_mode,==,description_only,products_and_description';

            return $rules;
        }

        $rules['slug'] = ['required', 'max:255', new Slug, new ProductCategoryUniqueSlug('category_translations')];
        $rules['name'] = 'required';
        $rules['description'] = 'required_if:display_mode,==,description_only,products_and_description';

        return $rules;
    }

    /**
     * Get the custom attribute names for validator errors, so a translated field is named by its
     * label rather than its locale-prefixed key.
     */
    public function attributes(): array
    {
        $locale = core()->getRequestedLocaleCode();

        return [
            'logo_path.*' => trans('admin::app.catalog.categories.create.logo'),
            'banner_path.*' => trans('admin::app.catalog.categories.create.banner'),
            'name' => trans('admin::app.catalog.categories.create.name'),
            'slug' => trans('admin::app.catalog.categories.create.slug'),
            'description' => trans('admin::app.catalog.categories.create.description'),
            $locale.'.name' => trans('admin::app.catalog.categories.edit.name'),
            $locale.'.slug' => trans('admin::app.catalog.categories.edit.slug'),
            $locale.'.description' => trans('admin::app.catalog.categories.edit.description'),
        ];
    }
}
