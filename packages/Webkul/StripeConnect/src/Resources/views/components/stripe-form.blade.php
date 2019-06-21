<div class="stripe-form-content">
    <form action="{{ route('stripe.get.token') }}" method="post" id="stripe-payment-form">
        <div class="stripe-fields" style="display: flex; flex-direction: column; justify-content: center; align-items: center; font-family: 'Montserrat', sans-serif; padding-left: 30px; padding-right: 30px;">
            <div id="card-number" class="control-group" style="background: #FFFFFF; border: 1px solid #979797; border-radius: 5px; min-width: 360px; height: 43px; border-radius: 3px; font-size: 16px; padding-left: 15px; padding-top: 12px;"></div>
            <!-- Used to display card number date error -->
            <div class="stripe-errors" id="card-number-error" role="alert"></div>

            <div class="stripe-field-combinator" style="display: flex; width: 100%; flex-direction: row; justify-content: space-between; align-items: center;">
                <div id="card-expiry" class="control-group" style="background: #FFFFFF; border: 1px solid #979797; border-radius: 5px; width: 65%; height: 43px; border-radius: 3px; font-size: 16px; padding-left: 15px; padding-top: 12px;"></div>

                <div id="card-cvc" class="control-group" style="background: #FFFFFF; border: 1px solid #979797; border-radius: 5px; width: 30%; height: 43px; border-radius: 3px; font-size: 16px; padding-left: 15px; padding-top: 12px;"></div>
            </div>

            <!-- Used to display card expiry date error -->
            <div class="stripe-errors" id="card-expiration-error" role="alert"></div>

            {{-- @if(auth()->guard('customer')->check())
                <div class="control-group">
                    <span class="checkbox">
                        <input type="checkbox" id="remember-card" name="remember-card">
                        <label class="checkbox-view" for="remember-card"></label>
                        {{ __('stripe::app.remember-card') }}
                    </span>
                </div>
            @endif --}}

            <button class="btn btn-primary btn-lg" id="stripe-pay-button" style="border-radius: 3px !important;">Pay Now
                <label style="">
                    ( {{ core()->currency(\Cart::getCart()->base_grand_total) }} )
                </label>
            </button>
        </div>
    </form>
    <div class="horizontal-rule mt-15"></div>

    <div class="more-payment-icons">
        <span id="visa-icon">
            <i class="icon visa-icon"></i>
        </span>

        <span id="master-icon">
            <i class="icon master-icon"></i>
        </span>

        <span id="mastro-icon">
            <i class="icon mastro-icon"></i>
        </span>

        <span id="pci-icon">
            <i class="icon pci-icon"></i>
        </span>
    </div>
</div>