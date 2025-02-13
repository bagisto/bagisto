@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $dataDeleteRequest['customer_name']]), 👋
        </p>
    </div>

    <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            @lang('shop::app.emails.customers.gdpr.delete-request.summary')
        </div>
    </div>

    <div style="flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 20px;">
        <div style="line-height: 25px;">
            <div style="font-size: 16px;color: #242424">
                <span style="font-weight: bold;">@lang('shop::app.emails.customers.gdpr.delete-request.request-status')</span> {{ $dataDeleteRequest['status'] }}
            </div>
        </div>

        <div style="line-height: 25px;">
            <div style="font-size: 16px;color: #242424;">
                <span style="font-weight: bold;">@lang('shop::app.emails.customers.gdpr.delete-request.request-type')</span> {{ $dataDeleteRequest['type'] }}
            </div>

            <div style="font-size: 16px;color: #242424;">
                <span style="font-weight: bold;">@lang('shop::app.emails.customers.gdpr.delete-request.message')</span> {{ $dataDeleteRequest['message'] }}
            </div>
        </div>
    </div>
@endcomponent