@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $comment->order->customer_full_name]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.emails.orders.commented.title', [
                'order_id'   => '<a href="' . route('shop.customers.account.orders.view', $comment->order_id) . '" style="color: #2969FF;">#' . $comment->order->increment_id . '</a>',
                'created_at' => core()->formatDate($comment->order->created_at, 'Y-m-d H:i:s')
            ])
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        {{ $comment->comment }}
    </p>
@endcomponent