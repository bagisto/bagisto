<?php

namespace Webkul\Sales\Generators;

use Webkul\Sales\Models\Order;

class OrderSequencer extends Sequencer
{
    /**
     * Create order sequencer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setAllConfigs();
    }

    /**
     * Set all configs.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setAllConfigs()
    {
        $this->prefix = core()->getConfigData('sales.orderSettings.order_number.order_number_prefix');

        $this->length = core()->getConfigData('sales.orderSettings.order_number.order_number_length');

        $this->suffix = core()->getConfigData('sales.orderSettings.order_number.order_number_suffix');

        $this->generatorClass = core()->getConfigData('sales.orderSettings.order_number.order_number_generator_class');

        $this->lastId = $this->getLastId();
    }

    /**
     * Get last id.
     *
     * @return int
     */
    public function getLastId()
    {
        $lastOrder = Order::query()->orderBy('id', 'desc')->limit(1)->first();

        return $lastOrder ? $lastOrder->id : 0;
    }
}
