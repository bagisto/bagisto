@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.invoices.view-title', ['invoice_id' => $invoice->increment_id ?? $invoice->id]) }}
@stop

@section('content-wrapper')
    @php
        $order = $invoice->order;
    @endphp

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    {!! view_render_event('sales.invoice.title.before', ['order' => $order]) !!}

                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.invoices.index') }}'"></i>

                    {{ __('admin::app.sales.invoices.view-title', ['invoice_id' => $invoice->increment_id ?? $invoice->id]) }}

                    {!! view_render_event('sales.invoice.title.after', ['order' => $order]) !!}
                </h1>
            </div>

            <div class="page-action">
                {!! view_render_event('sales.invoice.page_action.before', ['order' => $order]) !!}

                <a
                    href="javascript:void(0);"
                    class="btn btn-lg btn-primary"
                    @click="showModal('duplicateInvoiceFormModal')">
                    {{ __('admin::app.sales.invoices.send-duplicate-invoice') }}
                </a>

                <a href="{{ route('admin.sales.invoices.print', $invoice->id) }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.sales.invoices.print') }}
                </a>

                {!! view_render_event('sales.invoice.page_action.after', ['order' => $order]) !!}
            </div>
        </div>

        <div class="page-content">
            <tabs>
                <tab name="{{ __('admin::app.sales.orders.info') }}" :selected="true">
                    <div class="sale-container">
                        <accordian title="{{ __('admin::app.sales.orders.order-and-account') }}" :active="true">
                            <div slot="body">
                                <div class="sale">
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
                                                    {{ __('admin::app.sales.invoices.status') }}
                                                </span>

                                                <span class="value">
                                                    {{ $invoice->status_label }}
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
                            </div>
                        </accordian>

                        @if (
                            $order->billing_address
                            || $order->shipping_address
                        )
                            <accordian title="{{ __('admin::app.sales.orders.address') }}" :active="true">
                                <div slot="body">
                                    <div class="sale">
                                        @if ($order->billing_address)
                                            <div class="sale-section">
                                                <div class="secton-title" >
                                                    <span>{{ __('admin::app.sales.orders.billing-address') }}</span>
                                                </div>

                                                <div class="section-content">
                                                    @include ('admin::sales.address', ['address' => $order->billing_address])

                                                    {!! view_render_event('sales.invoice.billing_address.after', ['order' => $order]) !!}
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

                                                    {!! view_render_event('sales.invoice.shipping_address.after', ['order' => $order]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </accordian>
                        @endif

                        <accordian title="{{ __('admin::app.sales.orders.products-ordered') }}" :active="true">
                            <div slot="body">
                                <div class="table">
                                    <div class="table-responsive">
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
                </tab>

                <tab name="{{ __('admin::app.sales.transactions.title') }}" :selected="false">
                    <div class="sale-container">
                        <datagrid-plus src="{{ route('admin.sales.invoices.transactions', $invoice->id) }}"></datagrid-plus>
                    </div>
                </tab>
            </tabs>
        </div>
    </div>

    <modal id="duplicateInvoiceFormModal" :is-open="modalIds.duplicateInvoiceFormModal">
        <h3 slot="header">{{ __('admin::app.sales.invoices.send-duplicate-invoice') }}</h3>

        <div slot="body">
            <form
                method="POST"
                action="{{ route('admin.sales.invoices.send-duplicate-invoice', $invoice->id) }}"
                @submit.prevent="onSubmit">
                @csrf()

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('admin::app.admin.emails.email') }}</label>

                    <input
                        class="control"
                        id="email"
                        v-validate="'required|email'"
                        type="email"
                        name="email"
                        data-vv-as="&quot;{{ __('admin::app.admin.emails.email') }}&quot;"
                        value="{{ $invoice->order->customer_email }}">

                    <span
                        class="control-error"
                        v-text="errors.first('email')"
                        v-if="errors.has('email')">
                    </span>
                </div>

                <button type="submit" class="btn btn-lg btn-primary float-right">
                    {{ __('admin::app.sales.invoices.send') }}
                </button>
            </form>
        </div>
    </modal>
@stop
