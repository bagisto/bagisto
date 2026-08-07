<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\Installer\Database\Seeders\ProductTableSeeder;

/**
 * Run the seeder's real attribute-value builder for the given locales and return the
 * rows it would have written, counted per attribute code. The insert is intercepted so
 * the locale filtering can be exercised without seeding a couple of thousand products
 * into the shared test database. The subclass is anonymous because a named one would
 * sit in a PSR-4 root and break `composer dump-autoload`.
 */
function seededAttributeCounts(array $locales, string $defaultLocale): array
{
    $seeder = new class extends ProductTableSeeder
    {
        /**
         * Rows the seeder would have written, keyed by table.
         */
        public array $captured = [];

        /**
         * Build the attribute values for the given locales.
         */
        public function buildFor(array $locales, string $defaultLocale): void
        {
            $this->now = Carbon::now();
            $this->timestamp = $this->now->format('Y-m-d H:i:s');
            $this->defaultLocale = $defaultLocale;
            $this->locales = $locales;

            $this->seedAttributeValues($this->buildProductsDataByLocale());
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
    };

    $seeder->buildFor($locales, $defaultLocale);

    return $seeder->countsByAttributeCode();
}

it('should seed the non translatable attributes when english is not an allowed locale', function () {
    $counts = seededAttributeCounts(['ar'], 'ar');

    foreach (['status', 'visible_individually', 'price', 'sku'] as $code) {
        expect($counts[$code] ?? 0)->toBeGreaterThan(0);
    }
});

it('should seed the same non translatable attributes whichever locales are chosen', function () {
    $withEnglish = seededAttributeCounts(['en', 'ar'], 'ar');

    $withoutEnglish = seededAttributeCounts(['ar'], 'ar');

    foreach (['status', 'visible_individually', 'price', 'sku'] as $code) {
        expect($withoutEnglish[$code] ?? 0)->toBe($withEnglish[$code] ?? 0);
    }
});

it('should seed the translatable attributes once per allowed locale', function () {
    $single = seededAttributeCounts(['ar'], 'ar');

    $double = seededAttributeCounts(['en', 'ar'], 'ar');

    expect($double['name'])->toBe($single['name'] * 2)
        ->and($double['url_key'])->toBe($single['url_key'] * 2);
});
