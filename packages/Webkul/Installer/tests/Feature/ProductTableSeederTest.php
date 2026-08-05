<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\Installer\Database\Seeders\ProductTableSeeder;

/**
 * Runs the seeder's real attribute-value builder but captures the rows instead of
 * inserting them, so the locale filtering can be exercised without seeding a couple
 * of thousand products into the shared test database.
 */
class CapturingProductTableSeeder extends ProductTableSeeder
{
    /**
     * Rows the seeder would have written, keyed by table.
     */
    public array $captured = [];

    /**
     * Build the attribute values for the given locales and return the seeder.
     */
    public static function forLocales(array $locales, string $defaultLocale): self
    {
        $seeder = new self;

        $seeder->now = Carbon::now();
        $seeder->timestamp = $seeder->now->format('Y-m-d H:i:s');
        $seeder->defaultLocale = $defaultLocale;
        $seeder->locales = $locales;

        $seeder->seedAttributeValues($seeder->buildProductsDataByLocale());

        return $seeder;
    }

    /**
     * Count the captured attribute values per attribute code.
     */
    public function countsByAttributeCode(): array
    {
        $codes = DB::table('attributes')->pluck('code', 'id');

        return collect($this->captured['product_attribute_values'] ?? [])
            ->groupBy(fn ($row) => $codes[$row['attribute_id']] ?? '')
            ->map->count()
            ->all();
    }

    /**
     * Capture the rows rather than writing them.
     */
    protected function bulkInsert(string $table, array $rows): void
    {
        $this->captured[$table] = array_merge($this->captured[$table] ?? [], $rows);
    }
}

it('should seed the non translatable attributes when english is not an allowed locale', function () {
    $counts = CapturingProductTableSeeder::forLocales(['ar'], 'ar')->countsByAttributeCode();

    foreach (['status', 'visible_individually', 'price', 'sku'] as $code) {
        expect($counts[$code] ?? 0)->toBeGreaterThan(0);
    }
});

it('should seed the same non translatable attributes whichever locales are chosen', function () {
    $withEnglish = CapturingProductTableSeeder::forLocales(['en', 'ar'], 'ar')->countsByAttributeCode();

    $withoutEnglish = CapturingProductTableSeeder::forLocales(['ar'], 'ar')->countsByAttributeCode();

    foreach (['status', 'visible_individually', 'price', 'sku'] as $code) {
        expect($withoutEnglish[$code] ?? 0)->toBe($withEnglish[$code] ?? 0);
    }
});

it('should seed the translatable attributes once per allowed locale', function () {
    $single = CapturingProductTableSeeder::forLocales(['ar'], 'ar')->countsByAttributeCode();

    $double = CapturingProductTableSeeder::forLocales(['en', 'ar'], 'ar')->countsByAttributeCode();

    expect($double['name'])->toBe($single['name'] * 2)
        ->and($double['url_key'])->toBe($single['url_key'] * 2);
});
