@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            <img src="{{ bagisto_asset('images/logo.svg') }}">
        </a>
    </div>

    <div style="padding: 30px;">
        {{ $data['content'] }}

        <div>
            You Can Unsubscribe From The List By Clicking Link Below.
        </div>
        <span>
            <a href="{{ route('shop.unsubscribe', $data['token']) }}" class="btn btn-success btn-md">{{ __('shop::app.subscription.unsubscribe') }}</a>
        </span>
    </div>
@endcomponent