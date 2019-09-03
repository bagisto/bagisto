@if($payment['method'] == "stripe")
    @include('stripe::components.add-card')
    <div class="stripe-block-modal close" id="stripe-cards">
        <div class="stripe-title">
            <img class="stripe-logo" src="{{ asset('vendor/webkul/stripe/assets/images/icons/stripe-logo.png') }}" style="height: 70px;"/>

            <span id="close-stripe-modal">
                <i class="icon stripe-close-icon"></i>
            </span>
        </div>

        <div class="horizontal-rule"></div>

        @include('stripe::components.stripe-form')

        <div class="cp-spinner cp-round" id="loader">
        </div>
    </div>
@endif