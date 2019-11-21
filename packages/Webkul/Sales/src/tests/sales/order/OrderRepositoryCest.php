<?php

namespace tests\sales\order;

use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;
use Mockery;

class OrderRepositoryCest
{
    private $repository;

    public function _before()
    {
        $this->repository = Mockery::mock(OrderRepository::class);
        $this->repository
            ->shouldReceive('generateIncrementId')
            ->andSet('order', new Order);
    }

    public function testGenerateIncrementIdOnEmptyDatabase(UnitTester $I)
    {
        $result = $this->repository->generateIncrementId();

        $I->expect(1, $result);
    }

    public function testGenerateIncrementIdOnFilledDatabase(UnitTester $I)
    {
        $order = new Order(['id' => rand(666, 1337)]);
        $order->save();

        $result = $this->repository->generateIncrementId();

        $I->expect($order->id + 1, $result);
    }
}
