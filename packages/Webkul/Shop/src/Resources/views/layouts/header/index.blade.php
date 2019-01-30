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
                    <form role="search" action="{{ route('shop.search.index') }}" method="GET" style="display: inherit;">
                        <input type="search" name="term" class="search-field" placeholder="{{ __('shop::app.header.search-text') }}" required>

                        <div class="search-icon-wrapper">
                            <button class="" class="background: none;">
                                <i class="icon icon-search"></i>
                            </button>
                        </div>
                    </form>
                </li>
            </ul>
        </div>

        <div class="right-content">
            <ul class="right-content-menu">
                {!! view_render_event('bagisto.shop.layout.header.currency-item.before') !!}

                @if (core()->getCurrentChannel()->currencies->count() > 1)
                    <li class="list">
                        <span class="dropdown-toggle">
                            {{ core()->getCurrentCurrencyCode() }}
                            <i class="icon arrow-down-icon active"></i>
                        </span>

                        <ul class="dropdown-list currency">
                            @foreach (core()->getCurrentChannel()->currencies as $currency)
                                <li>
                                    <a href="?currency={{ $currency->code }}">{{ $currency->code }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                {!! view_render_event('bagisto.shop.layout.header.currency-item.after') !!}

                {!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}

                <li class="list">
                    <span class="dropdown-toggle">
                        <i class="icon account-icon"></i>
                        <i class="icon arrow-down-icon active"></i>
                    </span>

                    @guest('customer')
                        <ul class="dropdown-list account guest">
                            <li>
                                <div>
                                    <label style="color: #9e9e9e; font-weight: 700; text-transform: uppercase; font-size: 15px;">
                                        {{ __('shop::app.header.title') }}
                                    </label>
                                </div>

                                <div style="margin-top: 5px;">
                                    <span style="font-size: 12px;">{{ __('shop::app.header.dropdown-text') }}</span>
                                </div>

                                <div style="margin-top: 15px;">
                                    <a class="btn btn-primary btn-sm" href="{{ route('customer.session.index') }}" style="color: #ffffff">
                                        {{ __('shop::app.header.sign-in') }}
                                    </a>

                                    <a class="btn btn-primary btn-sm" href="{{ route('customer.register.index') }}" style="float: right; color: #ffffff">
                                        {{ __('shop::app.header.sign-up') }}
                                    </a>
                                </div>
                            </li>
                        </ul>
                    @endguest

                    @auth('customer')
                        <ul class="dropdown-list account customer">
                            <li>
                                <div>
                                    <label style="color: #9e9e9e; font-weight: 700; text-transform: uppercase; font-size: 15px;">
                                        {{ auth()->guard('customer')->user()->first_name }}
                                    </label>
                                </div>

                                <ul>
                                    <li>
                                        <a href="{{ route('customer.profile.index') }}">{{ __('shop::app.header.profile') }}</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('customer.wishlist.index') }}">{{ __('shop::app.header.wishlist') }}</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('shop.checkout.cart.index') }}">{{ __('shop::app.header.cart') }}</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('customer.session.destroy') }}">{{ __('shop::app.header.logout') }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endauth
                </li>

                {!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}

                {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}

                <li class="cart-dropdown-container list">
                    @inject ('productImageHelper', 'Webkul\Product\Helpers\ProductImage')

                    <ul class="cart-dropdown">
                        <span class="icon cart-icon"></span>
                        @include('shop::checkout.cart.mini-cart')
                    </ul>
                </li>

                {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
            </ul>
        </div>
    </div>

    <div class="header-bottom" id="header-bottom">
        @include('shop::layouts.header.nav-menu.navmenu')
    </div>

    <div class="search-responsive mt-10">
        <form role="search" action="{{ route('shop.search.index') }}" method="GET" style="display: inherit;">
            <div class="search-content">
                <button class="" style="background: none; border: none; padding: 0px;">
                    <i class="icon icon-search mt-10"></i>
                </button>
                <input type="search" name="term" class="search mt-5">
                <button class="" style="background: none; float: right; border: none; padding: 0px;">
                    <i class="icon icon-menu-back right mt-10"></i>
                </button>
            </div>
        </form>

        {{--  <div class="search-content">
            <i class="icon icon-search mt-10"></i>
            <span class="suggestion mt-15">Designer sarees</span>
        </div>  --}}
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var hamMenu = document.getElementById("hammenu");
            var search = document.getElementById("search");
            var searchResponsive = document.getElementsByClassName('search-responsive')[0];
            var navResponsive = document.getElementsByClassName('header-bottom')[0];

            if (search && hamMenu) {
                search.addEventListener("click", header);
                hamMenu.addEventListener("click", header);
            }

            function header() {
                var className = document.getElementById(this.id).className;
                if (className === 'icon icon-search' ) {
                    search.classList.remove("icon-search");
                    search.classList.add("icon-menu-close");
                    hamMenu.classList.remove("icon-menu-close");
                    hamMenu.classList.add("icon-menu");
                    searchResponsive.style.display = 'block';
                    navResponsive.style.display = 'none';
                } else if (className === 'icon icon-menu') {
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
        });
    </script>
@endpush
