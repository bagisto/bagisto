@component('shop::emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                <img src="{{ bagisto_asset('images/logo.svg') }}">
            </a>
        </div>

        <div  style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
            Welcome to Bagisto,
        </div>

        <div>
            Hi {{ $data['first_name'] }},

            Welcome and thank you for registering at Bagisto!

            Your account has now been created successfully and you can login using your email address and password credentials.

            Upon logging in, you will be able to access other services including reviewing past orders, printing invoices and editing your account information.

            Thanks,
            Bagisto

        </div>

        <div  style="margin-top: 40px; text-align: center">

        </div>
    </div>

@endcomponent