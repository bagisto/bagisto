<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Illuminate\Support\Facades\Storage;

class CSV extends AbstractSource
{
    /**
     * CSV reader
     */
    protected mixed $reader;

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
            throw new \LogicException("Unable to open file: '{$filePath}'");
        }
    }

    /**
     * Close file handle
     *
     * @return void
     */
    public function __destruct()
    {
        if (! is_object($this->reader)) {
            return;
        }

        $this->reader->close();
    }

    /**
     * Read next line from csv
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
     * Rewind the iterator to the first row
     */
    public function rewind(): void
    {
        rewind($this->reader);

        parent::rewind();
    }
}
