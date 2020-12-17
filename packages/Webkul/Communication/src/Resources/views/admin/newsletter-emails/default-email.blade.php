@component('communication::admin.newsletter-emails.layouts.master')

    <div>
        <div style="text-align: center;">
            <a href="{{ config('app.url') }}">
                @include ('communication::admin.newsletter-emails.layouts.logo')
            </a>
        </div>

        <div>
            {!! $newsletter->content !!}
        </div>

        <div  style="margin-top: 40px; text-align: center">
            <a href="{{ route('shop.unsubscribe', $subscriber->token) }}" style="font-size: 16px;
            color: #FFFFFF; text-align: center; background: #0031F0; padding: 10px 100px;text-decoration: none;">
                {!! __('shop::app.mail.customer.subscription.unsubscribe') !!}
            </a>
        </div>
    </div>

@endcomponent