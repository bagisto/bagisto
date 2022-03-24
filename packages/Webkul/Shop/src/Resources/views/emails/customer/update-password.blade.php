@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>

    <div style="padding: 30px;">
        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('shop::app.mail.update-password.dear', ['name' => $user->name]) }},
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('shop::app.mail.update-password.info') }}
        </p>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('shop::app.mail.update-password.thanks') }}
        </p>
    </div>
@endcomponent