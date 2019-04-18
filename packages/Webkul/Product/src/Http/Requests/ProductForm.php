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
        $this->rules = [
            'sku' => ['required', 'unique:products,sku,' . $this->id, new \Webkul\Core\Contracts\Validations\Slug],
            'variants.*.name' => 'required',
            'variants.*.sku' => 'required',
            'variants.*.price' => 'required',
            'variants.*.weight' => 'required',
        ];

        $inputs = $this->all();

        $product = $this->product->find($this->id);

        $attributes = $product->attribute_family->custom_attributes;

        $productSuperAttributes = $product->super_attributes;

        foreach ($attributes as $attribute) {
            if (! $productSuperAttributes->contains($attribute)) {
                if ($attribute->code == 'sku') {
                    continue;
                }

                if ($product->type == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'])) {
                    continue;
                }

                $validations = [];

                if ($attribute->is_required) {
                    array_push($validations, 'required');
                } else {
                    array_push($validations, 'nullable');
                }

                if ($attribute->type == 'text' && $attribute->validation) {
                    array_push($validations, $attribute->validation);
                }

                if ($attribute->type == 'price') {
                    array_push($validations, 'decimal');
                }

                if ($attribute->is_unique) {
                    array_push($validations, function ($field, $value, $fail) use ($inputs, $attribute) {
                        $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                        if (! $this->attributeValue->isValueUnique($this->id, $attribute->id, $column, $inputs[$attribute->code])) {
                            $fail('The :attribute has already been taken.');
                        }
                    });
                }

                $this->rules[$attribute->code] = $validations;
            }
        }

        return $this->rules;
    }
}
