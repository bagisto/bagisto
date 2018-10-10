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

                            @guest('customer')
                                <span class="name">Login & Register</span>
                            @endguest

                            @auth('customer')
                                <span class="name">Account</span>
                            @endauth

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

                                    <li><a href="{{ route('shop.checkout.cart.index') }}">Cart</a></li>

                                    <li><a href="{{ route('customer.orders.index') }}">Orders</a></li>

                                    <li><a href="{{ route('customer.session.destroy') }}">Logout</a></li>
                                </ul>

                            </div>

                        </div>
                    @endauth
                </li>
            </ul>
            <ul class="cart-dropdown-container">

                <?php $cart = cart()->getCart(); ?>

                @inject ('productImageHelper', 'Webkul\Product\Product\ProductImage')

                <li class="cart-dropdown">
                    <span class="icon cart-icon"></span>
                    @if($cart)
                        @php
                            $items = $cart->items;
                        @endphp
                        <div class="dropdown-toggle">
                            <div style="display: inline-block; cursor: pointer;">
                                @if($cart->items_qty - intval($cart->items_qty) > 0)
                                    <span class="name">
                                        Cart
                                        <span class="count"> ({{ $cart->items_qty }})</span>
                                    </span>
                                @else
                                    <span class="name">
                                        Cart
                                        <span class="count"> ({{ intval($cart->items_qty) }})</span>
                                    </span>
                                @endif
                            </div>

                            <i class="icon arrow-down-icon active"></i>

                        </div>
                        <div class="dropdown-list" style="display: none; top: 50px; right: 0px">
                            <div class="dropdown-container">
                                <div class="dropdown-cart">
                                    <div class="dropdown-header">
                                        <p class="heading">Cart Subtotal - {{ $cart->sub_total }}</p>
                                    </div>

                                    <div class="dropdown-content">
                                        @foreach($items as $item)
                                            @if($item->type == "configurable")
                                            <div class="item">
                                                <div class="item-image" >
                                                    @php
                                                        $images = $productImageHelper->getProductBaseImage($item->child->product);
                                                    @endphp
                                                    <img src="{{ $images['small_image_url'] }}" />
                                                </div>

                                                <div class="item-details">

                                                    <div class="item-name">{{ $item->child->name }}</div>

                                                    <div class="item-price">{{ $item->total }}</div>

                                                    <div class="item-qty">Quantity - {{ $item->quantity }}</div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="item">
                                                <div class="item-image" >
                                                    @php
                                                        $images = $productImageHelper->getProductBaseImage($item->product);
                                                    @endphp
                                                    <img src="{{ $images['small_image_url'] }}" />
                                                </div>

                                                <div class="item-details">

                                                    <div class="item-name">{{ $item->name }}</div>

                                                    <div class="item-price">{{ $item->total }}</div>

                                                    <div class="item-qty">Quantity - {{ $item->quantity }}</div>
                                                </div>
                                            </div>
                                            @endif
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

                                <span class="name">Cart<span class="count"> (0) </span></span>
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

                                        <li><a href="{{ route('shop.checkout.cart.index') }}">Cart</a></li>

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
                        @if(isset($cart) && session()->has('cart'))
                        @php
                            $cart = session()->get('cart');
                        @endphp
                        <div class="dropdown-toggle">
                            <span class="icon cart-icon"></span>
                        </div>

                        <div class="dropdown-list" style="display: none; top: 50px; right: 0px">
                            <div class="dropdown-container">
                                <div class="dropdown-cart">
                                    <div class="dropdown-header">
                                        <p class="heading">Cart Subtotal - {{ $cart->sub_total }}</p>
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
                                {{-- <span class="name"><span class="count"> 0 &nbsp;
                                    </span>Products</span> --}}
                                <span class="icon cart-icon"></span>
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