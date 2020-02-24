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

        <?php
            $term = request()->input('term');

            if (! is_null($term)) {
                $serachQuery = 'term='.request()->input('term');
            }
        ?>

        <div class="right-content">

            <span class="search-box"><span class="icon icon-search" id="search"></span></span>

            <ul class="right-content-menu">

                {!! view_render_event('bagisto.shop.layout.header.currency-item.before') !!}

                @if (core()->getCurrentChannel()->currencies->count() > 1)
                    <li class="currency-switcher">
                        <span class="dropdown-toggle">
                            {{ core()->getCurrentCurrencyCode() }}

                            <i class="icon arrow-down-icon"></i>
                        </span>

                        <ul class="dropdown-list currency">
                            @foreach (core()->getCurrentChannel()->currencies as $currency)
                                <li>
                                    @if (isset($serachQuery))
                                        <a href="?{{ $serachQuery }}&currency={{ $currency->code }}">{{ $currency->code }}</a>
                                    @else
                                        <a href="?currency={{ $currency->code }}">{{ $currency->code }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                {!! view_render_event('bagisto.shop.layout.header.currency-item.after') !!}


                {!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}

                <li>
                    <span class="dropdown-toggle">
                        <i class="icon account-icon"></i>

                        <span class="name">{{ __('shop::app.header.account') }}</span>

                        <i class="icon arrow-down-icon"></i>
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
                                    <a class="btn btn-primary btn-md" href="{{ route('customer.session.index') }}" style="color: #ffffff">
                                        {{ __('shop::app.header.sign-in') }}
                                    </a>

                                    <a class="btn btn-primary btn-md" href="{{ route('customer.register.index') }}" style="float: right; color: #ffffff">
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

                <li class="cart-dropdown-container">

                    @include('shop::checkout.cart.mini-cart')

                </li>

                {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}

            </ul>

            <span class="menu-box" ><span class="icon icon-menu" id="hammenu"></span>
        </div>
    </div>

    <div class="header-bottom" id="header-bottom">
        @include('shop::layouts.header.nav-menu.navmenu')
    </div>

    <div class="search-responsive mt-10" id="search-responsive">
        <form role="search" action="{{ route('shop.search.index') }}" method="GET" style="display: inherit;">
            <div class="search-content">
                <button style="background: none; border: none; padding: 0px;">
                    <i class="icon icon-search"></i>
                </button>
                <input type="search" name="term" class="search">
                <i class="icon icon-menu-back right"></i>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            $('body').delegate('#search, .icon-menu-close, .icon.icon-menu', 'click', function(e) {
                toggleDropdown(e);
            });

            function toggleDropdown(e) {
                var currentElement = $(e.currentTarget);

                if (currentElement.hasClass('icon-search')) {
                    currentElement.removeClass('icon-search');
                    currentElement.addClass('icon-menu-close');
                    $('#hammenu').removeClass('icon-menu-close');
                    $('#hammenu').addClass('icon-menu');
                    $("#search-responsive").css("display", "block");
                    $("#header-bottom").css("display", "none");
                } elseif (currentElement.hasClass('icon-menu')) {
                    currentElement.removeClass('icon-menu');
                    currentElement.addClass('icon-menu-close');
                    $('#search').removeClass('icon-menu-close');
                    $('#search').addClass('icon-search');
                    $("#search-responsive").css("display", "none");
                    $("#header-bottom").css("display", "block");
                } else {
                    currentElement.removeClass('icon-menu-close');
                    $("#search-responsive").css("display", "none");
                    $("#header-bottom").css("display", "none");
                    if (currentElement.attr("id") == 'search') {
                        currentElement.addClass('icon-search');
                    } else {
                        currentElement.addClass('icon-menu');
                    }
                }
            }
        });
    </script>
@endpush
