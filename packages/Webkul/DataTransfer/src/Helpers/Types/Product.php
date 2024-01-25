<?php

namespace Webkul\DataTransfer\Helpers\Types;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\Slug;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Types\Product\SKUStorage;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class Product extends AbstractType
{
    /**
     * Error code for invalid product type
     */
    const ERROR_INVALID_TYPE = 'invalid_product_type';

    /**
     * Error code for non existing SKU
     */
    const ERROR_SKU_NOT_FOUND_FOR_DELETE = 'sku_not_found_to_delete';

    /**
     * Error code for duplicate url key
     */
    const ERROR_DUPLICATE_URL_KEY = 'duplicated_url_key';

    /**
     * Error code for invalid attribute family code
     */
    const ERROR_INVALID_ATTRIBUTE_FAMILY_CODE = 'attribute_family_code_not_found';

    /**
     * Error message templates
     */
    protected array $messageTemplates = [
        self::ERROR_INVALID_TYPE                  => 'Product type is invalid or not supported',
        self::ERROR_SKU_NOT_FOUND_FOR_DELETE      => 'Product with specified SKU not found',
        self::ERROR_DUPLICATE_URL_KEY             => 'URL key: \'%s\' was already generated for an item with the SKU: \'%s\'.',
        self::ERROR_INVALID_ATTRIBUTE_FAMILY_CODE => 'Invalid value for attribute family column (attribute family doesn\'t exist?)',
    ];

    /**
     * Resource link needed
     */
    protected bool $linkNeeded = true;

    /**
     * Permanent entity columns
     */
    protected array $permanentAttributes = ['sku'];

    /**
     * Permanent entity columns
     */
    protected string $masterAttributeCode = 'sku';

    /**
     * Permanent entity columns
     *
     * @var array
     */
    protected $attributeFamilies = [];

    /**
     * Permanent entity columns
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Permanent entity columns
     *
     * @var array
     */
    protected $typeFamilyAttributes = [];

    /**
     * Permanent entity columns
     *
     * @var array
     */
    protected $typeFamilyValidationRules = [];

    /**
     * Permanent entity columns
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Permanent entity columns
     */
    protected array $urlKeys = [];

    /**
     * Permanent entity columns
     */
    protected array $validColumnNames = [
        'locale',
        'type',
        'attribute_family_code',
        'categories',
        'tax_category_name',
        'inventories',
        'related_skus',
        'cross_sell_skus',
        'up_sell_skus',
        'bundle_options',
        'associated_skus',
    ];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected ImportBatchRepository $importBatchRepository,
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected AttributeRepository $attributeRepository,
        protected CategoryRepository $categoryRepository,
        protected InventorySourceRepository $inventorySourceRepository,
        protected ProductRepository $productRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected SKUStorage $skuStorage
    ) {
        parent::__construct($importBatchRepository);

        $this->initAttributes();
    }

    /**
     * Load all attributes and families to use later
     */
    protected function initAttributes(): void
    {
        $this->attributeFamilies = $this->attributeFamilyRepository->all();

        $this->attributes = $this->attributeRepository->all();

        foreach ($this->attributes as $key => $attribute) {
            $this->validColumnNames[] = $attribute->code;
        }
    }

    /**
     * Initialize Product error templates
     */
    protected function initErrorTemplates(): void
    {
        foreach ($this->messageTemplates as $errorCode => $template) {
            $this->errorHelper->addErrorMessageTemplate($errorCode, $template);
        }

        parent::initErrorTemplates();
    }

    /**
     * Retrieve valid column names
     */
    public function getValidColumnNames(): array
    {
        return $this->validColumnNames;
    }

    /**
     * Save validated batches
     */
    protected function saveValidatedBatches(): self
    {
        $source = $this->getSource();

        $source->rewind();

        $this->skuStorage->init();

        while ($source->valid()) {
            try {
                $rowData = $source->current();
            } catch (\InvalidArgumentException $e) {
                $source->next();

                continue;
            }

            $this->validateRow($rowData, $source->getCurrentRowNumber());

            $source->next();
        }

        $this->checkForDuplicateUrlKeys();

        parent::saveValidatedBatches();

        return $this;
    }

    /**
     * Start the import process
     */
    public function importBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.import.before', $batch);

        /**
         * Load SKU storage with batch skus
         */
        $this->skuStorage->load(Arr::pluck($batch->data, 'sku'));

        $products = [];

        $categories = [];

        $attributes = [];

        $inventories = [];

        foreach ($batch->data as $rowData) {
            $this->prepareProducts($rowData, $products);

            $this->prepareCategories($rowData, $categories);

            $this->prepareAttributeValues($rowData, $attributes);

            $this->prepareInventories($rowData, $inventories);
        }

        $this->saveProducts($products);

        $this->saveCategories($categories);

        $this->saveAttributeValues($attributes);

        $this->saveInventories($inventories);

        /**
         * Update import batch summary
         */
        $this->importBatchRepository->update([
            'state' => Import::STATE_PROCESSING,

            'summary'      => [
                'created' => $this->getCreatedItemsCount(),
                'updated' => $this->getUpdatedItemsCount(),
                'deleted' => $this->getDeletedItemsCount(),
            ],
        ], $batch->id);

        Event::dispatch('data_transfer.imports.batch.import.after', $batch);

        return true;
    }

    /**
     * Start the import product links process
     */
    public function importLinksBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.linking.before', $batch);

        /**
         * Load SKU storage with batch skus
         */
        $this->skuStorage->load(Arr::pluck($batch->data, 'sku'));

        $links = [];

        foreach ($batch->data as $rowData) {
            $this->prepareLinks($rowData, $links);
        }

        $this->saveLinks($links);

        /**
         * Update import batch summary
         */
        $this->importBatchRepository->update([
            'state' => Import::STATE_COMPLETED,
        ], $batch->id);

        Event::dispatch('data_transfer.imports.batch.linking.after', $batch);

        return true;
    }

    /**
     * Prepare products
     */
    public function prepareProducts(array $rowData, array &$products): void
    {
        $attributeFamilyId = $this->attributeFamilies
            ->where('code', $rowData['attribute_family_code'])
            ->first()->id;

        if ($this->isSKUExist($rowData['sku'])) {
            $products['update'][] = [
                'type'                => $rowData['type'],
                'sku'                 => $rowData['sku'],
                'attribute_family_id' => $attributeFamilyId,
            ];
        } else {
            $products['insert'][$rowData['sku']] = [
                'type'                => $rowData['type'],
                'sku'                 => $rowData['sku'],
                'attribute_family_id' => $attributeFamilyId,
                'created_at'          => $rowData['created_at'] ?? now(),
                'updated_at'          => $rowData['updated_at'] ?? now(),
            ];
        }
    }

    /**
     * Save products
     */
    public function saveProducts(array $products): void
    {
        if (! empty($products['update'])) {
            $this->updatedItemsCount += count($products['update']);

            $this->productRepository->upsert(
                $products['update'],
                $this->masterAttributeCode
            );
        }

        if (! empty($products['insert'])) {
            $this->createdItemsCount += count($products['insert']);

            $this->productRepository->insert($products['insert']);

            /**
             * Update the sku storage with newly created products
             */
            $newProducts = $this->productRepository->findWhereIn(
                'sku',
                array_keys($products['insert']),
                [
                    'id',
                    'type',
                    'sku',
                    'attribute_family_id',
                ]
            );

            foreach ($newProducts as $product) {
                $this->skuStorage->set($product->sku, [
                    'id'                  => $product->id,
                    'type'                => $product->type,
                    'attribute_family_id' => $product->attribute_family_id,
                ]);
            }
        }
    }

    /**
     * Prepare categories
     */
    public function prepareCategories(array $rowData, array &$categories): void
    {
        $names = explode('/', $rowData['categories']);

        $categoryIds = [];

        foreach ($names as $name) {
            if (isset($this->categories[$name])) {
                $categoryIds = array_merge($categoryIds, $this->categories[$name]);

                continue;
            }

            $this->categories[$name] = $this->categoryRepository
                ->whereTranslation('name', $name)
                ->pluck('id')
                ->toArray();

            $categoryIds = array_merge($categoryIds, $this->categories[$name]);
        }

        $categories[$rowData['sku']] = $categoryIds;
    }

    /**
     * Save categories
     */
    public function saveCategories(array $categories): void
    {
        $productCategories = [];

        foreach ($categories as $sku => $categoryIds) {
            $product = $this->skuStorage->get($sku);

            foreach ($categoryIds as $categoryId) {
                $productCategories[] = [
                    'product_id'  => $product['id'],
                    'category_id' => $categoryId,
                ];
            }
        }

        DB::table('product_categories')->upsert(
            $productCategories,
            ['product_id', 'category_id'],
        );
    }

    /**
     * Save products
     */
    public function prepareAttributeValues(array $rowData, array &$attributes): array
    {
        $data = [];

        $familyAttributes = $this->getProductTypeFamilyAttributes($rowData['type'], $rowData['attribute_family_code']);

        foreach ($rowData as $attributeCode => $value) {
            if (is_null($value)) {
                continue;
            }

            $attribute = $familyAttributes->where('code', $attributeCode)->first();

            if (! $attribute) {
                continue;
            }

            $attributeTypeValues = array_fill_keys(array_values($attribute->attributeTypeFields), null);

            $attributes[$rowData['sku']][$attribute->id] = array_merge($attributeTypeValues, [
                'attribute_id'          => $attribute->id,
                $attribute->column_name => $value,
                'channel'               => $attribute->value_per_channel ? ($rowData['channel'] ?? 'default') : null,
                'locale'                => $attribute->value_per_locale ? $rowData['locale'] : null,
            ]);
        }

        return $attributes;
    }

    /**
     * Save products
     */
    public function saveAttributeValues(array $attributes): void
    {
        $attributeValues = [];

        foreach ($attributes as $sku => $skuAttributes) {
            foreach ($skuAttributes as $attribute) {
                $product = $this->skuStorage->get($sku);

                $attribute['product_id'] = (int) $product['id'];

                $attribute['unique_id'] = implode('|', array_filter([
                    $attribute['channel'],
                    $attribute['locale'],
                    $attribute['product_id'],
                    $attribute['attribute_id'],
                ]));

                $attributeValues[] = $attribute;
            }
        }

        $this->productAttributeValueRepository->upsert($attributeValues, ['unique_id']);
    }

    /**
     * Prepare links
     */
    public function prepareLinks(array $rowData, array &$links): void
    {
        $linkTableMapping = [
            'related'    => 'product_relations',
            'cross_sell' => 'product_cross_sells',
            'up_sell'    => 'product_up_sells',
        ];

        foreach ($linkTableMapping as $type => $table) {
            if (! empty($rowData[$type . '_skus'])) {
                foreach (explode(',', $rowData[$type . '_skus']) as $sku) {
                    $links[$table][$rowData['sku']][] = $sku;
                }
            }
        }
    }

    /**
     * Save links
     */
    public function saveLinks(array $links): void
    {
        $notLoadedSkus = [];

        foreach (array_unique(Arr::flatten($links)) as $sku) {
            if ($this->skuStorage->has($sku)) {
                continue;
            }

            $notLoadedSkus[] = $sku;
        }

        /**
         * Load not loaded SKUs to the sku storage
         */
        if (! empty($notLoadedSkus)) {
            $this->skuStorage->load($notLoadedSkus);
        }

        foreach ($links as $table => $linksData) {
            $productLinks = [];

            foreach ($linksData as $sku => $linkedSkus) {
                $product = $this->skuStorage->get($sku);

                foreach ($linkedSkus as $linkedSku) {
                    $linkedProduct = $this->skuStorage->get($linkedSku);

                    if (! $linkedProduct) {
                        continue;
                    }

                    $productLinks[] = [
                        'parent_id' => $product['id'],
                        'child_id'  => $linkedProduct['id'],
                    ];
                }
            }

            DB::table($table)->upsert(
                $productLinks,
                ['parent_id', 'child_id'],
            );
        }
    }

    /**
     * Prepare inventories
     */
    public function prepareInventories(array $rowData, array &$inventories): void
    {
        if (empty($rowData['inventories'])) {
            return;
        }

        $inventorySources = explode(',', $rowData['inventories']);

        foreach ($inventorySources as $inventorySource) {
            [$inventorySource, $qty] = explode('=', $inventorySource);

            $inventories[$rowData['sku']][] = [
                'source' => $inventorySource,
                'qty'    => $qty,
            ];
        }
    }

    /**
     * Save inventories
     */
    public function saveInventories(array $inventories): void
    {
        if (empty($inventories)) {
            return;
        }

        $inventorySources = $this->inventorySourceRepository
            ->findWhereIn('code', Arr::flatten(Arr::pluck($inventories, '*.source')));

        $productInventories = [];

        foreach ($inventories as $sku => $skuInventories) {
            $product = $this->skuStorage->get($sku);

            foreach ($skuInventories as $inventory) {
                $inventorySource = $inventorySources->where('code', $inventory['source'])->first();

                if (! $inventorySource) {
                    continue;
                }

                $productInventories[] = [
                    'inventory_source_id' => $inventorySource->id,
                    'product_id'          => $product['id'],
                    'qty'                 => $inventory['qty'],
                    'vendor_id'           => 0,
                ];
            }
        }

        $this->productInventoryRepository->upsert(
            $productInventories,
            ['product_id', 'inventory_source_id', 'vendor_id']
        );
    }

    /**
     * Validates row
     */
    public function validateRow(array $rowData, int $rowNumber): bool
    {
        /**
         * If row is already validated than no need for further validation
         */
        if (isset($this->validatedRows[$rowNumber])) {
            return ! $this->errorHelper->isRowInvalid($rowNumber);
        }

        $this->validatedRows[$rowNumber] = true;

        /**
         * If import action is replace than no need for further validation
         */
        if ($this->import->action == Import::ACTION_REPLACE) {
            if (! $this->isSKUExist($rowData['sku'])) {
                $this->skipRow($rowNumber, self::ERROR_SKU_NOT_FOUND_FOR_DELETE);

                return false;
            }
        }

        /**
         * If import action is delete than no need for further validation
         */
        if ($this->import->action == Import::ACTION_DELETE) {
            if (! $this->isSKUExist($rowData['sku'])) {
                $this->skipRow($rowNumber, self::ERROR_SKU_NOT_FOUND_FOR_DELETE);

                return false;
            }

            return true;
        }

        /**
         * Check if product type exists
         */
        if (! config('product_types.' . $rowData['type'])) {
            $this->skipRow($rowNumber, self::ERROR_INVALID_TYPE, 'type');

            return false;
        }

        /**
         * Check if attribute family exists
         */
        if (! $this->attributeFamilies->where('code', $rowData['attribute_family_code'])->first()) {
            $this->skipRow($rowNumber, self::ERROR_INVALID_ATTRIBUTE_FAMILY_CODE, 'attribute_family_code');

            return false;
        }

        if (! isset($this->typeFamilyValidationRules[$rowData['type']][$rowData['attribute_family_code']])) {
            $this->typeFamilyValidationRules[$rowData['type']][$rowData['attribute_family_code']] = $this->getValidationRules($rowData);
        }

        /**
         * Validate product attributes
         */
        $validator = Validator::make($rowData, $this->typeFamilyValidationRules[$rowData['type']][$rowData['attribute_family_code']]);

        if ($validator->fails()) {
            $failedAttributes = $validator->failed();

            foreach ($validator->errors()->getMessages() as $attributeCode => $message) {
                $errorCode = array_key_first($failedAttributes[$attributeCode] ?? []);

                $this->skipRow($rowNumber, $errorCode, $attributeCode, current($message));
            }
        }

        /**
         * Check if url_key is unique
         */
        if (
            empty($this->urlKeys[$rowData['url_key']])
            || ($this->urlKeys[$rowData['url_key']]['sku'] == $rowData['sku'])
        ) {
            $this->urlKeys[$rowData['url_key']] = [
                'sku'        => $rowData['sku'],
                'row_number' => $rowNumber,
            ];
        } else {
            $message = sprintf(
                $this->messageTemplates[self::ERROR_DUPLICATE_URL_KEY],
                'url_key',
                $this->urlKeys[$rowData['url_key']]['sku']
            );

            $this->skipRow($rowNumber, self::ERROR_DUPLICATE_URL_KEY, 'url_key', $message);
        }

        return ! $this->errorHelper->isRowInvalid($rowNumber);
    }

    /**
     * Prepare validation rules
     */
    public function getValidationRules(array $rowData): array
    {
        $rules = [
            'sku'                => ['required', new Slug],
            'url_key'            => ['required'],
            'special_price_from' => ['nullable', 'date'],
            'special_price_to'   => ['nullable', 'date', 'after_or_equal:special_price_from'],
            'special_price'      => ['nullable', new Decimal, 'lt:price'],
        ];

        $attributes = $this->getProductTypeFamilyAttributes($rowData['type'], $rowData['attribute_family_code']);

        foreach ($attributes as $attribute) {
            if (
                in_array($attribute->code, ['sku', 'url_key'])
                || $attribute->type == 'boolean'
            ) {
                continue;
            }

            $validations = [];

            if (! isset($rules[$attribute->code])) {
                $validations[] = $attribute->is_required ? 'required' : 'nullable';
            } else {
                $validations = $rules[$attribute->code];
            }

            if (
                $attribute->type == 'text'
                && $attribute->validation
            ) {
                if ($attribute->validation === 'decimal') {
                    $validations[] = new Decimal;
                } elseif ($attribute->validation === 'regex') {
                    $validations[] = 'regex:' . $attribute->regex;
                } else {
                    $validations[] = $attribute->validation;
                }
            }

            if ($attribute->type == 'price') {
                $validations[] = new Decimal;
            }

            if ($attribute->is_unique) {
                array_push($validations, function ($field, $value, $fail) use ($attribute, $rowData) {
                    $product = $this->skuStorage->get($rowData['sku']);

                    $count = $this->productAttributeValueRepository
                        ->where($attribute->column_name, $rowData[$attribute->code])
                        ->where('attribute_id', '=', $attribute->id)
                        ->where('product_attribute_values.product_id', '!=', $product['id'])
                        ->count('product_attribute_values.id');

                    if ($count) {
                        $fail(__('admin::app.catalog.products.index.already-taken', ['name' => ':attribute']));
                    }
                });
            }

            $rules[$attribute->code] = $validations;
        }

        return $rules;
    }

    /**
     * Check that url_keys are not assigned to other products in DB
     */
    protected function checkForDuplicateUrlKeys(): void
    {
        $products = $this->productRepository
            ->resetScope()
            ->select('products.id', 'product_attribute_values.text_value as url_key', 'products.sku')
            ->leftJoin('product_attribute_values', 'products.id', 'product_attribute_values.product_id')
            ->leftJoin('attributes', 'product_attribute_values.attribute_id', 'attributes.id')
            ->where('attributes.code', 'url_key')
            ->where('product_attribute_values.text_value', array_keys($this->urlKeys))
            ->whereNotIn('products.sku', Arr::pluck($this->urlKeys, 'sku'))
            ->get();

        foreach ($products as $product) {
            $this->skipRow(
                $this->urlKeys[$product->url_key]['row_number'],
                self::ERROR_DUPLICATE_URL_KEY,
                'url_key',
                sprintf(
                    $this->messageTemplates[self::ERROR_DUPLICATE_URL_KEY],
                    $product->url_key,
                    $product->sku
                )
            );
        }
    }

    /**
     * Retrieve product type family attributes
     */
    public function getProductTypeFamilyAttributes(string $type, string $attributeFamilyCode): mixed
    {
        if (isset($this->typeFamilyAttributes[$type][$attributeFamilyCode])) {
            return $this->typeFamilyAttributes[$type][$attributeFamilyCode];
        }

        $attributeFamily = $this->attributeFamilies->where('code', $attributeFamilyCode)->first();

        $product = ProductModel::make([
            'type'                => $type,
            'attribute_family_id' => $attributeFamily->id,
        ]);

        return $this->typeFamilyAttributes[$type][$attributeFamilyCode] = $product->getEditableAttributes();
    }

    /**
     * Prepare row data to save into the database
     */
    protected function prepareRowForDb(array $rowData): array
    {
        $rowData = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $rowData);

        return $rowData;
    }

    /**
     * Check if SKU exists
     */
    public function isSKUExist(string $sku): bool
    {
        return $this->skuStorage->has($sku);
    }

    /**
     * Add row as skipped
     *
     * @param  int|null  $rowNumber
     * @param  string|null  $columnName
     * @param  string|null  $errorMessage
     * @return $this
     */
    private function skipRow($rowNumber, string $errorCode, $columnName = null, $errorMessage = null): self
    {
        $this->addRowError($errorCode, $rowNumber, $columnName, $errorMessage);

        $this->errorHelper->addRowToSkip($rowNumber);

        return $this;
    }
}
