<?php

return [

    /**
     * Default Select Value
     */
    'default_index' => 'id',

    /**
     * Default OrderBy
     *
     * * Accepted Values = Array
     */
    'order' => [
        'default' => 'descending',
        'descending' => 'desc',
        'ascending' => 'asc'
    ],

    /**
     * Select distinct records only
     *
     * Accepted Values = True || False
     */
    'distinct' => true,

    /**
     * Default pagination
     *
     * Accepted Value = integer
     */
    'paginate' => false,

    'operators' => [
        'eq' => "=",
        'lt' => "<",
        'gt' => ">",
        'lte' => "<=",
        'gte' => ">=",
        'neqs' => "<>",
        'neqn' => "!=",
        'eqo' => "<=>",
        'like' => "like",
        'blike' => "like binary",
        'nlike' => "not like",
        'ilike' => "ilike",
        'and' => "&",
        'bor' => "|",
        'regex' => "regexp",
        'notregex' => "not regexp",
        // 14 => "^",
        // 15 => "<<",
        // 16 => ">>",
        // 17 => "rlike",
        // 20 => "~",
        // 21 => "~*",
        // 22 => "!~",
        // 23 => "!~*",
        // 24 => "similar to",
        // 25 => "not similar to",
        // 26 => "not ilike",
        // 27 => "~~*",
        // 28 => "!~~*"
    ],

    'bindings' => [
        0 => "select",
        1 => "from",
        2 => "join",
        3 => "where",
        4 => "having",
        5 => "order",
        6 => "union"
    ],

    'selectcomponents' => [
        0 => "aggregate",
        1 => "columns",
        2 => "from",
        3 => "joins",
        4 => "wheres",
        5 => "groups",
        6 => "havings",
        7 => "orders",
        8 => "limit",
        9 => "offset",
        10 => "lock"
    ]
];