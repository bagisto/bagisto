<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Sales\InvoiceController;
use Webkul\Admin\Http\Controllers\Sales\OrderController;
use Webkul\Admin\Http\Controllers\Sales\RefundController;
use Webkul\Admin\Http\Controllers\Sales\ShipmentController;
use Webkul\Admin\Http\Controllers\Sales\TransactionController;

/**
 * Sales routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('sales')->group(function () {
        /**
         * Orders routes.
         */
        Route::get('orders', [OrderController::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.orders.index',
        ])->name('admin.sales.orders.index');

        Route::get('orders/view/{id}', [OrderController::class, 'view'])->defaults('_config', [
            'view' => 'admin::sales.orders.view',
        ])->name('admin.sales.orders.view');

        Route::get('orders/cancel/{id}', [OrderController::class, 'cancel'])->defaults('_config', [
            'view' => 'admin::sales.orders.cancel',
        ])->name('admin.sales.orders.cancel');

        Route::post('orders/create/{order_id}', [OrderController::class, 'comment'])->name('admin.sales.orders.comment');

        /**
         * Invoices routes.
         */
        Route::get('invoices', [InvoiceController::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.invoices.index',
        ])->name('admin.sales.invoices.index');

        Route::get('invoices/create/{order_id}', [InvoiceController::class, 'create'])->defaults('_config', [
            'view' => 'admin::sales.invoices.create',
        ])->name('admin.sales.invoices.create');

        Route::post('invoices/create/{order_id}', [InvoiceController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.sales.orders.view',
        ])->name('admin.sales.invoices.store');

        Route::get('invoices/view/{id}', [InvoiceController::class, 'view'])->defaults('_config', [
            'view' => 'admin::sales.invoices.view',
        ])->name('admin.sales.invoices.view');

        Route::post('invoices/send-duplicate/{id}', [InvoiceController::class, 'sendDuplicateInvoice'])
            ->name('admin.sales.invoices.send_duplicate');

        Route::get('invoices/print/{id}', [InvoiceController::class, 'printInvoice'])->defaults('_config', [
            'view' => 'admin::sales.invoices.print',
        ])->name('admin.sales.invoices.print');

        Route::get('invoices/{id}transactions', [InvoiceController::class, 'invoiceTransactions'])
            ->name('admin.sales.invoices.transactions');

        /**
         * Shipments routes.
         */
        Route::get('shipments', [ShipmentController::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.shipments.index',
        ])->name('admin.sales.shipments.index');

        Route::get('shipments/create/{order_id}', [ShipmentController::class, 'create'])->defaults('_config', [
            'view' => 'admin::sales.shipments.create',
        ])->name('admin.sales.shipments.create');

        Route::post('shipments/create/{order_id}', [ShipmentController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.sales.orders.view',
        ])->name('admin.sales.shipments.store');

        Route::get('shipments/view/{id}', [ShipmentController::class, 'view'])->defaults('_config', [
            'view' => 'admin::sales.shipments.view',
        ])->name('admin.sales.shipments.view');

        /**
         * Refunds routes.
         */
        Route::get('refunds', [RefundController::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.refunds.index',
        ])->name('admin.sales.refunds.index');

        Route::get('refunds/create/{order_id}', [RefundController::class, 'create'])->defaults('_config', [
            'view' => 'admin::sales.refunds.create',
        ])->name('admin.sales.refunds.create');

        Route::post('refunds/create/{order_id}', [RefundController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.sales.refunds.index',
        ])->name('admin.sales.refunds.store');

        Route::post('refunds/update-qty/{order_id}', [RefundController::class, 'updateQty'])->defaults('_config', [
            'redirect' => 'admin.sales.orders.view',
        ])->name('admin.sales.refunds.update_qty');

        Route::get('refunds/view/{id}', [RefundController::class, 'view'])->defaults('_config', [
            'view' => 'admin::sales.refunds.view',
        ])->name('admin.sales.refunds.view');

        /**
         * Transactions routes.
         */
        Route::get('transactions', [TransactionController::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.transactions.index',
        ])->name('admin.sales.transactions.index');

        Route::get('transactions/create', [TransactionController::class, 'create'])->defaults('_config', [
            'view' => 'admin::sales.transactions.create',
        ])->name('admin.sales.transactions.create');

        Route::post('transactions/create', [TransactionController::class, 'store'])->name('admin.sales.transactions.store');

        Route::get('transactions/view/{id}', [TransactionController::class, 'view'])->defaults('_config', [
            'view' => 'admin::sales.transactions.view',
        ])->name('admin.sales.transactions.view');
    });
});
