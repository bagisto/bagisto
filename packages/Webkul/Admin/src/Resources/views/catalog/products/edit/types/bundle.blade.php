@php
    $options = $product->bundle_options()->with([
        'product',
        'bundle_option_products',
        'bundle_option_products.product.inventory_indices',
        'bundle_option_products.product.images',
    ])->get();
@endphp

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.bundle.before', ['product' => $product]) !!}

<v-bundle-options :errors="errors"></v-bundle-options>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.bundle.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-bundle-options-template"
    >
        <div class="box-shadow relative rounded bg-white dark:bg-gray-900">
            <!-- Panel Header -->
            <div class="mb-2.5 flex justify-between gap-5 p-4">
                <div class="flex flex-col gap-2">
                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.catalog.products.edit.types.bundle.title')
                    </p>

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                        @lang('admin::app.catalog.products.edit.types.bundle.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex items-center gap-x-1">
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
                >
                </v-bundle-option-item>
            </div>

            <!-- For Empty Option -->
            <div
                class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                v-else
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-options.svg') }}"
                    class="h-20 w-20 rounded border border-dashed dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center gap-1.5">
                    <p class="text-base font-semibold text-gray-400">
                        @lang('admin::app.catalog.products.edit.types.bundle.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('admin::app.catalog.products.edit.types.bundle.empty-info')
                    </p>
                </div>

                <div
                    class="secondary-button text-sm"
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
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.title')
                            </p>
                        </x-slot>
        
                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.catalog.products.edit.types.bundle.update-create.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="label"
                                    rules="required"
                                    ::value="selectedOption.label"
                                    :label="trans('admin::app.catalog.products.edit.types.bundle.update-create.name')"
                                />
        
                                <x-admin::form.control-group.error control-name="label" />
                            </x-admin::form.control-group>

                            <div class="flex gap-4">
                                <!-- Type -->
                                <x-admin::form.control-group class="flex-1">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.bundle.update-create.type')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        ::value="selectedOption.type"
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
        
                                    <x-admin::form.control-group.error control-name="type" />
                                </x-admin::form.control-group>

                                <!-- Is Required -->
                                <x-admin::form.control-group class="flex-1">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.catalog.products.edit.types.bundle.update-create.is-required')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="is_required"
                                        rules="required"
                                        ::value="selectedOption.is_required"
                                        :label="trans('admin::app.catalog.products.edit.types.bundle.update-create.is-required')"
                                    >
                                        <option value="1">
                                            @lang('admin::app.catalog.products.edit.types.bundle.update-create.yes')
                                        </option>

                                        <option value="0">
                                            @lang('admin::app.catalog.products.edit.types.bundle.update-create.no')
                                        </option>
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error control-name="is_required" />
                                </x-admin::form.control-group>
                            </div>
                        </x-slot>
        
                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-admin::button
                                button-type="button"
                                class="primary-button"
                                :title="trans('admin::app.catalog.products.edit.types.bundle.update-create.save-btn')"
                            />
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-bundle-option-item-template"
    >
        <!-- Panel -->
        <div>
            <!-- Hidden Inputs -->
            <input
                type="hidden"
                :name="'bundle_options[' + option.id + '][{{$currentLocale->code}}][label]'"
                :value="option.label"
            />

            <input
                type="hidden"
                :name="'bundle_options[' + option.id + '][type]'"
                :value="option.type"
            />

            <input
                type="hidden"
                :name="'bundle_options[' + option.id + '][is_required]'"
                :value="option.is_required"
            />

            <input
                type="hidden"
                :name="'bundle_options[' + option.id + '][sort_order]'"
                :value="index"
            />

            <!-- Panel Header -->
            <div class="mb-2.5 flex justify-between gap-5 p-4">
                <div class="flex flex-col gap-2">
                    <p
                        class="text-base font-semibold text-gray-800 dark:text-white"
                        :class="{'required': option.is_required == 1}"
                    >
                        @{{ (index + 1) + '. ' + option.label + ' - ' + types[option.type].title }}
                    </p>

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                        @{{ types[option.type].info }}
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex items-center gap-x-5">
                    <p
                        class="cursor-pointer font-semibold text-blue-600 transition-all hover:underline"
                        @click="$refs['productSearch' + option.id].openDrawer()"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.option.add-btn')
                    </p>

                    <p
                        class="cursor-pointer font-semibold text-blue-600 transition-all hover:underline"
                        @click="edit"
                    >
                        @lang('admin::app.catalog.products.edit.types.bundle.option.edit-btn')
                    </p>

                    <p
                        class="cursor-pointer font-semibold text-red-600 transition-all hover:underline"
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
                    handle=".icon-drag"
                    :list="option.bundle_option_products"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <div class="flex justify-between gap-2.5 border-b border-slate-300 p-4 dark:border-gray-800">
                            <!-- Information -->
                            <div class="flex gap-2.5">
                                <!-- Drag Icon -->
                                <div>
                                    <i class="icon-drag cursor-grab text-xl transition-all hover:text-gray-700 dark:text-gray-300"></i>
                                </div>

                                <!-- Is Default Option -->
                                <div>
                                    <input
                                        :type="[option.type == 'checkbox' || option.type == 'multiselect' ? 'checkbox' : 'radio']"
                                        :id="'bundle_options[' + option.id + '][products][' + element.id + '][is_default]'"
                                        class="peer sr-only"
                                        :name="'bundle_options[' + option.id + '][products][' + element.id + '][is_default]'"
                                        :value="element.is_default"
                                        :checked="element.is_default"
                                        @change="updateIsDefault(element)"
                                    />

                                    <label
                                        class="cursor-pointer text-2xl peer-checked:text-blue-600"
                                        :class="[option.type == 'checkbox' || option.type == 'multiselect' ? 'icon-uncheckbox  peer-checked:icon-checked' : 'icon-radio-normal peer-checked:icon-radio-selected']"
                                        :for="'bundle_options[' + option.id + '][products][' + element.id + '][is_default]'"
                                    >
                                    </label>
                                </div>
                                
                                <!-- Image -->
                                <div
                                    class="relative h-[60px] max-h-[60px] w-full max-w-[60px] overflow-hidden rounded"
                                    :class="{'overflow-hidden rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert': ! element.product.images.length}"
                                >
                                    <template v-if="! element.product.images.length">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                    
                                        <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                            @lang('admin::app.catalog.products.edit.types.bundle.image-placeholder')
                                        </p>
                                    </template>
                
                                    <template v-else>
                                        <img :src="element.product.images[0].url">
                                    </template>
                                </div>

                                <!-- Details -->
                                <div class="grid place-content-start gap-1.5">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @{{ element.product.name }}
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.catalog.products.edit.types.bundle.option.sku')".replace(':sku', element.product.sku) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid place-content-start gap-1 ltr:text-right rtl:text-left">
                                <p class="font-semibold text-gray-800 dark:text-white">
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
                                    <x-admin::form.control-group.label class="required !block">
                                        @lang('admin::app.catalog.products.edit.types.bundle.option.default-qty')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        :name="'bundle_options[' + option.id + '][products][' + element.id + '][qty]'"
                                        v-model="element.qty"
                                        class="min-h-[39px] w-[86px] rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                        :class="[errors['bundle_options[' + option.id + '][products][' + element.id + '][qty]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required|numeric|min_value:1"
                                        label="@lang('admin::app.catalog.products.edit.types.bundle.option.default-qty')"
                                    ></v-field>

                                    <v-error-message
                                        :name="'bundle_options[' + option.id + '][products][' + element.id + '][qty]'"
                                        v-slot="{ message }"
                                    >
                                        <p class="mt-1 text-xs italic text-red-600">
                                            @{{ message }}
                                        </p>
                                    </v-error-message>
                                </x-admin::form.control-group>
                                
                                <p
                                    class="cursor-pointer text-red-600 transition-all hover:underline"
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
                class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                v-else
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                    class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center gap-1.5">
                    <p class="text-base font-semibold text-gray-400">
                        @lang('admin::app.catalog.products.edit.types.bundle.option.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('admin::app.catalog.products.edit.types.bundle.option.empty-info')
                    </p>
                </div>

                <div
                    class="secondary-button text-sm"
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
            />
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

                        this.options[indexToUpdate] = {
                            ...this.selectedOption,
                            ...params
                        };
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

            emits: ['onEdit', 'onRemove'],

            data() {
                return {
                    types: {
                        select: {
                            key: 'select',
                            title: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.select.title')',
                            info: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.select.info')'
                        },

                        radio: {
                            key: 'radio',
                            title: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.radio.title')',
                            info: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.radio.info')'
                        },

                        multiselect: {
                            key: 'multiselect',
                            title: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.multiselect.title')',
                            info: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.multiselect.info')'
                        },

                        checkbox: {
                            key: 'checkbox',
                            title: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.checkbox.title')',
                            info: '@lang('admin::app.catalog.products.edit.types.bundle.option.types.checkbox.info')'
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
                    selectedProducts.forEach((product) => {
                        this.option.bundle_option_products.push({
                            id: 'product_' + this.option.bundle_option_products.length,
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
                    this.option.bundle_option_products.forEach((productOption) => {
                        if (
                            this.option.type == 'radio' 
                            || this.option.type == 'select'
                        ) {
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
