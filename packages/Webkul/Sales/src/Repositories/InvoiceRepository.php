<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\Invoice;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Sales\Repositories\OrderItemRepository as OrderItem;
use Webkul\Sales\Repositories\InvoiceItemRepository as InvoiceItem;

/**
 * Invoice Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class InvoiceRepository extends Repository
{
    /**
     * OrderRepository object
     *
     * @var Object
     */
    protected $order;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItem;

    /**
     * InvoiceItemRepository object
     *
     * @var Object
     */
    protected $invoiceItem;

    /**
     * Create a new repository instance.
     *
     * @param \Webkul\Sales\Repositories\OrderRepository $order
     * @param \Webkul\Sales\Repositories\OrderItemRepository $orderItem
     * @param \Webkul\Sales\Repositories\InvoiceItemRepository $invoiceItem
     * @param \Illuminate\Container\Container $app
     */
    public function __construct(
        Order $order,
        OrderItem $orderItem,
        InvoiceItem $invoiceItem,
        App $app
    )
    {
        $this->order = $order;

        $this->orderItem = $orderItem;

        $this->invoiceItem = $invoiceItem;

        parent::__construct($app);
    }
    
    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return Invoice::class;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        DB::beginTransaction();
        
        try {
            Event::fire('sales.invoice.save.before', $data);

            $order = $this->order->find($data['order_id']);

            $totalQty = array_sum($data['invoice']['items']);

            $invoice = $this->model->create([
                    'order_id' => $order->id,
                    'total_qty' => $totalQty,
                    'state' => 'paid',
                    'base_currency_code' => $order->base_currency_code,
                    'channel_currency_code' => $order->channel_currency_code,
                    'order_currency_code' => $order->order_currency_code,
                    'order_address_id' => $order->billing_address->id,
                ]);

            foreach ($data['invoice']['items'] as $itemId => $qty) {
                if (! $qty) continue;

                $orderItem = $this->orderItem->find($itemId);

                if ($qty > $orderItem->qty_to_invoice)
                    $qty = $orderItem->qty_to_invoice;

                $invoiceItem = $this->invoiceItem->create([
                        'invoice_id' => $invoice->id,
                        'order_item_id' => $orderItem->id,
                        'name' => $orderItem->name,
                        'sku' => $orderItem->sku,
                        'qty' => $qty,
                        'price' => $orderItem->price,
                        'base_price' => $orderItem->base_price,
                        'total' => $orderItem->price * $qty,
                        'base_total' => $orderItem->base_price * $qty,
                        'tax_amount' => ( ($orderItem->tax_amount / $orderItem->qty_ordered) * $qty ),
                        'base_tax_amount' => ( ($orderItem->base_tax_amount / $orderItem->qty_ordered) * $qty ),
                        'product_id' => $orderItem->product_id,
                        'product_type' => $orderItem->product_type,
                        'additional' => $orderItem->additional,
                    ]);
                
                if ($orderItem->type == 'configurable' && $orderItem->child) {
                    $childOrderItem = $orderItem->child;

                    $invoiceItem->child = $this->invoiceItem->create([
                            'invoice_id' => $invoice->id,
                            'order_item_id' => $childOrderItem->id,
                            'parent_id' => $invoiceItem->id,
                            'name' => $childOrderItem->name,
                            'sku' => $childOrderItem->sku,
                            'qty' => $qty,
                            'price' => $childOrderItem->price,
                            'base_price' => $childOrderItem->base_price,
                            'total' => $childOrderItem->price * $qty,
                            'base_total' => $childOrderItem->base_price * $qty,
                            'tax_amount' => 0,
                            'base_tax_amount' => 0,
                            'product_id' => $childOrderItem->product_id,
                            'product_type' => $childOrderItem->product_type,
                            'additional' => $childOrderItem->additional,
                        ]);
                }

                $this->orderItem->collectTotals($orderItem);
            }

            $this->collectTotals($invoice);

            $this->order->collectTotals($order);

            $this->order->updateOrderStatus($order);

            Event::fire('sales.invoice.save.after', $invoice);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
        
        DB::commit();

        return $invoice;
    }

    /**
     * @param mixed $invoice
     * @return mixed
     */
    public function collectTotals($invoice)
    {
        $subTotal = $baseSubTotal = 0;
        $taxAmount = $baseTaxAmount = 0;

        foreach ($invoice->items as $invoiceItem) {
            $subTotal += $invoiceItem->total;
            $baseSubTotal += $invoiceItem->base_total;

            $taxAmount += $invoiceItem->tax_amount;
            $baseTaxAmount += $invoiceItem->base_tax_amount;
        }

        $shippingAmount = $invoice->order->shipping_amount;
        $baseShippingAmount = $invoice->order->base_shipping_amount;

        if ($invoice->order->shipping_amount) {
            foreach ($invoice->order->invoices as $prevInvoice) {
                if ((float) $prevInvoice->shipping_amount) {
                    $shippingAmount = 0;
                    $baseShippingAmount = 0;
                }
            }
        }

        $invoice->sub_total = $subTotal;
        $invoice->base_sub_total = $baseSubTotal;

        $invoice->shipping_amount = $shippingAmount;
        $invoice->base_shipping_amount = $baseShippingAmount;

        $invoice->tax_amount = $taxAmount;
        $invoice->base_tax_amount = $baseTaxAmount;

        $invoice->grand_total = $subTotal + $taxAmount + $shippingAmount;
        $invoice->base_grand_total = $baseSubTotal + $baseTaxAmount + $baseShippingAmount;

        $invoice->save();

        return $invoice;
    }
}