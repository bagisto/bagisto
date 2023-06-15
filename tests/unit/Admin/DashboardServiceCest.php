<?php

namespace Tests\Unit\Admin;

use UnitTester;
use Webkul\Admin\Services\DashboardService;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Codeception\Stub;

class DashboardServiceCest
{
    private DashboardService $dashboardService;

    public function _before(UnitTester $I)
    {
        $orderRepository = Stub::makeEmpty(OrderRepository::class);
        $orderItemRepository = Stub::makeEmpty(OrderItemRepository::class);
        $invoiceRepository = Stub::makeEmpty(InvoiceRepository::class);
        $customerRepository = Stub::makeEmpty(CustomerRepository::class);
        $productInventoryRepository = Stub::makeEmpty(ProductInventoryRepository::class);
        $productRepository = Stub::makeEmpty(ProductRepository::class);

        $this->dashboardService = new DashboardService(
            $orderRepository,
            $orderItemRepository,
            $invoiceRepository,
            $customerRepository,
            $productInventoryRepository,
            $productRepository
        );
    }
    
    public function testStartDate(UnitTester $I): void
    {
        $this->dashboardService->setStartDate(now()->subDays(30));
        $I->assertEquals(now()->subDays(30)->startOfDay(), $this->dashboardService->getStartDate());
    }

    public function testEndDate(UnitTester $I): void
    {
        $this->dashboardService->setEndDate(now()->subDay());
        $I->assertEquals(now()->subDay()->endOfDay(), $this->dashboardService->getEndDate());
    }
}
