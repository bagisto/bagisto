<?php

namespace Webkul\DataTransfer\Helpers;

use Webkul\DataTransfer\Contracts\Import as ImportContract;
use League\Csv\Reader;

class Source
{
    /**
     * @var \League\Csv\Reader
     */
    protected $reader;

    /**
     * @var array
     */
    protected $columnNames = [];

    /**
     * Quantity of columns
     *
     * @var int
     */
    protected $totalColumns = 0;

    /**
     * Current row
     *
     * @var array
     */
    protected $currentRowData = [];

    /**
     * Current row number
     *
     * -1 means "out of bounds"
     *
     * @var int
     */
    protected $currentRowNumber = -1;

    /**
     * @var bool
     */
    protected $foundWrongQuoteFlag = false;

    /**
     * @return void
     */
    public function __construct(
        string $filePath,
        string $delimiter = ','
    )
    {
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
     *
     * @return int -1 if out of bounds, 0 or more otherwise
     */
    public function getCurrentRowNumber()
    {
        return $this->currentRowNumber;
    }

    /**
     * Checks if current position is valid
     */
    public function valid(): bool
    {
        return -1 !== $this->currentRowNumber;
    }

    /**
     * Read next line from CSV-file
     */
    public function current(): array|bool
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
     *
     * @return array|bool
     */
    public function next()
    {
        $this->currentRowNumber++;

        $row = $this->getNextRow();

        if (false === $row || [] === $row) {
            $this->currentRowData = [];

            $this->currentRowNumber = -1;
        } else {
            $this->currentRowData = $row;
        }
    }

    /**
     * Read next line from CSV-file
     *
     * @return array|bool
     */
    protected function getNextRow()
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
     *
     * @return void
     */
    public function rewind()
    {
        $this->currentRowNumber = -1;

        $this->currentRowData = [];

        $this->next();
    }

    /**
     * Seeks to a position (Seekable interface)
     *
     * @param int $position The position to seek to 0 or more
     * @return void
     * @throws \OutOfBoundsException
     */
    public function seek($position)
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
     *
     * @return array
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }

    /**
     * Column names getter.
     *
     * @return int
     */
    public function getTotalColumns()
    {
        return $this->columnNames;
    }
}