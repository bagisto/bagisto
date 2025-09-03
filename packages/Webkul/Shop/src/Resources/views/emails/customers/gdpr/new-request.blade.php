@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.dear', ['customer_name' => $gdprRequest->customer->name]), 👋
        </p>
    </div>

    <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            {{ $gdprRequest->type == 'update' ? __('shop::app.emails.customers.gdpr.new-request.update-summary') : __('shop::app.emails.customers.gdpr.new-request.delete-summary') }}
        </div>
    </div>

    <div style="flex-direction: row;margin-top: 20px;justify-content: space-between;margin-bottom: 20px;">
        <div style="line-height: 25px;font-size: 16px;color: #242424">
            <span style="font-weight: bold;">
                @lang('shop::app.emails.customers.gdpr.new-request.request-status')</span> {{ $gdprRequest->status }}
        </div>

        <div style="line-height: 25px; font-size: 16px;color: #242424;">
            <div>
                <span style="font-weight: bold;">
                    @lang('shop::app.emails.customers.gdpr.new-request.request-type')</span> {{ $gdprRequest->type }}
            </div>

            <div>
                <span style="font-weight: bold">
                    @lang('shop::app.emails.customers.gdpr.new-request.message')</span> {{ $gdprRequest->message }}
            </div>
        </div>
    </div>
@endcomponent