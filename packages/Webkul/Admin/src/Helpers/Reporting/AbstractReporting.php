<?php

namespace Webkul\Admin\Helpers\Reporting;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

abstract class AbstractReporting
{
    /**
     * The starting date for a given period.
     *
     * @var \Carbon\Carbon
     */
    protected Carbon $startDate;

    /**
     * The ending date for a given period.
     *
     * @var \Carbon\Carbon
     */
    protected Carbon $endDate;

    /**
     * The starting date for the previous period.
     *
     * @var \Carbon\Carbon
     */
    protected Carbon $lastStartDate;

    /**
     * The ending date for the previous period.
     *
     * @var \Carbon\Carbon
     */
    protected Carbon $lastEndDate;

    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setStartDate(request()->date('start'));

        $this->setEndDate(request()->date('end'));
    }

    /**
     * Set the start date or default to 30 days ago if not provided.
     *
     * @param  \Carbon\Carbon|null  $startDate
     * @return void
     */
    public function setStartDate(?Carbon $startDate = null): self
    {
        $this->startDate = $startDate ? $startDate->startOfDay() : now()->subDays(30)->startOfDay();

        $this->setLastStartDate();

        return $this;
    }

    /**
     * Sets the end date to the provided date's end of day, or to the current
     * date if not provided or if the provided date is in the future.
     *
     * @param  \Carbon\Carbon|null  $endDate
     * @return void
     */
    public function setEndDate(?Carbon $endDate = null): self
    {
        $this->endDate = ($endDate && $endDate->endOfDay() <= now()) ? $endDate->endOfDay() : now();

        $this->setLastEndDate();

        return $this;
    }

    /**
     * Get the start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    /**
     * Get the end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->endDate;
    }

    /**
     * Sets the start date for the last period.
     */
    private function setLastStartDate(): void
    {
        if (! isset($this->startDate)) {
            $this->setStartDate(request()->date('start'));
        }

        if (! isset($this->endDate)) {
            $this->setEndDate(request()->date('end'));
        }

        $this->lastStartDate = $this->startDate->clone()->subDays($this->startDate->diffInDays($this->endDate));
    }

    /**
     * Sets the end date for the last period.
     */
    private function setLastEndDate(): void
    {
        $this->lastEndDate = $this->startDate->clone();
    }

    /**
     * Get the last start date.
     *
     * @return \Carbon\Carbon
     */
    public function getLastStartDate(): Carbon
    {
        return $this->lastStartDate;
    }

    /**
     * Get the last end date.
     *
     * @return \Carbon\Carbon
     */
    public function getLastEndDate(): Carbon
    {
        return $this->lastEndDate;
    }

    /**
     * Calculate the percentage change between previous and current values.
     *
     * @param  float|int  $previous
     * @param  float|int  $current
     */
    public function getPercentageChange($previous, $current): float|int
    {
        if (! $previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }

    /**
     * Returns time intervals.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @return array
     */
    public function getTimeInterval($startDate, $endDate, $period)
    {
        if ($period == 'auto') {
            $totalMonths = $startDate->diffInMonths($endDate) + 1;

            /**
             * If the difference between the start and end date is more than 5 months
             */
            $intervals = $this->getMonthsInterval($startDate, $endDate);

            if (! empty($intervals)) {
                return [
                    'group_column' => 'MONTH(created_at)',
                    'intervals'    => $intervals,
                ];
            }

            /**
             * If the difference between the start and end date is more than 6 weeks
             */
            $intervals = $this->getWeeksInterval($startDate, $endDate);

            if (! empty($intervals)) {
                return [
                    'group_column' => 'WEEK(created_at)',
                    'intervals'    => $intervals,
                ];
            }

            /**
             * If the difference between the start and end date is less than 6 weeks
             */
            return [
                'group_column' => 'DAYOFYEAR(created_at)',
                'intervals'    => $this->getDaysInterval($startDate, $endDate),
            ];
        } else {
            $datePeriod = CarbonPeriod::create($this->startDate, "1 $period", $this->endDate);

            if ($period == 'year') {
                $formatter = '?';
            } elseif ($period == 'month') {
                $formatter = '?-?';
            } else {
                $formatter = '?-?-?';
            }

            $groupColumn = 'DATE_FORMAT(created_at, "'.Str::replaceArray('?', ['%Y', '%m', '%d'], $formatter).'")';

            $intervals = [];

            foreach ($datePeriod as $date) {
                $formattedDate = $date->format(Str::replaceArray('?', ['Y', 'm', 'd'], $formatter));

                $intervals[] = [
                    'filter' => $formattedDate,
                    'start'  => $formattedDate,
                ];
            }

            return [
                'group_column' => $groupColumn,
                'intervals'    => $intervals,
            ];
        }
    }

    /**
     * Returns time intervals.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getMonthsInterval($startDate, $endDate)
    {
        $intervals = [];

        $totalMonths = $startDate->diffInMonths($endDate) + 1;

        /**
         * If the difference between the start and end date is less than 5 months
         */
        if ($totalMonths <= 5) {
            return $intervals;
        }

        for ($i = 0; $i < $totalMonths; $i++) {
            $intervalStartDate = clone $startDate;

            $intervalStartDate->addMonths($i);

            $start = $intervalStartDate->startOfDay();

            $end = ($totalMonths - 1 == $i)
                ? $endDate
                : $intervalStartDate->addMonth()->subDay()->endOfDay();

            $intervals[] = [
                'filter' => $start->month,
                'start'  => $start->format('d M'),
                'end'    => $end->format('d M'),
            ];
        }

        return $intervals;
    }

    /**
     * Returns time intervals.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getWeeksInterval($startDate, $endDate)
    {
        $intervals = [];

        $startWeekDay = Carbon::createFromTimeString(core()->xWeekRange($startDate, 0).' 00:00:01');

        $endWeekDay = Carbon::createFromTimeString(core()->xWeekRange($endDate, 1).' 23:59:59');

        $totalWeeks = $startWeekDay->diffInWeeks($endWeekDay);

        /**
         * If the difference between the start and end date is less than 6 weeks
         */
        if ($totalWeeks <= 6) {
            return $intervals;
        }

        for ($i = 0; $i < $totalWeeks; $i++) {
            $intervalStartDate = clone $startDate;

            $intervalStartDate->addWeeks($i);

            $start = $i == 0
                ? $startDate
                : Carbon::createFromTimeString(core()->xWeekRange($intervalStartDate, 0).' 00:00:01');

            $end = ($totalWeeks - 1 == $i)
                ? $endDate
                : Carbon::createFromTimeString(core()->xWeekRange($intervalStartDate->subDay(), 1).' 23:59:59');

            $intervals[] = [
                'filter' => $start->week,
                'start'  => $start->format('d M'),
                'end'    => $end->format('d M'),
            ];
        }

        return $intervals;
    }

    /**
     * Returns time intervals.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getDaysInterval($startDate, $endDate)
    {
        $intervals = [];

        $totalDays = $startDate->diffInDays($endDate) + 1;

        for ($i = 0; $i < $totalDays; $i++) {
            $intervalStartDate = clone $startDate;

            $intervalStartDate->addDays($i);

            $intervals[] = [
                'filter' => $intervalStartDate->dayOfYear,
                'start'  => $intervalStartDate->startOfDay()->format('d M'),
                'end'    => $intervalStartDate->endOfDay()->format('d M'),
            ];
        }

        return $intervals;
    }
}
