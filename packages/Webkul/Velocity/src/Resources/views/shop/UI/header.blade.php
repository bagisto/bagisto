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

@push('scripts')
    <script type="text/x-template" id="mobile-header-template">
        <div class="row">
            <div class="col-6">
                <div v-if="hamburger" class="nav-container scrollable">
                    <div class="wrapper" v-if="this.rootCategories">
                        <div class="greeting drawer-section fw6">
                            <i class="material-icons">perm_identity</i>
                            <span>
                                <slot name="greetings"></slot>

                                <i
                                    @click="closeDrawer()"
                                    class="material-icons float-right text-dark">
                                    cancel
                                </i>
                            </span>
                        </div>

                        <ul type="none" class="velocity-content" v-if="headerContent.length > 0">
                            <li :key="index" v-for="(content, index) in headerContent">
                                <a
                                    class="unset"
                                    v-text="content.title"
                                    :href="`${$root.baseUrl}/${content.page_link}`">
                                </a>
                            </li>
                        </ul>

                        <ul type="none" class="category-wrapper" v-if="rootCategoriesCollection.length > 0">
                            <li v-for="(category, index) in rootCategoriesCollection">
                                <a class="unset" :href="`${$root.baseUrl}/${category.slug}`">
                                    <div class="category-logo">
                                        <img
                                            class="category-icon"
                                            v-if="category.category_icon_path"
                                            :src="`${$root.baseUrl}/storage/${category.category_icon_path}`" alt="" width="20" height="20" />
                                    </div>
                                    <span v-text="category.name"></span>
                                </a>

                                <i class="rango-arrow-right" @click="toggleSubcategories(index, $event)"></i>
                            </li>
                        </ul>

                        <slot name="customer-navigation"></slot>

                        <ul type="none" class="meta-wrapper">
                            <li>
                                <template v-if="locale">
                                    <div class="language-logo-wrapper">
                                        <img
                                            class="language-logo"
                                            :src="`${$root.baseUrl}/storage/${locale.locale_image}`"
                                            alt="" v-if="locale.locale_image" />

                                        <img
                                            class="language-logo"
                                            :src="`${$root.baseUrl}/themes/velocity/assets/images/flags/en.png`"
                                            alt="" v-else-if="locale.code == 'en'" />
                                    </div>

                                    <span v-text="locale.name"></span>
                                </template>

                                <i
                                    class="rango-arrow-right"
                                    @click="toggleMetaInfo('languages')">
                                </i>
                            </li>

                            <li>
                                <span v-text="currency.code"></span>

                                <i
                                    class="rango-arrow-right"
                                    @click="toggleMetaInfo('currencies')">
                                </i>
                            </li>

                            <slot name="extra-navigation"></slot>
                        </ul>
                    </div>

                    <div class="wrapper" v-else-if="subCategory">
                        <div class="drawer-section">
                            <i class="rango-arrow-left fs24 text-down-4" @click="toggleSubcategories('root')"></i>

                            <h4 class="display-inbl" v-text="subCategory.name"></h4>

                            <i class="material-icons float-right text-dark" @click="closeDrawer()">
                                cancel
                            </i>
                        </div>

                        <ul type="none">
                            <li
                                :key="index"
                                v-for="(nestedSubCategory, index) in subCategory.children">

                                <a
                                    class="unset"
                                    :href="`${$root.baseUrl}/${subCategory.slug}/${nestedSubCategory.slug}`">

                                    <div class="category-logo">
                                        <img
                                            class="category-icon"
                                            v-if="nestedSubCategory.category_icon_path"
                                            :src="`${$root.baseUrl}/storage/${nestedSubCategory.category_icon_path}`" alt="" width="20" height="20" />
                                    </div>
                                    <span v-text="nestedSubCategory.name"></span>
                                </a>

                                <ul
                                    type="none"
                                    class="nested-category"
                                    v-if="nestedSubCategory.children && nestedSubCategory.children.length > 0">

                                    <li
                                        :key="`index-${Math.random()}`"
                                        v-for="(thirdLevelCategory, index) in nestedSubCategory.children">
                                        <a
                                            class="unset"
                                            :href="`${$root.baseUrl}/${subCategory.slug}/${nestedSubCategory.slug}/${thirdLevelCategory.slug}`">

                                            <div class="category-logo">
                                                <img
                                                    class="category-icon"
                                                    v-if="thirdLevelCategory.category_icon_path"
                                                    :src="`${$root.baseUrl}/storage/${thirdLevelCategory.category_icon_path}`" alt="" width="20" height="20" />
                                            </div>
                                            <span v-text="thirdLevelCategory.name"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="wrapper" v-else-if="languages">
                        <div class="drawer-section">
                            <i class="rango-arrow-left fs24 text-down-4" @click="toggleMetaInfo('languages')"></i>
                            <h4 class="display-inbl" v-text="__('responsive.header.languages')"></h4>
                            <i class="material-icons float-right text-dark" @click="closeDrawer()">cancel</i>
                        </div>

                        <ul type="none">
                            <li v-for="(locale, index) in allLocales" :key="index">
                                <a
                                    class="unset"
                                    :href="`?locale=${locale.code}`">
                                    <div class="category-logo">
                                        <img
                                            class="category-icon"
                                            :src="`${$root.baseUrl}/themes/velocity/assets/images/flags/en.png`" alt=""
                                            width="20" height="20" v-if="locale.code == 'en'" />

                                        <img
                                            class="category-icon"
                                            :src="`${$root.baseUrl}/storage/${locale.locale_image}`" alt=""
                                            width="20" height="20" v-else />
                                    </div>

                                    <span v-text="locale.name"></span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="wrapper" v-else-if="currencies">
                        <div class="drawer-section">
                            <i class="rango-arrow-left fs24 text-down-4" @click="toggleMetaInfo('currencies')"></i>
                            <h4 class="display-inbl" v-text="__('shop.general.currencies')"></h4>
                            <i class="material-icons float-right text-dark" @click="closeDrawer()">cancel</i>
                        </div>

                        <ul type="none">
                            <li v-for="(currency, index) in allCurrencies" :key="index">
                                <a
                                    class="unset"
                                    :href="`?currency=${currency.code}`">
                                    <span v-text="currency.code"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="hamburger-wrapper" @click="toggleHamburger">
                    <i class="rango-toggle hamburger"></i>
                </div>

                <a class="left" href="{{ route('shop.home.index') }}" aria-label="Logo">
                    <img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt="" />
                </a>
            </div>

            <div class="right-vc-header col-6">

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

                <a class="unset cursor-pointer" @click="openSearchBar">
                    <i class="material-icons">search</i>
                </a>

                <a href="{{ route('shop.checkout.cart.index') }}" class="unset">
                    <i class="material-icons text-down-3">shopping_cart</i>

                    <div class="badge-wrapper">
                        <span class="badge" v-text="cartItemsCount"></span>
                    </div>
                </a>
            </div>

            <div class="right searchbar" v-if="isSearchbar">
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
            </div>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('mobile-header', {
                template: '#mobile-header-template',
                props: [
                    'heading',
                    'headerContent',
                    'categoryCount',
                ],

                data: function () {
                    return {
                        'compareCount': 0,
                        'wishlistCount': 0,
                        'languages': false,
                        'hamburger': false,
                        'currencies': false,
                        'subCategory': null,
                        'isSearchbar': false,
                        'rootCategories': true,
                        'cartItemsCount': '{{ $cartItemsCount }}',
                        'rootCategoriesCollection': this.$root.sharedRootCategories,
                        'isCustomer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                        'locale': @json($locale),
                        'currency': @json($currency),
                        'allLocales': @json($allLocales),
                        'allCurrencies': @json($allCurrency),
                    }
                },

                watch: {
                    hamburger: function (value) {
                        if (value) {
                            document.body.classList.add('open-hamburger');
                        } else {
                            document.body.classList.remove('open-hamburger');
                        }
                    },

                    '$root.headerItemsCount': function () {
                        this.updateHeaderItemsCount();
                    },

                    '$root.miniCartKey': function () {
                        this.getMiniCartDetails();
                    },

                    '$root.sharedRootCategories': function (categories) {
                        this.formatCategories(categories);
                    }
                },

                created: function () {
                    console.log(this.allCurrencies, this.allLocales);

                    this.getMiniCartDetails();
                    this.updateHeaderItemsCount();
                },

                methods: {
                    openSearchBar: function () {
                        this.isSearchbar = !this.isSearchbar;

                        let footer = $('.footer');
                        let homeContent = $('#home-right-bar-container');

                        if (this.isSearchbar) {
                            footer[0].style.opacity = '.3';
                            homeContent[0].style.opacity = '.3';
                        } else {
                            footer[0].style.opacity = '1';
                            homeContent[0].style.opacity = '1';
                        }
                    },

                    toggleHamburger: function () {
                        this.hamburger = !this.hamburger;
                    },

                    closeDrawer: function() {
                        $('.nav-container').hide();

                        this.toggleHamburger();
                        this.rootCategories = true;
                    },

                    toggleSubcategories: function (index, event) {
                        if (index == "root") {
                            this.rootCategories = true;
                            this.subCategory = false;
                        } else {
                            event.preventDefault();

                            let categories = this.$root.sharedRootCategories;
                            this.rootCategories = false;
                            this.subCategory = categories[index];
                        }
                    },

                    toggleMetaInfo: function (metaKey) {
                        this.rootCategories = ! this.rootCategories;

                        this[metaKey] = !this[metaKey];
                    },

                    updateHeaderItemsCount: function () {
                        if (! this.isCustomer) {
                            let comparedItems = this.getStorageValue('compared_product');

                            if (comparedItems) {
                                this.compareCount = comparedItems.length;
                            }
                        } else {
                            this.$http.get(`${this.$root.baseUrl}/items-count`)
                                .then(response => {
                                    this.compareCount = response.data.compareProductsCount;
                                    this.wishlistCount = response.data.wishlistedProductsCount;
                                })
                                .catch(exception => {
                                    console.log(this.__('error.something_went_wrong'));
                                });
                        }
                    },

                    getMiniCartDetails: function () {
                        this.$http.get(`${this.$root.baseUrl}/mini-cart`)
                        .then(response => {
                            if (response.data.status) {
                                this.cartItemsCount = response.data.mini_cart.cart_items.length;
                            }
                        })
                        .catch(exception => {
                            console.log(this.__('error.something_went_wrong'));
                        });
                    },

                    formatCategories: function (categories) {
                        let slicedCategories = categories;
                        let categoryCount = this.categoryCount ? this.categoryCount : 9;

                        if (
                            slicedCategories
                            && slicedCategories.length > categoryCount
                        ) {
                            slicedCategories = categories.slice(0, categoryCount);
                        }

                        this.rootCategoriesCollection = slicedCategories;
                    },
                },
            });
        })();
    </script>
@endpush