<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;

class Integer extends Column
{
    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
            foreach ($requestedValues as $value) {
                $scopeQueryBuilder->orWhere($this->getDatabaseColumnName(), $value);
            }
        });
    }
}
