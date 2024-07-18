<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Illuminate\Support\Facades\Storage;
use XMLReader;

class XML extends AbstractSource
{
    /**
     * XML reader.
     */
    protected $reader;

    /**
     * File path.
     */
    protected string $filePath;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        try {
            $this->reader = new XMLReader();

            $this->reader->open(Storage::disk('private')->path($filePath));

            while (
                $this->reader->read()
                && ! $this->reader->attributeCount
            );

            $this->columnNames = $this->getColumnNames();

            $this->totalColumns = count($this->columnNames);
        } catch (\Exception $e) {
            throw new \LogicException("Unable to open file: '{$filePath}'");
        }
    }

    /**
     * Get column names from the first element's children.
     */
    public function getColumnNames(): array
    {
        $columnNames = [];

        if ($this->reader->moveToFirstAttribute()) {
            do {
                $columnNames[] = $this->reader->name;
            } while ($this->reader->moveToNextAttribute());
        }

        $this->reader->moveToElement();

        return $columnNames;
    }

    /**
     * Read next element from XML.
     */
    protected function getNextRow(): array|bool
    {
        $rowData = [];

        if ($this->reader->moveToFirstAttribute()) {
            do {
                $rowData[] = $this->reader->value;
            } while ($this->reader->moveToNextAttribute());
        }

        while (
            $this->reader->read()
            && ! $this->reader->attributeCount
        );

        return $rowData;
    }

    /**
     * Rewind the iterator to the first row.
     */
    public function rewind(): void
    {
        $this->currentRowNumber = 0;

        $this->currentRowData = [];

        $this->reader->close();

        $this->reader->open(Storage::disk('private')->path($this->filePath));

        while (
            $this->reader->read()
            && ! $this->reader->attributeCount
        );

        $this->next();
    }
}
