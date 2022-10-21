@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            @if (core()->getConfigData('general.design.admin_logo.logo_image'))
                <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image')) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
            @else
                <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="{{ config('app.name') }}"/>
            @endif
        </a>
    </div>

    <?php $order = $shipment->order; ?>
    <?php $inventory = $shipment->inventory_source; ?>

    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <span style="font-weight: bold;">
                {{ __('shop::app.mail.shipment.inventory-heading', ['order_id' => $order->increment_id, 'shipment_id' => $shipment->id]) }}
            </span> <br>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.dear', ['customer_name' => $inventory->name]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! __('shop::app.mail.shipment.greeting', [
                    'order_id' => '<a href="' . route('shop.customer.orders.view', $order->id) . '" style="color: #0041FF; font-weight: bold;">#' . $order->increment_id . '</a>',
                    'created_at' => core()->formatDate($order->created_at, 'Y-m-d H:i:s')
                    ])
                !!}
            </p>
        </div>

        <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
            @if ($order->shipping_address)
                <div style="line-height: 25px;">
                    <div style="font-weight: bold;font-size: 16px;color: #242424;">
                        {{ __('shop::app.mail.order.shipping-address') }}
                    </div>

                    <div>
                        {{ $order->shipping_address->company_name ?? '' }}
                    </div>

                    <div>
                        {{ $order->shipping_address->name }}
                    </div>

                    <div>
                        {{ $order->shipping_address->address1 }}
                    </div>

                    <div>
                        {{ $order->shipping_address->postcode . " " . $order->shipping_address->city }}
                    </div>

                    <div>
                        {{ $order->shipping_address->state }}
                    </div>

                    <div>
                        {{ core()->country_name($order->shipping_address->country) }}
                    </div>

                    <div>---</div>

                    <div style="margin-bottom: 40px;">
                        {{ __('shop::app.mail.order.contact') }} : {{ $order->shipping_address->phone }}
                    </div>

                    <div style="font-weight: bold;font-size: 16px;color: #242424;">
                        {{ __('shop::app.mail.order.shipping') }}
                    </div>

                    <div style="font-size: 16px;color: #242424;">
                        <div style="font-weight: bold;">
                            {{ $order->shipping_title }}
                        </div>

                        <div style="margin-top: 5px;">
                            <span style="font-weight: bold;">{{ __('shop::app.mail.shipment.carrier') }} : </span>{{ $shipment->carrier_title }}
                        </div>

                        <div style="margin-top: 5px;">
                            <span style="font-weight: bold;">{{ __('shop::app.mail.shipment.tracking-number') }} : </span>{{ $shipment->track_number }}
                        </div>
                    </div>
                </div>
            @endif

            @if ($order->billing_address)
                <div style="line-height: 25px;">
                    <div style="font-weight: bold;font-size: 16px;color: #242424;">
                        {{ __('shop::app.mail.order.billing-address') }}
                    </div>

                    <div>
                        {{ $order->billing_address->company_name ?? '' }}
                    </div>

                    <div>
                        {{ $order->billing_address->name }}
                    </div>

                    <div>
                        {{ $order->billing_address->address1 }}
                    </div>

                    <div>
                        {{ $order->billing_address->postcode . " " . $order->billing_address->city }}
                    </div>

                    <div>
                        {{ $order->billing_address->state }}
                    </div>

                    <div>
                        {{ core()->country_name($order->billing_address->country) }}
                    </div>

                    <div>---</div>

                    <div style="margin-bottom: 40px;">
                        {{ __('shop::app.mail.order.contact') }} : {{ $order->billing_address->phone }}
                    </div>

                    <div style="font-weight: bold; font-size: 16px; color: #242424;">
                        {{ __('shop::app.mail.order.payment') }}
                    </div>

                    <div style="font-weight: bold; font-size: 16px; color: #242424;">
                        {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}
                    </div>
                </div>
            @endif
        </div>

        <div class="section-content">
            <div class="table mb-20">
                <table style="overflow-x: auto; border-collapse: collapse;
                border-spacing: 0;width: 100%">
                    <thead>
                        <tr style="background-color: #f2f2f2">
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.price') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.qty') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($shipment->items as $item)
                            <tr>
                                <td data-value="{{ __('shop::app.customer.account.order.view.SKU') }}" style="text-align: left;padding: 8px">{{ $item->sku }}</td>

                                <td data-value="{{ __('shop::app.customer.account.order.view.product-name') }}" style="text-align: left;padding: 8px">
                                    {{ $item->name }}

                                    @if (isset($item->additional['attributes']))
                                        <div class="item-options">

                                            @foreach ($item->additional['attributes'] as $attribute)
                                                <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                            @endforeach

                                        </div>
                                    @endif
                                </td>

                                <td data-value="{{ __('shop::app.customer.account.order.view.price') }}" style="text-align: left;padding: 8px">{{ core()->formatPrice($item->price, $order->order_currency_code) }}</td>

                                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}" style="text-align: left;padding: 8px">{{ $item->qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- <div style="margin-top: 20px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block;width: 100%">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!!
                    __('shop::app.mail.order.help', [
                        'support_email' => '<a style="color:#0041FF" href="mailto:' . config('mail.from.address') . '">' . config('mail.from.address'). '</a>'
                        ])
                !!}
            </p>
        </div> --}}
    </div>
@endcomponent
