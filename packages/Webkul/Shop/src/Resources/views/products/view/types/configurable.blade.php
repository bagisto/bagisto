@if (Webkul\Product\Helpers\ProductType::hasVariants($product->type))
    {!! view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]) !!}

    <v-product-configurable-options :errors="errors"></v-product-configurable-options>

    {!! view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]) !!}

    @push('scripts')
        <script
            type="text/x-template"
            id="v-product-configurable-options-template"
        >
            <div class="w-[455px] max-w-full">
                <input
                    type="hidden"
                    name="selected_configurable_option"
                    id="selected_configurable_option"
                    :value="selectedProductId"
                    ref="selected_configurable_option"
                >

                <div
                    class="mt-5"
                    v-for='(attribute, index) in childAttributes'
                >
                    <!-- Dropdown Options Container -->
                    <template
                        v-if="! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown'"
                    >
                        <!-- Dropdown Label -->
                        <h2
                            class="mb-4 text-xl max-sm:text-base"
                            v-text="attribute.label"
                        ></h2>
                        
                        <!-- Dropdown Options -->
                        <v-field
                            as="select"
                            :name="'super_attribute[' + attribute.id + ']'"
                            class="custom-select block w-full mb-3 py-3 px-5 bg-white border border-[#E9E9E9] rounded-lg text-base text-[#6E6E6E] focus:ring-blue-500 focus:border-blue-500 max-md:border-0 max-md:outline-none max-md:w-[110px] cursor-pointer"
                            :class="[errors['super_attribute[' + attribute.id + ']'] ? 'border border-red-500' : '']"
                            :id="'attribute_' + attribute.id"
                            rules="required"
                            :label="attribute.label"
                            :aria-label="attribute.label"
                            :disabled="attribute.disabled"
                            @change="configure(attribute, $event.target.value)"
                        >
                            <option
                                v-for='(option, index) in attribute.options'
                                :value="option.id"
                                :selected="index == attribute.selectedIndex"
                            >
                                @{{ option.label }}
                            </option>
                        </v-field>
                    </template>

                    <!-- Swatch Options Container -->
                    <template v-else>
                        <!-- Option Label -->
                        <h2
                            class="mb-4 text-xl max-sm:text-base"
                            v-text="attribute.label"
                        ></h2>

                        <!-- Swatch Options -->
                        <div class="flex gap-3 items-center">
                            <template v-for="(option, index) in attribute.options">
                                <!-- Color Swatch Options -->
                                <template v-if="option.id">
                                    <label
                                        class="relative flex -m-0.5 p-0.5 items-center justify-center rounded-full focus:outline-none cursor-pointer"
                                        :class="{'ring-gray-900 ring ring-offset-1' : index == attribute.selectedIndex}"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'color'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                class="sr-only peer"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span
                                            class="h-8 w-8 bg-navyBlue border border-navyBlue border-opacity-10 rounded-full max-sm:h-[25px] max-sm:w-[25px]"
                                            :style="{ 'background-color': option.swatch_value }"
                                        ></span>
                                    </label>

                                    <!-- Image Swatch Options -->
                                    <label 
                                        class="group relative flex items-center justify-center w-[60px] bg-white overflow-hidden rounded-full border h-[60px] hover:bg-gray-50 focus:outline-none text-gray-900 font-medium uppercase shadow-sm max-sm:w-[35px] max-sm:h-[35px] sm:py-6 cursor-pointer"
                                        :class="{'ring-2 ring-navyBlue' : index == attribute.selectedIndex }"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'image'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                class="sr-only peer"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <img
                                            :src="option.swatch_value"
                                            :title="option.label"
                                        />
                                    </label>

                                    <!-- Text Swatch Options -->
                                    <label 
                                        class="group relative flex items-center justify-center min-w-[60px] bg-white py-3 px-4 rounded-full border h-[60px] hover:bg-gray-50 focus:outline-none text-gray-900 font-medium uppercase shadow-sm max-sm:w-[35px] max-sm:h-[35px] sm:py-6 cursor-pointer"
                                        :class="{'ring-2 ring-navyBlue' : index == attribute.selectedIndex }"
                                        :title="option.label"
                                        v-if="attribute.swatch_type == 'text'"
                                    >
                                        <v-field
                                            type="radio"
                                            :name="'super_attribute[' + attribute.id + ']'"
                                            :value="option.id"
                                            v-slot="{ field }"
                                            rules="required"
                                            :label="attribute.label"
                                            :aria-label="attribute.label"
                                        >
                                            <input
                                                type="radio"
                                                :name="'super_attribute[' + attribute.id + ']'"
                                                :value="option.id"
                                                v-bind="field"
                                                :id="'attribute_' + attribute.id"
                                                :aria-labelledby="'color-choice-' + index + '-label'"
                                                class="sr-only peer"
                                                @click="configure(attribute, $event.target.value)"
                                            />
                                        </v-field>

                                        <span
                                            class="text-lg max-sm:text-sm"
                                            v-text="option.label"
                                        ></span>

                                        <span class="absolute -inset-px rounded-full pointer-events-none"></span>
                                    </label>
                                </template>
                            </template>

                            <span
                                class="text-gray-600 text-sm max-sm:text-xs"
                                v-if="! attribute.options.length"
                            >
                                @lang('shop::app.products.view.type.configurable.select-above-options')
                            </span>
                        </div>
                    </template>

                    <v-error-message
                        :name="['super_attribute[' + attribute.id + ']']"
                        v-slot="{ message }"
                    >
                        <p
                            class="mt-1 text-red-500 text-xs italic"
                            v-text="message"
                        >
                        </p>
                    </v-error-message>
                </div>
            </div>
        </script>

        <script type="module">
            let galleryImages = @json(product_image()->getGalleryImages($product));

            app.component('v-product-configurable-options', {
                template: '#v-product-configurable-options-template',

                props: ['errors'],

                data() {
                    return {
                        defaultVariant: @json($product->getTypeInstance()->getDefaultVariant()),

                        config: @json(app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($product)),

                        childAttributes: [],

                        selectedProductId: '',

                        simpleProduct: null,

                        galleryImages: [],
                    }
                },

                watch: {
                    simpleProduct: {
                        deep: true,

                        handler(selectedProduct) {
                            if (selectedProduct) {
                                return;
                            }

                            this.$parent.$parent.$refs.gallery.media.images = @json(product_image()->getGalleryImages($product));
                        },
                    },
                },

                mounted() {
                    this.prepareAttributes();

                    this.prepareDefaultSelection();
                },

                methods: {
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

                    prepareDefaultSelection() {
                        if (this.defaultVariant) {
                            this.childAttributes.forEach((attribute) => {
                                let attributeValue = this.defaultVariant[attribute.code];

                                this.configure(attribute, attributeValue);
                            });
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

                        this.reloadPrice();
                        this.changeProductImages();
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
                            'label': "@lang('shop::app.products.view.type.configurable.select-options')",
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

                    reloadPrice () {
                        let selectedOptionCount = 0;

                        this.childAttributes.forEach(function(attribute) {
                            if (attribute.selectedIndex) {
                                selectedOptionCount++;
                            }
                        });

                        let priceLabelElement = document.querySelector('.price-label');
                        let priceElement = document.querySelector('.special-price') ? document.querySelector('.special-price') : document.querySelector('.final-price');
                        let regularPriceElement = document.querySelector('.regular-price');

                        if (this.childAttributes.length == selectedOptionCount) {
                            priceLabelElement.style.display = 'none';

                            if (regularPriceElement) {
                                regularPriceElement.style.display = 'none';
                            }

                            priceElement.innerHTML = this.config.variant_prices[this.simpleProduct].final.formatted_price;

                            if (regularPriceElement && this.config.variant_prices[this.simpleProduct].final.price < this.config.variant_prices[this.simpleProduct].regular.price) {
                                regularPriceElement.innerHTML = this.config.variant_prices[this.simpleProduct].regular.formatted_price;
                                regularPriceElement.style.display = 'inline-block';
                            }

                            this.$emitter.emit('configurable-variant-selected-event',this.simpleProduct);
                        } else {
                            priceLabelElement.style.display = 'inline-block';

                            priceElement.innerHTML = this.config.regular.formatted_price;

                            this.$emitter.emit('configurable-variant-selected-event', 0);
                        }
                    },

                    changeProductImages () {
                        galleryImages.splice(0, galleryImages.length)

                        if (this.simpleProduct) {
                            this.config.variant_images[this.simpleProduct].forEach(function(image) {
                                galleryImages.push(image)
                            });

                            this.config.variant_videos[this.simpleProduct].forEach(function(video) {
                                galleryImages.push(video)
                            });
                        }

                        this.galleryImages.forEach(function(image) {
                            galleryImages.push(image)
                        });

                        if (galleryImages.length) {
                            this.$parent.$parent.$refs.gallery.media.images =  { ...galleryImages };
                        }

                        this.$emitter.emit('configurable-variant-update-images-event', galleryImages);
                    },
                }
            });

        </script>
    @endpush

@endif