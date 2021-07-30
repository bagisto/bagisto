<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        {{-- title --}}
        <title>@yield('page_title')</title>

        {{-- meta data --}}
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url()->to('/') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        {!! view_render_event('bagisto.shop.layout.head') !!}

        {{-- for extra head data --}}
        @yield('head')

        {{-- seo meta data --}}
        @section('seo')
            <meta name="description" content="{{ core()->getCurrentChannel()->description }}"/>
        @show

        {{-- fav icon --}}
        @if ($favicon = core()->getCurrentChannel()->favicon_url)
            <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
        @else
            <link rel="icon" sizes="16x16" href="{{ asset('/themes/velocity/assets/images/static/v-icon.png') }}" />
        @endif

        {{-- all styles --}}
        @include('shop::layouts.styles')
    </head>

    <body @if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl') class="rtl" @endif>
        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        {{-- main app --}}
        <div id="app">
            <product-quick-view v-if="$root.quickView"></product-quick-view>

            <div class="main-container-wrapper">

                @section('body-header')
                    @include('shop::layouts.top-nav.index')

                    {!! view_render_event('bagisto.shop.layout.header.before') !!}

                        @include('shop::layouts.header.index')

                    {!! view_render_event('bagisto.shop.layout.header.after') !!}

                    <div class="main-content-wrapper col-12 no-padding">
                        @php
                            $velocityContent = app('Webkul\Velocity\Repositories\ContentRepository')->getAllContents();
                        @endphp

                        <header class="row velocity-divide-page vc-header header-shadow active">
                            <div class="vc-small-screen container">
                                @php
                                    $cart = cart()->getCart();

                                    $cartItemsCount = trans('shop::app.minicart.zero');

                                    if ($cart) {
                                        $cartItemsCount = $cart->items->count();
                                    }
                                @endphp

                                @php
                                    $currency = $locale = null;

                                    $currentLocale = app()->getLocale();
                                    $currentCurrency = core()->getCurrentCurrencyCode();

                                    $allLocales = core()->getCurrentChannel()->locales;
                                    $allCurrency = core()->getCurrentChannel()->currencies;
                                @endphp

                                @foreach ($allLocales as $appLocale)
                                    @if ($appLocale->code == $currentLocale)
                                        @php
                                            $locale = $appLocale;
                                        @endphp
                                    @endif
                                @endforeach

                                @foreach ($allCurrency as $appCurrency)
                                    @if ($appCurrency->code == $currentCurrency)
                                        @php
                                            $currency = $appCurrency;
                                        @endphp
                                    @endif
                                @endforeach

                                <mobile-header
                                    is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
                                    heading= "{{ __('velocity::app.menu-navbar.text-category') }}"
                                    :header-content="{{ json_encode($velocityContent) }}"
                                    category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
                                    cart-items-count="{{ $cartItemsCount }}"
                                    cart-route="{{ route('shop.checkout.cart.index') }}"
                                    :locale="{{ json_encode($locale) }}"
                                    :all-locales="{{ json_encode($allLocales) }}"
                                    :currency="{{ json_encode($currency) }}"
                                    :all-currencies="{{ json_encode($allCurrency) }}"
                                >

                                    {{-- this is default content if js is not loaded --}}
                                    <div>
                                        <div class="hamburger-wrapper">
                                            <i class="rango-toggle hamburger"></i>
                                        </div>

                                        <a class="left" href="{{ route('shop.home.index') }}" aria-label="Logo">
                                            <img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt="" />
                                        </a>
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
                                                <a
                                                    class="unset"
                                                    href="{{ route('customer.session.destroy') }}">
                                                    <span>{{ __('shop::app.header.logout') }}</span>
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

                                    <template v-slot:top-header>
                                        @php
                                            $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;
                                        @endphp

                                        @if ($showCompare)
                                            <compare-component-with-badge
                                                is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
                                                is-text="false"
                                                src="{{ auth()->guard('customer')->check() ? route('velocity.customer.product.compare') : route('velocity.product.compare') }}">
                                            </compare-component-with-badge>
                                        @endif

                                        @php
                                            $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                                        @endphp

                                        @if ($showWishlist)
                                            <wishlist-component-with-badge
                                                is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
                                                is-text="false"
                                                src="{{ route('customer.wishlist.index') }}">
                                            </wishlist-component-with-badge>
                                        @endif
                                    </template>

                                    <template v-slot:search-bar>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <form
                                                        method="GET"
                                                        role="search"
                                                        id="search-form"
                                                        action="{{ route('velocity.search.index') }}">
                                                        <div
                                                            class="btn-toolbar full-width search-form"
                                                            role="toolbar">

                                                            <searchbar-component>
                                                                <template v-slot:image-search>
                                                                    <image-search-component
                                                                        status="{{core()->getConfigData('general.content.shop.image_search') == '1' ? 'true' : 'false'}}"
                                                                        upload-src="{{ route('shop.image.search.upload') }}"
                                                                        view-src="{{ route('shop.search.index') }}"
                                                                        common-error="{{ __('shop::app.common.error') }}"
                                                                        size-limit-error="{{ __('shop::app.common.image-upload-limit') }}">
                                                                    </image-search-component>
                                                                </template>
                                                            </searchbar-component>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                </mobile-header>
                            </div>

                            <div>
                                <sidebar-header heading= "{{ __('velocity::app.menu-navbar.text-category') }}">

                                    {{-- this is default content if js is not loaded --}}
                                    <div class="main-category fs16 unselectable fw6 left">
                                        <i class="rango-view-list text-down-4 align-vertical-top fs18"></i>

                                        <span class="pl5">{{ __('velocity::app.menu-navbar.text-category') }}</span>
                                    </div>

                                </sidebar-header>
                            </div>

                            <div class="content-list right">
                                <right-side-header :header-content="{{ json_encode($velocityContent) }}">

                                    {{-- this is default content if js is not loaded --}}
                                    <ul type="none" class="no-margin">
                                    </ul>

                                </right-side-header>
                            </div>
                        </header>

                        <div class="">
                            <div class="row col-12 remove-padding-margin">
                                <sidebar-component
                                    main-sidebar=true
                                    id="sidebar-level-0"
                                    url="{{ url()->to('/') }}"
                                    category-count="{{ $velocityMetaData ? $velocityMetaData->sidebar_category_count : 10 }}"
                                    add-class="category-list-container pt10">
                                </sidebar-component>

                                <div
                                    class="col-12 no-padding content" id="home-right-bar-container">

                                    <div class="container-right row no-margin col-12 no-padding">

                                        {!! view_render_event('bagisto.shop.layout.content.before') !!}

                                            @yield('content-wrapper')

                                        {!! view_render_event('bagisto.shop.layout.content.after') !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @show

                <div class="container">

                    {!! view_render_event('bagisto.shop.layout.full-content.before') !!}

                        @yield('full-content-wrapper')

                    {!! view_render_event('bagisto.shop.layout.full-content.after') !!}

                </div>
            </div>

            <div class="modal-parent" id="loader" style="top: 0" v-show="showPageLoader">
                <velocity-overlay-loader :is-open="true"></velocity-overlay-loader>
            </div>
        </div>

        {{-- footer --}}
        @section('footer')
            {!! view_render_event('bagisto.shop.layout.footer.before') !!}

                @include('shop::layouts.footer.index')

            {!! view_render_event('bagisto.shop.layout.footer.after') !!}
        @show

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        <div id="alert-container"></div>

        {{-- all scripts --}}
        @include('shop::layouts.scripts')
    </body>
</html>
