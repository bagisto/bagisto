<?php

namespace Webkul\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;

    /**
     * Order repository instance.
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Order item repository instance.
     *
     * @var \Webkul\Sales\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * Invoice repository instance.
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Customer repository instance.
     *
     * @var \Webkul\Customer\Repositories\CustomerRepository
     */
    protected $customerRepository;

    /**
     * Product inventory repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductInventoryRepository
     */
    protected $productInventoryRepository;

    /**
     * Start date.
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $startDate;

    /**
     * Last start date.
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $lastStartDate;

    /**
     * End date.
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $endDate;

    /**
     * Last end date.
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $lastEndDate;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        InvoiceRepository $invoiceRepository,
        CustomerRepository $customerRepository,
        ProductInventoryRepository $productInventoryRepository
    ) {
        $this->_config = request('_config');

        $this->middleware('admin');

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->invoiceRepository = $invoiceRepository;

        $this->customerRepository = $customerRepository;

        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->setStartEndDate();

        $statistics = [
            /**
             * These are the stats with percentage change.
             */
            'total_customers'          => [
                'previous' => $previous = $this->getCustomersBetweenDates($this->lastStartDate, $this->lastEndDate),
                'current'  => $current = $this->getCustomersBetweenDates($this->startDate, $this->endDate),
                'progress' => $this->getPercentageChange($previous, $current),
            ],
            'total_orders'             =>  [
                'previous' => $previous = $this->previousOrders()->count(),
                'current'  => $current = $this->currentOrders()->count(),
                'progress' => $this->getPercentageChange($previous, $current),
            ],
            'total_sales'              =>  [
                'previous' => $previous = $this->previousOrders()->sum('base_grand_total_invoiced') - $this->previousOrders()->sum('base_grand_total_refunded'),
                'current'  => $current = $this->currentOrders()->sum('base_grand_total_invoiced') - $this->currentOrders()->sum('base_grand_total_refunded'),
                'progress' => $this->getPercentageChange($previous, $current),
            ],
            'avg_sales'                =>  [
                'previous' => $previous = $this->previousOrders()->avg('base_grand_total_invoiced') - $this->previousOrders()->avg('base_grand_total_refunded'),
                'current'  => $current = $this->currentOrders()->avg('base_grand_total_invoiced') - $this->currentOrders()->avg('base_grand_total_refunded'),
                'progress' => $this->getPercentageChange($previous, $current),
            ],

            /**
             * These are the normal stats.
             */
            'total_unpaid_invoices'    => $this->getTotalPendingInvoices(),
            'top_selling_categories'   => $this->getTopSellingCategories(),
            'top_selling_products'     => $this->getTopSellingProducts(),
            'customer_with_most_sales' => $this->getCustomerWithMostSales(),
            'stock_threshold'          => $this->getStockThreshold(),
        ];

        foreach (core()->getTimeInterval($this->startDate, $this->endDate) as $interval) {
            $statistics['sale_graph']['label'][] = $interval['start']->format('d M');

            $total = $this->getOrdersBetweenDate($interval['start'], $interval['end'])->sum('base_grand_total_invoiced') - $this->getOrdersBetweenDate($interval['start'], $interval['end'])->sum('base_grand_total_refunded');

            $statistics['sale_graph']['total'][] = $total;
            $statistics['sale_graph']['formated_total'][] = core()->formatBasePrice($total);
        }

        return view($this->_config['view'], compact('statistics'))->with(['startDate' => $this->startDate, 'endDate' => $this->endDate]);
    }

    /**
     * Sets start and end date.
     *
     * @return void
     */
    public function setStartEndDate()
    {
        $this->startDate = request()->get('start')
            ? Carbon::createFromTimeString(request()->get('start') . " 00:00:01")
            : Carbon::createFromTimeString(Carbon::now()->subDays(30)->format('Y-m-d') . " 00:00:01");

        $this->endDate = request()->get('end')
            ? Carbon::createFromTimeString(request()->get('end') . " 23:59:59")
            : Carbon::now();

        if ($this->endDate > Carbon::now()) {
            $this->endDate = Carbon::now();
        }

        $this->lastStartDate = clone $this->startDate;
        $this->lastEndDate = clone $this->startDate;

        $this->lastStartDate->subDays($this->startDate->diffInDays($this->endDate));
    }

    /**
     * Returns percentage difference
     *
     * @param  int  $previous
     * @param  int  $current
     * @return int
     */
    public function getPercentageChange($previous, $current)
    {
        if (! $previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }

    /**
     * Returns the list of top selling categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTopSellingCategories()
    {
        return $this->orderItemRepository->getModel()
            ->leftJoin('products', 'order_items.product_id', 'products.id')
            ->leftJoin('product_categories', 'products.id', 'product_categories.product_id')
            ->leftJoin('categories', 'product_categories.category_id', 'categories.id')
            ->leftJoin('category_translations', 'categories.id', 'category_translations.category_id')
            ->where('category_translations.locale', app()->getLocale())
            ->where('order_items.created_at', '>=', $this->startDate)
            ->where('order_items.created_at', '<=', $this->endDate)
            ->addSelect(DB::raw('SUM(qty_invoiced - qty_refunded) as total_qty_invoiced'))
            ->addSelect(DB::raw('COUNT(' . DB::getTablePrefix() . 'products.id) as total_products'))
            ->addSelect('order_items.id', 'categories.id as category_id', 'category_translations.name')
            ->groupBy('categories.id')
            ->havingRaw('SUM(qty_invoiced - qty_refunded) > 0')
            ->orderBy('total_qty_invoiced', 'DESC')
            ->limit(5)
            ->get();
    }

    /**
     * Return stock threshold.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getStockThreshold()
    {
        return $this->productInventoryRepository->getModel()
            ->leftJoin('products', 'product_inventories.product_id', 'products.id')
            ->select(DB::raw('SUM(qty) as total_qty'))
            ->addSelect('product_inventories.product_id')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'ASC')
            ->limit(5)
            ->get();
    }

    /**
     * Returns top selling products.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTopSellingProducts()
    {
        return $this->orderItemRepository->getModel()
            ->select(DB::raw('SUM(qty_ordered) as total_qty_ordered'))
            ->addSelect('id', 'product_id', 'product_type', 'name')
            ->where('order_items.created_at', '>=', $this->startDate)
            ->where('order_items.created_at', '<=', $this->endDate)
            ->whereNull('parent_id')
            ->groupBy('product_id')
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(5)
            ->get();
    }

    /**
     * Returns cutomer with most sales.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCustomerWithMostSales()
    {
        $dbPrefix = DB::getTablePrefix();

        return $this->orderRepository->getModel()
            ->leftJoin('refunds', 'orders.id', 'refunds.order_id')
            ->select(DB::raw("(SUM({$dbPrefix}orders.base_grand_total) - SUM(IFNULL({$dbPrefix}refunds.base_grand_total, 0))) as total_base_grand_total"))
            ->addSelect(DB::raw("COUNT({$dbPrefix}orders.id) as total_orders"))
            ->addSelect('orders.id', 'customer_id', 'customer_email', 'customer_first_name', 'customer_last_name')
            ->where('orders.created_at', '>=', $this->startDate)
            ->where('orders.created_at', '<=', $this->endDate)
            ->where('orders.status', '<>', 'closed')
            ->where('orders.status', '<>', 'canceled')
            ->groupBy('customer_email')
            ->orderBy('total_base_grand_total', 'DESC')
            ->limit(5)
            ->get();
    }

    /**
     * Returns previous order query.
     *
     * @return Illuminate\Database\Query\Builder
     */
    private function previousOrders()
    {
        return $this->getOrdersBetweenDate($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current order query.
     *
     * @return Illuminate\Database\Query\Builder
     */
    private function currentOrders()
    {
        return $this->getOrdersBetweenDate($this->startDate, $this->endDate);
    }

    /**
     * Returns orders between two dates.
     *
     * @param  \Illuminate\Support\Carbon  $start
     * @param  \Illuminate\Support\Carbon  $end
     * @return Illuminate\Database\Query\Builder
     */
    private function getOrdersBetweenDate($start, $end)
    {
        return $this->orderRepository->scopeQuery(function ($query) use ($start, $end) {
            return $query->where('orders.created_at', '>=', $start)->where('orders.created_at', '<=', $end);
        });
    }

    /**
     * Returns customers between two dates.
     *
     * @param  \Illuminate\Support\Carbon  $start
     * @param  \Illuminate\Support\Carbon  $end
     * @return int
     */
    private function getCustomersBetweenDates($start, $end)
    {
        return $this->customerRepository
            ->where('customers.created_at', '>=', $start)
            ->where('customers.created_at', '<=', $end)
            ->count();
    }

    /**
     * Returns total pending invoices between two dates.
     *
     * @param  \Illuminate\Support\Carbon  $start
     * @param  \Illuminate\Support\Carbon  $end
     * @return string
     */
    private function getTotalPendingInvoices()
    {
        return $this->invoiceRepository
            ->where('state', 'pending')
            ->sum('grand_total');
    }
}
