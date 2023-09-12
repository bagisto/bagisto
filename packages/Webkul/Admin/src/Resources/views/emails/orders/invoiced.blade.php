@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <span style="font-size: 22px;font-weight: 600;color: #121A26">
            {{ __('admin::app.emails.orders.invoiced.title') }}
        </span> <br>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('admin::app.emails.dear', ['customer_name' => $invoice->order->customer_full_name]) }},👋
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            @lang('admin::app.emails.orders.invoiced.greeting', [
                'invoice_id' => $invoice->increment_id,
                'order_id'   => '<a href="' . route('admin.sales.orders.view', $invoice->order_id) . '" style="color: #2969FF;">#' . $invoice->order->increment_id . '</a>',
                'created_at' => core()->formatDate($invoice->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <div style="font-size: 20px;font-weight: 600;color: #121A26">
        {{ __('admin::app.emails.orders.invoiced.summary') }}
    </div>

    <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
        @if ($invoice->order->shipping_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.shipping-address') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $invoice->order->shipping_address->company_name ?? '' }}<br/>

                    {{ $invoice->order->shipping_address->name }}<br/>
                    
                    {{ $invoice->order->shipping_address->address1 }}<br/>
                    
                    {{ $invoice->order->shipping_address->postcode . " " . $invoice->order->shipping_address->city }}<br/>
                    
                    {{ $invoice->order->shipping_address->state }}<br/>

                    ---<br/>

                    {{ __('admin::app.emails.orders.contact') }} : {{ $invoice->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.shipping') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ $invoice->order->shipping_title }}
                </div>
            </div>
        @endif

        @if ($invoice->order->billing_address)
            <div style="line-height: 25px;">
                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.billing-address') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;margin-bottom: 40px;">
                    {{ $invoice->order->billing_address->company_name ?? '' }}<br/>

                    {{ $invoice->order->billing_address->name }}<br/>
                    
                    {{ $invoice->order->billing_address->address1 }}<br/>
                    
                    {{ $invoice->order->billing_address->postcode . " " . $invoice->order->billing_address->city }}<br/>
                    
                    {{ $invoice->order->billing_address->state }}<br/>

                    ---<br/>

                    {{ __('admin::app.emails.orders.contact') }} : {{ $invoice->order->billing_address->phone }}
                </div>

                <div style="font-size: 16px;font-weight: 600;color: #121A26;">
                    {{ __('admin::app.emails.orders.payment') }}
                </div>

                <div style="font-size: 16px;font-weight: 400;color: #384860;">
                    {{ core()->getConfigData('sales.payment_methods.' . $invoice->order->payment->method . '.title') }}
                </div>

                @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($invoice->order->payment->method); @endphp

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
                    <th style="text-align: left;padding: 15px">{{ __('admin::app.emails.orders.sku') }}</th>
                    <th style="text-align: left;padding: 15px">{{ __('admin::app.emails.orders.name') }}</th>
                    <th style="text-align: left;padding: 15px">{{ __('admin::app.emails.orders.price') }}</th>
                    <th style="text-align: left;padding: 15px">{{ __('admin::app.emails.orders.qty') }}</th>
                </tr>
            </thead>

            <tbody style="font-size: 16px;font-weight: 400;color: #384860;">
                @foreach ($invoice->items as $item)
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

                        <td style="text-align: left;padding: 15px">{{ core()->formatPrice($item->price, $invoice->order_currency_code) }}
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
                {{ core()->formatPrice($invoice->sub_total, $invoice->order_currency_code) }}
            </span>
        </div>

        @if ($invoice->order->shipping_address)
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>{{ __('admin::app.emails.orders.shipping-handling') }}</span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($invoice->shipping_amount, $invoice->order_currency_code) }}
                </span>
            </div>
        @endif

        @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($invoice->order, false) as $taxRate => $taxAmount )
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>
                    {{ __('admin::app.emails.orders.tax') }} {{ $taxRate }} %
                </span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($invoice->tax_amount, $invoice->order_currency_code) }}
                </span>
            </div>
        @endforeach

        @if ($invoice->discount_amount > 0)
            <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));">
                <span>{{ __('admin::app.emails.orders.discount') }}</span>

                <span style="text-align: right;">
                    {{ core()->formatPrice($invoice->discount_amount, $invoice->order_currency_code) }}
                </span>
            </div>
        @endif

        <div style="display: grid;gap: 100px;grid-template-columns: repeat(2, minmax(0, 1fr));font-weight: bold">
            <span>{{ __('admin::app.emails.orders.grand-total') }}</span>

            <span style="text-align: right;">
                {{ core()->formatPrice($invoice->grand_total, $invoice->order_currency_code) }}
            </span>
        </div>
    </div>
@endcomponent
