
@extends('admin::layouts.master')

@section('content-wrapper')
    

    <div class="order-place-detail">
        
        <div class="order-account-information">
            <accordian :title="'Order and Account'" :active="true">
                <div slot="body">

                    <div class="order-information">
                        <div class="order-info">
                            <span>Order Information <span >
                            <span class="edit">
                               Edit
                            </span>
                        </div>
                        <div class="horizotal-rule"> </div>

                        <div class="order-account">
                            <div class="left-content"> 
                                <span> 
                                    Order Date 
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    August 4,2018,9:05:36:AM 
                                </span>
                            </div>
                        </div>

                         <div class="order-account">
                            <div class="left-content"> 
                                <span> 
                                    Order Status 
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    Pending
                                </span>
                            </div>
                        </div>

                         <div class="order-account">
                            <div class="left-content"> 
                                <span> 
                                    Channel
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    Web Store-en_GB
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="account-information">
                        <div class="account-info">
                            <span>Account Information <span >
                            <span class="edit">
                               Edit
                            </span>
                        </div>
                        <div class="horizotal-rule"> </div>

                        <div class="order-account">
                            <div class="left-content"> 
                                <span > 
                                    Customer Name 
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span class="name"> 
                                    Lee Stoike 
                                </span>
                            </div>
                        </div>

                         <div class="order-account">
                            <div class="left-content"> 
                                <span> 
                                    Email
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    lee.stoike@examplemail.com
                                </span>
                            </div>
                        </div>

                         <div class="order-account">
                            <div class="left-content"> 
                                <span> 
                                    Contact Number 
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    9876543210
                                </span>
                            </div>
                        </div>

                    </div>

                </div>
            </accordian>
        </div>

        <div class="address">

            <accordian :title="'Address'" :active="true">

            <div slot="body">

                <div class="address-information">
                    <div class="address-name">
                        <span> Shipping Address <span >
                        <span class="edit">
                        Edit
                        </span>
                    </div>
                    <div class="horizotal-rule"> </div>
                    <div class="address-detail">
                        <span> 0933 Crossing Suite 12B </span>
                        <span> Dallas , Texas <span>
                        <span> United States </span>
                        <span> 75001 </span>
                    </div>
                </div>

                <div class="address-information">
                    <div class="address-name">
                        <span> Billing Address <span >
                        <span class="edit">
                        Edit
                        </span>
                    </div>
                    <div class="horizotal-rule"> </div>
                    <div class="address-detail">
                        <span> 0933 Crossing Suite 12B </span>
                        <span> Dallas , Texas <span>
                        <span> United States </span>
                        <span> 75001 </span>
                    </div>
                </div>
            
            </div>

            </accordian>

        </div>

        <div class="payment-shipping">
            <accordian :title="'Payment and Shipping'" :active="true">
                <div slot="body">

                    <div class="payment-information">
                        <div class="title">
                            <span>Payment Information <span >
                        </div>
                        <div class="horizotal-rule"> </div>

                        <div class="payment-info">
                            <div class="left-content"> 
                                <span> 
                                    Payment Method 
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    Bank Wire Transfer
                                </span>
                            </div>
                        </div>

                        <div class="payment-info">
                            <div class="left-content"> 
                                <span> 
                                    Currency
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    US Dollar
                                </span>
                            </div>
                        </div>

                    </div>

                     <div class="payment-information">
                        <div class="title">
                            <span> Shipping Information <span >
                        </div>
                        <div class="horizotal-rule"> </div>

                        <div class="payment-info">
                            <div class="left-content"> 
                                <span> 
                                    Shipping Method 
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    Flat Rate-Fixed-$10.00
                                </span>
                            </div>
                        </div>

                        <div class="payment-info">
                            <div class="left-content"> 
                                <span> 
                                    Expected Delivery
                                </span>
                            </div>
                            <div class="right-content"> 
                                <span> 
                                    3 Days
                                </span>
                            </div>
                        </div>

                    </div>

                
                </div>
            </accordian>
        </div>

        <div class="order-products">

            <accordian :title="'Products Ordered'" :active="true">

                <div slot="body">

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

                </div>
            </accordian>

        </div>
    <div>                    
                
@stop



