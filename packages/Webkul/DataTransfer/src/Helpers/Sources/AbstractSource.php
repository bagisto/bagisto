<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;

abstract class AbstractSource
{
    /**
     * Reader.
     */
    protected mixed $reader;

    /**
     * Column names.
     */
    protected array $columnNames = [];

    /**
     * Quantity of columns.
     */
    protected int $totalColumns = 0;

    /**
     * Current row.
     */
    protected array $currentRowData = [];

    /**
     * Current row number.
     */
    protected int $currentRowNumber = -1;

    /**
     * Flag to indicate that wrong quote was found.
     */
    protected bool $foundWrongQuoteFlag = false;

    /**
     * Initialize reader.
     */
    abstract protected function initialize(): void;

    /**
     * Read next line from source.
     */
    abstract protected function getNextRow(): array|bool;

    /**
     * Generate error report.
     */
    abstract public function generateErrorReport(array $errors): string;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected string $filePath,
        protected string $delimiter = ','
    ) {
        try {
            $this->initialize();
        } catch (\Exception $e) {
            throw new \LogicException("Unable to open file: '{$filePath}'");
        }
    }

    /**
     * Return the key of the current row.
     */
    public function getCurrentRowNumber(): int
    {
        return $this->currentRowNumber;
    }

    /**
     * Checks if current position is valid.
     */
    public function valid(): bool
    {
        return $this->currentRowNumber !== -1;
    }

    /**
     * Read next line from source.
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
     * Read next line from source.
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
     * Rewind the iterator to the first row.
     */
    public function rewind(): void
    {
        $this->currentRowNumber = 0;

        $this->currentRowData = [];

        $this->getNextRow();

        $this->next();
    }

    /**
     * Set reader.
     */
    public function setReader(mixed $reader): void
    {
        $this->reader = $reader;
    }

    /**
     * Get reader.
     */
    public function getReader(): mixed
    {
        return $this->reader;
    }

    /**
     * Set column names.
     */
    public function setColumnNames(array $columnNames): void
    {
        $this->columnNames = $columnNames;
    }

    /**
     * Get column names.
     */
    public function getColumnNames(): array
    {
        return $this->columnNames;
    }

    /**
     * Set total columns count.
     */
    public function setTotalColumns(int $totalColumns): void
    {
        $this->totalColumns = $totalColumns;
    }

    /**
     * Total columns count.
     */
    public function getTotalColumns(): int
    {
        return $this->totalColumns;
    }

    /**
     * Error file path.
     */
    public function errorFilePath(): string
    {
        $fileType = pathinfo($this->filePath, PATHINFO_EXTENSION);

        return 'imports/'.time().'-error-report.'.$fileType;
    }
}
