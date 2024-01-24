<?php

namespace Webkul\DataTransfer\Helpers\Types;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Contracts\ImportBatch as ImportBatchContract;
use Webkul\DataTransfer\Jobs\ImportBatch;
use Webkul\DataTransfer\Jobs\ImportCompleted;
use Webkul\DataTransfer\Jobs\ImportLinkBatch;
use Webkul\DataTransfer\Jobs\ImportLinking;
use Webkul\DataTransfer\Jobs\ImportStarted;
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
    public const ERROR_CODE_COLUMN_NAME_INVALID = 'column_came_invalid';

    /**
     * Error code for attribute not valid.
     */
    public const ERROR_CODE_ATTRIBUTE_NOT_VALID = 'attribute_not_valid';

    /**
     * Error code for duplicate unique attribute.
     */
    public const ERROR_CODE_DUPLICATE_UNIQUE_ATTRIBUTE = 'duplicate_unique_attribute';

    /**
     * Error code for illegal characters.
     */
    public const ERROR_CODE_ILLEGAL_CHARACTERS = 'illegal_Characters';

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
     * Error code for invalid attribute type.
     */
    public const ERROR_INVALID_ATTRIBUTE_TYPE = 'invalid_attribute_type';

    /**
     * Error code for invalid attribute option.
     */
    public const ERROR_INVALID_ATTRIBUTE_OPTION = 'absent_attribute_option';

    /**
     * Error message templates.
     */
    protected array $errorMessageTemplates = [
        self::ERROR_CODE_SYSTEM_EXCEPTION           => 'An unexpected system error occurred.',
        self::ERROR_CODE_COLUMN_NOT_FOUND           => 'Required columns not found: %s.',
        self::ERROR_CODE_COLUMN_EMPTY_HEADER        => 'Columns number "%s" have empty headers.',
        self::ERROR_CODE_COLUMN_NAME_INVALID        => 'Invalid column names: "%s".',
        self::ERROR_CODE_ATTRIBUTE_NOT_VALID        => "Please correct the value for attribute '%s'.",
        self::ERROR_CODE_DUPLICATE_UNIQUE_ATTRIBUTE => "Duplicate Unique Attribute for '%s'.",
        self::ERROR_CODE_ILLEGAL_CHARACTERS         => 'Illegal character used for attribute %s.',
        self::ERROR_CODE_INVALID_ATTRIBUTE          => 'Header contains invalid attribute(s): "%s".',
        self::ERROR_CODE_WRONG_QUOTES               => 'Curly quotes used instead of straight quotes.',
        self::ERROR_CODE_COLUMNS_NUMBER             => 'Number of columns does not correspond to the number of rows in the header.',
        self::ERROR_INVALID_ATTRIBUTE_TYPE          => 'Value for attribute \'%s\' contains an incorrect value.',
        self::ERROR_INVALID_ATTRIBUTE_OPTION        => 'Value for attribute %s contains an incorrect value. See acceptable values specified in Admin settings.',
    ];

    public const BATCH_SIZE = 50;

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
     * Resource link needed
     */
    protected bool $linkNeeded = false;

    /**
     * Valid column names
     */
    protected array $validColumnNames = [];

    /**
     * Rows which will be skipped during import
     */
    protected array $skippedRows = [];

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
     * Initialize Product error templates
     */
    protected function initErrorTemplates(): void
    {
        foreach ($this->errorMessageTemplates as $errorCode => $template) {
            $this->errorHelper->addErrorMessageTemplate($errorCode, $template);
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

        $this->initErrorTemplates();

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
    public function importData(?int $importBatchId = null): bool
    {
        if ($importBatchId) {
            $batch = $this->importBatchRepository->find($importBatchId);

            $this->importBatch($batch);

            return true;
        }

        $importBatches = [];

        $importLinkBatches = [];

        foreach ($this->import->batches as $batch) {
            $importBatches[] = new ImportBatch($batch);

            $importLinkBatches[] = new ImportLinkBatch($batch);
        }

        Bus::chain([
            new ImportStarted($this->import),
            Bus::batch($importBatches),

            new ImportLinking($this->import),
            Bus::batch($importLinkBatches),

            new ImportCompleted($this->import),
        ])->dispatch();

        return true;
    }

    /**
     * Link resource data.
     */
    public function linkData(?int $importBatchId = null): bool
    {
        $batch = $this->importBatchRepository->find($importBatchId);

        $this->importLinksBatch($batch);

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
}
