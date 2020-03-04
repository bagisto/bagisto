@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.customer.account.wishlist.page-title') }}
@endsection

@section('content-wrapper')
    @guest('customer')
        <wishlist-product></wishlist-product>
    @endguest

    @auth('customer')
        @push('scripts')
            <script>
                window.location = '{{ route('customer.wishlist.index') }}';
            </script>
        @endpush
    @endauth
@endsection

@push('scripts')
    <script type="text/x-template" id="wishlist-product-template">
        <section class="cart-details row no-margin col-12">
            <h1 class="fw6 col-6">
                {{ __('shop::app.customer.account.wishlist.title') }}
            </h1>

            <div class="col-6" v-if="products.length > 0">
                <button
                    class="theme-btn light pull-right"
                    @click="removeProduct('all')">
                    {{ __('shop::app.customer.account.wishlist.deleteall') }}
                </button>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.guest-customer.view.before') !!}

            <div class="row products-collection col-12 ml0">
                <carousel-component
                    slides-per-page="6"
                    navigation-enabled="hide"
                    pagination-enabled="hide"
                    id="wishlist-products-carousel"
                    :slides-count="products.length">

                    <slide
                        :key="index"
                        :slot="`slide-${index}`"
                        v-for="(product, index) in products">
                        <product-card :product="product"></product-card>
                    </slide>
                </carousel-component>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.guest-customer.view.after') !!}
        </section>
    </script>

    <script>
        Vue.component('wishlist-product', {
            template: '#wishlist-product-template',

            data: function () {
                return {
                    'products': [],
                    'isProductListLoaded': false,
                }
            },

            mounted: function () {
                this.getProducts();
            },

            methods: {
                'getProducts': function () {
                    let items = JSON.parse(window.localStorage.getItem('wishlist_product'));
                    items = items ? items.join('&') : '';

                    var data = {
                        params: {
                            items,
                            data: true,
                        }
                    };

                    this.$http.get(`${this.$root.baseUrl}/comparison`, data)
                    .then(response => {
                        this.isProductListLoaded = true;
                        this.products = response.data.products;
                    })
                    .catch(error => {
                        console.log(this.__('error.something_went_wrong'));
                    });
                },

                'removeProduct': function (productId) {
                    if (this.isCustomer) {
                        this.$http.delete(`${this.$root.baseUrl}/comparison?productId=${productId}`)
                        .then(response => {
                            if (productId == 'all') {
                                this.$set(this, 'products', this.products.filter(product => false));
                            } else {
                                this.$set(this, 'products', this.products.filter(product => product.id != productId));
                            }

                            window.showAlert(`alert-${response.data.status}`, response.data.label, response.data.message);
                        })
                        .catch(error => {
                            console.log(this.__('error.something_went_wrong'));
                        });
                    } else {
                        let existingItems = window.localStorage.getItem('compared_product');
                        existingItems = JSON.parse(existingItems);

                        if (productId == "all") {
                            updatedItems = [];
                            this.$set(this, 'products', []);
                        } else {
                            updatedItems = existingItems.filter(item => item != productId);
                            this.$set(this, 'products', this.products.filter(product => product.slug != productId));
                        }

                        window.localStorage.setItem('compared_product', JSON.stringify(updatedItems));

                        window.showAlert(
                            `alert-success`,
                            this.__('shop.general.alert.success'),
                            `${this.__('customer.compare.removed')}`
                        );
                    }
                },
            }
        });
    </script>
@endpush