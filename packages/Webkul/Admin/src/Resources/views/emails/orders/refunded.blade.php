@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            {{ __('admin::app.emails.orders.refunded.title') }}
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('admin::app.emails.dear', ['customer_name' => $refund->order->customer_full_name]) }},👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('admin::app.emails.orders.refunded.greeting', [
                'invoice_id' => $refund->increment_id,
                'order_id'   => '<a href="' . route('admin.sales.orders.view', $refund->order_id) . '" style="color: #2969FF;">#' . $refund->order->increment_id . '</a>',
                'created_at' => core()->formatDate($refund->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <div style="font-size: 20px;font-weight: 600;color: #121A26">
        {{ __('admin::app.emails.orders.refunded.summary') }}
    </div>

    <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
        @if ($refund->order->shipping_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.shipping-address') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $refund->order->shipping_address->company_name ?? '' }}<br/>

                    {{ $refund->order->shipping_address->name }}<br/>
                    
                    {{ $refund->order->shipping_address->address1 }}<br/>
                    
                    {{ $refund->order->shipping_address->postcode . " " . $refund->order->shipping_address->city }}<br/>
                    
                    {{ $refund->order->shipping_address->state }}<br/>

                    ---<br/>

                    {{ __('admin::app.emails.orders.contact') }} : {{ $refund->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.shipping') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ $refund->order->shipping_title }}
                </div>
            </div>
        @endif

        @if ($refund->order->billing_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.billing-address') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $refund->order->billing_address->company_name ?? '' }}<br/>

                    {{ $refund->order->billing_address->name }}<br/>
                    
                    {{ $refund->order->billing_address->address1 }}<br/>
                    
                    {{ $refund->order->billing_address->postcode . " " . $refund->order->billing_address->city }}<br/>
                    
                    {{ $refund->order->billing_address->state }}<br/>

                    ---<br/>

                    {{ __('admin::app.emails.orders.contact') }} : {{ $refund->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.payment') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ core()->getConfigData('sales.payment_methods.' . $refund->order->payment->method . '.title') }}
                </div>

                @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($refund->order->payment->method); @endphp

                @if (! empty($additionalDetails))
                    <div style="font-size: 16px; color: #384860;">
                        <div>{{ $additionalDetails['title'] }}</div>
                        <div>{{ $additionalDetails['value'] }}</div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div style="padding-bottom: 40px;border-bottom: 1px solid #CBD5E1;">
        <table style="overflow-x: auto; border-collapse: collapse;
        border-spacing: 0;width: 100%">
            <thead>
                <tr style="color: #121A26;border-top: 1px solid #CBD5E1;border-bottom: 1px solid #CBD5E1;">
                    <th style="text-align: left;padding: 15px">{{ __('admin::app.emails.orders.name') }}</th>
                    <th style="text-align: left;padding: 15px">{{ __('admin::app.emails.orders.price') }}</th>
                    <th style="text-align: left;padding: 15px">{{ __('admin::app.emails.orders.qty') }}</th>
                </tr>
            </thead>

            <tbody style="font-size: 16px;font-weight: 400;color: #384860;">
                @foreach ($refund->items as $item)
                    <tr>
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

                        <td style="text-align: left;padding: 15px">{{ core()->formatPrice($item->price, $refund->order_currency_code) }}
                        </td>

                        <td style="text-align: left;padding: 15px">{{ $item->qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="display: grid;justify-content: end;font-size: 16px;color: #384860;line-height: 30px;padding-top: 20px;padding-bottom: 20px;">
        <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
            <span>{{ __('admin::app.emails.orders.subtotal') }}</span>

            <span style="text-align: right;">
                {{ core()->formatPrice($refund->sub_total, $refund->order_currency_code) }}
            </span>
        </div>

        @if ($refund->order->shipping_address)
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>{{ __('admin::app.emails.orders.shipping-handling') }}</span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($refund->shipping_amount, $refund->order_currency_code) }}
                </span>
            </div>
        @endif

        @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($refund->order, false) as $taxRate => $taxAmount )
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    {{ __('admin::app.emails.orders.tax') }} {{ $taxRate }} %
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($refund->tax_amount, $refund->order_currency_code) }}
                </span>
            </div>
        @endforeach

        @if ($refund->discount_amount > 0)
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>{{ __('admin::app.emails.orders.discount') }}</span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($refund->discount_amount, $refund->order_currency_code) }}
                </span>
            </div>
        @endif

        <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));font-weight: bold">
            <span>{{ __('admin::app.emails.orders.grand-total') }}</span>

            <span style="text-align: right;">
                {{ core()->formatPrice($refund->grand_total, $refund->order_currency_code) }}
            </span>
        </div>
    </div>
@endcomponent
