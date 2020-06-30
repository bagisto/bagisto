<?php


namespace Webkul\Shop\Generators;


use Webkul\Sales\Models\Order;

class OrderNumberIdSequencer implements Sequencer
{
    /**
     * @inheritDoc
     */
    public static function generate(): string
    {
        foreach ([
                     'Prefix' => 'prefix',
                     'Length' => 'length',
                     'Suffix' => 'suffix',
                 ] as
                 $varSuffix => $confKey) {
            $var = "invoiceNumber{$varSuffix}";
            $$var = core()->getConfigData('sales.orderSettings.order_number.order_number_' . $confKey) ?: false;
        }

        $lastOrder = Order::query()->orderBy('id', 'desc')->limit(1)->first();
        $lastId = $lastOrder ? $lastOrder->id : 0;

        if ($invoiceNumberLength && ($invoiceNumberPrefix || $invoiceNumberSuffix)) {
            $invoiceNumber = ($invoiceNumberPrefix) . sprintf("%0{$invoiceNumberLength}d",
                    0) . ($lastId + 1) . ($invoiceNumberSuffix);
        } else {
            $invoiceNumber = $lastId + 1;
        }

        return $invoiceNumber;
    }
}