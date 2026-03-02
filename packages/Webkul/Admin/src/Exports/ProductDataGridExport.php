<?php

namespace Webkul\Admin\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Webkul\DataGrid\DataGrid;
use Webkul\Product\Models\ProductAttributeValue;

class ProductDataGridExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * Cached product rows from the query builder (fetched once, reused by both
     * headings() and collection() since Maatwebsite Excel calls headings() first).
     */
    protected ?Collection $products = null;

    /**
     * Union of all attributes across the exported products' attribute families,
     * ordered by position and unique by attribute id.
     */
    protected ?Collection $attributes = null;

    /**
     * Attribute option labels keyed by option id, translated to the active locale.
     */
    protected ?Collection $optionLabels = null;

    /**
     * Attribute values lookup: [product_id => [attribute_id => stdClass]]
     * Only values matching the active locale/channel are kept.
     */
    protected ?Collection $attributeValuesByProduct = null;

    /**
     * Image URLs grouped by product_id: [product_id => Collection<string>]
     */
    protected ?Collection $imagesByProduct = null;

    /**
     * Video URLs grouped by product_id: [product_id => Collection<string>]
     */
    protected ?Collection $videosByProduct = null;

    /**
     * Map of attribute_family_id => int[] (attribute ids belonging to that family).
     */
    protected ?Collection $familyAttributeMap = null;

    /**
     * Map of product_id => attribute_family_id.
     */
    protected ?Collection $productFamilyMap = null;

    /**
     * Create a new instance.
     */
    public function __construct(protected DataGrid $datagrid) {}

    /**
     * Return the cached product collection.
     * Maatwebsite Excel calls headings() before collection(), so we pre-load
     * everything in ensurePreloaded() which is safe to call multiple times.
     */
    public function collection(): Collection
    {
        $this->ensurePreloaded();

        return $this->products;
    }

    /**
     * Build the header row: standard datagrid columns + all attribute columns
     * (union of families in the export) + Images + Videos.
     */
    public function headings(): array
    {
        $this->ensurePreloaded();

        $datagridHeaders = collect($this->datagrid->getColumns())
            ->filter(fn ($column) => $column->getExportable())
            ->map(fn ($column) => $column->getLabel())
            ->values()
            ->toArray();

        $attributeHeaders = $this->attributes
            ->map(fn ($attribute) => $attribute->admin_name)
            ->values()
            ->toArray();

        return array_merge(
            $datagridHeaders,
            $attributeHeaders,
            [
                trans('admin::app.catalog.products.edit.images.title'),
                trans('admin::app.catalog.products.edit.videos.title'),
            ]
        );
    }

    /**
     * Map a single product row to export values.
     *
     * For each attribute column: only output a value when the attribute belongs
     * to the product's own attribute family; otherwise leave blank (null).
     */
    public function map(mixed $record): array
    {
        $datagridValues = collect($this->datagrid->getColumns())
            ->filter(fn ($column) => $column->getExportable())
            ->map(fn ($column) => $this->sanitize($record->{$column->getIndex()}))
            ->values()
            ->toArray();

        $familyId = $this->productFamilyMap->get($record->product_id);

        $familyAttrIds = $this->familyAttributeMap->get($familyId, []);

        $attributeValues = $this->attributes
            ->map(function ($attribute) use ($record, $familyAttrIds) {
                // Only export value when this attribute belongs to the product's family.
                if (! in_array($attribute->id, $familyAttrIds)) {
                    return null;
                }

                return $this->resolveAttributeValue($record->product_id, $attribute);
            })
            ->values()
            ->toArray();

        $images = $this->imagesByProduct->get($record->product_id, collect())->implode(', ');

        $videos = $this->videosByProduct->get($record->product_id, collect())->implode(', ');

        return array_merge($datagridValues, $attributeValues, [$images, $videos]);
    }

    /**
     * Idempotent bootstrap: runs the product query once, then loads all related
     * data in a few batch queries.  Safe to call from both headings() and collection().
     */
    protected function ensurePreloaded(): void
    {
        if ($this->products !== null) {
            return;
        }

        $this->products = $this->datagrid->getQueryBuilder()->get();

        if ($this->products->isEmpty()) {
            $this->attributes = collect();
            $this->optionLabels = collect();
            $this->attributeValuesByProduct = collect();
            $this->imagesByProduct = collect();
            $this->videosByProduct = collect();
            $this->familyAttributeMap = collect();
            $this->productFamilyMap = collect();

            return;
        }

        $productIds = $this->products->pluck('product_id')->unique()->values()->toArray();

        $this->preloadFamilyAttributes($productIds);
        $this->preloadAttributeValues($productIds);
        $this->preloadMedia($productIds);
    }

    /**
     * Load the attribute-family context for the exported products:
     *  1. product_id → attribute_family_id mapping
     *  2. Unique attributes across all those families (ordered by position)
     *  3. family_id → [attribute_ids] map for per-row filtering in map()
     */
    protected function preloadFamilyAttributes(array $productIds): void
    {
        $locale = app()->getLocale();

        // product_id → attribute_family_id
        $this->productFamilyMap = DB::table('product_flat')
            ->whereIn('product_id', $productIds)
            ->where('locale', $locale)
            ->pluck('attribute_family_id', 'product_id');

        $familyIds = $this->productFamilyMap->unique()->filter()->values()->toArray();

        if (empty($familyIds)) {
            $this->attributes = collect();
            $this->familyAttributeMap = collect();

            return;
        }

        // All attributes that appear in at least one of the relevant families.
        // unique() on 'id' keeps the first occurrence so the ordered position is preserved.
        $this->attributes = DB::table('attributes')
            ->join('attribute_group_mappings', 'attributes.id', '=', 'attribute_group_mappings.attribute_id')
            ->join('attribute_groups', 'attribute_group_mappings.attribute_group_id', '=', 'attribute_groups.id')
            ->whereIn('attribute_groups.attribute_family_id', $familyIds)
            ->select(
                'attributes.id',
                'attributes.code',
                'attributes.admin_name',
                'attributes.type',
                'attributes.value_per_locale',
                'attributes.value_per_channel',
            )
            ->orderBy('attributes.position')
            ->get()
            ->unique('id')
            ->values();

        // Build family → [attr_id, ...] map so map() can check membership quickly.
        // A separate query keeps correct many-to-family assignments.
        $familyAttrRows = DB::table('attributes')
            ->join('attribute_group_mappings', 'attributes.id', '=', 'attribute_group_mappings.attribute_id')
            ->join('attribute_groups', 'attribute_group_mappings.attribute_group_id', '=', 'attribute_groups.id')
            ->whereIn('attribute_groups.attribute_family_id', $familyIds)
            ->select('attributes.id as attribute_id', 'attribute_groups.attribute_family_id')
            ->get();

        $this->familyAttributeMap = $familyAttrRows
            ->groupBy('attribute_family_id')
            ->map(fn ($rows) => $rows->pluck('attribute_id')->unique()->values()->toArray());
    }

    /**
     * Batch-load all product attribute values for the given product IDs.
     * Respects value_per_locale / value_per_channel flags and resolves select /
     * multiselect / checkbox option labels in one extra query.
     */
    protected function preloadAttributeValues(array $productIds): void
    {
        $locale = app()->getLocale();
        $channel = core()->getRequestedChannelCode();

        $rawValues = DB::table('product_attribute_values')
            ->select(
                'product_id',
                'attribute_id',
                'locale',
                'channel',
                'text_value',
                'boolean_value',
                'integer_value',
                'float_value',
                'datetime_value',
                'date_value',
            )
            ->whereIn('product_id', $productIds)
            ->get();

        // Collect all attribute-option ids used so we can translate them in one query.
        $allOptionIds = collect();

        foreach ($rawValues as $row) {
            if ($row->integer_value !== null) {
                $allOptionIds->push((int) $row->integer_value);
            }

            if (! empty($row->text_value) && str_contains($row->text_value, ',')) {
                foreach (explode(',', $row->text_value) as $id) {
                    if (is_numeric(trim($id))) {
                        $allOptionIds->push((int) trim($id));
                    }
                }
            }
        }

        $uniqueOptionIds = $allOptionIds->unique()->filter()->values()->toArray();

        $this->optionLabels = ! empty($uniqueOptionIds)
            ? DB::table('attribute_option_translations')
                ->whereIn('attribute_option_id', $uniqueOptionIds)
                ->where('locale', $locale)
                ->pluck('label', 'attribute_option_id')
            : collect();

        // Keep only the value row that matches the active locale/channel for each
        // (product_id, attribute_id) pair.
        $attributesById = $this->attributes->keyBy('id');
        $grouped = [];

        foreach ($rawValues as $row) {
            $attribute = $attributesById->get($row->attribute_id);

            if (! $attribute) {
                continue;
            }

            $matchesLocale = $attribute->value_per_locale
                ? $row->locale === $locale
                : $row->locale === null;

            $matchesChannel = $attribute->value_per_channel
                ? $row->channel === $channel
                : $row->channel === null;

            if (! $matchesLocale || ! $matchesChannel) {
                continue;
            }

            $grouped[$row->product_id][$row->attribute_id] = $row;
        }

        $this->attributeValuesByProduct = collect($grouped);
    }

    /**
     * Batch-load product images and videos, building per-product URL collections.
     */
    protected function preloadMedia(array $productIds): void
    {
        $this->imagesByProduct = DB::table('product_images')
            ->select('product_id', 'path', 'position')
            ->whereIn('product_id', $productIds)
            ->orderBy('product_id')
            ->orderBy('position')
            ->get()
            ->groupBy('product_id')
            ->map(fn ($rows) => $rows->map(fn ($row) => Storage::url($row->path)));

        $this->videosByProduct = DB::table('product_videos')
            ->select('product_id', 'path', 'position')
            ->whereIn('product_id', $productIds)
            ->orderBy('product_id')
            ->orderBy('position')
            ->get()
            ->groupBy('product_id')
            ->map(fn ($rows) => $rows->map(fn ($row) => Storage::url($row->path)));
    }

    /**
     * Return the human-readable / sanitized value for a single attribute on a product.
     *
     * Returns null when no value exists.
     */
    protected function resolveAttributeValue(int $productId, object $attribute): mixed
    {
        $productValues = $this->attributeValuesByProduct->get($productId, []);

        $row = $productValues[$attribute->id] ?? null;

        if ($row === null) {
            return null;
        }

        $column = ProductAttributeValue::$attributeTypeFields[$attribute->type] ?? 'text_value';

        $value = $row->{$column};

        if ($value === null || $value === '') {
            return null;
        }

        return match ($attribute->type) {
            'select' => $this->optionLabels->get((int) $value, $value),

            'multiselect', 'checkbox' => collect(explode(',', $value))
                ->map(fn ($id) => $this->optionLabels->get((int) trim($id), trim($id)))
                ->filter()
                ->implode(', '),

            'boolean' => (bool) $value
                ? trans('admin::app.export.yes')
                : trans('admin::app.export.no'),

            default => $this->sanitize($value),
        };
    }

    /**
     * Sanitize a value to prevent formula injection in spreadsheet cells.
     */
    protected function sanitize(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $trimmed = ltrim($value);

        if ($trimmed === '') {
            return $value;
        }

        $dangerousChars = ['=', '+', '-', '@', "\t", "\r", "\n", '|', '%'];

        $firstChar = mb_substr($trimmed, 0, 1);

        if (in_array($firstChar, $dangerousChars, true)) {
            return "'".$value;
        }

        if (preg_match('/^[\s]*[@=+\-|%]/u', $value)) {
            return "'".$value;
        }

        return $value;
    }
}
