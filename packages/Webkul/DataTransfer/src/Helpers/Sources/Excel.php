<?php

namespace Webkul\DataTransfer\Helpers\Sources;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel extends AbstractSource
{
    /**
     * CSV reader
     */
    protected mixed $reader;

    /**
     * Current row number
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
     * Read next line from csv
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
     * Rewind the iterator to the first row
     */
    public function rewind(): void
    {
        $this->currentRowNumber = 1;

        $this->next();
    }
}
