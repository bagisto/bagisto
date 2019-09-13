@component('shop::emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                @include ('shop::emails.layouts.logo')
            </a>
        </div>

        <div  style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
            Welcome to Bagisto - Email Subscription
        </div>

        <div>
            Thanks for putting me into your inbox. It’s been a while since you’ve read Bagisto
            email, and we don’t want to overwhelm your inbox. If you still do not want to receive
            the latest email marketing news then for sure click the button below.
        </div>

        <div  style="margin-top: 40px; text-align: center">
            <a href="{{ route('shop.unsubscribe', $data['token']) }}" style="font-size: 16px;
            color: #FFFFFF; text-align: center; background: #0031F0; padding: 10px 100px;text-decoration: none;">Unsubscribe</a>
        </div>
    </div>

@endcomponent