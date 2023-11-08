@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('shop::app.emails.customers.admin.new_customer_registration_notification.dear'), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('shop::app.emails.customers.admin.new_customer_registration_notification.description')
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @lang('shop::app.emails.customers.admin.new_customer_registration_notification.name', ['customerName' => $customer->name])
    </p>
    
@endcomponent