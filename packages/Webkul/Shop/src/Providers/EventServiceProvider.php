<?php

namespace Webkul\Shop\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\EUWithdrawal\Events\WithdrawalReceived;
use Webkul\Shop\Listeners\CatalogCache;
use Webkul\Shop\Listeners\Customer;
use Webkul\Shop\Listeners\EUWithdrawal\SendConfirmation as EUWithdrawalSendConfirmation;
use Webkul\Shop\Listeners\GDPR;
use Webkul\Shop\Listeners\Invoice;
use Webkul\Shop\Listeners\Order;
use Webkul\Shop\Listeners\Refund;
use Webkul\Shop\Listeners\Shipment;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /**
         * EU Withdrawal — durable-medium confirmation email
         * (Directive (EU) 2023/2673, Art. 11a(3)). Sent synchronously by the
         * listener so a queue failure cannot leave a withdrawal unconfirmed.
         */
        WithdrawalReceived::class => [
            [EUWithdrawalSendConfirmation::class, 'handle'],
        ],

        /**
         * Catalog cache invalidation. Any change that can alter a cached
         * storefront API response bumps the catalog version so listings are
         * served fresh: product/category saves, review status changes or
         * deletions (ratings and review counts), and order/cancel/refund
         * (stock-driven saleability). The order and refund events are wired
         * in the "Sales" section below, next to their existing listeners.
         */
        'catalog.product.create.after' => [
            [CatalogCache::class, 'flush'],
        ],

        'catalog.product.update.after' => [
            [CatalogCache::class, 'flush'],
        ],

        'catalog.product.delete.before' => [
            [CatalogCache::class, 'flush'],
        ],

        'catalog.category.create.after' => [
            [CatalogCache::class, 'flush'],
        ],

        'catalog.category.update.after' => [
            [CatalogCache::class, 'flush'],
        ],

        'catalog.category.delete.before' => [
            [CatalogCache::class, 'flush'],
        ],

        'customer.review.update.after' => [
            [CatalogCache::class, 'flush'],
        ],

        'customer.review.delete.before' => [
            [CatalogCache::class, 'flush'],
        ],

        /**
         * Customer related events.
         */
        'customer.registration.after' => [
            [Customer::class, 'afterCreated'],
        ],

        'customer.password.update.after' => [
            [Customer::class, 'afterPasswordUpdated'],
        ],

        'customer.subscription.after' => [
            [Customer::class, 'afterSubscribed'],
        ],

        'customer.note.create.after' => [
            [Customer::class, 'afterNoteCreated'],
        ],

        /**
         * GDPR related events.
         */
        'customer.account.gdpr-request.create.after' => [
            [GDPR::class, 'afterGdprRequestCreated'],
        ],

        'customer.account.gdpr-request.update.after' => [
            [GDPR::class, 'afterGdprRequestUpdated'],
        ],

        /**
         * Sales related events.
         */
        'checkout.order.save.after' => [
            [Order::class, 'afterCreated'],
            [CatalogCache::class, 'flush'],
        ],

        'sales.order.cancel.after' => [
            [Order::class, 'afterCanceled'],
            [CatalogCache::class, 'flush'],
        ],

        'sales.order.comment.create.after' => [
            [Order::class, 'afterCommented'],
        ],

        'sales.invoice.save.after' => [
            [Invoice::class, 'afterCreated'],
        ],

        'sales.invoice.send_duplicate_email' => [
            [Invoice::class, 'afterCreated'],
        ],

        'sales.shipment.save.after' => [
            [Shipment::class, 'afterCreated'],
        ],

        'sales.refund.save.after' => [
            [Refund::class, 'afterCreated'],
            [CatalogCache::class, 'flush'],
        ],
    ];
}
