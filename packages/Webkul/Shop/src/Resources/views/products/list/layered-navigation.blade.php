<div class="layered-filter-wrapper">
    {!! view_render_event('bagisto.shop.products.list.layered-nagigation.before') !!}

    <layered-navigation
        attribute-src="{{ route('shop.catalog.categories.filterable_attributes', $category->id ?? null) }}"
        max-price-src="{{ route('shop.catalog.categories.maximum_price', $category->id ?? null) }}">
    </layered-navigation>

    {!! view_render_event('bagisto.shop.products.list.layered-nagigation.after') !!}
</div>

@push('scripts')
    <script type="text/x-template" id="layered-navigation-template">
        <div>
            <div class="filter-title">
                {{ __('shop::app.products.layered-nav-title') }}
            </div>

            <div class="filter-content">
                <div class="filter-attributes">
                    <filter-attribute-item
                        v-for='(attribute, index) in attributes'
                        :key="index"
                        :index="index"
                        :attribute="attribute"
                        :appliedFilterValues="appliedFilters[attribute.code]"
                        :max-price-src="maxPriceSrc"
                        @onFilterAdded="addFilters(attribute.code, $event)">
                    </filter-attribute-item>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="filter-attribute-item-template">
        <div class="filter-attributes-item" :class="[active ? 'active' : '']">
            <div class="filter-attributes-title" @click="active = ! active">
                @{{ attribute.name ? attribute.name : attribute.admin_name }}

                <div class="pull-right">
                    <span class="remove-filter-link" v-if="appliedFilters.length" @click.stop="clearFilters()">
                        {{ __('shop::app.products.remove-filter-link-title') }}
                    </span>

                    <i class="icon" :class="[active ? 'arrow-up-icon' : 'arrow-down-icon']"></i>
                </div>
            </div>

            <div class="filter-attributes-content">
                <ol class="items" v-if="attribute.type != 'price'">
                    <li class="item" v-for='(option, index) in attribute.options'>
                        <span class="checkbox">
                            <input
                                :id="option.id"
                                type="checkbox"
                                v-bind:value="option.id"
                                v-model="appliedFilters"
                                @change="addFilter($event)"/>

                            <label class="checkbox-view" :for="option.id"></label>

                            @{{ option.label ? option.label : option.admin_name }}
                        </span>
                    </li>
                </ol>

                <div class="price-range-wrapper" v-if="attribute.type == 'price'">
                    <vue-slider
                        ref="slider"
                        v-model="sliderConfig.value"
                        :process-style="sliderConfig.processStyle"
                        :tooltip-style="sliderConfig.tooltipStyle"
                        :max="sliderConfig.max"
                        :lazy="true"
                        @change="priceRangeUpdated($event)">
                    </vue-slider>
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('layered-navigation', {
            template: '#layered-navigation-template',

            props: [
                'attributeSrc',
                'maxPriceSrc',
            ],

            data: function() {
                return {
                    appliedFilters: {},
                    attributes: [],
                };
            },

            created: function () {
                this.setFilterAttributes();

                this.setAppliedFilters();
            },

            methods: {
                setFilterAttributes: function () {
                    axios
                        .get(this.attributeSrc)
                        .then((response) => {
                            this.attributes = response.data.filter_attributes;
                        });
                },

                setAppliedFilters: function () {
                    let urlParams = new URLSearchParams(window.location.search);

                    urlParams.forEach((value, index) => {
                        this.appliedFilters[index] = value.split(',');
                    });
                },

                addFilters: function (attributeCode, filters) {
                    if (filters.length) {
                        this.appliedFilters[attributeCode] = filters;
                    } else {
                        delete this.appliedFilters[attributeCode];
                    }

                    this.applyFilter();
                },

                applyFilter: function () {
                    let params = [];

                    for(key in this.appliedFilters) {
                        if (key != 'page') {
                            params.push(key + '=' + this.appliedFilters[key].join(','));
                        }
                    }

                    window.location.href = "?" + params.join('&');
                }
            }
        });

        Vue.component('filter-attribute-item', {
            template: '#filter-attribute-item-template',

            props: [
                'index',
                'attribute',
                'appliedFilterValues',
                'maxPriceSrc',
            ],

            data: function() {
                return {
                    appliedFilters: [],

                    active: false,

                    sliderConfig: {
                        value: [0, 0],

                        max: 500,

                        processStyle: {
                            "backgroundColor": "#FF6472"
                        },

                        tooltipStyle: {
                            "backgroundColor": "#FF6472",
                            "borderColor": "#FF6472"
                        }
                    }
                }
            },

            created: function () {
                if (! this.index) this.active = true;

                if (this.appliedFilterValues && this.appliedFilterValues.length) {
                    this.appliedFilters = this.appliedFilterValues;

                    if (this.attribute.type == 'price') {
                        this.sliderConfig.value = this.appliedFilterValues;
                    }

                    this.active = true;
                }

                this.setMaxPrice();
            },

            methods: {
                setMaxPrice: function () {
                    if (this.attribute['code'] != 'price') {
                        return;
                    }

                    axios
                        .get(this.maxPriceSrc)
                        .then((response) => {
                            let maxPrice  = response.data.max_price;
                            this.sliderConfig.max = maxPrice ? ((parseInt(maxPrice) !== 0 || maxPrice) ? parseInt(maxPrice) : 500) : 500;
                            
                            if (! this.appliedFilterValues) { 
                                this.sliderConfig.value = [0, this.sliderConfig.max];
                                this.sliderConfig.priceTo = this.sliderConfig.max;
                            }
                        });
                },

                addFilter: function (e) {
                    this.$emit('onFilterAdded', this.appliedFilters);
                },

                priceRangeUpdated: function (value) {
                    this.appliedFilters = value;

                    this.$emit('onFilterAdded', this.appliedFilters);
                },

                clearFilters: function () {
                    if (this.attribute.type == 'price') {
                        this.sliderConfig.value = [0, 0];
                    }

                    this.appliedFilters = [];

                    this.$emit('onFilterAdded', this.appliedFilters);
                }
            }
        });
    </script>
@endpush
