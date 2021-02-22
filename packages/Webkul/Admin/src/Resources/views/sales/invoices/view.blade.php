@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.invoices.view-title', ['invoice_id' => $invoice->id]) }}
@stop

@section('content-wrapper')

    <?php $order = $invoice->order; ?>

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {!! view_render_event('sales.invoice.title.before', ['order' => $order]) !!}

                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.invoices.index') }}'"></i>

                    {{ __('admin::app.sales.invoices.view-title', ['invoice_id' => $invoice->id]) }}

                    {!! view_render_event('sales.invoice.title.after', ['order' => $order]) !!}
                </h1>
            </div>

            <div class="page-action">
                {!! view_render_event('sales.invoice.page_action.before', ['order' => $order]) !!}

                <a href="{{ route('admin.sales.invoices.print', $invoice->id) }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.sales.invoices.print') }}
                </a>

                {!! view_render_event('sales.invoice.page_action.after', ['order' => $order]) !!}
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
                                        {{ __('admin::app.sales.invoices.order-id') }}
                                    </span>

                                    <span class="value">
                                        <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->increment_id }}</a>
                                    </span>
                                </div>

                                {!! view_render_event('sales.invoice.increment_id.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">
                                        {{ __('admin::app.sales.orders.order-date') }}
                                    </span>

                                    <span class="value">
                                        {{ $order->created_at }}
                                    </span>
                                </div>

                                {!! view_render_event('sales.invoice.created_at.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">
                                        {{ __('admin::app.sales.orders.order-status') }}
                                    </span>

                                    <span class="value">
                                        {{ $order->status_label }}
                                    </span>
                                </div>

                                {!! view_render_event('sales.invoice.status_label.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">
                                        {{ __('admin::app.sales.orders.channel') }}
                                    </span>

                                    <span class="value">
                                        {{ $order->channel_name }}
                                    </span>
                                </div>

                                {!! view_render_event('sales.invoice.channel_name.after', ['order' => $order]) !!}
                            </div>
                        </div>

                        <div class="sale-section">
                            <div class="secton-title">
                                <span>{{ __('admin::app.sales.orders.account-info') }}</span>
                            </div>

                            <div class="section-content">
                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.customer-name') }}</span>
                                    <span class="value">{{ $invoice->order->customer_full_name }}</span>
                                </div>

                                {!! view_render_event('sales.invoice.customer_name.after', ['order' => $order]) !!}

                                <div class="row">
                                    <span class="title">{{ __('admin::app.sales.orders.email') }}</span>
                                    <span class="value">{{ $invoice->order->customer_email }}</span>
                                </div>

                                {!! view_render_event('sales.invoice.customer_email.after', ['order' => $order]) !!}
                            </div>
                        </div>

                    </div>
                </accordian>

                @if ($order->billing_address || $order->shipping_address)
                    <accordian :title="'{{ __('admin::app.sales.orders.address') }}'" :active="true">
                        <div slot="body" style="display: flex; overflow:auto;">

                            @if ($order->billing_address)
                                <div class="sale-section">
                                    <div class="secton-title" style="width: 380px;">
                                        <span>{{ __('admin::app.sales.orders.billing-address') }}</span>
                                    </div>

                                    <div class="section-content" style="width: 380px;">
                                        @include ('admin::sales.address', ['address' => $order->billing_address])

                                        {!! view_render_event('sales.invoice.billing_address.after', ['order' => $order]) !!}
                                    </div>
                                </div>
                            @endif

                            @if ($order->shipping_address)
                                <div class="sale-section" style="margin: 0 0 0 300px;">
                                    <div class="secton-title" style="width: 400px;">
                                        <span>{{ __('admin::app.sales.orders.shipping-address') }}</span>
                                    </div>

                                    <div class="section-content" style="width: 400px;">
                                        @include ('admin::sales.address', ['address' => $order->shipping_address])

                                        {!! view_render_event('sales.invoice.shipping_address.after', ['order' => $order]) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </accordian>
                @endif

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
                                        @if ($invoice->base_discount_amount > 0)
                                            <th>{{ __('admin::app.sales.orders.discount-amount') }}</th>
                                        @endif
                                        <th>{{ __('admin::app.sales.orders.grand-total') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>

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

                                            @if ($invoice->base_discount_amount > 0)
                                                <td>{{ core()->formatBasePrice($item->base_discount_amount) }}</td>
                                            @endif

                                            <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <table class="sale-summary">
                            <tr>
                                <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_sub_total) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('admin::app.sales.orders.shipping-handling') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_shipping_amount) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('admin::app.sales.orders.tax') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_tax_amount) }}</td>
                            </tr>

                            @if ($invoice->base_discount_amount > 0)
                                <tr>
                                    <td>{{ __('admin::app.sales.orders.discount') }}</td>
                                    <td>-</td>
                                    <td>{{ core()->formatBasePrice($invoice->base_discount_amount) }}</td>
                                </tr>
                            @endif

                            <tr class="bold">
                                <td>{{ __('admin::app.sales.orders.grand-total') }}</td>
                                <td>-</td>
                                <td>{{ core()->formatBasePrice($invoice->base_grand_total) }}</td>
                            </tr>
                        </table>

                    </div>
                </accordian>

            </div>
        </div>

    </div>
@stop