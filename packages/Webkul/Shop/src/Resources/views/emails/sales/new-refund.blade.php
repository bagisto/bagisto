@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ route('shop.home.index') }}">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>

    <?php $order = $refund->order; ?>

    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <span style="font-weight: bold;">
                {{ __('shop::app.mail.refund.heading', ['order_id' => $order->increment_id, 'refund_id' => $refund->id]) }}
            </span> <br>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.dear', ['customer_name' => $order->customer_full_name]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! __('shop::app.mail.order.greeting', [
                    'order_id' => '<a href="' . route('shop.customer.orders.view', $order->id) . '" style="color: #0041FF; font-weight: bold;">#' . $order->increment_id . '</a>',
                    'created_at' => core()->formatDate($order->created_at, 'Y-m-d H:i:s')
                    ])
                !!}
            </p>
        </div>

        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            {{ __('shop::app.mail.refund.summary') }}
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

                    <div style="font-size: 16px;color: #242424;">
                        {{ __('shop::app.mail.order.shipping') }}
                    </div>

                    <div style="font-weight: bold;font-size: 16px;color: #242424;">
                        {{ $order->shipping_title }}
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

                    <div style="font-size: 16px; color: #242424;">
                        {{ __('shop::app.mail.order.payment') }}
                    </div>

                    <div style="font-weight: bold;font-size: 16px; color: #242424;">
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
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.price') }}</th>
                            <th style="text-align: left;padding: 8px">{{ __('shop::app.customer.account.order.view.qty') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($refund->items as $item)
                            <tr>
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

                                <td data-value="{{ __('shop::app.customer.account.order.view.price') }}" style="text-align: left;padding: 8px">
                                    {{ core()->formatPrice($item->price, $order->order_currency_code) }}
                                </td>

                                <td data-value="{{ __('shop::app.customer.account.order.view.qty') }}" style="text-align: left;padding: 8px">
                                    {{ $item->qty }}
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="font-size: 16px;color: #242424;line-height: 30px;float: right;width: 40%;margin-top: 20px;">
            <div>
                <span>{{ __('shop::app.mail.order.subtotal') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($refund->sub_total, $refund->order_currency_code) }}
                </span>
            </div>

            @if ($order->shipping_address)
                <div>
                    <span>{{ __('shop::app.mail.order.shipping-handling') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($refund->shipping_amount, $refund->order_currency_code) }}
                    </span>
                </div>
            @endif

            @if ($refund->tax_amount > 0)
                @foreach (Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($refund, false) as $taxRate => $taxAmount)
                <div>
                    <span>{{ __('shop::app.mail.order.tax') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($refund->tax_amount, $refund->order_currency_code) }}
                    </span>
                </div>
                @endforeach
            @endif

            @if ($refund->discount_amount > 0)
                <div>
                    <span>{{ __('shop::app.mail.order.discount') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($refund->discount_amount, $refund->order_currency_code) }}
                    </span>
                </div>
            @endif

            @if ($refund->adjustment_refund > 0)
                <div>
                    <span>{{ __('shop::app.mail.refund.adjustment-refund') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($refund->adjustment_refund, $refund->order_currency_code) }}
                    </span>
                </div>
            @endif

            @if ($refund->adjustment_fee > 0)
                <div>
                    <span>{{ __('shop::app.mail.refund.adjustment-fee') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($refund->adjustment_fee, $refund->order_currency_code) }}
                    </span>
                </div>
            @endif

            <div style="font-weight: bold">
                <span>{{ __('shop::app.mail.order.grand-total') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($refund->grand_total, $refund->order_currency_code) }}
                </span>
            </div>
        </div>

        <div style="margin-top: 65px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block;width: 100%">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!!
                    __('shop::app.mail.order.help', [
                        'support_email' => '<a style="color:#0041FF" href="mailto:' . core()->getSenderEmailDetails()['email'] . '">' . core()->getSenderEmailDetails()['email']. '</a>'
                        ])
                !!}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.thanks') }}
            </p>
        </div>
    </div>
@endcomponent
