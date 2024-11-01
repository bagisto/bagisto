<?php

namespace Webkul\DataGrid\Enums;

enum RangeOptionEnum: string
{
    /**
     * Today.
     */
    case TODAY = 'today';

    /**
     * Yesterday.
     */
    case YESTERDAY = 'yesterday';

    /**
     * This week.
     */
    case THIS_WEEK = 'this_week';

    /**
     * This month.
     */
    case THIS_MONTH = 'this_month';

    /**
     * Last month.
     */
    case LAST_MONTH = 'last_month';

    /**
     * Last three months.
     */
    case LAST_THREE_MONTHS = 'last_three_months';

    /**
     * Last six months.
     */
    case LAST_SIX_MONTHS = 'last_six_months';

    /**
     * This year.
     */
    case THIS_YEAR = 'this_year';
}
