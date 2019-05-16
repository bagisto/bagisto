@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-catalog-rule') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog-rule.store') }}" @submit.prevent="onSubmit">
            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.promotion.add-catalog-rule') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.promotion.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <catalog-rule></catalog-rule>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script type="text/x-template" id="catalog-rule-form-template">
            <div>
                @csrf()

                <accordian :active="true" title="Information">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                            <label for="name" class="required">{{ __('admin::app.promotion.general-info.name') }}</label>

                            <input type="text" class="control" name="name" v-model="name" v-validate="'required'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.name') }}&quot;">

                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="description">{{ __('admin::app.promotion.general-info.description') }}</label>

                            <textarea class="control" name="description" v-model="description" v-validate="'required'" value="{{ old('description') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.description') }}&quot;"></textarea>

                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('customer_groups[]') ? 'has-error' : '']">
                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.cust-groups') }}</label>

                            <select type="text" class="control" name="customer_groups[]" v-model="customer_groups" v-validate="'required'" value="{{ old('customer_groups') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                <option disabled="disabled">Select Customer Groups</option>
                                @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach
                            </select>

                            <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups[]') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                            <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>

                            <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.channels') }}&quot;" multiple="multiple">
                                <option disabled="disabled">Select Channels</option>
                                @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach
                            </select>

                            <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('channels[]') }}</span>
                        </div>

                        <datetime :name="starts_from">
                            <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                                <label for="starts_from" class="required">{{ __('admin::app.promotion.general-info.starts-from') }}</label>

                                <input type="text" class="control" v-model="starts_from" name="starts_from" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.starts-from') }}&quot;">

                                <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                            </div>
                        </datetime>

                        <datetime :name="starts_from">
                            <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                                <label for="ends_till" class="required">{{ __('admin::app.promotion.general-info.ends-till') }}</label>

                                <input type="text" class="control" v-model="ends_till" name="ends_till" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.ends-till') }}&quot;">

                                <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                            </div>
                        </datetime>

                        <div class="control-group" :class="[errors.has('priority') ? 'has-error' : '']">
                            <label for="priority" class="required">{{ __('admin::app.promotion.general-info.priority') }}</label>

                            <input type="number" class="control" step="1" name="priority" v-model="priority" v-validate="'required|numeric|min_value:1'" value="{{ old('priority') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.priority') }}&quot;">

                            <span class="control-error" v-if="errors.has('priority')">@{{ errors.first('priority') }}</span>
                        </div>
                    </div>
                </accordian>

                <accordian :active="true" title="Conditions">
                    <div slot="body">
                        <div class="add-condition">
                            <div class="control-group" :class="[errors.has('criteria') ? 'has-error' : '']">
                                <label for="criteria" class="required">{{ __('admin::app.promotion.general-info.add-condition') }}</label>

                                <select type="text" class="control" name="criteria" v-model="criteria" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;">
                                        <option value="condition_combination">Condition Combination</option>
                                        <option value="attribute">Attribute</option>
                                    {{-- <option value="category">Category</option> --}}
                                </select>

                                <span class="control-error" v-if="errors.has('criteria')">@{{ errors.first('criteria') }}</span>
                            </div>

                            <span class="btn btn-primary btn-lg" v-on:click="addCondition">Add Condition</span>
                        </div>

                        <div class="condition-set" v-if="attributes_list.length">
                            <!-- <span class="label mt-20">Assume all conditions are true:</span>
                            <span class="label mt-20">Assume
                                <select>
                                    <option>All</option>
                                    <option>Any</option>
                                </select>
                                 are
                                <select>
                                    <option>True</option>
                                    <option>False</option>
                                </select>
                                 .
                            </span> -->

                            <!-- Attribute -->
                            <div v-for="(attrs, index) in attributes_list" :key="index">
                                <div class="control-container mt-20">
                                    <div class="title-bar">
                                        <span>Attribute is </span>
                                        <span class="icon cross-icon" v-on:click="removeAttr(index)"></span>
                                    </div>

                                    <div class="control-group mt-10" :key="index">
                                        <select class="control" name="attributes[]" v-model="attributes_list[index].attribute" v-validate="'required'" title="You Can Make Multiple Selections Here" style="margin-right: 15px;" v-on:change="enableCondition($event, index)">
                                            <option disabled="disabled">Select attribute</option>
                                            <option v-for="attr_ip in attrs_input" :value="attr_ip.id">@{{ attr_ip.name }}</option>
                                        </select>

                                        <div v-if='attributes_list[index].type == "text" || attributes_list[index].type == "textarea"'>
                                            <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option>is</option>
                                                <option>is any of</option>
                                                <option>contains</option>
                                            </select>

                                            <input type="text" class="control" name="attributes[]" v-model="attributes_list[index].value" placeholder="Enter Value">
                                        </div>

                                        <div v-if='attributes_list[index].type == "price"'>
                                            <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option>is</option>
                                                <option>is any of</option>
                                                <option>contains</option>
                                            </select>

                                            <input type="number" step="0.1000" class="control" name="attributes[]" v-model="attributes_list[index].value" placeholder="Enter Value">
                                        </div>

                                        <div v-else-if='attributes_list[index].type == "boolean"'>
                                            <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option selected="selected">is</option>
                                            </select>

                                            <select class="control" name="attributes[]" v-model="attributes_list[index].value">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>

                                        <div v-else-if='attributes_list[index].type == "date"'>
                                            <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option :selected="true">is</option>
                                            </select>

                                            <date>
                                                <input type="text" class="control" v-model="attributes_list[index].value" name="attributes[]" v-validate="'required'" value="Enter Value">
                                            </date>
                                        </div>

                                        <div v-else-if='attributes_list[index].type == "datetime"'>
                                            <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option selected="selected">is</option>
                                            </select>

                                            <datetime>
                                                <input type="text" class="control" v-model="attributes_list[index].value" name="attributes[]" v-validate="'required'" value="Enter Value">
                                            </datetime>
                                        </div>

                                        <div v-else-if='attributes_list[index].type == "select" || attributes_list[index].type == "multiselect"'>
                                            <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option selected="selected">is</option>
                                            </select>

                                            <datetime>
                                                <input type="text" class="control" v-model="attributes_list[index].value" name="attributes[]" v-validate="'required'" value="Enter Select Values">
                                            </datetime>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- category -->
                            <div v-for="(cat, index) in cats">
                                <div class="control-container mt-20">
                                    <div class="title-bar">
                                        <span>Category </span>
                                        <span class="icon cross-icon" v-on:click="removeCat(index)"></span>
                                    </div>

                                    <div class="control-group mt-15" :key="index">
                                        <select class="control" name="categories[]" v-model="cats[index].condition" v-validate="'required'" title="You Can Make Multiple Selections Here" style="margin-right: 15px;">
                                            <option>is</option>
                                            <option>is any of</option>
                                            <option>contains</option>
                                        </select>

                                        <select class="control" name="categories[]" v-model="cats[index].category" v-validate="'required'" value="{{ old('category') }}" data-vv-as="&quot;{{ __('admin::app.promotion.category') }}&quot;" multiple>
                                            <option disabled="disabled">Select Category</option>
                                            <option v-for="category in categories" :value="category.id">@{{ category.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <accordian :active="true" title="Actions">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('apply') ? 'has-error' : '']">
                            <label for="apply" class="required">Apply</label>

                            <select class="control" name="apply" v-model="apply" v-validate="'required'" value="{{ old('apply') }}" data-vv-as="&quot;Apply As&quot;" v-on:change="detectApply">
                                <option value="1">{{ __('admin::app.promotion.catalog.apply-percent') }}</option>
                                <option value="2">{{ __('admin::app.promotion.catalog.apply-fixed') }}</option>
                                <option value="3">{{ __('admin::app.promotion.catalog.adjust-to-percent') }}</option>
                                <option value="4">{{ __('admin::app.promotion.catalog.adjust-to-value') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('apply')">@{{ errors.first('apply') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('disc_amount') ? 'has-error' : '']" v-if="apply_amt">
                            <label for="disc_amount" class="required">{{ __('admin::app.promotion.general-info.disc_amt') }}</label>

                            <input type="number" step="1.0000" class="control" name="disc_amount" v-model="disc_amount" v-validate="'required|decimal|min_value:0.0001'" value="{{ old('disc_amount') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_amt') }}&quot;">

                            <span class="control-error" v-if="errors.has('disc_amount')">@{{ errors.first('disc_amount') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('disc_percent') ? 'has-error' : '']" v-if="apply_prct">
                            <label for="disc_percent" class="required">{{ __('admin::app.promotion.general-info.disc_percent') }}</label>

                            <input type="number" step="0.5000" class="control" name="disc_percent" v-model="disc_percent" v-validate="'required|decimal|min_value:0.0001'" value="{{ old('disc_percent') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_percent') }}&quot;">

                            <span class="control-error" v-if="errors.has('disc_percent')">@{{ errors.first('disc_percent') }}</span>
                        </div>
                    </div>
                </accordian>
            </div>
        </script>

        <script>
            Vue.component('catalog-rule', {
                template: '#catalog-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        apply: null,
                        apply_amt: false,
                        apply_prct: false,
                        attributes_list: [],
                        attrs_input: @json($criteria[0]),
                        attr_object: {
                            attribute: null,
                            condition: null,
                            type: null,
                            value: null
                        },
                        attr_obj_count: 0,
                        cat: {
                            category: null,
                            condition: null,
                        },
                        categories: @json($criteria[1]),
                        cats: [],
                        cats_count: 0,
                        channels: [],
                        conditions: [
                        ],
                        criteria: null,
                        customer_groups: [],
                        description: null,
                        disc_amount: 0.0,
                        disc_percent: 0.0,
                        ends_till: null,
                        end_other_rules: null,
                        name: null,
                        priority: 0,
                        starts_from: null
                    }
                },

                mounted () {
                },

                methods: {
                    addCondition () {
                        if (this.criteria == 'attribute' || this.criteria == 'category') {
                            this.condition_on = this.criteria;
                        } else {
                            alert('please try again');

                            return false;
                        }

                        if (this.condition_on == 'attribute') {
                            this.attributes_list.push(this.attr_object);

                            this.attr_object = {
                                attribute: null,
                                condition: null,
                                value: null,
                                type: null,
                                options: null
                            };
                        } else if (this.condition_on == 'category') {
                            this.cats.push(this.cat);
                            this.conditions.push(this.cat);

                            this.cat = {
                                category: null,
                                condition: null
                            };
                        }
                    },

                    enableCondition(event, index) {
                        this.attributes_list[index].type = this.attrs_input[event.target.selectedIndex - 1].type;

                        var this_this = this;

                        if (this.attrs_input[event.target.selectedIndex - 1].type == 'select' || this.attrs_input[event.target.selectedIndex - 1].type == 'multiselect') {
                            axios.post('{{ route('admin.catalog-rule.options') }}', {
                                'attribute' : this.attrs_input[event.target.selectedIndex - 1].attribute
                            })
                            .then(function (response) {
                                console.log(response);

                                this.attributes_list[index].value = null;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                        }
                    },

                    detectApply() {
                        if (this.apply == 1 || this.apply == 3) {
                            this.apply_prct = true;
                            this.apply_amt = false;
                        } else if (this.apply == 2 || this.apply == 4) {
                            this.apply_prct = false;
                            this.apply_amt = true;
                        }
                    },

                    removeAttr(index) {
                        this.attrs.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    }
                }
            });
        </script>
    @endpush
@stop