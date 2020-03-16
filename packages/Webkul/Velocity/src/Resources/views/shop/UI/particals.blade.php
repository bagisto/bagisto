<script type="text/x-template" id="cart-btn-template">
    <button
        type="button"
        id="mini-cart"
        @click="toggleMiniCart"
        :class="`btn btn-link disable-box-shadow ${itemCount == 0 ? 'cursor-not-allowed' : ''}`">

        <div class="mini-cart-content">
            <i class="material-icons-outlined text-down-3">shopping_cart</i>
            <span class="badge" v-text="itemCount" v-if="itemCount != 0"></span>
            <span class="fs18 fw6 cart-text">{{ __('velocity::app.minicart.cart') }}</span>
        </div>
        <div class="down-arrow-container">
            <span class="rango-arrow-down"></span>
        </div>
    </button>
</script>

<script type="text/x-template" id="close-btn-template">
    <button type="button" class="close disable-box-shadow">
        <span class="white-text fs20" @click="togglePopup">×</span>
    </button>
</script>

<script type="text/x-template" id="quantity-changer-template">
    <div :class="`quantity control-group ${errors.has(controlName) ? 'has-error' : ''}`">
        <label class="required">{{ __('shop::app.products.quantity') }}</label>
        <button type="button" class="decrease" @click="decreaseQty()">-</button>

        <input
            :value="qty"
            class="control"
            :name="controlName"
            :v-validate="validations"
            data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;"
            readonly />

        <button type="button" class="increase" @click="increaseQty()">+</button>

        <span class="control-error" v-if="errors.has(controlName)">@{{ errors.first(controlName) }}</span>
    </div>
</script>

<script type="text/x-template" id="logo-template">
    <a
        :class="`left ${addClass}`"
        href="{{ route('shop.home.index') }}">

        @if ($logo = core()->getCurrentChannel()->logo_url)
            <img class="logo" src="{{ $logo }}" />
        @else
            <img class="logo" src="{{ asset('themes/velocity/assets/images/logo-text.png') }}" />
        @endif
    </a>
</script>

<script type="text/x-template" id="searchbar-template">
    <div class="row no-margin right searchbar">
        <div class="col-lg-6 col-md-12 no-padding input-group">
            <form
                method="GET"
                role="search"
                id="search-form"
                action="{{ route('velocity.search.index') }}">

                <div
                    class="btn-toolbar full-width"
                    role="toolbar">

                    <div class="btn-group full-width">
                        <div class="selectdiv">
                            <select class="form-control fs13 styled-select" name="category" @change="focusInput($event)">
                                <option value="">
                                    {{ __('velocity::app.header.all-categories') }}
                                </option>

                                <template v-for="(category, index) in $root.sharedRootCategories">
                                    <option
                                        :key="index"
                                        selected="selected"
                                        :value="category.id"
                                        v-if="(category.id == searchedQuery.category)">
                                        @{{ category.name }}
                                    </option>

                                    <option :key="index" :value="category.id" v-else>
                                        @{{ category.name }}
                                    </option>
                                </template>
                            </select>

                            <div class="select-icon-container">
                                <span class="select-icon rango-arrow-down"></span>
                            </div>
                        </div>

                        <div class="full-width">

                            <input
                                required
                                name="term"
                                type="search"
                                class="form-control"
                                :value="searchedQuery.term ? searchedQuery.term.split('+').join(' ') : ''"
                                placeholder="{{ __('velocity::app.header.search-text') }}" />

                            <button class="btn" type="submit" id="header-search-icon">
                                <i class="fs16 fw6 rango-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-6">
            {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}
                @include('shop::checkout.cart.mini-cart')
            {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}

            {!! view_render_event('bagisto.shop.layout.header.compare.before') !!}
                <a class="compare-btn unset" href="{{ route('velocity.product.compare') }}">
                    <i class="material-icons">compare_arrows</i>
                    <div class="badge-container" v-if="compareCount > 0">
                        <span class="badge" v-text="compareCount"></span>
                    </div>
                    <span>{{ __('velocity::app.customer.compare.text') }}</span>
                </a>
            {!! view_render_event('bagisto.shop.layout.header.compare.after') !!}

            {!! view_render_event('bagisto.shop.layout.header.wishlist.before') !!}
                <a class="wishlist-btn unset" :href="`${isCustomer ? '{{ route('customer.wishlist.index') }}' : '{{ route('velocity.product.guest-wishlist') }}'}`">
                    <i class="material-icons">favorite_border</i>
                    <div class="badge-container" v-if="wishlistCount > 0">
                        <span class="badge" v-text="wishlistCount"></span>
                    </div>
                    <span>{{ __('shop::app.layouts.wishlist') }}</span>
                </a>
            {!! view_render_event('bagisto.shop.layout.header.wishlist.after') !!}
        </div>
    </div>
</script>

<script type="text/x-template" id="content-header-template">
    <header class="row velocity-divide-page vc-header header-shadow active">
        <div class="vc-small-screen container" v-if="isMobile()">
            <div class="row">
                <div class="col-6">
                    <div v-if="hamburger" class="nav-container scrollable">
                        <div class="wrapper" v-if="this.rootCategories">
                            <div class="greeting drawer-section fw6">
                                <i class="material-icons">perm_identity</i>
                                <span>
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
                                    <i
                                        class="material-icons pull-right"
                                        @click="closeDrawer()">
                                        cancel
                                    </i>
                                </span>
                            </div>

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

                            <ul type="none" class="velocity-content">
                                <li :key="index" v-for="(content, index) in headerContent">
                                    <a
                                        class="unset"
                                        v-text="content.title"
                                        :href="`${$root.baseUrl}/${content.page_link}`">
                                    </a>
                                </li>
                            </ul>

                            <ul type="none" class="category-wrapper">
                                <li v-for="(category, index) in $root.sharedRootCategories">
                                    <a class="unset" :href="`${$root.baseUrl}/${category.slug}`">
                                        <div class="category-logo">
                                            <img
                                                class="category-icon"
                                                v-if="category.category_icon_path"
                                                :src="`${$root.baseUrl}/storage/${category.category_icon_path}`" />
                                        </div>
                                        <span v-text="category.name"></span>
                                    </a>

                                    <i class="rango-arrow-right" @click="toggleSubcategories(index, $event)"></i>
                                </li>
                            </ul>

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

                                    <li>
                                        <a href="{{ route('customer.wishlist.index') }}" class="unset">
                                            <i class="icon wishlist text-down-3"></i>
                                            <span>{{ __('shop::app.header.wishlist') }}</span>
                                        </a>
                                    </li>

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

                            <ul type="none" class="meta-wrapper">
                                <li>
                                    <div class="language-logo-wrapper">
                                        @if ($locale->locale_image)
                                            <img
                                                class="language-logo"
                                                src="{{ asset('/storage/' . $locale->locale_image) }}" />
                                        @elseif ($locale->code == "en")
                                            <img
                                                class="language-logo"
                                                src="{{ asset('/themes/velocity/assets/images/flags/en.png') }}" />
                                        @endif
                                    </div>
                                    <span>{{ $locale->name }}</span>

                                    <i
                                        class="rango-arrow-right"
                                        @click="toggleMetaInfo('languages')">
                                    </i>
                                </li>

                                <li>
                                    <span>{{ $currency->code }}</span>

                                    <i
                                        class="rango-arrow-right"
                                        @click="toggleMetaInfo('currencies')">
                                    </i>
                                </li>

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
                            </ul>
                        </div>

                        <div class="wrapper" v-else-if="subCategory">
                            <div class="drawer-section">
                                <i class="rango-arrow-left fs24 text-down-4" @click="toggleSubcategories('root')"></i>

                                <h4 class="display-inbl">@{{ subCategory.name }}</h4>

                                <i class="material-icons pull-right" @click="closeDrawer()">
                                    cancel
                                </i>
                            </div>

                            <ul type="none">
                                <li
                                    :key="index"
                                    v-for="(nestedSubCategory, index) in subCategory.children">

                                    <a
                                        class="unset"
                                        :href="`${$root.baseUrl}/${nestedSubCategory.slug}`">

                                        <div class="category-logo">
                                            <img
                                                class="category-icon"
                                                v-if="nestedSubCategory.category_icon_path"
                                                :src="`${$root.baseUrl}/storage/${nestedSubCategory.category_icon_path}`" />
                                        </div>
                                        <span>@{{ nestedSubCategory.name }}</span>
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
                                                :href="`${$root.baseUrl}/${nestedSubCategory.slug}`">

                                                <div class="category-logo">
                                                    <img
                                                        class="category-icon"
                                                        v-if="thirdLevelCategory.category_icon_path"
                                                        :src="`${$root.baseUrl}/storage/${thirdLevelCategory.category_icon_path}`" />
                                                </div>
                                                <span>@{{ thirdLevelCategory.name }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <div class="wrapper" v-else-if="languages">
                            <div class="drawer-section">
                                <i class="rango-arrow-left fs24 text-down-4" @click="toggleSubcategories('root')"></i>
                                <h4 class="display-inbl">Languages</h4>
                                <i class="material-icons pull-right" @click="closeDrawer()">cancel</i>
                            </div>

                            <ul type="none">
                                @foreach ($allLocales as $locale)
                                    <li>
                                        <a
                                            class="unset"
                                            @if (isset($serachQuery))
                                                href="?{{ $serachQuery }}&locale={{ $locale->code }}"
                                            @else
                                                href="?locale={{ $locale->code }}"
                                            @endif>

                                            <div class="category-logo">
                                                <img
                                                    class="category-icon"
                                                    src="{{ asset('/storage/' . $locale->locale_image) }}" />
                                            </div>

                                            <span>
                                                {{ isset($serachQuery) ? $locale->title : $locale->name }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="wrapper" v-else-if="currencies">
                            <div class="drawer-section">
                                <i class="rango-arrow-left fs24 text-down-4" @click="toggleSubcategories('root')"></i>
                                <h4 class="display-inbl">Currencies</h4>
                                <i class="material-icons pull-right" @click="closeDrawer()">cancel</i>
                            </div>

                            <ul type="none">
                                @foreach ($allCurrency as $currency)
                                    <li>
                                        @if (isset($serachQuery))
                                            <a
                                                class="unset"
                                                href="?{{ $serachQuery }}&locale={{ $currency->code }}">
                                                <span>{{ $currency->code }}</span>
                                            </a>
                                        @else
                                            <a
                                                class="unset"
                                                href="?locale={{ $currency->code }}">
                                                <span>{{ $currency->code }}</span>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="hamburger-wrapper" @click="toggleHamburger">
                        <i class="rango-toggle hamburger"></i>
                    </div>

                    <logo-component></logo-component>
                </div>

                <div class="right-vc-header col-6">
                    <a class="compare-btn unset" href="{{ route('velocity.product.compare') }}">
                        <div class="badge-container" v-if="compareCount > 0">
                            <span class="badge" v-text="compareCount"></span>
                        </div>
                        <i class="material-icons">compare_arrows</i>
                    </a>

                    <a class="wishlist-btn unset" :href="`${isCustomer ? '{{ route('customer.wishlist.index') }}' : '{{ route('velocity.product.guest-wishlist') }}'}`">
                        <div class="badge-container" v-if="wishlistCount > 0">
                            <span class="badge" v-text="wishlistCount"></span>
                        </div>
                        <i class="material-icons">favorite_border</i>
                    </a>

                    <a class="unset cursor-pointer" @click="openSearchBar">
                        <i class="material-icons">search</i>
                    </a>

                    @php
                        $cart = cart()->getCart();

                        $cartItemsCount = trans('shop::app.minicart.zero');
                        if ($cart) {
                            $cartItemsCount = $cart->items->count();
                        }
                    @endphp

                    <a href="{{ route('shop.checkout.cart.index') }}" class="unset">
                        <div class="badge-wrapper">
                            <span class="badge">{{ $cartItemsCount }}</span>
                        </div>
                        <i class="material-icons text-down-3">shopping_cart</i>
                    </a>
                </div>

                <searchbar-component v-if="isSearchbar"></searchbar-component>
            </div>
        </div>

        <div
            v-else
            @mouseout="toggleSidebar('0', $event, 'mouseout')"
            @mouseover="toggleSidebar('0', $event, 'mouseover')"
            :class="`main-category fs16 unselectable fw6 ${($root.sharedRootCategories.length > 0) ? 'cursor-pointer' : 'cursor-not-allowed'} left`">

            <i class="rango-view-list text-down-4 align-vertical-top fs18">
            </i>
            <span
                class="pl5"
                v-text="heading"
                @mouseover="toggleSidebar('0', $event, 'mouseover')">
            </span>
        </div>

        <div class="content-list right">
            <ul type="none" class="no-margin">
                <li v-for="(content, index) in headerContent" :key="index">
                    <a
                        v-text="content.title"
                        :href="`${$root.baseUrl}/${content['page_link']}`"
                        v-if="(content['content_type'] == 'link' || content['content_type'] == 'category')"
                        :target="content['link_target'] ? '_blank' : '_self'">
                    </a>
                </li>
            </ul>
        </div>
    </header>
</script>

<script type="text/x-template" id="sidebar-categories-template">
    <div class="wrapper" v-if="rootCategories">
        Hello World
    </div>

    <div class="wrapper" v-else-if="subCategory">
        Hello World 2
    </div>
</script>

<script type="text/javascript">
    (() => {
        Vue.component('cart-btn', {
            template: '#cart-btn-template',

            props: ['itemCount'],

            methods: {
                toggleMiniCart: function () {
                    let modal = $('#cart-modal-content')[0];
                    if (modal)
                        modal.classList.toggle('hide');

                    let accountModal = $('.account-modal')[0];
                    if (accountModal)
                        accountModal.classList.add('hide');

                    event.stopPropagation();
                }
            }
        });

        Vue.component('close-btn', {
            template: '#close-btn-template',

            methods: {
                togglePopup: function () {
                    $('#cart-modal-content').hide();
                }
            }
        });

        Vue.component('quantity-changer', {
            template: '#quantity-changer-template',
            inject: ['$validator'],
            props: {
                controlName: {
                    type: String,
                    default: 'quantity'
                },

                quantity: {
                    type: [Number, String],
                    default: 1
                },

                minQuantity: {
                    type: [Number, String],
                    default: 1
                },

                validations: {
                    type: String,
                    default: 'required|numeric|min_value:1'
                }
            },

            data: function() {
                return {
                    qty: this.quantity
                }
            },

            watch: {
                quantity: function (val) {
                    this.qty = val;

                    this.$emit('onQtyUpdated', this.qty)
                }
            },

            methods: {
                decreaseQty: function() {
                    if (this.qty > this.minQuantity)
                        this.qty = parseInt(this.qty) - 1;

                    this.$emit('onQtyUpdated', this.qty)
                },

                increaseQty: function() {
                    this.qty = parseInt(this.qty) + 1;

                    this.$emit('onQtyUpdated', this.qty)
                }
            }
        });

        Vue.component('logo-component', {
            template: '#logo-template',
            props: ['addClass'],
        });

        Vue.component('searchbar-component', {
            template: '#searchbar-template',
            data: function () {
                return {
                    compareCount: 0,
                    wishlistCount: 0,
                    searchedQuery: [],
                    isCustomer: '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
                }
            },

            watch: {
                '$root.headerItemsCount': function () {
                    this.updateHeaderItemsCount();
                }
            },

            created: function () {
                let searchedItem = window.location.search.replace("?", "");
                searchedItem = searchedItem.split('&');

                let updatedSearchedCollection = {};

                searchedItem.forEach(item => {
                    let splitedItem = item.split('=');
                    updatedSearchedCollection[splitedItem[0]] = splitedItem[1];
                });

                this.searchedQuery = updatedSearchedCollection;

                this.updateHeaderItemsCount();
            },

            methods: {
                'focusInput': function (event) {
                    $(event.target.parentElement.parentElement).find('input').focus();
                },

                'updateHeaderItemsCount': function () {
                    if (! this.isCustomer) {
                        let comparedItems = this.getStorageValue('compared_product');
                        let wishlistedItems = this.getStorageValue('wishlist_product');

                        if (wishlistedItems) {
                            this.wishlistCount = wishlistedItems.length;
                        }

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
                }
            }
        });

        Vue.component('content-header', {
            template: '#content-header-template',
            props: [
                'heading',
                'headerContent',
            ],

            data: function () {
                return {
                    'languages': false,
                    'hamburger': false,
                    'currencies': false,
                    'subCategory': null,
                    'isSearchbar': false,
                    'rootCategories': true,
                    'isCustomer': '{{ auth()->guard('customer')->user() ? "true" : "false" }}' == "true",
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
                }
            },

            created: function () {
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
                    } else {
                        event.preventDefault();

                        let categories = this.$root.sharedRootCategories;
                        this.rootCategories = false;
                        this.subCategory = categories[index];
                    }
                },

                toggleMetaInfo: function (metaKey) {
                    this.rootCategories = false;
                    this[metaKey] = !this[metaKey];
                },

                updateHeaderItemsCount: function () {
                    if (! this.isCustomer) {
                        let comparedItems = this.getStorageValue('compared_product');
                        let wishlistedItems = this.getStorageValue('wishlist_product');

                        if (wishlistedItems) {
                            this.wishlistCount = wishlistedItems.length;
                        }

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
                }
            },
        });
    })()
</script>