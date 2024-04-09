@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            {{ request()->message }}
        </p>
    </div>

    <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
        You can reply to this email to contact <a href="mailto:{{ request()->email }}">{{ request()->email }}</a>.
    </p>
@endcomponent