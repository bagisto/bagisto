<?php

namespace Webkul\Core\Helpers;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\Laravel5;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductInventory;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductDownloadableLink;
use Webkul\Product\Models\ProductDownloadableLinkTranslation;

class Laravel5Helper extends Laravel5
{
    public const SIMPLE_PRODUCT = 1;
    public const VIRTUAL_PRODUCT = 2;
    public const DOWNLOADABLE_PRODUCT = 3;

    /**
     * Returns field name of given attribute.
     *
     * @param  string  $attribute
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
        
        if (! array_key_exists($attribute, $attributes)) {
            return null;
        }

        return $attributes[$attribute];
    }

    /**
     * Helper function to generate products for testing
     *
     * @param  int  $productType
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Models\Product
     * @part ORM
     */
    public function haveProduct(int $productType, array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        switch ($productType) {
            case self::DOWNLOADABLE_PRODUCT:
                $product = $I->haveDownloadableProduct($configs, $productStates);

                break;

            case self::VIRTUAL_PRODUCT:
                $product = $I->haveVirtualProduct($configs, $productStates);
                
                break;

            case self::SIMPLE_PRODUCT:
            default:
                $product = $I->haveSimpleProduct($configs, $productStates);
        }

        if ($product !== null) {
            Event::dispatch('catalog.product.create.after', $product);
        }

        return $product;
    }

    /**
     * @param  array  $configs
     * @param  array  $productStates
     * @return  \Webkul\Product\Contracts\Product
     */
    private function haveSimpleProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('simple', $productStates)) {
            $productStates = array_merge($productStates, ['simple']);
        }

        /** @var Product $product */
        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        $I->createAttributeValues($product->id, $configs['attributeValues'] ?? []);

        $I->createInventory($product->id, $configs['productInventory'] ?? []);

        return $product->refresh();
    }

    /**
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Contracts\Product
     */
    private function haveVirtualProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('virtual', $productStates)) {
            $productStates = array_merge($productStates, ['virtual']);
        }

        /** @var Product $product */
        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        $I->createAttributeValues($product->id, $configs['attributeValues'] ?? []);

        $I->createInventory($product->id, $configs['productInventory'] ?? []);

        return $product->refresh();
    }

    /**
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Contracts\Product
     */
    private function haveDownloadableProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('downloadable', $productStates)) {
            $productStates = array_merge($productStates, ['downloadable']);
        }

        /** @var Product $product */
        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        $I->createAttributeValues($product->id, $configs['attributeValues'] ?? []);

        $I->createDownloadableLink($product->id);

        return $product->refresh();
    }

    /**
     * @param array $attributes
     * @param array $states
     *
     * @return \Webkul\Product\Models\Product
     */
    private function createProduct(array $attributes = [], array $states = []): Product
    {
        return factory(Product::class)->states($states)->create($attributes);
    }

    /**
     * @param  int    $productId
     * @param  array  $inventoryConfig
     * @return void
     */
    private function createInventory(int $productId, array $inventoryConfig = []): void
    {
        $I = $this;

        $I->have(ProductInventory::class, array_merge($inventoryConfig, [
            'product_id'          => $productId,
            'inventory_source_id' => 1,
        ]));
    }

    /**
     * @param  int  $productId
     * @return void
     */
    private function createDownloadableLink(int $productId): void
    {
        $I = $this;

        $link = $I->have(ProductDownloadableLink::class, [
            'product_id' => $productId,
        ]);

        $I->have(ProductDownloadableLinkTranslation::class, [
            'product_downloadable_link_id' => $link->id,
        ]);
    }

    /**
     * @param  int  $productId
     * @param  array  $attributeValues
     * @return void
     */
    private function createAttributeValues(int $productId, array $attributeValues = []): void
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
            $data = ['product_id' => $productId];

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