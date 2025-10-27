<?php

namespace Webkul\DataGrid\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Webkul\DataGrid\DataGrid;

class DataGridExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(protected DataGrid $datagrid) {}

    /**
     * Query.
     */
    public function query(): mixed
    {
        return $this->datagrid->getQueryBuilder();
    }

    /**
     * Headings.
     */
    public function headings(): array
    {
        return collect($this->datagrid->getColumns())
            ->filter(fn ($column) => $column->getExportable())
            ->map(fn ($column) => $column->getLabel())
            ->toArray();
    }

    /**
     * Mapping.
     */
    public function map(mixed $record): array
    {
        return collect($this->datagrid->getColumns())
            ->filter(fn ($column) => $column->getExportable())
            ->map(fn ($column) => $this->sanitize($record->{$column->getIndex()}))
            ->toArray();
    }

    /**
     * Sanitize data to prevent formula injection.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function sanitize($value)
    {
        if (! is_string($value)) {
            return $value;
        }

        $trimmed = ltrim($value);

        if ($trimmed === '') {
            return $value;
        }

        // expanded list of dangerous characters
        $dangerousChars = ['=', '+', '-', '@', "\t", "\r", "\n", '|', '%'];

        $firstChar = mb_substr($trimmed, 0, 1);

        // check if starts with dangerous character
        if (in_array($firstChar, $dangerousChars, true)) {
            // prefix with single quote and preserve original spacing
            return "'".$value;
        }

        // optional: check for suspicious patterns
        if (preg_match('/^[\s]*[@=+\-|%]/u', $value)) {
            return "'".$value;
        }

        return $value;
    }
}
