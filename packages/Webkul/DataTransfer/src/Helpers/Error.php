<?php

namespace Webkul\DataTransfer\Helpers;

class Error
{
    const VALIDATION_STRATEGY_SKIP_ERRORS = 'skip-errors';

    const VALIDATION_STRATEGY_STOP_ON_ERROR = 'stop-on-errors';

    /**
     * Validation strategy
     */
    protected string $validationStrategy;

    /**
     * Error Items
     */
    protected array $items = [];

    /**
     * Invalid rows
     */
    protected array $invalidRows = [];

    /**
     * Skipped rows
     */
    protected array $skippedRows = [];

    /**
     * Allowed errors
     */
    protected int $allowedErrors = 0;

    /**
     * Errors count
     */
    protected int $errorsCount = 0;

    /**
     * Error message template
     */
    protected array $messageTemplate = [];

    /**
     * Initialize validation strategy.
     */
    public function initValidationStrategy(string $validationStrategy, int $allowedErrors): self
    {
        $this->validationStrategy = $validationStrategy;

        $this->allowedErrors = $allowedErrors;

        return $this;
    }

    /**
     * Add error message template
     */
    public function addErrorMessageTemplate(string $code, string $template): self
    {
        $this->messageTemplate[$code] = $template;

        return $this;
    }

    /**
     * Add error message.
     * 
     * @param  string  $code
     * @param  string|null  $row
     * @param  string|null  $column
     * @param  string|null  $message
     */
    public function addError($code, $rowNumber = null, $columnName = null, $message = null): self
    {
        if ($this->isErrorAlreadyAdded($rowNumber, $code, $columnName)) {
            return $this;
        }

        $this->addRowToInvalid($rowNumber);

        $message = $this->getErrorMessage($code, $message, $columnName);

        $this->items[$rowNumber][] = [
            'code'    => $code,
            'column'  => $columnName,
            'message' => $message,
        ];

        $this->errorsCount++;

        return $this;        
    }

    /**
     * Check if error is already added for the row, code and column.
     * 
     * @param  integer|null  $rowNumber
     * @param  string  $code
     * @param  string|null  $columnName
     */
    public function isErrorAlreadyAdded($rowNumber, $code, $columnName): bool
    {
        return collect($this->items[$rowNumber] ?? [])
            ->where('code', $code)
            ->where('column', $columnName)
            ->isNotEmpty();
    }

    /**
     * Add specific row to invalid list via row number
     *
     * @param  integer|null  $rowNumber
     */
    protected function addRowToInvalid($rowNumber): self
    {
        if (is_null($rowNumber)) {
            return $this;
        }

        if (! in_array($rowNumber, $this->invalidRows)) {
            $this->invalidRows[] = $rowNumber;
        }
        
        return $this;
    }

    /**
     * Add specific row to invalid list via row number
     *
     * @param  integer|null  $rowNumber
     */
    public function addRowToSkip($rowNumber): self
    {
        if (is_null($rowNumber)) {
            return $this;
        }

        if (! in_array($rowNumber, $this->skippedRows)) {
            $this->skippedRows[] = $rowNumber;
        }

        return $this;
    }

    /**
     * Check if row is invalid by row number
     */
    public function isRowInvalid(int $rowNumber): bool
    {
        return in_array($rowNumber, array_merge($this->invalidRows, $this->skippedRows));
    }

    /**
     * Get all errors from an import process
     */
    public function getAllErrors(): array
    {
        return $this->items;
    }

    /**
     * Build an error message via code, message and column name
     *
     * @param string $code
     * @param string $message
     * @param string $columnName
     * @return string
     */
    protected function getErrorMessage($code, $message, $columnName): string
    {
        if (
            empty($message)
            && isset($this->messageTemplate[$code])
        ) {
            $message = (string) $this->messageTemplate[$code];
        }

        if (
            $columnName
            && $message
        ) {
            $message = sprintf($message, $columnName);
        }

        if (! $message) {
            $message = $code;
        }

        return $message;
    }

    /**
     * Get number of invalid rows
     */
    public function getInvalidRowsCount(): int
    {
        return count($this->invalidRows);
    }

    /**
     * Check if error limit has been exceeded
     */
    public function isErrorLimitExceeded(): bool
    {
        $errorsCount = $this->getErrorsCount();

        if (
            $this->validationStrategy == self::VALIDATION_STRATEGY_STOP_ON_ERROR
            && $errorsCount > $this->allowedErrors
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get current error count
     */
    public function getErrorsCount(): int
    {
        return $this->errorsCount;
    }
}