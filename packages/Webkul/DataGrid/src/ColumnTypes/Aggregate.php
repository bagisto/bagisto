<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Enums\FilterTypeEnum;

class Aggregate extends Column
{
    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        if ($this->filterableType === FilterTypeEnum::DROPDOWN->value) {
            return $queryBuilder->having(function ($scopeQueryBuilder) use ($requestedValues) {
                if (is_string($requestedValues)) {
                    $scopeQueryBuilder->orHaving($this->getDatabaseColumnName(), $requestedValues);

                    return;
                }

                foreach ($requestedValues as $value) {
                    $scopeQueryBuilder->orHaving($this->getDatabaseColumnName(), $value);
                }
            });
        }

        return $queryBuilder->having(function ($scopeQueryBuilder) use ($requestedValues) {
            if (is_string($requestedValues)) {
                $scopeQueryBuilder->orHaving($this->getDatabaseColumnName(), 'LIKE', '%'.$requestedValues.'%');

                return;
            }

            foreach ($requestedValues as $value) {
                $scopeQueryBuilder->orHaving($this->getDatabaseColumnName(), 'LIKE', '%'.$value.'%');
            }
        });
    }
}
