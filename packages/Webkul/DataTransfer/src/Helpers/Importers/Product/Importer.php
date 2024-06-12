<?php

namespace Webkul\DataTransfer\Helpers\Importers\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\Slug;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Jobs\ElasticSearch\DeleteIndex as DeleteIndexJob;
use Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex as UpdateCreateElasticSearchIndexJob;
use Webkul\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;
use Webkul\Product\Jobs\UpdateCreatePriceIndex as UpdateCreatePriceIndexJob;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductBundleOptionRepository;
use Webkul\Product\Repositories\ProductCustomerGroupPriceRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;

class Importer extends AbstractImporter
{
    /**
     * Product type simple
     */
    const PRODUCT_TYPE_SIMPLE = 'simple';

    /**
     * Product type virtual
     */
    const PRODUCT_TYPE_VIRTUAL = 'virtual';

    /**
     * Product type downloadable
     */
    const PRODUCT_TYPE_DOWNLOADABLE = 'downloadable';

    /**
     * Product type configurable
     */
    const PRODUCT_TYPE_CONFIGURABLE = 'configurable';

    /**
     * Product type bundle
     */
    const PRODUCT_TYPE_BUNDLE = 'bundle';

    /**
     * Product type grouped
     */
    const PRODUCT_TYPE_GROUPED = 'grouped';

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
     * Error code for super attribute code not found
     */
    const ERROR_SUPER_ATTRIBUTE_CODE_NOT_FOUND = 'attribute_family_code_not_found';

    /**
     * Error message templates
     */
    protected array $messages = [
        self::ERROR_INVALID_TYPE                   => 'data_transfer::app.importers.products.validation.errors.invalid-type',
        self::ERROR_SKU_NOT_FOUND_FOR_DELETE       => 'data_transfer::app.importers.products.validation.errors.sku-not-found',
        self::ERROR_DUPLICATE_URL_KEY              => 'data_transfer::app.importers.products.validation.errors.duplicate-url-key',
        self::ERROR_INVALID_ATTRIBUTE_FAMILY_CODE  => 'data_transfer::app.importers.products.validation.errors.invalid-attribute-family',
        self::ERROR_SUPER_ATTRIBUTE_CODE_NOT_FOUND => 'data_transfer::app.importers.products.validation.errors.super-attribute-not-found',
    ];

    /**
     * Permanent entity columns
     */
    protected array $permanentAttributes = ['sku'];

    /**
     * Permanent entity column
     */
    protected string $masterAttributeCode = 'sku';

    /**
     * Cached attribute families
     */
    protected mixed $attributeFamilies = [];

    /**
     * Cached attributes
     */
    protected mixed $attributes = [];

    /**
     * Cached product type family attributes
     */
    protected array $typeFamilyAttributes = [];

    /**
     * Product type family validation rules
     */
    protected array $typeFamilyValidationRules = [];

    /**
     * Cached categories
     */
    protected array $categories = [];

    /**
     * Cached channels
     */
    protected Collection $channels;

    /**
     * Cached categories
     */
    protected mixed $customerGroups = [];

    /**
     * Urls keys storage
     */
    protected array $urlKeys = [];

    /**
     * Urls keys storage
     */
    protected array $productFlatColumns = [];

    /**
     * Is linking required
     */
    protected bool $linkingRequired = true;

    /**
     * Is indexing required
     */
    protected bool $indexingRequired = true;

    /**
     * Valid csv columns
     */
    protected array $validColumnNames = [
        'locale',
        'type',
        'attribute_family_code',
        'parent_sku',
        'categories',
        'images',
        'customer_group_prices',
        'tax_category_name',
        'inventories',
        'related_skus',
        'cross_sell_skus',
        'up_sell_skus',
        'configurable_variants',
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
        protected AttributeOptionRepository $attributeOptionRepository,
        protected CategoryRepository $categoryRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected ChannelRepository $channelRepository,
        protected InventorySourceRepository $inventorySourceRepository,
        protected ProductRepository $productRepository,
        protected ProductFlatRepository $productFlatRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        protected ProductImageRepository $productImageRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected ProductBundleOptionRepository $productBundleOptionRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductCustomerGroupPriceRepository $productCustomerGroupPriceRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository,
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
    protected function initErrorMessages(): void
    {
        foreach ($this->messages as $errorCode => $message) {
            $this->errorHelper->addErrorMessage($errorCode, trans($message));
        }

        parent::initErrorMessages();
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
        if (
            $rowData['type'] == self::PRODUCT_TYPE_DOWNLOADABLE
            || ! config('product_types.'.$rowData['type'])
        ) {
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
                trans($this->messages[self::ERROR_DUPLICATE_URL_KEY]),
                'url_key',
                $this->urlKeys[$rowData['url_key']]['sku']
            );

            $this->skipRow($rowNumber, self::ERROR_DUPLICATE_URL_KEY, 'url_key', $message);
        }

        /**
         * Additional Validations
         *
         * 1: Check if bundle option data is valid
         * 2: Check if grouped products data is valid
         * 3: Check if grouped products data is valid
         * 4: Customer group prices validation for non composite products
         */
        $optionsData = [];

        $validationRules = [];

        if ($rowData['type'] == self::PRODUCT_TYPE_BUNDLE) {
            $validationRules = [
                'bundle_options.*.name'     => 'sometimes|required',
                'bundle_options.*.type'     => 'sometimes|required|in:select,radio,checkbox,multiselect',
                'bundle_options.*.required' => 'sometimes|required|boolean',
                'bundle_options.*.sku'      => 'sometimes|required',
                'bundle_options.*.price'    => ['sometimes', 'required', new Decimal],
                'bundle_options.*.qty'      => 'sometimes|required|integer',
                'bundle_options.*.default'  => 'sometimes|required|boolean',
            ];

            $options = explode('|', $rowData['bundle_options'] ?? '');

            foreach ($options as $option) {
                parse_str(str_replace(',', '&', $option), $attributes);

                $optionsData['bundle_options'][] = $attributes;
            }
        } elseif ($rowData['type'] == self::PRODUCT_TYPE_GROUPED) {
            $validationRules = [
                'associated_skus.*.sku' => 'sometimes|required',
                'associated_skus.*.qty' => 'sometimes|required|integer',
            ];

            $associatedSkus = explode(',', $rowData['associated_skus'] ?? '');

            foreach ($associatedSkus as $row) {
                [$sku, $qty] = explode('=', $row);

                $optionsData['associated_skus'][] = [
                    'sku' => $sku ?? '',
                    'qty' => $qty ?? null,
                ];
            }
        } elseif ($rowData['type'] == self::PRODUCT_TYPE_CONFIGURABLE) {
            $validationRules = [
                'configurable_variants.*.sku' => 'sometimes|required',
            ];

            $options = explode('|', $rowData['configurable_variants'] ?? '');

            foreach ($options as $option) {
                parse_str(str_replace(',', '&', $option), $attributes);

                $optionsData['configurable_variants'][] = $attributes;
            }
        } else {
            /**
             * Validate customer group prices
             */
            $validationRules = [
                'customer_group_prices.*.group' => 'sometimes|required',
                'customer_group_prices.*.qty'   => 'sometimes|required|integer',
                'customer_group_prices.*.type'  => 'sometimes|required|in:fixed,discount',
                'customer_group_prices.*.price' => ['sometimes', 'required', new Decimal],
            ];

            $customerGroupPrices = explode('|', $rowData['customer_group_prices'] ?? '');

            foreach ($customerGroupPrices as $customerGroupPrice) {
                parse_str(str_replace(',', '&', $customerGroupPrice), $attributes);

                $optionsData['customer_group_prices'][] = $attributes;
            }
        }

        if (! empty($optionsData)) {
            $validator = Validator::make($optionsData, $validationRules);

            if ($validator->fails()) {
                foreach ($validator->errors()->getMessages() as $attributeCode => $message) {
                    $failedAttributes = $validator->failed();

                    $errorCode = array_key_first($failedAttributes[$attributeCode] ?? []);

                    $this->skipRow($rowNumber, $errorCode, $attributeCode, current($message));
                }
            }
        }

        /**
         * Check if configurable super attribute exists in the attribute family
         *
         * Below is the example of configurable_variants
         *
         * sku=SP-005,color=Yellow,size=M|sku=SP-006,color=Yellow,size=L|sku=SP-007,color=Green,size=M|sku=SP-008,color=Green,size=L
         */
        if ($rowData['type'] == self::PRODUCT_TYPE_CONFIGURABLE) {
            $variants = explode('|', $rowData['configurable_variants'] ?? '');

            $familyAttributes = $this->getProductTypeFamilyAttributes($rowData['type'], $rowData['attribute_family_code']);

            foreach ($variants as $variant) {
                parse_str(str_replace(',', '&', $variant), $variantAttributes);

                $configurableVariants = Arr::except($variantAttributes, 'sku');

                foreach ($configurableVariants as $superAttribute => $optionLabel) {
                    if (! $familyAttributes->where('code', $superAttribute)->first()) {
                        $this->skipRow(
                            $rowNumber,
                            self::ERROR_SUPER_ATTRIBUTE_CODE_NOT_FOUND,
                            'configurable_variants',
                            sprintf(
                                trans($this->messages[self::ERROR_SUPER_ATTRIBUTE_CODE_NOT_FOUND]),
                                $superAttribute,
                                $rowData['attribute_family_code']
                            )
                        );
                    }
                }
            }
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
            if (in_array($attribute->code, ['sku', 'url_key'])) {
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
                    $validations[] = 'regex:'.$attribute->regex;
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
        if (empty($this->urlKeys)) {
            return;
        }

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
                    trans($this->messages[self::ERROR_DUPLICATE_URL_KEY]),
                    $product->url_key,
                    $product->sku
                )
            );
        }
    }

    /**
     * Start the import process
     */
    public function importBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.import.before', $batch);

        if ($batch->import->action == Import::ACTION_DELETE) {
            $this->deleteProducts($batch);
        } else {
            $this->saveProductsData($batch);
        }

        /**
         * Update import batch summary
         */
        $batch = $this->importBatchRepository->update([
            'state' => Import::STATE_PROCESSED,

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
     * Start the products linking process
     */
    public function linkBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.linking.before', $batch);

        /**
         * Load SKU storage with batch skus
         */
        $this->skuStorage->load(Arr::pluck($batch->data, 'sku'));

        $configurableVariants = [];

        $groupAssociations = [];

        $bundleOptions = [];

        $links = [];

        foreach ($batch->data as $rowData) {
            /**
             * Prepare configurable variants
             */
            $this->prepareConfigurableVariants($rowData, $configurableVariants);

            /**
             * Prepare products association for grouped product
             */
            $this->prepareGroupAssociations($rowData, $groupAssociations);

            /**
             * Prepare bundle options
             */
            $this->prepareBundleOptions($rowData, $bundleOptions);

            /**
             * Prepare products association for related, cross sell and up sell
             */
            $this->prepareLinks($rowData, $links);
        }

        $this->saveConfigurableVariants($configurableVariants);

        $this->saveGroupAssociations($groupAssociations);

        $this->saveBundleOptions($bundleOptions);

        $this->saveLinks($links);

        /**
         * Update import batch summary
         */
        $this->importBatchRepository->update([
            'state' => Import::STATE_LINKED,
        ], $batch->id);

        Event::dispatch('data_transfer.imports.batch.linking.after', $batch);

        return true;
    }

    /**
     * Start the products indexing process
     */
    public function indexBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.indexing.before', $batch);

        /**
         * Load SKU storage with batch skus
         */
        $this->skuStorage->load(Arr::pluck($batch->data, 'sku'));

        $typeProductIds = [];

        foreach ($batch->data as $rowData) {
            $product = $this->skuStorage->get($rowData['sku']);

            $typeProductIds[$product['type']][] = (int) $product['id'];
        }

        $productIdsToIndex = [];

        foreach ($typeProductIds as $type => $productIds) {
            switch ($type) {
                case self::PRODUCT_TYPE_SIMPLE:
                case self::PRODUCT_TYPE_VIRTUAL:
                    $productIdsToIndex = [
                        ...$productIds,
                        ...$productIdsToIndex,
                    ];

                    /**
                     * Get all the parent bundle product ids
                     */
                    $parentBundleProductIds = $this->productBundleOptionRepository
                        ->select('product_bundle_options.product_id')
                        ->leftJoin('product_bundle_option_products', 'product_bundle_options.id', 'product_bundle_option_products.product_bundle_option_id')
                        ->whereIn('product_bundle_option_products.product_id', $productIds)
                        ->pluck('product_id')
                        ->toArray();

                    $productIdsToIndex = [
                        ...$productIdsToIndex,
                        ...$parentBundleProductIds,
                    ];

                    /**
                     * Get all the parent grouped product ids
                     */
                    $parentGroupedProductIds = $this->productGroupedProductRepository
                        ->select('product_id')
                        ->whereIn('associated_product_id', $productIds)
                        ->pluck('product_id')
                        ->toArray();

                    $productIdsToIndex = [
                        ...$productIdsToIndex,
                        ...$parentGroupedProductIds,
                    ];

                    /**
                     * Get all the parent configurable product ids
                     */
                    $parentConfigurableProductIds = $this->productRepository->select('parent_id')
                        ->whereIn('id', $productIds)
                        ->whereNotNull('parent_id')
                        ->pluck('parent_id')
                        ->toArray();

                    $productIdsToIndex = [
                        ...$productIdsToIndex,
                        ...$parentConfigurableProductIds,
                    ];

                    break;

                case self::PRODUCT_TYPE_CONFIGURABLE:
                    $productIdsToIndex = [
                        ...$productIdsToIndex,
                        ...$productIds,
                    ];

                    /**
                     * Get all configurable product children ids
                     */
                    $associatedProductIds = $this->productRepository->select('id')
                        ->whereIn('parent_id', $productIds)
                        ->pluck('id')
                        ->toArray();

                    $productIdsToIndex = [
                        ...$associatedProductIds,
                        ...$productIdsToIndex,
                    ];

                    break;

                case self::PRODUCT_TYPE_BUNDLE:
                    $productIdsToIndex = [
                        ...$productIdsToIndex,
                        ...$productIds,
                    ];

                    /**
                     * Get all bundle product associated product ids
                     */
                    $associatedProductIds = $this->productBundleOptionProductRepository
                        ->select('product_bundle_option_products.product_id')
                        ->leftJoin('product_bundle_options', 'product_bundle_option_products.product_bundle_option_id', 'product_bundle_options.id')
                        ->whereIn('product_bundle_options.product_id', $productIds)
                        ->pluck('product_id')
                        ->toArray();

                    $productIdsToIndex = [
                        ...$associatedProductIds,
                        ...$productIdsToIndex,
                    ];

                    break;

                case self::PRODUCT_TYPE_GROUPED:
                    $productIdsToIndex = [
                        ...$productIdsToIndex,
                        ...$productIds,
                    ];

                    /**
                     * Get all grouped product associated product ids
                     */
                    $associatedProductIds = $this->productGroupedProductRepository
                        ->select('associated_product_id')
                        ->whereIn('product_id', $productIds)
                        ->pluck('associated_product_id')
                        ->toArray();

                    $productIdsToIndex = [
                        ...$associatedProductIds,
                        ...$productIdsToIndex,
                    ];

                    break;
            }
        }

        $productIdsToIndex = array_unique($productIdsToIndex);

        Bus::chain([
            new UpdateCreateInventoryIndexJob($productIdsToIndex),
            new UpdateCreatePriceIndexJob($productIdsToIndex),
            new UpdateCreateElasticSearchIndexJob($productIdsToIndex),
        ])->onConnection('sync')->dispatch();

        /**
         * Update import batch summary
         */
        $this->importBatchRepository->update([
            'state' => Import::STATE_INDEXED,
        ], $batch->id);

        Event::dispatch('data_transfer.imports.batch.indexing.after', $batch);

        return true;
    }

    /**
     * Delete products from current batch
     */
    protected function deleteProducts(ImportBatchContract $batch): bool
    {
        /**
         * Load SKU storage with batch skus
         */
        $this->skuStorage->load(Arr::pluck($batch->data, 'sku'));

        $idsToDelete = [];

        foreach ($batch->data as $rowData) {
            if (! $this->isSKUExist($rowData['sku'])) {
                continue;
            }

            $product = $this->skuStorage->get($rowData['sku']);

            $idsToDelete[] = $product['id'];
        }

        $idsToDelete = array_unique($idsToDelete);

        $this->deletedItemsCount = count($idsToDelete);

        $this->productRepository->deleteWhere([['id', 'IN', $idsToDelete]]);

        /**
         * Remove product images from the storage
         */
        foreach ($idsToDelete as $id) {
            $imageDirectory = $this->productImageRepository->getProductDirectory((object) ['id' => $id]);

            if (! Storage::exists($imageDirectory)) {
                continue;
            }

            Storage::deleteDirectory($imageDirectory);
        }

        DeleteIndexJob::dispatch($idsToDelete)->onConnection('sync');

        return true;
    }

    /**
     * Save products from current batch
     */
    protected function saveProductsData(ImportBatchContract $batch): bool
    {
        /**
         * Load SKU storage with batch skus
         */
        $this->skuStorage->load(Arr::pluck($batch->data, 'sku'));

        $products = [];

        $channels = [];

        $customerGroupPrices = [];

        $categories = [];

        $attributeValues = [];

        $inventories = [];

        $imagesData = [];

        $flatData = [];

        foreach ($batch->data as $rowData) {
            /**
             * Prepare products for import
             */
            $this->prepareProducts($rowData, $products);

            /**
             * Prepare product channels to attach with products
             */
            $this->prepareChannels($rowData, $channels);

            /**
             * Prepare customer group prices
             */
            $this->prepareCustomerGroupPrices($rowData, $customerGroupPrices);

            /**
             * Prepare product categories to attach with products
             */
            $this->prepareCategories($rowData, $categories);

            /**
             * Prepare products attribute values
             */
            $this->prepareAttributeValues($rowData, $attributeValues);

            /**
             * Prepare products inventories for every inventory source
             */
            $this->prepareInventories($rowData, $inventories);

            /**
             * Prepare products images
             */
            $this->prepareImages($rowData, $imagesData);

            /**
             * Prepare products data for product_flat table
             */
            $this->prepareFlatData($rowData, $flatData);
        }

        $this->saveProducts($products);

        $this->saveChannels($channels);

        $this->saveCustomerGroupPrices($customerGroupPrices);

        $this->saveCategories($categories);

        $this->saveAttributeValues($attributeValues);

        $this->saveInventories($inventories);

        $this->saveImages($imagesData);

        $this->saveFlatData($flatData);

        return true;
    }

    /**
     * Prepare products from current batch
     */
    public function prepareProducts(array $rowData, array &$products): void
    {
        $attributeFamilyId = $this->attributeFamilies
            ->where('code', $rowData['attribute_family_code'])
            ->first()->id;

        if ($this->isSKUExist($rowData['sku'])) {
            $products['update'][$rowData['sku']] = [
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
     * Save products from current batch
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
     * Prepare customer group prices from current batch
     */
    public function prepareCustomerGroupPrices(array $rowData, array &$customerGroupPrices): void
    {
        if (empty($rowData['customer_group_prices'])) {
            return;
        }

        $prices = explode('|', $rowData['customer_group_prices']);

        $customerGroups = $this->getCustomerGroups();

        foreach ($prices as $price) {
            parse_str(str_replace(',', '&', $price), $attributes);

            $customerGroupPrices[$rowData['sku']][] = [
                'qty'               => $attributes['qty'],
                'value_type'        => $attributes['type'],
                'value'             => $attributes['price'],
                'customer_group_id' => $customerGroups->where('code', $attributes['group'])->first()?->id,
            ];
        }
    }

    /**
     * Save customer group prices from current batch
     */
    public function saveCustomerGroupPrices(array $customerGroupPrices): void
    {
        $productCustomerGroupPrices = [];

        foreach ($customerGroupPrices as $sku => $skuCustomerGroupPrices) {
            foreach ($skuCustomerGroupPrices as $customerGroupPrices) {
                $product = $this->skuStorage->get($sku);

                $customerGroupPrices['product_id'] = (int) $product['id'];

                $customerGroupPrices['unique_id'] = implode('|', array_filter([
                    $customerGroupPrices['qty'],
                    $customerGroupPrices['product_id'],
                    $customerGroupPrices['customer_group_id'],
                ]));

                $productCustomerGroupPrices[$customerGroupPrices['unique_id']] = $customerGroupPrices;
            }
        }

        $this->productCustomerGroupPriceRepository->upsert($productCustomerGroupPrices, 'unique_id');
    }

    /**
     * Prepare categories from current batch
     */
    public function prepareCategories(array $rowData, array &$categories): void
    {
        if (empty($rowData['categories'])) {
            return;
        }

        /**
         * Reset the sku categories data to prevent
         * data duplication in case of multiple locales
         */
        $categories[$rowData['sku']] = [];

        $names = explode('/', $rowData['categories'] ?? '');

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
     * Save categories from current batch
     */
    public function saveCategories(array $categories): void
    {
        if (empty($categories)) {
            return;
        }

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
            [
                'product_id',
                'category_id',
            ],
        );
    }

    /**
     * Prepare products channels data
     */
    public function prepareChannels(array $rowData, array &$channels): void
    {
        $channels[$rowData['sku']][] = $this->getChannels()
            ->where('code', $rowData['channel'])
            ->first()
            ->id;
    }

    /**
     * Save channels from current batch
     */
    public function saveChannels(array $channels): void
    {
        $productChannels = [];

        foreach ($channels as $sku => $channelIds) {
            $product = $this->skuStorage->get($sku);

            foreach (array_unique($channelIds) as $channelId) {
                $productChannels[] = [
                    'product_id' => $product['id'],
                    'channel_id' => $channelId,
                ];
            }
        }

        DB::table('product_channels')->upsert(
            $productChannels,
            [
                'product_id',
                'channel_id',
            ],
        );
    }

    /**
     * Save products from current batch
     */
    public function prepareAttributeValues(array $rowData, array &$attributeValues): void
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

            $attributeValues[$rowData['sku']][] = array_merge($attributeTypeValues, [
                'attribute_id'          => $attribute->id,
                $attribute->column_name => $value,
                'channel'               => $attribute->value_per_channel ? $rowData['channel'] : null,
                'locale'                => $attribute->value_per_locale ? $rowData['locale'] : null,
            ]);
        }
    }

    /**
     * Save products from current batch
     */
    public function saveAttributeValues(array $attributeValues): void
    {
        $productAttributeValues = [];

        foreach ($attributeValues as $sku => $skuAttributes) {
            foreach ($skuAttributes as $attribute) {
                $product = $this->skuStorage->get($sku);

                $attribute['product_id'] = (int) $product['id'];

                $attribute['unique_id'] = implode('|', array_filter([
                    $attribute['channel'],
                    $attribute['locale'],
                    $attribute['product_id'],
                    $attribute['attribute_id'],
                ]));

                $productAttributeValues[$attribute['unique_id']] = $attribute;
            }
        }

        $this->productAttributeValueRepository->upsert($productAttributeValues, 'unique_id');
    }

    /**
     * Prepare inventories from current batch
     */
    public function prepareInventories(array $rowData, array &$inventories): void
    {
        if (empty($rowData['inventories'])) {
            return;
        }

        /**
         * Reset the sku inventories data to prevent
         * data duplication in case of multiple locales
         */
        $inventories[$rowData['sku']] = [];

        $inventorySources = explode(',', $rowData['inventories'] ?? '');

        foreach ($inventorySources as $inventorySource) {
            [$inventorySource, $qty] = explode('=', $inventorySource ?? '');

            $inventories[$rowData['sku']][] = [
                'source' => $inventorySource,
                'qty'    => $qty,
            ];
        }
    }

    /**
     * Save inventories from current batch
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
            [
                'product_id',
                'inventory_source_id',
                'vendor_id',
            ],
        );
    }

    /**
     * Prepare images from current batch
     */
    public function prepareImages(array $rowData, array &$imagesData): void
    {
        if (empty($rowData['images'])) {
            return;
        }

        /**
         * Skip the image upload if product is already created
         */
        if ($this->skuStorage->has($rowData['sku'])) {
            return;
        }

        /**
         * Reset the sku images data to prevent
         * data duplication in case of multiple locales
         */
        $imagesData[$rowData['sku']] = [];

        $imageNames = array_map('trim', explode(',', $rowData['images']));

        foreach ($imageNames as $key => $image) {
            $path = 'import/'.$this->import->images_directory_path.'/'.$image;

            if (! Storage::disk('local')->has($path)) {
                continue;
            }

            $imagesData[$rowData['sku']][] = [
                'name' => $image,
                'path' => Storage::disk('local')->path($path),
            ];
        }
    }

    /**
     * Save images from current batch
     */
    public function saveImages(array $imagesData): void
    {
        if (empty($imagesData)) {
            return;
        }

        $productImages = [];

        foreach ($imagesData as $sku => $images) {
            $product = $this->skuStorage->get($sku);

            foreach ($images as $key => $image) {
                $file = new UploadedFile($image['path'], $image['name']);

                $image = (new ImageManager())->make($file)->encode('webp');

                $imageDirectory = $this->productImageRepository->getProductDirectory((object) $product);

                $path = $imageDirectory.'/'.Str::random(40).'.webp';

                $productImages[] = [
                    'type'       => 'images',
                    'path'       => $path,
                    'product_id' => $product['id'],
                    'position'   => $key + 1,
                ];

                Storage::put($path, $image);
            }
        }

        $this->productImageRepository->insert($productImages);
    }

    /**
     * Prepare products flat data
     */
    public function prepareFlatData(array $rowData, array &$flatData): void
    {
        $attributeFamily = $this->attributeFamilies->where('code', $rowData['attribute_family_code'])->first();

        $flatColumns = $this->getProductFlatColumns();

        $data = [];

        foreach ($flatColumns as $column) {
            if (in_array($column, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $data[$column] = $rowData[$column] ?? null;
        }

        $data = array_merge($data, [
            'locale'  => $rowData['locale'],
            'channel' => $rowData['channel'],
        ]);

        $flatData[] = $data;
    }

    /**
     * Save products flat data
     */
    public function saveFlatData(array &$flatData): void
    {
        $products = [];

        foreach ($flatData as $attributes) {
            $product = $this->skuStorage->get($attributes['sku']);

            $products[] = array_merge($attributes, [
                'product_id'          => $product['id'],
                'attribute_family_id' => $product['attribute_family_id'],
            ]);
        }

        $this->productFlatRepository->upsert(
            $products,
            [
                'product_id',
                'channel',
                'locale',
            ],
        );
    }

    /**
     * Prepare configurable variants
     */
    public function prepareConfigurableVariants(array $rowData, array &$configurableVariants): void
    {
        if (
            $rowData['type'] != self::PRODUCT_TYPE_CONFIGURABLE
            && empty($rowData['configurable_variants'])
        ) {
            return;
        }

        $variants = explode('|', $rowData['configurable_variants']);

        foreach ($variants as $variant) {
            parse_str(str_replace(',', '&', $variant), $variantAttributes);

            $configurableVariants[$rowData['sku']][$variantAttributes['sku']] = Arr::except($variantAttributes, 'sku');
        }
    }

    /**
     * Save configurable variants from current batch
     */
    public function saveConfigurableVariants(array $configurableVariants): void
    {
        if (empty($configurableVariants)) {
            return;
        }

        $variantSkus = array_map('array_keys', $configurableVariants);

        /**
         * Load not loaded SKUs to the sku storage
         */
        $this->loadUnloadedSKUs(array_unique(Arr::flatten($variantSkus)));

        $superAttributeOptions = $this->getSuperAttributeOptions($configurableVariants);

        $parentAssociations = [];

        $superAttributes = [];

        $superAttributeValues = [];

        foreach ($configurableVariants as $sku => $variants) {
            $product = $this->skuStorage->get($sku);

            foreach ($variants as $variantSku => $variantSuperAttributes) {
                $variant = $this->skuStorage->get($variantSku);

                $parentAssociations[] = [
                    'sku'       => $variantSku,
                    'parent_id' => $product['id'],
                ];

                foreach ($variantSuperAttributes as $superAttributeCode => $optionLabel) {
                    $attribute = $this->attributes->where('code', $superAttributeCode)->first();

                    $attributeOption = $superAttributeOptions->where('attribute_id', $attribute->id)
                        ->where('admin_name', $optionLabel)
                        ->first();

                    $attributeTypeValues = array_fill_keys(array_values($attribute->attributeTypeFields), null);

                    $attributeTypeValues = array_merge($attributeTypeValues, [
                        'product_id'            => $variant['id'],
                        'attribute_id'          => $attribute->id,
                        $attribute->column_name => $attributeOption->id,
                        'channel'               => null,
                        'locale'                => null,
                    ]);

                    $attributeTypeValues['unique_id'] = implode('|', array_filter([
                        $attributeTypeValues['channel'],
                        $attributeTypeValues['locale'],
                        $attributeTypeValues['product_id'],
                        $attributeTypeValues['attribute_id'],
                    ]));

                    $superAttributeValues[] = $attributeTypeValues;
                }
            }

            $superAttributeCodes = array_keys(current($variants));

            foreach ($superAttributeCodes as $attributeCode) {
                $attribute = $this->attributes->where('code', $attributeCode)->first();

                $superAttributes[] = [
                    'product_id'   => $product['id'],
                    'attribute_id' => $attribute->id,
                ];
            }
        }

        /**
         * Save the variants parent associations
         */
        $this->productRepository->upsert($parentAssociations, 'sku');

        /**
         * Save super attributes associations for configurable products
         */
        DB::table('product_super_attributes')->upsert(
            $superAttributes,
            [
                'product_id',
                'attribute_id',
            ],
        );

        /**
         * Save variants super attributes option values
         */
        $this->productAttributeValueRepository->upsert($superAttributeValues, 'unique_id');
    }

    /**
     * Prepare group associations
     */
    public function prepareGroupAssociations(array $rowData, array &$groupAssociations): void
    {
        if (
            $rowData['type'] != self::PRODUCT_TYPE_GROUPED
            && empty($rowData['associated_skus'])
        ) {
            return;
        }

        $associatedSkus = explode(',', $rowData['associated_skus']);

        foreach ($associatedSkus as $row) {
            [$sku, $qty] = explode('=', $row);

            $groupAssociations[$rowData['sku']][$sku] = $qty;
        }
    }

    /**
     * Save links from current batch
     */
    public function saveGroupAssociations(array $groupAssociations): void
    {
        if (empty($groupAssociations)) {
            return;
        }

        $associatedSkus = array_map('array_keys', $groupAssociations);

        /**
         * Load not loaded SKUs to the sku storage
         */
        $this->loadUnloadedSKUs(array_unique(Arr::flatten($associatedSkus)));

        $associatedProducts = [];

        foreach ($groupAssociations as $sku => $associatedSkus) {
            $product = $this->skuStorage->get($sku);

            $sortOrder = 0;

            foreach ($associatedSkus as $associatedSku => $qty) {
                $associatedProduct = $this->skuStorage->get($associatedSku);

                if (! $associatedProduct) {
                    continue;
                }

                $associatedProducts[] = [
                    'qty'                   => $qty,
                    'sort_order'            => $sortOrder++,
                    'product_id'            => $product['id'],
                    'associated_product_id' => $associatedProduct['id'],
                ];
            }
        }

        $this->productGroupedProductRepository->upsert(
            $associatedProducts,
            [
                'product_id',
                'associated_product_id',
            ],
        );
    }

    /**
     * Prepare bundle options from current batch
     */
    public function prepareBundleOptions(array $rowData, array &$bundleOptions): void
    {
        if (
            $rowData['type'] != self::PRODUCT_TYPE_BUNDLE
            && empty($rowData['bundle_options'])
        ) {
            return;
        }

        $options = explode('|', $rowData['bundle_options']);

        $optionSortOrder = 0;

        foreach ($options as $option) {
            parse_str(str_replace(',', '&', $option), $attributes);

            if (! isset($bundleOptions[$rowData['sku']][$rowData['locale']][$attributes['name']])) {
                $productSortOrder = 0;

                $bundleOptions[$rowData['sku']][$rowData['locale']][$attributes['name']]['attributes'] = [
                    'type'        => $attributes['type'],
                    'is_required' => $attributes['required'],
                    'sort_order'  => $optionSortOrder++,
                ];
            }

            $bundleOptions[$rowData['sku']][$rowData['locale']][$attributes['name']]['skus'][$attributes['sku']] = [
                'qty'        => $attributes['qty'],
                'is_default' => $attributes['default'],
                'sort_order' => $productSortOrder++,
            ];
        }
    }

    /**
     * Save bundle options from current batch
     */
    public function saveBundleOptions(array &$bundleOptions): void
    {
        if (empty($bundleOptions)) {
            return;
        }

        $associatedSkus = [];

        foreach (data_get($bundleOptions, '*.*.*.skus') as $options) {
            $associatedSkus = array_merge($associatedSkus, array_keys($options));
        }

        /**
         * Load not loaded SKUs to the sku storage
         */
        $this->loadUnloadedSKUs(array_unique(Arr::flatten($associatedSkus)));

        $upsertData = [];

        $existingOptions = $this->getExistingBundleOptions($bundleOptions);

        foreach ($bundleOptions as $sku => $localeOptions) {
            $product = $this->skuStorage->get($sku);

            $createdUpdatedOptionIds = [];

            foreach (current($localeOptions) as $optionName => $option) {
                $optionAlreadyCreated = true;

                $bundleOption = $existingOptions->where('product_id', $product['id'])
                    ->where('label', $optionName)
                    ->first();

                if (! $bundleOption) {
                    $bundleOption = $this->productBundleOptionRepository->create([
                        'product_id'  => $product['id'],
                        'type'        => $option['attributes']['type'],
                        'is_required' => $option['attributes']['is_required'],
                        'sort_order'  => $option['attributes']['sort_order'],
                    ]);
                } else {
                    $upsertData['options'][] = [
                        'id'          => $bundleOption->id,
                        'product_id'  => $product['id'],
                        'type'        => $option['attributes']['type'],
                        'is_required' => $option['attributes']['is_required'],
                        'sort_order'  => $option['attributes']['sort_order'],
                    ];
                }

                $createdUpdatedOptionIds[] = $bundleOption->id;

                foreach ($option['skus'] as $associatedSKU => $optionProduct) {
                    $associatedProduct = $this->skuStorage->get($associatedSKU);

                    $upsertData['products'][] = [
                        'product_bundle_option_id' => $bundleOption->id,
                        'product_id'               => $associatedProduct['id'],
                        'qty'                      => $optionProduct['qty'],
                        'is_default'               => $optionProduct['is_default'],
                        'sort_order'               => $optionProduct['sort_order'],
                    ];
                }
            }

            /**
             * Prepare translation for bundle options
             */
            foreach ($localeOptions as $locale => $options) {
                $key = 0;

                foreach ($options as $optionName => $option) {
                    $bundleOptionId = $createdUpdatedOptionIds[$key++] ?? null;

                    if (! $bundleOptionId) {
                        continue;
                    }

                    $upsertData['translations'][] = [
                        'product_bundle_option_id' => $bundleOptionId,
                        'label'                    => $optionName,
                        'locale'                   => $locale,
                    ];
                }
            }
        }

        if (! empty($upsertData['options'])) {
            $this->productBundleOptionRepository->upsert($upsertData['options'], 'id');
        }

        if (! empty($upsertData['products'])) {
            DB::table('product_bundle_option_translations')->upsert(
                $upsertData['translations'],
                [
                    'product_bundle_option_id',
                    'label',
                    'locale',
                ],
            );
        }

        if (! empty($upsertData['products'])) {
            $this->productBundleOptionProductRepository->upsert(
                $upsertData['products'],
                [
                    'product_id',
                    'product_bundle_option_id',
                ],
            );
        }
    }

    /**
     * Prepare links from current batch
     */
    public function prepareLinks(array $rowData, array &$links): void
    {
        $linkTableMapping = [
            'related'    => 'product_relations',
            'cross_sell' => 'product_cross_sells',
            'up_sell'    => 'product_up_sells',
        ];

        foreach ($linkTableMapping as $type => $table) {
            if (empty($rowData[$type.'_skus'])) {
                continue;
            }

            /**
             * Reset the sku links data to prevent
             * data duplication in case of multiple locales
             */
            $links[$table][$rowData['sku']] = [];

            foreach (explode(',', $rowData[$type.'_skus'] ?? '') as $sku) {
                $links[$table][$rowData['sku']][] = $sku;
            }
        }
    }

    /**
     * Save links from current batch
     */
    public function saveLinks(array $links): void
    {
        /**
         * Load not loaded SKUs to the sku storage
         */
        $this->loadUnloadedSKUs(array_unique(Arr::flatten($links)));

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
                [
                    'parent_id',
                    'child_id',
                ],
            );
        }
    }

    /**
     * Returns existing bundled options of current batch
     */
    public function getExistingBundleOptions(array $bundleOptions): mixed
    {
        $queryBuilder = $this->productBundleOptionRepository
            ->select('product_bundle_options.id', 'label', 'locale', 'product_id', 'type', 'is_required', 'sort_order')
            ->leftJoin('product_bundle_option_translations', 'product_bundle_option_translations.product_bundle_option_id', 'product_bundle_options.id');

        foreach ($bundleOptions as $sku => $localeOptions) {
            $product = $this->skuStorage->get($sku);

            foreach ($localeOptions as $locale => $options) {
                foreach ($options as $optionName => $option) {
                    $queryBuilder->orWhere(function ($query) use ($product, $optionName, $locale) {
                        $query->where('product_bundle_options.product_id', $product['id'])
                            ->where('product_bundle_option_translations.label', $optionName)
                            ->where('product_bundle_option_translations.locale', $locale);
                    });
                }
            }
        }

        return $queryBuilder->get();
    }

    /**
     * Returns super attributes options of current batch
     */
    public function getSuperAttributeOptions(array $variants): mixed
    {
        $optionLabels = array_unique(Arr::flatten($variants));

        return $this->attributeOptionRepository->findWhereIn('admin_name', $optionLabels);
    }

    /**
     * Save links
     */
    public function loadUnloadedSKUs(array $skus): void
    {
        $notLoadedSkus = [];

        foreach ($skus as $sku) {
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
     * Retrieve customer groups
     */
    public function getCustomerGroups(): mixed
    {
        if (! empty($this->customerGroups)) {
            return $this->customerGroups;
        }

        return $this->customerGroups = $this->customerGroupRepository->all();
    }

    /**
     * Retrieve channels
     */
    public function getChannels(): mixed
    {
        if (! empty($this->channels)) {
            return $this->channels;
        }

        return $this->channels = $this->channelRepository->all();
    }

    /**
     * Retrieve product_flat table columns
     */
    protected function getProductFlatColumns(): array
    {
        if (! empty($this->productFlatColumns)) {
            return $this->productFlatColumns;
        }

        return $this->productFlatColumns = Schema::getColumnListing('product_flat');
    }

    /**
     * Check if SKU exists
     */
    public function isSKUExist(string $sku): bool
    {
        return $this->skuStorage->has($sku);
    }

    /**
     * Prepare row data to save into the database
     */
    protected function prepareRowForDb(array $rowData): array
    {
        $rowData = parent::prepareRowForDb($rowData);

        $rowData['locale'] = $rowData['locale'] ?? app()->getLocale();

        $rowData['channel'] = $rowData['channel'] ?? core()->getDefaultChannelCode();

        return $rowData;
    }
}
