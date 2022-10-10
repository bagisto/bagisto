<?php

namespace Actions;

use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use StdClass;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductDownloadableLink;
use Webkul\Product\Models\ProductDownloadableLinkTranslation;
use Webkul\Product\Models\ProductInventory;

trait ProductAction
{
    /**
     * Returns the field name of the given attribute in which a value should be saved inside
     * the 'product_attribute_values' table. Depends on the type.
     *
     * @param  string  $type
     * @return string|null
     */
    public static function getAttributeFieldName(string $type): ?string
    {
        $possibleTypes = [
            'text'     => 'text_value',
            'select'   => 'integer_value',
            'boolean'  => 'boolean_value',
            'textarea' => 'text_value',
            'price'    => 'float_value',
            'date'     => 'date_value',
            'checkbox' => 'text_value',
        ];

        return $possibleTypes[$type];
    }

    /**
     * Prepare for cart.
     *
     * @param  array  $options
     * @return array
     */
    public function prepareCart(array $options = []): array
    {
        $I = $this;

        $faker = Factory::create();

        $product = $I->haveProduct(self::SIMPLE_PRODUCT, $options['productOptions'] ?? []);

        if (isset($options['customer'])) {
            $customer = $options['customer'];
        } else {
            $customer = $I->have(Customer::class);
        }

        $I->have(CustomerAddress::class, [
            'default_address' => 1,
            'customer_id'     => $customer->id,
            'first_name'      => $customer->first_name,
            'last_name'       => $customer->last_name,
            'company_name'    => $faker->company,
        ]);

        if (isset($options['payment_method'])
            && $options['payment_method'] === 'free_of_charge') {
            $grand_total = '0.0000';

            $base_grand_total = '0.0000';
        } else {
            $grand_total = (string) $faker->numberBetween(1, 666);

            $base_grand_total = $grand_total;
        }

        $cart = $I->have(Cart::class, [
            'customer_id'         => $customer->id,
            'customer_first_name' => $customer->first_name,
            'customer_last_name'  => $customer->last_name,
            'customer_email'      => $customer->email,
            'is_active'           => $options['is_active'] ?? 1,
            'channel_id'          => $options['channel_id'] ?? 1,
            'grand_total'         => $grand_total,
            'base_grand_total'    => $base_grand_total,
        ]);

        $cartAddress = $I->have(CartAddress::class, ['cart_id' => $cart->id]);

        $type = $options['product_type'] ?? 'simple';

        $totalQtyOrdered = 0;

        $cartItems = [];

        $generatedCartItems = random_int(3, 10);

        for ($i = 2; $i <= $generatedCartItems; $i++) {
            $quantity = random_int(1, 10);

            $cartItem = $I->have(CartItem::class, [
                'type'       => $type,
                'quantity'   => $quantity,
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
            ]);

            $totalQtyOrdered += $quantity;

            $cartItems[] = $cartItem;
        }

        // actually set the cart to the user's session
        // when in an functional test:
        $stub = new StdClass();
        $stub->id = $cart->id;
        $I->setSession(['cart' => $stub]);

        return [
            'cart'            => $cart,
            'product'         => $product,
            'customer'        => $customer,
            'cartAddress'     => $cartAddress,
            'cartItems'       => $cartItems,
            'totalQtyOrdered' => $totalQtyOrdered,
        ];
    }

    /**
     * Helper function to generate products for testing.
     *
     * By default, the product will be generated as saleable, this means it has a price,
     * weight, is active and has a positive inventory stock, if necessary.
     *
     * @param  int  $productType  (See constants in this class for usage.)
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Models\Product
     */
    public function haveProduct(int $productType, array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        switch ($productType) {
            case self::BOOKING_EVENT_PRODUCT:
                $product = $I->haveBookingEventProduct($configs, $productStates);
                break;

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

        return $product;
    }

    /**
     * Generate simple product.
     *
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Models\Product
     */
    public function haveSimpleProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('simple', $productStates)) {
            $productStates = array_merge($productStates, ['simple']);
        }

        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        Event::dispatch('catalog.product.create.after', $product);

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

        $I->createInventory($product->id, $configs['productInventory'] ?? []);

        $product = $product->refresh();

        Event::dispatch('catalog.product.update.after', $product);

        return $product;
    }

    /**
     * Generate virtual product.
     *
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Models\Product
     */
    public function haveVirtualProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('virtual', $productStates)) {
            $productStates = array_merge($productStates, ['virtual']);
        }

        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        Event::dispatch('catalog.product.create.after', $product);

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

        $I->createInventory($product->id, $configs['productInventory'] ?? []);

        Event::dispatch('catalog.product.update.after', $product);

        return $product->refresh();
    }

    /**
     * Generate downloadable product.
     *
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Models\Product
     */
    public function haveDownloadableProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('downloadable', $productStates)) {
            $productStates = array_merge($productStates, ['downloadable']);
        }

        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        Event::dispatch('catalog.product.create.after', $product);

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

        $I->createDownloadableLink($product->id);

        Event::dispatch('catalog.product.update.after', $product);

        return $product->refresh();
    }

    /**
     * Generate booking product.
     *
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Models\Product
     */
    public function haveBookingEventProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('booking', $productStates)) {
            $productStates = array_merge($productStates, ['booking']);
        }

        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        Event::dispatch('catalog.product.create.after', $product);

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

        $I->createBookingEventProduct($product->id);

        Event::dispatch('catalog.product.update.after', $product);

        return $product->refresh();
    }

    /**
     * Create product.
     *
     * @param  array  $attributes
     * @param  array  $states
     * @return \Webkul\Product\Models\Product
     */
    protected function createProduct(array $attributes = [], array $states = []): Product
    {
        return Product::factory()
            ->state(function () use ($states) {
                return [
                    'type' => $states[0],
                ];
            })
            ->create($attributes);
    }

    /**
     * Create inventory.
     *
     * @param  int  $productId
     * @param  array  $inventoryConfig
     * @return void
     */
    protected function createInventory(int $productId, array $inventoryConfig = []): void
    {
        $I = $this;

        $I->have(ProductInventory::class, array_merge($inventoryConfig, [
            'product_id'          => $productId,
            'inventory_source_id' => 1,
        ]));
    }

    /**
     * Create downloadable link.
     *
     * @param  int  $productId
     * @return void
     */
    protected function createDownloadableLink(int $productId): void
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
     * Create booking event.
     *
     * @param  int  $productId
     * @return void
     */
    protected function createBookingEventProduct(int $productId): void
    {
        $I = $this;

        $bookingProduct = $I->have(BookingProduct::class, [
            'product_id' => $productId,
        ]);

        $I->have(BookingProductEventTicket::class, [
            'booking_product_id' => $bookingProduct->id,
        ]);
    }

    /**
     * Create attribute values.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @param  array  $attributeValues
     * @return void
     */
    protected function createAttributeValues(Product $product, array $attributeValues = []): void
    {
        $I = $this;

        $faker = Factory::create();

        $brand = Attribute::query()
            ->where(['code' => 'brand'])
            ->firstOrFail(); // usually 25

        if (! AttributeOption::query()
            ->where(['attribute_id' => $brand->id])
            ->exists()) {
            AttributeOption::create([
                'admin_name'   => 'Webkul Demo Brand (c) 2020',
                'attribute_id' => $brand->id,
            ]);
        }

        /**
         * Some defaults that should apply to all generated products.
         * By defaults products will be generated as saleable.
         * If you do not want this, this defaults can be overriden by $attributeValues.
         */
        $defaultAttributeValues = [
            'name'                 => $faker->words(3, true),
            'description'          => $faker->sentence,
            'short_description'    => $faker->sentence,
            'sku'                  => $product->sku,
            'url_key'              => $faker->slug,
            'status'               => true,
            'guest_checkout'       => true,
            'visible_individually' => true,
            'special_price_from'   => null,
            'special_price_to'     => null,
            'special_price'        => null,
            'price'                => $faker->randomFloat(2, 1, 1000),
            'weight'               => '1.00',
            'brand'                => AttributeOption::query()->firstWhere('attribute_id', $brand->id)->id,
        ];

        $attributeValues = array_merge($defaultAttributeValues, $attributeValues);

        /**
         * List of the possible attributes a product can have.
         *
         * @var array
         */
        $possibleAttributeValues = DB::table('attributes')
            ->select('id', 'code', 'type')
            ->get()
            ->toArray();

        foreach ($possibleAttributeValues as $attributeSet) {
            $data = [
                'product_id'   => $product->id,
                'attribute_id' => $attributeSet->id,
            ];

            $fieldName = self::getAttributeFieldName($attributeSet->type);

            $data[$fieldName] = $attributeValues[$attributeSet->code] ?? null;

            $data = $this->appendAttributeDependencies($attributeSet->code, $data);

            $I->have(ProductAttributeValue::class, $data);
        }
    }

    /**
     * Append attribute dependencies.
     *
     * @param  string  $attributeCode
     * @param  array  $data
     * @return array
     */
    protected function appendAttributeDependencies(string $attributeCode, array $data): array
    {
        $locale = core()->getCurrentLocale()->code;

        $channel = core()->getCurrentChannelCode();

        $attributeSetDependencies = [
            'name'               => [
                'locale',
                'channel',
            ],
            'tax_category_id'    => [
                'channel',
            ],
            'short_description'  => [
                'locale',
                'channel',
            ],
            'description'        => [
                'locale',
                'channel',
            ],
            'cost'               => [
                'channel',
            ],
            'special_price_from' => [
                'channel',
            ],
            'special_price_to'   => [
                'channel',
            ],
            'meta_title'         => [
                'locale',
                'channel',
            ],
            'meta_keywords'      => [
                'locale',
                'channel',
            ],
            'meta_description'   => [
                'locale',
                'channel',
            ],
            'custom_sale_badge'  => [
                'locale',
            ],
        ];

        if (array_key_exists($attributeCode, $attributeSetDependencies)) {
            foreach ($attributeSetDependencies[$attributeCode] as $key) {
                if ($key === 'locale') {
                    $data['locale'] = $locale;
                }

                if ($key === 'channel') {
                    $data['channel'] = $channel;
                }
            }
        }

        return $data;
    }
}
