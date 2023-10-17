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
     * @return array
     */
    public function getTotalSalesStats(): array
    {
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
     * @return array
     */
    public function getAverageSalesStats(): array
    {
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
     * @return array
     */
    public function getTotalOrdersStats(): array
    {
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
     * @return array
     */
    public function getAbandonedCartsStats(): array
    {
        $totalAbandonedProducts = $this->cartReporting->getTotalAbandonedCartProducts();

        $products = $this->cartReporting->getAbandonedCartProducts();

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
     * @return array
     */
    public function getRefundsStats(): array
    {
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
     * @return array
     */
    public function getTaxCollectedStats(): array
    {
        $taxCollected = $this->saleReporting->getTaxCollectedProgress();

        $taxCategories = $this->saleReporting->getTopTaxCategories();

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
     * @return array
     */
    public function getShippingCollectedStats(): array
    {
        $shippingCollected = $this->saleReporting->getShippingCollectedProgress();

        $shippingMethods = $this->saleReporting->getTopShippingMethods();

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopPaymentMethods(): Collection
    {
        $totalOrders = $this->saleReporting->getTotalOrdersProgress();

        $paymentMethods = $this->saleReporting->getTopPaymentMethods();

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
     * @return array
     */
    public function getTotalCustomersStats(): array
    {
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersWithMostSales(): Collection
    {
        $totalSales = $this->saleReporting->getTotalSalesProgress();

        $customers = $this->customerReporting->getCustomersWithMostSales();

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersWithMostOrders(): Collection
    {
        $totalOrders = $this->saleReporting->getTotalOrdersProgress();

        $customers = $this->customerReporting->getCustomersWithMostSales();

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersWithMostReviews(): Collection
    {
        $totalReviews = $this->customerReporting->getTotalReviewsProgress();

        $customers = $this->customerReporting->getCustomersWithMostReviews();

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopCustomerGroups(): Collection
    {
        $totalCustomers = $this->customerReporting->getTotalCustomersProgress();

        $groups = $this->customerReporting->getGroupsWithMostCustomers();

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
     * @return array
     */
    public function getTotalSoldQuantitiesStats(): array
    {
        return [
            'sales'     => $this->productReporting->getTotalSoldQuantitiesProgress(),

            'over_time' => [
                'previous' => $this->productReporting->getPreviousTotalSoldQuantitiesOverTime(),
                'current'  => $this->productReporting->getCurrentTotalSoldQuantitiesOverTime(),
            ],
        ];
    }

    /**
     * Returns top selling products by revenue statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopProductsByRevenue(): Collection
    {
        return $this->productReporting->getTopProductsByRevenue();
    }

    /**
     * Returns top selling products by quantity statistics.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopProductsByQuantity(): Collection
    {
        return $this->productReporting->getTopProductsByQuantity();
    }

    /**
     * Returns the total products added to wishlist statistics.
     * 
     * @return array
     */
    public function getTotalProductsAddedToWishlistStats(): array
    {
        return [
            'sales'     => $this->productReporting->getTotalProductsAddedToWishlistProgress(),

            'over_time' => [
                'previous' => $this->productReporting->getPreviousTotalProductsAddedToWishlistOverTime(),
                'current'  => $this->productReporting->getCurrentTotalProductsAddedToWishlistOverTime(),
            ],
        ];
    }

    /**
     * Returns the products with most reviews
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductsWithMostReviews(): Collection
    {
        $totalReviews = $this->productReporting->getTotalReviewsProgress();

        $products = $this->productReporting->getProductsWithMostReviews();

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductsWithMostVisits(): Collection
    {
        $totalVisits = $this->visitorReporting->getTotalVisitorsProgress();

        $products = $this->visitorReporting->getVisitableWithMostVisits(ProductModel::class);

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