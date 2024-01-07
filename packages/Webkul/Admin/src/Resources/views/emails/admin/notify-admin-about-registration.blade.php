@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('admin::app.emails.dear',['admin_name'=>$adminName]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('admin::app.emails.admin.registration.description')
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @lang('admin::app.emails.admin.registration.name', ['customerName' => $customer->name])
    </p>
@endcomponent