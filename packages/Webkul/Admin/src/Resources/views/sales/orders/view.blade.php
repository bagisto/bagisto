@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.orders.view-title', ['order_id' => $order->id]) }}
@stop

@section('content-wrapper')

    <div class="content full-page">

        <div class="page-header">

            <div class="page-title">
                <h1>{{ __('admin::app.sales.orders.view-title', ['order_id' => $order->id]) }}</h1>
            </div>

            <div class="page-action">
                @if($order->canCancel())
                    <a href="{{ route('admin.sales.orders.cancel', $order->id) }}" class="btn btn-lg btn-primary" v-alert:message="'{{ __('admin::app.sales.orders.cancel-confirm-msg') }}'">
                        {{ __('admin::app.sales.orders.cancel-btn-title') }}
                    </a>
                @endif

                @if($order->canInvoice())
                    <a href="{{ route('admin.sales.invoices.create', $order->id) }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.orders.invoice-btn-title') }}
                    </a>
                @endif

                @if($order->canShip())
                    <a href="{{ route('admin.sales.shipments.create', $order->id) }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.orders.shipment-btn-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">

            <tabs>
                <tab name="{{ __('admin::app.sales.orders.info') }}" :selected="true">
                    <div class="sale-container">

                        <accordian :title="'{{ __('admin::app.sales.orders.order-and-account') }}'" :active="true">
                            <div slot="body">

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.orders.order-info') }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.order-date') }}
                                            </span>

                                            <span class="value">
                                                {{ $order->created_at }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.order-status') }}
                                            </span>

                                            <span class="value">
                                                {{ $order->status_label }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.channel') }}
                                            </span>

                                            <span class="value">
                                                {{ $order->channel_name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.orders.account-info') }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.customer-name') }}
                                            </span>

                                            <span class="value">
                                                {{ $order->customer_full_name }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.email') }}
                                            </span>

                                            <span class="value">
                                                {{ $order->customer_email }}
                                            </span>
                                        </div>

                                        @if(!is_null($order->customer))
                                            <div class="row">
                                                <span class="title">
                                                    {{ __('admin::app.customers.customers.customer_group') }}
                                                </span>

                                                <span class="value">
                                                    {{ $order->customer->customerGroup['name'] }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </accordian>

                        <accordian :title="'{{ __('admin::app.sales.orders.address') }}'" :active="true">
                            <div slot="body">

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.orders.billing-address') }}</span>
                                    </div>

                                    <div class="section-content">

                                        @include ('admin::sales.address', ['address' => $order->billing_address])

                                    </div>
                                </div>

                                @if($order->shipping_address)
                                    <div class="sale-section">
                                        <div class="secton-title">
                                            <span>{{ __('admin::app.sales.orders.shipping-address') }}</span>
                                        </div>

                                        <div class="section-content">

                                            @include ('admin::sales.address', ['address' => $order->shipping_address])

                                        </div>
                                    </div>
                                @endif

                            </div>
                        </accordian>

                        <accordian :title="'{{ __('admin::app.sales.orders.payment-and-shipping') }}'" :active="true">
                            <div slot="body">

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.orders.payment-info') }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.payment-method') }}
                                            </span>

                                            <span class="value">
                                                {{ core()->getConfigData('paymentmethods.' . $order->payment->method . '.title') }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.currency') }}
                                            </span>

                                            <span class="value">
                                                {{ $order->order_currency_code }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.orders.shipping-info') }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.shipping-method') }}
                                            </span>

                                            <span class="value">
                                                {{ $order->shipping_title }}
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span class="title">
                                                {{ __('admin::app.sales.orders.shipping-price') }}
                                            </span>

                                            <span class="value">
                                                {{ core()->formatBasePrice($order->base_shipping_amount) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </accordian>

                        <accordian :title="'{{ __('admin::app.sales.orders.products-ordered') }}'" :active="true">
                            <div slot="body">

                                <div class="table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                                                <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                                                <th>{{ __('admin::app.sales.orders.price') }}</th>
                                                <th>{{ __('admin::app.sales.orders.qty') }}</th>
                                                <th>{{ __('admin::app.sales.orders.item-status') }}</th>
                                                <th>{{ __('admin::app.sales.orders.subtotal') }}</th>
                                                <th>{{ __('admin::app.sales.orders.tax-percent') }}</th>
                                                <th>{{ __('admin::app.sales.orders.tax-amount') }}</th>
                                                <th>{{ __('admin::app.sales.orders.grand-total') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->type == 'configurable' ? $item->child->sku : $item->sku }}
                                                    </td>
                                                    <td>
                                                        {{ $item->name }}

                                                        @if ($html = $item->getOptionDetailHtml())
                                                            <p>{{ $html }}</p>
                                                        @endif
                                                    </td>
                                                    <td>{{ core()->formatBasePrice($item->base_price) }}</td>
                                                    <td>{{ $item->qty_ordered }}</td>
                                                    <td>
                                                        <span class="qty-row">
                                                            {{ $item->qty_invoiced ? __('admin::app.sales.orders.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}
                                                        </span>

                                                        <span class="qty-row">
                                                            {{ $item->qty_shipped ? __('admin::app.sales.orders.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}
                                                        </span>

                                                        <span class="qty-row">
                                                            {{ $item->qty_canceled ? __('admin::app.sales.orders.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ core()->formatBasePrice($item->base_total) }}</td>
                                                    <td>{{ $item->tax_percent }}%</td>
                                                    <td>{{ core()->formatBasePrice($item->base_tax_amount) }}</td>
                                                    <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount) }}</td>
                                                </tr>
                                            @endforeach
                                    </table>
                                </div>

                                <table class="sale-summary">
                                    <tr>
                                        <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                                        <td>-</td>
                                        <td>{{ core()->formatBasePrice($order->base_sub_total) }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('admin::app.sales.orders.shipping-handling') }}</td>
                                        <td>-</td>
                                        <td>{{ core()->formatBasePrice($order->base_shipping_amount) }}</td>
                                    </tr>

                                    <tr class="border">
                                        <td>{{ __('admin::app.sales.orders.tax') }}</td>
                                        <td>-</td>
                                        <td>{{ core()->formatBasePrice($order->base_tax_amount) }}</td>
                                    </tr>

                                    <tr class="bold">
                                        <td>{{ __('admin::app.sales.orders.grand-total') }}</td>
                                        <td>-</td>
                                        <td>{{ core()->formatBasePrice($order->base_grand_total) }}</td>
                                    </tr>

                                    <tr class="bold">
                                        <td>{{ __('admin::app.sales.orders.total-paid') }}</td>
                                        <td>-</td>
                                        <td>{{ core()->formatBasePrice($order->base_grand_total_invoiced) }}</td>
                                    </tr>

                                    <tr class="bold">
                                        <td>{{ __('admin::app.sales.orders.total-refunded') }}</td>
                                        <td>-</td>
                                        <td>{{ core()->formatBasePrice($order->base_grand_total_refunded) }}</td>
                                    </tr>

                                    <tr class="bold">
                                        <td>{{ __('admin::app.sales.orders.total-due') }}</td>
                                        <td>-</td>
                                        <td>{{ core()->formatBasePrice($order->base_total_due) }}</td>
                                    </tr>
                                </table>

                            </div>
                        </accordian>

                    </div>
                </tab>

                <tab name="{{ __('admin::app.sales.orders.invoices') }}">

                    <div class="table" style="padding: 20px 0">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('admin::app.sales.invoices.id') }}</th>
                                    <th>{{ __('admin::app.sales.invoices.date') }}</th>
                                    <th>{{ __('admin::app.sales.invoices.order-id') }}</th>
                                    <th>{{ __('admin::app.sales.invoices.customer-name') }}</th>
                                    <th>{{ __('admin::app.sales.invoices.status') }}</th>
                                    <th>{{ __('admin::app.sales.invoices.amount') }}</th>
                                    <th>{{ __('admin::app.sales.invoices.action') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($order->invoices as $invoice)
                                    <tr>
                                        <td>#{{ $invoice->id }}</td>
                                        <td>{{ $invoice->created_at }}</td>
                                        <td>#{{ $invoice->order->id }}</td>
                                        <td>{{ $invoice->address->name }}</td>
                                        <td>{{ $invoice->status_label }}</td>
                                        <td>{{ core()->formatBasePrice($invoice->base_grand_total) }}</td>
                                        <td class="action">
                                            <a href="{{ route('admin.sales.invoices.view', $invoice->id) }}">
                                                <i class="icon eye-icon"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                @if (!$order->invoices->count())
                                    <tr>
                                        <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                    <tr>
                                @endif
                        </table>
                    </div>

                </tab>

                <tab name="{{ __('admin::app.sales.orders.shipments') }}">

                    <div class="table" style="padding: 20px 0">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('admin::app.sales.shipments.id') }}</th>
                                    <th>{{ __('admin::app.sales.shipments.date') }}</th>
                                    <th>{{ __('admin::app.sales.shipments.order-id') }}</th>
                                    <th>{{ __('admin::app.sales.shipments.order-date') }}</th>
                                    <th>{{ __('admin::app.sales.shipments.customer-name') }}</th>
                                    <th>{{ __('admin::app.sales.shipments.total-qty') }}</th>
                                    <th>{{ __('admin::app.sales.shipments.action') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($order->shipments as $shipment)
                                    <tr>
                                        <td>#{{ $shipment->id }}</td>
                                        <td>{{ $shipment->created_at }}</td>
                                        <td>#{{ $shipment->order->id }}</td>
                                        <td>{{ $shipment->order->created_at }}</td>
                                        <td>{{ $shipment->address->name }}</td>
                                        <td>{{ $shipment->total_qty }}</td>
                                        <td class="action">
                                            <a href="{{ route('admin.sales.shipments.view', $shipment->id) }}">
                                                <i class="icon eye-icon"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                @if (!$order->shipments->count())
                                    <tr>
                                        <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                    <tr>
                                @endif
                        </table>
                    </div>

                </tab>
            </tabs>
        </div>

    </div>
@stop