<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleXMLElement;
use XMLReader;

class XML extends AbstractSource
{
    /**
     * Initialize.
     */
    public function initialize(): void
    {
        $this->reader = new XMLReader;

        $this->reader->open(Storage::disk('private')->path($this->filePath));

        while (
            $this->reader->read()
            && ! $this->reader->attributeCount
        );

        $this->columnNames = $this->getColumnNames();

        $this->totalColumns = count($this->columnNames);
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

    /**
     * Generate error report.
     */
    public function generateErrorReport(array $errors): string
    {
        $this->rewind();

        $childElement = $this->reader->name;

        $parentElement = Str::pluralStudly($this->reader->name);

        $writer = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><'.$parentElement.'></'.$parentElement.'>');

        while ($this->valid()) {
            try {
                $rowData = $this->current();
            } catch (\InvalidArgumentException $e) {
                $this->next();

                continue;
            }

            $rowErrors = $errors[$this->getCurrentRowNumber()] ?? [];

            if (! empty($rowErrors)) {
                $rowErrors = Arr::pluck($rowErrors, 'message');
            }

            $rowData['errors'] = implode('|', $rowErrors);

            $customer = $writer->addChild($childElement);

            foreach ($rowData as $key => $value) {
                if (is_string($key)) {
                    $customer->addAttribute($key, $value);
                }
            }

            $this->next();
        }

        $writer->saveXML(Storage::disk('private')->path($this->errorFilePath()));

        return $this->errorFilePath();
    }

    /**
     * Close file handle.
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
}
