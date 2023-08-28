<?php

namespace Webkul\DataGrid\Enums;

enum FormInputTypeEnum: string
{
    /**
     * Date type.
     */
    case DATE = 'date';

    /**
     * Date time type.
     */
    case DATE_TIME = 'datetime-local';
}
