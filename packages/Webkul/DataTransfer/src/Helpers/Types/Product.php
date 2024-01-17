<?php

namespace Webkul\DataTransfer\Helpers\Types;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\Slug;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;

class Product extends AbstractType
{
    const ERROR_INVALID_TYPE = 'invalid_product_type';

    const ERROR_SKU_NOT_FOUND_FOR_DELETE = 'sku_not_found_to_delete';

    const ERROR_DUPLICATE_URL_KEY = 'duplicated_url_key';

    const ERROR_INVALID_ATTRIBUTE_FAMILY_CODE = 'attribute_family_code_not_found';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::ERROR_INVALID_TYPE                  => 'Product type is invalid or not supported',
        self::ERROR_SKU_NOT_FOUND_FOR_DELETE      => 'Product with specified SKU not found',
        self::ERROR_DUPLICATE_URL_KEY             => 'URL key: \'%s\' was already generated for an item with the SKU: \'%s\'.',
        self::ERROR_INVALID_ATTRIBUTE_FAMILY_CODE => 'Invalid value for attribute family column (attribute family doesn\'t exist?)',
    ];

    /**
     * Permanent entity columns
     *
     * @var string[]
     */
    protected $permanentAttributes = ['sku'];

    /**
     * @var string
     */
    protected $masterAttributeCode = 'sku';

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
    protected $productTypeFamilyAttributes = [];

    /**
     * Permanent entity columns
     *
     * @var array
     */
    protected $urlKeys = [];

    /**
     * Permanent entity columns
     *
     * @var string[]
     */
    protected $validColumnNames = [
        'locale',
        'type',
        'attribute_family_code',
        'categories',
        'tax_category_name',
        'qty',
        'related_skus',
        'cross_sell_skus',
        'up_sell_skus',
        'bundle_options',
        'associated_skus',
        'created_at',
        'updated_at',
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
        protected ProductRepository $productRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        parent::__construct($importBatchRepository);

        $this->initAttributes();
    }

    /**
     * Save validated batches
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
     *
     * @return array
     */
    public function getValidColumnNames(): array
    {
        return $this->validColumnNames;
    }

    /**
     * Save validated batches
     */
    protected function saveValidatedBatches(): void
    {
        $source = $this->getSource();

        $source->rewind();

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
    }

    /**
     * Check that url_keys are not assigned to other products in DB
     *
     * @return void
     */
    protected function checkForDuplicateUrlKeys(): void
    {
        $products = $this->productRepository
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
            $this->skipRow($rowNumber, self::ERROR_INVALID_PRODUCT_TYPE, 'type');

            return false;
        }

        /**
         * Check if attribute family exists
         */
        if (! $this->attributeFamilies->where('code', $rowData['attribute_family_code'])->first()) {
            $this->skipRow($rowNumber, self::ERROR_INVALID_ATTRIBUTE_FAMILY_CODE, 'attribute_family_code');

            return false;
        }

        /**
         * Validate product attributes
         */
        $validator = Validator::make($rowData, $this->getValidationRules($rowData));

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
                    $count = $this->productAttributeValueRepository
                        ->where($attribute->column_name, $rowData[$attribute->code])
                        ->where('attribute_id', '=', $attribute->id)
                        ->where('product_id', '!=', $productId)
                        ->count('id');
            
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
     * Retrieve product type family attributes
     */
    public function getProductTypeFamilyAttributes(string $type, string $attributeFamilyCode): mixed
    {
        if (isset($this->productTypeFamilyAttributes[$type][$attributeFamilyCode])) {
            return $this->productTypeFamilyAttributes[$type][$attributeFamilyCode];
        }

        $attributeFamily = $this->attributeFamilies->where('code', $attributeFamilyCode)->first();

        $product = ProductModel::make([
            'type'                => $type,
            'attribute_family_id' => $attributeFamily->id,
        ]);

        return $this->productTypeFamilyAttributes[$type][$attributeFamilyCode] = $product->getEditableAttributes();
    }

    /**
     * Prepare row data to save into the database
     */
    protected function prepareRowForDb(array $rowData): array
    {
        return $rowData;
    }


    /**
     * Check if SKU exists
     */
    public function isSKUExist(string $sku): bool
    {
        return $this->productRepository->findOneByField('sku', $sku) ? true : false;
    }

    /**
     * Add row as skipped
     *
     * @param  int|null  $rowNumber
     * @param  string  $errorCode
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