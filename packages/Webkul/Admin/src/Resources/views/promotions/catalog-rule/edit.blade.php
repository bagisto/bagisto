@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.edit-catalog-rule') }}
@stop

@section('content')
    <div class="content">
        <catalog-rule></catalog-rule>
    </div>

    @push('scripts')
        <script type="text/x-template" id="catalog-rule-form-template">
            <div>
                {{ dd($catalog_rule[5]) }}
                <form method="POST" action="{{ route('admin.catalog-rule.update') }}" @submit.prevent="onSubmit">
                    @csrf

                    <div class="page-header">
                        <div class="page-title">
                            <h1>
                                <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                                {{ __('admin::app.promotion.edit-catalog-rule') }}
                            </h1>
                        </div>

                        <div class="page-action">
                            <button type="submit" class="btn btn-lg btn-primary">
                                {{ __('admin::app.promotion.edit-btn-title') }}
                            </button>
                        </div>
                    </div>

                    <div class="page-content">
                        <div class="form-container">
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

                                        <select type="text" class="control" name="customer_groups[]" v-model="customer_groups" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Customer Groups</option>
                                            @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups[]') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                        <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>

                                        <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.channels') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Channels</option>
                                            @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('channels[]') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                        <label for="status" class="required">{{ __('admin::app.promotion.general-info.status') }}</label>

                                        <select type="text" class="control" name="status" v-model="status" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.status') }}&quot;">
                                            <option disabled="disabled">Select status</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('end_other_rules') ? 'has-error' : '']">
                                        <label for="end_other_rules" class="required">{{ __('admin::app.promotion.general-info.end_other_rules') }}</label>

                                        <select type="text" class="control" name="end_other_rules" v-model="end_other_rules" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.end_other_rules') }}&quot;">
                                            <option disabled="disabled">Select option</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('end_other_rules')">@{{ errors.first('end_other_rules') }}</span>
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

                                            <select type="text" class="control" name="criteria" v-model="criteria" v-validate="'required'" value="" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;">
                                                    <option value="condition_combination">Condition Combination</option>
                                                    <option value="attribute">Attribute</option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('criteria')">@{{ errors.first('criteria') }}</span>
                                        </div>

                                        <span class="btn btn-primary btn-lg" v-on:click="addCondition">Add Condition</span>
                                    </div>

                                    <div class="condition-set" v-if="attributes_list.length">
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
                                                            @foreach($catalog_rule[4]['text'] as $key => $value)
                                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                                            @endforeach
                                                        </select>

                                                        <input type="text" class="control" name="attributes[]" v-model="attributes_list[index].value" placeholder="Enter Value">
                                                    </div>

                                                    <div v-if='attributes_list[index].type == "price"'>
                                                        <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                            @foreach($catalog_rule[4]['numeric'] as $key => $value)
                                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                                            @endforeach
                                                        </select>

                                                        <input type="number" step="0.1000" class="control" name="attributes[]" v-model="attributes_list[index].value" placeholder="Enter Value">
                                                    </div>

                                                    <div v-else-if='attributes_list[index].type == "boolean"'>
                                                        <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                            <option selected="selected">is</option>
                                                        </select>

                                                        <select class="control" name="attributes[]" v-model="attributes_list[index].value">
                                                            @foreach($catalog_rule[4]['boolean'] as $key => $value)
                                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div v-else-if='attributes_list[index].type == "date"'>
                                                        <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                            @foreach($catalog_rule[4]['numeric'] as $key => $value)
                                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                                            @endforeach
                                                        </select>

                                                        <date>
                                                            <input type="text" class="control" v-model="attributes_list[index].value" name="attributes[]" v-validate="'required'" value="Enter Value">
                                                        </date>
                                                    </div>

                                                    <div v-else-if='attributes_list[index].type == "datetime"'>
                                                        <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                            @foreach($catalog_rule[4]['numeric'] as $key => $value)
                                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                                            @endforeach
                                                        </select>

                                                        <datetime>
                                                            <input type="text" class="control" v-model="attributes_list[index].value" name="attributes[]" v-validate="'required'" value="Enter Value">
                                                        </datetime>
                                                    </div>

                                                    <div v-else-if='attributes_list[index].type == "select" || attributes_list[index].type == "multiselect"'>
                                                        <select class="control" name="attributes[]" v-model="attributes_list[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                            @foreach($catalog_rule[4]['text'] as $key => $value)
                                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                                            @endforeach
                                                        </select>

                                                        <select class="control" v-model="attributes_list[index].value" name="attributes[]" v-validate="'required'" multiple>
                                                            <option v-for="option in attributes_list[index].options" :value="option.id">@{{ option.admin_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="all_conditions[]" v-model="all_conditions">
                                    </div>
                                </div>
                            </accordian>

                            <accordian :active="true" title="Actions">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('apply') ? 'has-error' : '']">
                                        <label for="apply" class="required">Apply</label>

                                        <select class="control" name="apply" v-model="apply" v-validate="'required'" value="{{ old('apply') }}" data-vv-as="&quot;Apply As&quot;" v-on:change="detectApply">
                                            @foreach($catalog_rule[3]['actions'] as $key => $value)
                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                            @endforeach
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
                    </div>
                </form>
            </div>
        </script>

        <script>
            Vue.component('catalog-rule', {
                template: '#catalog-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        all_conditions: [],
                        apply: null,
                        apply_amt: false,
                        apply_prct: false,
                        applied_config: @json($catalog_rule[3]),
                        attributes_list: [],
                        attrs_input: @json($catalog_rule[0]),
                        attrs_options: @json($catalog_rule[2]),
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
                        categories: @json($catalog_rule[1]),
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
                        starts_from: null,
                        status: null,
                    }
                },

                mounted () {
                    all = @json($catalog_rule[5]);
                    this.name = all.name;
                    this.description = all.description;
                    this.apply = all.action_type;
                    this.end_other_rules = all.end_other_rules;
                    this.criteria = 'attribute';

                    channels = @json($catalog_rule[6]);
                    for(i in channels) {
                        this.channels.push(channels[i].channel_id);
                    }

                    customer_groups = @json($catalog_rule[7]);
                    for(i in customer_groups) {
                        this.customer_groups.push(customer_groups[i].customer_group_id);
                    }

                    this.attributes_list = JSON.parse(JSON.parse(all.conditions)[0]);
                    this.status = @json($catalog_rule[5]).status;
                    this.starts_from = @json($catalog_rule[5]).starts_from;
                    this.ends_till = @json($catalog_rule[5]).ends_till;
                    this.priority = @json($catalog_rule[5]).priority;

                    if (this.apply == 0 || this.apply == 2) {
                        this.apply_prct = true;

                        this.disc_percent = @json($catalog_rule[5]).disc_percent;
                    } else if (this.apply == 1 || this.apply == 3) {
                        this.apply_amt = true;

                        this.disc_amount = @json($catalog_rule[5]).disc_amount;
                    }
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
                            console.log(this.attrs_input[event.target.selectedIndex - 1].name);

                            this.attributes_list[index].options = this.attrs_options[this.attrs_input[event.target.selectedIndex - 1].name];

                            this.attributes_list[index].value = [];
                        }
                    },

                    detectApply() {
                        if (this.apply == 0 || this.apply == 2) {
                            this.apply_prct = true;
                            this.apply_amt = false;
                        } else if (this.apply == 1 || this.apply == 3) {
                            this.apply_prct = false;
                            this.apply_amt = true;
                        }
                    },

                    removeAttr(index) {
                        this.attrs.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    },

                    onSubmit: function (e) {
                        for (index in this.attributes_list) {
                            if (this.attributes_list[index].condition == null || this.attributes_list[index].condition == "" || this.attributes_list[index].condition == undefined) {
                                window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                                this.$root.addFlashMessages();

                                return false;
                            } else if (this.attributes_list[index].value == null || this.attributes_list[index].value == "" || this.attributes_list[index].value == undefined) {
                                window.flashMessages = [{'type': 'alert-error', 'message': "{{ __('admin::app.promotion.catalog.condition-missing') }}" }];

                                this.$root.addFlashMessages();

                                return false;
                            }
                        }

                        this.all_conditions = JSON.stringify(this.attributes_list);

                        this.$validator.validateAll().then(result => {
                            if (result) {
                                e.target.submit();
                            }
                        });
                    },

                    addFlashMessages() {
                        const flashes = this.$refs.flashes;

                        flashMessages.forEach(function(flash) {
                            flashes.addFlash(flash);
                        }, this);
                    }
                }
            });
        </script>
    @endpush
@stop