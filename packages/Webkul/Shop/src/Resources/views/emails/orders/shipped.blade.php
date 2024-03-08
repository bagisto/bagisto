@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            @lang('shop::app.emails.orders.shipped.title')
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('shop::app.emails.dear', ['customer_name' => $shipment->order->customer_full_name]),👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('shop::app.emails.orders.shipped.greeting', [
                'invoice_id' => $shipment->increment_id,
                'order_id'   => '<a href="' . route('shop.customers.account.orders.view', $shipment->order_id) . '" style="color: #2969FF;">#' . $shipment->order->increment_id . '</a>',
                'created_at' => core()->formatDate($shipment->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <div style="font-size: 20px;font-weight: 600;color: #121A26">
        @lang('shop::app.emails.orders.shipped.summary')
    </div>

    <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
        @if ($shipment->order->shipping_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.shipping-address')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $shipment->order->shipping_address->company_name ?? '' }}<br/>

                    {{ $shipment->order->shipping_address->name }}<br/>
                    
                    {{ $shipment->order->shipping_address->address }}<br/>
                    
                    {{ $shipment->order->shipping_address->postcode . " " . $shipment->order->shipping_address->city }}<br/>
                    
                    {{ $shipment->order->shipping_address->state }}<br/>

                    ---<br/>

                    @lang('shop::app.emails.orders.contact') : {{ $shipment->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.shipping')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ $shipment->order->shipping_title }}
                </div>

                @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($shipment->order->payment->method); @endphp

                @if (! empty($additionalDetails))
                    <div style="font-size: 16px; color: #384860;">
                        <div>
                            <span>
                                @lang('shop::app.emails.orders.carrier') : 
                            </span>
                            
                            {{ $shipment->carrier_title }}
                        </div>

                        <div>
                            <span>
                                @lang('shop::app.emails.orders.tracking-number', ['tracking_number' =>  $shipment->track_number])
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if ($shipment->order->billing_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.billing-address')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $shipment->order->billing_address->company_name ?? '' }}<br/>

                    {{ $shipment->order->billing_address->name }}<br/>
                    
                    {{ $shipment->order->billing_address->address }}<br/>
                    
                    {{ $shipment->order->billing_address->postcode . " " . $shipment->order->billing_address->city }}<br/>
                    
                    {{ $shipment->order->billing_address->state }}<br/>

                    ---<br/>

                    @lang('shop::app.emails.orders.contact') : {{ $shipment->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('shop::app.emails.orders.payment')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ core()->getConfigData('sales.payment_methods.' . $shipment->order->payment->method . '.title') }}
                </div>
            </div>
        @endif
    </div>

    <div style="padding-bottom: 40px;border-bottom: 1px solid #CBD5E1;">
        <table style="overflow-x: auto; border-collapse: collapse;
        border-spacing: 0;width: 100%">
            <thead>
                <tr style="color: #121A26;border-top: 1px solid #CBD5E1;border-bottom: 1px solid #CBD5E1;">
                    @foreach (['sku', 'name', 'price', 'qty'] as $item)
                        <th style="text-align: left;padding: 15px">
                            @lang('shop::app.emails.orders.' . $item)
                        </th>    
                    @endforeach
                </tr>
            </thead>

            <tbody style="font-size: 16px;font-weight: 400;color: #384860;">
                @foreach ($shipment->items as $item)
                    <tr>
                        <td style="text-align: left;padding: 15px">{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>

                        <td style="text-align: left;padding: 15px">
                            {{ $item->name }}

                            @if (isset($item->additional['attributes']))
                                <div>

                                    @foreach ($item->additional['attributes'] as $attribute)
                                        <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                    @endforeach

                                </div>
                            @endif
                        </td>

                        <td style="text-align: left;padding: 15px">{{ core()->formatPrice($item->price, $shipment->order_currency_code) }}
                        </td>

                        <td style="text-align: left;padding: 15px">{{ $item->qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="display: grid;justify-content: end;font-size: 16px;color: #384860;line-height: 30px;padding-top: 20px;padding-bottom: 20px;">
        <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
            <span>
                @lang('shop::app.emails.orders.subtotal')
            </span>

            <span style="text-align: right;">
                {{ core()->formatPrice($shipment->sub_total, $shipment->order_currency_code) }}
            </span>
        </div>

        @if ($shipment->order->shipping_address)
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.shipping-handling')
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->shipping_amount, $shipment->order_currency_code) }}
                </span>
            </div>
        @endif

        @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($shipment->order, false) as $taxRate => $taxAmount )
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.tax') {{ $taxRate }} %
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->tax_amount, $shipment->order_currency_code) }}
                </span>
            </div>
        @endforeach

        @if ($shipment->discount_amount > 0)
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    @lang('shop::app.emails.orders.discount')
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($shipment->discount_amount, $shipment->order_currency_code) }}
                </span>
            </div>
        @endif

        <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));font-weight: bold">
            <span>
                @lang('shop::app.emails.orders.grand-total')
            </span>

            <span style="text-align: right;">
                {{ core()->formatPrice($shipment->grand_total, $shipment->order_currency_code) }}
            </span>
        </div>
    </div>
@endcomponent
