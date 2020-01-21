@component('shop::emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                @include ('shop::emails.layouts.logo')
            </a>
        </div>

        <div  style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
            {{ __('shop::app.mail.customer.new.dear', ['customer_name' => $customer['name']]) }},

        </div>

        <div>
            {!! __('shop::app.mail.customer.new.summary') !!}

        </div>

        <div>
            <b> {!! __('shop::app.mail.customer.new.username-email') !!} </b> - {{ $customer['email'] }} <br>
            <b> {!! __('shop::app.mail.customer.new.password') !!} </b> - {{ $password}}
        </div>

        <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
            {{ __('shop::app.mail.customer.new.thanks') }}
        </p>
    </div>

@endcomponent