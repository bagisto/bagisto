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
     * @return array
     */
    public function getTotalCustomersProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalCustomers($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalCustomers($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves today customers and their progress.
     * 
     * @return array
     */
    public function getTodayCustomersProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalCustomers(now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalCustomers(now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total customers by date
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return integer
     */
    public function getTotalCustomers($startDate, $endDate): int
    {
        return $this->customerRepository->getCustomersCountByDate($startDate, $endDate);
    }
}