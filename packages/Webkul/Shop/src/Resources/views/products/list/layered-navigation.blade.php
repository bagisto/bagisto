@inject ('attributeRepository', 'Webkul\Attribute\Repositories\AttributeRepository')

<div class="layered-filter-wrapper">

    {!! view_render_event('bagisto.shop.products.list.layered-nagigation.before') !!}

    <layered-navigation></layered-navigation>

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

                    <filter-attribute-item v-for='(attribute, index) in attributes' :attribute="attribute" :key="index" :index="index" @onFilterAdded="addFilters(attribute.code, $event)" :appliedFilterValues="appliedFilters[attribute.code]">
                    </filter-attribute-item>

                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="filter-attribute-item-template">
        <div class="filter-attributes-item" :class="[active ? 'active' : '']">

            <div class="filter-attributes-title" @click="active = !active">
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
                            <input type="checkbox" :id="option.id" v-bind:value="option.id" v-model="appliedFilters" @change="addFilter($event)"/>
                            <label class="checkbox-view" :for="option.id"></label>
                            @{{ option.label }}
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
                        @callback="priceRangeUpdated($event)"
                    ></vue-slider>
                </div>

            </div>

        </div>
    </script>

    <script>
        Vue.component('layered-navigation', {

            template: '#layered-navigation-template',

            data: () => ({
                attributes: @json($attributeRepository->getFilterAttributes()),
                appliedFilters: {}
            }),

            created () {
                var urlParams = new URLSearchParams(window.location.search);

                var entries = urlParams.entries();

                for(pair of entries) {
                   this.appliedFilters[pair[0]] = pair[1].split(',');
                }
            },

            methods: {
                addFilters (attributeCode, filters) {
                    if (filters.length) {
                        this.appliedFilters[attributeCode] = filters;
                    } else {
                        delete this.appliedFilters[attributeCode];
                    }

                    this.applyFilter()
                },

                applyFilter () {
                    var params = [];

                    for(key in this.appliedFilters) {
                        params.push(key + '=' + this.appliedFilters[key].join(','))
                    }

                    window.location.href = "?" + params.join('&');
                }
            }

        });

        Vue.component('filter-attribute-item', {

            template: '#filter-attribute-item-template',

            props: ['index', 'attribute', 'appliedFilterValues'],

            data: () => ({
                appliedFilters: [],

                active: false,

                sliderConfig: {
                    value: [
                        0,
                        0
                    ],
                    max: 500,
                    processStyle: {
                        "backgroundColor": "#FF6472"
                    },
                    tooltipStyle: {
                        "backgroundColor": "#FF6472",
                        "borderColor": "#FF6472"
                    }
                }
            }),

            created () {
                if (!this.index)
                    this.active = true;

                if (this.appliedFilterValues && this.appliedFilterValues.length) {
                    this.appliedFilters = this.appliedFilterValues;

                    if (this.attribute.type == 'price') {
                        this.sliderConfig.value = this.appliedFilterValues;
                    }

                    this.active = true;
                }
            },

            methods: {
                addFilter (e) {
                    this.$emit('onFilterAdded', this.appliedFilters)
                },

                priceRangeUpdated (value) {
                    this.appliedFilters = value;

                    this.$emit('onFilterAdded', this.appliedFilters)
                },

                clearFilters () {
                    if (this.attribute.type == 'price') {
                        this.sliderConfig.value = [0, 0];
                    }

                    this.appliedFilters = [];

                    this.$emit('onFilterAdded', this.appliedFilters)
                }
            }

        });

    </script>
@endpush