<?php

namespace Webkul\DataTransfer\Helpers\Types;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Jobs\Import\Completed as CompletedJob;
use Webkul\DataTransfer\Jobs\Import\ImportBatch as ImportBatchJob;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;

abstract class AbstractType
{
    /**
     * Error code for system exception.
     */
    public const ERROR_CODE_SYSTEM_EXCEPTION = 'system_exception';

    /**
     * Error code for column not found.
     */
    public const ERROR_CODE_COLUMN_NOT_FOUND = 'column_not_found';

    /**
     * Error code for column empty header.
     */
    public const ERROR_CODE_COLUMN_EMPTY_HEADER = 'column_empty_header';

    /**
     * Error code for column name invalid.
     */
    public const ERROR_CODE_COLUMN_NAME_INVALID = 'column_name_invalid';

    /**
     * Error code for invalid attribute.
     */
    public const ERROR_CODE_INVALID_ATTRIBUTE = 'invalid_attribute_name';

    /**
     * Error code for wrong quotes.
     */
    public const ERROR_CODE_WRONG_QUOTES = 'wrong_quotes';

    /**
     * Error code for wrong columns number.
     */
    public const ERROR_CODE_COLUMNS_NUMBER = 'wrong_columns_number';

    /**
     * Error message templates.
     */
    protected array $errorMessages = [
        self::ERROR_CODE_SYSTEM_EXCEPTION    => 'data_transfer::app.validation.errors.system',
        self::ERROR_CODE_COLUMN_NOT_FOUND    => 'data_transfer::app.validation.errors.column-not-found',
        self::ERROR_CODE_COLUMN_EMPTY_HEADER => 'data_transfer::app.validation.errors.column-empty-headers',
        self::ERROR_CODE_COLUMN_NAME_INVALID => 'data_transfer::app.validation.errors.column-name-invalid',
        self::ERROR_CODE_INVALID_ATTRIBUTE   => 'data_transfer::app.validation.errors.invalid-attribute',
        self::ERROR_CODE_WRONG_QUOTES        => 'data_transfer::app.validation.errors.wrong-quotes',
        self::ERROR_CODE_COLUMNS_NUMBER      => 'data_transfer::app.validation.errors.column-numbers',
    ];

    public const BATCH_SIZE = 50;

    /**
     * Is linking required
     */
    protected bool $linkingRequired = false;

    /**
     * Is indexing required
     */
    protected bool $indexingRequired = false;

    /**
     * Error helper instance.
     *
     * @var \Webkul\DataTransfer\Helpers\Error
     */
    protected $errorHelper;

    /**
     * Import instance.
     */
    protected ImportContract $import;

    /**
     * Source instance.
     *
     * @var \Webkul\DataTransfer\Helpers\Source
     */
    protected $source;

    /**
     * Valid column names
     */
    protected array $validColumnNames = [];

    /**
     * Array of numbers of validated rows as keys and boolean TRUE as values
     */
    protected array $validatedRows = [];

    /**
     * Number of rows processed by validation
     */
    protected int $processedRowsCount = 0;

    /**
     * Number of created items
     */
    protected int $createdItemsCount = 0;

    /**
     * Number of updated items
     */
    protected int $updatedItemsCount = 0;

    /**
     * Number of deleted items
     */
    protected int $deletedItemsCount = 0;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected ImportBatchRepository $importBatchRepository)
    {
    }

    /**
     * Validate data row
     */
    abstract public function validateRow(array $rowData, int $rowNumber): bool;

    /**
     * Import data rows
     */
    abstract public function importBatch(ImportBatchContract $importBatchContract): bool;

    /**
     * Initialize Product error messages
     */
    protected function initErrorMessages(): void
    {
        foreach ($this->errorMessages as $errorCode => $message) {
            $this->errorHelper->addErrorMessage($errorCode, trans($message));
        }
    }

    /**
     * Import instance.
     */
    public function setImport(ImportContract $import): self
    {
        $this->import = $import;

        return $this;
    }

    /**
     * Import instance.
     *
     * @param  \Webkul\DataTransfer\Helpers\Source  $errorHelper
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Import instance.
     *
     * @param  \Webkul\DataTransfer\Helpers\Error  $errorHelper
     */
    public function setErrorHelper($errorHelper): self
    {
        $this->errorHelper = $errorHelper;

        $this->initErrorMessages();

        return $this;
    }

    /**
     * Import instance.
     *
     * @return \Webkul\DataTransfer\Helpers\Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Validate data.
     */
    public function validateData(): void
    {
        Event::dispatch('data_transfer.imports.validate.before', $this->import);

        $errors = [];

        $absentColumns = array_diff($this->permanentAttributes, $this->getSource()->getColumnNames());

        if (! empty($absentColumns)) {
            $errors[self::ERROR_CODE_COLUMN_NOT_FOUND] = $absentColumns;
        }

        foreach ($this->getSource()->getColumnNames() as $columnNumber => $columnName) {
            if (empty($columnName)) {
                $errors[self::ERROR_CODE_COLUMN_EMPTY_HEADER][] = $columnNumber + 1;
            } elseif (! preg_match('/^[a-z][a-z0-9_]*$/', $columnName)) {
                $errors[self::ERROR_CODE_COLUMN_NAME_INVALID][] = $columnName;
            } elseif (! in_array($columnName, $this->getValidColumnNames())) {
                $errors[self::ERROR_CODE_INVALID_ATTRIBUTE][] = $columnName;
            }
        }

        /**
         * Add Columns Errors
         */
        foreach ($errors as $errorCode => $error) {
            $this->addErrors($errorCode, $error);
        }

        if (! $this->errorHelper->getErrorsCount()) {
            $this->saveValidatedBatches();
        }

        Event::dispatch('data_transfer.imports.validate.after', $this->import);
    }

    /**
     * Save validated batches
     */
    protected function saveValidatedBatches(): self
    {
        $source = $this->getSource();

        $batchRows = [];

        $source->rewind();

        /**
         * Clean previous saved batches
         */
        $this->importBatchRepository->deleteWhere([
            'import_id' => $this->import->id,
        ]);

        while (
            $source->valid()
            || count($batchRows)
        ) {
            if (
                count($batchRows) == self::BATCH_SIZE
                || ! $source->valid()
            ) {
                $this->importBatchRepository->create([
                    'import_id' => $this->import->id,
                    'data'      => $batchRows,
                ]);

                $batchRows = [];
            }

            if ($source->valid()) {
                $rowData = $source->current();

                if ($this->validateRow($rowData, $source->getCurrentRowNumber())) {
                    $batchRows[] = $this->prepareRowForDb($rowData);
                }

                $this->processedRowsCount++;

                $source->next();
            }
        }

        return $this;
    }

    /**
     * Start the import process
     */
    public function importData(?ImportBatchContract $importBatch = null): bool
    {
        if ($importBatch) {
            $this->importBatch($importBatch);

            return true;
        }

        $importBatches = [];

        foreach ($this->import->batches as $batch) {
            $importBatches[] = new ImportBatchJob($batch);
        }

        Bus::chain([
            Bus::batch($importBatches),

            new CompletedJob($this->import),
        ])->dispatch();

        return true;
    }

    /**
     * Link resource data.
     */
    public function linkData(ImportBatchContract $importBatch): bool
    {
        $this->linkBatch($importBatch);

        return true;
    }

    /**
     * Index resource data.
     */
    public function indexData(ImportBatchContract $importBatch): bool
    {
        $this->indexBatch($importBatch);

        return true;
    }

    /**
     * Add errors to error aggregator
     */
    protected function addErrors(string $code, mixed $errors): void
    {
        $this->errorHelper->addError(
            $code,
            null,
            implode('", "', $errors)
        );
    }

    /**
     * Add error with corresponding current data source row number.
     */
    public function addRowError(string $code, int $rowNumber, ?string $columnName = null, ?string $message = null): self
    {
        $this->errorHelper->addError(
            $code,
            $rowNumber,
            $columnName,
            $message
        );

        return $this;
    }

    /**
     * Returns number of checked rows
     */
    public function getProcessedRowsCount(): int
    {
        return $this->processedRowsCount;
    }

    /**
     * Returns number of created items count
     */
    public function getCreatedItemsCount(): int
    {
        return $this->createdItemsCount;
    }

    /**
     * Returns number of updated items count
     */
    public function getUpdatedItemsCount(): int
    {
        return $this->updatedItemsCount;
    }

    /**
     * Returns number of deleted items count
     */
    public function getDeletedItemsCount(): int
    {
        return $this->deletedItemsCount;
    }

    /**
     * Is linking resource required for the import operation
     */
    public function isLinkingRequired(): bool
    {
        return $this->linkingRequired;
    }

    /**
     * Is indexing resource required for the import operation
     */
    public function isIndexingRequired(): bool
    {
        return $this->indexingRequired;
    }
}
