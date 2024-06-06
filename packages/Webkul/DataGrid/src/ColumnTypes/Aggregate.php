<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;

class Aggregate extends Column
{
    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        return $queryBuilder->having(function ($scopeQueryBuilder) use ($requestedValues) {
            foreach ($requestedValues as $value) {
                $scopeQueryBuilder->orHaving($this->getDatabaseColumnName(), 'LIKE', '%'.$value.'%');
            }
        });
    }
}
