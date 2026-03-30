<?php

namespace Webkul\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\Core\Contracts\DatabaseGrammar;

/**
 * @method static string concat(string ...$parts)
 * @method static string groupConcat(string $column, string $separator = ',', bool $distinct = false, ?string $orderBy = null, string $orderDirection = 'ASC')
 * @method static string findInSet(string $needle, string $column)
 * @method static string orderByField(string $column, array $values)
 * @method static string dateFormat(string $column, string $format)
 * @method static string dateDiff(string $date1, string $date2)
 * @method static string now()
 * @method static string extractDatePart(string $part, string $column)
 * @method static string monthDay(string $column)
 * @method static string jsonExtractText(string $column, string $path)
 * @method static string jsonExtractNumeric(string $column, string $path)
 *
 * @see DatabaseGrammar
 */
class DbGrammar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DatabaseGrammar::class;
    }
}
