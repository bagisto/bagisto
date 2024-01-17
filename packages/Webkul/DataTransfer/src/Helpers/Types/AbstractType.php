<?php

namespace Webkul\DataTransfer\Helpers\Types;

use Webkul\DataTransfer\Contracts\Import as ImportContract;
use Webkul\DataTransfer\Repositories\ImportBatchRepository;

abstract class AbstractType
{
    public const ERROR_CODE_SYSTEM_EXCEPTION = 'system_exception';

    public const ERROR_CODE_COLUMN_NOT_FOUND = 'column_not_found';

    public const ERROR_CODE_COLUMN_EMPTY_HEADER = 'column_empty_header';

    public const ERROR_CODE_COLUMN_NAME_INVALID = 'column_came_invalid';

    public const ERROR_CODE_ATTRIBUTE_NOT_VALID = 'attribute_not_valid';

    public const ERROR_CODE_DUPLICATE_UNIQUE_ATTRIBUTE = 'duplicate_unique_attribute';

    public const ERROR_CODE_ILLEGAL_CHARACTERS = 'illegal_Characters';

    public const ERROR_CODE_INVALID_ATTRIBUTE = 'invalid_attribute_name';

    public const ERROR_CODE_WRONG_QUOTES = 'wrong_quotes';

    public const ERROR_CODE_COLUMNS_NUMBER = 'wrong_columns_number';

    public const ERROR_INVALID_ATTRIBUTE_TYPE = 'invalid_attribute_type';

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
        self::ERROR_CODE_ILLEGAL_CHARACTERS         => "Illegal character used for attribute %s.",
        self::ERROR_CODE_INVALID_ATTRIBUTE          => 'Header contains invalid attribute(s): "%s".',
        self::ERROR_CODE_WRONG_QUOTES               => "Curly quotes used instead of straight quotes.",
        self::ERROR_CODE_COLUMNS_NUMBER             => "Number of columns does not correspond to the number of rows in the header.",
        self::ERROR_INVALID_ATTRIBUTE_TYPE          => 'Value for attribute \'%s\' contains an incorrect value.',
        self::ERROR_INVALID_ATTRIBUTE_OPTION        => "Value for attribute %s contains an incorrect value. See acceptable values specified in Admin settings."
    ];

    /**
     * Error helper instance.
     * 
     * @var \Webkul\DataTransfer\Helpers\Error
     */
    protected $errorHelper;

    /**
     * @var \Webkul\DataTransfer\Contracts\Import
     */
    protected $import;

    /**
     * @var \Webkul\DataTransfer\Helpers\Source
     */
    protected $source;

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
     * Number of items to save to the db in one query
     */
    protected int $batchSize = 100;

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\DataTransfer\Repositories\ImportBatchRepository  $importBatchRepository
     * @return void
     */
    public function __construct(protected ImportBatchRepository $importBatchRepository)
    {
    }

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
    }

    /**
     * Save validated batches
     */
    protected function saveValidatedBatches(): self
    {
        $source = $this->getSource();

        $batchRows = [];

        $source->rewind();

        while (
            $source->valid()
            || count($batchRows)
        ) {
            if (
                count($batchRows) == $this->batchSize
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
     *
     * @param  string  $columnName
     * @param  string  $message
     */
    public function addRowError(string $code, int $rowNumber, $columnName = null, $message = null): self
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
}