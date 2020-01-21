<?php

namespace Webkul\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductAttributeValueRepository as AttributeValue;
use Webkul\Product\Models\ProductAttributeValue;

class ProductForm extends FormRequest
{
    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * ProductAttributeValueRepository object
     *
     * @var array
     */
    protected $attributeValue;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository     $attributeFamily
     * @param  Webkul\Product\Repositories\ProductRepository               $product
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $attributeValue
     * @return void
     */
    public function __construct(AttributeFamily $attributeFamily, Product $product, AttributeValue $attributeValue)
    {
        $this->attributeFamily = $attributeFamily;

        $this->product = $product;

        $this->attributeValue = $attributeValue;
    }

    protected $rules;

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
        $product = $this->product->find($this->id);
        
        $this->rules = array_merge($product->getTypeInstance()->getTypeValidationRules(), [
            'sku' => ['required', 'unique:products,sku,' . $this->id, new \Webkul\Core\Contracts\Validations\Slug],
            'images.*' => 'mimes:jpeg,jpg,bmp,png',
            'special_price_from' => 'nullable|date',
            'special_price_to' => 'nullable|date|after_or_equal:special_price_from'
        ]);

        foreach ($product->getEditableAttributes() as $attribute) {
            if ($attribute->code == 'sku' || $attribute->type == 'boolean')
                continue;

            $validations = [];

            if (! isset($this->rules[$attribute->code]))
                array_push($validations, $attribute->is_required ? 'required' : 'nullable');
            else
                $validations = $this->rules[$attribute->code];

            if ($attribute->type == 'text' && $attribute->validation) {
                array_push($validations, 
                        $attribute->validation == 'decimal'
                        ? new \Webkul\Core\Contracts\Validations\Decimal
                        : $attribute->validation
                    );
            }

            if ($attribute->type == 'price')
                array_push($validations, new \Webkul\Core\Contracts\Validations\Decimal);

            if ($attribute->is_unique) {
                array_push($validations, function ($field, $value, $fail) use ($attribute) {
                    $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                    if (! $this->attributeValue->isValueUnique($this->id, $attribute->id, $column, request($attribute->code)))
                        $fail('The :attribute has already been taken.');
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
