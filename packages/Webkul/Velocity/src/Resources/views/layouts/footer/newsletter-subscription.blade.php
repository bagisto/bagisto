@if (
    $velocityMetaData->subscription_bar_content
    || core()->getConfigData('customer.settings.newsletter.subscription')
)
    <div class="newsletter-subscription">
        <div class="newsletter-wrapper row">
            @if ($velocityMetaData->subscription_bar_content)
                {!! $velocityMetaData->subscription_bar_content !!}
            @endif

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="subscribe-newsletter col-lg-6 text-right">
                    <div class="form-container">
                        <form action="{{ route('shop.subscribe') }}">
                            <div class="subscriber-form-div">
                                <div :class="`control-group ${errors.has('subscriber_email') ? 'has-error' : ''}`">
                                    <input
                                        type="email"
                                        name="subscriber_email"
                                        class="control subscribe-field"
                                        placeholder="{{ __('velocity::app.customer.login-form.your-email-address') }}"
                                        required />

                                    <button class="theme-btn subscribe-btn fw6">
                                        {{ __('shop::app.subscription.subscribe') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
