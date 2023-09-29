@php
    $options = $product->bundle_options()->with(['product','bundle_option_products','bundle_option_products.product.inventory_indices','bundle_option_products.product.images'])->get();
@endphp

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.bundle.before', ['product' => $product]) !!}

<v-bundle-options :errors="errors"></v-bundle-options>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.bundle.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-bundle-options-template">
        <div class="relative bg-white dark:bg-gray-900  rounded-[4px] box-shadow">
            <!-- Panel Header -->
            <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('admin::app.catalog.products.edit.types.bundle.title')
                    </p>

                    <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                        @lang('admin::app.catalog.products.edit.types.bundle.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-[4px] items-center">
                    <div
                        class="secondary-button"
                        @click="resetForm(); $refs.updateCreateOptionModal.open()"
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
                    class="w-[80px] h-[80px] border border-dashed dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
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
                    class="secondary-button text-[14px]"
                    @click="resetForm(); $refs.updateCreateOptionModal.open()"
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
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.title')
                            </p>
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800  ">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.bundle.update-create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="label"
                                        rules="required"
                                        v-model="selectedOption.label"
                                        :label="trans('admin::app.catalog.products.edit.types.bundle.update-create.name')"
                                    >
                                    </x-admin::form.control-group.control>
            
                                    <x-admin::form.control-group.error control-name="label"></x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <div class="flex gap-[16px]">
                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.bundle.update-create.type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="type"
                                            rules="required"
                                            v-model="selectedOption.type"
                                            :label="trans('admin::app.catalog.products.edit.types.bundle.update-create.type')"
                                        >
                                            <option value="select">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.select')
                                            </option>

                                            <option value="radio">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.radio')
                                            </option>

                                            <option value="checkbox">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.checkbox')
                                            </option>

                                            <option value="multiselect">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.multiselect')
                                            </option>
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error control-name="type"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="flex-1">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.catalog.products.edit.types.bundle.update-create.is-required')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="is_required"
                                            rules="required"
                                            v-model="selectedOption.is_required"
                                            :label="trans('admin::app.catalog.products.edit.types.bundle.update-create.is-required')"
                                        >
                                            <option value="1">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.yes')
                                            </option>

                                            <option value="0">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.no')
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
                                    class="primary-button"
                                >
                                    @lang('admin::app.catalog.products.edit.types.bundle.update-create.save-btn')
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
            <input type="hidden" :name="'bundle_options[' + option.id + '][{{$currentLocale->code}}][label]'" :value="option.label"/>

            <input type="hidden" :name="'bundle_options[' + option.id + '][type]'" :value="option.type"/>

            <input type="hidden" :name="'bundle_options[' + option.id + '][is_required]'" :value="option.is_required"/>

            <input type="hidden" :name="'bundle_options[' + option.id + '][sort_order]'" :value="index"/>

            <!-- Panel Header -->
            <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p
                        class="text-[16px] text-gray-800 dark:text-white font-semibold"
                        :class="{'required': option.is_required}"
                    >
                        @{{ (index + 1) + '. ' + option.label + ' - ' + types[option.type].title }}
                    </p>

                    <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                        @{{ types[option.type].info }}
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-[20px] items-center">
                    <p
                        class="text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                        @click="$refs['productSearch' + option.id].openDrawer()"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.option.add-btn')
                    </p>

                    <p
                        class="text-blue-600 font-semibold cursor-pointer transition-all hover:underline"
                        @click="edit"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.option.edit-btn')
                    </p>

                    <p
                        class="text-red-600 font-semibold cursor-pointer transition-all hover:underline"
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
                    v-bind="{animation: 200}"
                    :list="option.bundle_option_products"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <div class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300 dark:border-gray-800">
                            <!-- Information -->
                            <div class="flex gap-[10px]">
                                <!-- Drag Icon -->
                                <i class="icon-drag text-[20px] text-gray-600 dark:text-gray-300 transition-all pointer-events-none"></i>

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
                                        class="text-[24px] peer-checked:text-blue-600 cursor-pointer"
                                        :class="[option.type == 'checkbox' || option.type == 'multiselect' ? 'icon-uncheckbox  peer-checked:icon-checked' : 'icon-radio-normal peer-checked:icon-radio-selected']"
                                        :for="'bundle_options[' + option.id + '][products][' + element.id + '][is_default]'"
                                    >
                                    </label>
                                </div>
                                
                                <!-- Image -->
                                <div
                                    class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded-[4px] overflow-hidden"
                                    :class="{'border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion overflow-hidden': ! element.product.images.length}"
                                >
                                    <template v-if="! element.product.images.length">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                    
                                        <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                                            @lang('admin::app.catalog.products.edit.types.bundle.image-placeholder')
                                        </p>
                                    </template>
                
                                    <template v-else>
                                        <img :src="element.product.images[0].url">
                                    </template>
                                </div>

                                <!-- Details -->
                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">
                                        @{{ element.product.name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.catalog.products.edit.types.bundle.option.sku')".replace(':sku', element.product.sku) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-[4px] place-content-start text-right">
                                <p class="text-gray-800 font-semibold dark:text-white">
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
                                        class="flex w-[86px] min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                        :class="[errors['bundle_options[' + option.id + '][products][' + element.id + '][qty]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required|numeric|min_value:1"
                                    ></v-field>
                                </x-admin::form.control-group>

                                <p
                                    class="text-red-600 cursor-pointer transition-all hover:underline"
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
                    class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion"
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
                    class="secondary-button text-[14px]"
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
                    options: @json($options),

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
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.options.indexOf(option);

                            this.options.splice(index, 1);
                        }
                    });
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
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.option.bundle_option_products.indexOf(product);

                            this.option.bundle_option_products.splice(index, 1);
                        }
                    });
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