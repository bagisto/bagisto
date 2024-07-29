<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv as CSVWriter;

class CSV extends AbstractSource
{
    /**
     * Initialize.
     */
    public function initialize(): void
    {
        $this->reader = fopen(Storage::disk('private')->path($this->filePath), 'r');

        $this->columnNames = fgetcsv($this->reader, 4096, $this->delimiter);

        $this->totalColumns = count($this->columnNames);
    }

    /**
     * Read next line from csv.
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
     * Rewind the iterator to the first row.
     */
    public function rewind(): void
    {
        rewind($this->reader);

        parent::rewind();
    }

    /**
     * Generate error report.
     */
    public function generateErrorReport(array $errors): string
    {
        $this->rewind();

        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();

        /**
         * Add headers with extra error column.
         */
        $sheet->fromArray(
            [array_merge($this->getColumnNames(), [
                'errors',
            ])],
            null,
            'A1'
        );

        $rowNumber = 2;

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

            $rowData[] = implode('|', $rowErrors);

            $sheet->fromArray([$rowData], null, 'A'.$rowNumber++);

            $this->next();
        }

        $writer = new CSVWriter($spreadsheet);

        $writer->setDelimiter(',');

        $writer->save(Storage::disk('private')->path($this->errorFilePath()));

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
