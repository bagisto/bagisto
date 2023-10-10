<?php

namespace Webkul\Admin\Helpers\Reporting;

use Webkul\Customer\Repositories\CustomerRepository;

class Customer extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @return void
     */
    public function __construct(protected CustomerRepository $customerRepository)
    {
        parent::__construct();
    }

    /**
     * Retrieves total customers and their progress.
     * 
     * @param  \Carbon\Carbon|null  $startDate
     * @param  \Carbon\Carbon|null  $endDate
     * @return array
     */
    public function getTotalCustomersProgress($startDate = null, $endDate = null): array
    {
        $previous = $this->customerRepository->getCustomersCountByDate(
            $startDate ? $this->yesterdayEndDate : $this->lastStartDate,
            $endDate ? $this->yesterdayStartDate : $this->lastEndDate
        );
    
        $current = $this->customerRepository->getCustomersCountByDate(
            $startDate ?? $this->startDate,
            $endDate ?? $this->endDate
        );

        return [
            'previous' => $previous,
            'current'  => $current,
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }
}