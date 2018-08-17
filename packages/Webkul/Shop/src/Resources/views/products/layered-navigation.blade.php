@inject ('attributeRepository', 'Webkul\Attribute\Repositories\AttributeRepository')

<layered-navigation></layered-navigation>

@push('scripts')
    <script type="text/x-template" id="layered-navigation-template">
        <div class="layered-filter-wrapper">

            <div class="filter-title">
                {{ __('shop::app.products.layered-nav-title') }}
            </div>

            <div class="filter-content">

                <div class="filter-attributes">
                    
                    <filter-attribute-item v-for='(attribute, index) in attributes' :attribute="attribute" :key="index" :index="index" @onFilterAdded="addFilters(attribute.code, $event)"></filter-attribute-item>

                </div>

            </div>

        </div>
    </script>

    <script type="text/x-template" id="filter-attribute-item-template">
        <div class="filter-attributes-item" :class="[active ? 'active' : '']">

            <div class="filter-attributes-title" @click="active = !active">
                @{{ attribute.name }}

                <i class="icon" :class="[active ? 'arrow-up-icon' : 'arrow-down-icon']"></i>
            </div>

            <div class="filter-attributes-content">

                <ol class="items" v-if="attribute.type != 'price'">
                    <li class="item" v-for='(option, index) in attribute.options'>

                        <span class="checkbox">
                            <input type="checkbox" :id="option.id" :value="option.id" @change="addFilter($event)"/>
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

            methods: {
                addFilters (attributeCode, filters) {
                    if(filters.length) {
                        this.appliedFilters[attributeCode] = filters;
                    } else {
                        delete this.appliedFilters[attributeCode]; 
                    }
                }
            }

        });

        Vue.component('filter-attribute-item', {

            template: '#filter-attribute-item-template',

            props: ['index', 'attribute'],

            data: () => ({
                appliedFilters: [],
                active: false,
                sliderConfig: {
                    value: [
                        100,
                        250
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
                if(!this.index)
                    this.active = true;
            },

            methods: {
                addFilter (e) {
                    if(event.target.checked) {
                        this.appliedFilters.push(event.target.value);
                    } else {
                        let index = this.appliedFilters.indexOf(event.target.value)

                        this.appliedFilters.splice(index, 1)
                    }

                    this.$emit('onFilterAdded', this.appliedFilters)
                },

                priceRangeUpdated (value) {
                    console.log(value)
                }
            }

        });

    </script>
@endpush