@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $fullName]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.emails.customers.subscribed.greeting')
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @lang('shop::app.emails.customers.subscribed.description')
    </p>

    <div style="display: flex;margin-bottom: 95px">
        <a
            href="{{ route('shop.subscription.destroy', $customer->token) }}"
            style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;"
        >
            @lang('shop::app.emails.customers.subscribed.unsubscribe')
        </a>
    </div>
@endcomponent