@if (
    $velocityMetaData
    && $velocityMetaData->subscription_bar_content
    || core()->getConfigData('customer.settings.newsletter.subscription')
)
    <div class="newsletter-subscription">
        <div class="newsletter-wrapper row col-12">
            @if ($velocityMetaData && $velocityMetaData->subscription_bar_content)
                {!! $velocityMetaData->subscription_bar_content !!}
            @endif

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="subscribe-newsletter col-lg-6">
                    <div class="form-container">
                        <form action="{{ route('shop.subscribe') }}">
                            <div class="subscriber-form-div">
                                <div class="control-group">
                                    <input
                                        type="email"
                                        name="subscriber_email"
                                        class="control subscribe-field"
                                        placeholder="{{ __('velocity::app.customer.login-form.your-email-address') }}"
                                        aria-label="Newsletter"
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
