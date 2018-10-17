@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.dashboard.title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <h1>Dashboard</h1>
        
        <div class="dashboard-content">
            
            <div class="dashboard-stats">

                <div class="dashboard-card">
                    <div class="visitor-content">
                        <div class="title">
                            <span>NEW VISITORS </span>
                        </div>
                        <div class="data">
                            <span>450 </span>
                            <span>
                                <img src="{{asset('themes/default/assets/images/complete.svg')}}" />
                            </span>
                            <span class="right">
                                12.5% Increased 
                            </span>
                        </div>
                    </div>
                </div> 
                
                <div class="dashboard-card">
                    <div class="visitor-content">
                        <div class="title">
                            <span>NEW VISITORS </span>
                        </div>
                        <div class="data">
                            <span>450 </span>
                            <span>
                                <img src="{{asset('themes/default/assets/images/complete.svg')}}" />
                            </span>
                            <span class="right">
                                12.5% Increased 
                            </span>
                        </div>
                    </div>
                </div>  

                <div class="dashboard-card">
                    <div class="visitor-content">
                        <div class="title">
                            <span>NEW VISITORS </span>
                        </div>
                        <div class="data">
                            <span>450 </span>
                            <span>
                                <img src="{{asset('themes/default/assets/images/complete.svg')}}" />
                            </span>
                            <span class="right">
                                12.5% Increased 
                            </span>
                        </div>
                    </div>
                </div>  

                <div class="dashboard-card">
                    <div class="visitor-content">
                        <div class="title">
                            <span>NEW VISITORS </span>
                        </div>
                        <div class="data">
                            <span>450 </span>
                            <span>
                                <img src="{{asset('themes/default/assets/images/complete.svg')}}" />
                            </span>
                            <span class="right">
                                12.5% Increased 
                            </span>
                        </div>
                    </div>
                </div>  




            
            </div>

            <div class="graph-category">
                <div class="dashboard-graph">
                </div>

                <div class="performing-category">
                    <div class="category">
                        <div class="title">
                            <span> TOP PERFORMING CATEGORIES </span>
                        </div>

                        <div class="category-info">
                           
                            <ul>

                                <li> 
                                    <div class="category-list">
                                        <div class="cat-name">
                                            <span> Men </span>
                                            <span class="icon angle-right-icon right-side"></span>
                                        </div>
                                        <div class="product-info">
                                            <span>250 Products . 290 Sales </span>
                                        </div>
                                        <div class="horizon-rule">
                                        </div>
                                    </div>
                                </li>

                                <li> 
                                    <div class="category-list">
                                        <div class="cat-name">
                                            <span> Women </span>
                                            <span class="icon angle-right-icon right-side"></span>
                                        </div>
                                        <div class="product-info">
                                            <span>375 Products . 350 Sales </span>
                                        </div>
                                        <div class="horizon-rule">
                                        </div>
                                    </div>
                                </li>

                                <li> 
                                    <div class="category-list">
                                        <div class="cat-name">
                                            <span> Electronics </span>
                                            <span class="icon angle-right-icon right-side"></span>
                                        </div>
                                        <div class="product-info">
                                            <span>200 Products . 214 Sales </span>
                                        </div>
                                        <div class="horizon-rule">
                                        </div>
                                    </div>
                                </li>

                                <li> 
                                    <div class="category-list">
                                        <div class="cat-name">
                                            <span> Accessories </span>
                                            <span class="icon angle-right-icon right-side"></span>
                                        </div>
                                        <div class="product-info">
                                            <span>180 Products . 180 Sales </span>
                                        </div>
                                        <div class="horizon-rule">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale-stock">
                <div class="sale">
                    <div class="top-sale">
                        <div class="title">
                            <span> TOP  SELLING PRODUCTS </span>
                        </div>

                        <div class="sale-info">
                            <ul>
                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Men's Olive Denim Jacket </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>250 Sales . In Stock - 500 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Apple iPhone 8 Plus - 64GB </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>250 Sales . In Stock - 500 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Long Lenngth Printed Shrug </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>250 Products . In Stock - 500 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Black Round Neck T-Shirt for Men </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>250 Products . In Stock - 500 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Men's Linnen Shirt -Regular Fit </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>250 Products . In Stock - 500 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="sale">
                    <div class="top-sale">
                        <div class="title">
                            <span> CUSTOMERS WITH MOST SALES </span>
                        </div>

                        <div class="sale-info">
                            <ul>
                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Emma Wagner </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span> 24 Orders . Revenue $450.00 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                 <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Emma Wagner </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span> 24 Orders . Revenue $450.00 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                 <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Emma Wagner </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span> 24 Orders . Revenue $450.00 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                 <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Emma Wagner </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span> 24 Orders . Revenue $450.00 </span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                 <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Emma Wagner </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span> 24 Orders . Revenue $450.00 </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="sale">
                    <div class="top-sale">
                        <div class="title">
                            <span> TOP  SELLING PRODUCTS </span>
                        </div>

                        <div class="sale-info">
                            <ul>
                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Men's Olive Denim Jacket </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>7 left </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Apple iPhone 8 Plus - 64GB </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                                
                                            </div>
                                            <div class="product-info">
                                                <span>250 Sales . In Stock - 500 </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Long Lenngth Printed Shrug </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>250 Products . In Stock - 500 </span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Black Round Neck T-Shirt for Men </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                                
                                            </div>
                                            <div class="product-info">
                                                <span>250 Products . In Stock - 500 </span>
                                            </div>
                                        </div>
                                    
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>

                                <li> 
                                    <div class="pro-attribute">
                                        <div class="pro-img">
                                            <!-- <span class="icon settings-icon"></span> -->
                                        </div>
                                        <div class="product-description">
                                            <div class="product-name">
                                                <span> Men's Linnen Shirt -Regular Fit </span>
                                                <span class="icon angle-right-icon right-side"></span>
                                            </div>
                                            <div class="product-info">
                                                <span>250 Products . In Stock - 500 </span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="horizontal-rule">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

              

            </div>
        </div>
    </div>
 
              

@stop
