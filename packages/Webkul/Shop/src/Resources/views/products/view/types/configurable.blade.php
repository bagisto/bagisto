@if (Webkul\Product\Helpers\ProductType::hasVariants($product->type))

    @inject ('configurableOptionHelper', 'Webkul\Product\Helpers\ConfigurableOption')

    {!! view_render_event('bagisto.shop.products.view.configurable-options.before', ['product' => $product]) !!}

    <v-product-options></v-product-options>

    {!! view_render_event('bagisto.shop.products.view.configurable-options.after', ['product' => $product]) !!}

    @push('scripts')
        <script type="text/x-template" id="v-product-options-template">
            <div class="attributes">
                <input type="hidden" id="selected_configurable_option" ref="selected_configurable_option" name="selected_configurable_option" :value="selectedProductId">

                <div v-for='(attribute, index) in childAttributes' class="attribute control-group" >
                    <span v-if="! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown'">
                        <div class="mt-[20px]">
                            <h3 class="text-[20px] mb-[15px] max-sm:text-[16px]" v-if="(attribute.options).length > 1" v-text="attribute.label"></h3>
                            
                            <div v-if="attribute.code == 'color'" class="flex items-center space-x-3">   
                                <template v-for="(option, index) in attribute.options">
                                    <label
                                      class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none undefined"
                                      :class="{'ring-gray-900 ring ring-offset-1' : index == attribute.selectedIndex}"
                                      :title="option.label"
                                    >
                                        <input
                                            type="radio"
                                            :name="['super_attribute[' + attribute.id + ']']"
                                            :value="option.id"
                                            :id="['attribute_' + attribute.id]"
                                            class="sr-only"
                                            :aria-labelledby="'color-choice-' + index + '-label'"
                                            @click="configure(attribute, $event.target.value)"
                                        >
                                        <span :style="{ 'background-color': option.label }" class="h-8 w-8 rounded-full bg-navyBlue border border-navyBlue border-opacity-10 max-sm:h-[25px] max-sm:w-[25px]"></span>
                                    </label>
                                </template>
                            </div>

                            <div v-else-if="attribute.code == 'size'"  class="flex flex-wrap gap-[12px]">
                                <template v-for="(option, index) in attribute.options">
                                    <label 
                                        class="group relative flex items-center justify-center rounded-full border h-[60px] w-[60px] py-3 px-4 font-medium uppercase hover:bg-gray-50 focus:outline-none sm:py-6 cursor-pointer bg-white text-gray-900 shadow-sm  max-sm:w-[35px] max-sm:h-[35px]"
                                        :class="{'ring-2 ring-navyBlue' : index == attribute.selectedIndex }"
                                        :title="option.label"
                                        >

                                        <input
                                            type="radio"
                                            :name="['super_attribute[' + attribute.id + ']']"
                                            :value="option.id"
                                            :id="['attribute_' + attribute.id]"
                                            class="sr-only"
                                            :aria-labelledby="'color-choice-' + index + '-label'"
                                            @click="configure(attribute, $event.target.value)"
                                        >

                                        <span class="text-[18px] max-sm:text-[14px]" v-text="option.label"></span>
                                        <span class="pointer-events-none absolute -inset-px rounded-full"></span>
                                    </label>
                                </template>
                            </div>

                            <div v-else class="flex flex-wrap gap-[12px]">
                                <select
                                    :name="['super_attribute[' + attribute.id + ']']"
                                    :id="['attribute_' + attribute.id]"
                                    class="bg-gray-50 mt-5 border border-gray-300 text-black-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    :disabled="attribute.disabled"
                                    :data-vv-as="'&quot;' + attribute.label + '&quot;'"
                                    @change="configure(attribute, $event.target.value)"
                                >
                                    <option
                                        v-for='(option, index) in attribute.options' :value="option.id"
                                        :selected="index == attribute.selectedIndex">
                                        @{{ option.label }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </span>

                    <span class="swatch-container" v-else>
                        <label class="swatch"
                            v-for='(option, index) in attribute.options'
                            v-if="option.id"
                            :data-id="option.id"
                            :for="['attribute_' + attribute.id + '_option_' + option.id]">

                            <input type="radio"
                                :name="['super_attribute[' + attribute.id + ']']"
                                :id="['attribute_' + attribute.id + '_option_' + option.id]"
                                :value="option.id"
                                :data-vv-as="'&quot;' + attribute.label + '&quot;'"
                                @change="configure(attribute, $event.target.value)"
                                :checked="index == attribute.selectedIndex"/>

                            <span v-if="attribute.swatch_type == 'color'" :style="{ background: option.swatch_value }"></span>

                            <img v-if="attribute.swatch_type == 'image'" :src="option.swatch_value" :title="option.label" alt="" />

                            <span v-if="attribute.swatch_type == 'text'">
                                @{{ option.label }}
                            </span>

                        </label>

                        <span v-if="! attribute.options.length" class="no-options">{{ __('shop::app.products.select-above-options') }}</span>
                    </span>

                </div>
            </div>
        </script>

        @php
            $defaultVariant = $product->getTypeInstance()->getDefaultVariant();
            
            $config = $configurableOptionHelper->getConfigurationConfig($product);

            $galleryImages = product_image()->getGalleryImages($product);
        @endphp

        <script type="module">
            let galleryImages = @json($galleryImages);

            app.component('v-product-options', {
                template: '#v-product-options-template',

                data() {
                    return {
                        defaultVariant: @json($defaultVariant),

                        config: @json($config),

                        childAttributes: [],

                        selectedProductId: '',

                        simpleProduct: null,

                        galleryImages: [],
                    }
                },

                mounted() {
                    this.init();

                    this.initDefaultSelection();
                },

                methods: {
                    init() {
                        let config = @json($config);

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

                    initDefaultSelection() {
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

                        attribute.options = [{'id': '', 'label': this.config.chooseText, 'products': []}];

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

                        } else {
                            priceLabelElement.style.display = 'inline-block';

                            priceElement.innerHTML = this.config.regular.formatted_price;
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
                            this.$parent.$root.$refs.gallery.mediaContents.images =  { ...galleryImages };
                        }
                    },
                }
            });

        </script>
    @endpush

@endif