<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;

class Decimal extends Column
{
    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        return $queryBuilder;
    }
}
