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
     * Excel reader.
     */
    protected mixed $reader;

    /**
     * Current row number.
     */
    protected int $currentRowNumber = 1;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(string $filePath)
    {
        try {
            $factory = IOFactory::load(Storage::disk('private')->path($filePath));

            $this->reader = $factory->getActiveSheet();

            $highestColumn = $this->reader->getHighestColumn();

            $this->totalColumns = Coordinate::columnIndexFromString($highestColumn);

            $this->columnNames = $this->getNextRow();
        } catch (\Exception $e) {
            throw new \LogicException("Unable to open file: '{$filePath}'");
        }
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

        $spreadsheet = new Spreadsheet();

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

        $writer->save(Storage::disk('private')->path($errorFilePath = 'imports/'.time().'-error-report.xls'));

        return $errorFilePath;
    }
}
