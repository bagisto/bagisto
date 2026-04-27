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
    /*
    |--------------------------------------------------------------------------
    | String Concatenation
    |--------------------------------------------------------------------------
    */

    /**
     * Concatenate columns or literals into a single string expression.
     */
    public function concat(string ...$parts): string;

    /**
     * Concatenate columns or literals with a separator, skipping NULL values.
     */
    public function concatWs(string $separator, string ...$parts): string;

    /*
    |--------------------------------------------------------------------------
    | String Aggregation and Membership
    |--------------------------------------------------------------------------
    */

    /**
     * Aggregate column values into a delimited string.
     */
    public function groupConcat(
        string $column,
        string $separator = ',',
        bool $distinct = false,
        ?string $orderBy = null,
        string $orderDirection = 'ASC',
    ): string;

    /**
     * Search for a value within a comma-separated string column.
     *
     * @deprecated Prefer normalized pivot tables over comma-separated storage.
     */
    public function findInSet(string $needle, string $column): string;

    /**
     * Order results by a specific sequence of values.
     */
    public function orderByField(string $column, array $values): string;

    /*
    |--------------------------------------------------------------------------
    | LIKE Operators
    |--------------------------------------------------------------------------
    */

    /**
     * Return the case-insensitive LIKE operator for the current database driver.
     */
    public function caseInsensitiveLike(): string;

    /**
     * Return the case-sensitive LIKE operator for the current database driver.
     */
    public function caseSensitiveLike(): string;

    /*
    |--------------------------------------------------------------------------
    | Date and Time
    |--------------------------------------------------------------------------
    */

    /**
     * Return the current timestamp expression.
     */
    public function now(): string;

    /**
     * Format a date column using MySQL-style placeholders: %Y, %m, %d, %H, %i, %s.
     */
    public function dateFormat(string $column, string $format): string;

    /**
     * Calculate the number of days between two date expressions (date1 - date2).
     */
    public function dateDiff(string $date1, string $date2): string;

    /**
     * Extract a date part (MONTH, WEEK, DAYOFYEAR, YEAR, DAY) as an integer expression.
     */
    public function extractDatePart(string $part, string $column): string;

    /**
     * Extract the month and day from a date column, formatted as MM-DD.
     */
    public function monthDay(string $column): string;

    /**
     * Convert a Unix timestamp column to a timestamp/datetime expression.
     */
    public function fromUnixtime(string $column): string;

    /*
    |--------------------------------------------------------------------------
    | JSON Extraction
    |--------------------------------------------------------------------------
    */

    /**
     * Extract a text value from a JSON column at the given path.
     */
    public function jsonExtractText(string $column, string $path): string;

    /**
     * Extract a numeric value from a JSON column, returning 0 for null or empty values.
     */
    public function jsonExtractNumeric(string $column, string $path): string;
}
