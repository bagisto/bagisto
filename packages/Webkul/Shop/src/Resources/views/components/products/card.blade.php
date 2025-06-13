<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
    >
        <!-- Grid Card -->
        <div
            class="group relative"
            v-if="mode != 'list'"
        >
            <div class="relative overflow-hidden rounded-md bg-zylver-cream">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <!-- Product Image -->
                <a
                    :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`"
                    :aria-label="product.name"
                >
                    <x-shop::media.images.lazy
                        class="aspect-[1/1] w-full transition-transform duration-300 group-hover:scale-105"
                        ::src="product.base_image.medium_image_url"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="291"
                        ::alt="product.name"
                    />
                </a>

                <!-- Wishlist Icon -->
                @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                    <span
                        class="absolute top-3 right-3 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-white/80 text-xl transition-all hover:bg-white"
                        role="button"
                        aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                        tabindex="0"
                        :class="product.is_wishlist ? 'icon-heart-fill text-zylver-gold' : 'icon-heart'"
                        @click="addToWishlist()"
                    >
                    </span>
                @endif

                <!-- Badges -->
                <div class="absolute top-3 left-3">
                    <p class="rounded-full bg-zylver-gold px-3 py-1 text-xs font-medium text-zylver-olive-green" v-if="product.on_sale">
                        @lang('shop::app.components.products.card.sale')
                    </p>
                    <p class="rounded-full bg-zylver-olive-green px-3 py-1 text-xs font-medium text-zylver-cream" v-else-if="product.is_new">
                        @lang('shop::app.components.products.card.new')
                    </p>
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="mt-4 text-center">
                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <a
                    :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`"
                    :aria-label="product.name"
                    class="block font-lato text-base text-zylver-olive-green hover:text-zylver-gold"
                >
                    @{{ product.name }}
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="mt-1 font-lato text-lg font-semibold text-zylver-olive-green"
                    v-html="product.price_html"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Quick View (Placeholder for future implementation) -->
                <div class="mt-2 opacity-0 transition-opacity group-hover:opacity-100">
                    <button
                        type="button"
                        class="font-lato text-sm text-zylver-olive-green/80 hover:text-zylver-gold"
                        @click="openQuickView()"
                    >
                        Quick View
                    </button>
                </div>
            </div>
        </div>

        <!-- List Card -->
        <div
            class="relative flex max-w-max grid-cols-2 gap-4 overflow-hidden rounded max-sm:flex-wrap"
            v-else
        >
            <div class="group relative max-h-[258px] max-w-[250px] overflow-hidden"> 

                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`">
                    <x-shop::media.images.lazy
                        class="after:content-[' '] relative min-w-[250px] bg-zinc-100 transition-all duration-300 after:block after:pb-[calc(100%+9px)] group-hover:scale-105"
                        ::src="product.base_image.medium_image_url"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="300"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <div class="action-items bg-black">
                    <p
                        class="absolute top-5 inline-block rounded-[44px] bg-zylver-gold px-2.5 text-sm text-zylver-olive-green font-lato ltr:left-5 max-sm:ltr:left-2 rtl:right-5"
                        v-if="product.on_sale"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </p>

                    <p
                        class="absolute top-5 inline-block rounded-[44px] bg-zylver-olive-green px-2.5 text-sm text-zylver-cream font-lato ltr:left-5 max-sm:ltr:left-2 rtl:right-5"
                        v-else-if="product.is_new"
                    >
                        @lang('shop::app.components.products.card.new')
                    </p>

                    <div class="opacity-0 transition-all duration-300 group-hover:bottom-0 group-hover:opacity-100 max-sm:opacity-100">

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                            <span 
                                class="absolute top-5 flex h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md bg-zylver-white text-2xl ltr:right-5 rtl:left-5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                tabindex="0"
                                :class="product.is_wishlist ? 'icon-heart-fill text-zylver-gold' : 'icon-heart'"
                                @click="addToWishlist()"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                        @if (core()->getConfigData('catalog.products.settings.compare_option'))
                            <span
                                class="icon-compare absolute top-16 flex h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md bg-zylver-white text-2xl ltr:right-5 rtl:left-5"
                                role="button"
                                aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                                tabindex="0"
                                @click="addToCompare(product.id)"
                            >
                            </span>
                        @endif

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}
                    </div>
                </div>
            </div>

            <div class="grid content-start gap-4">

                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <p class="text-base">
                    @{{ product.name }}
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div
                    class="flex gap-2.5 text-lg font-semibold"
                    v-html="product.price_html"
                >
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Needs to implement that in future -->
                <div class="flex hidden gap-4">
                    <span class="block h-[30px] w-[30px] rounded-full bg-[#B5DCB4]">
                    </span>

                    <span class="block h-[30px] w-[30px] rounded-full bg-zinc-500">
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                <p class="text-sm text-zinc-500">
                    <template  v-if="! product.ratings.total">
                        <p class="text-sm text-zinc-500">
                            @lang('shop::app.components.products.card.review-description')
                        </p>
                    </template>

                    <template v-else>
                        @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                            <x-shop::products.ratings
                                ::average="product.ratings.average"
                                ::total="product.ratings.total"
                                ::rating="false"
                            />
                        @else
                            <x-shop::products.ratings
                                ::average="product.ratings.average"
                                ::total="product.reviews.total"
                                ::rating="false"
                            />
                        @endif
                    </template>
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                    <x-shop::button
                        class="primary-button whitespace-nowrap px-8 py-2.5"
                        :title="trans('shop::app.components.products.card.add-to-cart')"
                        ::loading="isAddingToCart"
                        ::disabled="! product.is_saleable || isAddingToCart"
                        @click="addToCart()"
                    />

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}

                @endif
            </div>
        </div>
    </script>

        <!-- Quick View Modal -->
        <div
            v-if="isQuickViewOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4"
            @click.self="closeQuickView" <!-- Close on overlay click -->
        >
            <div class="relative w-full max-w-4xl rounded-lg bg-zylver-white p-6 shadow-xl max-h-[90vh] overflow-y-auto md:p-8">
                <!-- Close Button -->
                <button
                    class="absolute top-4 right-4 z-10 text-2xl text-zylver-olive-green/70 hover:text-zylver-olive-green"
                    @click="closeQuickView"
                    aria-label="Close Quick View"
                >
                    <span class="icon-cancel"></span>
                </button>

                <!-- Modal Content (Static Placeholder) -->
                <div v-if="product" class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8">
                    <!-- Image Placeholder -->
                    <div class="aspect-square w-full rounded-md bg-zylver-cream">
                        <img v-if="product.base_image && product.base_image.large_image_url" :src="product.base_image.large_image_url" :alt="product.name" class="h-full w-full rounded-md object-cover"/>
                        <div v-else class="flex h-full w-full items-center justify-center">
                            <span class="icon-placeholder text-6xl text-zylver-olive-green/30"></span>
                        </div>
                    </div>

                    <!-- Info Placeholder -->
                    <div>
                        <h2 class="font-fraunces text-2xl text-zylver-olive-green md:text-3xl">
                            @{{ product.name }}
                        </h2>

                        <div class="mt-2 font-lato text-xl font-semibold text-zylver-olive-green md:text-2xl" v-html="product.price_html">
                        </div>

                        <p class="mt-4 font-lato text-sm text-zylver-olive-green/80">
                            <!-- Placeholder for short description. Actual description will require more advanced data fetching or passing. -->
                            Discover the elegance of this exquisite piece, crafted with the finest materials and attention to detail.
                        </p>

                        <!-- Options/Variants Placeholder -->
                        <div class="mt-6">
                            <h3 class="font-fraunces text-lg text-zylver-olive-green">Options</h3>
                            <div class="mt-2 space-y-2">
                                <p class="font-lato text-sm text-zylver-olive-green/80">Size: Default (More options on product page)</p>
                                <p class="font-lato text-sm text-zylver-olive-green/80">Material: As Described (More options on product page)</p>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            class="primary-button mt-6 w-full bg-zylver-olive-green py-3 text-zylver-cream hover:bg-zylver-gold hover:text-zylver-olive-green"
                            @click="addToCart()"
                            :disabled="!product.is_saleable || isAddingToCart"
                        >
                            <template v-if="isAddingToCart">
                                Adding...
                            </template>
                            <template v-else>
                                Add to Cart
                            </template>
                        </button>

                        <a
                            :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`"
                            class="mt-3 block text-center font-lato text-sm text-zylver-olive-green/80 hover:text-zylver-gold"
                        >
                            View Full Details
                        </a>
                    </div>
                </div>
            </div>
        </div>


    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    isAddingToCart: false,

                    isQuickViewOpen: false,
                }
            },

            methods: {

                openQuickView() {
                    this.isQuickViewOpen = true;
                    // Optional: Prevent body scroll when modal is open
                    document.body.style.overflow = 'hidden';
                },

                closeQuickView() {
                    this.isQuickViewOpen = false;
                    // Optional: Restore body scroll
                    document.body.style.overflow = '';
                },

                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = ! this.product.is_wishlist;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index')}}";
                        }
                },

                addToCompare(productId) {
                    /**
                     * This will handle for customers.
                     */
                    if (this.isCustomer) {
                        this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                'product_id': productId
                            })
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                if ([400, 422].includes(error.response.status)) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                    return;
                                }

                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                            });

                        return;
                    }

                    /**
                     * This will handle for guests.
                     */
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));

                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data );

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }
                            
                            this.isAddingToCart = false;
                        });
                },
            },
        });
    </script>
@endpushOnce