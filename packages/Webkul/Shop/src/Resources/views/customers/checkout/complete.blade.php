
@extends('shop::layouts.master')

@section('content-wrapper')


<div class="checkout-process">

    <div class="left-side">
        <div class="checkout-menu">

            <ul class="checkout-detail">

                <li>
                    <div class="wrapper">
                        <div class="decorator active">
                            <img src="{{asset('themes/default/assets/images/completed.svg')}}" />
                        </div>
                        
                        <span>Information</span>
                    </div>
                    
                </li>
                
                <li>
                    <div class="wrapper">
                        <div class="decorator">
                            <img src="{{asset('themes/default/assets/images/completed.svg')}}" />
                        </div>
                        
                        <span>Shipping</span>
                    </div>
                </li>
                
                <li>
                    <div class="wrapper">
                        <div class="decorator">
                            <img src="{{asset('themes/default/assets/images/completed.svg')}}" />
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


</div>

<div class="complete-page">
    <div class="order-summary">
        <span>Summary of Order<span>
    </div>

    <div class="address">

        <div class="shipping-address">
            <div class="shipping-title">
                <span>Shipping address</span>
            </div>

            <div class="shipping-content">
                <div class="name">
                    <span>John Doe </span>
                </div>
                <div class="addr">
                    <span>
                        25 , Washington USA 5751434
                    </span>
                </div>
                <div class="horizontal-rule">
                </div>
                <div class="contact">
                    <span>Contact : 9876543210 </span>
                </div>
            </div>
        </div>

        <div class="billing-address">
            <div class="shipping-title">
                <span>Billing address</span>
            </div>

            <div class="shipping-content">
                <div class="name">
                    <span>John Doe </span>
                </div>
                <div class="addr">
                    <span>
                        25 , Washington USA 5751434
                    </span>
                </div>
                <div class="horizontal-rule">
                </div>
                <div class="contact">
                    <span>Contact : 9876543210 </span>
                </div>
            </div>
        </div>

    </div>

    <div class="product-detail">
        <div class="product-image">
            <img src="{{asset('themes/default/assets/images/1.png')}}" />
        </div>

        <div class="product-desc">
            <div class="product-title">
                <span>
                    Rainbow creation Embroidered
                </span>
            </div>
            <div class="price">
                <span>
                    <label>Price </label>
                    <label class="bold"> $40.00 </label>
                <span>
            </div>
            <div class="quantity">
                <span>
                    <label>Quantity</label>
                    <label class="quat-bold"> 1 </label>
                <span>
            </div>
            <div class="pro-attribute">
                <span>
                    Color : Grey, Size : S,Sleeve Type : Puffed Sleeves,Occasion : Birthday ,Marriage Anniversary
                </span>
            </div>
        </div>
    </div>

    <div class="product-detail">
        <div class="product-image">
            <img src="{{asset('themes/default/assets/images/1.png')}}" />
        </div>

        <div class="product-desc">
            <div class="product-title">
                <span>
                    Rainbow creation Embroidered
                </span>
            </div>
            <div class="price">
                <span>
                    <label>Price </label>
                    <label class="bold"> $40.00 </label>
               </span>
            </div>
            <div class="quantity">
                <span>
                    <label>Quantity</label>
                    <label class="quat-bold"> 1 </label>
                </span>
            </div>
            <div class="pro-attribute">
                <span>
                    Color : Grey, Size : S,Sleeve Type : Puffed Sleeves,Occasion : Birthday ,Marriage Anniversary
                </span>
            </div>
        </div>
    </div>

    <div class="order-description">

        <div class="payment">
            
            <div class="shipping">
                <div class="pay-icon">
                    <img src="{{asset('themes/default/assets/images/shipping.svg')}}" />
                </div>
                <div class="shipping-text">
                    <div class="price">
                        <span>
                            $ 25.00
                        </span>
                    </div>

                    <div class="fedex-shipping">
                        <span>
                            FedEx Shipping
                        </span>
                    </div>
                </div>
            </div>

            <div class="net-banking">
                <div class="pay-icon">
                    <img src="{{asset('themes/default/assets/images/payment.svg')}}" />
                </div>
                <span>Net banking </span>
            </div>

        </div>

        <div class="product-bill">

            <div class="sub-total">
                <span>
                    Subtotal
                </span>
                <span class="right">
                    $ 2,506.00
                </span>
            </div>

            <div class="charge-discount">
                <span>
                   Delivery Charges
                </span>
                <span class="right">
                    $ 40.00
                </span>
            </div>

            <div class="charge-discount">
                <span>
                   Coupan discount
                </span>
                <span class="right">
                    $ 25.00
                </span>
            </div>

            <div class="horizontal-rule">
            </div>

            <div class="amount-pay">
                <span>
                   Amount payable
                </span>
                <span class="right">
                    $ 2,571.00
                </span>
            </div>

        </div>

    </div>

    <div class="horizontal-rule">
    </div>

    <div class="palce-order-button">
        <button>PLACE ORDER </button>
    </div>

</div>

@endsection