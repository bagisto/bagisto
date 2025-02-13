@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $adminUpdateRequest['customer_name']]), 👋
        </p>
    </div>

    <div style="padding: 30px;">
        <div style="flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 20px;">
            <span style="font-weight: bold;font-size: 16px;color: #242424">
                @lang('shop::app.emails.customers.gdpr.update-request.request-status')
            </span>
            
            {{ $adminUpdateRequest['status'] }}
        </spam>

        <div style="font-weight: bold;font-size: 16px;color: #242424;">
            <span style="font-weight: bold;">
                @lang('shop::app.emails.customers.gdpr.update-request.message')
            </span>

            {{ $adminUpdateRequest['message'] }}
        </div>
    </div>
@endcomponent