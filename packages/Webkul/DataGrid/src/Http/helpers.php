<?php

use Webkul\DataGrid\DataGrid;

if (! function_exists('datagrid')) {
    /**
     * Datagrid helper.
     */
    function datagrid(string $datagridClass): DataGrid
    {
        return app($datagridClass);
    }
}
