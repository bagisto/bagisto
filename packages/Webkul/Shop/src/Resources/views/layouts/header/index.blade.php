<div class="header" id="header">
    <div class="header-top">
        <div class="left-content">

            <ul class="logo-container">
                <li>
                    <a href="{{ route('shop.home.index') }}">
                        <img class="logo" src="{{ asset('vendor/webkul/shop/assets/images/logo.svg') }}" />
                    </a>
                </li>
            </ul>

            <ul class="search-container">
                <li class="search-group">
                    <input type="search" class="search-field" placeholder="Search for products">
                    <div class="search-icon-wrapper">
                        <span class="icon search-icon"></span>
                    </div>
                </li>
            </ul>

        </div>

        <div class="right-content">

            <ul class="account-dropdown-container">

                <li class="account-dropdown">

                    <span class="icon account-icon"></span>

                    <div class="dropdown-toggle">

                        <div style="display: inline-block; cursor: pointer;">
                            <span class="name">Account</span>
                        </div>

                        <i class="icon arrow-down-icon active"></i>

                    </div>

                    @guest
                        <div class="dropdown-list bottom-right" style="display: none;">

                            <div class="dropdown-container">

                                <label>Account</label>

                                <ul>
                                    <li><a href="{{ route('customer.session.index') }}">Sign In</a></li>

                                    <li><a href="{{ route('customer.register.index') }}">Sign Up</a></li>
                                </ul>

                            </div>

                        </div>
                    @endguest
                    @auth('customer')
                        <div class="dropdown-list bottom-right" style="display: none;">
                            <div class="dropdown-container">
                                <label>Account</label>
                                <ul>
                                    <li><a href="{{ route('customer.account.index') }}">Account</a></li>

                                    <li><a href="{{ route('customer.profile.index') }}">Profile</a></li>

                                    <li><a href="{{ route('customer.address.index') }}">Address</a></li>

                                    <li><a href="{{ route('customer.wishlist.index') }}">Wishlist</a></li>

                                    {{-- <li><a href="{{ route('customer.cart') }}">Cart</a></li> --}}
                                    <li><a href="{{ route('customer.orders.index') }}">Orders</a></li>

                                    <li><a href="{{ route('customer.session.destroy') }}">Logout</a></li>
                                </ul>

                            </div>

                        </div>
                    @endauth
                </li>
            </ul>
            <ul class="cart-dropdown-container">

                <li class="cart-dropdown">
                    <span class="icon cart-icon"></span>
                    @if(isset($cart))
                    @php
                        $cartInstance = session()->get('cart');
                    @endphp
                    <div class="dropdown-toggle">

                        <div style="display: inline-block; cursor: pointer;">
                            <span class="name"><span class="count">  {{$cartInstance->items_count}} Products</span>
                        </div>

                        <i class="icon arrow-down-icon active"></i>

                    </div>

                    <div class="dropdown-list" style="display: none; top: 50px; right: 0px">
                        <div class="dropdown-container">
                            <div class="dropdown-cart">
                                <div class="dropdown-header">
                                    <p class="heading">Cart Subtotal - {{ $cartInstance->sub_total }}</p>
                                </div>

                                <div class="dropdown-content">
                                    @foreach($cart as $product)
                                    <div class="item" >
                                        <div class="item-image" >
                                            <img src="{{$product['2']}}" />
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name">{{$product['0']}}</div>
                                            <div class="item-price">{{$product['1']}}</div>
                                            <div class="item-qty">Quantity - {{$product['3']}}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="dropdown-footer">
                                    <a href="{{ route('shop.checkout.cart.index') }}">View Shopping Cart</a>
                                    <button class="btn btn-primary btn-lg">CHECKOUT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="dropdown-toggle">
                        <div style="display: inline-block; cursor: pointer;">
                            <span class="name"><span class="count"> 0 &nbsp;</span>Products</span>
                        </div>
                    </div>
                    @endif
                </li>
            </ul>

            <ul class="right-responsive">
                <li class="search-box"><span class="icon search-icon" id="search"></span></li>
                <ul class="resp-account-dropdown-container">

                    <li class="account-dropdown">

                        <div class="dropdown-toggle">

                            <span class="icon account-icon"></span>

                            {{-- <div style="display: inline-block; cursor: pointer;">
                                <span class="name">Account</span>
                            </div>
                            <i class="icon arrow-down-icon active"></i> --}}

                        </div>

                        @guest
                            <div class="dropdown-list bottom-right" style="display: none;">

                                <div class="dropdown-container">

                                    <label>Account</label>

                                    <ul>
                                        <li><a href="{{ route('customer.session.index') }}">Sign In</a></li>

                                        <li><a href="{{ route('customer.register.index') }}">Sign Up</a></li>
                                    </ul>

                                </div>

                            </div>
                        @endguest
                        @auth('customer')
                            <div class="dropdown-list bottom-right" style="display: none;">
                                <div class="dropdown-container">
                                    <label>Account</label>
                                    <ul>
                                        <li><a href="{{ route('customer.account.index') }}">Account</a></li>

                                        <li><a href="{{ route('customer.profile.index') }}">Profile</a></li>

                                        <li><a href="{{ route('customer.address.index') }}">Address</a></li>

                                        <li><a href="{{ route('customer.wishlist.index') }}">Wishlist</a></li>

                                        {{-- <li><a href="{{ route('customer.cart') }}">Cart</a></li> --}}
                                        <li><a href="{{ route('customer.orders.index') }}">Orders</a></li>

                                        <li><a href="{{ route('customer.session.destroy') }}">Logout</a></li>
                                    </ul>

                                </div>

                            </div>
                        @endauth
                    </li>
                </ul>
                <ul class="resp-cart-dropdown-container">

                    <li class="cart-dropdown">
                        @if(isset($cart))
                        @php
                            $cartInstance = session()->get('cart');
                        @endphp
                        <div class="dropdown-toggle">
                            <span class="icon cart-icon"></span>
                        </div>

                        <div class="dropdown-list" style="display: none; top: 50px; right: 0px">
                            <div class="dropdown-container">
                                <div class="dropdown-cart">
                                    <div class="dropdown-header">
                                        <p class="heading">Cart Subtotal - {{ $cartInstance->sub_total }}</p>
                                    </div>

                                    <div class="dropdown-content">
                                        @foreach($cart as $product)
                                        <div class="item" >
                                            <div class="item-image" >
                                                <img src="{{$product['2']}}" />
                                            </div>
                                            <div class="item-details">
                                                <div class="item-name">{{$product['0']}}</div>
                                                <div class="item-price">{{$product['1']}}</div>
                                                <div class="item-qty">Quantity - {{$product['3']}}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="dropdown-footer">
                                        <a href="/">View Shopping Cart</a>
                                        <button class="btn btn-primary btn-lg">CHECKOUT</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="dropdown-toggle">
                            <div style="display: inline-block; cursor: pointer;">
                                <span class="name"><span class="count"> 0 &nbsp;</span>Products</span>
                            </div>
                        </div>
                        @endif
                    </li>
                </ul>
                {{-- <li class="cart-box"><span class="icon cart-icon"></span></li> --}}
                <li class="menu-box" ><span class="icon sortable-icon" id="sortable"></span></li>
            </ul>
        </div>
    </div>

    <div class="header-bottom" id="header-bottom">
    @include('shop::layouts.header.nav-menu.navmenu')
    </div>

</div>

{{--  @push('scripts')

    <script>

        window.onload = function() {

            var hamMenu = document.getElementById("hammenu");
            var search = document.getElementById("search");
            var searchSuggestion = document.getElementsByClassName('search-suggestion')[0];
            var headerBottom = document.getElementsByClassName('header-bottom')[0];
            var nav= document.getElementsByClassName('nav-responsive')[0];

            search.addEventListener("click", header);
            hamMenu.addEventListener("click", header);

            window.addEventListener('scroll', function() {
                if(window.pageYOffset > 70){
                    headerBottom.style.visibility = "hidden";

                }else{
                    headerBottom.style.visibility = "visible";
                }
            });

            function header(){

                var className = document.getElementById(this.id).className;

                if(className === 'icon search-icon' ){
                    search.classList.remove("search-icon");
                    search.classList.add("cross-icon");
                    searchSuggestion.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                    nav.style.display = 'none';
                }else if(className === 'icon sortable-icon'){
                    hamMenu.classList.remove("sortable-icon");
                    hamMenu.classList.add("cross-icon");
                    searchSuggestion.style.display = 'none';
                    nav.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }else{
                    search.classList.remove("cross-icon");
                    search.classList.add("search-icon");
                    hamMenu.classList.remove("cross-icon");
                    hamMenu.classList.add("sortable-icon");
                    searchSuggestion.style.display = 'none';
                    nav.style.display = 'none';
                    document.body.style.overflow = "scroll";
                }
            }
        }

    </script>

@endpush  --}}
