@if ($product->type == 'configurable')

@section('css')
    @parent
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
        .table td.actions .icon.pencil-lg-icon {
            margin-right: 10px;
        }
    </style>
@stop

<accordian :title="'{{ __($accordian['name']) }}'" :active="true">
    <div slot="body">

        <button type="button" class="btn btn-md btn-primary" @click="showModal('addVariant')">
            {{ __('admin::app.catalog.products.add-variant-btn-title') }}
        </button>

        <variant-list></variant-list>

    </div>
</accordian>

<modal id="addVariant" :is-open="modalIds.addVariant">
    <h3 slot="header">{{ __('admin::app.catalog.products.add-variant-title') }}</h3>

    <div slot="body">
        <variant-form></variant-form>
    </div>
</modal>

@push('scripts')
    @parent

    <script type="text/x-template" id="variant-form-template">
        <form method="POST" action="{{ route('admin.catalog.products.store') }}" data-vv-scope="add-variant-form" @submit.prevent="addVariant('add-variant-form')">

            <div class="page-content">
                <div class="form-container">

                    <div v-for='(attribute, index) in super_attributes' class="control-group" :class="[errors.has('add-variant-form.' + attribute.code) ? 'has-error' : '']">
                        <label :for="attribute.code" class="required">@{{ attribute.admin_name }}</label>
                        <select v-validate="'required'" v-model="variant[attribute.code]" class="control" :id="attribute.code" :name="attribute.code" :data-vv-as="'&quot;' + attribute.admin_name + '&quot;'">
                            <option v-for='(option, index) in attribute.options' :value="option.id">@{{ option.admin_name }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('add-variant-form.' + attribute.code)">@{{ errors.first('add-variant-form.' + attribute.code) }}</span>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.add-variant-title') }}
                    </button>

                </div>
            </div>

        </form>
    </script>

    <script type="text/x-template" id="variant-list-template">
        <div class="table" style="margin-top: 20px; overflow-x: unset;">
            <table>

                <thead>
                    <tr>
                        <th class="sku">{{ __('admin::app.catalog.products.sku') }}</th>
                        <th>{{ __('admin::app.catalog.products.name') }}</th>

                        @foreach ($product->super_attributes as $attribute)
                            <th class="{{ $attribute->code }}" style="width: 150px">{{ $attribute->admin_name }}</th>
                        @endforeach

                        <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>
                        <th class="price">{{ __('admin::app.catalog.products.price') }}</th>
                        <th class="weight">{{ __('admin::app.catalog.products.weight') }}</th>
                        <th class="status">{{ __('admin::app.catalog.products.status') }}</th>
                        <th class="actions"></th>
                    </tr>
                </thead>

                <tbody>

                    <variant-item v-for='(variant, index) in variants' :variant="variant" :key="index" :index="index" @onRemoveVariant="removeVariant($event)"></variant-item>

                </tbody>

            </table>
        </div>
    </script>

    <script type="text/x-template" id="variant-item-template">
        <tr>
            <td>
                <div class="control-group" :class="[errors.has(variantInputName + '[sku]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" v-model="variant.sku" :name="[variantInputName + '[sku]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.sku') }}&quot;" v-slugify/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[sku]')">@{{ errors.first(variantInputName + '[sku]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(variantInputName + '[name]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" v-model="variant.name"  :name="[variantInputName + '[name]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.name') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[name]')">@{{ errors.first(variantInputName + '[name]') }}</span>
                </div>
            </td>

            <td v-for='(attribute, index) in superAttributes'>
                <div class="control-group">
                    <input type="hidden" :name="[variantInputName + '[' + attribute.code + ']']" :value="variant[attribute.code]"/>
                    <input type="text" class="control" :value="optionName(variant[attribute.code])" readonly/>
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
                                <div class="control-group" :class="[errors.has(variantInputName + '[inventories][' + inventorySource.id + ']') ? 'has-error' : '']">
                                    <label>@{{ inventorySource.name }}</label>
                                    <input type="text" v-validate="'numeric|min:0'" :name="[variantInputName + '[inventories][' + inventorySource.id + ']']" v-model="inventories[inventorySource.id]" class="control" v-on:keyup="updateTotalQty()" :data-vv-as="'&quot;' + inventorySource.name  + '&quot;'"/>
                                    <span class="control-error" v-if="errors.has(variantInputName + '[inventories][' + inventorySource.id + ']')">@{{ errors.first(variantInputName + '[inventories][' + inventorySource.id + ']') }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(variantInputName + '[price]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" v-model="variant.price" :name="[variantInputName + '[price]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.price') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[price]')">@{{ errors.first(variantInputName + '[price]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group" :class="[errors.has(variantInputName + '[weight]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" v-model="variant.weight"  :name="[variantInputName + '[weight]']" class="control" data-vv-as="&quot;{{ __('admin::app.catalog.products.weight') }}&quot;"/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[weight]')">@{{ errors.first(variantInputName + '[weight]') }}</span>
                </div>
            </td>

            <td>
                <div class="control-group">
                    <select type="text" v-model="variant.status" :name="[variantInputName + '[status]']" class="control">
                        <option value="1" :selected="variant.status">{{ __('admin::app.catalog.products.enabled') }}</option>
                        <option value="0" :selected="!variant.status">{{ __('admin::app.catalog.products.disabled') }}</option>
                    </select>
                </div>
            </td>

            <td class="actions">
                <a :href="['{{ route('admin.catalog.products.index') }}/edit/' + variant.id]"><i class="icon pencil-lg-icon"></i></a>
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

        var super_attributes = @json($product->super_attributes);
        var variants = @json($product->variants);

        Vue.component('variant-form', {

            data: () => ({
                variant: {},
                super_attributes: super_attributes
            }),

            template: '#variant-form-template',

            created () {
                this.resetModel();
            },

            methods: {
                addVariant (formScope) {
                    this.$validator.validateAll(formScope).then((result) => {
                        if (result) {
                            var this_this = this;

                            var filteredVariants = variants.filter(function(variant) {
                                var matchCount = 0;

                                for (var key in this_this.variant) {
                                    if (variant[key] == this_this.variant[key]) {
                                        matchCount++;
                                    }
                                }

                                return matchCount == this_this.super_attributes.length;
                            })

                            if (filteredVariants.length) {
                                this.$parent.closeModal();

                                window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.catalog.products.variant-already-exist-message') }}" }];

                                this.$root.addFlashMessages()
                            } else {
                                var optionIds = [];
                                for (var key in this_this.variant) {
                                    optionIds.push(this_this.variant[key]);
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

                resetModel () {
                    var this_this = this;

                    this.super_attributes.forEach(function(attribute) {
                        this_this.variant[attribute.code] = '';
                    })
                }
            }
        });

        Vue.component('variant-list', {

            template: '#variant-list-template',

            inject: ['$validator'],

            data: () => ({
                variants: variants,
                superAttributes: super_attributes
            }),

            methods: {
                removeVariant(variant) {
                    let index = this.variants.indexOf(variant)

                    this.variants.splice(index, 1)
                },
            }

        });

        Vue.component('variant-item', {

            template: '#variant-item-template',

            props: ['index', 'variant'],

            inject: ['$validator'],

            data: () => ({
                inventorySources: @json($inventorySources),
                inventories: {},
                totalQty: 0,
                superAttributes: super_attributes
            }),

            created () {
                var this_this = this;
                this.inventorySources.forEach(function(inventorySource) {
                    this_this.inventories[inventorySource.id] = this_this.sourceInventoryQty(inventorySource.id)
                    this_this.totalQty += parseInt(this_this.inventories[inventorySource.id]);
                })
            },

            computed: {
                variantInputName () {
                    if (this.variant.id)
                        return "variants[" + this.variant.id + "]";

                    return "variants[variant_" + this.index + "]";
                }
            },

            methods: {
                removeVariant () {
                    this.$emit('onRemoveVariant', this.variant)
                },

                optionName (optionId) {
                    var optionName = '';

                    this.superAttributes.forEach(function(attribute) {
                        attribute.options.forEach(function(option) {
                            if (optionId == option.id) {
                                optionName = option.admin_name;
                            }
                        });
                    })

                    return optionName;
                },

                sourceInventoryQty (inventorySourceId) {
                    var inventories = this.variant.inventories.filter(function(inventory) {
                        return inventorySourceId === inventory.inventory_source_id;
                    })

                    if (inventories.length)
                        return inventories[0]['qty'];

                    return 0;
                },

                updateTotalQty () {
                    this.totalQty = 0;
                    for (var key in this.inventories) {
                        this.totalQty += parseInt(this.inventories[key]);
                    }
                }
            }

        });
    </script>
@endpush
@endif