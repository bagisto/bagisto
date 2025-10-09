@component('admin::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-weight: bold;font-size: 20px;color: #121A26;line-height: 24px;margin-bottom: 24px">
            @lang('admin::app.account.emails.common.dear', ['admin_name' => $admin->name]),
        </p>

        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            @lang('admin::app.account.emails.backup-codes.greeting')
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        @lang('admin::app.account.emails.backup-codes.description')
    </p>

    <div style="margin-bottom: 40px;">
        <p style="font-weight: bold;font-size: 18px;color: #121A26;line-height: 24px;margin-bottom: 16px">
            @lang('admin::app.account.emails.backup-codes.codes-title')
        </p>

        <p style="font-size: 14px;color: #384860;line-height: 20px;margin-bottom: 20px">
            @lang('admin::app.account.emails.backup-codes.codes-subtitle')
        </p>

        <div style="display: grid;grid-template-columns: repeat(2, 1fr);gap: 12px;margin-bottom: 24px;">
            @foreach($admin->two_factor_backup_codes as $code)
                <div style="background: #F8F9FA;border: 2px solid #060C3B;border-radius: 4px;padding: 12px;text-align: center;font-family: monospace;font-size: 16px;font-weight: bold;color: #060C3B;">
                    {{ $code }}
                </div>
            @endforeach
        </div>
    </div>

    <div style="background: #FFF3CD;border: 1px solid #F59E0B;border-radius: 4px;padding: 20px;margin-bottom: 40px;">
        <p style="font-weight: bold;font-size: 16px;color: #92400E;line-height: 24px;margin-bottom: 8px;">
            @lang('admin::app.account.emails.backup-codes.warning-title')
        </p>
        
        <p style="font-size: 14px;color: #92400E;line-height: 20px;margin: 0;">
            @lang('admin::app.account.emails.backup-codes.warning-description')
        </p>
    </div>
@endcomponent