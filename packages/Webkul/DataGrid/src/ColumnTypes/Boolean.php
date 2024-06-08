<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Enums\FilterTypeEnum;

class Boolean extends Column
{
    /**
     * Get filterable type.
     */
    public function getFilterableType(): ?string
    {
        return FilterTypeEnum::DROPDOWN->value;
    }

    /**
     * Get filterable options.
     */
    public function getFilterableOptions(): array
    {
        return [
            [
                'label' => trans('admin::app.components.datagrid.filters.boolean-options.true'),
                'value' => 1,
            ],
            [
                'label' => trans('admin::app.components.datagrid.filters.boolean-options.false'),
                'value' => 0,
            ],
        ];
    }

    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues): mixed
    {
        if ($this->filterableType === FilterTypeEnum::DROPDOWN->value) {
            return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
                if (is_string($requestedValues)) {
                    $scopeQueryBuilder->orWhere($this->getColumnName(), $requestedValues);

                    return;
                }

                foreach ($requestedValues as $value) {
                    $scopeQueryBuilder->orWhere($this->getColumnName(), $value);
                }
            });
        }

        throw new \Exception('Bool filter will only work with dropdown type.');
    }
}
