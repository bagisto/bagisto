<?php

namespace Webkul\Core\Contracts;

/**
 * Database-dialect-specific SQL fragment generator.
 *
 * All methods return raw SQL strings. Callers wrap in DB::raw() when needed.
 *
 * SECURITY CONTRACT: All string parameters MUST be developer-controlled SQL
 * fragments (column names, table prefixes, quoted literals). NEVER pass user
 * input directly. Use query bindings (?) for user-supplied values.
 */
interface DatabaseGrammar
{
    /**
     * Concatenate columns/literals.
     *
     * MySQL:  CONCAT(a, ' ', b)
     * PgSQL:  (COALESCE(a, '') || ' ' || COALESCE(b, ''))
     */
    public function concat(string ...$parts): string;

    /**
     * Aggregate values into a separated string.
     *
     * MySQL:  GROUP_CONCAT([DISTINCT] col [ORDER BY col] SEPARATOR ',')
     * PgSQL:  STRING_AGG([DISTINCT] col::text, ',' [ORDER BY col])
     */
    public function groupConcat(
        string $column,
        string $separator = ',',
        bool $distinct = false,
        ?string $orderBy = null,
        string $orderDirection = 'ASC',
    ): string;

    /**
     * Search for a value in a comma-separated string column.
     *
     * MySQL:  FIND_IN_SET(?, column)
     * PgSQL:  ? = ANY(STRING_TO_ARRAY(column, ','))
     *
     * @deprecated Prefer normalized pivot tables over comma-separated storage.
     */
    public function findInSet(string $needle, string $column): string;

    /**
     * Order by a specific sequence of values.
     *
     * MySQL:  FIELD(column, 1,2,3)
     * PgSQL:  COALESCE(ARRAY_POSITION(ARRAY[1,2,3], column), N+1)
     */
    public function orderByField(string $column, array $values): string;

    /**
     * Format a date column. Accepts MySQL-style format: %Y, %m, %d, %H, %i, %s
     *
     * MySQL:  DATE_FORMAT(col, '%Y-%m')
     * PgSQL:  TO_CHAR(col, 'YYYY-MM')
     */
    public function dateFormat(string $column, string $format): string;

    /**
     * Days between two date expressions (date1 - date2).
     *
     * MySQL:  DATEDIFF(date1, date2)
     * PgSQL:  (date1::date - date2::date)
     */
    public function dateDiff(string $date1, string $date2): string;

    /**
     * Current timestamp expression.
     */
    public function now(): string;

    /**
     * Extract a date part as integer.
     *
     * Supported parts: MONTH, WEEK, DAYOFYEAR, YEAR, DAY
     */
    public function extractDatePart(string $part, string $column): string;

    /**
     * Extract month-day from a date column (for birthday matching).
     *
     * MySQL:  DATE_FORMAT(col, '%m-%d')
     * PgSQL:  TO_CHAR(col, 'MM-DD')
     */
    public function monthDay(string $column): string;

    /**
     * Extract a text value from a JSON column.
     *
     * MySQL:  json_unquote(json_extract(col, '$.key'))
     * PgSQL:  (col::jsonb->>'key')
     */
    public function jsonExtractText(string $column, string $path): string;

    /**
     * Extract a numeric value from a JSON column (safe for SUM/AVG).
     *
     * MySQL:  COALESCE(CAST(json_unquote(json_extract(col, '$.key')) AS SIGNED), 0)
     * PgSQL:  COALESCE(NULLIF(col::jsonb->>'key', '')::bigint, 0)
     */
    public function jsonExtractNumeric(string $column, string $path): string;
}
