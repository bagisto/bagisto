<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.orders.create.title', ['name' => $cart->customer->name])
    </x-slot>

    <!-- Page Header -->
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <p class="text-xl text-gray-800 dark:text-white font-bold leading-6">
                @lang('admin::app.sales.orders.create.title', ['name' => $cart->customer->name])
            </p>
        </div>

        <!-- Back Button -->
        <a
            href="{{ route('admin.sales.orders.index') }}"
            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
        >
            @lang('admin::app.sales.orders.create.back-btn')
        </a>
    </div>

    <!-- Vue JS Component -->
    <v-create-order>
        <!-- Order Create Shimmer Effect -->
        <x-admin::shimmer.sales.orders.create />
    </v-create-order>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-order-template">
            <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
                <!-- Left Component -->
                {!! view_render_event('bagisto.admin.sales.order.create.left_component.before') !!}
                
                <div
                    class="flex flex-col gap-2 flex-1 max-xl:flex-auto overflow-y-auto"
                    id="steps-container"
                >
                    <!-- Cart Items Component -->
                    @include('admin::sales.orders.create.cart.items')

                    <!-- Included Addresses Blade File -->
                    <template v-if="cart.items_count && ['address', 'shipping', 'payment', 'review'].includes(currentStep)">
                        @include('admin::sales.orders.create.cart.address')
                    </template>

                    <!-- Included Shipping Methods Blade File -->
                    <template v-if="cart.have_stockable_items && ['shipping', 'payment', 'review'].includes(currentStep)">
                        @include('admin::sales.orders.create.cart.shipping')
                    </template>

                    <!-- Included Payment Methods Blade File -->
                    <template v-if="['payment', 'review'].includes(currentStep)">
                        @include('admin::sales.orders.create.cart.payment')
                    </template>

                    <!-- Included Payment Methods Blade File -->
                    <template v-if="['review'].includes(currentStep)">
                        @include('admin::sales.orders.create.cart.summary')
                    </template>

                    <!-- Product Option Form -->
                    <x-admin::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form
                            @submit="handleSubmit($event, addToCart)"
                            ref="addToCartForm"
                        >
                            <x-admin::drawer ref="productConfigurationDrawer">
                                <!-- Drawer Header -->
                                <x-slot:header>
                                    <div class="flex justify-between items-center">
                                        <p class="text-xl font-medium dark:text-white">
                                        @lang('admin::app.sales.orders.create.configuration')
                                        </p>

                                        <button class="primary-button ltr:mr-11 rtl:ml-11">
                                            @lang('admin::app.sales.orders.create.add-to-cart')
                                        </button>
                                    </div>
                                </x-slot>

                                <!-- Drawer Content -->
                                <x-slot:content class="!p-0">
                                    {!! view_render_event('bagisto.admin.sales.order.create.product_options.before') !!}

                                    <!-- Included Configurable Product Configuration Blade File -->
                                    <template v-if="selectedProductOptions.product.type == 'configurable'">
                                        @include('admin::sales.orders.create.types.configurable')
                                    </template>

                                    <!-- Included Bundle Product Configuration Blade File -->
                                    <template v-if="selectedProductOptions.product.type == 'bundle'">
                                        @include('admin::sales.orders.create.types.bundle')
                                    </template>

                                    <!-- Included Grouped Product Configuration Blade File -->
                                    <template v-if="selectedProductOptions.product.type == 'grouped'">
                                        @include('admin::sales.orders.create.types.grouped')
                                    </template>

                                    <!-- Included Downloadable Product Configuration Blade File -->
                                    <template v-if="selectedProductOptions.product.type == 'downloadable'">
                                        @include('admin::sales.orders.create.types.downloadable')
                                    </template>


                                    {!! view_render_event('bagisto.admin.sales.order.create.product_options.after') !!}
                                </x-slot>
                            </x-admin::drawer>
                        </form>
                    </x-admin::form>
                </div>

                {!! view_render_event('bagisto.admin.sales.order.create.left_component.after') !!}

                <!-- Right Component -->
                {!! view_render_event('bagisto.admin.sales.order.right_component.before') !!}

                <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                    <!-- Cart Items Component -->
                    @include('admin::sales.orders.create.cart-items')

                    <!-- Wishlist Items Component -->
                    @include('admin::sales.orders.create.wishlist-items')

                    <!-- Compare Items Component -->
                    @include('admin::sales.orders.create.compare-items')

                    <!-- Recent Order Items Component -->
                    @include('admin::sales.orders.create.recent-order-items')
                </div>
                    
                {!! view_render_event('bagisto.admin.sales.order.create.right_component.after') !!}
            </div>
        </script>

        <script type="module">
            app.component('v-create-order', {
                template: '#v-create-order-template',

                data() {
                    return {
                        cart: @json($cart),

                        selectedProductOptions: null,

                        currentStep: 'address',

                        isAddingToCart: false,

                        shippingMethods: null,

                        paymentMethods: null,

                        canPlaceOrder: false,
                    };
                },

                methods: {
                    setCart(cart) {
                        this.cart = cart;
                    },

                    getCart() {
                        this.$axios.get("{{ route('admin.sales.cart.index', $cart->id) }}")
                            .then(response => {
                                this.cart = response.data.data;
                            })
                            .catch(error => {
                                window.location.href = "{{ route('admin.sales.orders.index') }}";
                            });
                    },

                    configureAddToCart(params) {
                        this.selectedProductOptions = params;

                        if (
                            params.product.is_options_required
                            && ! params.additional?.attributes
                        ) {
                            this.$refs.productConfigurationDrawer.open();

                            return;
                        }

                        this.addToCart(params);
                    },

                    addToCart(params) {
                        let formData = {};

                        if (params.additional?.attributes) {
                            formData = {
                                product_id: params.product.id,

                                quantity: params.qty,

                                ...params.additional,
                            };
                        } else {
                            formData = new FormData(this.$refs.addToCartForm);

                            formData.append('product_id', this.selectedProductOptions.product.id);

                            formData.append('quantity', this.selectedProductOptions.qty);

                            this.$refs.productConfigurationDrawer.close();
                        }

                        this.isAddingToCart = true;

                        this.$axios.post("{{ route('admin.sales.cart.items.store', $cart->id) }}", formData)
                            .then(response => {
                                this.isAddingToCart = false;

                                this.cart = response.data.data;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {});
                    },

                    stepReset() {
                        this.currentStep = 'address';
                    },

                    stepForward(step) {
                        this.currentStep = step;

                        if (step == 'review') {
                            this.canPlaceOrder = true;

                            this.scrollToCurrentStep();

                            return;
                        }

                        this.canPlaceOrder = false;

                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = null;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = null;
                        }
                    },

                    stepProcessed(data) {
                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = data;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = data;
                        }

                        this.scrollToCurrentStep();

                        this.getCart();
                    },

                    scrollToCurrentStep() {
                        let container = document.getElementById(this.currentStep + '-step-container');

                        if (! container) {
                            return;
                        }

                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'end'
                        });
                    },
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>