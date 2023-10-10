<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Support\Carbon;

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
     * The start date for the previous day.
     * 
     * @var \Carbon\Carbon
     */
    protected Carbon $yesterdayStartDate;

    /**
     * The end date for the previous day.
     * 
     * @var \Carbon\Carbon
     */
    protected Carbon $yesterdayEndDate;

    /**
     * Create a helper instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->setStartDate(request()->date('start'));

        $this->setEndDate(request()->date('end'));

        $this->setLastStartDate();

        $this->setLastEndDate();
        
        $this->yesterdayStartDate = now()->subDay()->startOfDay();

        $this->yesterdayEndDate = now()->subDay()->endOfDay();
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
     * Set the start date or default to 30 days ago if not provided.
     * 
     * @param  \Carbon\Carbon|null  $startDate
     * @return void
     */
    public function setStartDate(?Carbon $startDate = null): self
    {
        $this->startDate = $startDate ? $startDate->startOfDay() : now()->subDays(30)->startOfDay();

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

        return $this;
    }

    /**
     * Sets the start date for the last period.
     * 
     * @return void
     */
    private function setLastStartDate(): void
    {
        if (! isset($this->startDate)) {
            $this->setStartDate();
        }

        if (! isset($this->endDate)) {
            $this->setEndDate();
        }

        $this->lastStartDate = $this->startDate->clone()->subDays($this->startDate->diffInDays($this->endDate));
    }

    /**
     * Sets the end date for the last period.
     * 
     * @return void
     */
    private function setLastEndDate(): void
    {
        $this->lastEndDate = $this->startDate->clone();
    }

    /**
     * Calculate the percentage change between previous and current values.
     *
     * @param  float|int  $previous
     * @param  float|int  $current
     * @return float|int
     */
    protected function getPercentageChange($previous, $current): float|int
    {
        if (!$previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }

    /**
     * Returns time intervals.
     *
     * @return array
     */
    public function getTimeInterval()
    {
        $timeIntervals = [];

        $totalMonths = $this->startDate->diffInMonths($this->endDate) + 1;

        /**
         * If the difference between the start and end date is more than 5 months
         */
        if ($totalMonths > 5) {
            for ($i = 0; $i < $totalMonths; $i++) {
                $startDate = clone $this->startDate;

                $startDate->addMonths($i);

                $start = Carbon::createFromTimeString($startDate->format('Y-m-d') . ' 00:00:01');

                $end = ($totalMonths - 1 == $i)
                    ? $this->endDate
                    : Carbon::createFromTimeString($startDate->addMonth()->subDay()->format('Y-m-d') . ' 23:59:59');

                $timeIntervals[] = [
                    'start'         => $start,
                    'end'           => $end,
                    'formattedDate' => $startDate->format('M'),
                ];
            }

            return $timeIntervals;
        }
        
        $startWeekDay = Carbon::createFromTimeString(core()->xWeekRange($this->startDate, 0) . ' 00:00:01');

        $endWeekDay = Carbon::createFromTimeString(core()->xWeekRange($this->endDate, 1) . ' 23:59:59');

        $totalWeeks = $startWeekDay->diffInWeeks($endWeekDay);

        /**
         * If the difference between the start and end date is more than 6 weeks
         */
        if ($totalWeeks > 6) {
            for ($i = 0; $i < $totalWeeks; $i++) {
                $startDate = clone $this->startDate;

                $startDate->addWeeks($i);

                $start = $i == 0
                    ? $this->startDate
                    : Carbon::createFromTimeString(core()->xWeekRange($startDate, 0) . ' 00:00:01');

                $end = ($totalWeeks - 1 == $i)
                    ? $this->endDate
                    : Carbon::createFromTimeString(core()->xWeekRange($startDate->subDay(), 1) . ' 23:59:59');

                $timeIntervals[] = [
                    'start'         => $start,
                    'end'           => $end,
                    'formattedDate' => $startDate->format('d M'),
                ];
            }

            return $timeIntervals;
        }

        /**
         * If the difference between the start and end date is less than 6 weeks
         */
        $totalDays = $this->startDate->diffInDays($this->endDate) + 1;

        for ($i = 0; $i < $totalDays; $i++) {
            $startDate = clone $this->startDate;

            $startDate->addDays($i);

            $timeIntervals[] = [
                'start'         => Carbon::createFromTimeString($startDate->format('Y-m-d') . ' 00:00:01'),
                'end'           => Carbon::createFromTimeString($startDate->format('Y-m-d') . ' 23:59:59'),
                'formattedDate' => $startDate->format('d M'),
            ];
        }

        return $timeIntervals;
    }
}