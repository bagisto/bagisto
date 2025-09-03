<!-- Separator -->
<span class="absolute my-1.5 block w-full border border-gray-200 ltr:left-0 rtl:right-0"></span>

<v-product-customer-group-price>
    <x-admin::shimmer.catalog.products.edit.group-price />
</v-product-customer-group-price>

@inject('customerGroupRepository', 'Webkul\Customer\Repositories\CustomerGroupRepository')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-customer-group-price-template"
    >
        <div>
            <!-- Header -->
            <div class="mt-1.5 flex items-center justify-between py-4">
                <p class="py-2.5 text-base font-semibold text-gray-800 dark:text-white">
                    @lang('admin::app.catalog.products.edit.price.group.title')
                </p>

                <p
                    class="cursor-pointer text-blue-600 transition-all hover:underline"
                    @click="resetForm(); $refs.groupPriceCreateModal.open()"
                >
                    @lang('admin::app.catalog.products.edit.price.group.create-btn')
                </p>
            </div>

            <!-- Content -->
            <div class="grid">
                <!-- Card -->
                <div
                    class="flex flex-col gap-2 py-2.5"
                    v-for="(item, index) in prices"
                >
                    <!-- Hidden Inputs -->
                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][customer_group_id]'"
                        :value="item.customer_group_id"
                    />

                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][qty]'"
                        :value="item.qty"
                    />

                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][value_type]'"
                        :value="item.value_type"
                    />

                    <input
                        type="hidden"
                        :name="'customer_group_prices[' + item.id + '][value]'"
                        :value="item.value"
                    />

                    <div class="flex justify-between">
                        <p class="font-semibold text-gray-600 dark:text-gray-300">
                            @{{ getGroupNameById(item.customer_group_id) }}
                        </p>

                        <p
                            class="cursor-pointer text-blue-600 transition-all hover:underline"
                            @click="selectedPrice = item; $refs.groupPriceCreateModal.open()"
                        >
                            @lang('admin::app.catalog.products.edit.price.group.edit-btn')
                        </p>
                    </div>

                    <p
                        class="text-gray-600 dark:text-gray-300"
                        v-if="item.value_type == 'fixed'"
                    >
                        @{{ "@lang('admin::app.catalog.products.edit.price.group.fixed-group-price-info')".replace(':qty', item.qty).replace(':price', item.value) }}
                    </p>

                    <p
                        class="text-gray-600 dark:text-gray-300"
                        v-else
                    >
                        @{{ "@lang('admin::app.catalog.products.edit.price.group.discount-group-price-info')".replace(':qty', item.qty).replace(':price', item.value) }}
                    </p>
                </div>

                <!-- Empty Container -->
                <div
                    class="flex items-center gap-5 py-2.5"
                    v-if="! prices.length"
                >
                    <img
                        src="{{ bagisto_asset('images/icon-discount.svg') }}"
                        class="h-20 w-20 rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                    />

                    <div class="flex flex-col gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.catalog.products.edit.price.group.add-group-price')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.catalog.products.edit.price.group.empty-info')
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="groupPriceCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="! selectedPrice.id"
                            >
                                @lang('admin::app.catalog.products.edit.price.group.create.create-title')
                            </p>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang('admin::app.catalog.products.edit.price.group.create.update-title')
                            </p>    
                        </x-slot>
        
                        <!-- Modal Content -->
                        <x-slot:content>
                            {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.catalog.products.edit.price.group.create.customer-group')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="select"
                                    name="customer_group_id"
                                    v-model="selectedPrice.customer_group_id"
                                    :label="trans('admin::app.catalog.products.edit.price.group.create.customer-group')"
                                >
                                    <option value="">
                                        @lang('admin::app.catalog.products.edit.price.group.create.all-groups')
                                    </option>

                                    <option
                                        v-for="group in groups"
                                        :value="group.id"
                                    >
                                        @{{ group.name }}
                                    </option>
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>

                            <div class="flex gap-4">
                                <x-admin::form.control-group class="flex-1">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.price.group.create.qty')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="qty"
                                        rules="required|numeric|min_value:1"
                                        v-model="selectedPrice.qty"
                                        :label="trans('admin::app.catalog.products.edit.price.group.create.qty')"
                                    />
        
                                    <x-admin::form.control-group.error control-name="qty" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="flex-1">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.price.group.create.price-type')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="value_type"
                                        rules="required"
                                        v-model="selectedPrice.value_type"
                                        :label="trans('admin::app.catalog.products.edit.price.group.create.price-type')"
                                    >
                                        <option value="fixed">
                                            @lang('admin::app.catalog.products.edit.price.group.create.fixed')
                                        </option>

                                        <option value="discount">
                                            @lang('admin::app.catalog.products.edit.price.group.create.discount')
                                        </option>
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error control-name="value_type" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="flex-1">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.price.group.create.price')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="value"
                                        ::rules="{required: true, decimal: true, min_value: 0, ...(selectedPrice.value_type === 'discount' ? {max_value: 100} : {})}"
                                        v-model="selectedPrice.value"
                                        :label="trans('admin::app.catalog.products.edit.price.group.create.price')"
                                    />
        
                                    <x-admin::form.control-group.error control-name="value" />
                                </x-admin::form.control-group>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.products.create_form.general.controls.before') !!}
                        </x-slot>
        
                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <div class="flex items-center gap-x-2.5">
                                <!-- Delete Button -->
                                <x-admin::button
                                    button-type="button"
                                    class="cursor-pointer whitespace-nowrap rounded-md border-2 border-transparent px-3 py-1.5 font-semibold text-red-600 transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                                    :title="trans('admin::app.catalog.products.edit.price.group.create.delete-btn')"
                                    v-if="selectedPrice.id"
                                    @click="remove"
                                />

                                <!-- Save Button -->
                                <x-admin::button
                                    button-type="button"
                                    class="primary-button"
                                    :title="trans('admin::app.catalog.products.edit.price.group.create.save-btn')"
                                />
                            </div>
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-product-customer-group-price', {
            template: '#v-product-customer-group-price-template',

            data: function() {
                return {
                    groups: @json($customerGroupRepository->all()),

                    prices: @json($product->customer_group_prices),

                    selectedPrice: {
                        customer_group_id: null,
                        qty: 0,
                        value_type: 'fixed',
                        value: 0,
                    }
                }
            },

            methods: {
                getGroupNameById(id) {
                    let group = this.groups.find(group => group.id == id);

                    return group ? group.name : "@lang('admin::app.catalog.products.edit.price.group.all-groups')";
                },

                create(params) {
                    if (this.selectedPrice.id == undefined) {
                        params.id = 'price_' + this.prices.length;

                        this.prices.push(params);
                    } else {
                        const indexToUpdate = this.prices.findIndex(price => price.id === this.selectedPrice.id);

                        this.prices[indexToUpdate] = this.selectedPrice;
                    }

                    this.resetForm();

                    this.$refs.groupPriceCreateModal.close();
                },

                resetForm() {
                    this.selectedPrice = {
                        customer_group_id: null,
                        qty: 0,
                        value_type: 'fixed',
                        value: 0,
                    };
                },

                remove() {
                    this.$refs.groupPriceCreateModal.close();

                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.prices.indexOf(this.selectedPrice);

                            this.prices.splice(index, 1);

                            this.resetForm();
                        }
                    });
                }
            }
        });
    </script>
@endPushOnce
