@component('shop::emails.layout')
    <div style="margin-bottom: 34px;">
        <p style="font-size: 16px;color: #384860;line-height: 24px;">
            {{ request()->message }}
        </p>
    </div>

        <p style="font-size: 16px;color: #384860;line-height: 24px;margin-bottom: 40px">
            @lang('shop::app.emails.contact.to')
            
            <a href="mailto:{{ request()->email }}">{{ request()->email }}</a>,

            @lang('shop::app.emails.contact.reply-to-mail')

            @if(request()->contact)
                @lang('shop::app.emails.contact.reach-via-phone')

                <a href="tel:{{ request()->contact }}">{{ request()->contact }}</a>.
            @endif
        </p>
    </p>
@endcomponent