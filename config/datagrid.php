<?php

return [

    /**
     * Default OrderBy
     *
     * * Accepted Values = Array
     */
    'defaultOrder' => [
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