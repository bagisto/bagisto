<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Sales\BookingController;
use Webkul\Admin\Http\Controllers\Sales\CartController;
use Webkul\Admin\Http\Controllers\Sales\InvoiceController;
use Webkul\Admin\Http\Controllers\Sales\OrderController;
use Webkul\Admin\Http\Controllers\Sales\RefundController;
use Webkul\Admin\Http\Controllers\Sales\RMA\CustomFieldController;
use Webkul\Admin\Http\Controllers\Sales\RMA\ReasonController;
use Webkul\Admin\Http\Controllers\Sales\RMA\RequestController;
use Webkul\Admin\Http\Controllers\Sales\RMA\RulesController;
use Webkul\Admin\Http\Controllers\Sales\RMA\StatusController;
use Webkul\Admin\Http\Controllers\Sales\ShipmentController;
use Webkul\Admin\Http\Controllers\Sales\TransactionController;

/**
 * Sales routes.
 */
Route::prefix('sales')->group(function () {
    /**
     * Invoices routes.
     */
    Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
        Route::get('', 'index')->name('admin.sales.invoices.index');

        Route::post('create/{order_id}', 'store')->name('admin.sales.invoices.store');

        Route::get('view/{id}', 'view')->name('admin.sales.invoices.view');

        Route::post('send-duplicate-email/{id}', 'sendDuplicateEmail')->name('admin.sales.invoices.send_duplicate_email');

        Route::get('print/{id}', 'printInvoice')->name('admin.sales.invoices.print');

        Route::post('mass-update/state', 'massUpdateState')->name('admin.sales.invoices.mass_update.state');
    });

    /**
     * Orders routes.
     */
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('', 'index')->name('admin.sales.orders.index');

        Route::get('create/{cartId}', 'create')->name('admin.sales.orders.create');

        Route::post('create/{cartId}', 'store')->name('admin.sales.orders.store');

        Route::get('view/{id}', 'view')->name('admin.sales.orders.view');

        Route::post('cancel/{id}', 'cancel')->name('admin.sales.orders.cancel');

        Route::get('reorder/{id}', 'reorder')->name('admin.sales.orders.reorder');

        Route::post('comment/{order_id}', 'comment')->name('admin.sales.orders.comment');

        Route::get('search', 'search')->name('admin.sales.orders.search');
    });

    /**
     * Refunds routes.
     */
    Route::controller(RefundController::class)->prefix('refunds')->group(function () {
        Route::get('', 'index')->name('admin.sales.refunds.index');

        Route::post('create/{order_id}', 'store')->name('admin.sales.refunds.store');

        Route::post('update-totals/{order_id}', 'updateTotals')->name('admin.sales.refunds.update_totals');

        Route::get('view/{id}', 'view')->name('admin.sales.refunds.view');
    });

    /**
     * Shipments routes.
     */
    Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {
        Route::get('', 'index')->name('admin.sales.shipments.index');

        Route::post('create/{order_id}', 'store')->name('admin.sales.shipments.store');

        Route::get('view/{id}', 'view')->name('admin.sales.shipments.view');
    });

    /**
     * Transactions routes.
     */
    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::get('', 'index')->name('admin.sales.transactions.index');

        Route::post('create', 'store')->name('admin.sales.transactions.store');

        Route::get('view/{id}', 'view')->name('admin.sales.transactions.view');
    });

    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('{id}', 'index')->name('admin.sales.cart.index');

        Route::post('create', 'store')->name('admin.sales.cart.store');

        Route::post('{id}/items', 'storeItem')->name('admin.sales.cart.items.store');

        Route::put('{id}/items', 'updateItem')->name('admin.sales.cart.items.update');

        Route::delete('{id}/items', 'destroyItem')->name('admin.sales.cart.items.destroy');

        Route::post('{id}/addresses', 'storeAddress')->name('admin.sales.cart.addresses.store');

        Route::post('{id}/shipping-methods', 'storeShippingMethod')->name('admin.sales.cart.shipping_methods.store');

        Route::post('{id}/payment-methods', 'storePaymentMethod')->name('admin.sales.cart.payment_methods.store');

        Route::post('{id}/coupon', 'storeCoupon')->name('admin.sales.cart.store_coupon');

        Route::delete('{id}/coupon', 'destroyCoupon')->name('admin.sales.cart.remove_coupon');
    });

    Route::controller(BookingController::class)->prefix('bookings')->group(function () {
        Route::get('', 'index')->name('admin.sales.bookings.index');

        Route::get('get', 'get')->name('admin.sales.bookings.get');
    });

    /**
     * RMA routes.
     */
    Route::prefix('rma')->group(function () {
        /**
         * Request routes.
         */
        Route::controller(RequestController::class)->prefix('requests')->group(function () {
            Route::get('', 'index')->name('admin.sales.rma.requests.index');

            Route::get('view/{id}', 'view')->name('admin.sales.rma.requests.view');

            Route::get('create', 'create')->name('admin.sales.rma.requests.create');

            Route::post('store', 'store')->name('admin.sales.rma.requests.store');

            Route::get('get-order-items/{orderId}', 'getOrderItems')->name('admin.sales.rma.requests.get-order-items');

            Route::get('get-resolution-reasons/{resolutionType}', 'getResolutionReasons')->name('admin.sales.rma.requests.get-resolution-reasons');

            Route::post('update-status/{id}', 'updateStatus')->name('admin.sales.rma.requests.update-status');

            Route::post('reopen/{id}', 'reOpenRequest')->name('admin.sales.rma.requests.re-open');

            Route::get('get-messages', 'getMessages')->name('admin.sales.rma.requests.get-messages');

            Route::post('send-message', 'sendMessage')->name('admin.sales.rma.requests.send-message');
        });

        /**
         * Reason routes.
         */
        Route::controller(ReasonController::class)->prefix('reasons')->group(function () {
            Route::get('', 'index')->name('admin.sales.rma.reasons.index');

            Route::post('store', 'store')->name('admin.sales.rma.reasons.store');

            Route::get('edit/{id}', 'edit')->name('admin.sales.rma.reasons.edit');

            Route::put('update/{id}', 'update')->name('admin.sales.rma.reasons.update');

            Route::delete('delete/{id}', 'destroy')->name('admin.sales.rma.reasons.delete');

            Route::post('mass-update', 'massUpdate')->name('admin.sales.rma.reasons.mass-update');

            Route::post('mass-delete', 'massDestroy')->name('admin.sales.rma.reasons.mass-delete');
        });

        /**
         * Status routes.
         */
        Route::controller(StatusController::class)->prefix('rma-status')->group(function () {
            Route::get('', 'index')->name('admin.sales.rma.statuses.index');

            Route::post('store', 'store')->name('admin.sales.rma.statuses.store');

            Route::get('edit/{id}', 'edit')->name('admin.sales.rma.statuses.edit');

            Route::put('update/{id}', 'update')->name('admin.sales.rma.statuses.update');

            Route::delete('delete/{id}', 'destroy')->name('admin.sales.rma.statuses.delete');

            Route::post('mass-update', 'massUpdate')->name('admin.sales.rma.statuses.mass-update');

            Route::post('mass-delete', 'massDestroy')->name('admin.sales.rma.statuses.mass-delete');
        });

        /**
         * Rules routes.
         */
        Route::controller(RulesController::class)->prefix('rules')->group(function () {
            Route::get('', 'index')->name('admin.sales.rma.rules.index');

            Route::post('store', 'store')->name('admin.sales.rma.rules.store');

            Route::get('edit/{id}', 'edit')->name('admin.sales.rma.rules.edit');

            Route::put('update/{id}', 'update')->name('admin.sales.rma.rules.update');

            Route::delete('delete/{id}', 'destroy')->name('admin.sales.rma.rules.delete');

            Route::post('mass-update', 'massUpdate')->name('admin.sales.rma.rules.mass-update');

            Route::post('mass-delete', 'massDestroy')->name('admin.sales.rma.rules.mass-delete');
        });

        /**
         * Custom field routes.
         */
        Route::controller(CustomFieldController::class)->prefix('custom-fields')->group(function () {
            Route::get('', 'index')->name('admin.sales.rma.custom-fields.index');

            Route::get('create', 'create')->name('admin.sales.rma.custom-fields.create');

            Route::post('store', 'store')->name('admin.sales.rma.custom-fields.store');

            Route::get('edit/{id}', 'edit')->name('admin.sales.rma.custom-fields.edit');

            Route::post('update/{id}', 'update')->name('admin.sales.rma.custom-fields.update');

            Route::delete('delete/{id}', 'destroy')->name('admin.sales.rma.custom-fields.delete');

            Route::post('mass-update', 'massUpdate')->name('admin.sales.rma.custom-fields.mass-update');

            Route::post('mass-delete', 'massDestroy')->name('admin.sales.rma.custom-fields.mass-delete');
        });
    });
});
