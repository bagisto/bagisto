<?php

namespace Webkul\Admin\Exports;

use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Webkul\Core\Traits\Sanitizer;

class ReportingExport implements FromCollection
{
    use Sanitizer;

    /**
     * Create a new instance.
     *
     * @param mixed records
     * @return void
     */
    public function __construct(protected $records = []) {}

    /**
     * function to create a blade view for export.
     *
     * @return Collection
     */
    public function collection()
    {
        $rows[] = Arr::pluck($this->records['columns'], 'label');

        foreach ($this->records['records'] as $key => $record) {
            $data = [];

            foreach ($this->records['columns'] as $column) {
                $data[$column['label']] = $this->sanitizeSpreadsheetValue($record[$column['key']] ?? null);
            }

            $rows[] = (object) $data;
        }

        return collect($rows);
    }
}
