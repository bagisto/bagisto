<?php

namespace Webkul\DataGrid\Enums;

enum ColumnTypeEnum: string
{
    /**
     * Boolean.
     */
    case BOOLEAN = 'boolean';

    /**
     * Basic dropdown.
     */
    case BASIC_DROPDOWN = 'basic_dropdown';

    /**
     * Date range.
     */
    case DATE_RANGE = 'date_range';

    /**
     * Date time range.
     */
    case DATE_TIME_RANGE = 'datetime_range';
}
