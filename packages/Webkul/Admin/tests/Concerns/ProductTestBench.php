<?php

namespace Webkul\Admin\Tests\Concerns;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductInventory;

trait ProductTestBench
{
    /**
     * All attribute codes in the default attribute family.
     */
    protected static array $productAttributeCodes = [
        'sku',
        'name',
        'url_key',
        'tax_category_id',
        'new',
        'featured',
        'visible_individually',
        'status',
        'short_description',
        'description',
        'price',
        'cost',
        'special_price',
        'special_price_from',
        'special_price_to',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'length',
        'width',
        'height',
        'weight',
        'color',
        'size',
        'brand',
        'guest_checkout',
        'product_number',
        'manage_stock',
        'allow_rma',
        'rma_rule_id',
    ];

    /**
     * Cached attribute map keyed by code.
     */
    protected static ?array $attributeMap = null;

    // ========================================================================
    // Factory-Based Helpers (for setup/validation/delete tests)
    // ========================================================================

    /**
     * Create a fully-indexed simple product via factory with all attribute
     * values, inventory, channel sync, and product_flat record.
     */
    public function createSimpleProduct(array $overrides = []): Product
    {
        return $this->createProduct('simple', $overrides);
    }

    /**
     * Create a fully-indexed virtual product via factory.
     */
    public function createVirtualProduct(array $overrides = []): Product
    {
        return $this->createProduct('virtual', $overrides);
    }

    /**
     * Create a fully-indexed product of any type via factory.
     */
    public function createProduct(string $type = 'simple', array $overrides = []): Product
    {
        $attributes = $this->getAttributeMap();
        $locale = app()->getLocale();
        $channel = core()->getCurrentChannel();

        $product = Product::factory()
            ->state(['type' => $type])
            ->has(
                ProductAttributeValue::factory()
                    ->count(count(static::$productAttributeCodes))
                    ->state(new Sequence(
                        fn (Sequence $sequence) => $this->buildAttributeValue(
                            $sequence,
                            $attributes,
                            $locale,
                            $channel->code,
                            $overrides,
                        ),
                    )),
                'attribute_values'
            )
            ->create();

        // Sync the SKU attribute value to match the product's actual SKU.
        $this->syncSkuAttributeValue($product, $attributes);

        // Sync unique_id for all attribute values.
        $this->syncUniqueIds($product);

        // Assign product to the current channel.
        $product->channels()->sync([$channel->id]);

        // Create inventory for stockable types.
        if (in_array($type, ['simple', 'virtual'])) {
            ProductInventory::factory()->create([
                'product_id' => $product->id,
                'inventory_source_id' => 1,
            ]);
        }

        // Dispatch the event that triggers product_flat indexing.
        Event::dispatch('catalog.product.update.after', $product);

        return $product->fresh();
    }

    // ========================================================================
    // Route-Based Helpers (for real store + update flow verification)
    // ========================================================================

    /**
     * Create a simple product via POST store + PUT update endpoints.
     */
    public function storeAndUpdateSimpleProduct(array $overrides = []): Product
    {
        $this->loginAsAdmin();

        $sku = fake()->uuid();

        // Store the product skeleton via the controller.
        $this->postJson(route('admin.catalog.products.store'), [
            'type' => 'simple',
            'attribute_family_id' => 1,
            'sku' => $sku,
        ])->assertOk();

        $product = Product::where('sku', $sku)->first();

        // Update with all attribute data via the controller.
        $data = array_merge([
            'sku' => $sku,
            'url_key' => fake()->unique()->slug(),
            'name' => 'Test Simple Product',
            'short_description' => 'A short description for testing.',
            'description' => 'A full description paragraph for testing purposes.',
            'price' => 299.99,
            'weight' => 15,
            'product_number' => fake()->numerify('bagisto-#########'),
            'meta_title' => 'Test Meta Title',
            'meta_keywords' => 'test, simple, product',
            'meta_description' => 'Test meta description for SEO.',
            'channel' => core()->getCurrentChannelCode(),
            'locale' => app()->getLocale(),
            'status' => 1,
            'visible_individually' => 1,
            'new' => 1,
            'featured' => 1,
            'guest_checkout' => 1,
            'inventories' => [1 => 100],
        ], $overrides);

        $this->putJson(route('admin.catalog.products.update', $product->id), $data)
            ->assertRedirect(route('admin.catalog.products.index'));

        return Product::find($product->id);
    }

    /**
     * Create a virtual product via POST store + PUT update endpoints.
     *
     * Virtual products skip weight, length, width, height, and depth.
     */
    public function storeAndUpdateVirtualProduct(array $overrides = []): Product
    {
        $this->loginAsAdmin();

        $sku = fake()->uuid();

        $this->postJson(route('admin.catalog.products.store'), [
            'type' => 'virtual',
            'attribute_family_id' => 1,
            'sku' => $sku,
        ])->assertOk();

        $product = Product::where('sku', $sku)->first();

        $data = array_merge([
            'sku' => $sku,
            'url_key' => fake()->unique()->slug(),
            'name' => 'Test Virtual Product',
            'short_description' => 'A short description for the virtual product.',
            'description' => 'A full description paragraph for the virtual product.',
            'price' => 149.99,
            'product_number' => fake()->numerify('bagisto-#########'),
            'meta_title' => 'Virtual Meta Title',
            'meta_keywords' => 'virtual, test, product',
            'meta_description' => 'Virtual meta description for SEO.',
            'channel' => core()->getCurrentChannelCode(),
            'locale' => app()->getLocale(),
            'status' => 1,
            'visible_individually' => 1,
            'new' => 1,
            'featured' => 1,
            'guest_checkout' => 1,
            'inventories' => [1 => 50],
        ], $overrides);

        $this->putJson(route('admin.catalog.products.update', $product->id), $data)
            ->assertRedirect(route('admin.catalog.products.index'));

        return Product::find($product->id);
    }

    /**
     * Create a configurable product with 4 variants via POST store + PUT update.
     *
     * Sends super_attributes (color + size) with 2 options each.
     */
    public function storeAndUpdateConfigurableProduct(): Product
    {
        $this->loginAsAdmin();

        $sku = fake()->uuid();

        // Store with super_attributes to trigger variant generation.
        $this->postJson(route('admin.catalog.products.store'), [
            'type' => 'configurable',
            'attribute_family_id' => 1,
            'sku' => $sku,
            'super_attributes' => [
                'color' => [1, 2],
                'size' => [6, 7],
            ],
        ])->assertOk();

        $product = Product::where('sku', $sku)->first();
        $product->load('variants');

        // Build variant update data using the auto-generated variants.
        $variants = [];

        foreach ($product->variants as $variant) {
            $variants[$variant->id] = [
                'sku' => $variant->sku,
                'name' => fake()->words(3, true),
                'price' => fake()->randomFloat(2, 10, 500),
                'weight' => fake()->numberBetween(1, 50),
                'status' => 1,
                'inventories' => [1 => rand(10, 200)],
            ];
        }

        // Update parent + variants via the controller.
        $this->putJson(route('admin.catalog.products.update', $product->id), [
            'sku' => $sku,
            'url_key' => fake()->unique()->slug(),
            'name' => 'Test Configurable Product',
            'short_description' => 'A short description for the configurable product.',
            'description' => 'A full description for the configurable product.',
            'channel' => core()->getCurrentChannelCode(),
            'locale' => app()->getLocale(),
            'status' => 1,
            'visible_individually' => 1,
            'new' => 1,
            'featured' => 1,
            'guest_checkout' => 1,
            'variants' => $variants,
        ])->assertRedirect(route('admin.catalog.products.index'));

        return Product::find($product->id);
    }

    /**
     * Create a downloadable product via POST store + PUT update.
     *
     * Includes two URL-based downloadable links and one sample.
     */
    public function storeAndUpdateDownloadableProduct(array $overrides = []): Product
    {
        $this->loginAsAdmin();

        $sku = fake()->uuid();

        $this->postJson(route('admin.catalog.products.store'), [
            'type' => 'downloadable',
            'attribute_family_id' => 1,
            'sku' => $sku,
        ])->assertOk();

        $product = Product::where('sku', $sku)->first();

        $data = array_merge([
            'sku' => $sku,
            'url_key' => fake()->unique()->slug(),
            'name' => 'Test Downloadable Product',
            'short_description' => 'A short description for the downloadable product.',
            'description' => 'A full description for the downloadable product.',
            'price' => 49.99,
            'channel' => core()->getCurrentChannelCode(),
            'locale' => app()->getLocale(),
            'status' => 1,
            'visible_individually' => 1,
            'new' => 1,
            'featured' => 1,
            'downloadable_links' => [
                'link_0' => [
                    'en' => ['title' => 'Link One'],
                    'price' => 10,
                    'downloads' => '5',
                    'sort_order' => '0',
                    'type' => 'url',
                    'url' => 'https://example.com/file1.pdf',
                    'sample_type' => 'url',
                    'sample_url' => 'https://example.com/sample1.pdf',
                ],
                'link_1' => [
                    'en' => ['title' => 'Link Two'],
                    'price' => 20,
                    'downloads' => '10',
                    'sort_order' => '1',
                    'type' => 'url',
                    'url' => 'https://example.com/file2.pdf',
                ],
            ],
            'downloadable_samples' => [
                'sample_0' => [
                    'title' => 'Sample One',
                    'sort_order' => '0',
                    'type' => 'url',
                    'url' => 'https://example.com/sample.pdf',
                ],
            ],
        ], $overrides);

        $this->putJson(route('admin.catalog.products.update', $product->id), $data)
            ->assertRedirect(route('admin.catalog.products.index'));

        return Product::find($product->id);
    }

    /**
     * Create a grouped product with two associated simple products via
     * POST store + PUT update.
     */
    public function storeAndUpdateGroupedProduct(): Product
    {
        $this->loginAsAdmin();

        $sku = fake()->uuid();

        $this->postJson(route('admin.catalog.products.store'), [
            'type' => 'grouped',
            'attribute_family_id' => 1,
            'sku' => $sku,
        ])->assertOk();

        $product = Product::where('sku', $sku)->first();

        // Create simple products to associate.
        $simpleA = $this->createSimpleProduct();
        $simpleB = $this->createSimpleProduct();

        $this->putJson(route('admin.catalog.products.update', $product->id), [
            'sku' => $sku,
            'url_key' => fake()->unique()->slug(),
            'name' => 'Test Grouped Product',
            'short_description' => 'A short description for the grouped product.',
            'description' => 'A full description for the grouped product.',
            'channel' => core()->getCurrentChannelCode(),
            'locale' => app()->getLocale(),
            'status' => 1,
            'visible_individually' => 1,
            'new' => 1,
            'featured' => 1,
            'guest_checkout' => 1,
            'links' => [
                'link_0' => [
                    'associated_product_id' => $simpleA->id,
                    'sort_order' => 0,
                    'qty' => 5,
                ],
                'link_1' => [
                    'associated_product_id' => $simpleB->id,
                    'sort_order' => 1,
                    'qty' => 10,
                ],
            ],
        ])->assertRedirect(route('admin.catalog.products.index'));

        return Product::find($product->id);
    }

    /**
     * Create a bundle product with one option containing two simple products
     * via POST store + PUT update.
     */
    public function storeAndUpdateBundleProduct(): Product
    {
        $this->loginAsAdmin();

        $sku = fake()->uuid();
        $locale = app()->getLocale();

        $this->postJson(route('admin.catalog.products.store'), [
            'type' => 'bundle',
            'attribute_family_id' => 1,
            'sku' => $sku,
        ])->assertOk();

        $product = Product::where('sku', $sku)->first();

        // Create simple products for bundle options.
        $simpleA = $this->createSimpleProduct();
        $simpleB = $this->createSimpleProduct();

        $this->putJson(route('admin.catalog.products.update', $product->id), [
            'sku' => $sku,
            'url_key' => fake()->unique()->slug(),
            'name' => 'Test Bundle Product',
            'short_description' => 'A short description for the bundle product.',
            'description' => 'A full description for the bundle product.',
            'channel' => core()->getCurrentChannelCode(),
            'locale' => $locale,
            'status' => 1,
            'visible_individually' => 1,
            'new' => 1,
            'featured' => 1,
            'guest_checkout' => 1,
            'bundle_options' => [
                'option_0' => [
                    $locale => ['label' => 'Select Color'],
                    'type' => 'select',
                    'is_required' => '1',
                    'sort_order' => 0,
                    'products' => [
                        'product_0' => [
                            'product_id' => $simpleA->id,
                            'qty' => 1,
                            'sort_order' => 0,
                            'is_default' => 1,
                        ],
                        'product_1' => [
                            'product_id' => $simpleB->id,
                            'qty' => 1,
                            'sort_order' => 1,
                            'is_default' => 0,
                        ],
                    ],
                ],
            ],
        ])->assertRedirect(route('admin.catalog.products.index'));

        return Product::find($product->id);
    }

    // ========================================================================
    // Internal Helpers
    // ========================================================================

    /**
     * Build a single attribute value row for the Sequence factory state.
     */
    protected function buildAttributeValue(
        Sequence $sequence,
        array $attributes,
        string $locale,
        string $channel,
        array $overrides,
    ): array {
        $index = $sequence->index % count(static::$productAttributeCodes);
        $code = static::$productAttributeCodes[$index];
        $attribute = $attributes[$code] ?? null;

        if (! $attribute) {
            return ['attribute_id' => 1];
        }

        $value = $this->getDefaultAttributeValue($code, $locale, $channel, $overrides);

        return array_merge($value, [
            'attribute_id' => $attribute->id,
        ]);
    }

    /**
     * Get the default value for a product attribute by code.
     */
    protected function getDefaultAttributeValue(string $code, string $locale, string $channel, array $overrides): array
    {
        // Allow overrides for specific attribute values.
        if (isset($overrides[$code])) {
            return is_array($overrides[$code]) ? $overrides[$code] : ['text_value' => $overrides[$code]];
        }

        return match ($code) {
            'sku' => [
                'text_value' => fake()->uuid(),
            ],

            'name' => [
                'text_value' => fake()->words(3, true),
                'locale' => $locale,
            ],

            'url_key' => [
                'text_value' => fake()->unique()->slug(),
                'locale' => $locale,
            ],

            'guest_checkout', 'new', 'featured', 'visible_individually' => [
                'boolean_value' => true,
            ],

            'manage_stock', 'status', 'allow_rma' => [
                'boolean_value' => true,
                'channel' => $channel,
            ],

            'meta_title', 'meta_keywords', 'meta_description', 'short_description' => [
                'text_value' => fake()->sentence(),
                'locale' => $locale,
            ],

            'description' => [
                'text_value' => fake()->paragraph(),
                'locale' => $locale,
            ],

            'price' => [
                'float_value' => fake()->randomFloat(2, 1, 1000),
            ],

            'cost', 'special_price' => [
                'float_value' => null,
            ],

            'special_price_from', 'special_price_to' => [
                'date_value' => null,
                'channel' => $channel,
            ],

            'weight', 'height', 'width', 'length' => [
                'text_value' => fake()->numberBetween(1, 100),
            ],

            'product_number' => [
                'text_value' => fake()->numerify('bagisto-#########'),
            ],

            'tax_category_id' => [
                'integer_value' => null,
                'channel' => $channel,
            ],

            'rma_rule_id' => [
                'integer_value' => null,
                'channel' => $channel,
            ],

            'color', 'size', 'brand' => [
                'integer_value' => null,
            ],

            default => [],
        };
    }

    /**
     * Sync the SKU attribute value to match the product model's actual SKU.
     */
    protected function syncSkuAttributeValue(Product $product, array $attributes): void
    {
        if (! isset($attributes['sku'])) {
            return;
        }

        $product->attribute_values()
            ->where('attribute_id', $attributes['sku']->id)
            ->update(['text_value' => $product->sku]);
    }

    /**
     * Build the unique_id for each attribute value row.
     *
     * The unique_id format is: channel|locale|product_id|attribute_id
     * (channel and locale are only included when the attribute is scoped).
     */
    protected function syncUniqueIds(Product $product): void
    {
        $product->load('attribute_values.attribute');

        foreach ($product->attribute_values as $attributeValue) {
            $attribute = $attributeValue->attribute;

            $attributeValue->unique_id = implode('|', array_filter([
                $attribute->value_per_channel ? $attributeValue->channel : null,
                $attribute->value_per_locale ? $attributeValue->locale : null,
                $product->id,
                $attributeValue->attribute_id,
            ]));

            $attributeValue->save();
        }
    }

    /**
     * Get the attribute map keyed by code, cached for the test suite.
     */
    protected function getAttributeMap(): array
    {
        if (static::$attributeMap === null) {
            static::$attributeMap = Attribute::whereIn('code', static::$productAttributeCodes)
                ->get()
                ->keyBy('code')
                ->toArray();

            // Convert to objects for easier access.
            static::$attributeMap = array_map(
                fn ($attr) => (object) $attr,
                static::$attributeMap
            );
        }

        return static::$attributeMap;
    }
}
