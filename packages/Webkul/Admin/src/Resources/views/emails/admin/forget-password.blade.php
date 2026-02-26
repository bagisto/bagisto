@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('admin::app.emails.dear', ['admin_name' => $userName]), 👋
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('admin::app.emails.admin.forgot-password.greeting')
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @lang('admin::app.emails.admin.forgot-password.description')
    </p>

    <div style="display: flex;margin-bottom: 95px">
        <a
            href="{{ route('admin.reset_password.create', $token) }}"
            style="padding: 16px 45px;justify-content: center;align-items: center;gap: 10px;border-radius: 2px;background: #060C3B;color: #FFFFFF;text-decoration: none;text-transform: uppercase;font-weight: 700;"
        >
            @lang('admin::app.emails.admin.forgot-password.reset-password')
        </a>
    </div>
@endcomponent