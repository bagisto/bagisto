<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="Cache-control" content="no-cache">

        <style type="text/css">
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
                font-weight: 600;
            }

            .logo {
                height: 70px;
                width: 70px;
            }

        </style>
    </head>

    <body style="background-image: none;background-color: #fff;">
        <div class="container">

            <div class="header">
                <div class="image">
                    <img class="logo" src="{{ Storage::url(core()->getConfigData('sales.orderSettings.invoice_slip_design.logo')) }}"/>
                </div>
                <div class="address">
                    <p>
                      <b> {{ core()->getConfigData('sales.orderSettings.invoice_slip_design.address') }} </b>
                    </p>
                </div>
            </div>

            <div class="invoice-summary">

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.invoice-id') }} -</span>
                    <span class="value">#{{ $invoice->id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.order-id') }} -</span>
                    <span class="value">#{{ $invoice->order->increment_id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.order-date') }} -</span>
                    <span class="value">{{ $invoice->created_at->format('M d, Y') }}</span>
                </div>

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
                                <td>
                                    <p>{{ $invoice->order->billing_address->name }}</p>
                                    <p>{{ $invoice->order->billing_address->address1 }}</p>
                                    <p>{{ $invoice->order->billing_address->city }}</p>
                                    <p>{{ $invoice->order->billing_address->state }}</p>
                                    <p>{{ core()->country_name($invoice->order->billing_address->country) }} {{ $invoice->order->billing_address->postcode }}</p>
                                    {{ __('shop::app.checkout.onepage.contact') }} : {{ $invoice->order->billing_address->phone }}
                                </td>

                                @if ($invoice->order->shipping_address)
                                    <td>
                                        <p>{{ $invoice->order->shipping_address->name }}</p>
                                        <p>{{ $invoice->order->shipping_address->address1 }}</p>
                                        <p>{{ $invoice->order->shipping_address->city }}</p>
                                        <p>{{ $invoice->order->shipping_address->state }}</p>
                                        <p>{{ core()->country_name($invoice->order->shipping_address->country) }} {{ $invoice->order->shipping_address->postcode }}</p>
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
                                <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                                <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                                <th>{{ __('admin::app.sales.orders.price') }}</th>
                                <th>{{ __('admin::app.sales.orders.qty') }}</th>
                                <th>{{ __('admin::app.sales.orders.subtotal') }}</th>
                                <th>{{ __('admin::app.sales.orders.tax-amount') }}</th>
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
                                    <td>{{ core()->formatBasePrice($item->base_total + $item->base_tax_amount) }}</td>
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

                    <tr>
                        <td>{{ __('admin::app.sales.orders.discount') }}</td>
                        <td>-</td>
                        <td>{{ core()->formatBasePrice($invoice->base_discount_amount) }}</td>
                    </tr>

                    <tr class="bold">
                        <td>{{ __('admin::app.sales.orders.grand-total') }}</td>
                        <td>-</td>
                        <td>{{ core()->formatBasePrice($invoice->base_grand_total) }}</td>
                    </tr>
                </table>

            </div>

        </div>
    </body>
</html>
