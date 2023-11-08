@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('admin::app.emails.dear', ['admin_name' => core()->getAdminEmailDetails()['name']]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            {!! __('admin::app.emails.customers.registration.greeting', [
                'customer_name' => '<a href="' . route('admin.customers.customers.view', $customer->id) . '" style="color: #2969FF;">'.$customer->name. '</a>'
                ])
            !!}
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @lang('admin::app.emails.customers.registration.description')
    </p>
@endcomponent