<?php

use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;

class OrderRepositoryCest
{
    private $repository;

    public function _before()
    {
        // @see https://stackoverflow.com/a/8457580
        $reflection = new ReflectionClass(OrderRepository::class);

        $property = $reflection->getProperty('model');
        $property->setAccessible(true);

        $this->repository = $reflection->newInstanceWithoutConstructor();
        $property->setValue($this->repository, new Order());
    }

    public function testGenerateIncrementIdOnEmptyDatabase(UnitTester $I)
    {
        $result = $this->repository->generateIncrementId();

        $I->assertEquals(1, $result);
    }

    public function testGenerateIncrementIdOnFilledDatabase(UnitTester $I)
    {
        $order = new Order(['id' => rand(666, 1337)]);
        $order->save();

        $result = $this->repository->generateIncrementId();

        $I->assertEquals($order->id + 1, $result);
    }
}
