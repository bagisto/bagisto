<?php

namespace Webkul\Admin\Helpers\Reporting;

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
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return array
     */
    public function getTotalVisitorsProgress($startDate = null, $endDate = null): array
    {
        $previous = $this->visitRepository->getTotalCountByDate(
            $startDate ? $this->yesterdayEndDate : $this->lastStartDate,
            $endDate ? $this->yesterdayStartDate : $this->lastEndDate
        );
    
        $current = $this->visitRepository->getTotalCountByDate(
            $startDate ?? $this->startDate,
            $endDate ?? $this->endDate
        );

        return [
            'previous' => $previous,
            'current'  => $current,
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves unique visitors and their progress.
     * 
     * @return array
     */
    public function getTotalUniqueVisitorsProcess(): array
    {
        return [
            'previous' => $previous = $this->visitRepository->getTotalUniqueCountByDate($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->visitRepository->getTotalUniqueCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Generates visitor graph data.
     * 
     * @return array
     */
    public function getVisitorsOverTime(): array
    {
        $data = [];

        $visits = $this->visitRepository->getPerDayTotalCountDate($this->startDate, $this->endDate);
        
        foreach ($this->getTimeInterval() as $interval) {
            $total = $visits->where('date', $interval['start']->format('Y-m-d'))->first();

            $data['label'][] = $interval['start']->format('d M');
            $data['total'][] = $total?->count ?? 0;
        }

        return $data;
    }
}