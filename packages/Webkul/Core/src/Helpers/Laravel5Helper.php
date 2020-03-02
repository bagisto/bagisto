<?php

namespace Webkul\Core\Helpers;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\Laravel5;
use Webkul\Checkout\Models\Cart;
use Webkul\Customer\Models\Customer;
use Webkul\Checkout\Models\CartItem;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Models\Product;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Product\Models\ProductInventory;
use Webkul\Customer\Models\CustomerAddress;
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
        if (! array_key_exists($attribute, $attributes)) {
            return null;
        }
        return $attributes[$attribute];
    }

    /**
     * Generate a cart for the customer. Usually this is necessary to prepare the database
     * before testing the checkout.
     *
     * @param array $options pass some options to configure some of the properties of the cart
     *
     * @return array the generated mocks as array
     *
     * @throws \Exception
     */
    public function prepareCart(array $options = []): array
    {
        $faker = \Faker\Factory::create();

        $I = $this;

        $product = $I->haveProduct(self::SIMPLE_PRODUCT, $options['productOptions'] ?? []);

        if (isset($options['customer'])) {
            $customer = $options['customer'];
        } else {
            $customer = $I->have(Customer::class);
        }

        $I->have(CustomerAddress::class, [
            'customer_id'     => $customer->id,
            'default_address' => 1,
            'first_name'      => $customer->first_name,
            'last_name'       => $customer->last_name,
            'company_name'    => $faker->company,
        ]);

        if (isset($options['payment_method']) && $options['payment_method'] === 'free_of_charge') {
            $grand_total = '0.0000';
            $base_grand_total = '0.0000';
        } else {
            $grand_total = (string)$faker->numberBetween(1, 666);
            $base_grand_total = $grand_total;
        }

        $cart = $I->have(Cart::class, [
            'customer_id'         => $customer->id,
            'customer_first_name' => $customer->first_name,
            'customer_last_name'  => $customer->last_name,
            'customer_email'      => $customer->email,
            'is_active'           => 1,
            'channel_id'          => 1,
            'grand_total'         => $grand_total,
            'base_grand_total'    => $base_grand_total,
        ]);

        $cartAddress = $I->have(CartAddress::class, ['cart_id' => $cart->id]);


        if (isset($options['product_type'])) {
            $type = $options['product_type'];
        } else {
            $type = 'virtual';
        }

        $generatedCartItems = rand(3, 10);
        for ($i = 2; $i <= $generatedCartItems; $i++) {
            $cartItem = $I->have(CartItem::class, [
                'type'       => $type,
                'quantity'   => random_int(1, 10),
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
            ]);
        }

        // actually set the cart to the user's session
        // when in an functional test:
        $stub = new \StdClass();
        $stub->id = $cart->id;
        $I->setSession(['cart' => $stub]);

        return [
            'cart'        => $cart,
            'product'     => $product,
            'customer'    => $customer,
            'cartAddress' => $cartAddress,
            'cartItem'    => $cartItem,
        ];

    }


    /**
     * Helper function to generate products for testing
     *
     * @param int   $productType
     * @param array $configs
     * @param array $productStates
     *
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
     * Set all session with the given key and value in the array.
     *
     * @param array $keyValue
     */
    public function setSession(array $keyValue)
    {
        session($keyValue);
    }

    /**
     * Flush the session data and regenerate the ID
     * A logged in user will be logged off.
     *
     */
    public function invalidateSession()
    {
        session()->invalidate();
    }


    /**
     * @param array $configs
     * @param array $productStates
     *
     * @return \Webkul\Product\Models\Product
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
     * @param array $configs
     * @param array $productStates
     *
     * @return \Webkul\Product\Models\Product
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
     * @param array $configs
     * @param array $productStates
     *
     * @return \Webkul\Product\Models\Product
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
     * @param int   $productId
     * @param array $inventoryConfig
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
     * @param int $productId
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
     * @param int   $productId
     * @param array $attributeValues
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