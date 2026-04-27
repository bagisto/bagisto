<?php

namespace Webkul\Core\Helpers\Database\Grammar;

use Webkul\Core\Contracts\DatabaseGrammar;

class PgSqlGrammar implements DatabaseGrammar
{
    /**
     * MySQL-to-PostgreSQL date format placeholder mapping.
     */
    protected array $dateFormatMap = [
        '%Y' => 'YYYY',
        '%m' => 'MM',
        '%d' => 'DD',
        '%H' => 'HH24',
        '%i' => 'MI',
        '%s' => 'SS',
    ];

    /*
    |--------------------------------------------------------------------------
    | String Concatenation
    |--------------------------------------------------------------------------
    */

    /**
     * Generates: (COALESCE(part1, '') || COALESCE(part2, '') || ...). Null-safe.
     */
    public function concat(string ...$parts): string
    {
        $safeParts = array_map(
            fn (string $part) => "COALESCE({$part}, '')",
            $parts
        );

        return '('.implode(' || ', $safeParts).')';
    }

    /**
     * Generates: CONCAT_WS('separator', part1, part2, ...). Native to PostgreSQL 9.1+.
     */
    public function concatWs(string $separator, string ...$parts): string
    {
        return "CONCAT_WS('{$separator}', ".implode(', ', $parts).')';
    }

    /*
    |--------------------------------------------------------------------------
    | String Aggregation and Membership
    |--------------------------------------------------------------------------
    */

    /**
     * Generates: STRING_AGG([DISTINCT] column::text, 'separator' [ORDER BY ...]).
     */
    public function groupConcat(
        string $column,
        string $separator = ',',
        bool $distinct = false,
        ?string $orderBy = null,
        string $orderDirection = 'ASC',
    ): string {
        $safeColumn = "NULLIF({$column}::text, '')";

        $expr = 'STRING_AGG(';
        $expr .= $distinct ? "DISTINCT {$safeColumn}" : $safeColumn;
        $expr .= ", '{$separator}'";

        if ($orderBy) {
            $dir = strtoupper($orderDirection) === 'DESC' ? 'DESC' : 'ASC';
            $expr .= " ORDER BY {$orderBy} {$dir}";
        }

        $expr .= ')';

        return $expr;
    }

    /**
     * Generates: needle = ANY(STRING_TO_ARRAY(column, ',')).
     */
    public function findInSet(string $needle, string $column): string
    {
        return "{$needle} = ANY(STRING_TO_ARRAY({$column}, ','))";
    }

    /**
     * Generates: COALESCE(ARRAY_POSITION(ARRAY[...], column), N+1). Missing values sort last.
     */
    public function orderByField(string $column, array $values): string
    {
        $safeValues = array_map('intval', $values);
        $maxPos = count($safeValues) + 1;

        return 'COALESCE(ARRAY_POSITION(ARRAY['.implode(',', $safeValues)."], {$column}), {$maxPos})";
    }

    /*
    |--------------------------------------------------------------------------
    | LIKE Operators
    |--------------------------------------------------------------------------
    */

    /**
     * Return the case-insensitive LIKE operator.
     */
    public function caseInsensitiveLike(): string
    {
        return 'ILIKE';
    }

    /**
     * Return the case-sensitive LIKE operator.
     */
    public function caseSensitiveLike(): string
    {
        return 'LIKE';
    }

    /*
    |--------------------------------------------------------------------------
    | Date and Time
    |--------------------------------------------------------------------------
    */

    /**
     * Generates: NOW().
     */
    public function now(): string
    {
        return 'NOW()';
    }

    /**
     * Generates: TO_CHAR(column, 'format'). Converts MySQL format placeholders to PostgreSQL.
     */
    public function dateFormat(string $column, string $format): string
    {
        $pgFormat = str_replace(
            array_keys($this->dateFormatMap),
            array_values($this->dateFormatMap),
            $format
        );

        return "TO_CHAR({$column}, '{$pgFormat}')";
    }

    /**
     * Generates: (date1::date - date2::date). Returns integer number of days.
     */
    public function dateDiff(string $date1, string $date2): string
    {
        return "({$date1}::date - {$date2}::date)";
    }

    /**
     * Generates: EXTRACT(part FROM column)::integer. Maps DAYOFYEAR to DOY.
     */
    public function extractDatePart(string $part, string $column): string
    {
        $pgPart = match (strtoupper($part)) {
            'DAYOFYEAR' => 'DOY',
            default => strtoupper($part),
        };

        return "EXTRACT({$pgPart} FROM {$column})::integer";
    }

    /**
     * Generates: TO_CHAR(column, 'MM-DD').
     */
    public function monthDay(string $column): string
    {
        return "TO_CHAR({$column}, 'MM-DD')";
    }

    /**
     * Generates: TO_TIMESTAMP(column). Returns timestamp with time zone.
     */
    public function fromUnixtime(string $column): string
    {
        return "TO_TIMESTAMP({$column})";
    }

    /*
    |--------------------------------------------------------------------------
    | JSON Extraction
    |--------------------------------------------------------------------------
    */

    /**
     * Generates: (column::jsonb->>'key').
     */
    public function jsonExtractText(string $column, string $path): string
    {
        $key = $this->mysqlPathToKey($path);

        return "({$column}::jsonb->>'{$key}')";
    }

    /**
     * Generates: COALESCE(NULLIF(column::jsonb->>'key', '')::bigint, 0). Null and empty safe.
     */
    public function jsonExtractNumeric(string $column, string $path): string
    {
        $key = $this->mysqlPathToKey($path);

        return "COALESCE(NULLIF({$column}::jsonb->>'{$key}', '')::bigint, 0)";
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Convert MySQL JSON path ('$.key' or '$."key"') to a plain key name.
     */
    private function mysqlPathToKey(string $path): string
    {
        return preg_replace('/^\$\.?"?([^"]*)"?$/', '$1', $path);
    }
}
