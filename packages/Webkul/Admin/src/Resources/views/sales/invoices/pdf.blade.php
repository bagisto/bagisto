<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        {{-- meta tags --}}
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        {{-- lang supports inclusion --}}
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

        {{-- main css --}}
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
                margin-top: 20px;
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
                padding: 5px 10px;
                background: #F4F4F4;
            }

            .table thead th:last-child {
                border-right: solid 1px #d3d3d3;
            }

            .table tbody td {
                padding: 5px 10px;
                border-bottom: solid 1px #d3d3d3;
                border-left: solid 1px #d3d3d3;
                color: #3A3A3A;
                vertical-align: middle;
            }

            .table tbody td p {
                margin: 0;
            }

            .table tbody td:last-child {
                border-right: solid 1px #d3d3d3;
            }

            .sale-summary {
                margin-top: 40px;
                float: right;
            }

            .sale-summary tr td {
                padding: 3px 5px;
            }

            .sale-summary tr.bold {
                font-weight: 600;
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

             .logo {
                margin-left: 300px;
            }
        </style>
    </head>

    <body style="background-image: none; background-color: #fff;">
        <div class="container">
            <div class="header">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center">{{ __('admin::app.sales.invoices.invoice') }}</h1>
                    </div>
                </div>

                @if (core()->getConfigData('sales.invoice_setttings.invoice_slip_design.logo'))
                    <div class="image">
                        <img class="logo" src="{{ Storage::url(core()->getConfigData('sales.invoice_setttings.invoice_slip_design.logo')) }}"/>
                    </div>
                @endif

                <div class="merchant-details">
                    <div><span class="merchant-details-title">{{ core()->getConfigData('sales.shipping.origin.store_name') ? core()->getConfigData('sales.shipping.origin.store_name') : '' }}</span></div>

                    <div>{{ core()->getConfigData('sales.shipping.origin.address1') ? core()->getConfigData('sales.shipping.origin.address1') : '' }}</div>

                    <div>
                        <span>{{ core()->getConfigData('sales.shipping.origin.zipcode') ? core()->getConfigData('sales.shipping.origin.zipcode') : '' }}</span>
                        <span>{{ core()->getConfigData('sales.shipping.origin.city') ? core()->getConfigData('sales.shipping.origin.city') : '' }}</span>
                    </div>

                    <div>{{ core()->getConfigData('sales.shipping.origin.state') ? core()->getConfigData('sales.shipping.origin.state') : '' }}</div>

                    <div>{{ core()->getConfigData('sales.shipping.origin.country') ?  core()->country_name(core()->getConfigData('sales.shipping.origin.country')) : '' }}</div>
                </div>

                <div class="merchant-details">
                    @if (core()->getConfigData('sales.shipping.origin.contact'))
                        <div>
                            <span class="merchant-details-title">{{ __('admin::app.admin.system.contact-number') }}:</span> {{ core()->getConfigData('sales.shipping.origin.contact') }}
                        </div>
                    @endif

                    @if (core()->getConfigData('sales.shipping.origin.vat_number'))
                        <div>
                            <span class="merchant-details-title">{{ __('admin::app.admin.system.vat-number') }}:</span> {{ core()->getConfigData('sales.shipping.origin.vat_number') }}
                        </div>
                    @endif

                    @if (core()->getConfigData('sales.shipping.origin.bank_details'))
                        <div>
                            <span class="merchant-details-title">{{ __('admin::app.admin.system.bank-details') }}:</span> {{ core()->getConfigData('sales.shipping.origin.bank_details') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="invoice-summary">
                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.invoice-id') }} -</span>
                    <span class="value">#{{ $invoice->increment_id ?? $invoice->id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.date') }} -</span>
                    <span class="value">{{ core()->formatDate($invoice->created_at, 'd-m-Y') }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.order-id') }} -</span>
                    <span class="value">#{{ $invoice->order->increment_id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.order-date') }} -</span>
                    <span class="value">{{ $invoice->created_at->format('d-m-Y') }}</span>
                </div>

                @if ($invoice->hasPaymentTerm())
                    <div class="row">
                        <span class="label">{{ __('admin::app.admin.system.payment-terms') }} -</span>
                        <span class="value">{{ $invoice->getFormattedPaymentTerm() }}</span>
                    </div>
                @endif

                <div class="table address">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%">{{ __('admin::app.sales.invoices.bill-to') }}</th>

                                @if ($invoice->order->shipping_address)
                                    <th>{{ __('admin::app.sales.invoices.ship-to') }}</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                @if ($invoice->order->billing_address)
                                    <td>
                                        <p>{{ $invoice->order->billing_address->company_name ?? '' }}</p>
                                        <p>{{ $invoice->order->billing_address->name }}</p>
                                        <p>{{ $invoice->order->billing_address->address1 }}</p>
                                        <p>{{ $invoice->order->billing_address->postcode . ' ' .$invoice->order->billing_address->city }} </p>
                                        <p>{{ $invoice->order->billing_address->state }}</p>
                                        <p>{{ core()->country_name($invoice->order->billing_address->country) }}</p>
                                        {{ __('shop::app.checkout.onepage.contact') }} : {{ $invoice->order->billing_address->phone }}
                                    </td>
                                @endif

                                @if ($invoice->order->shipping_address)
                                    <td>
                                        <p>{{ $invoice->order->shipping_address->company_name ?? '' }}</p>
                                        <p>{{ $invoice->order->shipping_address->name }}</p>
                                        <p>{{ $invoice->order->shipping_address->address1 }}</p>
                                        <p>{{ $invoice->order->shipping_address->postcode . ' ' . $invoice->order->shipping_address->city }}</p>
                                        <p>{{ $invoice->order->shipping_address->state }}</p>
                                        <p>{{ core()->country_name($invoice->order->shipping_address->country) }}</p>
                                        {{ __('shop::app.checkout.onepage.contact') }} : {{ $invoice->order->shipping_address->phone }}
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
                                <th style="width: 50%">{{ __('admin::app.sales.orders.payment-method') }}</th>

                                @if ($invoice->order->shipping_address)
                                    <th>{{ __('admin::app.sales.orders.shipping-method') }}</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    {{ core()->getConfigData('sales.paymentmethods.' . $invoice->order->payment->method . '.title') }}

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
                                <th class="text-center">{{ __('admin::app.sales.orders.SKU') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.product-name') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.price') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.qty') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.subtotal') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.tax-amount') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.grand-total') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td class="text-center">{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>

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

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_price, true) !!}</td>

                                    <td class="text-center">{{ $item->qty }}</td>

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_total, true) !!}</td>

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_tax_amount, true) !!}</td>

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_total + $item->base_tax_amount, true) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <table class="sale-summary">
                    <tr>
                        <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_sub_total, true) !!}</td>
                    </tr>

                    <tr>
                        <td>{{ __('admin::app.sales.orders.shipping-handling') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_shipping_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td>{{ __('admin::app.sales.orders.tax') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_tax_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td>{{ __('admin::app.sales.orders.discount') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_discount_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <hr>
                        </td>
                    </tr>

                    <tr>
                        <td>{{ __('admin::app.sales.orders.grand-total') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_grand_total, true) !!}</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
