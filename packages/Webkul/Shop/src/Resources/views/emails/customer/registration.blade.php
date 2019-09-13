@component('shop::emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                <img src="{{ bagisto_asset('images/logo.svg') }}">
            </a>
        </div>

        <div>
            Hi {{ $data['first_name'] }}, <br><br>

            Welcome and thank you for registering at Bagisto!<br><br>

            Your account has now been created successfully and you can login using your email address and password credentials.<br>

            Upon logging in, you will be able to access other services including reviewing past orders, wishlists and editing your account information.<br><br>

            Thanks,<br>
            Bagisto

        </div>

        <div  style="margin-top: 40px; text-align: center">

        </div>
    </div>

@endcomponent