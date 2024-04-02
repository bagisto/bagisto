<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <!-- meta tags -->
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- lang supports inclusion -->
        <style type="text/css">
            @font-face {
                font-family: 'Hind';
                src: url({{ asset('vendor/webkul/ui/assets/fonts/Hind/Hind-Regular.ttf') }}) format('truetype');
            }

            @font-face {
                font-family: 'Noto Sans';
                src: url({{ asset('vendor/webkul/ui/assets/fonts/Noto/NotoSans-Regular.ttf') }}) format('truetype');
            }
        </style>

        @php
            /* main font will be set on locale based */
            $mainFontFamily = app()->getLocale() === 'ar' ? 'DejaVu Sans' : 'Noto Sans';
        @endphp

        <!-- main css -->
        <style type="text/css">
            * {
                font-family: '{{ $mainFontFamily }}';
            }

            body, th, td, h5 {
                font-size: 12px;
                color: #000;
            }

            .container {
                padding: 20px;
                display: block;
            }

            .invoice-summary {
                margin-bottom: 20px;
            }

            .table {
                margin: 20px 6px 0px 6px;
                border-spacing: 0px 0px 15px 0px;
            }

            .table table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                table-layout: fixed;
            }

            .table thead th {
                font-weight: 700;
                border-top: solid 1px #d3d3d3;
                border-bottom: solid 1px #d3d3d3;
                border-left: solid 1px #d3d3d3;
                padding: 5px 12px;
                background: #005aff0d;
            }

            .table thead th:last-child {
                border-right: solid 1px #d3d3d3;
            }

            .table tbody td {
                padding: 5px 10px;
                color: #3A3A3A;
                vertical-align: middle;
                border-bottom: solid 1px #d3d3d3;
            }

            .table tbody td, p {
                margin: 0;
                color: #000;
            }

            .sale-summary {
                margin-top: 20px;
                float: right;
                background-color: #005aff0d;
            }

            .sale-summary tr td {
                padding: 3px 5px;
            }

            .sale-summary tr.bold {
                font-weight: 700;
            }

            .label {
                color: #000;
                font-weight: bold;
            }

            .logo {
                height: 70px;
                width: 70px;
            }

            .merchant-details {
                margin-bottom: 5px;
            }

            .merchant-details-title {
                font-weight: bold;
            }

            .text-center {
                text-align: center;
            }

            .col-6 {
                width: 42%;
                display: inline-block;
                vertical-align: top;
                margin: 0px 5px;
            }

            .table-header {
                color: #0041FF;
            }

            .align-left {
                text-align: left;
            }

            .invoice-text {
                font-size: 40px; 
                color: #3c41ff; 
                font-weight: bold;
                position: absolute; 
                width: 100%; 
                left: 0;
                text-align: center;
                top: -6px;
            }

            .without_logo {
                height: 35px;
                width: 35px;
            }
            
            .header {
                padding: 0px 2px;
                width:100%;
                position: relative;
                border-bottom: solid 1px #d3d3d3;
                padding-bottom: 20px;
            }
        </style>
    </head>

    <body style="background-image: none; background-color: #fff;">
        <div class="container">
            <div>
                <div class="row">
                    <div class="col-12 header">
                        @if (core()->getConfigData('sales.invoice_settings.invoice_slip_design.logo'))
                            <div class="image" style="display:inline-block; vertical-align: middle; padding-top:8px">
                                <img class="logo" src="{{ Storage::url(core()->getConfigData('sales.invoice_settings.invoice_slip_design.logo')) }}" alt=""/>
                            </div>
                        @else
                            <div class="without_logo" style="display:inline-block; vertical-align: middle; padding-top:8px">
                            </div>
                        @endif
                        <div class="invoice-text">
                            <span>{{ strtoupper(__('shop::app.customers.account.orders.invoice-pdf.invoice')) }}</span>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding: 5px">
                    <div class="col-12">
                        <div class="col-6">
                            <div class="merchant-details">
                                <div class="row">
                                    <span class="label">
                                        @lang('shop::app.customers.account.orders.invoice-pdf.invoice-id'): 
                                    </span>

                                    <span class="value">
                                        #{{ $invoice->increment_id ?? $invoice->id }}
                                    </span>
                                </div>

                                <div class="row">
                                    <span
                                        class="label">@lang('shop::app.customers.account.orders.invoice-pdf.date'):
                                    </span>

                                    <span class="value">
                                        {{ core()->formatDate($invoice->created_at, 'd-m-Y') }}
                                    </span>
                                </div>

                                <div style="padding-top: 20px">
                                    <span class="merchant-details-title">{{ core()->getConfigData('sales.shipping.origin.store_name') ? core()->getConfigData('sales.shipping.origin.store_name') : '' }}</span>
                                </div>

                                <div>{{ core()->getConfigData('sales.shipping.origin.address') ?? '' }}</div>

                                <div>
                                    <span>{{ core()->getConfigData('sales.shipping.origin.zipcode') ?? '' }}</span>
                                    <span>{{ core()->getConfigData('sales.shipping.origin.city') ?? '' }}</span>
                                </div>

                                <div>{{ core()->getConfigData('sales.shipping.origin.state') ?? '' }}</div>

                                <div>{{ core()->getConfigData('sales.shipping.origin.country') ?? '' }}</div>
                            </div>
                            <div class="merchant-details">
                                @if (core()->getConfigData('sales.shipping.origin.contact'))
                                    <span class="merchant-details-title">
                                        @lang('shop::app.customers.account.orders.invoice-pdf.contact-number'): 
                                    </span> 
                                    
                                    {{ core()->getConfigData('sales.shipping.origin.contact') }}
                                @endif

                                @if (core()->getConfigData('sales.shipping.origin.vat_number'))
                                    <span class="merchant-details-title">
                                        @lang('shop::app.customers.account.orders.invoice-pdf.vat-number'): 

                                    </span>

                                    {{ core()->getConfigData('sales.shipping.origin.vat_number') }}
                                @endif
                            </div>
                        </div>

                        <div class="col-6" style="padding-left: 80px">
                            <div class="row">
                                <span class="label">
                                    @lang('shop::app.customers.account.orders.invoice-pdf.order-id'): 
                                </span>

                                <span class="value">
                                    #{{ $invoice->order->increment_id }}
                                </span>
                            </div>
                           
                            <div class="row">
                                <span class="label">
                                    @lang('shop::app.customers.account.orders.invoice-pdf.order-date'): 
                                </span>

                                <span class="value">
                                    {{ core()->formatDate($invoice->order->created_at, 'd-m-Y') }}
                                </span>
                            </div>

                            @if ($invoice->hasPaymentTerm())
                                <div class="row">
                                    <span class="label">
                                        @lang('shop::app.customers.account.orders.invoice-pdf.payment-terms') -
                                    </span>

                                    <span class="value">
                                        {{ $invoice->getFormattedPaymentTerm() }}
                                    </span>
                                </div>
                            @endif

                            @if (core()->getConfigData('sales.shipping.origin.bank_details'))
                                <div class="row" style="padding-top: 20px">
                                    <span class="merchant-details-title">
                                        @lang('shop::app.customers.account.orders.invoice-pdf.bank-details'):
                                    </span> 
                                    <div>{{ core()->getConfigData('sales.shipping.origin.bank_details') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="invoice-summary">
                <div class="table address">
                    <table>
                        <thead>
                            <tr>
                                <th class="table-header align-left" style="width: 50%;">{{ ucwords(trans('shop::app.customers.account.orders.invoice-pdf.bill-to')) }}</th>
                                @if ($invoice->order->shipping_address)
                                    <th class="table-header align-left">{{ ucwords(trans('shop::app.customers.account.orders.invoice-pdf.ship-to')) }}</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                @if ($invoice->order->billing_address)
                                    <td>
                                        <p>{{ $invoice->order->billing_address->company_name ?? '' }}</p>
                                        <p>{{ $invoice->order->billing_address->name }}</p>
                                        <p>{{ $invoice->order->billing_address->address }}</p>
                                        <p>{{ $invoice->order->billing_address->city }}</p>
                                        <p>{{ $invoice->order->billing_address->state }}</p>
                                        <p>
                                            {{ core()->country_name($invoice->order->billing_address->country) }}
                                            {{ $invoice->order->billing_address->postcode }}
                                        </p>
                                        @lang('shop::app.customers.account.orders.invoice-pdf.contact') : {{ $invoice->order->billing_address->phone }}
                                    </td>
                                @endif

                                @if ($invoice->order->shipping_address)
                                    <td>
                                        <p>{{ $invoice->order->shipping_address->company_name ?? '' }}</p>
                                        <p>{{ $invoice->order->shipping_address->name }}</p>
                                        <p>{{ $invoice->order->shipping_address->address }}</p>
                                        <p>{{ $invoice->order->shipping_address->city }}</p>
                                        <p>{{ $invoice->order->shipping_address->state }}</p>
                                        <p>{{ core()->country_name($invoice->order->shipping_address->country) }} {{ $invoice->order->shipping_address->postcode }}</p>
                                        @lang('shop::app.customers.account.orders.invoice-pdf.contact') : {{ $invoice->order->shipping_address->phone }}
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table payment-shipment">
                    <table>
                        <thead>
                            <tr>
                                <th class="table-header align-left" style="width: 50%;">
                                    @lang('shop::app.customers.account.orders.invoice-pdf.payment-method')
                                </th>

                                @if ($invoice->order->shipping_address)
                                    <th class="table-header align-left">
                                        @lang('shop::app.customers.account.orders.invoice-pdf.shipping-method')
                                    </th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    {{ core()->getConfigData('sales.payment_methods.' . $invoice->order->payment->method . '.title') }}

                                    @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($invoice->order->payment->method); @endphp

                                    @if (! empty($additionalDetails))
                                        <div>
                                            <label class="label">{{ $additionalDetails['title'] }}:</label>
                                            <p class="value">{{ $additionalDetails['value'] }}</p>
                                        </div>
                                    @endif
                                </td>

                                @if ($invoice->order->shipping_address)
                                    <td>
                                        {{ $invoice->order->shipping_title }}
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table items">
                    <table>
                        <thead>
                            <tr>
                                @foreach (['sku', 'product-name', 'price', 'qty', 'subtotal', 'tax-amount', 'grand-total'] as $item)
                                    <th class="text-center table-header">
                                        @lang('shop::app.customers.account.orders.invoice-pdf.' . $item)
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $item->child ? $item->child->sku : $item->sku }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->name }}

                                        @if (isset($item->additional['attributes']))
                                            <div class="item-options">

                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach

                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ core()->formatPrice($item->price, $invoice->order->order_currency_code) }}</td>

                                    <td class="text-center">{{ $item->qty }}</td>

                                    <td class="text-center">{{ core()->formatPrice($item->total, $invoice->order->order_currency_code) }}</td>

                                    <td class="text-center">{{ core()->formatPrice($item->tax_amount, $invoice->order->order_currency_code) }}</td>

                                    <td class="text-center">{{ core()->formatPrice(($item->total + $item->tax_amount), $invoice->order->order_currency_code) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <table class="sale-summary">
                    <tr>
                        <td>
                            @lang('shop::app.customers.account.orders.invoice-pdf.subtotal')
                        </td>

                        <td>-</td>

                        <td>
                            {{ core()->formatPrice($invoice->sub_total, $invoice->order->order_currency_code) }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            @lang('shop::app.customers.account.orders.invoice-pdf.shipping-handling')
                        </td>

                        <td>-</td>

                        <td>
                            {{ core()->formatPrice($invoice->shipping_amount, $invoice->order->order_currency_code) }}
                        </td>
                    </tr>

                    @if ($invoice->base_discount_amount > 0)
                        <tr>
                            <td>
                                @lang('shop::app.customers.account.orders.invoice-pdf.discount')
                            </td>

                            <td>-</td>

                            <td>
                                {{ core()->formatPrice($invoice->discount_amount, $invoice->order_currency_code) }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td>
                            @lang('shop::app.customers.account.orders.invoice-pdf.tax')
                        </td>

                        <td>-</td>

                        <td>
                            {{ core()->formatPrice($invoice->tax_amount, $invoice->order->order_currency_code) }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <hr>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            @lang('shop::app.customers.account.orders.invoice-pdf.grand-total')
                        </td>

                        <td>-</td>

                        <td>
                            {{ core()->formatPrice($invoice->grand_total, $invoice->order->order_currency_code) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
