@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            @lang('admin::app.emails.orders.shipped.title')
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('admin::app.emails.dear', ['admin_name' => core()->getAdminEmailDetails()['name']]),👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('admin::app.emails.orders.shipped.greeting', [
                'invoice_id' => $shipment->increment_id,
                'order_id'   => '<a href="' . route('admin.sales.orders.view', $shipment->order_id) . '" style="color: #2969FF;">#' . $shipment->order->increment_id . '</a>',
                'created_at' => core()->formatDate($shipment->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <div style="font-size: 20px;font-weight: 600;color: #121A26">
        @lang('admin::app.emails.orders.shipped.summary')
    </div>

    <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
        @if ($shipment->order->shipping_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('admin::app.emails.orders.shipping-address')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $shipment->order->shipping_address->company_name ?? '' }}<br/>

                    {{ $shipment->order->shipping_address->name }}<br/>
                    
                    {{ $shipment->order->shipping_address->address }}<br/>
                    
                    {{ $shipment->order->shipping_address->postcode . " " . $shipment->order->shipping_address->city }}<br/>
                    
                    {{ $shipment->order->shipping_address->state }}<br/>

                    ---<br/>

                    @lang('admin::app.emails.orders.contact') : {{ $shipment->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('admin::app.emails.orders.shipping')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ $shipment->order->shipping_title }}
                </div>

                <div style="font-size: 16px; color: #384860;">
                    <div>
                        <span>@lang('admin::app.emails.orders.carrier') : </span>
                        
                        {{ $shipment->carrier_title }}
                    </div>

                    <div>
                        <span>
                            @lang('admin::app.emails.orders.tracking-number', ['tracking_number' => $shipment->track_number])
                        </span>
                    </div>
                </div>

                @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($shipment->order->payment->method); @endphp

                @if (! empty($additionalDetails))
                    <div style="font-size: 16px; color: #384860;">
                        <div>
                            <span>{{ $additionalDetails->title }} : </span>
                        </div>

                        <div>
                            <span>{{ $additionalDetails->value }} </span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if ($shipment->order->billing_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('admin::app.emails.orders.billing-address')
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $shipment->order->billing_address->company_name ?? '' }}<br/>

                    {{ $shipment->order->billing_address->name }}<br/>
                    
                    {{ $shipment->order->billing_address->address }}<br/>
                    
                    {{ $shipment->order->billing_address->postcode . " " . $shipment->order->billing_address->city }}<br/>
                    
                    {{ $shipment->order->billing_address->state }}<br/>

                    ---<br/>

                    @lang('admin::app.emails.orders.contact') : {{ $shipment->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    @lang('admin::app.emails.orders.payment')
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
                    <th style="text-align: left;padding: 15px">@lang('admin::app.emails.orders.sku')</th>
                    <th style="text-align: left;padding: 15px">@lang('admin::app.emails.orders.name')</th>
                    <th style="text-align: left;padding: 15px">@lang('admin::app.emails.orders.price')</th>
                    <th style="text-align: left;padding: 15px">@lang('admin::app.emails.orders.qty')</th>
                </tr>
            </thead>

            <tbody style="font-size: 16px;font-weight: 400;color: #384860;">
                @foreach ($shipment->items as $item)
                    <tr style="vertical-align: text-top;">
                        <td style="text-align: left;padding: 15px">
                            {{ $item->sku }}
                        </td>

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

                        <td style="display: flex;flex-direction: column;text-align: left;padding: 15px">
                            @if (core()->getConfigData('sales.taxes.sales.display_prices') == 'including_tax')
                                {{ core()->formatBasePrice($item->base_price_incl_tax) }}
                            @elseif (core()->getConfigData('sales.taxes.sales.display_prices') == 'both')
                                {{ core()->formatBasePrice($item->base_price_incl_tax) }}

                                <span style="font-size: 12px; white-space: nowrap">
                                    @lang('admin::app.emails.orders.excl-tax')

                                    <span style="font-weight: 600">
                                        {{ core()->formatBasePrice($item->base_price) }}
                                    </span>
                                </span>
                            @else
                                {{ core()->formatBasePrice($item->base_price) }}
                            @endif
                        </td>

                        <td style="text-align: left;padding: 15px">
                            {{ $item->qty }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endcomponent
