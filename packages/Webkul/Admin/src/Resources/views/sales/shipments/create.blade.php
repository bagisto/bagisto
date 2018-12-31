@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.shipments.add-title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <form method="POST" action="{{ route('admin.sales.shipments.store', $order->id) }}" @submit.prevent="onSubmit">
            @csrf()
            
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.sales.shipments.add-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.sales.shipments.save-btn-title') }}
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
                                            {{ __('admin::app.sales.shipments.order-id') }}
                                        </span>

                                        <span class="value">
                                            <a href="{{ route('admin.sales.orders.view', $order->id) }}">#{{ $order->id }}</a>
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

                                    <div class="control-group" :class="[errors.has('shipment[carrier_title]') ? 'has-error' : '']" style="margin-top: 40px">
                                        <label for="shipment[carrier_title]" class="required">{{ __('admin::app.sales.shipments.carrier-title') }}</label>
                                        <input type="text" v-validate="'required'" class="control" id="shipment[carrier_title]" name="shipment[carrier_title]" data-vv-as="&quot;{{ __('admin::app.sales.shipments.carrier-title') }}&quot;"/>
                                        <span class="control-error" v-if="errors.has('shipment[carrier_title]')">
                                            @{{ errors.first('shipment[carrier_title]') }}
                                        </span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('shipment[track_number]') ? 'has-error' : '']">
                                        <label for="shipment[track_number]" class="required">{{ __('admin::app.sales.shipments.tracking-number') }}</label>
                                        <input type="text" v-validate="'required'" class="control" id="shipment[track_number]" name="shipment[track_number]" data-vv-as="&quot;{{ __('admin::app.sales.shipments.tracking-number') }}&quot;"/>
                                        <span class="control-error" v-if="errors.has('shipment[track_number]')">
                                            @{{ errors.first('shipment[track_number]') }}
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
                                            <th>{{ __('admin::app.sales.shipments.qty-ordered') }}</th>
                                            <th>{{ __('admin::app.sales.shipments.qty-to-ship') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($order->items as $item)
                                            @if ($item->qty_to_ship > 0)
                                                <tr>
                                                    <td>{{ $item->type == 'configurable' ? $item->child->sku : $item->sku }}</td>
                                                    <td>
                                                        {{ $item->name }}

                                                        @if ($html = $item->getOptionDetailHtml())
                                                            <p>{{ $html }}</p>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->qty_ordered }}</td>
                                                    <td>
                                                        <div class="control-group" :class="[errors.has('shipment[items][{{ $item->id }}]') ? 'has-error' : '']">
                                                            <input type="text" v-validate="'required|numeric|min:0'" class="control" id="shipment[items][{{ $item->id }}]" name="shipment[items][{{ $item->id }}]" value="{{ $item->qty_to_ship }}" data-vv-as="&quot;{{ __('admin::app.sales.shipments.qty-to-ship') }}&quot;"/>

                                                            <span class="control-error" v-if="errors.has('shipment[items][{{ $item->id }}]')">
                                                                @verbatim
                                                                    {{ errors.first('shipment[items][<?php echo $item->id ?>]') }}
                                                                @endverbatim
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop