<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Repositories\VisitRepository;

class Visitor extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Core\Repositories\VisitRepository  $visitRepository
     * @return void
     */
    public function __construct(protected VisitRepository $visitRepository)
    {
        parent::__construct();
    }

    /**
     * Retrieves total visitors and their progress.
     * 
     * @param  string  $visitableType
     * @return array
     */
    public function getTotalVisitorsProgress($visitableType = null): array
    {
        return [
            'previous' => $previous = $this->getTotalVisitors($this->lastStartDate, $this->lastEndDate, $visitableType),
            'current'  => $current = $this->getTotalVisitors($this->startDate, $this->endDate, $visitableType),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total visitors and their progress.
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $visitableType
     * @return array
     */
    public function getTotalVisitors($startDate, $endDate, $visitableType = null): int
    {
        if ($visitableType) {
            $this->visitRepository
                ->whereNull('visitable_id')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id, "-", visitable_type)'))
                ->get()
                ->count();
        }

        return $this->visitRepository
            ->whereNull('visitable_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'))
            ->get()
            ->count();
    }

    /**
     * Retrieves unique visitors and their progress.
     * 
     * @param  string  $visitableType
     * @return array
     */
    public function getTotalUniqueVisitorsProgress($visitableType = null): array
    {
        return [
            'previous' => $previous = $this->getTotalUniqueVisitors($this->lastStartDate, $this->lastEndDate, $visitableType),
            'current'  => $current = $this->getTotalUniqueVisitors($this->startDate, $this->endDate, $visitableType),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total unique visitors
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $visitableType
     * @return array
     */
    public function getTotalUniqueVisitors($startDate, $endDate, $visitableType = null): int
    {
        if ($visitableType) {
            return $this->visitRepository
                ->where('visitable_type', $visitableType)
                ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id, "-", visitable_type)'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->count();
        }

        return $this->visitRepository
            ->whereNull('visitable_id')
            ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->count();
    }

    /**
     * Returns previous sales over time
     * 
     * @param  string  $visitableType
     * @return array
     */
    public function getPreviousTotalVisitorsOverTime($visitableType = null): array
    {
        return $this->getTotalVisitorsOverTime($this->lastStartDate, $this->lastEndDate, $visitableType);
    }

    /**
     * Returns current sales over time
     * 
     * @param  string  $visitableType
     * @return array
     */
    public function getCurrentTotalVisitorsOverTime($visitableType = null): array
    {
        return $this->getTotalVisitorsOverTime($this->startDate, $this->endDate, $visitableType);
    }

    /**
     * Generates visitor graph data.
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $visitableType
     * @return array
     */
    public function getTotalVisitorsOverTime($startDate, $endDate, $visitableType = null): array
    {
        $stats = [];

        $timeIntervals = $this->getTimeInterval($startDate, $endDate);

        $formatter = strtoupper($timeIntervals['type']) . '(created_at)';

        $visits = $this->visitRepository
            ->select(
                DB::raw("$formatter AS date"),
                DB::raw('COUNT(*) AS count')
            )
            ->whereNull('visitable_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($formatter))
            ->get();

        foreach ($timeIntervals['intervals'] as $interval) {
            $total = $visits->where('date', $interval['start']->{$timeIntervals['type']})->first();

            $stats['label'][] = $interval['start']->format('d M');
            $stats['total'][] = $total?->count ?? 0;
        }

        return $stats;
    }
}