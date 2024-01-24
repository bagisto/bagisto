<?php

namespace Webkul\DataTransfer\Helpers;

use Illuminate\Support\Facades\Storage;
use Webkul\DataTransfer\Helpers\Types\AbstractType;

class Source
{
    /**
     * CSV reader
     */
    protected mixed $reader;

    /**
     * Column names
     */
    protected array $columnNames = [];

    /**
     * Quantity of columns
     */
    protected int $totalColumns = 0;

    /**
     * Current row
     */
    protected array $currentRowData = [];

    /**
     * Current row number
     */
    protected int $currentRowNumber = -1;

    /**
     * Flag to indicate that wrong quote was found
     */
    protected bool $foundWrongQuoteFlag = false;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        string $filePath,
        protected string $delimiter = ','
    ) {
        try {
            $this->reader = fopen(Storage::disk('private')->path($filePath), 'r');

            $this->columnNames = fgetcsv($this->reader, 4096, $delimiter);

            $this->totalColumns = count($this->columnNames);
        } catch (\Exception $e) {
            throw new \LogicException("Unable to open file: '{$file}'");
        }
    }

    /**
     * Return the key of the current element (\Iterator interface)
     */
    public function getCurrentRowNumber(): int
    {
        return $this->currentRowNumber;
    }

    /**
     * Checks if current position is valid
     */
    public function valid(): bool
    {
        return $this->currentRowNumber !== -1;
    }

    /**
     * Read next line from CSV-file
     */
    public function current(): array
    {
        $row = $this->currentRowData;

        if (count($row) != $this->totalColumns) {
            if ($this->foundWrongQuoteFlag) {
                throw new \InvalidArgumentException(AbstractType::ERROR_CODE_WRONG_QUOTES);
            } else {
                throw new \InvalidArgumentException(AbstractType::ERROR_CODE_COLUMNS_NUMBER);
            }
        }

        return array_combine($this->columnNames, $row);
    }

    /**
     * Read next line from CSV-file
     */
    public function next(): void
    {
        $this->currentRowNumber++;

        $row = $this->getNextRow();

        if ($row === false || $row === []) {
            $this->currentRowData = [];

            $this->currentRowNumber = -1;
        } else {
            $this->currentRowData = $row;
        }
    }

    /**
     * Read next line from CSV-file
     */
    protected function getNextRow(): array
    {
        $parsed = fgetcsv($this->reader, 4096, $this->delimiter);

        if (is_array($parsed) && count($parsed) != $this->totalColumns) {
            foreach ($parsed as $element) {
                if ($element && strpos($element, "'") !== false) {
                    $this->foundWrongQuoteFlag = true;

                    break;
                }
            }
        } else {
            $this->foundWrongQuoteFlag = false;
        }

        return is_array($parsed) ? $parsed : [];
    }

    /**
     * Rewind the \Iterator to the first element (\Iterator interface)
     */
    public function rewind(): void
    {
        rewind($this->reader);

        $this->currentRowNumber = 0;

        $this->currentRowData = [];

        $this->getNextRow();

        $this->next();
    }

    /**
     * Column names getter.
     */
    public function getColumnNames(): array
    {
        return $this->columnNames;
    }

    /**
     * Column names getter.
     */
    public function getTotalColumns(): int
    {
        return $this->columnNames;
    }
}
