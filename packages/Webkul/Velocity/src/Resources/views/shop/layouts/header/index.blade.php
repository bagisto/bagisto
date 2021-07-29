<header class="sticky-header">
    <div class="row remove-padding-margin velocity-divide-page">
        <a class="left navbar-brand" href="{{ route('shop.home.index') }}" aria-label="Logo">
            <img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt="" />
        </a>

        <div class="right searchbar">
            <div class="row">
                <div class="col-lg-5 col-md-12">
                    <div class="input-group">
                        <form
                            method="GET"
                            role="search"
                            id="search-form"
                            action="{{ route('velocity.search.index') }}">

                            <div
                                class="btn-toolbar full-width"
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

                <div class="col-lg-7 col-md-12 vc-full-screen">
                    <div class="left-wrapper">

                        {!! view_render_event('bagisto.shop.layout.header.wishlist.before') !!}

                            @php
                                $showWishlist = core()->getConfigData('general.content.shop.wishlist_option') == "1" ? true : false;
                            @endphp

                            @if($showWishlist)

                                <wishlist-component-with-badge
                                    is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
                                    src="{{ route('customer.wishlist.index') }}">
                                </wishlist-component-with-badge>

                            @endif

                        {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!}

                        {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}

                            @php
                                $showCompare = core()->getConfigData('general.content.shop.compare_option') == "1" ? true : false;
                            @endphp

                            @if ($showCompare)

                                <compare-component-with-badge
                                    is-customer="{{ auth()->guard('customer')->check() ? 'true' : 'false' }}"
                                    src="{{ auth()->guard('customer')->check() ? route('velocity.customer.product.compare') : route('velocity.product.compare') }}">
                                </compare-component-with-badge>

                            @endif

                        {!! view_render_event('bagisto.shop.layout.header.compare.after') !!}

                        {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}

                            @include('shop::checkout.cart.mini-cart')

                        {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
    <script type="text/javascript">
        (() => {
            document.addEventListener('scroll', e => {
                scrollPosition = Math.round(window.scrollY);

                if (scrollPosition > 50) {
                    document.querySelector('header').classList.add('header-shadow');
                } else {
                    document.querySelector('header').classList.remove('header-shadow');
                }
            });
        })();
    </script>
@endpush
