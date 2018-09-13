<div class="left-side">
    <div class="checkout-menu">

        <ul class="checkout-detail">

            <li>
                <div class="wrapper">
                    <div class="decorator" v-bind:class="{ active: isGuest }">
                        <img src="{{asset('themes/default/assets/images/address.svg')}}" />
                    </div>

                    <span>Information</span>

                </div>

            </li>

            <li>
                <div class="wrapper" >
                    <div class="decorator" v-bind:class="{ active: isShip }">
                        <img src="{{asset('themes/default/assets/images/shipping.svg')}}"/>
                    </div>

                    <span>Shipping</span>
                </div>
            </li>

            <li>
                <div class="wrapper">
                    <div class="decorator">
                        <img src="{{asset('themes/default/assets/images/payment.svg')}}" />
                    </div>

                    <span>Payment</span>
                </div>
            </li>

            <li>
                <div class="wrapper">
                    <div class="decorator">
                        <img src="{{asset('themes/default/assets/images/finish.svg')}}" />
                    </div>

                    <span>Complete</span>
                </div>
            </li>
        </ul>

        <div class="horizontal-rule">
        </div>
    </div>
</div>

