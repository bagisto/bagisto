<?php

namespace Webkul\Core\Database\Grammar;

use Webkul\Core\Contracts\DatabaseGrammar;

class PgSqlGrammar implements DatabaseGrammar
{
    protected array $dateFormatMap = [
        '%Y' => 'YYYY',
        '%m' => 'MM',
        '%d' => 'DD',
        '%H' => 'HH24',
        '%i' => 'MI',
        '%s' => 'SS',
    ];

    public function concat(string ...$parts): string
    {
        $safeParts = array_map(
            fn (string $part) => "COALESCE({$part}, '')",
            $parts
        );

        return '('.implode(' || ', $safeParts).')';
    }

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

    public function findInSet(string $needle, string $column): string
    {
        return "{$needle} = ANY(STRING_TO_ARRAY({$column}, ','))";
    }

    public function orderByField(string $column, array $values): string
    {
        $safeValues = array_map('intval', $values);
        $maxPos = count($safeValues) + 1;

        return 'COALESCE(ARRAY_POSITION(ARRAY['.implode(',', $safeValues)."], {$column}), {$maxPos})";
    }

    public function dateFormat(string $column, string $format): string
    {
        $pgFormat = str_replace(
            array_keys($this->dateFormatMap),
            array_values($this->dateFormatMap),
            $format
        );

        return "TO_CHAR({$column}, '{$pgFormat}')";
    }

    public function dateDiff(string $date1, string $date2): string
    {
        return "({$date1}::date - {$date2}::date)";
    }

    public function now(): string
    {
        return 'NOW()';
    }

    public function extractDatePart(string $part, string $column): string
    {
        $pgPart = match (strtoupper($part)) {
            'DAYOFYEAR' => 'DOY',
            default => strtoupper($part),
        };

        return "EXTRACT({$pgPart} FROM {$column})::integer";
    }

    public function monthDay(string $column): string
    {
        return "TO_CHAR({$column}, 'MM-DD')";
    }

    public function jsonExtractText(string $column, string $path): string
    {
        $key = $this->mysqlPathToKey($path);

        return "({$column}::jsonb->>'{$key}')";
    }

    public function jsonExtractNumeric(string $column, string $path): string
    {
        $key = $this->mysqlPathToKey($path);

        return "COALESCE(NULLIF({$column}::jsonb->>'{$key}', '')::bigint, 0)";
    }

    /**
     * Convert MySQL JSON path '$.key' or '$."key"' to plain key name.
     */
    private function mysqlPathToKey(string $path): string
    {
        return preg_replace('/^\$\.?"?([^"]*)"?$/', '$1', $path);
    }
}
