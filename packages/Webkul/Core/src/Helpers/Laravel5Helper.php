<?php
namespace Webkul\Core\Helpers;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\Laravel5;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductInventory;

class Laravel5Helper extends Laravel5
{
    /**
     * Returns field name of given attribute.
     *
     * @param string $attribute
     *
     * @return string|null
     * @part ORM
     */
    public static function getAttributeFieldName(string $attribute): ?string
    {
        $attributes = [
            'product_id'           => 'integer_value',
            'sku'                  => 'text_value',
            'name'                 => 'text_value',
            'url_key'              => 'text_value',
            'tax_category_id'      => 'integer_value',
            'new'                  => 'boolean_value',
            'featured'             => 'boolean_value',
            'visible_individually' => 'boolean_value',
            'status'               => 'boolean_value',
            'short_description'    => 'text_value',
            'description'          => 'text_value',
            'price'                => 'float_value',
            'cost'                 => 'float_value',
            'special_price'        => 'float_value',
            'special_price_from'   => 'date_value',
            'special_price_to'     => 'date_value',
            'meta_title'           => 'text_value',
            'meta_keywords'        => 'text_value',
            'meta_description'     => 'text_value',
            'width'                => 'integer_value',
            'height'               => 'integer_value',
            'depth'                => 'integer_value',
            'weight'               => 'integer_value',
            'color'                => 'integer_value',
            'size'                 => 'integer_value',
            'brand'                => 'text_value',
            'guest_checkout'       => 'boolean_value',
        ];
        if (!array_key_exists($attribute, $attributes)) {
            return null;
        }
        return $attributes[$attribute];
    }
    /**
     * @param array $attributeValueStates
     *
     * @return \Webkul\Product\Models\Product
     * @part ORM
     */
    public function haveProduct(
        array $configs = [],
        array $productStates = []
    ): Product {
        $I = $this;
        /** @var Product $product */
        $product = factory(Product::class)->states($productStates)->create($configs['productAttributes'] ?? []);
        $I->createAttributeValues($product->id,$configs['attributeValues'] ?? []);
        $I->have(ProductInventory::class, array_merge($configs['productInventory'] ?? [], [
            'product_id'          => $product->id,
            'inventory_source_id' => 1,
        ]));
        Event::dispatch('catalog.product.create.after', $product);
        return $product;
    }
    private function createAttributeValues($id, array $attributeValues = [])
    {
        $I = $this;
        $productAttributeValues = [
            'sku',
            'url_key',
            'tax_category_id',
            'price',
            'cost',
            'name',
            'new',
            'visible_individually',
            'featured',
            'status',
            'guest_checkout',
            'short_description',
            'description',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'weight',
        ];
        foreach ($productAttributeValues as $attribute) {
            $data = ['product_id' => $id];
            if (array_key_exists($attribute, $attributeValues)) {
                $fieldName = self::getAttributeFieldName($attribute);
                if (! array_key_exists($fieldName, $data)) {
                    $data[$fieldName] = $attributeValues[$attribute];
                } else {
                    $data = [$fieldName => $attributeValues[$attribute]];
                }
            }
            $I->have(ProductAttributeValue::class, $data, $attribute);
        }
    }
}