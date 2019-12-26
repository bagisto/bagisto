<div class="newsletter-subscription">
    <div class="newsletter-wrapper">

    {!! DbView::make(core()->getCurrentChannel())->field('subscription_bar_content')->render() !!}

        <div class="subscribe-newsletter">
            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="form-container">

                    <form action="{{ route('shop.subscribe') }}">
                        <div class="subscriber-form-div">
                            <div
                                class="control-group"
                                :class="[errors.has('subscriber_email') ? 'has-error' : '']"
                            >
                                <input
                                    type="email"
                                    class="control subscribe-field"
                                    name="subscriber_email"
                                    placeholder="Your email address"
                                    required
                                />

                                <button class="theme-btn subscribe-btn fw6">
                                    {{ __('shop::app.subscription.subscribe') }}
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

    </div>
</div>