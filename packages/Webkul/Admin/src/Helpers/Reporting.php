<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Webkul\Admin\Helpers\Reporting\Cart;
use Webkul\Admin\Helpers\Reporting\Customer;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Sale;
use Webkul\Admin\Helpers\Reporting\Visitor;
use Webkul\Product\Models\Product as ProductModel;

class Reporting
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Cart $cartReporting,
        protected Sale $saleReporting,
        protected Product $productReporting,
        protected Customer $customerReporting,
        protected Visitor $visitorReporting
    ) {
    }

    /**
     * Returns the sales statistics.
     *
     * @param  string  $type
     */
    public function getTotalSalesStats($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentTotalSalesOverTime(request()->query('period') ?? 'day'));

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('admin::app.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('admin::app.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'sales'     => $this->saleReporting->getTotalSalesProgress(),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousTotalSalesOverTime(),
                'current'  => $this->saleReporting->getCurrentTotalSalesOverTime(),
            ],
        ];
    }

    /**
     * Returns the sales statistics.
     *
     * @param  string  $type
     */
    public function getAverageSalesStats($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentAverageSalesOverTime(request()->query('period') ?? 'day'));

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('admin::app.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('admin::app.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'sales'     => $this->saleReporting->getAverageSalesProgress(),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousAverageSalesOverTime(),
                'current'  => $this->saleReporting->getCurrentAverageSalesOverTime(),
            ],
        ];
    }

    /**
     * Returns the total orders statistics.
     *
     * @param  string  $type
     */
    public function getTotalOrdersStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('admin::app.reporting.sales.index.orders'),
                    ],
                ],

                'records'  => $this->saleReporting->getCurrentTotalOrdersOverTime(request()->query('period') ?? 'day'),
            ];
        }

        return [
            'orders'    => $this->saleReporting->getTotalOrdersProgress(),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousTotalOrdersOverTime(),
                'current'  => $this->saleReporting->getCurrentTotalOrdersOverTime(),
            ],
        ];
    }

    /**
     * Returns the purchase funnel statistics.
     */
    public function getPurchaseFunnelStats(): array
    {
        $startDate = $this->visitorReporting->getStartDate();

        $endDate = $this->visitorReporting->getEndDate();

        return [
            'visitors' => [
                'total'    => $totalVisitors = $this->visitorReporting->getTotalUniqueVisitors($startDate, $endDate),
                'progress' => $totalVisitors ? 100 : 0,
            ],

            'product_visitors' => [
                'total'    => $totalProductVisitors = $this->visitorReporting->getTotalUniqueVisitors($startDate, $endDate, ProductModel::class),
                'progress' => round($totalVisitors > 0 ? ($totalProductVisitors * 100) / $totalVisitors : 0, 1),
            ],

            'carts' => [
                'total'    => $totalCarts = $this->cartReporting->getTotalUniqueCartsUsers($startDate, $endDate),
                'progress' => round(min($totalVisitors > 0 ? ($totalCarts * 100) / $totalVisitors : 0, 100), 1),
            ],

            'orders' => [
                'total'    => $totalOrders = $this->saleReporting->getTotalUniqueOrdersUsers($startDate, $endDate),
                'progress' => round(min($totalVisitors > 0 ? ($totalOrders * 100) / $totalVisitors : 0, 100), 1),
            ],
        ];
    }

    /**
     * Returns the abandoned carts statistics.
     *
     * @param  string  $type
     */
    public function getAbandonedCartsStats($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = $this->cartReporting->getAbandonedCartProducts();

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => trans('admin::app.reporting.sales.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('admin::app.reporting.sales.index.name'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('admin::app.reporting.sales.index.count'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalAbandonedProducts = $this->cartReporting->getTotalAbandonedCartProducts();

        $products = $this->cartReporting->getAbandonedCartProducts(5);

        $products->map(function ($product) use ($totalAbandonedProducts) {
            if (! $totalAbandonedProducts) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->count * 100) / $totalAbandonedProducts;
            }

            return $product;
        });

        return [
            'sales'    => $this->cartReporting->getTotalAbandonedSalesProgress(),
            'carts'    => $this->cartReporting->getTotalAbandonedCartsProgress(),
            'rate'     => $this->cartReporting->getTotalAbandonedCartRateProgress(),
            'products' => $products,
        ];
    }

    /**
     * Returns the sales statistics.
     *
     * @param  string  $type
     */
    public function getRefundsStats($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentRefundsOverTime(request()->query('period') ?? 'day'));

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('admin::app.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('admin::app.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'refunds'   => $this->saleReporting->getRefundsProgress(),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousRefundsOverTime(),
                'current'  => $this->saleReporting->getCurrentRefundsOverTime(),
            ],
        ];
    }

    /**
     * Returns the tax collected statistics.
     *
     * @param  string  $type
     */
    public function getTaxCollectedStats($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentTaxCollectedOverTime(request()->query('period') ?? 'day'));

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('admin::app.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('admin::app.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $taxCollected = $this->saleReporting->getTaxCollectedProgress();

        $taxCategories = $this->saleReporting->getTopTaxCategories(5);

        $taxCategories->map(function ($taxCategory) use ($taxCollected) {
            if (! $taxCollected['current']) {
                $taxCategory->progress = 0;
            } else {
                $taxCategory->progress = ($taxCategory->total * 100) / $taxCollected['current'];
            }

            $taxCategory->formatted_total = core()->formatBasePrice($taxCategory->total);

            return $taxCategory;
        });

        return [
            'tax_collected'  => $taxCollected,
            'top_categories' => $taxCategories,

            'over_time'      => [
                'previous' => $this->saleReporting->getPreviousTaxCollectedOverTime(),
                'current'  => $this->saleReporting->getCurrentTaxCollectedOverTime(),
            ],
        ];
    }

    /**
     * Returns the shipping collected statistics.
     *
     * @param  string  $type
     */
    public function getShippingCollectedStats($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentShippingCollectedOverTime(request()->query('period') ?? 'day'));

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('admin::app.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('admin::app.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $shippingCollected = $this->saleReporting->getShippingCollectedProgress();

        $shippingMethods = $this->saleReporting->getTopShippingMethods(5);

        $shippingMethods->map(function ($shippingMethod) use ($shippingCollected) {
            if (! $shippingCollected['current']) {
                $shippingMethod->progress = 0;
            } else {
                $shippingMethod->progress = ($shippingMethod->total * 100) / $shippingCollected['current'];
            }

            $shippingMethod->formatted_total = core()->formatBasePrice($shippingMethod->total);

            $shippingMethod->title = current(explode(' - ', $shippingMethod->title));

            return $shippingMethod;
        });

        return [
            'shipping_collected' => $shippingCollected,
            'top_methods'        => $shippingMethods,

            'over_time'          => [
                'previous' => $this->saleReporting->getPreviousShippingCollectedOverTime(),
                'current'  => $this->saleReporting->getCurrentShippingCollectedOverTime(),
            ],
        ];
    }

    /**
     * Returns the shipping collected statistics.
     *
     * @param  string  $type
     */
    public function getTopPaymentMethods($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getTopPaymentMethods());

            $records = $records->map(function ($paymentMethod) {
                $paymentMethod->formatted_total = core()->formatBasePrice($paymentMethod->base_total);

                $paymentMethod->title = $paymentMethod->title ?? core()->getConfigData('sales.payment_methods.'.$paymentMethod->method.'.title');

                return $paymentMethod;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'title',
                        'label' => trans('admin::app.reporting.sales.index.payment-method'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('admin::app.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('admin::app.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalOrders = $this->saleReporting->getTotalOrdersProgress();

        $paymentMethods = $this->saleReporting->getTopPaymentMethods(5);

        $paymentMethods->map(function ($paymentMethod) use ($totalOrders) {
            if (! $totalOrders['current']) {
                $paymentMethod->progress = 0;
            } else {
                $paymentMethod->progress = ($paymentMethod->total * 100) / $totalOrders['current'];
            }

            $paymentMethod->formatted_total = core()->formatBasePrice($paymentMethod->base_total);

            $paymentMethod->title = $paymentMethod->title ?? core()->getConfigData('sales.payment_methods.'.$paymentMethod->method.'.title');
        });

        return $paymentMethods;
    }

    /**
     * Returns the total customers statistics.
     *
     * @param  string  $type
     */
    public function getTotalCustomersStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.customers.index.interval'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('admin::app.reporting.customers.index.customers'),
                    ],
                ],

                'records'  => $this->customerReporting->getCurrentTotalCustomersOverTime(request()->query('period') ?? 'day'),
            ];
        }

        return [
            'customers' => $this->customerReporting->getTotalCustomersProgress(),

            'over_time' => [
                'previous' => $this->customerReporting->getPreviousTotalCustomersOverTime(),
                'current'  => $this->customerReporting->getCurrentTotalCustomersOverTime(),
            ],
        ];
    }

    /**
     * Returns the total customers statistics.
     */
    public function getCustomersTrafficStats(): array
    {
        return [
            'total'     => $this->visitorReporting->getTotalVisitorsProgress(),
            'unique'    => $this->visitorReporting->getTotalUniqueVisitorsProgress(),

            'over_time' => [
                'previous' => $this->visitorReporting->getPreviousTotalVisitorsOverWeek(),
                'current'  => $this->visitorReporting->getCurrentTotalVisitorsOverWeek(),
            ],
        ];
    }

    /**
     * Returns the customers with most sales
     *
     * @param  string  $type
     */
    public function getCustomersWithMostSales($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = collect($this->customerReporting->getCustomersWithMostSales());

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => trans('admin::app.reporting.customers.index.name'),
                    ], [
                        'key'   => 'email',
                        'label' => trans('admin::app.reporting.customers.index.email'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('admin::app.reporting.customers.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalSales = $this->saleReporting->getTotalSalesProgress();

        $customers = $this->customerReporting->getCustomersWithMostSales(5);

        $customers->map(function ($customer) use ($totalSales) {
            if (! $totalSales['current']) {
                $customer->progress = 0;
            } else {
                $customer->progress = ($customer->total * 100) / $totalSales['current'];
            }

            $customer->formatted_total = core()->formatBasePrice($customer->total);
        });

        return $customers;
    }

    /**
     * Returns the customers with most orders
     *
     * @param  string  $type
     */
    public function getCustomersWithMostOrders($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getCustomersWithMostOrders();

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => trans('admin::app.reporting.customers.index.name'),
                    ], [
                        'key'   => 'email',
                        'label' => trans('admin::app.reporting.customers.index.email'),
                    ], [
                        'key'   => 'orders',
                        'label' => trans('admin::app.reporting.customers.index.orders'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalOrders = $this->saleReporting->getTotalOrdersProgress();

        $customers = $this->customerReporting->getCustomersWithMostOrders(5);

        $customers->map(function ($customer) use ($totalOrders) {
            if (! $totalOrders['current']) {
                $customer->progress = 0;
            } else {
                $customer->progress = ($customer->orders * 100) / $totalOrders['current'];
            }
        });

        return $customers;
    }

    /**
     * Returns the customers with most reviews
     *
     * @param  string  $type
     */
    public function getCustomersWithMostReviews($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getCustomersWithMostReviews();

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => trans('admin::app.reporting.customers.index.name'),
                    ], [
                        'key'   => 'email',
                        'label' => trans('admin::app.reporting.customers.index.email'),
                    ], [
                        'key'   => 'reviews',
                        'label' => trans('admin::app.reporting.customers.index.reviews'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalReviews = $this->customerReporting->getTotalReviewsProgress();

        $customers = $this->customerReporting->getCustomersWithMostReviews(5);

        $customers->map(function ($customer) use ($totalReviews) {
            if (! $totalReviews['current']) {
                $customer->progress = 0;
            } else {
                $customer->progress = ($customer->reviews * 100) / $totalReviews['current'];
            }
        });

        return $customers;
    }

    /**
     * Returns the top customers
     *
     * @param  string  $type
     */
    public function getTopCustomerGroups($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getGroupsWithMostCustomers();

            return [
                'columns' => [
                    [
                        'key'   => 'group_name',
                        'label' => trans('admin::app.reporting.customers.index.name'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('admin::app.reporting.customers.index.customers'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalCustomers = $this->customerReporting->getTotalCustomersProgress();

        $groups = $this->customerReporting->getGroupsWithMostCustomers(5);

        $groups->map(function ($group) use ($totalCustomers) {
            if (! $totalCustomers['current']) {
                $group->progress = 0;
            } else {
                $group->progress = ($group->total * 100) / $totalCustomers['current'];
            }
        });

        return $groups;
    }

    /**
     * Returns the total sold quantities statistics.
     *
     * @param  string  $type
     */
    public function getTotalSoldQuantitiesStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.products.index.interval'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('admin::app.reporting.products.index.quantities'),
                    ],
                ],

                'records'  => $this->productReporting->getCurrentTotalSoldQuantitiesOverTime(request()->query('period') ?? 'day'),
            ];
        }

        return [
            'quantities' => $this->productReporting->getTotalSoldQuantitiesProgress(),

            'over_time'  => [
                'previous' => $this->productReporting->getPreviousTotalSoldQuantitiesOverTime(),
                'current'  => $this->productReporting->getCurrentTotalSoldQuantitiesOverTime(),
            ],
        ];
    }

    /**
     * Returns the total products added to wishlist statistics.
     *
     * @param  string  $type
     */
    public function getTotalProductsAddedToWishlistStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('admin::app.reporting.products.index.interval'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('admin::app.reporting.products.index.total'),
                    ],
                ],

                'records'  => $this->productReporting->getCurrentTotalProductsAddedToWishlistOverTime(request()->query('period') ?? 'day'),
            ];
        }

        return [
            'wishlist'  => $this->productReporting->getTotalProductsAddedToWishlistProgress(),

            'over_time' => [
                'previous' => $this->productReporting->getPreviousTotalProductsAddedToWishlistOverTime(),
                'current'  => $this->productReporting->getCurrentTotalProductsAddedToWishlistOverTime(),
            ],
        ];
    }

    /**
     * Returns top selling products by revenue statistics.
     *
     * @param  string  $type
     */
    public function getTopSellingProductsByRevenue($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->productReporting->getTopSellingProductsByRevenue());

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => trans('admin::app.reporting.products.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('admin::app.reporting.products.index.name'),
                    ], [
                        'key'   => 'formatted_price',
                        'label' => trans('admin::app.reporting.products.index.price'),
                    ], [
                        'key'   => 'formatted_revenue',
                        'label' => trans('admin::app.reporting.products.index.revenue'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalSales = $this->saleReporting->getSubTotalSalesProgress();

        $products = $this->productReporting->getTopSellingProductsByRevenue(5);

        $products = $products->map(function ($product) use ($totalSales) {
            if (! $totalSales['current']) {
                $product['progress'] = 0;
            } else {
                $product['progress'] = ($product['revenue'] * 100) / $totalSales['current'];
            }

            $product['formatted_revenue'] = core()->formatBasePrice($product['revenue']);

            return $product;
        });

        return $products->toArray();
    }

    /**
     * Returns top selling products by quantity statistics.
     *
     * @param  string  $type
     */
    public function getTopSellingProductsByQuantity($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = $this->productReporting->getTopSellingProductsByQuantity();

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => trans('admin::app.reporting.products.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('admin::app.reporting.products.index.name'),
                    ], [
                        'key'   => 'total_qty_ordered',
                        'label' => trans('admin::app.reporting.products.index.quantities'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalSoldQuantities = $this->productReporting->getTotalSoldQuantitiesProgress();

        $products = $this->productReporting->getTopSellingProductsByQuantity(5);

        $products = $products->map(function ($product) use ($totalSoldQuantities) {
            if (! $totalSoldQuantities['current']) {
                $product['progress'] = 0;
            } else {
                $product['progress'] = ($product['total_qty_ordered'] * 100) / $totalSoldQuantities['current'];
            }

            return $product;
        });

        return $products->toArray();
    }

    /**
     * Returns the products with most reviews
     *
     * @param  string  $type
     */
    public function getProductsWithMostReviews($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->productReporting->getProductsWithMostReviews();

            return [
                'columns' => [
                    [
                        'key'   => 'product_id',
                        'label' => trans('admin::app.reporting.products.index.id'),
                    ], [
                        'key'   => 'product_name',
                        'label' => trans('admin::app.reporting.products.index.name'),
                    ], [
                        'key'   => 'reviews',
                        'label' => trans('admin::app.reporting.products.index.reviews'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalReviews = $this->productReporting->getTotalReviewsProgress();

        $products = $this->productReporting->getProductsWithMostReviews(5);

        $products->map(function ($product) use ($totalReviews) {
            if (! $totalReviews['current']) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->reviews * 100) / $totalReviews['current'];
            }
        });

        return $products;
    }

    /**
     * Returns the products with most visits
     *
     * @param  string  $type
     */
    public function getProductsWithMostVisits($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->visitorReporting->getVisitableWithMostVisits(ProductModel::class);

            return [
                'columns' => [
                    [
                        'key'   => 'visitable_id',
                        'label' => trans('admin::app.reporting.products.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('admin::app.reporting.products.index.name'),
                    ], [
                        'key'   => 'visits',
                        'label' => trans('admin::app.reporting.products.index.visits'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalVisits = $this->visitorReporting->getTotalVisitorsProgress(ProductModel::class);

        $products = $this->visitorReporting->getVisitableWithMostVisits(ProductModel::class, 5);

        $products->map(function ($product) use ($totalVisits) {
            if (! $totalVisits['current']) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->visits * 100) / $totalVisits['current'];
            }
        });

        return $products;
    }

    /**
     * Returns the last search terms
     *
     * @param  string  $type
     */
    public function getLastSearchTerms($type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->productReporting->getLastSearchTerms();

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => trans('admin::app.reporting.products.index.id'),
                    ], [
                        'key'   => 'term',
                        'label' => trans('admin::app.reporting.products.index.search-term'),
                    ], [
                        'key'   => 'results',
                        'label' => trans('admin::app.reporting.products.index.results'),
                    ], [
                        'key'   => 'uses',
                        'label' => trans('admin::app.reporting.products.index.uses'),
                    ], [
                        'key'   => 'channel_id',
                        'label' => trans('admin::app.reporting.products.index.channel'),
                    ], [
                        'key'   => 'locale',
                        'label' => trans('admin::app.reporting.products.index.locale'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return $this->productReporting->getLastSearchTerms(5);
    }

    /**
     * Returns the top search terms
     *
     * @param  string  $type
     */
    public function getTopSearchTerms($type = 'graph'): EloquentCollection|array
    {
        return $this->productReporting->getTopSearchTerms(5);
    }

    /**
     * Returns date range
     */
    public function getDateRange(): array
    {
        return [
            'previous' => $this->saleReporting->getLastStartDate()->format('d M Y').' - '.$this->saleReporting->getLastEndDate()->format('d M Y'),
            'current'  => $this->saleReporting->getStartDate()->format('d M Y').' - '.$this->saleReporting->getEndDate()->format('d M Y'),
        ];
    }

    /**
     * Get the start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->saleReporting->getStartDate();
    }

    /**
     * Get the end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->saleReporting->getEndDate();
    }
}
