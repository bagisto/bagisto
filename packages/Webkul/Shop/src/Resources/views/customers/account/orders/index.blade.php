
@extends('shop::layouts.master')

@section('content-wrapper')
<div class="account-content">
    <!-- @include('shop::customers.account.partials.sidemenu') -->
    <div class="order">
        <div class="order-section-head">
            <span class="order-number">Order #456103</span>
            <span class="order-status">PROCESSING</span>
            <span class="order-cancel"><a href="#">Cancel Order</a></span>
            <div class="horizontal-rule"></div>
        </div>

        <div class="order-section-head-small">
            
            <div class="horizon-rule">
            </div>
            <span class="icon">
                <img src="{{asset('themes/default/assets/images/icon-menu-back.svg')}}" />
            </span>

            <span class="order-number">
                ORDER #456103
            </span>

            <span class="cancel">
                Cancel
            </span>

            <div class="horizon-rule">
            </div>
        </div>

        <div class="payment-place">
            
            <div class="placed-on">
                <div class="place-text">
                    <span>Placed On</span>

                    <span class="processing">PROCESSING </span>
                </div>

                <div class="place-date">
                    <span>July 11, 2018 3:57AM</span>
                </div>
            </div>

            <div class="payment-status">
                <div class="payment-text">
                    <span>Payment Status</span>
                </div>

                <div class="status">
                    <span>Recieved</span>
                </div>
            </div>

            <div class="horizontal-rule"></div>
        </div>

        <div class="order-details">
            <span class="detail">Order Details</span>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Item Status</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Row total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PROD124</td>
                        <td>Apple iPhone 7- White-32GB</td>
                        <td>Packed (2)</td>
                        <td>$350.00</td>
                        <td>2</td>
                        <td>$700.00</td>
                    </tr>
                    <tr>
                        <td>PROD128</td>
                        <td>Blue Linen T-Shirt for Men- Small- Red</td>
                        <td>Shipped (2)</td>
                        <td>$45.00</td>
                        <td>2</td>
                        <td>$35.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="product-config">
            <div class="product-attribute">
                <div class="product-property">
                    <span>
                        SKU
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        PROD128
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                        Product Name
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        Apple iPhone 7 - White - 32GB
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                       Item Status
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        Packed (2)
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                       Price
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        $ 350.00
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                      Qty
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        2
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                       Row Total
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        $770.00
                    </span>
                </div>
            </div>
        </div>

         <div class="product-config">
            <div class="product-attribute">
                <div class="product-property">
                    <span>
                        SKU
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        PROD128
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                        Product Name
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        Apple iPhone 7 - White - 32GB
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                       Item Status
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        Packed (2)
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                       Price
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        $ 350.00
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                      Qty
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        2
                    </span>
                </div>
            </div>

            <div class="product-attribute">
                <div class="product-property">
                    <span>
                       Row Total
                    </span>
                </div>

                <div class="property-name">
                    <span>
                        $770.00
                    </span>
                </div>
            </div>
        </div>


        <div class="total">

            <div class="calculate">

                <div class="sub-total">
                    <span class="left">
                        Subtotal
                    </span>
                    <span class="middle">
                        -
                    </span>
                    <span class="right">
                        $805.00
                    </span>
                </div>

                <div class="ship-handle">
                    <span class="left">
                        Shipping & handling
                    </span>
                    <span class="middle">
                        -
                    </span>
                    <span class="right">
                        $5.00
                    </span>
                </div>

                <div class="discount">
                    <span class="left">
                        Discounts
                    </span>
                    <span class="middle">
                        -
                    </span>
                    <span class="right">
                        $15.00
                    </span>
                </div>

                <div class="grand-total">
                    <span class="left">
                        Grand Total
                    </span>
                    <span class="middle">
                        -
                    </span>
                    <span class="right">
                        $15.00
                    </span>
                </div>

                <div class="due">
                    <span class="left">
                        Total Due
                    </span>
                    <span class="middle">
                        -
                    </span>
                    <span class="right">
                        $15.00
                    </span>
                </div>

            </div>

        </div>

        <div class="horizontal-rule"></div> 
        
        
        <div class="order-information">
            <div class="order-address-method">
                <span class="address-method">
                    Shipping Address
                </span>
                <p>0933 crossing suite 128 Dallas,Texas United States 75001</p>
            </div>
            <div class="order-address-method">
                <span class="address-method">
                    Billing Address
                </span>
                <p>0933 crossing suite 128 Dallas,Texas United States 75001</p>
            </div>
            <div class="order-address-method">
                <span class="address-method">
                    Shipping Method
                </span>
                <p>Flat Rate- Fixed</p>
            </div>
            <div class="order-address-method">
                <span class="address-method">
                   Payment Method
                </span>
                <p>Bank Wire Transfer</p>
            </div>
        </div>
    </div>
</div>
@endsection

