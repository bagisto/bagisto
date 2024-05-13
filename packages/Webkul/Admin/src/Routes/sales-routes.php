<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Sales\CartController;
use Webkul\Admin\Http\Controllers\Sales\InvoiceController;
use Webkul\Admin\Http\Controllers\Sales\OrderController;
use Webkul\Admin\Http\Controllers\Sales\RefundController;
use Webkul\Admin\Http\Controllers\Sales\ShipmentController;
use Webkul\Admin\Http\Controllers\Sales\TransactionController;

/**
 * Sales routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
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
    });
});
