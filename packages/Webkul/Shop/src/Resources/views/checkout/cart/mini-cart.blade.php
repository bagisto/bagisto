<!-- Mini Cart Vue Component -->
<v-mini-cart>
    <span
        class="icon-cart cursor-pointer text-2xl"
        role="button"
        aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
    ></span>
</v-mini-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mini-cart-template"
    >
        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

        @if (core()->getConfigData('sales.checkout.mini_cart.display_mini_cart'))
            <x-shop::drawer>
                <!-- Drawer Toggler -->
                <x-slot:toggle>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                    <span class="relative">
                        <span
                            class="icon-cart cursor-pointer text-2xl"
                            role="button"
                            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                            tabindex="0"
                        ></span>

                        @if (core()->getConfigData('sales.checkout.my_cart.summary') == 'display_item_quantity')
                            <span
                                class="absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5 max-md:ltr:left-4 max-md:rtl:right-4"
                                v-if="cart?.items_qty"
                            >
                                @{{ cart.items_qty }}
                            </span>
                        @else
                            <span
                                class="absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5 max-md:px-2 max-md:py-1.5 max-md:ltr:left-4 max-md:rtl:right-4"
                                v-if="cart?.items_count"
                            >
                                @{{ cart.items_count }}
                            </span>
                        @endif
                    </span>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
                </x-slot>

                <!-- Drawer Header -->
                <x-slot:header>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.before') !!}

                    <div class="flex items-center justify-between">
                        <p class="text-2xl font-medium max-md:text-xl max-sm:text-xl">
                            @lang('shop::app.checkout.cart.mini-cart.shopping-cart')
                        </p>
                    </div>

                    <p class="text-base max-md:text-zinc-500 max-sm:text-xs">
                        {{ core()->getConfigData('sales.checkout.mini_cart.offer_info')}}
                    </p>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.after') !!}
                </x-slot>

                <!-- Drawer Content -->
                <x-slot:content>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.before') !!}

                    <!-- Cart Item Listing -->
                    <div 
                        class="mt-9 grid gap-12 max-md:mt-2.5 max-md:gap-5" 
                        v-if="cart?.items?.length"
                    >
                        <div 
                            class="flex gap-x-5 max-md:gap-x-4" 
                            v-for="item in cart?.items"
                        >
                            <!-- Cart Item Image -->
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.before') !!}

                            <div class="">
                                <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`">
                                    <img
                                        :src="item.base_image.small_image_url"
                                        class="max-w-28 max-h-28 rounded-xl max-md:max-h-20 max-md:max-w-[76px]"
                                    />
                                </a>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.after') !!}

                        <!-- Cart Item Information -->
                        <div class="grid flex-1 place-content-start justify-stretch gap-y-2.5">
                            <div class="flex justify-between gap-2 max-md:gap-0 max-sm:flex-wrap">

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.before') !!}

                                    <a
                                    class="max-w-4/5 max-md:w-full"
                                    :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`"
                                >
                                        <p class="text-base font-medium max-md:font-normal max-sm:text-sm">
                                            @{{ item.name }}
                                        </p>
                                    </a>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.after') !!}

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.before') !!}

                                    <template v-if="displayTax.prices == 'including_tax'">
                                        <p class="text-lg max-md:font-semibold max-sm:text-sm">
                                            @{{ item.formatted_price_incl_tax }}
                                        </p>
                                    </template>

                                    <template v-else-if="displayTax.prices == 'both'">
                                        <p class="flex flex-col text-lg max-md:font-semibold max-sm:text-sm">
                                            @{{ item.formatted_price_incl_tax }}

                                            <span class="text-xs font-normal text-zinc-500">
                                                @lang('shop::app.checkout.cart.mini-cart.excl-tax')

                                                <span class="font-medium text-black">@{{ item.formatted_price }}</span>
                                            </span>
                                        </p>
                                    </template>

                                    <template v-else>
                                        <p class="text-lg max-md:font-semibold max-sm:text-sm">
                                            @{{ item.formatted_price }}
                                        </p>
                                    </template>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.after') !!}
                                </div>

                                <!-- Cart Item Options Container -->
                                <div
                                    class="grid select-none gap-x-2.5 gap-y-1.5 max-sm:gap-y-0.5"
                                    v-if="item.options.length"
                                >

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.before') !!}

                                    <!-- Details Toggler -->
                                    <div class="">
                                        <p
                                            class="flex cursor-pointer items-center gap-x-4 text-base max-md:gap-x-1.5 max-md:text-sm max-sm:text-xs"
                                            @click="item.option_show = ! item.option_show"
                                        >
                                            @lang('shop::app.checkout.cart.mini-cart.see-details')

                                            <span
                                                class="text-2xl max-md:text-xl max-sm:text-lg"
                                                :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                            ></span>
                                        </p>
                                    </div>

                                    <!-- Option Details -->
                                    <div
                                        class="grid gap-2"
                                        v-show="item.option_show"
                                    >
                                        <template v-for="option in item.options">
                                            <div class="max-md:grid max-md:gap-0.5">
                                                <p class="text-sm font-medium text-zinc-500 max-md:font-normal max-sm:text-xs">
                                                    @{{ option.attribute_name + ':' }}
                                                </p>
        
                                                <p class="text-sm max-sm:text-xs">
                                                    @{{ option.option_label }}
                                                </p>
                                            </div>
                                        </template>
                                    </div>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.after') !!}
                                </div>

                                <div class="flex flex-wrap items-center gap-5 max-md:gap-2.5">
                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.before') !!}

                                <!-- Cart Item Quantity Changer -->
                                <x-shop::quantity-changer
                                    class="max-h-9 max-w-[150px] gap-x-2.5 rounded-[54px] px-3.5 py-1.5 max-md:gap-x-2 max-md:px-1 max-md:py-0.5"
                                    name="quantity"
                                    ::value="item?.quantity"
                                    @change="updateItem($event, item)"
                                />

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.after') !!}

                                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.before') !!}
                                
                                <!-- Cart Item Remove Button -->
                                <button
                                    type="button"
                                    class="text-blue-700 max-md:text-sm"
                                    @click="removeItem(item.id)"
                                >
                                    @lang('shop::app.checkout.cart.mini-cart.remove')
                                </button>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.after') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Cart Section -->
                    <div
                        class="mt-32 pb-8 max-md:mt-32"
                        v-else
                    >
                        <div class="b-0 grid place-items-center gap-y-5 max-md:gap-y-0">
                            <img
                                class="max-md:h-[100px] max-md:w-[100px]"
                                src="{{ bagisto_asset('images/thank-you.png') }}"
                            >

                            <p
                                class="text-xl max-md:text-sm"
                                role="heading"
                            >
                                @lang('shop::app.checkout.cart.mini-cart.empty-cart')
                            </p>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.after') !!}
                </x-slot>

            <!-- Drawer Footer -->
            <x-slot:footer>
                <div
                    v-if="cart?.items?.length"
                    class="grid-col-1 grid gap-5 max-md:gap-2.5"
                >
                    <div
                        class="my-8 flex items-center justify-between border-b border-zinc-200 px-6 pb-2 max-md:my-0 max-md:border-t max-md:px-5 max-md:py-2"
                        :class="{'!justify-end': isLoading}"
                    >
                        {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.before') !!}

                        <template v-if="! isLoading">
                            <p class="text-sm font-medium text-zinc-500">
                                @lang('shop::app.checkout.cart.mini-cart.subtotal')
                            </p>
                        
                        <template v-if="displayTax.subtotal == 'including_tax'">
                            <p class="text-3xl font-semibold max-md:text-base">
                                @{{ cart.formatted_sub_total_incl_tax }}
                            </p>
                        </template>

                        <template v-else-if="displayTax.subtotal == 'both'">
                            <p class="flex flex-col text-3xl font-semibold max-md:text-sm max-sm:text-right">
                                @{{ cart.formatted_sub_total_incl_tax }}
                                
                                <span class="text-sm font-normal text-zinc-500 max-sm:text-xs">
                                    @lang('shop::app.checkout.cart.mini-cart.excl-tax')
                                    
                                    <span class="font-medium text-black">@{{ cart.formatted_sub_total }}</span>
                                </span>
                            </p>
                        </template>

                        <template v-else>
                            <p class="text-3xl font-semibold max-md:text-base">
                                @{{ cart.formatted_sub_total }}
                            </p>
                        </template>
                    </template>

                        <template v-else>
                            <!-- Spinner -->
                            <svg
                                class="text-blue h-8 w-8 animate-spin text-[5px] font-semibold max-md:h-7 max-md:w-7 max-sm:h-4 max-sm:w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                aria-hidden="true"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                        </template>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.mini-cart.action.before') !!}

                        <!-- Cart Action Container -->
                        <div class="grid gap-2.5 px-6 max-md:px-4 max-sm:gap-1.5">
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.before') !!}

                        <a
                            href="{{ route('shop.checkout.onepage.index') }}"
                            class="mx-auto block w-full cursor-pointer rounded-2xl bg-navyBlue px-11 py-4 text-center text-base font-medium text-white max-md:rounded-lg max-md:px-5 max-md:py-2"
                        >
                            @lang('shop::app.checkout.cart.mini-cart.continue-to-checkout')
                        </a>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.after') !!}

                            <div class="block cursor-pointer text-center text-base font-medium max-md:py-1.5">
                                <a href="{{ route('shop.checkout.cart.index') }}">
                                    @lang('shop::app.checkout.cart.mini-cart.view-cart')
                                </a>
                            </div>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.mini-cart.action.after') !!}
                    </div>
                </x-slot>
            </x-shop::drawer>

        @else
            <a href="{{ route('shop.checkout.onepage.index') }}">
                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                    <span class="relative">
                        <span
                            class="icon-cart cursor-pointer text-2xl"
                            role="button"
                            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                            tabindex="0"
                        ></span>

                        <span
                            class="absolute -top-4 rounded-[44px] bg-navyBlue px-2 py-1.5 text-xs font-semibold leading-[9px] text-white ltr:left-5 rtl:right-5 max-md:px-2 max-md:py-1.5 max-md:ltr:left-4 max-md:rtl:right-4"
                            v-if="cart?.items_qty"
                        >
                            @{{ cart.items_qty }}
                        </span>
                    </span>

                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
            </a>
        @endif

        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
    </script>

    <script type="module">
        app.component("v-mini-cart", {
            template: '#v-mini-cart-template',

            data() {
                return  {
                    cart: null,

                    isLoading:false,

                    displayTax: {
                        prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",
                        subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",
                    },
                }
            },

            mounted() {
                this.getCart();

                /**
                 * To Do: Implement this.
                 *
                 * Action.
                 */
                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });
            },

            methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },

                updateItem(quantity, item) {
                    this.isLoading = true;

                    let qty = {};

                    qty[item.id] = quantity;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                        .then(response => {
                            if (response.data.message) {
                                this.cart = response.data.data;
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isLoading = false;
                        }).catch(error => this.isLoading = false);
                },

                removeItem(itemId) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.isLoading = true;

                            this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                '_method': 'DELETE',
                                'cart_item_id': itemId,
                            })
                            .then(response => {
                                this.cart = response.data.data;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                
                                this.isLoading = false;
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });

                                this.isLoading = false;
                            });
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce