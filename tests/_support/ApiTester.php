<?php

use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductInventory;

/**
 * Inherited methods.
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Sanctum authenticated customer.
     *
     * @return \Webkul\Customer\Models\Customer
     */
    public function amSanctumAuthenticatedCustomer()
    {
        return Sanctum::actingAs(
            Customer::factory()->create(),
            ['*']
        );
    }

    /**
     * Create token for sanctum authenticated customer.
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return string
     */
    public function amCreatingTokenForSanctumAuthenticatedCustomer(Customer $customer)
    {
        return $this->grabTokenFromSanctumGeneratedString(
            $customer->createToken($this->fake()->company)->plainTextToken
        );
    }

    /**
     * Set all necessary headers, if token is passed then bearable authentication header
     * will pass.
     *
     * @param  optional|string  $token
     * @return void
     */
    public function haveAllNecessaryHeaders($token = null)
    {
        $this->haveHttpHeader('Accept', 'application/json');

        $this->haveHttpHeader('Content-Type', 'application/json');

        if ($token) {
            $this->amBearerAuthenticated($token);
        }
    }

    /**
     * Check all necessary success response.
     *
     * @return void
     */
    public function seeAllNecessarySuccessResponse()
    {
        $this->seeResponseCodeIsSuccessful();

        $this->seeResponseIsJson();
    }

    /**
     * Get JSON decoded response.
     *
     * @return mixed
     */
    public function grabJsonDecodedResponse()
    {
        return json_decode($this->grabResponse());
    }

    /**
     * Get token from response.
     *
     * @return string
     */
    public function grabTokenFromResponse()
    {
        $idAndToken = $this->grabDataFromResponseByJsonPath('token')[0];

        return $this->grabTokenFromSanctumGeneratedString($idAndToken);
    }

    /**
     * Get token from sanctum generated string.
     *
     * @return string
     */
    public function grabTokenFromSanctumGeneratedString($idAndToken)
    {
        $idAndToken = explode('|', $idAndToken);

        return $idAndToken[1];
    }

    /**
     * Clean all fields.
     *
     * @param  array  $fields
     * @return array
     */
    public function cleanAllFields(array $fields)
    {
        return collect($fields)->map(function ($field, $key) {
            return $this->cleanField($field);
        })->toArray();
    }

    /**
     * Clean field.
     *
     * @param  string  $field
     * @return string
     */
    public function cleanField($field)
    {
        return preg_replace('/[^A-Za-z0-9 ]/', '', $field);
    }

    /**
     * Generate cart.
     *
     * @param  array  $attributes
     * @return \Webkul\Checkout\Contracts\Cart
     */
    public function haveCart($attributes = [])
    {
        return Cart::factory($attributes)->adjustCustomer()->create();
    }

    /**
     * Generate cart items.
     *
     * @param  array  $attributes
     * @return \Webkul\Checkout\Contracts\CartItem
     */
    public function haveCartItems($attributes = [])
    {
        return CartItem::factory($attributes)->adjustProduct()->create();
    }

    /**
     * Generate simple product.
     *
     * @param  array  $configs
     * @param  array  $productStates
     * @return \Webkul\Product\Contracts\Product
     */
    public function haveSimpleProduct(array $configs = [], array $productStates = []): Product
    {
        $I = $this;

        if (! in_array('simple', $productStates)) {
            $productStates = array_merge($productStates, ['simple']);
        }

        $product = $I->createProduct($configs['productAttributes'] ?? [], $productStates);

        $I->createAttributeValues($product, $configs['attributeValues'] ?? []);

        $I->createInventory($product->id, $configs['productInventory'] ?? []);

        return $product->refresh();
    }

    /**
     * Create product.
     *
     * @param  array  $attributes
     * @param  array  $states
     * @return \Webkul\Product\Contracts\Product
     */
    public function createProduct(array $attributes = [], array $states = []): Product
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
     * Create product inventory.
     *
     * @param  int  $productId
     * @param  array  $inventoryConfig
     * @return void
     */
    public function createInventory(int $productId, array $inventoryConfig = []): void
    {
        $I = $this;

        $I->have(ProductInventory::class, array_merge($inventoryConfig, [
            'product_id'          => $productId,
            'inventory_source_id' => 1,
        ]));
    }

    /**
     * Create attribute values.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array  $attributeValues
     * @return void
     */
    public function createAttributeValues(Product $product, array $attributeValues = []): void
    {
        $I = $this;

        $brand = Attribute::query()->where(['code' => 'brand'])->firstOrFail();

        if (! AttributeOption::query()->where(['attribute_id' => $brand->id])->exists()) {
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
            'name'                 => $I->fake()->words(3, true),
            'description'          => $I->fake()->sentence,
            'short_description'    => $I->fake()->sentence,
            'sku'                  => $product->sku,
            'url_key'              => $I->fake()->slug,
            'status'               => true,
            'guest_checkout'       => true,
            'visible_individually' => true,
            'special_price_from'   => null,
            'special_price_to'     => null,
            'special_price'        => null,
            'price'                => $I->fake()->randomFloat(2, 1, 1000),
            'weight'               => '1.00',
            'brand'                => AttributeOption::query()->firstWhere('attribute_id', $brand->id)->id,
        ];

        $attributeValues = array_merge($defaultAttributeValues, $attributeValues);

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
     * Get attribute field names.
     *
     * @param  string  $type
     * @return string|void
     */
    private static function getAttributeFieldName(string $type): ?string
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
     * Append attribute dependencies.
     *
     * @param  string  $attributeCode
     * @param  array  $data
     * @return array
     */
    private function appendAttributeDependencies(string $attributeCode, array $data): array
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
