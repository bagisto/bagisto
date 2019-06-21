@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.groups.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer_group_catalog.update', $group->id) }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>
                        
                        {{ __('admin::app.customers.groups.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.groups.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('customergroupcatalog::app.customers.groups.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">
                                    {{ __('admin::app.customers.groups.name') }}
                                </label>
                                <input type="text" class="control" name="name" v-validate="'required'" value="{{ $group->name }}" data-vv-as="&quot;{{ __('admin::app.customers.groups.name') }}&quot;">
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                        </div>
                    
                    </accordian>

                    <accordian :title="'{{ __('customergroupcatalog::app.customers.groups.linked-product-categories') }}'">
                        <div slot="body">

                            <linked-products-categories></linked-products-categories>

                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')

    <script type="text/x-template" id="linked-products-categories-template">
        <div>

            <div class="control-group" v-for='key in controls'>
                <label>@{{ controls_labels[key] }}</label>

                <input type="text" class="control" placeholder="{{ __('customergroupcatalog::app.customers.groups.search-hint') }}" v-model.lazy="search_terms[key]" v-debounce="500" autocomplete="off">

                <div class="linked-product-search-result">
                    <ul>
                        <li v-for='(result, index) in searched_results[key]' v-if='searched_results[key].length' @click="addItem(result, key)">
                            @{{ result.name }}
                        </li>

                        <li v-if='! searched_results[key].length && search_terms[key].length && ! is_searching[key]'>
                            {{ __('admin::app.catalog.products.no-result-found') }}
                        </li>

                        <li v-if="is_searching[key] && search_terms[key].length">
                            {{ __('admin::app.catalog.products.searching') }}
                        </li>
                    </ul>
                </div>

                <span class="filter-tag" style="text-transform: capitalize; margin-top: 10px; margin-right: 0px; justify-content: flex-start" v-if="saved_results[key].length">


                    <span class="wrapper" style="margin-left: 0px; margin-right: 10px;" v-for='(savedItem, index) in saved_results[key]'>
                        <input type="hidden" :name="key + '[]'" :value="savedItem.id"/>

                        @{{ savedItem.name }}
                        <span class="icon cross-icon" @click="removeItem(savedItem, key)"></span>
                    </span>

                </span>
            </div>

        </div>
    </script>

    <script>
        Vue.component('linked-products-categories', {

            template: '#linked-products-categories-template',

            data: function() {
                return {
                    searched_results: {
                        products: [],

                        categories: []
                    },

                    saved_results: {
                        products: [],
                        
                        categories: []
                    },

                    linked_results: {
                        products: @json(app('Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository')->getProducts($group)),
                        
                        categories: @json(app('Webkul\CustomerGroupCatalog\Repositories\CustomerGroupRepository')->getCategories($group))
                    },

                    search_terms: {
                        products: '',

                        categories: ''
                    },

                    is_searching: {
                        products: false,

                        categories: false
                    },

                    controls: [
                        // 'products',

                        'categories'
                    ],

                    controls_labels: {
                        products: "{{ __('customergroupcatalog::app.customers.groups.products') }}",

                        categories: "{{ __('customergroupcatalog::app.customers.groups.categories') }}"
                    }                    
                }
            },

            created: function () {
                for (var index in this.linked_results.products) {
                    this.saved_results['products'].push(this.linked_results.products[index]);
                }

                for (var index in this.linked_results.categories) {
                    this.saved_results['categories'].push(this.linked_results.categories[index]);
                }
            },

            watch: {
                'search_terms.products': function(newVal, oldVal) {
                    this.search('products')
                },

                'search_terms.categories': function(newVal, oldVal) {
                    this.search('categories')
                }
            },

            methods: {
                addItem: function (item, key) {
                    this.saved_results[key].push(item);

                    this.search_terms[key] = '';

                    this.searched_results[key] = [];
                },

                removeItem: function (item, key) {
                    for (var index in this.saved_results[key]) {
                        if (this.saved_results[key][index].id == item.id) {
                            this.saved_results[key].splice(index, 1);
                        }
                    }
                },

                search: function (key) {
                    this_this = this;

                    this.is_searching[key] = true;

                    if (! this.search_terms[key].length) {
                        this_this.searched_results[key] = [];

                        this_this.is_searching[key] = false;

                        return;
                    }
                    
                    this.$http.get ("{{ route('admin.customer_group_catalog.search.catalog') }}", {params: {query: this.search_terms[key], type: key}})
                        .then (function(response) {
                             for (var key1 in this_this.saved_results[key]) {
                                for (var key2 in response.data) {
                                    if (response.data[key2].id == this_this.saved_results[key][key1].id) {
                                        response.data.splice(key2, 1);
                                    }
                                }
                            }

                            this_this.searched_results[key] = response.data;

                            this_this.is_searching[key] = false;
                        })
                        .catch (function (error) {
                            this_this.is_searching[key] = false;
                        })
                }
            }
        });

    </script>
@endpush