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

                            <order-item-list></order-item-list>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')

    <script type="text/x-template" id="order-item-list-template">

        <div>            
        <div class="control-group" :class="[errors.has('shipment[source]') ? 'has-error' : '']">
            <label for="shipment[source]" class="required">{{ __('admin::app.sales.shipments.source') }}</label>
            
            <select v-validate="'required'" class="control" name="shipment[source]" id="shipment[source]" data-vv-as="&quot;{{ __('admin::app.sales.shipments.source') }}&quot;" v-model="source">
                <option value="">{{ __('admin::app.sales.shipments.select-source') }}</option>

                @foreach ($order->channel->inventory_sources as $key => $inventorySource)
                    <option value="{{ $inventorySource->id }}">{{ $inventorySource->name }}</option>
                @endforeach

            </select>

            <span class="control-error" v-if="errors.has('shipment[source]')">
                @{{ errors.first('shipment[source]') }}
            </span>
        </div>

        <div class="table">

            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                        <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                        <th>{{ __('admin::app.sales.shipments.qty-ordered') }}</th>
                        <th>{{ __('admin::app.sales.shipments.qty-to-ship') }}</th>
                        <th>{{ __('admin::app.sales.shipments.available-sources') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($order->items as $item)
                        @if ($item->qty_to_ship > 0 && $item->product)
                            <tr>
                                <td>{{ $item->type == 'configurable' ? $item->child->sku : $item->sku }}</td>
                                <td>
                                    {{ $item->name }}

                                    @if ($html = $item->getOptionDetailHtml())
                                        <p>{{ $html }}</p>
                                    @endif
                                </td>
                                <td>{{ $item->qty_ordered }}</td>
                                <td>{{ $item->qty_to_ship }}</td>
                                <td>
                                    
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin::app.sales.shipments.source') }}</th>
                                                <th>{{ __('admin::app.sales.shipments.qty-available') }}</th>
                                                <th>{{ __('admin::app.sales.shipments.qty-to-ship') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($order->channel->inventory_sources as $key => $inventorySource)
                                                <tr>
                                                    <td>
                                                        {{ $inventorySource->name }}
                                                    </td>

                                                    <td>
                                                        <?php 
                                                            if ($item->type == 'configurable') {
                                                                $sourceQty = $item->child->product->inventory_source_qty($inventorySource);
                                                            } else {
                                                                $sourceQty = $item->product->inventory_source_qty($inventorySource);  
                                                            }
                                                        ?>

                                                        <?php 
                                                            $sourceQty = 0;

                                                            $product = $item->type == 'configurable' ? $item->child->product : $item->product;

                                                            foreach ($product->inventories as $inventory) {
                                                                if ($inventory->inventory_source_id == $inventorySource->id) {
                                                                    $sourceQty += $inventory->qty;
                                                                }
                                                            }
                                                        ?>

                                                        {{ $sourceQty }}
                                                    </td>

                                                    <td>
                                                        <?php $inputName = "shipment[items][$item->id][$inventorySource->id]"; ?>
                                                        
                                                        <div class="control-group" :class="[errors.has('{{ $inputName }}') ? 'has-error' : '']">

                                                            <input type="text" v-validate="'required|numeric|min_value:0|max_value:{{$sourceQty}}'" class="control" id="{{ $inputName }}" name="{{ $inputName }}" value="0" data-vv-as="&quot;{{ __('admin::app.sales.shipments.qty-to-ship') }}&quot;" :disabled="source != '{{ $inventorySource->id }}'"/>

                                                            <span class="control-error" v-if="errors.has('{{ $inputName }}')">
                                                                @verbatim
                                                                    {{ errors.first('<?php echo $inputName; ?>') }}
                                                                @endverbatim
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                </tbody>
            </table>
        </div>
        </div>

    </script>

    <script>
        Vue.component('order-item-list', {

            template: '#order-item-list-template',

            inject: ['$validator'],

            data: () => ({
                source: ""
            })
        });
    </script>

@endpush