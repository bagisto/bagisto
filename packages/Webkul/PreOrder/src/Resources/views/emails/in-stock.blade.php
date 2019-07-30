@component('shop::emails.layouts.master')

    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            <img src="{{ bagisto_asset('images/logo.svg') }}">
        </a>
    </div>

    <div style="padding: 30px;">

        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('preorder::app.mail.in-stock.dear', ['name' => $item->order->customer_full_name]) }},
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">

                {!! 
                    __('preorder::app.mail.in-stock.info', [
                        'name' => '<a style="color:#0041FF" href="' . route('shop.products.index', ['slug' => $item->order_item->product->url_key]) . '">' . $item->order_item->product->name . '</a>',
                        'link' => $item->order->is_guest ? route('preorder.shop.preorder.complete', ['token' => $item->token]) : route('customer.orders.view', ['id' => $item->order_id])
                    ]) 
                !!}

            </p>
        </div>

        <div style="font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! 
                    __('shop::app.mail.order.help', [
                        'support_email' => '<a style="color:#0041FF" href="mailto:' . config('mail.from.address') . '">' . config('mail.from.address'). '</a>'
                        ]) 
                !!}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.thanks') }}
            </p>
        </div>

    </div>

@endcomponent