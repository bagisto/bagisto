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
            ->map(fn ($column) => $record->{$column->getIndex()})
            ->toArray();
    }
}
