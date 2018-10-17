@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.order.view.page-tile', ['order_id' => $order->id]) }}
@endsection

@section('content-wrapper')

    <div class="account-content">
        @include('shop::customers.account.partials.sidemenu')

        <div class="account-layout">

            <div class="account-head">
                <span class="account-heading">
                    {{ __('shop::app.customer.account.order.view.page-tile', ['order_id' => $order->id]) }}
                </span>
            </div>

            <div class="sale-container">

                <tabs>
                    <tab name="{{ __('shop::app.customer.account.order.view.info') }}" :selected="true">

                        <div class="sale-section">
                            <div class="section-content">
                                <div class="row">
                                    <span class="title"> 
                                        {{ __('shop::app.customer.account.order.view.placed-on') }}
                                    </span>

                                    <span class="value"> 
                                        {{ core()->formatDate($order->created_at, 'd M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="sale-section">
                            <div class="secton-title">
                                <span>{{ __('shop::app.customer.account.order.view.products-ordered') }}</span>
                            </div>

                            <div class="section-content">
                                <div class="table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.item-status') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.tax-percent') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                                <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->type == 'configurable' ? $item->child->sku : $item->sku }}
                                                    </td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ core()->formatPrice($item->price, $order->order_currency_code) }}</td>
                                                    <td>
                                                        <span class="qty-row">
                                                            {{ __('shop::app.customer.account.order.view.item-ordered', ['qty_ordered' => $item->qty_ordered]) }}
                                                        </span>

                                                        <span class="qty-row">
                                                            {{ $item->qty_invoiced ? __('shop::app.customer.account.order.view.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}
                                                        </span>

                                                        <span class="qty-row">
                                                            {{ $item->qty_shipped ? __('shop::app.customer.account.order.view.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}
                                                        </span>

                                                        <span class="qty-row">
                                                            {{ $item->qty_canceled ? __('shop::app.customer.account.order.view.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ core()->formatPrice($item->total, $order->order_currency_code) }}</td>
                                                    <td>{{ number_format($item->tax_percent, 2) }}%</td>
                                                    <td>{{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}</td>
                                                    <td>{{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}</td>
                                                </tr>
                                            @endforeach
                                    </table>
                                </div>

                                <div class="totals">
                                    <table class="sale-summary">
                                        <tbody>
                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($order->sub_total, $order->order_currency_code) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($order->shipping_amount, $order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="border">
                                                <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($order->tax_amount, $order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.total-paid') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($order->grand_total_invoiced, $order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.total-refunded') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($order->grand_total_refunded, $order->order_currency_code) }}</td>
                                            </tr>

                                            <tr class="bold">
                                                <td>{{ __('shop::app.customer.account.order.view.total-due') }}</td>
                                                <td>-</td>
                                                <td>{{ core()->formatPrice($order->total_due, $order->order_currency_code) }}</td>
                                            </tr>
                                        <tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </tab>

                    @if ($order->invoices->count())
                        <tab name="{{ __('shop::app.customer.account.order.view.invoices') }}">

                            @foreach ($order->invoices as $invoice)

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('shop::app.customer.account.order.view.individual-invoice', ['invoice_id' => $invoice->id]) }}</span>
                                    </div>

                                    <div class="section-content">
                                        <div class="table">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.price') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @foreach ($invoice->items as $item)
                                                        <tr>
                                                            <td>{{ $item->child ? $item->child->sku : $item->sku }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td>{{ core()->formatPrice($item->price, $order->order_currency_code) }}</td>
                                                            <td>{{ $item->qty }}</td>
                                                            <td>{{ core()->formatPrice($item->total, $order->order_currency_code) }}</td>
                                                            <td>{{ core()->formatPrice($item->tax_amount, $order->order_currency_code) }}</td>
                                                            <td>{{ core()->formatPrice($item->total + $item->tax_amount, $order->order_currency_code) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="totals">
                                            <table class="sale-summary">
                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.subtotal') }}</td>
                                                    <td>-</td>
                                                    <td>{{ core()->formatPrice($invoice->sub_total, $order->order_currency_code) }}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.shipping-handling') }}</td>
                                                    <td>-</td>
                                                    <td>{{ core()->formatPrice($invoice->shipping_amount, $order->order_currency_code) }}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{ __('shop::app.customer.account.order.view.tax') }}</td>
                                                    <td>-</td>
                                                    <td>{{ core()->formatPrice($invoice->tax_amount, $order->order_currency_code) }}</td>
                                                </tr>

                                                <tr class="bold">
                                                    <td>{{ __('shop::app.customer.account.order.view.grand-total') }}</td>
                                                    <td>-</td>
                                                    <td>{{ core()->formatPrice($invoice->grand_total, $order->order_currency_code) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            
                            @endforeach

                        </tab>
                    @endif

                    @if ($order->shipments->count())
                        <tab name="{{ __('shop::app.customer.account.order.view.shipments') }}">

                            @foreach ($order->shipments as $shipment)

                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('shop::app.customer.account.order.view.individual-shipment', ['shipment_id' => $shipment->id]) }}</span>
                                    </div>

                                    <div class="section-content">

                                        <div class="table">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                                                        <th>{{ __('shop::app.customer.account.order.view.qty') }}</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @foreach ($shipment->items as $item)
                                                    
                                                        <tr>
                                                            <td>{{ $item->sku }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td>{{ $item->qty }}</td>
                                                        </tr>

                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            @endforeach

                        </tab>
                    @endif
                </tabs>

                <div class="sale-section">
                    <div class="section-content" style="border-bottom: 0">
                        <div class="order-box-container">
                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.shipping-address') }}
                                </div>

                                <div class="box-content">

                                    @include ('admin::sales.address', ['address' => $order->billing_address])

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.billing-address') }}
                                </div>

                                <div class="box-content">

                                    @include ('admin::sales.address', ['address' => $order->shipping_address])

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.shipping-method') }}
                                </div>

                                <div class="box-content">

                                    {{ $order->shipping_title }}

                                </div>
                            </div>

                            <div class="box">
                                <div class="box-title">
                                    {{ __('shop::app.customer.account.order.view.payment-method') }}
                                </div>

                                <div class="box-content">
                                    {{ core()->getConfigData('paymentmethods.' . $order->payment->method . '.title') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
        </div>

    </div>

@endsection