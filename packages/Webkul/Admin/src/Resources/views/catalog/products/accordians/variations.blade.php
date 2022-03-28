@push('css')
    <style>
        .table th.price, .table th.weight {
            width: 100px;
        }

        .table th.actions {
            width: 85px;
        }

        .table td.actions .icon {
            margin-top: 8px;
        }
    </style>
@endpush

@php
    $variantImages = [];

    foreach ($product->variants as $variant) {
        foreach ($variant->images as $image) {
            $variantImages[$variant->id] = $image;
        }
    }
@endphp

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.variations.before', ['product' => $product]) !!}

<accordian title="{{ __('admin::app.catalog.products.variations') }}" :active="true">
    <div slot="body">
        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.variations.controls.before', ['product' => $product]) !!}

        <button type="button" class="btn btn-md btn-primary" @click="showModal('addVariant')">
            {{ __('admin::app.catalog.products.add-variant-btn-title') }}
        </button>

        <variant-list></variant-list>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.variations.controls.after', ['product' => $product]) !!}
    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.variations.after', ['product' => $product]) !!}

<modal id="addVariant" :is-open="modalIds.addVariant">
    <h3 slot="header">{{ __('admin::app.catalog.products.add-variant-title') }}</h3>

    <div slot="body">
        <variant-form></variant-form>
    </div>
</modal>

@push('scripts')
    <script type="text/x-template" id="variant-form-template">
        <form
            method="POST"
            action="{{ route('admin.catalog.products.store') }}"
            data-vv-scope="add-variant-form" @submit.prevent="addVariant('add-variant-form')">
            <div class="page-content">
                <div class="form-container">
                    <div
                        v-for='(attribute, index) in super_attributes'
                        :class="['control-group', errors.has('add-variant-form.' + attribute.code) ? 'has-error' : '']">
                        <label
                            class="required"
                            :for="attribute.code"
                            v-text="attribute.admin_name">
                        </label>

                        <select
                            v-validate="'required'"
                            v-model="variant[attribute.code]"
                            class="control"
                            :id="attribute.code"
                            :name="attribute.code"
                            :data-vv-as="'&quot;' + attribute.admin_name + '&quot;'"
                        >
                            <option
                                v-for='(option, index) in attribute.options'
                                :value="option.id"
                                v-text="option.admin_name">
                            </option>
                        </select>

                        <span
                            class="control-error"
                            v-text="errors.first('add-variant-form.' + attribute.code)"
                            v-if="errors.has('add-variant-form.' + attribute.code)">
                        </span>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.add-variant-title') }}
                    </button>
                </div>
            </div>
        </form>
    </script>

    <script type="text/x-template" id="variant-list-template">
        <div class="table" style="margin-top: 20px; overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th class="is-default">{{ __('admin::app.catalog.products.is-default') }}</th>
                        <th class="sku">{{ __('admin::app.catalog.products.sku') }}</th>
                        <th>{{ __('admin::app.catalog.products.name') }}</th>
                        <th>{{ __('admin::app.catalog.products.images') }}</th>
                        <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>
                        <th class="price">{{ __('admin::app.catalog.products.price') }}</th>
                        <th class="weight">{{ __('admin::app.catalog.products.weight') }}</th>
                        <th class="status">{{ __('admin::app.catalog.products.status') }}</th>
                        <th class="actions"></th>
                    </tr>
                </thead>

                <tbody>
                    <variant-item
                        v-for='(variant, index) in variants'
                        :key="index"
                        :index="index"
                        :variant="variant" @onRemoveVariant="removeVariant($event)">
                    </variant-item>
                </tbody>
            </table>
        </div>
    </script>

    <script type="text/x-template" id="variant-item-template">
        <tr>
            <td>
                <div class="control-group">
                    <span class="radio">
                        <input
                            id="default_variant_id"
                            type="radio"
                            name="default_variant_id"
                            :value="variant.id"
                            v-on:change="checkDefaultVariant(variant.id)"
                            :checked="variant.id == default_variant_id">
                        <label class="radio-view" :for="[variantInputName + '[default_variant_id]']"></label>
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(variantInputName + '[sku]') ? 'has-error' : '']">
                    <input
                        class="control"
                        type="text"
                        :name="[variantInputName + '[sku]']"
                        v-model="variant.sku"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('admin::app.catalog.products.sku') }}&quot;"
                        v-slugify/>

                    <span
                        class="control-error"
                        v-text="errors.first(variantInputName + '[sku]')"
                        v-if="errors.has(variantInputName + '[sku]')">
                    </span>
                </div>
            </td>

            <td>
                <div
                    :class="['control-group', errors.has(variantInputName + '[name]') ? 'has-error' : '']">
                    <input
                        class="control"
                        type="text"
                        :name="[variantInputName + '[name]']"
                        v-model="variant.name"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('admin::app.catalog.products.name') }}&quot;"/>

                    <span
                        class="control-error"
                        v-text="errors.first(variantInputName + '[name]')"
                        v-if="errors.has(variantInputName + '[name]')">
                    </span>
                </div>

                <div class="item-options" style="margin-top: 10px">
                    <div v-for='(attribute, index) in superAttributes'>
                        <b>@{{ attribute.admin_name }} : </b>@{{ optionName(variant[attribute.code]) }}

                        <input
                            type="hidden"
                            :name="[variantInputName + '[' + attribute.code + ']']"
                            :value="variant[attribute.code]"
                        />
                    </div>
                </div>
            </td>

            <td>
                <div :class="['control-group', errors.has(variantInputName + '[images][files][' + index + ']') ? 'has-error' : '']">
                    <div v-for='(image, index) in items' class="image-wrapper variant-image">
                        <label class="image-item" v-bind:class="{ 'has-image': imageData[index] }">
                            <input
                                type="hidden"
                                :name="[variantInputName + '[images][files][' + image.id + ']']"
                                v-if="! new_image[index]"/>

                            <input
                                :ref="'imageInput' + index"
                                :id="image.id"
                                type="file"
                                :name="[variantInputName + '[images][files][' + index + ']']"
                                accept="image/*"
                                multiple="multiple"
                                v-validate="'mimes:image/*'"
                                @change="addImageView($event, index)"/>

                            <img
                                class="preview"
                                :src="imageData[index]"
                                v-if="imageData[index]">
                        </label>

                        <span class="icon trash-icon" @click="removeImage(image)"></span>
                    </div>

                    <label class="btn btn-lg btn-primary add-image" @click="createFileType">
                        {{ __('admin::app.catalog.products.add-image-btn-title') }}
                    </label>
                </div>
            </td>

            <td>
                <button style="width: 100%;" type="button" class="dropdown-btn dropdown-toggle">
                    @{{ totalQty }}

                    <i class="icon arrow-down-icon"></i>
                </button>

                <div class="dropdown-list">
                    <div class="dropdown-container">
                        <ul>
                            <li v-for='(inventorySource, index) in inventorySources'>
                                <div class="control-group"
                                    :class="[errors.has(variantInputName + '[inventories][' + inventorySource.id + ']') ? 'has-error' : '']">
                                    <label v-text="inventorySource.name"></label>

                                    <input
                                        type="text"
                                        :name="[variantInputName + '[inventories][' + inventorySource.id + ']']"
                                        v-model="inventories[inventorySource.id]" class="control"
                                        v-validate="'numeric|min:0'"
                                        :data-vv-as="'&quot;' + inventorySource.name  + '&quot;'"
                                        v-on:keyup="updateTotalQty()"/>

                                    <span
                                        class="control-error"
                                        v-text="errors.first(variantInputName + '[inventories][' + inventorySource.id + ']')"
                                        v-if="errors.has(variantInputName + '[inventories][' + inventorySource.id + ']')">
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </td>

            <td>
                <div :class="['control-group', errors.has(variantInputName + '[price]') ? 'has-error' : '']">
                    <input
                        class="control"
                        type="number"
                        :name="[variantInputName + '[price]']"
                        v-model="variant.price"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('admin::app.catalog.products.price') }}&quot;"
                        step="any"/>

                    <span
                        class="control-error"
                        v-text="errors.first(variantInputName + '[price]')"
                        v-if="errors.has(variantInputName + '[price]')">
                    </span>
                </div>
            </td>

            <td>
                <div :class="['control-group', errors.has(variantInputName + '[weight]') ? 'has-error' : '']">
                    <input
                        type="number"
                        :name="[variantInputName + '[weight]']" class="control"
                        v-model="variant.weight"
                        v-validate="'required'"
                        data-vv-as="&quot;{{ __('admin::app.catalog.products.weight') }}&quot;"
                        step="any"/>

                    <span
                        class="control-error"
                        v-text="errors.first(variantInputName + '[weight]')"
                        v-if="errors.has(variantInputName + '[weight]')">
                    </span>
                </div>
            </td>

            <td>
                <div class="control-group">
                    <select
                        class="control"
                        type="text"
                        v-model="variant.status"
                        :name="[variantInputName + '[status]']">

                        <option
                            value="1"
                            :selected="variant.status">
                            {{ __('admin::app.catalog.products.enabled') }}
                        </option>

                        <option
                            value="0"
                            :selected="!variant.status">
                            {{ __('admin::app.catalog.products.disabled') }}
                        </option>
                    </select>
                </div>
            </td>

            <td class="actions">
                <a :href="['{{ route('admin.catalog.products.index') }}/edit/' + variant.id]">
                    <i class="icon pencil-lg-icon"></i>
                </a>

                <i class="icon remove-icon" @click="removeVariant()"></i>
            </td>
        </tr>
    </script>

    <script>
        $(document).ready(function () {
            Vue.config.ignoredElements = [
                'variant-form',
                'variant-list',
                'variant-item'
            ];
        });

        let super_attributes = @json(app('\Webkul\Product\Repositories\ProductRepository')->getSuperAttributes($product));
        let variants = @json($product->variants);

        Vue.component('variant-form', {
            data: function () {
                return {
                    variant: {},
                    super_attributes: super_attributes
                }
            },

            template: '#variant-form-template',

            created: function () {
                this.resetModel();
            },

            methods: {
                addVariant: function (formScope) {
                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {
                            let self = this;

                            let filteredVariants = variants.filter(function (variant) {
                                let matchCount = 0;

                                for (let key in self.variant) {
                                    if (variant[key] == self.variant[key]) {
                                        matchCount++;
                                    }
                                }

                                return matchCount == self.super_attributes.length;
                            })

                            if (filteredVariants.length) {
                                this.$parent.closeModal();

                                window.flashMessages = [{
                                    'type': 'alert-error',
                                    'message': "{{ __('admin::app.catalog.products.variant-already-exist-message') }}"
                                }];

                                this.$root.addFlashMessages()
                            } else {
                                let optionIds = [];
                                for (let key in self.variant) {
                                    optionIds.push(self.variant[key]);
                                }

                                variants.push(Object.assign({
                                    sku: '{{ $product->sku }}' + '-variant-' + optionIds.join('-'),
                                    name: '',
                                    price: 0,
                                    weight: 0,
                                    status: 1
                                }, this.variant));

                                this.resetModel();

                                this.$parent.closeModal();
                            }
                        }
                    });
                },

                resetModel: function () {
                    let self = this;

                    this.super_attributes.forEach(function (attribute) {
                        self.variant[attribute.code] = '';
                    })
                }
            }
        });

        Vue.component('variant-list', {
            template: '#variant-list-template',

            inject: ['$validator'],

            data: function () {
                return {
                    variants: variants,

                    old_variants: @json(old('variants')),

                    superAttributes: super_attributes
                }
            },

            created: function () {
                let index = 0;

                for (let key in this.old_variants) {
                    let variant = this.old_variants[key];

                    if (key.indexOf('variant_') !== -1) {
                        let inventories = [];

                        for (let inventorySourceId in variant['inventories']) {
                            inventories.push({
                                'qty': variant['inventories'][inventorySourceId],
                                'inventory_source_id': inventorySourceId
                            })
                        }

                        variant['inventories'] = inventories;

                        variants.push(variant);
                    } else {
                        for (let code in variant) {
                            if (code != 'inventories') {
                                variants[index][code] = variant[code];
                            } else {
                                variants[index][code] = [];

                                for (let inventorySourceId in variant[code]) {
                                    variants[index][code].push({
                                        'qty': variant[code][inventorySourceId],
                                        'inventory_source_id': inventorySourceId
                                    })
                                }
                            }
                        }
                    }

                    index++;
                }
            },

            methods: {
                removeVariant: function (variant) {
                    let index = this.variants.indexOf(variant)

                    this.variants.splice(index, 1)
                },
            }
        });

        Vue.component('variant-item', {
            template: '#variant-item-template',

            props: ['index', 'variant'],

            inject: ['$validator'],

            data: function () {
                return {
                    default_variant_id: parseInt('{{ $product->additional['default_variant_id'] ?? null }}'),
                    inventorySources: @json($inventorySources),
                    inventories: {},
                    totalQty: 0,
                    superAttributes: super_attributes,
                    items: [],
                    imageCount: 0,
                    images: {},
                    imageData: [],
                    new_image: [],
                }
            },

            created: function () {
                let self = this;

                this.inventorySources.forEach(function (inventorySource) {
                    self.inventories[inventorySource.id] = self.sourceInventoryQty(inventorySource.id)
                    self.totalQty += parseInt(self.inventories[inventorySource.id]);
                })
            },

            mounted () {
                let self = this;

                self.variant.images.forEach(function(image) {
                    self.items.push(image)
                    self.imageCount++;

                    if (image.id && image.url) {
                        self.imageData.push(image.url);
                    } else if (image.id && image.file) {
                        self.readFile(image.file);
                    }
                });
            },

            computed: {
                variantInputName: function () {
                    if (this.variant.id)
                        return "variants[" + this.variant.id + "]";

                    return "variants[variant_" + this.index + "]";
                }
            },

            methods: {
                removeVariant: function () {
                    this.$emit('onRemoveVariant', this.variant);
                },

                checkDefaultVariant: function (variantId) {
                    this.default_variant_id = variantId;
                },

                optionName: function (optionId) {
                    let optionName = '';

                    this.superAttributes.forEach(function (attribute) {
                        attribute.options.forEach(function (option) {
                            if (optionId == option.id) {
                                optionName = option.admin_name;
                            }
                        });
                    })

                    return optionName;
                },

                sourceInventoryQty: function (inventorySourceId) {
                    if (!Array.isArray(this.variant.inventories))
                        return 0;

                    let inventories = this.variant.inventories.filter(function (inventory) {
                        return inventorySourceId === parseInt(inventory.inventory_source_id);
                    })

                    if (inventories.length)
                        return inventories[0]['qty'];

                    return 0;
                },

                updateTotalQty: function () {
                    this.totalQty = 0;

                    for (let key in this.inventories) {
                        this.totalQty += parseInt(this.inventories[key]);
                    }
                },

                createFileType: function() {
                    let self = this;

                    this.imageCount++;

                    this.items.push({'id': 'image_' + this.imageCount});

                    this.imageData[this.imageData.length] = '';
                },

                removeImage (image) {
                    let index = this.items.indexOf(image);

                    Vue.delete(this.items, index);

                    Vue.delete(this.imageData, index);
                },

                addImageView: function($event, index) {
                    let ref = "imageInput" + index;
                    let imageInput = this.$refs[ref][0];

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            this.readFile(imageInput.files[0], index);

                        } else {
                            imageInput.value = "";

                            alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                        }
                    }
                },

                readFile: function(image, index) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.imageData.splice(index, 1, e.target.result);
                    }

                    reader.readAsDataURL(image);

                    this.new_image[index] = 1;
                },
            }
        });
    </script>
@endpush
