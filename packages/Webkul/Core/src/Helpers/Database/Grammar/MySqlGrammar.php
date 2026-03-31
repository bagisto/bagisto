<?php

namespace Webkul\Core\Helpers\Database\Grammar;

use Webkul\Core\Contracts\DatabaseGrammar;

class MySqlGrammar implements DatabaseGrammar
{
    /**
     * Generates: CONCAT(part1, part2, ...).
     */
    public function concat(string ...$parts): string
    {
        return 'CONCAT('.implode(', ', $parts).')';
    }

    /**
     * Generates: GROUP_CONCAT([DISTINCT] column [ORDER BY ...] SEPARATOR ',').
     */
    public function groupConcat(
        string $column,
        string $separator = ',',
        bool $distinct = false,
        ?string $orderBy = null,
        string $orderDirection = 'ASC',
    ): string {
        $expr = 'GROUP_CONCAT(';
        $expr .= $distinct ? "DISTINCT {$column}" : $column;

        if ($orderBy) {
            $dir = strtoupper($orderDirection) === 'DESC' ? 'DESC' : 'ASC';
            $expr .= " ORDER BY {$orderBy} {$dir}";
        }

        $expr .= " SEPARATOR '{$separator}')";

        return $expr;
    }

    /**
     * Generates: FIND_IN_SET(needle, column).
     */
    public function findInSet(string $needle, string $column): string
    {
        return "FIND_IN_SET({$needle}, {$column})";
    }

    /**
     * Generates: FIELD(column, val1, val2, ...). Values are cast to integers for safety.
     */
    public function orderByField(string $column, array $values): string
    {
        $safeValues = array_map('intval', $values);

        return 'FIELD('.$column.', '.implode(',', $safeValues).')';
    }

    /**
     * Generates: DATE_FORMAT(column, 'format').
     */
    public function dateFormat(string $column, string $format): string
    {
        return "DATE_FORMAT({$column}, '{$format}')";
    }

    /**
     * Generates: DATEDIFF(date1, date2).
     */
    public function dateDiff(string $date1, string $date2): string
    {
        return "DATEDIFF({$date1}, {$date2})";
    }

    /**
     * Generates: NOW().
     */
    public function now(): string
    {
        return 'NOW()';
    }

    /**
     * Generates: MONTH(col), WEEK(col), DAYOFYEAR(col), etc.
     */
    public function extractDatePart(string $part, string $column): string
    {
        return match (strtoupper($part)) {
            'MONTH' => "MONTH({$column})",
            'WEEK' => "WEEK({$column})",
            'DAYOFYEAR' => "DAYOFYEAR({$column})",
            'YEAR' => "YEAR({$column})",
            'DAY' => "DAY({$column})",
            default => "EXTRACT({$part} FROM {$column})",
        };
    }

    /**
     * Generates: DATE_FORMAT(column, '%m-%d').
     */
    public function monthDay(string $column): string
    {
        return "DATE_FORMAT({$column}, '%m-%d')";
    }

    /**
     * Generates: json_unquote(json_extract(column, 'path')).
     */
    public function jsonExtractText(string $column, string $path): string
    {
        return "json_unquote(json_extract({$column}, '{$path}'))";
    }

    /**
     * Generates: COALESCE(CAST(json_unquote(json_extract(column, 'path')) AS SIGNED), 0).
     */
    public function jsonExtractNumeric(string $column, string $path): string
    {
        return "COALESCE(CAST(json_unquote(json_extract({$column}, '{$path}')) AS SIGNED), 0)";
    }
}
