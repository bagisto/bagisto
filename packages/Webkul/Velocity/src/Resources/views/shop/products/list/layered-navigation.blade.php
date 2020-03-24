@inject ('productRepository', 'Webkul\Product\Repositories\ProductRepository')
@inject ('attributeRepository', 'Webkul\Attribute\Repositories\AttributeRepository')
@inject ('productFlatRepository', 'Webkul\Product\Repositories\ProductFlatRepository')

<?php
    $filterAttributes = [];

    if (isset($category)) {
        $products = $productRepository->getAll($category->id);

        $filterAttributes = $productFlatRepository->getFilterableAttributes($category, $products);
    }

    if (! count($filterAttributes) > 0) {
        $filterAttributes = $attributeRepository->getFilterAttributes();
    }

    foreach ($filterAttributes as $attribute) {
        if ($attribute->code <> 'price') {
            if (! $attribute->options->isEmpty()) {
                $attributes[] = $attribute;
            }
        } else {
            $attributes[] = $attribute;
        }
    }

    $filterAttributes = collect($attributes);
?>

<div class="layered-filter-wrapper left">

    {!! view_render_event('bagisto.shop.products.list.layered-nagigation.before') !!}

        <layered-navigation></layered-navigation>

    {!! view_render_event('bagisto.shop.products.list.layered-nagigation.after') !!}

</div>

@push('scripts')
    <script type="text/x-template" id="layered-navigation-template">
        <div v-if="attributes.length > 0">

            <h3 class="filter-title fw6 mb20">
                {{ __('shop::app.products.layered-nav-title') }}
            </h3>

            <div class="filter-content">
                <div class="filter-attributes">
                    <filter-attribute-item
                        :key="index"
                        :index="index"
                        :attribute="attribute"
                        v-for='(attribute, index) in attributes'
                        @onFilterAdded="addFilters(attribute.code, $event)"
                        :appliedFilterValues="appliedFilters[attribute.code]">
                    </filter-attribute-item>

                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="filter-attribute-item-template">
        <div :class="`cursor-pointer filter-attributes-item ${active ? 'active' : ''}`">
            <div class="filter-attributes-title" @click="active = !active">
                <h4 class="fw6 display-inbl">@{{ attribute.name ? attribute.name : attribute.admin_name }}</h4>

                <div class="pull-right display-table">
                    <span class="link-color cursor-pointer" v-if="appliedFilters.length" @click.stop="clearFilters()">
                        {{ __('shop::app.products.remove-filter-link-title') }}
                    </span>

                    <i :class="`icon fs16 cell ${active ? 'rango-arrow-up' : 'rango-arrow-down'}`"></i>
                </div>
            </div>

            <div class="filter-attributes-content">
                <ul type="none" class="items ml15" v-if="attribute.type != 'price'">
                    <li
                        class="item"
                        v-for='(option, index) in attribute.options'>

                        <div
                            class="checkbox"
                            @click="optionClicked(option.id, $event)">
                            <input
                                type="checkbox"
                                :id="option.id"
                                v-bind:value="option.id"
                                v-model="appliedFilters"
                                @change="addFilter($event)" />
                            <span>@{{ option.label ? option.label : option.admin_name }}</span>
                        </div>
                    </li>
                </ul>

                <div class="price-range-wrapper" v-if="attribute.type == 'price'">
                    <vue-slider
                        ref="slider"
                        v-model="sliderConfig.value"
                        :process-style="sliderConfig.processStyle"
                        :tooltip-style="sliderConfig.tooltipStyle"
                        :max="sliderConfig.max"
                        :lazy="true"
                        @change="priceRangeUpdated($event)"
                    ></vue-slider>

                    <div class="filter-input row col-12 no-padding">
                        <input
                            type="text"
                            disabled
                            name="price_from"
                            :value="sliderConfig.priceFrom"
                            id="price_from" />

                        <label class="col text-center" for="to">to</label>
                        <input
                        type="text"
                        disabled
                        name="price_to"
                        :value="sliderConfig.priceTo"
                        id="price_to">
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        Vue.component('layered-navigation', {
            template: '#layered-navigation-template',
            data: function() {
                return {
                    appliedFilters: {},
                    attributes: @json($filterAttributes),
                }
            },

            created: function () {
                let urlParams = new URLSearchParams(window.location.search);

                urlParams.forEach((value, index) => {
                    this.appliedFilters[index] = value.split(',');
                });
            },

            methods: {
                addFilters: function (attributeCode, filters) {
                    if (filters.length) {
                        this.appliedFilters[attributeCode] = filters;
                    } else {
                        delete this.appliedFilters[attributeCode];
                    }

                    this.applyFilter();
                },

                applyFilter: function () {
                    var params = [];

                    for (key in this.appliedFilters) {
                        if (key != 'page') {
                            params.push(key + '=' + this.appliedFilters[key].join(','))
                        }
                    }

                    window.location.href = "?" + params.join('&');
                },
            }
        });

        Vue.component('filter-attribute-item', {
            template: '#filter-attribute-item-template',
            props: [
                'index',
                'attribute',
                'addFilters',
                'appliedFilterValues',
            ],

            data: function() {
                let maxPrice  = '{{ core()->convertPrice($productFlatRepository->getCategoryProductMaximumPrice($category)) }}';

                maxPrice = maxPrice ? ((parseInt(maxPrice) !== 0 || maxPrice) ? parseInt(maxPrice) : 500) : 500;

                return {
                    active: false,
                    appliedFilters: [],
                    sliderConfig: {
                        max: maxPrice,
                        value: [ 0, 0 ],

                        processStyle: {
                            "backgroundColor": "#FF6472"
                        },

                        tooltipStyle: {
                            "borderColor": "#FF6472",
                            "backgroundColor": "#FF6472",
                        },

                        priceTo: 0,
                        priceFrom: 0,
                    }
                }
            },

            created: function () {
                if (!this.index)
                    this.active = false;

                if (this.appliedFilterValues && this.appliedFilterValues.length) {
                    this.appliedFilters = this.appliedFilterValues;
                    if (this.attribute.type == 'price') {
                        this.sliderConfig.value = this.appliedFilterValues;
                        this.sliderConfig.priceFrom = this.appliedFilterValues[0];
                        this.sliderConfig.priceTo = this.appliedFilterValues[1];
                    }

                    this.active = true;
                }
            },

            methods: {
                addFilter: function (e) {
                    this.$emit('onFilterAdded', this.appliedFilters)
                },

                priceRangeUpdated: function (value) {
                    this.appliedFilters = value;
                    this.$emit('onFilterAdded', this.appliedFilters)
                },

                clearFilters: function () {
                    if (this.attribute.type == 'price') {
                        this.sliderConfig.value = [0, 0];
                    }

                    this.appliedFilters = [];

                    this.$emit('onFilterAdded', this.appliedFilters)
                },

                optionClicked: function (id, {target}) {
                    let checkbox = $(`#${id}`);
                    if (checkbox && checkbox.length > 0 && target.type != "checkbox") {
                        checkbox = checkbox[0];
                        checkbox.checked = !checkbox.checked;

                        if (checkbox.checked) {
                            this.appliedFilters.push(id);
                        } else {
                            let idPosition = this.appliedFilters.indexOf(id);
                            if (idPosition == -1)
                                idPosition = this.appliedFilters.indexOf(id.toString());

                            this.appliedFilters.splice(idPosition, 1);
                        }

                        this.addFilter(event);
                    }
                }
            }
        });
    </script>
@endpush