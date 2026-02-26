<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls as XLSWriter;

class XLS extends AbstractSource
{
    /**
     * Current row number.
     */
    protected int $currentRowNumber = 1;

    /**
     * Initialize.
     */
    public function initialize(): void
    {
        $factory = IOFactory::load(Storage::disk('private')->path($this->filePath));

        $this->reader = $factory->getActiveSheet();

        $this->totalColumns = Coordinate::columnIndexFromString($this->reader->getHighestColumn());

        $this->columnNames = $this->getNextRow();
    }

    /**
     * Read next line from excel.
     */
    protected function getNextRow(): array|bool
    {
        for ($column = 1; $column <= $this->totalColumns; $column++) {
            $rowData[] = $this->reader->getCellByColumnAndRow($column, $this->currentRowNumber)->getValue();
        }

        $filteredRowData = array_filter($rowData);

        if (empty($filteredRowData)) {
            return false;
        }

        return $rowData;
    }

    /**
     * Rewind the iterator to the first row.
     */
    public function rewind(): void
    {
        $this->currentRowNumber = 1;

        $this->next();
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

        $writer = new XLSWriter($spreadsheet);

        $writer->save(Storage::disk('private')->path($this->errorFilePath()));

        return $this->errorFilePath();
    }
}
