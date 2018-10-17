
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
                        <span class="icon icon-search"></span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="right-content">
            <ul class="account-dropdown-container">
                <li class="account-dropdown">
                    <div class="dropdown-toggle">
                        <div style="display: inline-flex; align-items: center; cursor: pointer;">
                            <span class="icon account-icon" style="margin-top: 5px;"></span>
                            <i class="icon arrow-down-icon active"></i>
                        </div>
                    </div>
                    @guest('customer')
                        <div class="dropdown-list bottom-right" style="display: none;">
                            <div class="dropdown-container">
                                <label>Account</label><br/>
                                <span style="font-size: 12px;">Manage Cart, Orders & Wishlist.</span>
                                <ul class="account-dropdown-list">
                                    <li><a class="btn btn-primary btn-sm" href="{{ route('customer.session.index') }}">Sign In</a></li>

                                    <li><a class="btn btn-primary btn-sm" href="{{ route('customer.register.index') }}">Sign Up</a></li>
                                </ul>

                            </div>

                        </div>
                    @endguest
                    @auth('customer')
                        <div class="dropdown-list bottom-right" style="display: none; max-width: 230px;">
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

                @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

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

                                        <a class="btn btn-primary btn-lg" style="color: white;" href="{{ route('shop.checkout.onepage.index') }}">CHECKOUT</a>
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
                <li class="search-box"><span class="icon icon-search" id="search"></span></li>
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
                        @if(isset($cart))
                        <div>
                            <a href="{{ route('shop.checkout.cart.index') }}"><span class="icon cart-icon"></span></a>
                        </div>
                        @else
                        <div style="display: inline-block; cursor: pointer;">
                            <span class="icon cart-icon"></span>
                        </div>
                        @endif
                    </li>
                </ul>
                <li class="menu-box" ><span class="icon icon-menu" id="hammenu"></span></li>
            </ul>
        </div>
    </div>

    <div class="header-bottom" id="header-bottom">
        @include('shop::layouts.header.nav-menu.navmenu')
    </div>

    <div class="search-responsive">
        <div class="search-content">
            <i class="icon icon-search mt-10"></i>
            <input  class="search mt-5">
            <i class="icon icon-menu-back right mt-10"></i>
        </div>

        <div class="search-content">
            <i class="icon icon-search mt-10"></i>
            <span class="suggestion mt-15">Designer sarees</span>
        </div>
    </div>

    <div class="responsive-nav">
        <category-nav categories='@json($categories)' url="{{url()->to('/')}}"></category-nav>
    </div>
</div>



@push('scripts')

<script>

    window.onload = function() {
        var hamMenu = document.getElementById("hammenu");
        var search = document.getElementById("search");

        var searchResponsive = document.getElementsByClassName('search-responsive')[0];
        var sortLimit = document.getElementsByClassName('reponsive-sorter-limiter')[0];
        var layerFilter = document.getElementsByClassName('responsive-layred-filter')[0];
        var navResponsive = document.getElementsByClassName('responsive-nav')[0];
        var thumbList = document.getElementsByClassName('thumb-list')[0];
        var thumbFrame = document.getElementsByClassName('thumb-frame');
        var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

        search.addEventListener("click", header);
        hamMenu.addEventListener("click", header);

        // activate on window resize
        window.addEventListener('resize', function(){
            if(window.innerWidth > 720){
                searchResponsive.style.display = 'none';
                navResponsive.style.display = 'none';
                if(layerFilter && sortLimit){
                    layerFilter.style.display ="none";
                    sortLimit.style.display ="none";
                }
            }
            if(window.innerWidth < 1313 && window.innerWidth > 720){
                if(thumbList){
                    thumbList.style.maxHeight = productHeroImage.offsetHeight + "px";
                    for(let i=0 ; i < thumbFrame.length ; i++){
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight/4) + "px";
                    }
                }
            }else {
                for(let i=0 ; i < thumbFrame.length ; i++){
                    thumbFrame[i].style.height = 120 + "px";
                }
            }
        });

        // for header responsive icon
        function header(){
            var className = document.getElementById(this.id).className;
            if(className === 'icon icon-search' ){
                search.classList.remove("icon-search");
                search.classList.add("icon-menu-close");
                hamMenu.classList.remove("icon-menu-close");
                hamMenu.classList.add("icon-menu");
                searchResponsive.style.display = 'block';
                navResponsive.style.display = 'none';
            }else if(className === 'icon icon-menu'){
                hamMenu.classList.remove("icon-menu");
                hamMenu.classList.add("icon-menu-close");
                search.classList.remove("icon-menu-close");
                search.classList.add("icon-search");
                searchResponsive.style.display = 'none';
                navResponsive.style.display = 'block';
            }else{
                search.classList.remove("icon-menu-close");
                search.classList.add("icon-search");
                hamMenu.classList.remove("icon-menu-close");
                hamMenu.classList.add("icon-menu");
                searchResponsive.style.display = 'none';
                navResponsive.style.display = 'none';
            }
        }
    }

</script>

@endpush