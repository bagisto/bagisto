@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            @if (core()->getConfigData('general.design.admin_logo.logo_image'))
                <img src="{{ \Illuminate\Support\Facades\Storage::url(core()->getConfigData('general.design.admin_logo.logo_image')) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
            @else
                <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="{{ config('app.name') }}"/>
            @endif
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