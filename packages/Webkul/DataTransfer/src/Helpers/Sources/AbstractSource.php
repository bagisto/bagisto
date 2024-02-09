<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;

abstract class AbstractSource
{
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
     * Read next line from source
     */
    abstract protected function getNextRow(): array|bool;

    /**
     * Return the key of the current row
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
     * Read next line from source
     */
    public function current(): array
    {
        $row = $this->currentRowData;

        if (count($row) != $this->totalColumns) {
            if ($this->foundWrongQuoteFlag) {
                throw new \InvalidArgumentException(AbstractImporter::ERROR_CODE_WRONG_QUOTES);
            } else {
                throw new \InvalidArgumentException(AbstractImporter::ERROR_CODE_COLUMNS_NUMBER);
            }
        }

        return array_combine($this->columnNames, $row);
    }

    /**
     * Read next line from source
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
     * Rewind the iterator to the first row
     */
    public function rewind(): void
    {
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
