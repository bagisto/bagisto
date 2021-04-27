@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.refunds.view-title', ['refund_id' => $refund->id]) }}
@stop

@section('content-wrapper')

    <?php $order = $refund->order; ?>

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.refunds.index') }}'"></i>

                    {{ __('admin::app.sales.refunds.view-title', ['refund_id' => $refund->id]) }}
                </h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">
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
                                        {{ __('admin::app.sales.refunds.order-id') }}
                                    </span>

                                    <span class="value">
                                        <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                                    </span>
                                </div>

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
                                        {{ $refund->order->customer_full_name }}
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="title">
                                        {{ __('admin::app.sales.orders.email') }}
                                    </span>

                                    <span class="value">
                                        {{ $refund->order->customer_email }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </accordian>

                @if ($order->billing_address || $order->shipping_address)
                    <accordian :title="'{{ __('admin::app.sales.orders.address') }}'" :active="true">
                        <div slot="body">

                            @if ($order->billing_address)
                                <div class="sale-section">
                                    <div class="secton-title">
                                        <span>{{ __('admin::app.sales.orders.billing-address') }}</span>
                                    </div>

                                    <div class="section-content">

                                        @include ('admin::sales.address', ['address' => $order->billing_address])

                                    </div>
                                </div>
                            @endif

                            @if ($order->shipping_address)
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
                @endif

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
                                        {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}
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
                                        <th>{{ __('admin::app.sales.orders.subtotal') }}</th>
                                        <th>{{ __('admin::app.sales.orders.tax-amount') }}</th>
                                        <th>{{ __('admin::app.sales.orders.discount-amount') }}</th>
                                        <th>{{ __('admin::app.sales.orders.grand-total') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($refund->items as $item)
                                        <tr>
                                            <td>{{ $item->child ? $item->child->sku : $item->sku }}</td>

                                            <td>
                                                {{ $item->name }}

                                                @if (isset($item->additional['attributes']))
                                                    <div class="item-options">

                                                        @foreach ($item->additional['attributes'] as $attribute)
                                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                        @endforeach

                                                    </div>
                                                @endif
                                            </td>

                                            <td>{{ core()->formatBasePrice($item->base_price) }}</td>

                                            <td>{{ $item->qty }}</td>

                                            <td>{{ core()->formatBasePrice($item->base_total) }}</td>

                                            <td>{{ core()->formatBasePrice($item->base_tax_amount) }}</td>

                                            <td>{{ core()->formatBasePrice($item->base_discount_amount) }}</td>

                                            <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}</td>
                                        </tr>
                                    @endforeach

                                    @if (! $refund->items->count())
                                        <tr>
                                            <td class="empty" colspan="7">{{ __('admin::app.common.no-result-found') }}</td>
                                        <tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>

                        <table class="sale-summary">
                            <tr>
                                <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($refund->base_sub_total) }}</td>
                            </tr>

                            @if ($refund->base_shipping_amount > 0)
                                <tr>
                                    <td>{{ __('admin::app.sales.orders.shipping-handling') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatBasePrice($refund->base_shipping_amount) }}</td>
                                </tr>
                            @endif

                            @if ($refund->base_tax_amount > 0)
                                <tr>
                                    <td>{{ __('admin::app.sales.orders.tax') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatBasePrice($refund->base_tax_amount) }}</td>
                                </tr>
                            @endif

                            @if ($refund->base_discount_amount > 0)
                                <tr>
                                    <td>{{ __('admin::app.sales.orders.discount') }}</td>
                                    <td>-</td>
                                    <td>-{{ core()->formatBasePrice($refund->base_discount_amount) }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td>{{ __('admin::app.sales.refunds.adjustment-refund') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($refund->base_adjustment_refund) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('admin::app.sales.refunds.adjustment-fee') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($refund->base_adjustment_fee) }}</td>
                            </tr>

                            <tr class="bold">
                                <td>{{ __('admin::app.sales.orders.grand-total') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($refund->base_grand_total) }}</td>
                            </tr>
                        </table>

                    </div>
                </accordian>

            </div>
        </div>

    </div>
@stop