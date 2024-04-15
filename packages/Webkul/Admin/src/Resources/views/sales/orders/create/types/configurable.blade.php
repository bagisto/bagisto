{!! view_render_event('bagisto.admin.sales.order.create.types.configurable.before') !!}

<v-product-configurable-options
    :errors="errors"
    :product-options="selectedProductOptions"
></v-product-configurable-options>

{!! view_render_event('bagisto.admin.sales.order.create.types.configurable.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-configurable-options-template"
    >
        <div class="w-[455px] max-w-full p-4">
            <x-admin::form.control-group.control
                type="hidden"
                name="selected_configurable_option"
                v-model="selectedProductId"
            />

            <template v-for='(attribute, index) in childAttributes'>
                <x-admin::form.control-group>
                    <!-- Dropdown Options Container -->
                    <x-admin::form.control-group.label class="required !mt-0">
                        @{{ attribute.label }}
                    </x-admin::form.control-group.label>
                    
                    <x-admin::form.control-group.control
                        type="select"
                        ::name="'super_attribute[' + attribute.id + ']'"
                        rules="required"
                        ::disabled="attribute.disabled"
                        ::label="attribute.label"
                        @change="configure(attribute, $event.target.value)"
                    >
                        <option
                            v-for='(option, index) in attribute.options'
                            :value="option.id"
                            :selected="index == attribute.selectedIndex"
                        >
                            @{{ option.label }}
                        </option>
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.error ::name="'super_attribute[' + attribute.id + ']'" />
                </x-admin::form.control-group>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-product-configurable-options', {
            template: '#v-product-configurable-options-template',

            props: ['errors', 'productOptions'],

            data() {
                return {
                    config: [],

                    isLoading: false,

                    childAttributes: [],

                    selectedProductId: '',

                    simpleProduct: null,
                }
            },

            watch: {
                simpleProduct: {
                    deep: true,

                    handler(selectedProduct) {
                        if (selectedProduct) {
                            return;
                        }
                    },
                },
            },

            mounted() {
                this.getAttibuteOptions();
            },

            methods: {
                getAttibuteOptions() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('admin.catalog.products.configurable.options', ':replace') }}".replace(':replace', this.productOptions.product.id))
                        .then(response => {
                            this.config = response.data.data;

                            this.prepareAttributes();

                            this.isLoading = false;
                        })
                        .catch(error => {});
                },

                prepareAttributes() {
                    let config = JSON.parse(JSON.stringify(this.config));

                    let childAttributes = this.childAttributes,
                        attributes = config.attributes.slice(),
                        index = attributes.length,
                        attribute;

                    while (index--) {
                        attribute = attributes[index];

                        attribute.options = [];

                        if (index) {
                            attribute.disabled = true;
                        } else {
                            this.fillSelect(attribute);
                        }

                        attribute = Object.assign(attribute, {
                            childAttributes: childAttributes.slice(),
                            prevAttribute: attributes[index - 1],
                            nextAttribute: attributes[index + 1]
                        });

                        childAttributes.unshift(attribute);
                    }
                },

                configure(attribute, value) {
                    this.simpleProduct = this.getSelectedProductId(attribute, value);

                    if (value) {
                        attribute.selectedIndex = this.getSelectedIndex(attribute, value);

                        if (attribute.nextAttribute) {
                            attribute.nextAttribute.disabled = false;

                            this.fillSelect(attribute.nextAttribute);

                            this.resetChildren(attribute.nextAttribute);
                        } else {
                            this.selectedProductId = this.simpleProduct;
                        }
                    } else {
                        attribute.selectedIndex = 0;

                        this.resetChildren(attribute);

                        this.clearSelect(attribute.nextAttribute)
                    }
                },

                getSelectedIndex(attribute, value) {
                    let selectedIndex = 0;

                    attribute.options.forEach(function(option, index) {
                        if (option.id == value) {
                            selectedIndex = index;
                        }
                    })

                    return selectedIndex;
                },

                getSelectedProductId(attribute, value) {
                    let options = attribute.options,
                        matchedOptions;

                    matchedOptions = options.filter(function (option) {
                        return option.id == value;
                    });

                    if (matchedOptions[0] != undefined && matchedOptions[0].allowedProducts != undefined) {
                        return matchedOptions[0].allowedProducts[0];
                    }

                    return undefined;
                },

                fillSelect(attribute) {
                    let options = this.getAttributeOptions(attribute.id),
                        prevOption,
                        index = 1,
                        allowedProducts,
                        i,
                        j;

                    this.clearSelect(attribute)

                    attribute.options = [{
                        'id': '',
                        'label': "@lang('admin::app.sales.orders.create.types.configurable.select-options')",
                        'products': []
                    }];

                    if (attribute.prevAttribute) {
                        prevOption = attribute.prevAttribute.options[attribute.prevAttribute.selectedIndex];
                    }

                    if (options) {
                        for (i = 0; i < options.length; i++) {
                            allowedProducts = [];

                            if (prevOption) {
                                for (j = 0; j < options[i].products.length; j++) {
                                    if (prevOption.allowedProducts && prevOption.allowedProducts.indexOf(options[i].products[j]) > -1) {
                                        allowedProducts.push(options[i].products[j]);
                                    }
                                }
                            } else {
                                allowedProducts = options[i].products.slice(0);
                            }

                            if (allowedProducts.length > 0) {
                                options[i].allowedProducts = allowedProducts;

                                attribute.options[index] = options[i];

                                index++;
                            }
                        }
                    }
                },

                resetChildren(attribute) {
                    if (attribute.childAttributes) {
                        attribute.childAttributes.forEach(function (set) {
                            set.selectedIndex = 0;
                            set.disabled = true;
                        });
                    }
                },

                clearSelect (attribute) {
                    if (! attribute)
                        return;

                    if (! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown') {
                        let element = document.getElementById("attribute_" + attribute.id);

                        if (element) {
                            element.selectedIndex = "0";
                        }
                    } else {
                        let elements = document.getElementsByName('super_attribute[' + attribute.id + ']');

                        let self = this;

                        elements.forEach(function(element) {
                            element.checked = false;
                        })
                    }
                },

                getAttributeOptions (attributeId) {
                    let self = this,
                        options;

                    this.config.attributes.forEach(function(attribute, index) {
                        if (attribute.id == attributeId) {
                            options = attribute.options;
                        }
                    })

                    return options;
                },
            }
        });

    </script>
@endPushOnce