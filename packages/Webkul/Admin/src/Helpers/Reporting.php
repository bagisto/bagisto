<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Webkul\Admin\Helpers\Reporting\Cart;
use Webkul\Admin\Helpers\Reporting\Sale;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Customer;
use Webkul\Admin\Helpers\Reporting\Visitor;
use Webkul\Product\Models\Product as ProductModel;

class Reporting
{
    /**
     * Create a controller instance.
     * 
     * @param  \Webkul\Admin\Helpers\Reporting\Cart  $cartReporting
     * @param  \Webkul\Admin\Helpers\Reporting\Sale  $saleReporting
     * @param  \Webkul\Admin\Helpers\Reporting\Product  $productReporting
     * @param  \Webkul\Admin\Helpers\Reporting\Customer  $customerReporting
     * @param  \Webkul\Admin\Helpers\Reporting\Visitor  $visitorReporting
     * @return void
     */
    public function __construct(
        protected Cart $cartReporting,
        protected Sale $saleReporting,
        protected Product $productReporting,
        protected Customer $customerReporting,
        protected Visitor $visitorReporting
    )
    {
    }

    /**
     * Returns the sales statistics.
     * 
     * @param  string  $type
     * @return array
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
                        'label' => 'Interval'
                    ], [
                        'key'   => 'count',
                        'label' => 'Orders'
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total'
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'sales'     => $this->saleReporting->getTotalSalesProgress(),

            'over_time' => [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => 'Interval'
                    ], [
                        'key'   => 'count',
                        'label' => 'Orders'
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total'
                    ],
                ],

                'previous' => $this->saleReporting->getPreviousTotalSalesOverTime(request()->query('period') ?? 'day'),
                'current'  => $this->saleReporting->getCurrentTotalSalesOverTime(request()->query('period') ?? 'day'),
            ],
        ];
    }

    /**
     * Returns the sales statistics.
     * 
     * @param  string  $type
     * @return array
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
                        'label' => 'Interval'
                    ], [
                        'key'   => 'count',
                        'label' => 'Orders'
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total'
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
     * @return array
     */
    public function getTotalOrdersStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => 'Interval'
                    ], [
                        'key'   => 'count',
                        'label' => 'Orders'
                    ]
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
     * 
     * @return array
     */
    public function getPurchaseFunnelStats(): array
    {
        $startDate = $this->visitorReporting->getStartDate();

        $endDate = $this->visitorReporting->getEndDate();

        return [
            'visitors'         => [
                'total'    => $totalVisitors = $this->visitorReporting->getTotalUniqueVisitors($startDate, $endDate),
                'progress' => 100,
            ],

            'product_visitors' => [
                'total'    => $totalProductVisitors = $this->visitorReporting->getTotalUniqueVisitors($startDate, $endDate, ProductModel::class),
                'progress' => ($totalProductVisitors * 100) / $totalVisitors,
            ],

            'carts' => [
                'total'    => $totalCarts = $this->cartReporting->getTotalUniqueCartsUsers($startDate, $endDate),
                'progress' => ($totalCarts * 100) / $totalVisitors,
            ],

            'orders' => [
                'total'    => $totalOrders = $this->saleReporting->getTotalUniqueOrdersUsers($startDate, $endDate),
                'progress' => ($totalOrders * 100) / $totalVisitors,
            ],
        ];
    }

    /**
     * Returns the abandoned carts statistics.
     * 
     * @param  string  $type
     * @return array
     */
    public function getAbandonedCartsStats($type = 'graph'): array
    {
        if ($type == 'table') {
            $records = $this->cartReporting->getAbandonedCartProducts();

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => 'Id'
                    ], [
                        'key'   => 'name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'count',
                        'label' => 'Count'
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalAbandonedProducts = $this->cartReporting->getTotalAbandonedCartProducts();

        $products = $this->cartReporting->getAbandonedCartProducts(5);

        $products->map(function($product) use ($totalAbandonedProducts) {
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
     * @return array
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
                        'label' => 'Interval'
                    ], [
                        'key'   => 'count',
                        'label' => 'Orders'
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total'
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
     * @return array
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
                        'label' => 'Interval'
                    ], [
                        'key'   => 'count',
                        'label' => 'Orders'
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total'
                    ],
                ],

                'records'  => $records,
            ];
        }

        $taxCollected = $this->saleReporting->getTaxCollectedProgress();

        $taxCategories = $this->saleReporting->getTopTaxCategories(5);

        $taxCategories->map(function($taxCategory) use ($taxCollected) {
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
     * @return array
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
                        'label' => 'Interval'
                    ], [
                        'key'   => 'count',
                        'label' => 'Orders'
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total'
                    ],
                ],

                'records'  => $records,
            ];
        }

        $shippingCollected = $this->saleReporting->getShippingCollectedProgress();

        $shippingMethods = $this->saleReporting->getTopShippingMethods(5);

        $shippingMethods->map(function($shippingMethod) use($shippingCollected) {
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
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getTopPaymentMethods($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getTopPaymentMethods());

            $records = $records->map(function($paymentMethod) {
                $paymentMethod->formatted_total = core()->formatBasePrice($paymentMethod->total);

                $paymentMethod->title = $paymentMethod->title ?? core()->getConfigData('sales.payment_methods.' . $paymentMethod->method . '.title');

                return $paymentMethod;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'title',
                        'label' => 'Payment Method',
                    ], [
                        'key'   => 'total',
                        'label' => 'Orders',
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total',
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalOrders = $this->saleReporting->getTotalOrdersProgress();

        $paymentMethods = $this->saleReporting->getTopPaymentMethods(5);

        $paymentMethods->map(function($paymentMethod) use($totalOrders) {
            if (! $totalOrders['current']) {
                $paymentMethod->progress = 0;
            } else {
                $paymentMethod->progress = ($paymentMethod->total * 100) / $totalOrders['current'];
            }

            $paymentMethod->formatted_total = core()->formatBasePrice($paymentMethod->total);

            $paymentMethod->title = $paymentMethod->title ?? core()->getConfigData('sales.payment_methods.' . $paymentMethod->method . '.title');
        });

        return $paymentMethods;
    }

    /**
     * Returns the total customers statistics.
     * 
     * @param  string  $type
     * @return array
     */
    public function getTotalCustomersStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => 'Interval'
                    ], [
                        'key'   => 'total',
                        'label' => 'Customers'
                    ]
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
     * 
     * @return array
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
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getCustomersWithMostSales($type = 'graph'): Collection|array
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
                        'label' => 'Name'
                    ], [
                        'key'   => 'email',
                        'label' => 'Email'
                    ], [
                        'key'   => 'formatted_total',
                        'label' => 'Total'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalSales = $this->saleReporting->getTotalSalesProgress();

        $customers = $this->customerReporting->getCustomersWithMostSales(5);

        $customers->map(function($customer) use($totalSales) {
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
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getCustomersWithMostOrders($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getCustomersWithMostOrders();

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'email',
                        'label' => 'Email'
                    ], [
                        'key'   => 'orders',
                        'label' => 'Orders'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalOrders = $this->saleReporting->getTotalOrdersProgress();

        $customers = $this->customerReporting->getCustomersWithMostOrders(5);

        $customers->map(function($customer) use($totalOrders) {
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
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getCustomersWithMostReviews($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getCustomersWithMostReviews();

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'email',
                        'label' => 'Email'
                    ], [
                        'key'   => 'reviews',
                        'label' => 'Reviews'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalReviews = $this->customerReporting->getTotalReviewsProgress();

        $customers = $this->customerReporting->getCustomersWithMostReviews(5);

        $customers->map(function($customer) use($totalReviews) {
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
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getTopCustomerGroups($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getGroupsWithMostCustomers();

            return [
                'columns' => [
                    [
                        'key'   => 'group_name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'total',
                        'label' => 'Customers'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalCustomers = $this->customerReporting->getTotalCustomersProgress();

        $groups = $this->customerReporting->getGroupsWithMostCustomers(5);

        $groups->map(function($group) use($totalCustomers) {
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
     * @return array
     */
    public function getTotalSoldQuantitiesStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => 'Interval'
                    ], [
                        'key'   => 'total',
                        'label' => 'Quantities'
                    ]
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
     * @return array
     */
    public function getTotalProductsAddedToWishlistStats($type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => 'Interval'
                    ], [
                        'key'   => 'total',
                        'label' => 'Total'
                    ]
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
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getTopSellingProductsByRevenue($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = collect($this->productReporting->getTopSellingProductsByRevenue());

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => 'Id'
                    ], [
                        'key'   => 'name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'formatted_price',
                        'label' => 'Price'
                    ], [
                        'key'   => 'formatted_revenue',
                        'label' => 'Revenue'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalSales = $this->saleReporting->getTotalSalesProgress();

        $products = $this->productReporting->getTopSellingProductsByRevenue(5);

        $products->map(function($product) use($totalSales) {
            if (! $totalSales['current']) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->revenue * 100) / $totalSales['current'];
            }

            $product->formatted_revenue = core()->formatBasePrice($product->revenue);
        });

        return $products;
    }

    /**
     * Returns top selling products by quantity statistics.
     * 
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getTopSellingProductsByQuantity($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = $this->productReporting->getTopSellingProductsByQuantity();

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => 'Id'
                    ], [
                        'key'   => 'name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'total_qty_ordered',
                        'label' => 'Quantities'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalSoldQuantities = $this->productReporting->getTotalSoldQuantitiesProgress();

        $products = $this->productReporting->getTopSellingProductsByQuantity(5);

        $products->map(function($product) use($totalSoldQuantities) {
            if (! $totalSoldQuantities['current']) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->total_qty_ordered * 100) / $totalSoldQuantities['current'];
            }
        });

        return $products;
    }

    /**
     * Returns the products with most reviews
     * 
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getProductsWithMostReviews($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = $this->productReporting->getProductsWithMostReviews();

            return [
                'columns' => [
                    [
                        'key'   => 'product_id',
                        'label' => 'Id'
                    ], [
                        'key'   => 'product_name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'reviews',
                        'label' => 'Reviews'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalReviews = $this->productReporting->getTotalReviewsProgress();

        $products = $this->productReporting->getProductsWithMostReviews(5);

        $products->map(function($product) use($totalReviews) {
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
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getProductsWithMostVisits($type = 'graph'): Collection|array
    {
        if ($type == 'table') {
            $records = $this->visitorReporting->getVisitableWithMostVisits(ProductModel::class);

            return [
                'columns' => [
                    [
                        'key'   => 'visitable_id',
                        'label' => 'Id'
                    ], [
                        'key'   => 'name',
                        'label' => 'Name'
                    ], [
                        'key'   => 'visits',
                        'label' => 'Visits'
                    ]
                ],

                'records'  => $records,
            ];
        }

        $totalVisits = $this->visitorReporting->getTotalVisitorsProgress(ProductModel::class);

        $products = $this->visitorReporting->getVisitableWithMostVisits(ProductModel::class, 5);

        $products->map(function($product) use($totalVisits) {
            if (! $totalVisits['current']) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->visits * 100) / $totalVisits['current'];
            }
        });

        return $products;
    }

    /**
     * Returns date range
     * 
     * @return array
     */
    public function getDateRange(): array
    {
        return [
            'previous' => $this->saleReporting->getLastStartDate()->format('d M Y') . ' - ' . $this->saleReporting->getLastEndDate()->format('d M Y'),
            'current'  => $this->saleReporting->getStartDate()->format('d M Y') . ' - ' . $this->saleReporting->getEndDate()->format('d M Y'),
        ];
    }
}