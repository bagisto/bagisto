<?php

namespace Webkul\DataTransfer\Helpers;

class Error
{
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
     * Errors count
     */
    protected int $errorsCount = 0;

    /**
     * Error message template
     */
    protected array $messageTemplate = [];

    /**
     * Add error message template
     */
    public function addErrorMessage(string $code, string $template): self
    {
        $this->messageTemplate[$code] = $template;

        return $this;
    }

    /**
     * Add error message.
     */
    public function addError(string $code, ?int $rowNumber = null, ?string $columnName = null, ?string $message = null): self
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
     */
    public function isErrorAlreadyAdded(?int $rowNumber, string $code, ?string $columnName): bool
    {
        return collect($this->items[$rowNumber] ?? [])
            ->where('code', $code)
            ->where('column', $columnName)
            ->isNotEmpty();
    }

    /**
     * Add specific row to invalid list via row number
     */
    protected function addRowToInvalid(?int $rowNumber): self
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
     */
    public function addRowToSkip(?int $rowNumber): self
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
     * Build an error message via code, message and column name
     */
    protected function getErrorMessage(?string $code, ?string $message, ?string $columnName): string
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
     * Get current error count
     */
    public function getErrorsCount(): int
    {
        return $this->errorsCount;
    }

    /**
     * Get all errors from an import process
     */
    public function getAllErrors(): array
    {
        return $this->items;
    }

    /**
     * Return all errors grouped by code
     */
    public function getAllErrorsGroupedByCode(): array
    {
        $errors = [];

        foreach ($this->items as $rowNumber => $rowErrors) {
            foreach ($rowErrors as $error) {
                if ($rowNumber === '') {
                    $errors[$error['code']][$error['message']] = null;
                } else {
                    $errors[$error['code']][$error['message']][] = $rowNumber;
                }
            }
        }

        return $errors;
    }
}
