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
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        <div class="mt-5 flex justify-center max-lg:hidden">
            {!! view_render_event('bagisto.shop.customers.account.compare.breadcrumbs.before') !!}

            <div class="flex items-center gap-x-2.5">
                <x-shop::breadcrumbs name="compare" />
            </div>

            {!! view_render_event('bagisto.shop.customers.account.compare.breadcrumbs.after') !!}
        </div>
    @endif

    <!-- Compare Component -->
    <div class="container mt-8 px-[60px] max-lg:px-8 max-md:mt-7 max-md:px-0">
        <v-compare>
            <!-- Shimmer Effect -->
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
                    <div class="flex items-center justify-between max-md:px-4">

                        {!! view_render_event('bagisto.shop.customers.account.compare.title.before') !!}

                        <h1 class="text-2xl font-medium max-sm:text-base">
                            @lang('shop::app.compare.title')
                        </h1>

                        {!! view_render_event('bagisto.shop.customers.account.compare.title.after') !!}

                        {!! view_render_event('bagisto.shop.customers.account.compare.remove_all.before') !!}

                        <div
                            class="secondary-button flex items-center gap-x-2.5 whitespace-nowrap border-zinc-200 px-5 py-3 font-normal max-md:rounded-lg max-md:px-3 max-md:text-xs max-sm:py-1.5"
                            v-if="items.length"
                            @click="removeAll"
                        >
                            <span class="icon-bin text-2xl max-md:hidden"></span>

                            @lang('shop::app.compare.delete-all')
                        </div>

                        {!! view_render_event('bagisto.shop.customers.account.compare.remove_all.after') !!}
                    </div>

                    <div
                        class="journal-scroll mt-16 grid overflow-auto max-md:mt-7"
                        v-if="items.length"
                    >
                        <template v-for="attribute in comparableAttributes">
                            <!-- Product Card -->
                            <div
                                class="flex max-w-full items-center border-b border-zinc-200"
                                v-if="attribute.code == 'product'"
                            >
                                {!! view_render_event('bagisto.shop.customers.account.compare.attribute_name.before') !!}

                                <div class="min-w-[304px] max-w-full max-md:grid max-md:h-full max-md:min-w-40 max-md:items-center max-md:bg-gray-200 max-sm:min-w-[110px]">
                                    <p class="text-sm font-medium max-md:pl-4">
                                        @{{ attribute.name ?? attribute.admin_name }}
                                    </p>
                                </div>

                                {!! view_render_event('bagisto.shop.customers.account.compare.attribute_name.after') !!}

                                <div class="flex gap-3 border-zinc-200 max-md:gap-0 max-md:border-0 ltr:border-l-[1px] rtl:border-r-[1px]">
                                    <div
                                        class="relative w-[311px] max-w-[311px] px-5 max-md:w-60 max-md:px-2.5 max-sm:w-[190px]"
                                        v-for="product in items"
                                    >
                                        <span
                                            class="icon-cancel absolute top-5 z-[1] flex h-[30px] w-[30px] cursor-pointer items-center justify-center rounded-md border border-zinc-200 bg-white text-2xl max-md:top-10 max-md:h-6 max-md:w-6 max-md:rounded-full max-md:text-sm ltr:right-10 max-md:ltr:right-4 rtl:left-10 max-md:rtl:left-4"
                                            @click="remove(product.id)"
                                        ></span>

                                        <x-shop::products.card class="[&_span.icon-compare]:hidden" />
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.customers.account.compare.comparable_attribute.before') !!}

                            <!-- Comparable Attributes -->
                            <div
                                class="flex max-w-full items-center border-b border-zinc-200"
                                v-else
                            >
                                <div class="min-w-[304px] max-w-full max-md:grid max-md:h-full max-md:min-w-40 max-md:items-center max-md:bg-gray-200 max-sm:min-w-[110px]">
                                    <p class="text-sm font-medium max-md:pl-4">
                                        @{{ attribute.name ?? attribute.admin_name }}
                                    </p>
                                </div>

                                <div class="flex gap-3 border-zinc-200 max-md:gap-0 max-md:border-0 ltr:border-l-[1px] rtl:border-r-[1px]">
                                    <div
                                        class="w-[311px] max-w-[311px] p-5 max-md:w-60 max-md:px-2.5 max-sm:w-[190px]"
                                        v-for="(product, index) in items"
                                    >
                                        <p
                                            class="break-all text-sm"
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
                        class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center"
                        v-else
                    >
                        <img
                            class="max-sm:h-[100px] max-sm:w-[100px]"
                            src="{{ bagisto_asset('images/thank-you.png') }}"
                            alt="@lang('shop::app.compare.empty-text')"
                        />
                        
                        <p
                            class="text-xl max-sm:text-sm"
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
