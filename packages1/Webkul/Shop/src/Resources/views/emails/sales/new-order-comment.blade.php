@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>

    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.comment.dear', ['customer_name' => $comment->order->customer_full_name]) }},
            </p>
        </div>

        <div style="line-height: 30px;margin-bottom: 20px !important;">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;font-style: italic;">
                {{ $comment->comment }}
            </p>
        </div>

        <div style="margin-top: 20px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block">
            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!!
                    __('shop::app.mail.order.comment.help', [
                        'support_email' => '<a style="color:#0041FF" href="mailto:' . config('mail.from.address') . '">' . config('mail.from.address'). '</a>'
                        ])
                !!}
            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.order.comment.thanks') }}
            </p>
        </div>
    </div>
@endcomponent
