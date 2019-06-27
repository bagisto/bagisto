@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            <img src="{{ bagisto_asset('images/logo.svg') }}">
        </a>
    </div>

    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <span style="font-weight: bold;">
                {{ __('shop::app.mail.order.heading') }}
            </span> <br>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('shop::app.mail.order.dear-admin', ['admin_name' => config('mail.from.name')]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! __('shop::app.mail.order.greeting-admin', [
                    'order_id' => '<a href="' . route('customer.orders.view', $order->id) . '" style="color: #0041FF; font-weight: bold;">#' . $order->id . '</a>',
                    'created_at' => $order->created_at
                    ])
                !!}
            </p>
        </div>

        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            {{ __('shop::app.mail.order.summary') }}
        </div>

        <div style="display: flex;flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 40px;">
            <div style="line-height: 25px;">
                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.shipping-address') }}
                </div>

                <div>
                    {{ $order->shipping_address->name }}
                </div>

                <div>
                    {{ $order->shipping_address->address1 }}, {{ $order->shipping_address->state }}
                </div>

                <div>
                    {{ core()->country_name($order->shipping_address->country) }} {{ $order->shipping_address->postcode }}
                </div>

                <div>---</div>

                <div style="margin-bottom: 40px;">
                    {{ __('shop::app.mail.order.contact') }} : {{ $order->shipping_address->phone }}
                </div>

                <div style="font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.shipping') }}
                </div>

                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ $order->shipping_title }}
                </div>
            </div>

            <div style="line-height: 25px;">
                <div style="font-weight: bold;font-size: 16px;color: #242424;">
                    {{ __('shop::app.mail.order.billing-address') }}
                </div>

                <div>
                    {{ $order->billing_address->name }}
                </div>

                <div>
                    {{ $order->billing_address->address1 }}, {{ $order->billing_address->state }}
                </div>

                <div>
                    {{ core()->country_name($order->billing_address->country) }} {{ $order->billing_address->postcode }}
                </div>

                <div>---</div>

                <div style="margin-bottom: 40px;">
                    {{ __('shop::app.mail.order.contact') }} : {{ $order->billing_address->phone }}
                </div>

                <div style="font-size: 16px; color: #242424;">
                    {{ __('shop::app.mail.order.payment') }}
                </div>

                <div style="font-weight: bold;font-size: 16px; color: #242424;">
                    {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}
                </div>
            </div>
        </div>

        @foreach ($order->items as $item)
            <div style="background: #FFFFFF;border: 1px solid #E8E8E8;border-radius: 3px;padding: 20px;margin-bottom: 10px">
                <p style="font-size: 18px;color: #242424;line-height: 24px;margin-top: 0;margin-bottom: 10px;font-weight: bold;">
                    {{ $item->name }}
                </p>

                <div style="margin-bottom: 10px;">
                    <label style="font-size: 16px;color: #5E5E5E;">
                        {{ __('shop::app.mail.order.price') }}
                    </label>
                    <span style="font-size: 18px;color: #242424;margin-left: 40px;font-weight: bold;">
                        {{ core()->formatBasePrice($item->base_price) }}
                    </span>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="font-size: 16px;color: #5E5E5E;">
                        {{ __('shop::app.mail.order.quantity') }}
                    </label>
                    <span style="font-size: 18px;color: #242424;margin-left: 40px;font-weight: bold;">
                        {{ $item->qty_ordered }}
                    </span>
                </div>

                @if ($html = $item->getOptionDetailHtml())
                    <div style="">
                        <label style="margin-top: 10px; font-size: 16px;color: #5E5E5E; display: block;">
                            {{ $html }}
                        </label>
                    </div>
                @endif
            </div>
        @endforeach

        <div style="font-size: 16px;color: #242424;line-height: 30px;float: right;width: 40%;margin-top: 20px;">
            <div>
                <span>{{ __('shop::app.mail.order.subtotal') }}</span>
                <span style="float: right;">
                    {{ core()->formatBasePrice($order->base_sub_total) }}
                </span>
            </div>

            <div>
                <span>{{ __('shop::app.mail.order.shipping-handling') }}</span>
                <span style="float: right;">
                    {{ core()->formatBasePrice($order->base_shipping_amount) }}
                </span>
            </div>

            <div>
                <span>{{ __('shop::app.mail.order.tax') }}</span>
                <span style="float: right;">
                    {{ core()->formatBasePrice($order->base_tax_amount) }}
                </span>
            </div>

            @if ($order->discount_amount > 0)
                <div>
                    <span>{{ __('shop::app.mail.order.discount') }}</span>
                    <span style="float: right;">
                        {{ core()->formatBasePrice($order->base_discount_amount) }}
                    </span>
                </div>
            @endif

            <div style="font-weight: bold">
                <span>{{ __('shop::app.mail.order.grand-total') }}</span>
                <span style="float: right;">
                    {{ core()->formatBasePrice($order->base_grand_total) }}
                </span>
            </div>
        </div>

        <div style="margin-top: 65px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!!
                    __('shop::app.mail.order.help', [
                        'support_email' => '<a style="color:#0041FF" href="mailto:' . env('ADMIN_MAIL_TO') . '">' . env('ADMIN_MAIL_TO'). '</a>'
                        ])
                !!}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.thanks') }}
            </p>
        </div>
    </div>
@endcomponent
