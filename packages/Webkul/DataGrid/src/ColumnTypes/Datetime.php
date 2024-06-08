<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Enums\DateRangeOptionEnum;

class Datetime extends Column
{
    /**
     * Get filterable options.
     */
    public function getFilterableOptions(): array
    {
        return DateRangeOptionEnum::options();
    }

    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedDates)
    {
        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedDates) {
            if (is_string($requestedDates)) {
                $rangeOption = collect($this->getFilterableOptions())->firstWhere('name', $requestedDates);

                $requestedDates = ! $rangeOption
                    ? [[$requestedDates, $requestedDates]]
                    : [[$rangeOption['from'], $rangeOption['to']]];
            }

            foreach ($requestedDates as $value) {
                $scopeQueryBuilder->whereBetween($this->getColumnName(), [$value[0] ?? '', $value[1] ?? '']);
            }
        });
    }
}
