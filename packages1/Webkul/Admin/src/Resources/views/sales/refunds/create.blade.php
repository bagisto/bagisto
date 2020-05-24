@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.refunds.add-title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <form method="POST" action="{{ route('admin.sales.refunds.store', $order->id) }}" @submit.prevent="onSubmit">
            @csrf()

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.sales.refunds.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.refunds.save-btn-title') }}
                    </button>
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

                            <refund-items></refund-items>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script type="text/x-template" id="refund-items-template">
        <div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                            <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                            <th>{{ __('admin::app.sales.orders.item-status') }}</th>
                            <th>{{ __('admin::app.sales.orders.subtotal') }}</th>
                            <th>{{ __('admin::app.sales.orders.tax-amount') }}</th>
                            @if ($order->base_discount_amount > 0)
                                <th>{{ __('admin::app.sales.orders.discount-amount') }}</th>
                            @endif
                            <th>{{ __('admin::app.sales.orders.grand-total') }}</th>
                            <th>{{ __('admin::app.sales.refunds.qty-ordered') }}</th>
                            <th>{{ __('admin::app.sales.refunds.qty-to-refund') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ Webkul\Product\Helpers\ProductType::hasVariants($item->type) ? $item->child->sku : $item->sku }}</td>

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

                                <td>
                                    <span class="qty-row">
                                        {{ $item->qty_ordered ? __('admin::app.sales.orders.item-ordered', ['qty_ordered' => $item->qty_ordered]) : '' }}
                                    </span>

                                    <span class="qty-row">
                                        {{ $item->qty_invoiced ? __('admin::app.sales.orders.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}
                                    </span>

                                    <span class="qty-row">
                                        {{ $item->qty_shipped ? __('admin::app.sales.orders.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}
                                    </span>

                                    <span class="qty-row">
                                        {{ $item->qty_refunded ? __('admin::app.sales.orders.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}
                                    </span>

                                    <span class="qty-row">
                                        {{ $item->qty_canceled ? __('admin::app.sales.orders.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                    </span>
                                </td>

                                <td>{{ core()->formatBasePrice($item->base_price) }}</td>

                                <td>{{ core()->formatBasePrice($item->base_tax_amount) }}</td>

                                @if ($order->base_discount_amount > 0)
                                    <td>{{ core()->formatBasePrice($item->base_discount_amount) }}</td>
                                @endif

                                <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount - $item->base_discount_amount) }}</td>

                                <td>{{ $item->qty_ordered }}</td>

                                <td>
                                    <div class="control-group" :class="[errors.has('refund[items][{{ $item->id }}]') ? 'has-error' : '']">
                                        <input type="text" v-validate="'required|numeric|min:0'" class="control" id="refund[items][{{ $item->id }}]" name="refund[items][{{ $item->id }}]" v-model="refund.items[{{ $item->id }}]" data-vv-as="&quot;{{ __('admin::app.sales.refunds.qty-to-refund') }}&quot;"/>

                                        <span class="control-error" v-if="errors.has('refund[items][{{ $item->id }}]')">
                                            @verbatim
                                                {{ errors.first('refund[items][<?php echo $item->id ?>]') }}
                                            @endverbatim
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="width: 100%; display: inline-block">
                <button type="button" class="btn btn-lg btn-primary" style="float: right" @click="updateQty">
                    {{ __('admin::app.sales.refunds.update-qty') }}
                </button>
            </div>

            <table v-if="refund.summary" class="sale-summary">
                <tr>
                    <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                    <td>-</td>
                    <td>@{{ refund.summary.subtotal.formated_price }}</td>
                </tr>

                <tr>
                    <td>{{ __('admin::app.sales.orders.discount') }}</td>
                    <td>-</td>
                    <td>-@{{ refund.summary.discount.formated_price }}</td>
                </tr>

                <tr>
                    <td>{{ __('admin::app.sales.refunds.refund-shipping') }}</td>
                    <td>-</td>
                    <td>
                        <div class="control-group" :class="[errors.has('refund[shipping]') ? 'has-error' : '']" style="width: 100px; margin-bottom: 0;">
                            <input type="text" v-validate="'required|min_value:0|max_value:{{$order->base_shipping_invoiced - $order->base_shipping_refunded}}'" class="control" id="refund[shipping]" name="refund[shipping]" v-model="refund.summary.shipping.price" data-vv-as="&quot;{{ __('admin::app.sales.refunds.refund-shipping') }}&quot;" style="width: 100%; margin: 0"/>

                            <span class="control-error" v-if="errors.has('refund[shipping]')">
                                @{{ errors.first('refund[shipping]') }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>{{ __('admin::app.sales.refunds.adjustment-refund') }}</td>
                    <td>-</td>
                    <td>
                        <div class="control-group" :class="[errors.has('refund[adjustment_refund]') ? 'has-error' : '']" style="width: 100px; margin-bottom: 0;">
                            <input type="text" v-validate="'required|min_value:0'" class="control" id="refund[adjustment_refund]" name="refund[adjustment_refund]" value="0" data-vv-as="&quot;{{ __('admin::app.sales.refunds.adjustment-refund') }}&quot;" style="width: 100%; margin: 0"/>

                            <span class="control-error" v-if="errors.has('refund[adjustment_refund]')">
                                @{{ errors.first('refund[adjustment_refund]') }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>{{ __('admin::app.sales.refunds.adjustment-fee') }}</td>
                    <td>-</td>
                    <td>
                        <div class="control-group" :class="[errors.has('refund[adjustment_fee]') ? 'has-error' : '']" style="width: 100px; margin-bottom: 0;">
                            <input type="text" v-validate="'required|min_value:0'" class="control" id="refund[adjustment_fee]" name="refund[adjustment_fee]" value="0" data-vv-as="&quot;{{ __('admin::app.sales.refunds.adjustment-fee') }}&quot;" style="width: 100%; margin: 0"/>

                            <span class="control-error" v-if="errors.has('refund[adjustment_fee]')">
                                @{{ errors.first('refund[adjustment_fee]') }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr class="border">
                    <td>{{ __('admin::app.sales.orders.tax') }}</td>
                    <td>-</td>
                    <td>@{{ refund.summary.tax.formated_price }}</td>
                </tr>

                <tr class="bold">
                    <td>{{ __('admin::app.sales.orders.grand-total') }}</td>
                    <td>-</td>
                    <td>@{{ refund.summary.grand_total.formated_price }}</td>
                </tr>
            </table>
        </div>
    </script>

    <script>
        Vue.component('refund-items', {
            template: '#refund-items-template',

            inject: ['$validator'],

            data: function() {
                return {
                    refund: {
                        items: {},

                        summary: null
                    }
                }
            },

            mounted: function() {
                @foreach ($order->items as $item)
                    this.refund.items[{{$item->id}}] = {{ $item->qty_to_refund }};
                @endforeach

                this.updateQty();
            },

            methods: {
                updateQty: function() {
                    var this_this = this;

                    this.$http.post("{{ route('admin.sales.refunds.update_qty', $order->id) }}", this.refund.items)
                        .then(function(response) {
                            if (! response.data) {
                                window.flashMessages = [{
                                    'type': 'alert-error',
                                    'message': "{{ __('admin::app.sales.refunds.invalid-qty') }}"
                                }];

                                this_this.$root.addFlashMessages()
                            } else {
                                this_this.refund.summary = response.data;
                            }
                        })
                        .catch(function (error) {})
                }
            }
        });
    </script>
@endpush