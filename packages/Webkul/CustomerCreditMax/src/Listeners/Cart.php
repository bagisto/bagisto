<?php

namespace Webkul\CustomerCreditMax\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Sales\Repositories\OrderRepository;
use Cart as CartFacade;

/**
 * Cart event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart
{
    /**
     * OrderRepository object
     *
     * @var Product
    */
    protected $orderRepository;

    /**
     * Create a new cart event listener instance.
     *
     * @param  Webkul\Sales\Repositories\OrderRepository $orderRepository
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Return current logged in customer
     *
     * @return Customer | Boolean
     */
    public function getCurrentCustomerGuard()
    {
        $guard = request()->has('token') ? 'api' : 'customer';

        return auth()->guard($guard);
    }

    /**
     * Checks if customer credit amount exceeded or not
     *
     * @param mixed $cartItem
     */
    public function cartItemAddBefore($productId)
    {
        if (! core()->getConfigData('customer.settings.credit_max.status') || ! $this->getCurrentCustomerGuard()->check())
            return;

        $baseGrandTotal = $this->orderRepository->scopeQuery(function ($query) {
            return $query
                ->where('orders.customer_id', $this->getCurrentCustomerGuard()->user()->id)
                ->where('orders.status', '<>', 'canceled');
        })->sum('base_grand_total');

        $baseGrandTotalInvoiced = $this->orderRepository->scopeQuery(function ($query) {
            return $query
                ->where('orders.customer_id', $this->getCurrentCustomerGuard()->user()->id)
                ->where('orders.status', '<>', 'canceled');
        })->sum('base_grand_total_invoiced');

        if ( ($baseGrandTotal - $baseGrandTotalInvoiced) >= core()->getConfigData('customer.settings.credit_max.amount'))
            throw new \Exception('You available credit limit has been exceeded. Please pay your pending invoice.');
    }
}