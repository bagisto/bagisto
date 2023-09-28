<?php

namespace Webkul\DataGrid\Enums;

enum ColumnTypeEnum: string
{
    /**
     * Boolean.
     */
    case BOOLEAN = 'boolean';
        
    /**
     * Select.
     */
    case SELECT = 'select';    

    /**
     * Date range.
     */
    case DATE_RANGE = 'date_range';

    /**
     * Date time range.
     */
    case DATE_TIME_RANGE = 'datetime_range';
}
