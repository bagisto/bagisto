<?php

namespace Webkul\Installer\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Installer\Database\Seeders\Category\CategoryTableSeeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Base path for product images within the package.
     */
    const BASE_PATH = 'packages/Webkul/Installer/src/Resources/assets/images/seeders/products/';

    /**
     * Path to the JSON data file.
     */
    const DATA_FILE = __DIR__.'/../../Data/demo-products.json';

    /**
     * Maximum rows per INSERT to stay within MySQL placeholder limits.
     */
    const CHUNK_SIZE = 500;

    /**
     * Attribute codes that map to select/integer-type values on products.
     *
     * @var string[]
     */
    const SELECT_ATTRIBUTE_CODES = [
        'color',
        'size',
        'brand',
        'select_age_group',
        'waist_size_male_and_female',
        'neck',
        'sleeve',
        'select_size_female_footwear',
        'select_size_male_footwear',
    ];

    /**
     * Product columns excluded from attribute-value processing.
     *
     * @var string[]
     */
    const SKIP_ATTRIBUTES = [
        'product_id',
        'parent_id',
        'type',
        'attribute_family_id',
        'locale',
        'channel',
        'created_at',
        'updated_at',
    ];

    /**
     * Attributes that receive locale-specific values.
     *
     * @var string[]
     */
    const LOCALE_SPECIFIC_ATTRIBUTES = [
        'name',
        'url_key',
        'short_description',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    /**
     * Mapping of attribute types to their value column in product_attribute_values.
     *
     * @var array<string, string>
     */
    protected $attributeTypeFields = [
        'text' => 'text_value',
        'textarea' => 'text_value',
        'price' => 'float_value',
        'boolean' => 'boolean_value',
        'select' => 'integer_value',
        'multiselect' => 'text_value',
        'datetime' => 'datetime_value',
        'date' => 'date_value',
        'file' => 'text_value',
        'image' => 'text_value',
        'checkbox' => 'text_value',
    ];

    /**
     * Cached JSON data.
     */
    protected ?array $jsonData = null;

    /**
     * Current timestamp string shared across all insert operations.
     */
    protected string $timestamp;

    /**
     * Carbon instance for date calculations.
     */
    protected Carbon $now;

    /**
     * Active locales for this seeding run.
     *
     * @var string[]
     */
    protected array $locales;

    /**
     * Default locale for this seeding run.
     */
    protected string $defaultLocale;

    // =========================================================================
    // Entry Point
    // =========================================================================

    /**
     * Seed the application's database with demo products and all relations.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('products')->delete();

        $this->now = Carbon::now();
        $this->timestamp = $this->now->format('Y-m-d H:i:s');

        $this->defaultLocale = data_get($parameters, 'default_locale', config('app.locale'));
        $this->locales = data_get($parameters, 'allowed_locales', [$this->defaultLocale]);

        $this->seedAttributeInfrastructure();

        (new CategoryTableSeeder)->sampleCategories($parameters);

        $this->seedProducts($this->defaultLocale);

        $this->seedProductRelations();
    }

    // =========================================================================
    // Product Seeding
    // =========================================================================

    /**
     * Insert products, their attribute values, and channel assignments.
     */
    protected function seedProducts(string $defaultLocale): void
    {
        $localeProductsData = $this->buildProductsDataByLocale();

        $products = collect($localeProductsData[$defaultLocale])
            ->map(fn ($row) => Arr::only($row, ['parent_id', 'sku', 'type', 'attribute_family_id', 'created_at', 'updated_at']))
            ->all();

        DB::table('products')->insert($products);

        $this->seedAttributeValues($localeProductsData);

        $this->seedProductChannels();
    }

    /**
     * Build product data arrays keyed by locale.
     *
     * @return array<string, array>
     */
    protected function buildProductsDataByLocale(): array
    {
        $data = $this->loadJsonData();

        return collect($this->locales)->mapWithKeys(function ($locale) use ($data) {
            $products = collect($data['products'])->map(function ($product) use ($locale) {
                return $this->buildSingleProductData($product, $locale);
            })->all();

            return [$locale => $products];
        })->all();
    }

    /**
     * Build a single product's attribute data for a given locale.
     */
    protected function buildSingleProductData(array $product, string $locale): array
    {
        $translations = data_get($product, "translations.$locale", data_get($product, 'translations.en', []));

        $result = [
            'sku' => $product['sku'],
            'type' => $product['type'],
            'product_number' => $product['product_number'],
            'name' => data_get($translations, 'name', ''),
            'short_description' => data_get($translations, 'short_description', ''),
            'description' => data_get($translations, 'description', ''),
            'url_key' => $product['url_key'],
            'new' => $product['new'],
            'featured' => $product['featured'],
            'status' => $product['status'],
            'meta_title' => data_get($translations, 'meta_title', ''),
            'meta_keywords' => data_get($translations, 'meta_keywords', ''),
            'meta_description' => data_get($translations, 'meta_description', ''),
            'price' => $product['price'],
            'special_price' => $product['special_price'],
            'special_price_from' => $this->parseDateValue($product['special_price_from']),
            'special_price_to' => $this->parseDateValue($product['special_price_to']),
            'weight' => $product['weight'],
            'created_at' => $this->timestamp,
            'updated_at' => $this->timestamp,
            'locale' => $locale,
            'channel' => 'default',
            'attribute_family_id' => $product['attribute_family_id'],
            'product_id' => $product['product_id'],
            'parent_id' => $product['parent_id'],
            'visible_individually' => $product['visible_individually'],
        ];

        foreach (self::SELECT_ATTRIBUTE_CODES as $code) {
            if (Arr::has($product, $code)) {
                $result[$code] = $product[$code];
            }
        }

        return $result;
    }

    /**
     * Build and insert product_attribute_values from the locale-keyed product data.
     */
    protected function seedAttributeValues(array $localeProductsData): void
    {
        $attributes = DB::table('attributes')->get();
        $nullColumns = collect($this->attributeTypeFields)->values()->mapWithKeys(fn ($f) => [$f => null])->all();

        $seen = [];
        $rows = [];

        foreach ($localeProductsData as $locale => $productsData) {
            foreach ($productsData as $productData) {
                foreach ($productData as $code => $value) {
                    if (in_array($code, self::SKIP_ATTRIBUTES)) {
                        continue;
                    }

                    if ($locale !== 'en' && ! in_array($code, self::LOCALE_SPECIFIC_ATTRIBUTES)) {
                        continue;
                    }

                    $attribute = $attributes->firstWhere('code', $code);

                    if (! $attribute) {
                        continue;
                    }

                    $uniqueId = collect([
                        $attribute->value_per_channel ? 'default' : null,
                        $attribute->value_per_locale ? $locale : null,
                        $productData['product_id'],
                        $attribute->id,
                    ])->filter()->implode('|');

                    if (isset($seen[$uniqueId])) {
                        continue;
                    }

                    $seen[$uniqueId] = true;

                    $rows[] = array_merge($nullColumns, [
                        'attribute_id' => $attribute->id,
                        'product_id' => $productData['product_id'],
                        'channel' => $attribute->value_per_channel ? 'default' : null,
                        'locale' => $attribute->value_per_locale ? $locale : null,
                        'unique_id' => $uniqueId,
                        'json_value' => null,
                        $this->attributeTypeFields[$attribute->type] => $value,
                    ]);
                }
            }
        }

        $this->bulkInsert('product_attribute_values', $rows);
    }

    /**
     * Assign every product to the default channel.
     */
    protected function seedProductChannels(): void
    {
        $channels = DB::table('products')->get()->map(fn ($p) => [
            'product_id' => $p->id,
            'channel_id' => 1,
        ])->all();

        $this->bulkInsert('product_channels', $channels);
    }

    // =========================================================================
    // Product Relations
    // =========================================================================

    /**
     * Seed all product relations from JSON data.
     */
    protected function seedProductRelations(): void
    {
        $this->seedConfigurableProducts();
        $this->seedGroupedProducts();
        $this->seedBundleProducts();
        $this->seedDownloadableProducts();
        $this->seedBookingProducts();

        $this->seedCategories();
        $this->seedCustomerGroupPrices();
        $this->seedInventories();
        $this->seedImages();
        $this->seedProductLinks();
    }

    /**
     * Seed configurable product super attributes.
     */
    protected function seedConfigurableProducts(): void
    {
        $this->insertFromJson('super_attributes', 'product_super_attributes');
    }

    /**
     * Seed grouped product associations.
     */
    protected function seedGroupedProducts(): void
    {
        $this->insertFromJson('grouped_products', 'product_grouped_products');
    }

    /**
     * Seed bundle options, option products, and their translations.
     */
    protected function seedBundleProducts(): void
    {
        $data = $this->loadJsonData();

        if (blank($data['bundle_options'] ?? [])) {
            return;
        }

        $this->insertStrippingTranslations('bundle_options', 'product_bundle_options');

        $this->insertFromJson('bundle_option_products', 'product_bundle_option_products');

        $this->insertTranslations($data['bundle_options'], 'product_bundle_option_translations', 'product_bundle_option_id', [
            'label' => 'translations.label',
        ]);
    }

    /**
     * Seed downloadable links/samples and their translations.
     */
    protected function seedDownloadableProducts(): void
    {
        $data = $this->loadJsonData();

        if (filled($data['downloadable_links'] ?? [])) {
            $this->insertStrippingTranslations('downloadable_links', 'product_downloadable_links', withTimestamps: true);

            $this->insertTranslations($data['downloadable_links'], 'product_downloadable_link_translations', 'product_downloadable_link_id', [
                'title' => 'translations.title',
            ]);
        }

        if (filled($data['downloadable_samples'] ?? [])) {
            $this->insertStrippingTranslations('downloadable_samples', 'product_downloadable_samples', withTimestamps: true);

            $this->insertTranslations($data['downloadable_samples'], 'product_downloadable_sample_translations', 'product_downloadable_sample_id', [
                'title' => 'translations.title',
            ]);
        }
    }

    /**
     * Seed all booking-related tables.
     */
    protected function seedBookingProducts(): void
    {
        $data = $this->loadJsonData();

        if (blank($data['booking_products'] ?? [])) {
            return;
        }

        $bookingProducts = collect($data['booking_products'])
            ->map(fn ($item) => [
                'id' => $item['id'],
                'product_id' => $item['product_id'],
                'type' => $item['type'],
                'qty' => $item['qty'],
                'location' => $item['location'],
                'show_location' => $item['show_location'],
                'available_every_week' => $item['available_every_week'],
                'available_from' => $this->parseDateValue($item['available_from']),
                'available_to' => $this->parseDateValue($item['available_to']),
                'created_at' => $this->timestamp,
                'updated_at' => $this->timestamp,
            ])
            ->all();

        DB::table('booking_products')->insert($bookingProducts);

        $this->insertFromJson('booking_product_default_slots', 'booking_product_default_slots');
        $this->insertFromJson('booking_product_appointment_slots', 'booking_product_appointment_slots');
        $this->insertFromJson('booking_product_rental_slots', 'booking_product_rental_slots');

        $this->insertFromJsonWithTimestamps('booking_product_table_slots', 'booking_product_table_slots');

        if (filled($data['booking_product_event_tickets'] ?? [])) {
            $this->insertStrippingTranslations('booking_product_event_tickets', 'booking_product_event_tickets');

            $this->insertTranslations($data['booking_product_event_tickets'], 'booking_product_event_ticket_translations', 'booking_product_event_ticket_id', [
                'name' => 'translations.name',
                'description' => 'translations.description',
            ]);
        }
    }

    /**
     * Seed product-category assignments.
     */
    protected function seedCategories(): void
    {
        $data = $this->loadJsonData();

        $this->bulkInsert('product_categories', $data['categories']);
    }

    /**
     * Seed customer group prices with timestamps.
     */
    protected function seedCustomerGroupPrices(): void
    {
        $this->insertFromJsonWithTimestamps('customer_group_prices', 'product_customer_group_prices');
    }

    /**
     * Seed product inventories.
     */
    protected function seedInventories(): void
    {
        $data = $this->loadJsonData();

        $this->bulkInsert('product_inventories', $data['inventories']);
    }

    /**
     * Seed product images by copying files from the package to storage.
     */
    protected function seedImages(): void
    {
        $data = $this->loadJsonData();

        $images = collect($data['images'])
            ->map(fn ($item) => [
                'id' => $item['id'],
                'type' => $item['type'],
                'path' => $this->storeProductImage($item['path'], $item['file']),
                'product_id' => $item['product_id'],
                'position' => $item['position'],
            ])
            ->filter(fn ($item) => filled($item['path']))
            ->all();

        $this->bulkInsert('product_images', $images);
    }

    /**
     * Seed up-sells, cross-sells, and related product links.
     */
    protected function seedProductLinks(): void
    {
        $this->insertFromJson('up_sells', 'product_up_sells');
        $this->insertFromJson('cross_sells', 'product_cross_sells');
        $this->insertFromJson('relations', 'product_relations');
    }

    // =========================================================================
    // Attribute Infrastructure
    // =========================================================================

    /**
     * Seed additional attribute families, custom attributes, groups, options,
     * and their translations. Only inserts rows that don't already exist.
     */
    protected function seedAttributeInfrastructure(): void
    {
        $data = $this->loadJsonData();

        $this->seedAttributeFamilies($data);
        $this->seedCustomAttributes($data);
        $this->seedAttributeTranslations($data);
        $this->seedAttributeGroups($data);
        $this->seedAttributeGroupMappings($data);
        $this->seedAttributeOptions($data);
    }

    /**
     * Seed additional attribute families that don't exist yet.
     */
    protected function seedAttributeFamilies(array $data): void
    {
        if (blank($data['attribute_families'] ?? [])) {
            return;
        }

        $rows = collect($data['attribute_families'])
            ->filter(fn ($item) => ! DB::table('attribute_families')->where('id', $item['id'])->exists())
            ->map(function ($item) {
                $row = Arr::except($item, ['translations']);
                $row['name'] = data_get($item, "translations.{$this->defaultLocale}.name", $row['name'] ?? '');

                return $row;
            })
            ->values()
            ->all();

        if (filled($rows)) {
            DB::table('attribute_families')->insert($rows);
        }
    }

    /**
     * Seed custom attributes that don't exist yet.
     */
    protected function seedCustomAttributes(array $data): void
    {
        if (blank($data['custom_attributes'] ?? [])) {
            return;
        }

        $attributes = collect($data['custom_attributes'])
            ->filter(fn ($a) => ! DB::table('attributes')->where('id', $a['id'])->exists())
            ->map(function ($a) {
                $row = Arr::except($a, ['translations']);
                $row['admin_name'] = data_get($a, "translations.{$this->defaultLocale}.admin_name", $row['admin_name'] ?? '');
                $row['created_at'] = $this->now;
                $row['updated_at'] = $this->now;

                return $row;
            })
            ->values()
            ->all();

        if (filled($attributes)) {
            DB::table('attributes')->insert($attributes);
        }
    }

    /**
     * Seed attribute translations for custom attributes across all locales.
     */
    protected function seedAttributeTranslations(array $data): void
    {
        if (blank($data['custom_attribute_translations'] ?? [])) {
            return;
        }

        $existing = DB::table('attribute_translations')
            ->whereIn('attribute_id', collect($data['custom_attribute_translations'])->pluck('attribute_id'))
            ->get()
            ->groupBy('attribute_id');

        $rows = [];

        foreach ($data['custom_attribute_translations'] as $t) {
            foreach ($this->locales as $locale) {
                if (! $existing->get($t['attribute_id'], collect())->where('locale', $locale)->count()) {
                    $name = data_get($t, "translations.$locale.name", data_get($t, 'translations.en.name', ''));

                    $rows[] = [
                        'attribute_id' => $t['attribute_id'],
                        'locale' => $locale,
                        'name' => $name,
                    ];
                }
            }
        }

        if (filled($rows)) {
            DB::table('attribute_translations')->insert($rows);
        }
    }

    /**
     * Seed additional attribute groups that don't exist yet.
     */
    protected function seedAttributeGroups(array $data): void
    {
        if (blank($data['attribute_groups'] ?? [])) {
            return;
        }

        $rows = collect($data['attribute_groups'])
            ->filter(fn ($item) => ! DB::table('attribute_groups')->where('id', $item['id'])->exists())
            ->map(function ($item) {
                $row = Arr::except($item, ['translations']);
                $row['name'] = data_get($item, "translations.{$this->defaultLocale}.name", $row['name'] ?? '');

                return $row;
            })
            ->values()
            ->all();

        if (filled($rows)) {
            DB::table('attribute_groups')->insert($rows);
        }
    }

    /**
     * Seed attribute-to-group mappings that don't exist yet.
     */
    protected function seedAttributeGroupMappings(array $data): void
    {
        if (blank($data['attribute_group_mappings'] ?? [])) {
            return;
        }

        $existing = DB::table('attribute_group_mappings')->get();

        $mappings = collect($data['attribute_group_mappings'])
            ->filter(fn ($m) => ! $existing
                ->where('attribute_id', $m['attribute_id'])
                ->where('attribute_group_id', $m['attribute_group_id'])
                ->count())
            ->values()
            ->all();

        if (filled($mappings)) {
            DB::table('attribute_group_mappings')->insert($mappings);
        }
    }

    /**
     * Seed custom attribute options and their translations.
     * Avoids inserting duplicates by checking for existing option IDs.
     */
    protected function seedAttributeOptions(array $data): void
    {
        if (blank($data['custom_attribute_options'] ?? [])) {
            return;
        }

        $options = collect($data['custom_attribute_options'])
            ->filter(fn ($o) => ! DB::table('attribute_options')->where('id', $o['id'])->exists())
            ->map(function ($o) {
                $row = Arr::except($o, ['translations']);
                $row['admin_name'] = data_get($o, "translations.{$this->defaultLocale}.admin_name", $row['admin_name'] ?? '');

                return $row;
            })
            ->values()
            ->all();

        if (filled($options)) {
            DB::table('attribute_options')->insert($options);
        }

        $translations = $data['custom_attribute_option_translations'] ?? [];

        if (blank($translations)) {
            return;
        }

        foreach ($this->locales as $locale) {
            $rows = collect($translations)
                ->filter(fn ($t) => ! DB::table('attribute_option_translations')
                    ->where('attribute_option_id', $t['attribute_option_id'])
                    ->where('locale', $locale)
                    ->exists())
                ->map(fn ($t) => [
                    'attribute_option_id' => $t['attribute_option_id'],
                    'locale' => $locale,
                    'label' => data_get($t, "translations.$locale.label", data_get($t, 'translations.en.label', '')),
                ])
                ->values()
                ->all();

            if (filled($rows)) {
                DB::table('attribute_option_translations')->insert($rows);
            }
        }
    }

    // =========================================================================
    // Reusable Insert Helpers
    // =========================================================================

    /**
     * Insert data from a JSON key directly into a table, if the key has data.
     */
    protected function insertFromJson(string $jsonKey, string $table): void
    {
        $data = $this->loadJsonData();

        if (filled($data[$jsonKey] ?? [])) {
            DB::table($table)->insert($data[$jsonKey]);
        }
    }

    /**
     * Insert data from a JSON key, adding created_at/updated_at timestamps.
     */
    protected function insertFromJsonWithTimestamps(string $jsonKey, string $table): void
    {
        $data = $this->loadJsonData();

        if (blank($data[$jsonKey] ?? [])) {
            return;
        }

        $rows = collect($data[$jsonKey])
            ->map(fn ($item) => array_merge($item, [
                'created_at' => $this->timestamp,
                'updated_at' => $this->timestamp,
            ]))
            ->all();

        DB::table($table)->insert($rows);
    }

    /**
     * Insert data from a JSON key, stripping the embedded 'translations' key
     * and optionally appending timestamps.
     */
    protected function insertStrippingTranslations(string $jsonKey, string $table, bool $withTimestamps = false): void
    {
        $data = $this->loadJsonData();

        if (blank($data[$jsonKey] ?? [])) {
            return;
        }

        $rows = collect($data[$jsonKey])
            ->map(function ($item) use ($withTimestamps) {
                $row = Arr::except($item, ['translations']);

                if ($withTimestamps) {
                    $row['created_at'] = $this->timestamp;
                    $row['updated_at'] = $this->timestamp;
                }

                return $row;
            })
            ->all();

        DB::table($table)->insert($rows);
    }

    /**
     * Generate per-locale translation rows for items that embed a 'translations' key.
     *
     * @param  array  $items  The source items containing embedded translations.
     * @param  string  $table  The translations database table.
     * @param  string  $foreignKey  The column linking back to the parent item.
     * @param  array  $fieldMap  Map of DB column => dot-notation path in item.
     */
    protected function insertTranslations(array $items, string $table, string $foreignKey, array $fieldMap): void
    {
        foreach ($this->locales as $locale) {
            $rows = collect($items)->map(function ($item) use ($locale, $foreignKey, $fieldMap) {
                $row = [
                    $foreignKey => $item['id'],
                    'locale' => $locale,
                ];

                foreach ($fieldMap as $column => $path) {
                    $localePath = Str::replaceFirst('translations.', "translations.$locale.", $path);

                    $row[$column] = data_get($item, $localePath, data_get($item, Str::replaceFirst('translations.', 'translations.en.', $path), ''));
                }

                return $row;
            })->all();

            DB::table($table)->insert($rows);
        }
    }

    /**
     * Insert rows from a JSON key only if their 'id' doesn't already exist.
     */
    protected function insertIfNotExists(array $data, string $jsonKey, string $table): void
    {
        if (blank($data[$jsonKey] ?? [])) {
            return;
        }

        $rows = collect($data[$jsonKey])
            ->filter(fn ($item) => ! DB::table($table)->where('id', $item['id'])->exists())
            ->values()
            ->all();

        if (filled($rows)) {
            DB::table($table)->insert($rows);
        }
    }

    /**
     * Insert rows in chunks to stay within MySQL placeholder limits.
     */
    protected function bulkInsert(string $table, array $rows): void
    {
        if (blank($rows)) {
            return;
        }

        collect($rows)->chunk(self::CHUNK_SIZE)->each(
            fn ($chunk) => DB::table($table)->insert($chunk->all())
        );
    }

    // =========================================================================
    // Utilities
    // =========================================================================

    /**
     * Copy a product image from the package assets into Laravel storage.
     *
     * @return string|null The storage path, or null if the source file doesn't exist.
     */
    protected function storeProductImage(string $targetPath, string $file): ?string
    {
        $filePath = base_path(self::BASE_PATH.$file);

        if (Filesystem::exists($filePath)) {
            return Storage::putFile($targetPath, new File($filePath));
        }

        return null;
    }

    /**
     * Load and cache the JSON data file.
     */
    protected function loadJsonData(): array
    {
        if ($this->jsonData === null) {
            $this->jsonData = json_decode(Filesystem::get(self::DATA_FILE), true);
        }

        return $this->jsonData;
    }

    /**
     * Parse relative date expressions from JSON into absolute datetime strings.
     *
     * Supports: 'now', 'tomorrow', '+N days', or a literal datetime string.
     */
    protected function parseDateValue(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return match (true) {
            $value === 'now' => $this->now->format('Y-m-d H:i:s'),
            $value === 'tomorrow' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
            Str::startsWith($value, '+') && Str::contains($value, 'day') => $this->now->copy()->addDays((int) Str::before(Str::after($value, '+'), ' '))->format('Y-m-d H:i:s'),
            default => $value,
        };
    }
}
