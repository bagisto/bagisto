<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\Invoice;

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
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItemRepository;

    /**
     * InvoiceItemRepository object
     *
     * @var Object
     */
    protected $invoiceItemRepository;

    /**
     * DownloadableLinkPurchasedRepository object
     *
     * @var Object
     */
    protected $downloadableLinkPurchasedRepository;

    /**
     * Create a new repository instance.
     *
     * @param \Webkul\Sales\Repositories\OrderRepository                     $orderRepository
     * @param \Webkul\Sales\Repositories\OrderItemRepository                 $orderItemRepository
     * @param \Webkul\Sales\Repositories\InvoiceItemRepository               $invoiceItemRepository
     * @param \Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository $downloadableLinkPurchasedRepository
     * @param \Illuminate\Container\Container                                $app
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        InvoiceItemRepository $invoiceItemRepository,
        DownloadableLinkPurchasedRepository $downloadableLinkPurchasedRepository,
        App $app
    )
    {
        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->invoiceItemRepository = $invoiceItemRepository;

        $this->invoiceItemRepository = $invoiceItemRepository;

        $this->downloadableLinkPurchasedRepository = $downloadableLinkPurchasedRepository;

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
            Event::dispatch('sales.invoice.save.before', $data);

            $order = $this->orderRepository->find($data['order_id']);

            $totalQty = array_sum($data['invoice']['items']);

            $invoice = $this->model->create([
                    'order_id'              => $order->id,
                    'total_qty'             => $totalQty,
                    'state'                 => 'paid',
                    'base_currency_code'    => $order->base_currency_code,
                    'channel_currency_code' => $order->channel_currency_code,
                    'order_currency_code'   => $order->order_currency_code,
                    'order_address_id'      => $order->billing_address->id
                ]);

            foreach ($data['invoice']['items'] as $itemId => $qty) {
                if (! $qty) {
                    continue; 
                }

                $orderItem = $this->orderItemRepository->find($itemId);

                if ($qty > $orderItem->qty_to_invoice) {
                    $qty = $orderItem->qty_to_invoice;
                }

                $invoiceItem = $this->invoiceItemRepository->create([
                        'invoice_id'           => $invoice->id,
                        'order_item_id'        => $orderItem->id,
                        'name'                 => $orderItem->name,
                        'sku'                  => $orderItem->sku,
                        'qty'                  => $qty,
                        'price'                => $orderItem->price,
                        'base_price'           => $orderItem->base_price,
                        'total'                => $orderItem->price * $qty,
                        'base_total'           => $orderItem->base_price * $qty,
                        'tax_amount'           => ( ($orderItem->tax_amount / $orderItem->qty_ordered) * $qty ),
                        'base_tax_amount'      => ( ($orderItem->base_tax_amount / $orderItem->qty_ordered) * $qty ),
                        'discount_amount'      => ( ($orderItem->discount_amount / $orderItem->qty_ordered) * $qty ),
                        'base_discount_amount' => ( ($orderItem->base_discount_amount / $orderItem->qty_ordered) * $qty ),
                        'product_id'           => $orderItem->product_id,
                        'product_type'         => $orderItem->product_type,
                        'additional'           => $orderItem->additional
                    ]);

                if ($orderItem->getTypeInstance()->isComposite()) {
                    foreach ($orderItem->children as $childOrderItem) {
                        $finalQty = $childOrderItem->qty_ordered
                                ? ($childOrderItem->qty_ordered / $orderItem->qty_ordered) * $qty
                                : $orderItem->qty_ordered;

                        $this->invoiceItemRepository->create([
                                'invoice_id'           => $invoice->id,
                                'order_item_id'        => $childOrderItem->id,
                                'parent_id'            => $invoiceItem->id,
                                'name'                 => $childOrderItem->name,
                                'sku'                  => $childOrderItem->sku,
                                'qty'                  => $finalQty,
                                'price'                => $childOrderItem->price,
                                'base_price'           => $childOrderItem->base_price,
                                'total'                => $childOrderItem->price * $finalQty,
                                'base_total'           => $childOrderItem->base_price * $finalQty,
                                'tax_amount'           => 0,
                                'base_tax_amount'      => 0,
                                'discount_amount'      => 0,
                                'base_discount_amount' => 0,
                                'product_id'           => $childOrderItem->product_id,
                                'product_type'         => $childOrderItem->product_type,
                                'additional'           => $childOrderItem->additional
                            ]);

                        if ($childOrderItem->product
                            && ! $childOrderItem->getTypeInstance()->isStockable()
                            && $childOrderItem->getTypeInstance()->showQuantityBox()) {

                            $this->invoiceItemRepository->updateProductInventory([
                                    'invoice'   => $invoice,
                                    'product'   => $childOrderItem->product,
                                    'qty'       => $finalQty,
                                    'vendor_id' => isset($data['vendor_id']) ? $data['vendor_id'] : 0
                                ]);
                        }

                        $this->orderItemRepository->collectTotals($childOrderItem);
                    }
                } elseif ($orderItem->product
                    && ! $orderItem->getTypeInstance()->isStockable()
                    && $orderItem->getTypeInstance()->showQuantityBox()) {
                        
                    $this->invoiceItemRepository->updateProductInventory([
                            'invoice'   => $invoice,
                            'product'   => $orderItem->product,
                            'qty'       => $qty,
                            'vendor_id' => isset($data['vendor_id']) ? $data['vendor_id'] : 0
                        ]);
                }

                $this->orderItemRepository->collectTotals($orderItem);

                $this->downloadableLinkPurchasedRepository->updateStatus($orderItem, 'available');
            }

            $this->collectTotals($invoice);

            $this->orderRepository->collectTotals($order);

            $this->orderRepository->updateOrderStatus($order);

            Event::dispatch('sales.invoice.save.after', $invoice);
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
        $invoice->sub_total = $invoice->base_sub_total = 0;
        $invoice->tax_amount = $invoice->base_tax_amount = 0;
        $invoice->discount_amount = $invoice->base_discount_amount = 0;

        foreach ($invoice->items as $invoiceItem) {
            $invoice->sub_total += $invoiceItem->total;
            $invoice->base_sub_total += $invoiceItem->base_total;

            $invoice->tax_amount += $invoiceItem->tax_amount;
            $invoice->base_tax_amount += $invoiceItem->base_tax_amount;

            $invoice->discount_amount += $invoiceItem->discount_amount;
            $invoice->base_discount_amount += $invoiceItem->base_discount_amount;
        }

        $invoice->shipping_amount = $invoice->order->shipping_amount;
        $invoice->base_shipping_amount = $invoice->order->base_shipping_amount;

        $invoice->discount_amount += $invoice->order->shipping_discount_amount;
        $invoice->base_discount_amount += $invoice->order->base_shipping_discount_amount;

        if ($invoice->order->shipping_amount) {
            foreach ($invoice->order->invoices as $prevInvoice) {
                if ((float) $prevInvoice->shipping_amount) {
                    $invoice->shipping_amount = $invoice->base_shipping_amount = 0;
                }

                if ($prevInvoice->id != $invoice->id) {
                    $invoice->discount_amount -= $invoice->order->shipping_discount_amount;
                    $invoice->base_discount_amount -= $invoice->order->base_shipping_discount_amount;
                }
            }
        }

        $invoice->grand_total = $invoice->sub_total + $invoice->tax_amount + $invoice->shipping_amount - $invoice->discount_amount;
        $invoice->base_grand_total = $invoice->base_sub_total + $invoice->base_tax_amount + $invoice->base_shipping_amount - $invoice->base_discount_amount;

        $invoice->save();

        return $invoice;
    }
}