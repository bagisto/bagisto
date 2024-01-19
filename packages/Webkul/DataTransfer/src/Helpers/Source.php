<?php

namespace Webkul\DataTransfer\Helpers;

use League\Csv\Reader;

class Source
{
    /**
     * CSV reader
     */
    protected Reader $reader;

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
        string $delimiter = ','
    ) {
        try {
            $this->reader = Reader::createFromPath(storage_path($filePath), 'r');

            $this->reader->setHeaderOffset(0);

            $this->reader->setDelimiter($delimiter);

            $this->columnNames = $this->reader->getHeader();

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
        $parsed = $this->reader->fetchOne($this->currentRowNumber);

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
        $this->currentRowNumber = -1;

        $this->currentRowData = [];

        $this->next();
    }

    /**
     * Seeks to a position (Seekable interface)
     *
     * @return void
     *
     * @throws \OutOfBoundsException
     */
    public function seek(int $position)
    {
        if ($position == $this->currentRowNumber) {
            return;
        }

        if (! $position || $position < $this->currentRowNumber) {
            $this->rewind();
        }

        if ($position > 0) {
            do {
                $this->next();

                if ($this->currentRowNumber == $position) {
                    return;
                }
            } while ($this->currentRowNumber != -1);
        }

        throw new \OutOfBoundsException('Please correct the seek position.');
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
