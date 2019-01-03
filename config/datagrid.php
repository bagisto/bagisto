<?php

return [

    /**
     * Default Select Value
     */
    'select' => 'id',

    /**
     * Default OrderBy
     *
     * * Accepted Values = Array
     */
    'order' => [
        'column' => 'id',
        'direction' => 'desc'
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
     * Accepted Values = integer
     */
    'pagination' => 10
];