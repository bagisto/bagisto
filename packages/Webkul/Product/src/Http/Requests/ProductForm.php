<?php

namespace Webkul\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Models\ProductAttributeValue;

class ProductForm extends FormRequest
{
    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * ProductAttributeValueRepository object
     *
     * @var \Webkul\Product\Repositories\ProductAttributeValueRepository
     */
    protected $productAttributeValueRepository;

    /**
     * @var array
     */
    protected $rules;

    /**
     * Create a new form request instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository $productAttributeValueRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        $this->productRepository = $productRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;
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
            'sku'                => ['required', 'unique:products,sku,' . $this->id, new \Webkul\Core\Contracts\Validations\Slug],
            'images.*'           => 'mimes:jpeg,jpg,bmp,png',
            'special_price_from' => 'nullable|date',
            'special_price_to'   => 'nullable|date|after_or_equal:special_price_from',
            'special_price'      => ['nullable', new \Webkul\Core\Contracts\Validations\Decimal, 'lt:price'],
        ]);

        foreach ($product->getEditableAttributes() as $attribute) {
            if ($attribute->code == 'sku' || $attribute->type == 'boolean') {
                continue;
            }

            $validations = [];

            if (! isset($this->rules[$attribute->code])) {
                array_push($validations, $attribute->is_required ? 'required' : 'nullable');
            } else {
                $validations = $this->rules[$attribute->code];
            }

            if ($attribute->type == 'text' && $attribute->validation) {
                array_push($validations, 
                    $attribute->validation == 'decimal'
                    ? new \Webkul\Core\Contracts\Validations\Decimal
                    : $attribute->validation
                );
            }

            if ($attribute->type == 'price') {
                array_push($validations, new \Webkul\Core\Contracts\Validations\Decimal);
            }

            if ($attribute->is_unique) {
                array_push($validations, function ($field, $value, $fail) use ($attribute) {
                    $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                    if (! $this->productAttributeValueRepository->isValueUnique($this->id, $attribute->id, $column, request($attribute->code))) {
                        $fail('The :attribute has already been taken.');
                    }
                });
            }

            $this->rules[$attribute->code] = $validations;
        }

        return $this->rules;
    }

    /**
     * Custom message for validation
     *
     * @return array
    */
    public function messages()
    {
        return [
            'variants.*.sku.unique' => 'The sku has already been taken.',
        ];
    }
}
