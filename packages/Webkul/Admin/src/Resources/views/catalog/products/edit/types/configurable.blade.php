{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.configurable.before', ['product' => $product]) !!}

<v-product-variations></v-product-variations>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.configurable.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-variations-template">
        <div class="relative bg-white rounded-[4px] box-shadow">
            <!-- Panel Header -->
            <div class="flex flex-wrap gap-[10px] justify-between mb-[10px] p-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p class="text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.products.edit.types.configurable.title')
                    </p>

                    <p class="text-[12px] text-gray-500 font-medium">
                        @lang('admin::app.catalog.products.edit.types.configurable.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div class="flex gap-x-[4px] items-center">
                    <div
                        class="secondary-button"
                        @click="$refs.variantCreateModal.open()"
                    >
                        @lang('admin::app.catalog.products.edit.types.configurable.add-btn')
                    </div>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="grid">
                <v-product-variation-item
                    v-for='(variant, index) in variants'
                    :key="index"
                    :index="index"
                    :variant="variant"
                    :attributes="superAttributes"
                    @onRemoved="removeVariant"
                ></v-product-variation-item>
            </div>

            <!-- For Empty Variations -->
            <div
                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                v-if="! variants.length"
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                    class="w-[80px] h-[80px]"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center">
                    <p class="text-[16px] text-gray-400 font-semibold">
                        @lang('admin::app.catalog.products.edit.types.configurable.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('admin::app.catalog.products.edit.types.configurable.empty-info')
                    </p>
                </div>
                
                <!-- Add Row Button -->
                <div
                    class="secondary-button text-[14px]"
                    @click="$refs.variantCreateModal.open()"
                >
                    @lang('admin::app.catalog.products.edit.types.configurable.add-btn')
                </div>
            </div>

            <!-- Add Variant Form Modal -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, addVariant)">
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="variantCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.catalog.products.edit.types.configurable.create.title')
                            </p>
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <x-admin::form.control-group
                                    v-for='(attribute, index) in superAttributes'
                                >
                                    <x-admin::form.control-group.label class="required">
                                        @{{ attribute.admin_name }}
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        as="select"
                                        :name="attribute.code"
                                        class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400"
                                        :class="[errors[attribute.code] ? 'border border-red-500' : '']"
                                        rules="required"
                                        :label="attribute.admin_name"
                                    >
                                        <option
                                            v-for="option in attribute.options"
                                            :value="option.id"
                                        >
                                            @{{ option.admin_name }}
                                        </option>
                                    </v-field>

                                    <v-error-message
                                        :name="attribute.code"
                                        v-slot="{ message }"
                                    >
                                        <p
                                            class="mt-1 text-red-600 text-xs italic"
                                            v-text="message"
                                        >
                                        </p>
                                    </v-error-message>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.catalog.products.edit.types.configurable.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    {{-- Variation Item Template --}}
    <script type="text/x-template" id="v-product-variation-item-template">
        <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300">
            <!-- Information -->
            <div class="flex gap-[10px]">
                <!-- Form Hidden Fields -->
                <input type="hidden" :name="'variants[' + variant.id + '][sku]'" :value="variant.sku"/>

                <input type="hidden" :name="'variants[' + variant.id + '][name]'" :value="variant.name"/>

                <input type="hidden" :name="'variants[' + variant.id + '][price]'" :value="variant.price"/>

                <input type="hidden" :name="'variants[' + variant.id + '][weight]'" :value="variant.weight"/>

                <input type="hidden" :name="'variants[' + variant.id + '][status]'" :value="variant.status"/>

                <template v-for="attribute in attributes">
                    <input type="hidden" :name="'variants[' + variant.id + '][' + attribute.code + ']'" :value="variant[attribute.code]"/>
                </template>

                <template v-for="inventorySource in inventorySources">
                    <input type="hidden" :name="'variants[' + variant.id + '][inventories][' + inventorySource.id + ']'" :value="variant.inventories[inventorySource.id]"/>
                </template>

                <template v-for="(image, index) in variant.images">
                    <input type="hidden" :name="'variants[' + variant.id + '][images][files][' + image.id + ']'" v-if="! image.is_new"/>

                    <input type="hidden" :name="'variants[' + variant.id + '][images][positions][' + image.id + ']'"/>

                    <input
                        type="file"
                        :name="'variants[' + variant.id + '][images][files][]'"
                        :id="$.uid + '_imageInput_' + index"
                        class="hidden"
                        accept="image/*"
                        :ref="$.uid + '_imageInput_' + index"
                    />
                </template>

                <!-- Image -->
                <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden">
                    <template v-if="! variant.images.length">
                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                    </template>

                    <template v-else>
                        <img :src="variant.images[0].url">
                    </template>
                    
                    <p class="w-full absolute bottom-[5px] text-[6px] text-gray-400 text-center font-semibold">
                        @lang('admin::app.catalog.products.edit.types.configurable.image-placeholder')
                    </p>
                </div>

                <!-- Details -->
                <div class="grid gap-[6px] place-content-start">
                    <p
                        class="text-[16x] text-gray-800 font-semibold"
                        v-text="variant.name ?? 'N/A'"
                    >
                    </p>

                    <p class="text-gray-600">
                        @{{ "@lang('admin::app.catalog.products.edit.types.configurable.sku')".replace(':sku', variant.sku) }}
                    </p>

                    <div class="flex gap-[6px] place-items-start">
                        <span
                            class="label-active"
                            v-if="isDefault"
                        >
                            Default
                        </span>

                        <p class="text-gray-600">
                            <span
                                class="after:content-[',_'] last:after:content-['']"
                                v-for='(attribute, index) in attributes'
                            >
                                @{{ attribute.admin_name + ': ' + optionName(attribute, variant[attribute.code]) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid gap-[4px] place-content-start text-right">
                <p class="text-gray-800 font-semibold">
                    @{{ $admin.formatPrice(variant.price) }}  
                </p>

                <p class="text-gray-800 font-semibold">
                    @{{ "@lang('admin::app.catalog.products.edit.types.configurable.qty')".replace(':qty', totalQty) }}
                </p>

                <div class="flex gap-[10px]">
                    <!-- Remove -->
                    <p
                        class="text-red-600 cursor-pointer transition-all hover:underline"
                        @click="remove"
                    >
                        @lang('admin::app.catalog.products.edit.types.configurable.delete-btn')
                    </p>
                    
                    <!-- Edit -->
                    <div>
                        <p
                            class="text-emerald-600 cursor-pointer transition-all hover:underline"
                            @click="$refs.editVariantDrawer.open()"
                        >
                            @lang('admin::app.catalog.products.edit.types.configurable.edit-btn')
                        </p>

                        <!-- Edit Drawer -->
                        <x-admin::form
                            v-slot="{ meta, errors, handleSubmit }"
                            as="div"
                        >
                            <form @submit="handleSubmit($event, update)">
                                <!-- Edit Drawer -->
                                <x-admin::drawer
                                    ref="editVariantDrawer"
                                    class="text-left"
                                >
                                    <!-- Drawer Header -->
                                    <x-slot:header>
                                        <div class="flex justify-between items-center">
                                            <p class="text-[20px] font-medium">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.title')
                                            </p>

                                            <button class="mr-[45px] primary-button">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.save-btn')
                                            </button>
                                        </div>
                                    </x-slot:header>

                                    <!-- Drawer Content -->

                                    <x-slot:content>
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            ::value="variant.id"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.name')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="name"
                                                ::value="variant.name"
                                                rules="required"
                                                :label="trans('admin::app.catalog.products.edit.types.configurable.edit.name')"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="name"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.sku')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="sku"
                                                ::value="variant.sku"
                                                rules="required"
                                                :label="trans('admin::app.catalog.products.edit.types.configurable.edit.sku')"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="sku"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <div class="flex gap-[16px] mb-[10px]">
                                            <x-admin::form.control-group class="flex-1">
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('admin::app.catalog.products.edit.types.configurable.edit.price')
                                                </x-admin::form.control-group.label>
                    
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="price"
                                                    ::value="variant.price"
                                                    ::rules="{required: true, decimal: true, min_value: 0}"
                                                    :label="trans('admin::app.catalog.products.edit.types.configurable.edit.price')"
                                                >
                                                </x-admin::form.control-group.control>
                    
                                                <x-admin::form.control-group.error control-name="price"></x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group class="flex-1">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.catalog.products.edit.types.configurable.edit.status')
                                                </x-admin::form.control-group.label>
                    
                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="status"
                                                    ::value="variant.status"
                                                    rules="required"
                                                    :label="trans('admin::app.catalog.products.edit.types.configurable.edit.status')"
                                                >
                                                    <option value="1">
                                                        @lang('admin::app.catalog.products.edit.types.configurable.edit.enabled')
                                                    </option>

                                                    <option value="0">
                                                        @lang('admin::app.catalog.products.edit.types.configurable.edit.disabled')
                                                    </option>
                                                </x-admin::form.control-group.control>
                    
                                                <x-admin::form.control-group.error control-name="status"></x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.weight')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="weight"
                                                ::value="variant.weight"
                                                ::rules="{ required: true, regex: /^([0-9]*[1-9][0-9]*(\.[0-9]+)?|[0]+\.[0-9]*[1-9][0-9]*)$/ }"
                                                :label="trans('admin::app.catalog.products.edit.types.configurable.edit.weight')"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error control-name="weight"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <!-- Inventories -->
                                        <div class="grid mt-[20px]">
                                            <p class="mb-[10px] text-gray-800 font-semibold">
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.quantities')
                                            </p>

                                            <div class="grid grid-cols-3 gap-[16px] mb-[10px]">
                                                <x-admin::form.control-group
                                                    class="mb-[0px]"
                                                    v-for='inventorySource in inventorySources'
                                                >
                                                    <x-admin::form.control-group.label>
                                                        @{{ inventorySource.name }}
                                                    </x-admin::form.control-group.label>

                                                    <v-field
                                                        type="text"
                                                        :name="'inventories[' + inventorySource.id + ']'"
                                                        v-model="variant.inventories[inventorySource.id]"
                                                        class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400"
                                                        :class="[errors['inventories[' + inventorySource.id + ']'] ? 'border border-red-500' : '']"
                                                        rules="numeric|min:0"
                                                        :label="inventorySource.name"
                                                    >
                                                    </v-field>

                                                    <v-error-message
                                                        :name="'inventories[' + inventorySource.id + ']'"
                                                        v-slot="{ message }"
                                                    >
                                                        <p
                                                            class="mt-1 text-red-600 text-xs italic"
                                                            v-text="message"
                                                        >
                                                        </p>
                                                    </v-error-message>
                                                </x-admin::form.control-group>
                                            </div>
                                        </div>

                                        <v-product-images :uploaded-images="variant.images"></v-product-images>

                                        <p
                                            class="mt-[10px] font-semibold text-gray-800"
                                            v-if="typeof variant.id !== 'string'"
                                        >
                                            @lang('admin::app.catalog.products.edit.types.configurable.edit.edit-info')

                                            <a
                                                :href="'{{ route('admin.catalog.products.edit', ':id') }}'.replace(':id', variant.id)" 
                                                class="inline-block mt-[5px] text-blue-500 hover:text-blue-600"
                                                target="_blank"
                                            >
                                                @lang('admin::app.catalog.products.edit.types.configurable.edit.edit-link-title')
                                            </a>
                                        </p>
                                    </x-slot:content>
                                </x-admin::drawer>
                            </form>
                        </x-admin::form>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-variations', {
            template: '#v-product-variations-template',

            data: function () {
                return {
                    defaultId: parseInt('{{ $product->additional['default_variant_id'] ?? null }}'),

                    variants: @json($product->variants()->with(['images', 'inventories'])->get()),

                    superAttributes: @json($product->super_attributes()->with('options')->get()),

                    selectedVariant: {
                        id: null,
                        name: '',
                        sku: '',
                        price: 0,
                        status: 1,
                        weight: 0,
                        inventories: {},
                        images: []
                    }
                }
            },

            methods: {
                addVariant(params, { resetForm }) {
                    let self = this;

                    let filteredVariants = this.variants.filter(function (variant) {
                        let matchCount = 0;

                        for (let key in params) {
                            if (variant[key] == params[key]) {
                                matchCount++;
                            }
                        }

                        return matchCount == self.superAttributes.length;
                    })

                    if (filteredVariants.length) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "{{ trans('admin::app.catalog.products.edit.types.configurable.create.variant-already-exists') }}" });

                        return;
                    }

                    const optionIds = Object.values(params);

                    this.variants.push(Object.assign({
                        id: 'variant_' + this.variants.length,
                        sku: '{{ $product->sku }}' + '-variant-' + optionIds.join('-'),
                        name: '',
                        price: 0,
                        status: 1,
                        weight: 0,
                        inventories: {},
                        images: []
                    }, params));

                    resetForm();

                    this.$refs.variantCreateModal.close();
                },

                removeVariant(variant) {
                    this.variants.splice(this.variants.indexOf(variant), 1);
                }
            }
        });

        app.component('v-product-variation-item', {
            template: '#v-product-variation-item-template',

            props: [
                'variant',
                'attributes'
            ],

            data() {
                return {
                    inventorySources: @json($inventorySources),
                }
            },

            created() {
                let inventories = {};
                
                if (Array.isArray(this.variant.inventories)) {
                    this.variant.inventories.forEach(function (inventory) {
                        inventories[inventory.inventory_source_id] = inventory.qty;
                    });

                    this.variant.inventories = inventories; 
                }
            },

            mounted() {
                if (typeof this.variant.id === 'string' || this.variant.id instanceof String) {
                    this.$refs.editVariantDrawer.open();
                }
            },

            computed: {
                isDefault() {
                    return this.variant.id == this.$parent.defaultId;
                },

                totalQty() {
                    let totalQty = 0;

                    for (let key in this.variant.inventories) {
                        totalQty += parseInt(this.variant.inventories[key]);
                    }

                    return totalQty;
                }
            },

            methods: {
                optionName: function (attribute, optionId) {
                    return attribute.options.find(function (option) {
                        return option.id == optionId;
                    }).admin_name;
                },

                update(params) {
                    Object.assign(this.variant, params);

                    this.setFiles();

                    this.$refs.editVariantDrawer.close();
                },

                setFiles() {
                    let self = this;

                    this.variant.images.forEach(function (image, index) {
                        if (image.file instanceof File) {
                            image.is_new = 1;

                            const dataTransfer = new DataTransfer();

                            dataTransfer.items.add(image.file);

                            self.$refs[self.$.uid + '_imageInput_' + index][0].files = dataTransfer.files;
                        } else {
                            image.is_new = 0;
                        }
                    });
                },

                remove: function () {
                    this.$emit('onRemoved', this.variant);
                },
            }
        });
    </script>
@endPushOnce