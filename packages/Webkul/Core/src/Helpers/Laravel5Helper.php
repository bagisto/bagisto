<?php

namespace Webkul\Core\Helpers;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Faker\Factory;
use Illuminate\Support\Str;
use Codeception\Module\Laravel5;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Models\Product;
use Webkul\Attribute\Models\Attribute;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Product\Models\ProductInventory;
use Webkul\Core\Contracts\Validations\Slug;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductDownloadableLink;
use Webkul\Product\Models\ProductDownloadableLinkTranslation;

class Laravel5Helper extends Laravel5
{
    public const SIMPLE_PRODUCT = 1;
    public const VIRTUAL_PRODUCT = 2;
    public const DOWNLOADABLE_PRODUCT = 3;

    /**
     * Returns the field name of the given attribute in which a value should be saved inside
     * the 'product_attribute_values' table. Depends on the type.
     *
     * @param string $attribute
     *
     * @return string|null
     * @part ORM
     */
    public static function getAttributeFieldName(string $attribute): ?string
    {

        $attributes = [];

        // @todo implement json_value ?
        $possibleTypes = [
            'text'     => 'text_value',
            'select'   => 'integer_value',
            'boolean'  => 'boolean_value',
            'textarea' => 'text_value',
            'price'    => 'float_value',
            'date'     => 'date_value',
        ];

        foreach (Attribute::all() as $item) {
            $attributes[$item->code] = $possibleTypes[$item->type];
        }

        if (! array_key_exists($attribute, $attributes)) {
            return null;
        }

        return $attributes[$attribute];
    }

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

        if (isset($options['payment_method'])
            && $options['payment_method'] === 'free_of_charge') {
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
            $type = 'simple';
        }

        $totalQtyOrdered = 0;

        $cartItems = [];

        $generatedCartItems = rand(3, 10);

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
        $stub = new \StdClass();
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
     * Helper function to generate products for testing.
     *
     * By default, the product will be generated as saleable, this means it has a price,
     * weight, is active and has a positive inventory stock, if necessary.
     *
     * @param int   $productType see constants in this class for usage
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

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

        $I->createInventory($product->id, $configs['productInventory'] ?? ['qty' => 10]);

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

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

        $I->createInventory($product->id, $configs['productInventory'] ?? ['qty' => 10]);

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

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

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
     * @param Product $product
     * @param array   $attributeValues
     */
    private function createAttributeValues(Product $product, array $attributeValues = []): void
    {
        $I = $this;

        $faker = Factory::create();

        $brand = Attribute::where(['code' => 'brand'])->first(); // usually 25

        if (! AttributeOption::where(['attribute_id' => $brand->id])->exists()) {
            AttributeOption::create([
                'admin_name'   => 'Webkul Demo Brand (c) 2020',
                'attribute_id' => $brand->id,
            ]);

        }

        /** @var array $defaultAttributeValues
         * Some defaults that should apply to all generated products.
         * By defaults products will be generated as saleable.
         * If you do not want this, this defaults can be overriden by $attributeValues.
         */
        $defaultAttributeValues = [
            'name'                 => $faker->word,
            'description'          => $faker->sentence,
            'short_description'    => $faker->sentence,
            'sku'                  => $faker->word,
            'url_key'              => $faker->slug,
            'status'               => true,
            'visible_individually' => true,
            'special_price_from'   => null,
            'special_price_to'     => null,
            'special_price'        => null,
            'price'                => '1.00',
            'weight'               => '1.00', // necessary for shipping
            'brand'                => AttributeOption::firstWhere('attribute_id', $brand->id)->id,
        ];

        $attributeValues = array_merge($defaultAttributeValues, $attributeValues);

        /** @var array $possibleAttributeValues list of the possible attributes a product can have */
        $possibleAttributeValues = Attribute::all()->pluck('code')->toArray();

        // set a value for _every_ possible attribute, even if empty:
        foreach ($attributeValues as $attribute => $value) {
            if (! in_array($attribute, $possibleAttributeValues)) {
                throw new \Exception(
                    "The given attribute '{$attribute}' does not exist in the 'attributes' table (column: code)");
            }

            $attributeId = Attribute::query()
                ->where('code', $attribute)
                ->select('id')
                ->firstOrFail()
                ->id;

            $data = [
                'product_id'   => $product->id,
                'attribute_id' => $attributeId,
            ];

            $fieldName = self::getAttributeFieldName($attribute);

            $data[$fieldName] = $value;

            $I->have(ProductAttributeValue::class, $data);
        }
    }
}