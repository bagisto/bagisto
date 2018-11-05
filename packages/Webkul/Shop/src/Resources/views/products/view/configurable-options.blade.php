@if ($product->type == 'configurable')

    @inject ('configurableOptionHelper', 'Webkul\Product\Helpers\ConfigurableOption')

    <product-options>
        <!--<div class="attribute control-group has-error">
            <label class="reqiured">Color</label>
            <select name="super_attribute[104]" id="attribute_104" class="control" data-vv-id="1" aria-required="true" aria-invalid="true">
                <option value="">Choose an option</option>
            </select>
        </div>

        <div class="attribute control-group">
            <label class="reqiured">Size</label>
            <select name="super_attribute[105]" disabled="disabled" id="attribute_105" class="control" data-vv-id="2" aria-required="true" aria-invalid="false">
            </select>
        </div>-->
    </product-options>

    @push('scripts')

        <script type="text/x-template" id="product-options-template">
            <div class="attributes">

                <input type="hidden" name="selected_configurable_option" :value="selectedProductId">

                <div v-for='(attribute, index) in childAttributes' class="attribute control-group" :class="[errors.has('super_attribute[' + attribute.id + ']') ? 'has-error' : '']">
                    <label class="reqiured">@{{ attribute.label }}</label>

                    <select v-validate="'required'" class="control" :name="['super_attribute[' + attribute.id + ']']" :disabled="attribute.disabled" @change="configure(attribute, $event.target.value)" :id="['attribute_' + attribute.id]">

                        <option v-for='(option, index) in attribute.options' :value="option.id">@{{ option.label }}</option>

                    </select>

                    <span class="control-error" v-if="errors.has('super_attribute[' + attribute.id + ']')">
                        @{{ errors.first('super_attribute[' + attribute.id + ']') }}
                    </span>
                </div>

            </div>
        </script>

        {{--  <?php $config = $configurableOptionHelper->getConfigurationConfig($product) ?>  --}}

        <script>

            Vue.component('product-options', {

                template: '#product-options-template',

                inject: ['$validator'],

                data: () => ({
                    config: @json($config),

                    childAttributes: [],

                    selectedProductId: '',

                    simpleProduct: null,

                    galleryImages: []
                }),

                created () {
                    this.galleryImages = galleryImages.slice(0)

                    var config = @json($config);

                    var childAttributes = this.childAttributes,
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

                methods: {
                    configure (attribute, value) {
                        this.simpleProduct = this.getSelectedProductId(attribute, value);

                        if (value) {
                            attribute.selectedIndex = this.getSelectedIndex(attribute, value);

                            if (attribute.nextAttribute) {
                                attribute.nextAttribute.disabled = false;

                                this.fillSelect(attribute.nextAttribute);
                                this.resetChildren(attribute.nextAttribute);
                            } else {
                                this.selectedProductId = attribute.options[attribute.selectedIndex].allowedProducts[0];
                            }

                            //wishlist anchor href changer with options
                            @auth('customer')
                                var wishlistLink = $('#wishlist-changer').attr('href');

                                if(this.selectedProductId != '') {
                                    var splitted = wishlistLink.split("/");

                                    var lastItem = splitted.pop();

                                    lastItem = this.selectedProductId;

                                    var joined = splitted.join('/');

                                    var newWishlistUrl = joined+'/'+lastItem;

                                    $('#wishlist-changer').attr('href', newWishlistUrl);
                                }
                            @endauth

                            //buy now anchor href changer with options
                            var buyNowLink = $('#buynow-changer').attr('href');
                            if(this.selectedProductId != '') {
                                var splitted = buyNowLink.split("/");

                                var lastItem = splitted.pop();

                                lastItem = this.selectedProductId;

                                var joined = splitted.join('/');

                                var newBuyNowUrl = joined+'/'+lastItem;

                                $('#buynow-changer').attr('href', newBuyNowUrl);
                            }
                        } else {
                            attribute.selectedIndex = 0;

                            this.resetChildren(attribute);

                            this.clearSelect(attribute.nextAttribute)
                        }

                        this.reloadPrice();
                        this.changeProductImages();
                    },

                    getSelectedIndex (attribute, value) {
                        var selectedIndex = 0;

                        attribute.options.forEach(function(option, index) {
                            if(option.id == value) {
                                selectedIndex = index;
                            }
                        })

                        return selectedIndex;
                    },

                    getSelectedProductId (attribute, value) {
                        var options = attribute.options,
                            matchedOptions;

                        matchedOptions = options.filter(function (option) {
                            return option.id == value;
                        });

                        if(matchedOptions[0] != undefined && matchedOptions[0].allowedProducts != undefined) {
                            return matchedOptions[0].allowedProducts[0];
                        }

                        return undefined;
                    },

                    fillSelect (attribute) {
                        var options = this.getAttributeOptions(attribute.id),
                            prevOption,
                            index = 1,
                            allowedProducts,
                            i,
                            j;

                        this.clearSelect(attribute)

                        attribute.options = [];
                        attribute.options[0] = {'id': '', 'label': this.config.chooseText, 'products': []};

                        if (attribute.prevAttribute) {
                            prevOption = attribute.prevAttribute.options[attribute.prevAttribute.selectedIndex];
                        }

                        if (options) {
                            for (i = 0; i < options.length; i++) {
                                allowedProducts = [];

                                if (prevOption) {
                                    for (j = 0; j < options[i].products.length; j++) {
                                        if (prevOption.products && prevOption.products.indexOf(options[i].products[j]) > -1) {
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

                    resetChildren (attribute) {
                        if (attribute.childAttributes) {
                            attribute.childAttributes.forEach(function (set) {
                                set.selectedIndex = 0;
                                set.disabled = true;
                            });
                        }
                    },

                    clearSelect: function (attribute) {
                        if(!attribute)
                            return;

                        var element = document.getElementById("attribute_" + attribute.id);

                        if(element) {
                            element.selectedIndex = "0";
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
                        var selectedOptionCount = 0;

                        this.childAttributes.forEach(function(attribute) {
                            if(attribute.selectedIndex) {
                                selectedOptionCount++;
                            }
                        });

                        var priceLabelElement = document.querySelector('.price-label');
                        var priceElement = document.querySelector('.final-price');

                        if(this.childAttributes.length == selectedOptionCount) {
                            priceLabelElement.style.display = 'none';

                            priceElement.innerHTML = this.config.variant_prices[this.simpleProduct].final_price.formated_price;
                        } else {
                            priceLabelElement.style.display = 'inline-block';

                            priceElement.innerHTML = this.config.regular_price.formated_price;
                        }
                    },

                    changeProductImages () {
                        galleryImages.splice(0, galleryImages.length)

                        this.galleryImages.forEach(function(image) {
                            galleryImages.push(image)
                        });

                        if(this.simpleProduct) {
                            this.config.variant_images[this.simpleProduct].forEach(function(image) {
                                galleryImages.unshift(image)
                            });
                        }
                    },
                }

            });

        </script>
    @endpush

@endif