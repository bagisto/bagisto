<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Product\Repositories\ProductReviewRepository;

class Customer extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $reviewRepository
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected OrderRepository $orderRepository,
        protected ProductReviewRepository $reviewRepository
    )
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
     * Returns previous customers over time
     * 
     * @return array
     */
    public function getPreviousTotalCustomersOverTime(): array
    {
        return $this->getTotalCustomersOverTime($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current customers over time
     * 
     * @return array
     */
    public function getCurrentTotalCustomersOverTime(): array
    {
        return $this->getTotalCustomersOverTime($this->startDate, $this->endDate);
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

    /**
     * Retrieves total reviews and their progress.
     * 
     * @return array
     */
    public function getTotalReviewsProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalReviews($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalReviews($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total reviews by date
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return integer
     */
    public function getTotalReviews($startDate, $endDate): int
    {
        return $this->reviewRepository
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Gets customer with most sales.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersWithMostSales(): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->orderRepository
            ->addSelect(
                'orders.customer_id as id',
                'orders.customer_email as email',
                DB::raw('CONCAT(' . $tablePrefix . 'orders.customer_first_name, " ", ' . $tablePrefix . 'orders.customer_last_name) as full_name'),
                DB::raw('SUM(base_grand_total_invoiced - base_grand_total_refunded) as total'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Gets customer with most orders.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersWithMostOrders(): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->orderRepository
            ->addSelect(
                'orders.customer_id as id',
                'orders.customer_email as email',
                DB::raw('CONCAT(' . $tablePrefix . 'orders.customer_first_name, " ", ' . $tablePrefix . 'orders.customer_last_name) as full_name'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->orderByDesc('orders')
            ->get();
    }

    /**
     * Gets customer with most orders.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersWithMostReviews(): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->reviewRepository
            ->leftJoin('customers', 'product_reviews.customer_id', '=', 'customers.id')
            ->addSelect(
                'customers.id as id',
                'customers.email as email',
                DB::raw('CONCAT(' . $tablePrefix . 'customers.first_name, " ", ' . $tablePrefix . 'customers.last_name) as full_name'),
                DB::raw('COUNT(*) as reviews')
            )
            ->whereBetween('product_reviews.created_at', [$this->startDate, $this->endDate])
            ->where('product_reviews.status', 'approved')
            ->whereNotNull('customer_id')
            ->groupBy(DB::raw('CONCAT(email, "-", customers.id)'))
            ->orderByDesc('reviews')
            ->get();
    }

    /**
     * Gets customer with most sales.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGroupsWithMostCustomers(): Collection
    {
        return $this->customerRepository
            ->leftJoin('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
            ->select('customers.id as id', 'customer_groups.name as group_name')
            ->addSelect(DB::raw('COUNT(*) as total'))
            ->whereBetween('customers.created_at', [$this->startDate, $this->endDate])
            ->groupBy('customer_group_id')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Returns over time stats.
     * 
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalCustomersOverTime($startDate, $endDate): array
    {
        $stats = [];

        $timeIntervals = $this->getTimeInterval($startDate, $endDate);

        $formatter = strtoupper($timeIntervals['type']) . '(created_at)';

        $sales = $this->customerRepository
            ->select(
                DB::raw("$formatter AS date"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw($formatter))
            ->get();

        foreach ($timeIntervals['intervals'] as $interval) {
            $total = $sales->where('date', $interval['start']->{$timeIntervals['type']})->first();

            $stats['label'][] = $interval['start']->format('d M');
            $stats['total'][] = $total?->total ?? 0;
        }

        return $stats;
    }
}