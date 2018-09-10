@if ($product->type == 'configurable')

    @inject ('configurableOptionHelper', 'Webkul\Product\Product\ConfigurableOption')


    <product-options></product-options>

    @push('scripts')

        <script type="text/x-template" id="product-options-template">
            <div class="attributes">

                <div v-for='(attribute, index) in childAttributes' class="attribute control-group" :class="[errors.has('super_attribute[' + attribute.id + ']') ? 'has-error' : '']">
                    <label class="reqiured">@{{ attribute.label }}</label>

                    <select v-validate="'required'" class="control" :name="['super_attribute[' + attribute.id + ']']" :disabled="attribute.disabled" @change="configure(attribute, $event.target.value)">

                        <option  v-for='(option, index) in attribute.options' :value="option.id">@{{ option.label }}</option>
                        
                    </select>

                    <span class="control-error" v-if="errors.has('super_attribute[' + attribute.id + ']')">@{{ errors.first('super_attribute[' + attribute.id + ']') }}</span>
                </div>

            </div>
        </script>

        <script>
            Vue.component('product-options', {

                template: '#product-options-template',

                data: () => ({
                    config: @json($configurableOptionHelper->getConfigurationConfig($product)),
                    childAttributes: []
                }),

                created () {
                    var childAttributes = this.childAttributes,
                        attributes = this.config.attributes,
                        index = attributes.length,
                        attribute;

                    while (index--) {
                        // attribute = Object.assign({}, attributes[index]);
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

                methods: {
                    configure (attribute, value) {
                        // this.simpleProduct = this._getSimpleProductId(attribute);

                        if (value) {
                            if (attribute.nextAttribute) {
                                attribute.nextAttribute.disabled = false;
                                this.fillSelect(attribute.nextAttribute);
                                this.resetChildren(attribute.nextAttribute);
                            } else {
                                //Set product id hidden value
                            }
                        } else {
                            this.resetChildren(attribute);
                        }

                        // this.reloadPrice();
                        // this.changeProductImage();
                    },

                    fillSelect (attribute) {
                        var options = this.getAttributeOptions(attribute.id),
                            prevOption,
                            index = 1,
                            products,
                            i,
                            j;

                        attribute.options = [];
                        attribute.options[0] = {'id': '', 'label': this.config.chooseText, 'products': []};

                        if (attribute.prevAttribute) {
                            prevOption = attribute.prevAttribute.options[attribute.prevAttribute.selectedIndex];
                        }

                        // console.log(attribute)

                        if (options) {
                            for (i = 0; i < options.length; i++) {
                                products = [];

                                if (prevOption) {
                                    for (j = 0; j < options[i].products.length; j++) {
                                        if (prevOption.products &&
                                            prevOption.products.indexOf(options[i].products[j]) > -1) {
                                            products.push(options[i].products[j]);
                                        }
                                    }
                                } else {
                                    products = options[i].products.slice(0);
                                }

                                if (products.length > 0) {
                                    options[i].products = products;
                                    attribute.options[index] = options[i];

                                    index++;
                                }
                            }
                        }
                    },

                    resetChildren (attribute) {
                        if (attribute.childAttributes) {
                            attribute.childAttributes.forEach(function (set) {
                                set.selectedIndex = 0;
                                set.disabled = true;
                            });
                        }
                    },
                    
                    getAttributeOptions (attributeId) {
                        var this_this = this,
                            options;

                        
                        this.config.attributes.forEach(function(attribute, index) {
                            if (attribute.id == attributeId) {
                                options = attribute.options;
                            }    
                        })

                        return options;
                    },

                    reloadPrice () {

                    },

                    changeProductImage () {

                    },
                }

            });

        </script>
    @endpush

@endif