<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.compare.title')"/>

    <meta name="keywords" content="@lang('shop::app.compare.title')"/>
@endPush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.compare.title')
    </x-slot>

    <!-- Breadcrumb -->
    <div class="mt-5 flex justify-center max-lg:hidden">
        {!! view_render_event('bagisto.shop.customers.account.compare.breadcrumbs.before') !!}

		<div class="flex items-center gap-x-2.5">
            <x-shop::breadcrumbs name="compare" />
		</div>

        {!! view_render_event('bagisto.shop.customers.account.compare.breadcrumbs.after') !!}
	</div>

    <!-- Compare Component -->
    <div class="container mt-8 px-[60px] max-lg:px-8 max-sm:px-4">
        <v-compare>
            <!---- Shimmer Effect -->
            <x-shop::shimmer.compare :attributeCount="count($comparableAttributes)" />
        </v-compare>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-compare-template"
        >
            <div>
                {!! view_render_event('bagisto.shop.customers.account.compare.before') !!}

                <div v-if="! isLoading">
                    <div class="flex items-center justify-between">

                        {!! view_render_event('bagisto.shop.customers.account.compare.title.before') !!}

                        <h1 class="text-2xl font-medium">
                            @lang('shop::app.compare.title')
                        </h1>

                        {!! view_render_event('bagisto.shop.customers.account.compare.title.after') !!}

                        {!! view_render_event('bagisto.shop.customers.account.compare.remove_all.before') !!}

                        <div
                            class="secondary-button flex items-center gap-x-2.5 whitespace-nowrap border-[#E9E9E9] px-5 py-3 font-normal"
                            v-if="items.length"
                            @click="removeAll"
                        >
                            <span class="icon-bin text-2xl"></span>
                            @lang('shop::app.compare.delete-all')
                        </div>

                        {!! view_render_event('bagisto.shop.customers.account.compare.remove_all.after') !!}
                    </div>

                    <div
                        class="journal-scroll mt-16 grid overflow-auto"
                        v-if="items.length"
                    >
                        <template v-for="attribute in comparableAttributes">
                            <!-- Product Card -->
                            <div
                                class="flex max-w-full items-center border-b border-[#E9E9E9]"
                                v-if="attribute.code == 'product'"
                            >
                                {!! view_render_event('bagisto.shop.customers.account.compare.attribute_name.before') !!}

                                <div class="min-w-[304px] max-w-full max-sm:hidden">
                                    <p class="text-sm font-medium">
                                        @{{ attribute.name ?? attribute.admin_name }}
                                    </p>
                                </div>

                                {!! view_render_event('bagisto.shop.customers.account.compare.attribute_name.after') !!}

                                <div class="flex gap-3 border-[#E9E9E9] max-sm:border-0 ltr:border-l-[1px] rtl:border-r-[1px]">
                                    <div
                                        class="group relative"
                                        v-for="product in items"
                                    >
                                        <span
                                            class="icon-cancel absolute top-16 hidden h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md bg-white text-2xl transition-all duration-300 group-hover:z-[1] group-hover:flex ltr:right-5 rtl:left-5"
                                            @click="remove(product.id)"
                                        ></span>

                                        <x-shop::products.card class="min-w-[311px] max-w-[311px] p-5 pt-0 ltr:pr-0 max-sm:ltr:pl-0 rtl:pl-0 max-sm:rtl:pr-0" />
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.customers.account.compare.comparable_attribute.before') !!}

                            <!-- Comparable Attributes -->
                            <div
                                class="flex max-w-full items-center border-b border-[#E9E9E9] last:border-none"
                                v-else
                            >
                                <div class="min-w-[304px] max-w-full max-sm:hidden">
                                    <p class="text-sm font-medium">
                                        @{{ attribute.name ?? attribute.admin_name }}
                                    </p>
                                </div>

                                <div class="flex gap-3 border-[#E9E9E9] max-sm:border-0 ltr:border-l-[1px] rtl:border-r-[1px]">
                                    <div
                                        class="w-[311px] max-w-[311px] p-5 ltr:pr-0 max-sm:ltr:pl-0 rtl:pl-0 max-sm:rtl:pr-0"
                                        v-for="(product, index) in items"
                                    >
                                        <p class="mb-1.5 hidden text-sm font-medium max-sm:block">
                                            @{{ attribute.name ?? attribute.admin_name }} :
                                        </p>

                                        <p
                                            class="text-sm"
                                            v-html="product[attribute.code] ?? 'N/A'"
                                        >
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.customers.account.compare.comparable_attribute.after') !!}
                        </template>
                    </div>

                    <div
                        class="m-auto grid h-[476px] w-full place-content-center items-center justify-items-center text-center"
                        v-else
                    >
                        <img
                            src="{{ bagisto_asset('images/thank-you.png') }}"
                            alt="@lang('shop::app.compare.empty-text')"
                        />
                        
                        <p
                            class="text-xl"
                            role="heading"
                        >
                            @lang('shop::app.compare.empty-text')
                        </p>
                    </div>
                </div>

                <div v-else>
                    <!---- Shimmer Effect -->
                    <x-shop::shimmer.compare :attributeCount="count($comparableAttributes)" />
                </div>

                {!! view_render_event('bagisto.shop.customers.account.compare.after') !!}
            </div>
        </script>

        <script type="module">
            app.component("v-compare", {
                template: '#v-compare-template',

                data() {
                    return  {
                        comparableAttributes: [
                            ...[{'code': 'product', 'name': 'Product'}],
                            ...@json($comparableAttributes)
                        ],

                        items: [],

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

                        isLoading: true,
                    }
                },

                mounted() {
                    this.getItems();
                },

                methods: {
                    getItems() {
                        let productIds = [];
                        
                        if (! this.isCustomer) {
                            productIds = this.getStorageValue('compare_items');
                        }
                        
                        this.$axios.get("{{ route('shop.api.compare.index') }}", {
                                params: {
                                    product_ids: productIds,
                                },
                            })
                            .then(response => {
                                this.isLoading = false;

                                this.items = response.data.data;
                            })
                            .catch(error => {});
                    },

                    remove(productId) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                if (! this.isCustomer) {
                                    const index = this.items.findIndex((item) => item.id === productId);

                                    this.items.splice(index, 1);

                                    let items = this.getStorageValue()
                                        .filter(item => item != productId);

                                    localStorage.setItem('compare_items', JSON.stringify(items));

                                    return;
                                }

                                this.$axios.post("{{ route('shop.api.compare.destroy') }}", {
                                        '_method': 'DELETE',
                                        'product_id': productId,
                                    })
                                    .then(response => {
                                        this.items = response.data.data;

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    })
                                    .catch(error => {
                                        this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                                    });
                            }
                        });
                    },

                    removeAll() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                if (! this.isCustomer) {
                                    localStorage.removeItem('compare_items');

                                    this.items = [];

                                    this.$emitter.emit('add-flash', { type: 'success', message:  "@lang('shop::app.compare.remove-all-success')" });

                                    return;
                                }
                                
                                this.$axios.post("{{ route('shop.api.compare.destroy_all') }}", {
                                        '_method': 'DELETE',
                                    })
                                    .then(response => {
                                        this.items = [];

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    getStorageValue() {
                        let value = localStorage.getItem('compare_items');

                        if (! value) {
                            return [];
                        }

                        return JSON.parse(value);
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>
