{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.bundle.before', ['product' => $product]) !!}

<v-bundle-options :errors="errors"></v-bundle-options>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.bundle.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-bundle-options-template">
        <div class="relative bg-white rounded-[4px] box-shadow">
            <!-- Panel Header -->
            <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p class="text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.products.edit.types.bundle.title')
                    </p>

                    <p class="text-[12px] text-gray-500 font-medium">
                        @lang('admin::app.catalog.products.edit.types.bundle.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-[4px] items-center">
                    <div
                        class="px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                        @click="$refs.updateCreateOptionModal.open()"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.add-btn')
                    </div>
                </div>
            </div>

            <!-- Panel Content -->
            <div
                class="grid"
                v-if="options.length"
            >
                <!-- Bundle Option Component -->
                <v-bundle-option-item
                    v-for='(option, index) in options'
                    :key="index"
                    :index="index"
                    :option="option"
                    :errors="errors"
                    @onEdit="selectedOption = $event; $refs.updateCreateOptionModal.open()"
                    @onRemove="removeOption($event)"
                ></v-bundle-option-item>
            </div>

            <!-- For Empty Option -->
            <div
                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                v-else
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-options.svg') }}"
                    class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center">
                    <p class="text-[16px] text-gray-400 font-semibold">
                        @lang('admin::app.catalog.products.edit.types.bundle.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('admin::app.catalog.products.edit.types.bundle.empty-info')
                    </p>
                </div>

                <div
                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-[14px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                    @click="$refs.updateCreateOptionModal.open()"
                >
                    @lang('admin::app.catalog.products.edit.types.bundle.add-btn')
                </div>
            </div>

            <!-- Add Option Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="updateCreateOptionModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.catalog.products.edit.types.bundle.create.title')
                            </p>
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.bundle.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="label"
                                        rules="required"
                                        :label="trans('admin::app.catalog.products.edit.types.bundle.create.name')"
                                    >
                                    </x-admin::form.control-group.control>
            
                                    <x-admin::form.control-group.error control-name="label"></x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <div class="flex gap-[16px]">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.bundle.create.type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="type"
                                            rules="required"
                                            :label="trans('admin::app.catalog.products.edit.types.bundle.create.type')"
                                        >
                                            <option value="select">
                                                {{ __('admin::app.catalog.products.edit.types.bundle.create.select') }}
                                            </option>

                                            <option value="radio">
                                                {{ __('admin::app.catalog.products.edit.types.bundle.create.radio') }}
                                            </option>

                                            <option value="checkbox">
                                                {{ __('admin::app.catalog.products.edit.types.bundle.create.checkbox') }}
                                            </option>

                                            <option value="multiselect">
                                                {{ __('admin::app.catalog.products.edit.types.bundle.create.multiselect') }}
                                            </option>
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="type"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.bundle.create.is-required')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="is_required"
                                            rules="required"
                                            :label="trans('admin::app.catalog.products.edit.types.bundle.create.is-required')"
                                        >
                                            <option value="1">
                                                {{ __('admin::app.catalog.products.edit.types.bundle.create.yes') }}
                                            </option>

                                            <option value="0">
                                                {{ __('admin::app.catalog.products.edit.types.bundle.create.no') }}
                                            </option>
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="is_required"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </div>
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.catalog.products.edit.types.bundle.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="text/x-template" id="v-bundle-option-item-template">
        <!-- Panel -->
        <div>
            <!-- Hidden Inputs -->
            <input type="hidden" :name="'bundle_options[' + option.id + '][label]'" :value="option.label"/>

            <input type="hidden" :name="'bundle_options[' + option.id + '][type]'" :value="option.type"/>

            <input type="hidden" :name="'bundle_options[' + option.id + '][is_required]'" :value="option.is_required"/>

            <input type="hidden" :name="'bundle_options[' + option.id + '][sort_order]'" :value="index"/>

            <!-- Panel Header -->
            <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p
                        class="text-[16px] text-gray-800 font-semibold"
                        :class="{'required': option.is_required}"
                    >
                        @{{ (index + 1) + '. ' + option.label + ' - ' + types[option.type].title }}
                    </p>

                    <p class="text-[12px] text-gray-500 font-medium">
                        @{{ types[option.type].info }}
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-[20px] items-center">
                    <p
                        class="text-blue-600 font-semibold cursor-pointer"
                        @click="$refs['productSearch' + option.id].openDrawer()"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.option.add-btn')
                    </p>

                    <p
                        class="text-blue-600 font-semibold cursor-pointer"
                        @click="edit"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.option.edit-btn')
                    </p>

                    <p
                        class="text-red-600 font-semibold cursor-pointer"
                        @click="remove"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.option.delete-btn')
                    </p>
                </div>
            </div>

            <!-- Panel Content -->
            <div
                class="grid"
                v-if="option.bundle_option_products.length"
            >
                <!-- Draggable Products -->
                <draggable
                    ghost-class="draggable-ghost"
                    :list="option.bundle_option_products"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <div class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300 cursor-pointer">
                            <!-- Information -->
                            <div class="flex gap-[10px]">
                                <!-- Drag Icon -->
                                <i class="icon-drag text-[20px] text-gray-600 transition-all pointer-events-none"></i>

                                <!-- Is Default Option -->
                                <div>
                                    <input
                                        :type="[option.type == 'checkbox' || option.type == 'multiselect' ? 'checkbox' : 'radio']"
                                        :name="'bundle_options[' + option.id + '][products][' + element.id + '][is_default]'"
                                        class="sr-only peer"
                                        :id="'bundle_options[' + option.id + '][products][' + element.id + '][is_default]'"
                                        :value="element.is_default"
                                        :checked="element.is_default"
                                        @change="updateIsDefault(element)"
                                    />

                                    <label
                                        class="text-[24px] peer-checked:text-navyBlue cursor-pointer"
                                        :class="[option.type == 'checkbox' || option.type == 'multiselect' ? 'icon-uncheckbox  peer-checked:icon-checked' : 'icon-radio-normal peer-checked:icon-radio-selected']"
                                        :for="'bundle_options[' + option.id + '][products][' + element.id + '][is_default]'"
                                    >
                                    </label>
                                </div>
                                
                                <!-- Image -->
                                <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                    <img src="{{ bagisto_asset('images/product-placeholders/top-angle.svg') }}" class="w-[20px]" />
                                    <p class="text-[6px] text-gray-400 font-semibold">Product Image</p>
                                </div>

                                <!-- Details -->
                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 font-semibold">
                                        @{{ element.product.name }}
                                    </p>

                                    <p class="text-gray-600">
                                        @{{ "@lang('admin::app.catalog.products.edit.types.bundle.option.sku')".replace(':sku', element.product.sku) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-[4px] place-content-start text-right">
                                <p class="text-gray-800 font-semibold">
                                    @{{ $admin.formatPrice(element.product.price) }}    
                                </p>
                                
                                <!-- Hidden Input -->
                                <input
                                    type="hidden"
                                    :name="'bundle_options[' + option.id + '][products][' + element.id + '][product_id]'"
                                    :value="element.product.id"
                                />
                                
                                <input
                                    type="hidden"
                                    :name="'bundle_options[' + option.id + '][products][' + element.id + '][sort_order]'"
                                    :value="index"
                                />

                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.bundle.option.default-qty')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        :name="'bundle_options[' + option.id + '][products][' + element.id + '][qty]'"
                                        v-model="element.qty"
                                        class="flex w-[86px] min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                        :class="[errors['bundle_options[' + option.id + '][products][' + element.id + '][qty]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required|numeric|min_value:1"
                                    ></v-field>
                                </x-admin::form.control-group>

                                <p
                                    class="text-red-600 cursor-pointer"
                                    @click="removeProduct(element)"
                                >
                                    @lang('admin::app.catalog.products.edit.types.bundle.option.delete-btn')
                                </p>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!-- For Empty Option -->
            <div
                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                v-else
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                    class="w-[80px] h-[80px] border border-dashed border-gray-300 rounded-[4px]"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center">
                    <p class="text-[16px] text-gray-400 font-semibold">
                        @lang('admin::app.catalog.products.edit.types.bundle.option.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('admin::app.catalog.products.edit.types.bundle.option.empty-info')
                    </p>
                </div>

                <div
                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-[14px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                    @click="$refs['productSearch' + option.id].openDrawer()"
                >
                    @lang('admin::app.catalog.products.edit.types.bundle.option.add-btn')
                </div>
            </div>

            <!-- Product Search Blade Component -->
            <x-admin::products.search
                ::ref="'productSearch' + option.id"
                ::added-product-ids="addedProductIds"
                ::query-params="{type: 'simple'}"
                @onProductAdded="addSelected($event)"
            ></x-admin::products.search>
        </div>
    </script>

    <script type="module">
        app.component('v-bundle-options', {
            template: '#v-bundle-options-template',

            props: ['errors'],

            data() {
                return {
                    options: @json($product->bundle_options()->with(['product','bundle_option_products', 'bundle_option_products.product.inventory_indices'])->get()),

                    selectedOption: {
                        label: '',
                        type: 'select',
                        is_required: 1,
                        bundle_option_products: []
                    }
                }
            },

            methods: {
                updateOrCreate(params) {
                    if (this.selectedOption.id == undefined) {
                        params.id = 'option_' + this.options.length;

                        params.bundle_option_products = [];

                        this.options.push(params);
                    } else {
                        const indexToUpdate = this.options.findIndex(option => option.id === this.selectedOption.id);

                        this.options[indexToUpdate] = this.selectedOption;
                    }

                    this.resetForm();

                    this.$refs.updateCreateOptionModal.close();
                },

                removeOption(option) {
                    const indexToRemove = this.options.findIndex(option => option.id === option.id);

                    this.options.splice(indexToRemove, 1);
                },

                resetForm() {
                    this.selectedOption = {
                        label: '',
                        type: 'select',
                        is_required: 1,
                        bundle_option_products: []
                    };
                },
            }
        });

        app.component('v-bundle-option-item', {
            template: '#v-bundle-option-item-template',

            props: ['index', 'option', 'errors'],

            data() {
                return {
                    types: {
                        select: {
                            key: 'select',
                            title: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.select.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.select.info')"
                        },

                        radio: {
                            key: 'radio',
                            title: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.radio.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.radio.info')"
                        },

                        multiselect: {
                            key: 'multiselect',
                            title: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.multiselect.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.multiselect.info')"
                        },

                        checkbox: {
                            key: 'checkbox',
                            title: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.checkbox.title')",
                            info: "@lang('admin::app.catalog.products.edit.types.bundle.option.types.checkbox.info')"
                        }
                    },
                }
            },

            computed: {
                addedProductIds() {
                    return this.option.bundle_option_products.map(productOption => productOption.product.id);
                }
            },

            methods: {
                edit() {
                    this.$emit('onEdit', this.option);
                },

                remove() {
                    this.$emit('onRemove', this.option);
                },

                addSelected(selectedProducts) {
                    let self = this;

                    selectedProducts.forEach(function (product) {
                        self.option.bundle_option_products.push({
                            id: 'product_' + self.option.bundle_option_products.length,
                            qty: 1,
                            is_default: 0,
                            product: product,
                        });
                    });
                },

                removeProduct(product) {
                    let index = this.option.bundle_option_products.indexOf(product);

                    this.option.bundle_option_products.splice(index, 1);
                },

                updateIsDefault: function(updatedProductOption) {
                    let self = this;

                    this.option.bundle_option_products.forEach(function(productOption) {
                        if (self.option.type == 'radio' || self.option.type == 'select') {
                            productOption.is_default = productOption.product.id == updatedProductOption.product.id ? 1 : 0;
                        } else {
                            if (productOption.product.id == updatedProductOption.product.id) {
                                productOption.is_default = productOption.is_default ? 0 : 1;
                            }
                        }
                    });
                }
            }
        });
    </script>
@endPushOnce