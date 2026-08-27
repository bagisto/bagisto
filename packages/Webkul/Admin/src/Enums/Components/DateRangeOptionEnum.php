<?php

namespace Webkul\Admin\Enums\Components;

enum DateRangeOptionEnum: string
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
     * Last thirty days.
     */
    case LAST_THIRTY_DAYS = 'last_thirty_days';

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

    /**
     * Get options.
     */
    public static function options(string $format = 'Y-m-d H:i:s'): array
    {
        return [
            [
                'name' => self::TODAY->value,
                'label' => trans('admin::app.components.date-range-picker.presets.today'),
                'from' => now()->today()->format($format),
                'to' => now()->endOfDay()->format($format),
            ],
            [
                'name' => self::YESTERDAY->value,
                'label' => trans('admin::app.components.date-range-picker.presets.yesterday'),
                'from' => now()->yesterday()->format($format),
                'to' => now()->today()->subSecond(1)->format($format),
            ],
            [
                'name' => self::LAST_THIRTY_DAYS->value,
                'label' => trans('admin::app.components.date-range-picker.presets.last-thirty-days'),
                'from' => now()->subDays(30)->startOfDay()->format($format),
                'to' => now()->endOfDay()->format($format),
            ],
            [
                'name' => self::THIS_WEEK->value,
                'label' => trans('admin::app.components.date-range-picker.presets.this-week'),
                'from' => now()->startOfWeek()->format($format),
                'to' => now()->endOfWeek()->format($format),
            ],
            [
                'name' => self::THIS_MONTH->value,
                'label' => trans('admin::app.components.date-range-picker.presets.this-month'),
                'from' => now()->startOfMonth()->format($format),
                'to' => now()->endOfMonth()->format($format),
            ],
            [
                'name' => self::LAST_MONTH->value,
                'label' => trans('admin::app.components.date-range-picker.presets.last-month'),
                'from' => now()->subMonth(1)->startOfMonth()->format($format),
                'to' => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name' => self::LAST_THREE_MONTHS->value,
                'label' => trans('admin::app.components.date-range-picker.presets.last-three-months'),
                'from' => now()->subMonth(3)->startOfMonth()->format($format),
                'to' => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name' => self::LAST_SIX_MONTHS->value,
                'label' => trans('admin::app.components.date-range-picker.presets.last-six-months'),
                'from' => now()->subMonth(6)->startOfMonth()->format($format),
                'to' => now()->subMonth(1)->endOfMonth()->format($format),
            ],
            [
                'name' => self::THIS_YEAR->value,
                'label' => trans('admin::app.components.date-range-picker.presets.this-year'),
                'from' => now()->startOfYear()->format($format),
                'to' => now()->endOfYear()->format($format),
            ],
        ];
    }
}
