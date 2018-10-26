<div class="header" id="header">
    <div class="header-top">
        <div class="left-content">
            <ul class="logo-container">
                <li>
                    <a href="{{ route('shop.home.index') }}">
                        @if ($logo = core()->getCurrentChannel()->logo_url)
                            <img class="logo" src="{{ $logo }}" />
                        @else
                            <img class="logo" src="{{ bagisto_asset('images/logo.svg') }}" />
                        @endif
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
            @if (core()->getCurrentChannel()->currencies->count() > 1)
                <ul class="currency-switcher">
                    <div class="dropdown-toggle">
                        {{ core()->getCurrentCurrencyCode() }}
                        <i class="icon arrow-down-icon active"></i>
                    </div>

                    <div class="dropdown-list bottom-right">
                        <div class="dropdown-container">
                            <ul>
                                @foreach (core()->getCurrentChannel()->currencies as $currency)
                                    <li>
                                        <a href="?currency={{ $currency->code }}">{{ $currency->code }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </ul>
            @endif

            <ul class="account-dropdown-container">
                <li class="account-dropdown">
                    <div class="dropdown-toggle account">
                        <span class="icon account-icon"></span>
                        <i class="icon arrow-down-icon active"></i>
                    </div>

                    @guest('customer')
                        <div class="dropdown-list bottom-right" style="display: none;">
                            <div class="dropdown-container">
                                <label>{{ __('shop::app.header.title') }}</label><br/>
                                <span style="font-size: 12px;">{{ __('shop::app.header.dropdown-text') }}</span>
                                <ul class="account-dropdown-list">
                                    <li><a class="btn btn-primary btn-sm" href="{{ route('customer.session.index') }}">{{ __('shop::app.header.sign-in') }}</a></li>

                                    <li><a class="btn btn-primary btn-sm" href="{{ route('customer.register.index') }}">{{ __('shop::app.header.sign-up') }}</a></li>
                                </ul>

                            </div>

                        </div>
                    @endguest

                    @auth('customer')
                        <div class="dropdown-list bottom-right" style="display: none; max-width: 230px;">
                            <div class="dropdown-container">
                                <label>{{ __('shop::app.header.title') }}</label>
                                <ul>
                                    <li><a href="{{ route('customer.profile.index') }}">{{ __('shop::app.header.profile') }}</a></li>

                                    <li><a href="{{ route('customer.wishlist.index') }}">{{ __('shop::app.header.wishlist') }}</a></li>

                                    <li><a href="{{ route('shop.checkout.cart.index') }}">{{ __('shop::app.header.cart') }}</a></li>

                                    <li><a href="{{ route('customer.session.destroy') }}">{{ __('shop::app.header.logout') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    @endauth
                </li>
            </ul>

            <ul class="cart-dropdown-container">
                @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

                <li class="cart-dropdown">
                    <span class="icon cart-icon"></span>
                    @include('shop::checkout.cart.mini-cart')
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
                                    <label>{{ __('shop::app.header.title') }}</label>

                                    <ul>
                                        <li><a href="{{ route('customer.session.index') }}">{{ __('shop::app.header.sign-in') }}</a></li>
                                        <li><a href="{{ route('customer.register.index') }}">{{ __('shop::app.header.sign-up') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endguest

                        @auth('customer')
                            <div class="dropdown-list bottom-right" style="display: none;">
                                <div class="dropdown-container">

                                    <label>{{ __('shop::app.header.title') }}</label>

                                    <ul>
                                        <li><a href="{{ route('customer.profile.index') }}">{{ __('shop::app.header.profile') }}</a></li>

                                        <li><a href="{{ route('customer.wishlist.index') }}">{{ __('shop::app.header.wishlist') }}</a></li>

                                        <li><a href="{{ route('shop.checkout.cart.index') }}">{{ __('shop::app.header.cart') }}</a></li>

                                        <li><a href="{{ route('customer.session.destroy') }}">{{ __('shop::app.header.logout') }}</a></li>
                                    </ul>

                                </div>

                            </div>
                        @endauth
                    </li>
                </ul>

                <ul class="resp-cart-dropdown-container">

                    <li class="cart-dropdown">
                        <?php $cart = cart()->getCart(); ?>

                        @if(isset($cart))
                            <div>
                                <a href="{{ route('shop.checkout.cart.index') }}">
                                    <span class="icon cart-icon"></span>
                                </a>
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

    <div class="search-responsive mt-10">
        <div class="search-content">
            <i class="icon icon-search mt-10"></i>
            <input  class="search mt-5">
            <i class="icon icon-menu-back right mt-10"></i>
        </div>

        {{--  <div class="search-content">
            <i class="icon icon-search mt-10"></i>
            <span class="suggestion mt-15">Designer sarees</span>
        </div>  --}}
    </div>

</div>

@push('scripts')
    <script>
        window.onload = function() {
            var hamMenu = document.getElementById("hammenu");
            var search = document.getElementById("search");
            var searchResponsive = document.getElementsByClassName('search-responsive')[0];
            var navResponsive = document.getElementsByClassName('header-bottom')[0];

            search.addEventListener("click", header);
            hamMenu.addEventListener("click", header);

            function header() {
                var className = document.getElementById(this.id).className;
                if(className === 'icon icon-search' ) {
                    search.classList.remove("icon-search");
                    search.classList.add("icon-menu-close");
                    hamMenu.classList.remove("icon-menu-close");
                    hamMenu.classList.add("icon-menu");
                    searchResponsive.style.display = 'block';
                    navResponsive.style.display = 'none';
                } else if(className === 'icon icon-menu') {
                    hamMenu.classList.remove("icon-menu");
                    hamMenu.classList.add("icon-menu-close");
                    search.classList.remove("icon-menu-close");
                    search.classList.add("icon-search");
                    searchResponsive.style.display = 'none';
                    navResponsive.style.display = 'block';
                } else {
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