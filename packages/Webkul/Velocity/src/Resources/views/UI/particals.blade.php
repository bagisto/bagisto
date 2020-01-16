<script type="text/x-template" id="star-ratings-template">
    <div :class="`stars mr5 fs${size ? size : '16'} ${pushClass ? pushClass : ''}`">
        <input
            v-if="editable"
            type="number"
            :value="showFilled"
            name="rating"
            class="hidden" />

        <i
            :class="`material-icons ${editable ? 'cursor-pointer' : ''}`"
            v-for="(rating, index) in parseInt(showFilled ? showFilled : 3)"
            :key="`${index}${Math.random()}`"
            @click="updateRating(index + 1)">
            star
        </i>

        <template v-if="!hideBlank">
            <i
                :class="`material-icons ${editable ? 'cursor-pointer' : ''}`"
                v-for="(blankStar, index) in (5 - (showFilled ? showFilled : 3))"
                :key="`${index}${Math.random()}`"
                @click="updateRating(showFilled + index + 1)">
                star_border
            </i>
        </template>

        {{-- <i class="material-icons">star_border</i>
        <i class="material-icons">star</i> --}}
    </div>
</script>

<script type="text/x-template" id="cart-btn-template">
    <button
        type="button"
        id="mini-cart"
        @click="toggleMiniCart"
        class="btn btn-link disable-box-shadow">

        <div class="mini-cart-content">
            <i class="icon fs16 cell rango-arrow-down down-icon-position down-arrow-margin"></i>
            <i class="material-icons-outlined text-down-3">shopping_cart</i>
            <span class="badge" v-text="itemCount"></span>
            <span class="fs18 fw6 cart-text">{{ __('velocity::app.minicart.cart') }}</span>
        </div>
    </button>
</script>

<script type="text/x-template" id="close-btn-template">
    <button type="button" class="close disable-box-shadow">
        <span class="white-text fs20" @click="togglePopup">×</span>
    </button>
</script>

<script type="text/x-template" id="quantity-changer-template">
    <div class="quantity control-group" :class="[errors.has(controlName) ? 'has-error' : '']">
        <label class="required">{{ __('shop::app.products.quantity') }}</label>

        <button type="button" class="decrease" @click="decreaseQty()">-</button>

        <input :name="controlName" class="control" :value="qty" :v-validate="validations" data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;" readonly>

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
        <div class="col-8 no-padding input-group">
            <form
                method="GET"
                role="search"
                id="search-form"
                action="{{ route('shop.search.index') }}">

                <div
                    class="btn-toolbar full-width"
                    role="toolbar">

                    <div class="btn-group full-width">
                        <div class="selectdiv">
                            <select class="form-control fs13 border-right-0" name="category">
                                <option value="">
                                    {{ __('velocity::app.header.all-categories') }}
                                </option>

                                @foreach ($categories as $category)
                                    <option
                                        selected="selected"
                                        value="{{ $category->id }}"
                                        v-if="({{ $category->id }} == searchedQuery.category)">
                                        {{ $category->name }}
                                    </option>

                                    <option
                                        v-else
                                        value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                                <span class="select-icon rango-arrow-down"></span>
                            </select>
                        </div>

                        <div class="full-width">

                            <input
                                required
                                name="term"
                                type="search"
                                class="form-control"
                                :value="searchedQuery.term"
                                placeholder="{{ __('velocity::app.header.search-text') }}" />

                            <button class="btn" type="submit" id="header-search-icon">
                                <i class="fs16 fw6 rango-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-4">
            {!! view_render_event('bagisto.shop.layout.header.cart-item.before') !!}

                @include('shop::checkout.cart.mini-cart')

            {!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
        </div>
    </div>
</script>

<script type="text/x-template" id="content-header-template">

    <header class="row velocity-divide-page remove-padding-margin vc-header active">
        <div class="vc-small-screen container">
            <div class="row">
                <div class="col-8">
                    <div class="row col-12">

                        {{-- @include('shop::UI.shared.responsive-sidebar') --}}

                        <div class="hamburger-wrapper active" v-on:click="toggleHamburger">
                            <i class="rango-toggle hamburger"></i>
                        </div>
                        <logo-component add-class="ml30"></logo-component>
                    </div>
                </div>
                <div class="row right-vc-header col-4">
                    <a :href="`${url}/customer/account/wishlist`" class="unset">
                        <i class="material-icons">favorite_border</i>
                    </a>

                    <a class="unset cursor-pointer" @click="openSearchBar">
                        <i class="material-icons">search</i>
                    </a>

                    <a href="" class="unset">
                        <i class="material-icons text-down-3">shopping_cart</i>
                    </a>
                </div>

                <searchbar-component v-if="isSearchbar"></searchbar-component>
            </div>
        </div>

        <div
            @mouseout="toggleSidebar('0', $event, 'mouseout')"
            @mouseover="toggleSidebar('0', $event, 'mouseover')"
            class="main-category fs16 unselectable fw6 cursor-pointer left">

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
                        :href="content['page_link']"
                        v-if="(content['content_type'] == 'link')"
                        :target="content['link_target'] ? '_blank' : '_self'">
                    </a>

                    <a href="#" v-else v-text="content.title"></a>
                </li>
            </ul>
        </div>
    </header>
</script>

<script type="text/javascript">
    (() => {
        Vue.component('star-ratings', {
            props: [
                'ratings',
                'size',
                'hideBlank',
                'pushClass',
                'editable'
            ],

            template: '#star-ratings-template',

            data: function () {
                return {
                    showFilled: this.ratings,
                }
            },

            methods: {
                updateRating: function (index) {
                    index = Math.abs(index);

                    this.editable ? this.showFilled = index : '';

                    this.showFilled
                }
            },
        })

        Vue.component('cart-btn', {
            template: '#cart-btn-template',

            props: ['itemCount'],

            methods: {
                toggleMiniCart: function () {
                    let modal = $('#cart-modal-content')[0];
                    if (modal)
                        modal.classList.toggle('hide');

                    event.stopPropagation();
                }
            }
        })

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
                    searchedQuery: []
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
            }
        })

        Vue.component('content-header', {
            template: '#content-header-template',
            props: [
                'url',
                'heading',
                'isEnabled',
                'headerContent',
            ],

            data: function () {
                return {
                    'isSearchbar': false
                }
            },

            methods: {
                'openSearchBar': function () {
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
                    // element.classList.add("active");
                }
            }
        })
    })()
</script>