@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold; font-size: 20px; color: #121A26; line-height: 24px; margin-bottom: 24px;">
            @lang('admin::app.emails.dear', ['admin_name' => core()->getAdminEmailDetails()['name']]), 👋
        </p>
    </div>

    <div style="font-size: 20px; color: #242424; line-height: 30px; margin-bottom: 34px;">
        <div style="font-weight: bold; font-size: 20px; color: #242424; line-height: 30px; margin-bottom: 20px !important;">
            {{ __('admin::app.emails.customers.gdpr.status-update.summary') }}
        </div>
    </div>

    <div style="flex-direction: row; margin-top: 20px; justify-content: space-between; margin-bottom: 20px;">
        <div style="line-height: 25px; font-size: 16px; color: #242424;">
            <span style="font-weight: bold;">
                @lang('admin::app.emails.customers.gdpr.status-update.request-status')</span> {{ $gdprRequest->status }}
        </div>

        <div style="line-height: 25px; font-size: 16px; color: #242424;">
            <div>
                <span style="font-weight: bold;">
                    @lang('admin::app.emails.customers.gdpr.status-update.request-type')</span> {{ $gdprRequest->type }}
            </div>

            <div>
                <span style="font-weight: bold;">
                    @lang('admin::app.emails.customers.gdpr.status-update.message')</span> {{ $gdprRequest->message }}
            </div>
        </div>
    </div>
@endcomponent
