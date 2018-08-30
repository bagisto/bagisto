@extends('shop::layouts.master')

@section('content-wrapper')

<div class="checkout-process">

    <div class="left-side">
        <div class="checkout-menu">

            <ul class="checkout-detail">

                <li>
                    <div class="wrapper">
                        <div class="decorator active">
                            <img src="{{asset('themes/default/assets/images/address.svg')}}" />
                        </div>
                        
                        <span>Information</span>
                    </div>
                    
                </li>
                <div class="line"> </div>
                
                <li>
                    <div class="wrapper">
                        <div class="decorator">
                            <img src="{{asset('themes/default/assets/images/shipping.svg')}}" />
                        </div>
                        
                        <span>Shipping</span>
                    </div>
                </li>

                <div class="line"></div>
                
                <li>
                    <div class="wrapper">
                        <div class="decorator">
                            <img src="{{asset('themes/default/assets/images/payment.svg')}}" />
                        </div>
                        
                        <span>Payment</span>
                    </div>
                </li>

                <div class="line"></div>
                
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

    <div class="right-side">
        <div class="purchase-detail">
            <div class="price">
                <span>Price Detail</span>
            </div> 
            
            <div class="item-detail">
                <span>
                    <label>2 Items Price</label>
                    <label class="right">$ 2,506.00</label>
                </span>    
            </div>

            <div class="item-detail">
                <span>
                    <label>Delivery Charges</label>
                    <label class="right">$ 40.00</label>
                </span>    
            </div>
            <div class="item-detail">
                <span>
                    <label>Coupan Discount</label>
                    <label class="right">$ 25.00</label>
                </span>    
            </div>

            <div class="horizontal-rule">
            </div>

            <div class="payble-amount">
                <span>
                    <label>Amount Payble</label>
                    <label class="right">$ 2571.00</label>
                </span>    
            </div>
        </div>
    </div>
</div>

<div class="order-info">
    <div class="order-guest">
        <span class="order-text">
            Order as Guest
        </span>
        <button class="sign-in">
            SIGN IN
        </button>
    </div>

    <div class="control-group">
        <label for="first_name">Email address <span>*</span></label>
        <input type="text" class="control" name="email_address">
    </div>

    <div class="control-group">
        <label for="first_name">First Name <span>*</span> </label>
        <input type="text" class="control" name="first_name">
    </div>

    <div class="control-group">
        <label for="first_name">Last Name <span>*</span> </label>
        <input type="text" class="control" name="last_name">
    </div>

    <div class="control-group">
        <label for="first_name">Company Name <span>*</span> </label>
        <input type="text" class="control" name="company_name">
    </div>

    <div class="control-group">
        <label for="first_name">Street address <span>*</span> </label>
        <input type="text" class="control" name="street_address">
    </div>

    <div class="control-group">
        <label for="first_name">City <span>*</span> </label>
        <input type="text" class="control" name="city">
    </div>

    <div class="control-group">
        <label for="first_name">Country <span>*</span> </label>
        <input type="text" class="control" name="country">
    </div>

    <div class="control-group">
        <label for="first_name">Provinces <span>*</span> </label>
        <input type="text" class="control" name="provinces">
    </div>

    <div class="control-group">
        <label for="first_name">Zip code <span>*</span> </label>
        <input type="text" class="control" name="zip_code">
    </div>

    <div class="control-group">
        <label for="first_name">Phone number <span>*</span> </label>
        <input type="text" class="control" name="phone_number">
    </div>

    <div class="different-billing-addr">
        <span>qwdevf</span>
        <span>Use different address for billing?</span>
    </div>

    <div class="horizontal-rule">
    </div>

    <div class="countinue-button">
        <button>CONTINUE</button>
    </div>

</div>



@endsection
