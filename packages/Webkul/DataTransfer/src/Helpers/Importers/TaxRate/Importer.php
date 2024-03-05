<?php

namespace Webkul\DataTransfer\Helpers\Importers\TaxRate;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;
use Webkul\Tax\Repositories\TaxRateRepository;

class Importer extends AbstractImporter
{
    /**
     * Error code for non existing identifier
     */
    const ERROR_IDENTIFIER_NOT_FOUND_FOR_DELETE = 'identifier_not_found_to_delete';

    /**
     * Error code for duplicated identifier
     */
    const ERROR_DUPLICATE_IDENTIFIER = 'duplicated_identifier';

    /**
     * Permanent entity columns
     */
    protected array $validColumnNames = [
        'identifier',
        'is_zip_range',
        'zip_code',
        'zip_from',
        'zip_to',
        'state',
        'country',
        'tax_rate',
    ];

    /**
     * Error message templates
     */
    protected array $messages = [
        self::ERROR_IDENTIFIER_NOT_FOUND_FOR_DELETE => 'data_transfer::app.importers.tax-rates.validation.errors.identifier-not-found',
        self::ERROR_DUPLICATE_IDENTIFIER            => 'data_transfer::app.importers.tax-rates.validation.errors.duplicate-identifier',
    ];

    /**
     * Permanent entity columns
     *
     * @var string[]
     */
    protected $permanentAttributes = ['identifier'];

    /**
     * Permanent entity column
     */
    protected string $masterAttributeCode = 'identifier';

    /**
     * Identifiers storage
     */
    protected array $identifiers = [];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected ImportBatchRepository $importBatchRepository,
        protected TaxRateRepository $taxRateRepository,
        protected Storage $taxRateStorage
    ) {
        parent::__construct($importBatchRepository);
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
     * Validate data.
     */
    public function validateData(): void
    {
        $this->taxRateStorage->init();

        parent::validateData();
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
            if (! $this->isIdentifierExist($rowData['identifier'])) {
                $this->skipRow($rowNumber, self::ERROR_IDENTIFIER_NOT_FOUND_FOR_DELETE);

                return false;
            }

            return true;
        }

        /**
         * Validate product attributes
         */
        $validator = Validator::make($rowData, [
            'identifier'   => 'required|string',
            'is_zip_range' => 'sometimes|boolean',
            'zip_code'     => 'nullable|required_if:is_zip_range,0',
            'zip_from'     => 'nullable|required_if:is_zip_range,1',
            'zip_to'       => 'nullable|required_if:is_zip_range,1',
            'country'      => 'required|string',
            'tax_rate'     => 'required|numeric|min:0.0001',
        ]);

        if ($validator->fails()) {
            $failedAttributes = $validator->failed();

            foreach ($validator->errors()->getMessages() as $attributeCode => $message) {
                $errorCode = array_key_first($failedAttributes[$attributeCode] ?? []);

                $this->skipRow($rowNumber, $errorCode, $attributeCode, current($message));
            }
        }

        /**
         * Check if identifier is unique
         */
        if (! in_array($rowData['identifier'], $this->identifiers)) {
            $this->identifiers[] = $rowData['identifier'];
        } else {
            $message = sprintf(
                trans($this->messages[self::ERROR_DUPLICATE_IDENTIFIER]),
                $rowData['identifier']
            );

            $this->skipRow($rowNumber, self::ERROR_DUPLICATE_IDENTIFIER, 'identifier', $message);
        }

        return ! $this->errorHelper->isRowInvalid($rowNumber);
    }

    /**
     * Start the import process
     */
    public function importBatch(ImportBatchContract $batch): bool
    {
        Event::dispatch('data_transfer.imports.batch.import.before', $batch);

        if ($batch->import->action == Import::ACTION_DELETE) {
            $this->deleteTaxRates($batch);
        } else {
            $this->saveTaxRatesData($batch);
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
     * Delete tax rates from current batch
     */
    protected function deleteTaxRates(ImportBatchContract $batch): bool
    {
        /**
         * Load tax rates storage with batch identifiers
         */
        $this->taxRateStorage->load(Arr::pluck($batch->data, 'identifier'));

        $idsToDelete = [];

        foreach ($batch->data as $rowData) {
            if (! $this->isIdentifierExist($rowData['identifier'])) {
                continue;
            }

            $idsToDelete[] = $this->taxRateStorage->get($rowData['identifier']);
        }

        $idsToDelete = array_unique($idsToDelete);

        $this->deletedItemsCount = count($idsToDelete);

        $this->taxRateRepository->deleteWhere([['id', 'IN', $idsToDelete]]);

        return true;
    }

    /**
     * Save tax rates from current batch
     */
    protected function saveTaxRatesData(ImportBatchContract $batch): bool
    {
        /**
         * Load tax rate storage with batch identifier
         */
        $this->taxRateStorage->load(Arr::pluck($batch->data, 'identifier'));

        $taxRates = [];

        foreach ($batch->data as $rowData) {
            /**
             * Prepare tax rates for import
             */
            if ($this->isIdentifierExist($rowData['identifier'])) {
                $taxRates['update'][$rowData['identifier']] = $rowData;
            } else {
                $taxRates['insert'][$rowData['identifier']] = array_merge($rowData, [
                    'created_at' => $rowData['created_at'] ?? now(),
                    'updated_at' => $rowData['updated_at'] ?? now(),
                ]);
            }
        }

        if (! empty($taxRates['update'])) {
            $this->updatedItemsCount += count($taxRates['update']);

            $this->taxRateRepository->upsert(
                $taxRates['update'],
                $this->masterAttributeCode
            );
        }

        if (! empty($taxRates['insert'])) {
            $this->createdItemsCount += count($taxRates['insert']);

            $this->taxRateRepository->insert($taxRates['insert']);
        }

        return true;
    }

    /**
     * Check if identifier exists
     */
    public function isIdentifierExist(string $identifier): bool
    {
        return $this->taxRateStorage->has($identifier);
    }

    /**
     * Prepare row data to save into the database
     */
    protected function prepareRowForDb(array $rowData): array
    {
        $rowData = parent::prepareRowForDb($rowData);

        $rowData['is_zip'] = $rowData['is_zip_range'] ?? 0;

        return Arr::except($rowData, 'is_zip_range');
    }
}
