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

            {{-- Triggered on responsive mode only --}}
            <ul class="search-dropdown-container">
                <li class="search-dropdown">

                </li>
            </ul>

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

            @if(isset($cart))
                <cart-dropdown :items='@json($cart)'></cart-dropdown>
            @else
                <ul class="cart-dropdown">
                    <li class="cart-summary">
                        <span class="icon cart-icon"></span>

                        <span class="cart"><span class="cart-count">0</span>Products</span>

                        <span class="icon arrow-down-icon"></span>
                    </li>
                </ul>
            @endif

            {{-- Meant for responsive views only --}}
            <ul class="ham-dropdown-container">

                <li class="ham-dropdown">
                    {{-- use this section for the dropdown of the hamburger menu --}}
                </li>

            </ul>

        </div>
        <div class="right-responsive">

            <ul class="right-wrapper">
                <li class="search-box"><span class="icon search-icon" id="search"></span></li>
                <li class="account-box"><span class="icon account-icon"></span></li>
                <li class="cart-box"><span class="icon cart-icon"></span></li>
                <li class="menu-box" ><span class="icon sortable-icon" id="hammenu"></span></li>
            </ul>

        </div>
    </div>

    <div class="header-bottom" id="header-bottom">
    @include('shop::layouts.header.nav-menu.navmenu')
    </div>

</div>



@push('scripts')

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

@endpush
