<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Enums\DateRangeOptionEnum;
use Webkul\DataGrid\Enums\FilterTypeEnum;
use Webkul\DataGrid\Exceptions\InvalidColumnException;
use Webkul\DataGrid\Exceptions\InvalidColumnExpressionException;

class Date extends Column
{
    /**
     * Set filterable type.
     */
    public function setFilterableType(?string $filterableType): void
    {
        if (
            $filterableType
            && ($filterableType !== FilterTypeEnum::DATE_RANGE->value)
        ) {
            throw new InvalidColumnException('Date filters will only work with `date_range` type. Either remove the `filterable_type` or set it to `date_range`.');
        }

        parent::setFilterableType($filterableType);
    }

    /**
     * Set filterable options.
     */
    public function setFilterableOptions(mixed $filterableOptions): void
    {
        if (empty($filterableOptions)) {
            $filterableOptions = DateRangeOptionEnum::options();
        }

        parent::setFilterableOptions($filterableOptions);
    }

    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedDates)
    {
        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedDates) {
            if (is_string($requestedDates)) {
                $rangeOption = collect($this->filterableOptions)->firstWhere('name', $requestedDates);

                $requestedDates = ! $rangeOption
                    ? [[$requestedDates, $requestedDates]]
                    : [[$rangeOption['from'], $rangeOption['to']]];

                foreach ($requestedDates as $value) {
                    $scopeQueryBuilder->whereBetween($this->columnName, [
                        $value[0],
                        $value[1],
                    ]);
                }
            } elseif (is_array($requestedDates)) {
                foreach ($requestedDates as $value) {
                    $scopeQueryBuilder->whereBetween($this->columnName, [
                        $value[0] ? (str_contains($value[0], ' ') ? $value[0] : $value[0].' 00:00:01') : '',
                        $value[1] ? (str_contains($value[1], ' ') ? $value[1] : $value[1].' 23:59:59') : '',
                    ]);
                }
            } else {
                throw new InvalidColumnExpressionException('Only string and array are allowed for date column type.');
            }
        });
    }
}
