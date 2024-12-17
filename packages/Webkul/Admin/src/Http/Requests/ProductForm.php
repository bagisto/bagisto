<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Webkul\Admin\Validations\ProductCategoryUniqueSlug;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\Slug;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductRepository;

class ProductForm extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Max video upload size.
     *
     * @var int
     */
    protected $maxVideoFileSize;

    /**
     * Create a new form request instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository
    ) {
        $this->maxVideoFileSize = core()->getConfigData('catalog.products.attribute.file_attribute_upload_size') ?: '2048';
    }

    /**
     * Determine if the product is authorized to make this request.
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
        $product = $this->productRepository->find($this->id);

        $this->rules = array_merge($product->getTypeInstance()->getTypeValidationRules(), [
            'sku'                  => ['required', 'unique:products,sku,'.$this->id, new Slug],
            'url_key'              => ['required', new ProductCategoryUniqueSlug('products', $this->id)],
            'images.files.*'       => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'images.positions.*'   => ['nullable', 'integer'],
            'videos.files.*'       => ['nullable', 'mimetypes:application/octet-stream,video/mp4,video/webm,video/quicktime', 'max:'.$this->maxVideoFileSize],
            'videos.positions.*'   => ['nullable', 'integer'],
            'special_price_from'   => ['nullable', 'date'],
            'special_price_to'     => ['nullable', 'date', 'after_or_equal:special_price_from'],
            'special_price'        => ['nullable', new Decimal, 'lt:price'],
            'visible_individually' => ['sometimes', 'required', 'in:0,1'],
            'status'               => ['sometimes', 'required', 'in:0,1'],
            'guest_checkout'       => ['sometimes', 'required', 'in:0,1'],
            'new'                  => ['sometimes', 'required', 'in:0,1'],
            'featured'             => ['sometimes', 'required', 'in:0,1'],
        ]);

        if (request()->images) {
            foreach (request()->images['files'] as $key => $file) {
                if (Str::contains($key, 'image_')) {
                    $this->rules = array_merge($this->rules, [
                        'images.files.'.$key => ['required', 'mimes:bmp,jpeg,jpg,png,webp'],
                    ]);
                }
            }
        }

        foreach ($product->getEditableAttributes() as $attribute) {
            if (
                in_array($attribute->code, ['sku', 'url_key'])
                || $attribute->type == 'boolean'
            ) {
                continue;
            }

            $validations = [];

            if (! isset($this->rules[$attribute->code])) {
                $validations[] = $attribute->is_required ? 'required' : 'nullable';
            } else {
                $validations = $this->rules[$attribute->code];
            }

            if (
                $attribute->type == 'text'
                && $attribute->validation
            ) {
                if ($attribute->validation === 'decimal') {
                    $validations[] = new Decimal;
                } elseif ($attribute->validation === 'regex') {
                    $validations[] = 'regex:'.$attribute->regex;
                } else {
                    $validations[] = $attribute->validation;
                }
            }

            if ($attribute->type == 'price') {
                $validations[] = new Decimal;
            }

            if ($attribute->is_unique) {
                array_push($validations, function ($field, $value, $fail) use ($attribute) {
                    if (
                        ! $this->productAttributeValueRepository->isValueUnique(
                            $this->id,
                            $attribute->id,
                            $attribute->column_name,
                            request($attribute->code)
                        )
                    ) {
                        $fail(trans('admin::app.catalog.products.index.already-taken', ['name' => ':attribute']));
                    }
                });
            }

            $this->rules[$attribute->code] = $validations;
        }

        return $this->rules;
    }

    /**
     * Custom message for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'variants.*.sku.unique' => trans('admin::app.catalog.products.index.already-taken', ['name' => ':attribute']),
            'videos.files.*'        => trans('admin::app.catalog.products.edit.videos.error', ['max' => $this->maxVideoFileSize]),
        ];
    }

    /**
     * Attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'images.files.*' => 'image',
            'videos.files.*' => 'video',
            'variants.*.sku' => 'sku',
        ];
    }
}
