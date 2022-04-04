@php
    $cart = cart()->getCart();
    $cartItemsCount = $cart ? $cart->items->count() : trans('shop::app.minicart.zero');
@endphp

<mobile-header
    is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
    heading= "{{ __('velocity::app.menu-navbar.text-category') }}"
    :header-content="{{ json_encode(app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents()) }}"
    category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
    cart-items-count="{{ $cartItemsCount }}"
    cart-route="{{ route('shop.checkout.cart.index') }}"
    :locale="{{ json_encode(core()->getCurrentLocale()) }}"
    :all-locales="{{ json_encode(core()->getCurrentChannel()->locales()->orderBy('name')->get()) }}"
    :currency="{{ json_encode(core()->getCurrentCurrency()) }}"
    :all-currencies="{{ json_encode(core()->getCurrentChannel()->currencies) }}"
>

    {{-- this is default content if js is not loaded --}}
    <div class="row">
        <div class="col-6">
            <div class="hamburger-wrapper">
                <i class="rango-toggle hamburger"></i>
            </div>

            <a class="left" href="{{ route('shop.home.index') }}" aria-label="Logo">
                <img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt="" />
            </a>
        </div>

        <div class="right-vc-header col-6">
            <a class="unset cursor-pointer">
                <i class="material-icons">search</i>
            </a>
            <a href="{{ route('shop.checkout.cart.index') }}" class="unset">
                <i class="material-icons text-down-3">shopping_cart</i>
                <div class="badge-wrapper">
                    <span class="badge">{{ $cartItemsCount }}</span>
                </div>
            </a>
        </div>
    </div>

    <template v-slot:greetings>
        @guest('customer')
            <a class="unset" href="{{ route('customer.session.index') }}">
                {{ __('velocity::app.responsive.header.greeting', ['customer' => 'Guest']) }}
            </a>
        @endguest

        @auth('customer')
            <a class="unset" href="{{ route('customer.profile.index') }}">
                {{ __('velocity::app.responsive.header.greeting', ['customer' => auth()->guard('customer')->user()->first_name]) }}
            </a>
        @endauth
    </template>

    <template v-slot:customer-navigation>
        @auth('customer')
            <ul type="none" class="vc-customer-options">
                <li>
                    <a href="{{ route('customer.profile.index') }}" class="unset">
                        <i class="icon profile text-down-3"></i>
                        <span>{{ __('shop::app.header.profile') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('customer.address.index') }}" class="unset">
                        <i class="icon address text-down-3"></i>
                        <span>{{ __('velocity::app.shop.general.addresses') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('customer.reviews.index') }}" class="unset">
                        <i class="icon reviews text-down-3"></i>
                        <span>{{ __('velocity::app.shop.general.reviews') }}</span>
                    </a>
                </li>

                @if (core()->getConfigData('general.content.shop.wishlist_option'))
                    <li>
                        <a href="{{ route('customer.wishlist.index') }}" class="unset">
                            <i class="icon wishlist text-down-3"></i>
                            <span>{{ __('shop::app.header.wishlist') }}</span>
                        </a>
                    </li>
                @endif

                @if (core()->getConfigData('general.content.shop.compare_option'))
                    <li>
                        <a href="{{ route('velocity.customer.product.compare') }}" class="unset">
                            <i class="icon compare text-down-3"></i>
                            <span>{{ __('shop::app.customer.compare.text') }}</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('customer.orders.index') }}" class="unset">
                        <i class="icon orders text-down-3"></i>
                        <span>{{ __('velocity::app.shop.general.orders') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('customer.downloadable_products.index') }}" class="unset">
                        <i class="icon downloadables text-down-3"></i>
                        <span>{{ __('velocity::app.shop.general.downloadables') }}</span>
                    </a>
                </li>
            </ul>
        @endauth
    </template>

    <template v-slot:extra-navigation>
        <li>
            @auth('customer')
                <form id="customerLogout" action="{{ route('customer.session.destroy') }}" method="POST">
                    @csrf

                    @method('DELETE')
                </form>

                <a
                    class="unset"
                    href="{{ route('customer.session.destroy') }}"
                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                    {{ __('shop::app.header.logout') }}
                </a>
            @endauth

            @guest('customer')
                <a
                    class="unset"
                    href="{{ route('customer.session.create') }}">
                    <span>{{ __('shop::app.customer.login-form.title') }}</span>
                </a>
            @endguest
        </li>

        <li>
            @guest('customer')
                <a
                    class="unset"
                    href="{{ route('customer.register.index') }}">
                    <span>{{ __('shop::app.header.sign-up') }}</span>
                </a>
            @endguest
        </li>
    </template>

    <template v-slot:logo>
        <a class="left" href="{{ route('shop.home.index') }}" aria-label="Logo">
            <img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt="" />
        </a>
    </template>

    

    <template v-slot:search-bar>
        <div class="row">
            <div class="col-md-12">
                @include('velocity::shop.layouts.particals.search-bar')
            </div>
        </div>
    </template>

</mobile-header>