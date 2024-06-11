<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Enums\FilterTypeEnum;
use Webkul\DataGrid\Exceptions\InvalidColumnException;

class Boolean extends Column
{
    /**
     * Set filterable type.
     */
    public function setFilterableType(?string $filterableType): void
    {
        if (
            $filterableType
            && ($filterableType !== FilterTypeEnum::DROPDOWN->value)
        ) {
            throw new InvalidColumnException('Boolean filters will only work with `dropdown` type. Either remove the `filterable_type` or set it to `dropdown`.');
        }

        if (! $filterableType) {
            $filterableType = FilterTypeEnum::DROPDOWN->value;
        }

        parent::setFilterableType($filterableType);
    }

    /**
     * Set filterable options.
     */
    public function setFilterableOptions(mixed $filterableOptions): void
    {
        if (empty($filterableOptions)) {
            $filterableOptions = [
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

        parent::setFilterableOptions($filterableOptions);
    }

    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues): mixed
    {
        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
            if (is_string($requestedValues)) {
                $scopeQueryBuilder->orWhere($this->columnName, $requestedValues);

                return;
            }

            foreach ($requestedValues as $value) {
                $scopeQueryBuilder->orWhere($this->columnName, $value);
            }
        });
    }
}
