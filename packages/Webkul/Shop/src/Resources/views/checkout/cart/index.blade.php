{{-- SEO Meta Content --}}
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.cart.index.cart')"/>

    <meta name="keywords" content="@lang('shop::app.checkout.cart.index.cart')"/>
@endPush

<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.checkout.cart.index.cart')
    </x-slot>

    {{-- Page Header --}}
    <div class="flex flex-wrap">
        <div class="w-full flex justify-between px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0 py-[17px] max-lg:px-[30px] max-sm:px-[15px]">
            <div class="flex items-center gap-x-[54px] max-[1180px]:gap-x-[35px]">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="flex min-h-[30px]"
                    aria-label="Bagisto "
                >
                    <img
                        src="{{ bagisto_asset('images/logo.svg') }}"
                        alt="Bagisto "
                        width="131"
                        height="29"
                    >
                </a>
            </div>
        </div>
    </div>

    <div class="flex-auto">
        <div class="container px-[60px] max-lg:px-[30px]">
            {{-- Breadcrumbs --}}
            <x-shop::breadcrumbs name="cart"></x-shop::breadcrumbs>

            <v-cart ref="vCart">
                {{-- Cart Shimmer Effect --}}
                <x-shop::shimmer.checkout.cart :count="3"></x-shop::shimmer.checkout.cart>
            </v-cart>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-cart-template">
            <div>
                <!-- Cart Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.checkout.cart :count="3"></x-shop::shimmer.checkout.cart>
                </template>

                <!-- Cart Information -->
                <template v-else>
                    <div 
                        class="flex flex-wrap gap-[75px] mt-[30px] pb-[30px] max-1060:flex-col"
                        v-if="cart?.items?.length"
                    >
                        <div class="grid gap-[25px] flex-1">
                            <!-- Cart Mass Action Container -->
                            <div class="flex justify-between items-center pb-[10px] border-b-[1px] border-[#E9E9E9] max-sm:block">
                                <div class="flex select-none items-center">
                                    <input
                                        type="checkbox"
                                        id="select-all"
                                        class="hidden peer"
                                        v-model="allSelected"
                                        @change="selectAll"
                                    >

                                    <label
                                        class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                        for="select-all"
                                    >
                                    </label>

                                    <span class="text-[20px] max-md:text-[22px] max-sm:text-[18px] ml-[10px]">
                                        @{{ "@lang('shop::app.checkout.cart.index.items-selected')".replace(':count', selectedItemsCount) }}
                                    </span>
                                </div>

                                <div 
                                    class="max-sm:ml-[35px] max-sm:mt-[10px]"
                                    v-if="selectedItemsCount"
                                >
                                    <span
                                        class="text-[16px] text-[#0A49A7] cursor-pointer" 
                                        @click="removeSelectedItems"
                                    >
                                        @lang('shop::app.checkout.cart.index.remove')
                                    </span>

                                    @if (auth()->guard()->check())
                                        <span class="mx-[10px] border-r-[2px] border-[#E9E9E9]"></span>

                                        <span
                                            class="text-[16px] text-[#0A49A7] cursor-pointer" 
                                            @click="moveToWishlistSelectedItems"
                                        >
                                            @lang('shop::app.checkout.cart.index.move-to-wishlist')
                                        </span>    
                                    @endif
                                </div>
                            </div>
                        
                            <!-- Cart Item Listing Container -->
                            <div 
                                class="grid gap-y-[25px]" 
                                v-for="item in cart?.items"
                            >
                                <div class="flex gap-x-[10px] justify-between flex-wrap pb-[18px] border-b-[1px] border-[#E9E9E9]">
                                    <div class="flex gap-x-[20px]">
                                        <div class="select-none mt-[43px]">
                                            <input
                                                type="checkbox"
                                                :id="'item_' + item.id"
                                                class="hidden peer"
                                                v-model="item.selected"
                                                @change="updateAllSelected"
                                            >

                                            <label
                                                class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                                :for="'item_' + item.id"
                                            ></label>
                                        </div>

                                        <!-- Cart Item Image -->
                                        <x-shop::media.images.lazy
                                            class="h-[110px] min-w-[110px] max-w[110px] rounded-[12px]"
                                            ::src="item.base_image.small_image_url"
                                            ::alt="item.name"
                                            width="110"
                                            height="110"
                                            ::key="item.id"
                                            ::index="item.id"
                                        >
                                        </x-shop::media.images.lazy>

                                        <!-- Cart Item Options Container -->
                                        <div class="grid place-content-start gap-y-[10px]">

                                            <p 
                                                class="text-[16px] font-medium" 
                                                v-text="item.name"
                                            >
                                            </p>
                                    
                                            <!-- Cart Item Options Container -->
                                            <div
                                                class="grid gap-x-[10px] gap-y-[6px] select-none"
                                                v-if="item.options.length"
                                            >
                                                <!-- Details Toggler -->
                                                <div class="">
                                                    <p
                                                        class="flex gap-x-[15px] text-[16px] items-center cursor-pointer whitespace-nowrap"
                                                        @click="item.option_show = ! item.option_show"
                                                    >
                                                        @lang('shop::app.checkout.cart.index.see-details')

                                                        <span
                                                            class="text-[24px]"
                                                            :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                                        ></span>
                                                    </p>
                                                </div>

                                                <!-- Option Details -->
                                                <div class="grid gap-[8px]" v-show="item.option_show">
                                                    <div class="" v-for="option in item.options">
                                                        <p class="text-[14px] font-medium">
                                                            @{{ option.attribute_name + ':' }}
                                                        </p>

                                                        <p class="text-[14px]">
                                                            @{{ option.option_label }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="sm:hidden">
                                                <p 
                                                    class="text-[18px] font-semibold" 
                                                    v-text="item.formatted_total"
                                                >
                                                </p>
                                                
                                                <span
                                                    class="text-[16px] text-[#0A49A7] cursor-pointer" 
                                                    @click="removeItem(item.id)"
                                                >
                                                    @lang('shop::app.checkout.cart.index.remove')
                                                </span>
                                            </div>

                                            <x-shop::quantity-changer
                                                name="quantity"
                                                ::value="item?.quantity"
                                                class="flex gap-x-[10px] border rounded-[54px] border-navyBlue py-[5px] px-[14px] items-center max-w-max"
                                                @change="setItemQuantity(item.id, $event)"
                                            >
                                            </x-shop::quantity-changer>
                                        </div>
                                    </div>

                                    <div class="max-sm:hidden text-right">
                                        <p 
                                            class="text-[18px] font-semibold" 
                                            v-text="item.formatted_total"
                                        >
                                        </p>
                                        
                                        <!-- Cart Item Remove Button -->
                                        <span
                                            class="text-[16px] text-[#0A49A7] cursor-pointer" 
                                            @click="removeItem(item.id)"
                                        >
                                            @lang('shop::app.checkout.cart.index.remove')
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.before') !!}
        
                            <!-- Cart Item Actions -->
                            <div class="flex flex-wrap gap-[30px] justify-end">
                                <a
                                    class="secondary-button max-h-[55px] rounded-[18px]"
                                    href="{{ route('shop.home.index') }}"
                                >
                                    @lang('shop::app.checkout.cart.index.continue-shopping')
                                </a> 

                                <button
                                    class="secondary-button max-h-[55px] rounded-[18px]"
                                    @click="update()"
                                >
                                    @lang('shop::app.checkout.cart.index.update-cart')
                                </button>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.after') !!}
                            
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.cart.summary.before') !!}

                        <!-- Cart Summary -->
                        @include('shop::checkout.cart.summary')

                        {!! view_render_event('bagisto.shop.checkout.cart.summary.after') !!}

                    </div>

                    <!-- Empty Cart Section -->
                    <div
                        class="grid items-center justify-items-center w-[100%] m-auto h-[476px] place-content-center text-center"
                        v-else
                    >
                        <img src="{{ bagisto_asset('images/thank-you.png') }}"/>
                        
                        <p class="text-[20px]">
                            @lang('shop::app.checkout.cart.index.empty-product')
                        </p>
                    </div>
                </template>
            </div>
        </script>

        <script type="module">
            app.component("v-cart", {
                template: '#v-cart-template',

                data() {
                    return  {
                        cart: [],

                        allSelected: false,

                        applied: {
                            quantity: {},
                        },

                        isLoading: true,
                    }
                },

                mounted() {
                    this.get();
                },

                computed: {
                    selectedItemsCount() {
                        return this.cart.items.filter(item => item.selected).length;
                    },
                },

                methods: {
                    get() {
                        this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                            .then(response => {
                                this.cart = response.data.data;

                                this.isLoading = false;

                                if (response.data.message) {
                                    this.$emitter.emit('add-flash', { type: 'info', message: response.data.message });
                                }
                            })
                            .catch(error => {});     
                    },

                    selectAll() {
                        for (let item of this.cart.items) {
                            item.selected = this.allSelected;
                        }
                    },

                    updateAllSelected() {
                        this.allSelected = this.cart.items.every(item => item.selected);
                    },

                    update() {
                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty: this.applied.quantity })
                            .then(response => {
                                this.cart = response.data.data;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {});
                    },

                    setItemQuantity(itemId, quantity) {
                        this.applied.quantity[itemId] = quantity;
                    },

                    removeItem(itemId) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                        '_method': 'DELETE',
                                        'cart_item_id': itemId,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    removeSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy_selected') }}', {
                                        '_method': 'DELETE',
                                        'ids': selectedItemsIds,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('update-mini-cart', response.data.data );

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    moveToWishlistSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                this.$axios.post('{{ route('shop.api.checkout.cart.move_to_wishlist') }}', {
                                        'ids': selectedItemsIds,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('update-mini-cart', response.data.data );

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    })
                                    .catch(error => {});
                            }
                        });
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>
