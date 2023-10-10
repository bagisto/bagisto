<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Core\Repositories\VisitRepository;

class Reporting
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
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Core\Repositories\VisitRepository  $visitRepository
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected InvoiceRepository $invoiceRepository,
        protected CustomerRepository $customerRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected ProductRepository $productRepository,
        protected VisitRepository $visitRepository,
    ) {
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
    private function getPercentageChange($previous, $current): float|int
    {
        if (!$previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }

    /**
     * Retrieves total customers and their progress.
     * 
     * @param  \Carbon\Carbon|null  $todayStartOfDay
     * @param  \Carbon\Carbon|null  $todayEndOfDay
     * @return array
     */
    public function getTotalCustomers($todayStartOfDay = null, $todayEndOfDay = null): array
    {
        if ($todayStartOfDay && $todayEndOfDay) {
            return [
                'previous' => $previous = $this->customerRepository->getCustomersCountByDate($this->yesterdayEndDate, $this->yesterdayStartDate),
                'current'  => $current = $this->customerRepository->getCustomersCountByDate($todayStartOfDay, $todayEndOfDay),
                'progress' => $this->getPercentageChange($previous, $current)
            ];
        }

        return [
            'previous' => $previous = $this->customerRepository->getCustomersCountByDate($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->customerRepository->getCustomersCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total orders and their progress.
     * 
     * @param  \Carbon\Carbon|null  $todayStartOfDay
     * @param  \Carbon\Carbon|null  $todayEndOfDay
     * @return array
     */
    public function getTotalOrders($todayStartOfDay = null, $todayEndOfDay = null)
    {
        if ($todayStartOfDay && $todayEndOfDay) {
            return [
                'previous' => $previous = $this->orderRepository->getOrdersCountByDate($this->yesterdayEndDate, $this->yesterdayStartDate),
                'current'  => $current = $this->orderRepository->getOrdersByDate($todayStartOfDay, $todayEndOfDay),
                'progress' => $this->getPercentageChange($previous, count($current))
            ];
        }

        return [
            'previous' => $previous = $this->orderRepository->getOrdersCountByDate($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->orderRepository->getOrdersCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves total sales and their progress.
     * 
     * @param  \Carbon\Carbon|null  $todayStartOfDay
     * @param  \Carbon\Carbon|null  $todayEndOfDay
     * @return array
     */
    public function getTotalSales($todayStartOfDay = null, $todayEndOfDay = null): array
    {
        if ($todayStartOfDay && $todayEndOfDay) {
            return [
                'previous' => $previous = $this->orderRepository->getTotalSaleAmountByDate($this->yesterdayEndDate, $this->yesterdayStartDate),
                'current'  => $current = $this->orderRepository->getTotalSaleAmountByDate($todayStartOfDay, $todayEndOfDay),
                'progress' => $this->getPercentageChange($previous, $current)
            ];
        }

        $getTotalSales = $this->orderRepository->getTotalSaleAmountByDate($this->startDate, $this->endDate);

        return [
            'previous'        => $previous = $this->orderRepository->getTotalSaleAmountByDate($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $getTotalSales,
            'formatted_total' => core()->formatBasePrice($getTotalSales),
            'progress'        => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Retrieves average sales and their progress.
     * 
     * @return array
     */
    public function getAvgSales(): array
    {
        return [
            'previous' => $previous = $this->orderRepository->GetAvgSaleAmountByDate($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->orderRepository->GetAvgSaleAmountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current)
        ];
    }

    /**
     * Gets the total amount of pending invoices.
     * 
     * @return float
     */
    public function getTotalAmountOfPendingInvoices(): float
    {
        return $this->invoiceRepository->getTotalAmountOfPendingInvoices();
    }

    /**
     * Gets the top-selling categories.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getTopSellingCategories(): SupportCollection
    {
        $orderItems = $this->orderItemRepository->getTopSellingOrderItemsByDate($this->startDate, $this->endDate);

        $categories = $this->getCategoryStats($orderItems)->collapse()->unique('category_id')->values();

        return $categories->map(fn (array $category) => (object) $category);
    }

    /**
     * Calculates category statistics from order items.
     * 
     * @param  \Illuminate\Database\Eloquent\Collection  $orderItems
     * @return \Illuminate\Support\Collection
     */
    private function getCategoryStats(Collection $orderItems): SupportCollection
    {
        $products = $this->productRepository->whereIn('id', $orderItems->pluck('product_id'))->with('categories')->get();

        return $orderItems->map(function ($orderItem) use ($products) {
            $product = $products->firstWhere('id', $orderItem->product_id);

            if ($product) {
                return $this->getCategoryDetails($product, $orderItem);
            }
        });
    }

    /**
     * Gets category details.
     * 
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return \Illuminate\Support\Collection
     */
    private function getCategoryDetails(Product $product, OrderItem $orderItem): SupportCollection
    {
        return $product->categories->map(function ($category) use ($orderItem) {
            return [
                'total_qty_invoiced' => $orderItem->total_qty_invoiced,
                'total_products'     => $category->products->count(),
                'category_id'        => $category->id,
                'name'               => $category->translations->where('locale', app()->getLocale())->first()?->name,
            ];
        });
    }

    /**
     * Gets top-selling products.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopSellingProducts(): collection
    {
        $topSellingProducts = $this->orderItemRepository->getTopSellingProductsByDate($this->startDate, $this->endDate);

        foreach ($topSellingProducts as $orderItem) {
            $orderItem->formatted_total = core()->formatBasePrice($orderItem->total);

            $orderItem->formatted_price = core()->formatBasePrice($orderItem->price);
        }

        return $topSellingProducts;
    }

    /**
     * Gets customer with most sales.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomerWithMostSales(): Collection
    {
        $customerWithMostSales = $this->orderRepository->getCustomerWithMostSalesByDate($this->startDate, $this->endDate);

        foreach ($customerWithMostSales as $order) {
            $order->formatted_total_base_grand_total = core()->formatBasePrice($order->total_base_grand_total);
        }

        return $customerWithMostSales;
    }

    /**
     * Gets stock threshold.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockThreshold(): Collection
    {
        return $this->productInventoryRepository->getStockThreshold();
    }

    /**
     * Generates sale graph data.
     * 
     * @return array
     */
    public function generateSaleGraph(): array
    {
        $data = [];

        $sales = $this->orderRepository->getPerDayTotalSaleAmountByDate($this->startDate, $this->endDate);

        foreach ($this->getTimeInterval() as $interval) {
            $total = $sales->where('date', $interval['start']->format('Y-m-d'))->first();

            $data['label'][] = $interval['start']->format('d M');
            $data['total'][] = $total?->total ?? 0;
            $data['formatted_total'][] = core()->formatBasePrice($total?->total ?? 0);
        }

        return $data;
    }

    /**
     * Retrieves total visitors and their progress.
     * 
     * @param  \Carbon\Carbon|null  $todayStartOfDay
     * @param  \Carbon\Carbon|null  $todayEndOfDay
     * @return array
     */
    public function getTotalVisitors($todayStartOfDay = null, $todayEndOfDay = null)
    {
        if ($todayStartOfDay && $todayEndOfDay) {
            return [
                'previous' => $previous = $this->visitRepository->getTotalCountByDate($this->yesterdayEndDate, $this->yesterdayStartDate),
                'current'  => $current = $this->visitRepository->getTotalCountByDate($todayStartOfDay, $todayEndOfDay),
                'progress' => $this->getPercentageChange($previous, $current),
            ];
        }

        return [
            'previous' => $previous = $this->visitRepository->getTotalCountByDate($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->visitRepository->getTotalCountByDate($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves unique visitors and their progress.
     * 
     * @return array
     */
    public function getUniqueVisitors(): array
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
    public function generateVisitorGraph(): array
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

    /**
     * Returns today's details
     * 
     * @return array
     */
    public function getTodayDetails(): array
    {
        $todayStartOfDay = now()->today();

        $todayEndOfDay = now()->endOfDay();

        return [
            'today_customers' => $this->getTotalCustomers($todayStartOfDay, $todayEndOfDay),
            'today_sales'     => $this->getTotalSales($todayStartOfDay, $todayEndOfDay),
            'today_orders'    => $this->getTotalOrders($todayStartOfDay, $todayEndOfDay),
        ];
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
                $date = clone $this->startDate;

                $date->addMonths($i);

                $start = Carbon::createFromTimeString($date->format('Y-m-d') . ' 00:00:01');

                $end = $totalMonths - 1 == $i
                    ? $this->endDate
                    : Carbon::createFromTimeString($date->addMonth()->subDay()->format('Y-m-d') . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formattedDate' => $date->format('M')];
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
                $date = clone $this->startDate;

                $date->addWeeks($i);

                $start = $i == 0
                    ? $this->startDate
                    : Carbon::createFromTimeString(core()->xWeekRange($date, 0) . ' 00:00:01');

                $end = $totalWeeks - 1 == $i
                    ? $this->endDate
                    : Carbon::createFromTimeString(core()->xWeekRange($date->subDay(), 1) . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formattedDate' => $date->format('d M')];
            }

            return $timeIntervals;
        }

        /**
         * If the difference between the start and end date is less than 6 weeks
         */
        $totalDays = $this->startDate->diffInDays($this->endDate) + 1;

        for ($i = 0; $i < $totalDays; $i++) {
            $date = clone $this->startDate;

            $date->addDays($i);

            $start = Carbon::createFromTimeString($date->format('Y-m-d') . ' 00:00:01');

            $end = Carbon::createFromTimeString($date->format('Y-m-d') . ' 23:59:59');

            $timeIntervals[] = ['start' => $start, 'end' => $end, 'formattedDate' => $date->format('d M')];
        }

        return $timeIntervals;
    }
}
