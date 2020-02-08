{!! view_render_event('bagisto.admin.content.edit_form_accordian.content.product.before') !!}

    <catalog-product></catalog-product>

{!! view_render_event('bagisto.admin.content.edit_form_accordian.content.product.after') !!}
@push('scripts')
    <script type="text/x-template" id="catalog-product-template">
        <div>
            <?php $catalogType =  old($locale)['catalog_type'] ?? (isset($content) ? $content->translate($locale)['catalog_type'] : ''); ?>

            <div class="control-group" :class="[errors.has('{{$locale}}[catalog_type]') ? 'has-error' : '']">
                <label for="catalog_type" class="required">
                    {{ __('velocity::app.admin.contents.content.catalog-type') }}</label>

                <select class="control" v-validate="'required'" id="catalog_type" name="{{$locale}}[catalog_type]" v-model="catalog_type" data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.catalog-type') }}&quot;" @change="loadCatalogType($event)">
                    <option value="">{{ __('velocity::app.admin.contents.select') }}</option>

                    @foreach (velocity()->getCatalogType() as $key => $catalog_type)
                        <option value="{{ $key }}" {{ $catalogType == $key ? 'selected' : '' }}>{{ $catalog_type }}</option>
                    @endforeach
                </select>

                <span class="control-error" v-if="errors.has('{{$locale}}[catalog_type]')">@{{ errors.first('{!!$locale!!}[catalog_type]') }}</span>
            </div>

            <div v-if="catalog_type == 'custom'">
                <field-autocomplete
                    :fieldLabel="'{{ __('admin::app.catalog.attributes.options') }}'"
                    :fieldPlaceholder="'{{ __('velocity::app.admin.contents.search-hint') }}'"
                    :routePath="'{{ route('velocity.admin.content.search') }}'"
                    :linkedResults='@json(app('Webkul\Velocity\Repositories\ContentRepository')->getProducts($content->id))'
                ></field-autocomplete>
            </div>
        </div>

    </script>

    <script>
        Vue.component('catalog-product', {
            template: '#catalog-product-template',

            inject: ['$validator'],

            data() {
                return {
                    catalog_type: @json($catalogType),
                }
            },
            methods: {
                loadCatalogType(event) {
                    this.catalog_type = event.target.value;
                }
            }
        });
    </script>

    <script type="text/x-template" id="field-autocomplete-template">
        <div>
            <div class="control-group">
                <label v-text="fieldLabel"></label>

                <input type="text" class="control" :placeholder="fieldPlaceholder" v-model.lazy="search_field" v-debounce="500" autocomplete="off">

                <div class="linked-product-search-result">
                    <ul>
                        <li v-for='(result, index) in searched_results' v-if='searched_results.length' @click="addItem(result)">
                            @{{ result.name }}
                        </li>

                        <li v-if='! searched_results.length && search_field.length && ! is_searching'>
                            {{ __('velocity::app.admin.contents.no-result-found') }}
                        </li>

                        <li v-if="is_searching && search_field.length">
                            {{ __('admin::app.catalog.products.searching') }}
                        </li>
                    </ul>
                </div>

                <span class="filter-tag" style="text-transform: capitalize; margin-top: 10px; margin-right: 0px; justify-content: flex-start" v-if="saved_results.length">

                    <span class="wrapper" style="margin-left: 0px; margin-right: 10px;" v-for='(savedItem, index) in saved_results'>
                        <input type="hidden" name="{{$locale}}[products][]" :value="savedItem.id"/>

                        @{{ savedItem.name }}
                        <span class="icon cross-icon" @click="removeItem(savedItem)"></span>
                    </span>

                </span>

            </div>

        </div>
    </script>

    <script>
        Vue.component('field-autocomplete', {

            template: '#field-autocomplete-template',

            props: {
                fieldLabel: String,
                fieldPlaceholder: String,
                routePath: String,
                linkedResults: Object,
            },

            data: function() {
                return {
                    searched_results: [],

                    saved_results: [],

                    search_field: '',

                    is_searching: false,
                }
            },

            created () {
                if (this.linkedResults.length) {
                    for (var index in this.linkedResults) {
                        this.saved_results.push(this.linkedResults[index]);
                    }
                }
            },

            watch: {
                'search_field': function(newVal, oldVal) {
                    this.search();
                }
            },

            methods: {
                addItem: function (item) {
                    this.saved_results.push(item);

                    this.search_field = '';

                    this.searched_results = [];
                },

                removeItem: function (item) {
                    for (var index in this.saved_results) {
                        if (this.saved_results[index].id == item.id) {
                            this.saved_results.splice(index, 1);
                        }
                    }
                },
                search: function () {
                    this_this = this;

                    this_this.is_searching = true;

                    if (! this_this.search_field.length) {
                        this_this.searched_results = [];

                        this_this.is_searching = false;

                        return;
                    }

                    this.$http.get ( this_this.routePath , {params: {query: this_this.search_field}})
                    .then (function(response) {
                        for (var key1 in this_this.saved_results) {
                            for (var key2 in response.data) {
                                if (response.data[key2].id == this_this.saved_results[key1].id) {
                                    response.data.splice(key2, 1);
                                }
                            }
                        }

                        this_this.searched_results = response.data;

                        this_this.is_searching = false;
                    })
                    .catch (function (error) {
                        this_this.is_searching = false;
                    })

                }
            }
        });

    </script>
@endpush