<?php

namespace Webkul\DataGrid\ColumnTypes;

use Webkul\DataGrid\Column;
use Webkul\DataGrid\Exceptions\InvalidColumnExpressionException;

class Decimal extends Column
{
    /**
     * Process filter.
     */
    public function processFilter($queryBuilder, $requestedValues)
    {
        return $queryBuilder->where(function ($scopeQueryBuilder) use ($requestedValues) {
            if (is_string($requestedValues)) {
                $this->applyDecimalFilter($scopeQueryBuilder, $requestedValues);
            } elseif (is_array($requestedValues)) {
                foreach ($requestedValues as $value) {
                    $this->applyDecimalFilter($scopeQueryBuilder, $value);
                }
            } else {
                throw new InvalidColumnExpressionException('Only string and array are allowed for decimal column type.');
            }
        });
    }

    /**
     * Apply decimal filter.
     */
    private function applyDecimalFilter($queryBuilder, $value)
    {
        if (preg_match('/^([<>]=?|=)\s*(-?[\d.]+)$/', $value, $matches)) {
            $operator = $matches[1];

            $decimalValue = (float) $matches[2];

            $queryBuilder->orWhere($this->columnName, $operator, $decimalValue);
        } elseif (preg_match('/^(-?[\d.]+)\s*-\s*(-?[\d.]+)$/', $value, $matches)) {
            $min = (float) $matches[1];

            $max = (float) $matches[2];

            $queryBuilder->orWhereBetween($this->columnName, [$min, $max]);
        } else {
            $queryBuilder->orWhere($this->columnName, '=', (float) $value);
        }
    }
}
