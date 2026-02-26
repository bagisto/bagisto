<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Exceptions\InvalidColumnExpressionException;

class Integer extends Column
{
    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
            if (is_string($requestedValues)) {
                $this->applyIntegerFilter($scopeQueryBuilder, $requestedValues);
            } elseif (is_array($requestedValues)) {
                foreach ($requestedValues as $value) {
                    $this->applyIntegerFilter($scopeQueryBuilder, $value);
                }
            } else {
                throw new InvalidColumnExpressionException('Only string and array are allowed for integer column type.');
            }
        });
    }

    /**
     * Apply integer filter.
     */
    private function applyIntegerFilter($queryBuilder, $value)
    {
        if (preg_match('/^([<>]=?|=)\s*(-?\d+)$/', $value, $matches)) {
            $operator = $matches[1];

            $intValue = (int) $matches[2];

            $queryBuilder->orWhere($this->columnName, $operator, $intValue);
        } elseif (preg_match('/^(-?\d+)\s*-\s*(-?\d+)$/', $value, $matches)) {
            $min = (int) $matches[1];

            $max = (int) $matches[2];

            $queryBuilder->orWhereBetween($this->columnName, [$min, $max]);
        } else {
            $queryBuilder->orWhere($this->columnName, '=', (int) $value);
        }
    }
}
