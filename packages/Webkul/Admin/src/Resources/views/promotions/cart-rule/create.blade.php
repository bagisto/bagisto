@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-cart-rule') }}
@stop

@section('content')

    <div class="content">
        <cart-rule></cart-rule>
    </div>

    @push('scripts')
        <script type="text/x-template" id="cart-rule-form-template">
            <form method="POST" action="{{ route('admin.cart-rule.store') }}" @submit.prevent="onSubmit">
                @csrf

                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                            {{ __('admin::app.promotion.add-cart-rule') }}
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

                                        <textarea class="control" name="description" v-model="description" value="{{ old('description') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.description') }}&quot;"></textarea>

                                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                                    </div>

                                    <datetime :name="starts_from">
                                        <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                                            <label for="starts_from">{{ __('admin::app.promotion.general-info.starts-from') }}</label>

                                            <input type="text" class="control" v-model="starts_from" name="starts_from" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.starts-from') }}&quot;">

                                            <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                                        </div>
                                    </datetime>

                                    <datetime :name="starts_from">
                                        <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                                            <label for="ends_till">{{ __('admin::app.promotion.general-info.ends-till') }}</label>

                                            <input type="text" class="control" v-model="ends_till" name="ends_till" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.ends-till') }}&quot;">

                                            <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                                        </div>
                                    </datetime>

                                    <div class="control-group" :class="[errors.has('customer_groups[]') ? 'has-error' : '']">
                                        <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.cust-groups') }}</label>

                                        <select type="text" class="control" name="customer_groups[]" v-model="customer_groups" v-validate="'required'" value="{{ old('customer_groups[]') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Customer Groups</option>
                                            @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.has('customer_groups')">@{{ errors.first('customer_groups') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                        <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>

                                        <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.channels-req') }}&quot;" multiple="multiple">
                                            <option disabled="disabled">Select Channels</option>
                                            @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                            @endforeach
                                        </select>

                                        <span class="control-error" v-if="errors.first('channels[]')">@{{ errors.first('channels[]') }}</span>
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

                                    <div class="control-group" :class="[errors.has('use_coupon') ? 'has-error' :
                                    '']">
                                        <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.is-coupon') }}</label>

                                        <select type="text" class="control" name="use_coupon" v-model="use_coupon" v-validate="'required'" value="{{ old('use_coupon')}}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.is-coupon') }}&quot;">
                                            <option value="1" :selected="use_coupon == 1">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>
                                            <option value="0" :selected="use_coupon == 0">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('use_coupon')">@{{ errors.first('use_coupon') }}</span>
                                    </div>

                                    {{-- <div class="control-group" :class="[errors.has('auto_generation') ? 'has-error' : '']" v-if="use_coupon == 1">
                                        <label for="auto_generation" class="required">{{ __('admin::app.promotion.general-info.specific-coupon') }}</label>

                                        <input type="checkbox" class="control" name="auto_generation" v-model="auto_generation" value="{{ old('auto_generation') }}" data-vv-as="&quot;Specific Coupon&quot;" v-on:change="checkAutogen">

                                        <span class="control-error" v-if="errors.has('auto_generation')">@{{ errors.first('auto_generation') }}</span>
                                    </div> --}}

                                    {{-- <input type="hidden" name="auto_generation" v-model="auto_generation"> --}}

                                    {{-- <div class="control-group" :class="[errors.has('per_customer') ? 'has-error' : '']">
                                        <label for="per_customer" class="required">{{ __('admin::app.promotion.general-info.uses-per-cust') }}</label>

                                        <input type="number" step="1" class="control" name="per_customer" v-model="per_customer" v-validate="'required|numeric|min_value:0'" value="{{ old('per_customer') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.uses-per-cust') }}&quot;">

                                        <span class="control-error" v-if="errors.has('per_customer')">@{{ errors.first('per_customer') }}</span>
                                    </div> --}}

                                    <div class="control-group" :class="[errors.has('is_guest') ? 'has-error' : '']">
                                        <label for="is_guest" class="required">{{ __('admin::app.promotion.general-info.is-guest') }}</label>

                                        <select type="text" class="control" name="is_guest" v-model="is_guest" v-validate="'required'" value="{{ old('is_guest')}}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.is-guest') }}&quot;">
                                            <option value="1" :selected="is_guest == 1">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>
                                            <option value="0" :selected="is_guest == 0">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('is_guest')">@{{ errors.first('is_guest') }}</span>
                                    </div>

                                    {{-- <div class="control-group" :class="[errors.has('usage_limit') ? 'has-error' : '']">
                                        <label for="usage_limit" class="required">{{ __('admin::app.promotion.general-info.limit') }}</label>

                                        <input type="number" step="1" class="control" name="usage_limit" v-model="usage_limit" v-validate="'required|numeric|min_value:0'" value="{{ old('usage_limit') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.uses-per-cust') }}&quot;">

                                        <span class="control-error" v-if="errors.has('usage_limit')">@{{ errors.first('usage_limit') }}</span>
                                    </div> --}}

                                    <div class="control-group" :class="[errors.has('priority') ? 'has-error' : '']">
                                        <label for="priority" class="required">{{ __('admin::app.promotion.general-info.priority') }}</label>

                                        <input type="number" class="control" step="1" name="priority" v-model="priority" v-validate="'required|numeric|min_value:1'" value="{{ old('priority') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.priority') }}&quot;">

                                        <span class="control-error" v-if="errors.has('priority')">@{{ errors.first('priority') }}</span>
                                    </div>
                                </div>
                            </accordian>

                            <accordian :active="false" title="Conditions">
                                <div slot="body">
                                    <input type="hidden" name="all_conditions" v-model="all_conditions">

                                    <div class="add-condition">
                                        <div class="control-group">
                                            <label for="criteria" class="required">{{ __('admin::app.promotion.general-info.add-condition') }}</label>

                                            <select type="text" class="control" v-model="criteria">
                                                <option value="cart">Cart Properties</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        {{ __('admin::app.promotion.general-info.test-mode') }}
                                        <select class="control" v-model="match_criteria" style="margin-right: 15px;">
                                            {{ $i = 0 }}
                                            @foreach(config('pricerules.test_mode') as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                {{ $i++ }}
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="condition-set">
                                        <!-- Cart Attributes -->
                                        <div v-for="(condition, index) in conditions_list" :key="index">
                                            <div class="control-container mt-20">
                                                <div class="title-bar">
                                                    <span>Cart Attribute is </span>
                                                    <span class="icon cross-icon" v-on:click="removeCartAttr(index)"></span>
                                                </div>

                                                <div class="control-group mt-10" :key="index">
                                                    <select class="control" name="cart_attributes[]" v-model="conditions_list[index].attribute" title="You Can Make Multiple Selections Here" style="margin-right: 15px;" v-on:change="enableCondition($event, index)">
                                                        <option disabled="disabled">Select Option</option>
                                                        <option v-for="(cart_ip, index1) in cart_input" :value="cart_ip.code" :key="index1">@{{ cart_ip.name }}</option>
                                                    </select>

                                                    <div v-if='conditions_list[index].type == "string"'>
                                                        <select class="control" name="cart_attributes[]" v-model="conditions_list[index].condition" style="margin-right: 15px;">
                                                            <option v-for="(condition, index) in conditions.string" :value="index" :key="index">@{{ condition }}</option>
                                                        </select>

                                                        <div v-if='conditions_list[index].attribute == "shipping_state"'>
                                                            <select class="control" v-model="conditions_list[index].value">
                                                                <option disabled="disabled">Select State</option>
                                                                <optgroup v-for='(state, code) in country_and_states.states' :label="code">
                                                                    <option v-for="(stateObj, index) in state" :value="stateObj.code">@{{ stateObj.default_name }}</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>

                                                        <div v-if='conditions_list[index].attribute == "shipping_country"'>
                                                            <select class="control" v-model="conditions_list[index].value">
                                                                <option disabled="disabled">Select Country</option>
                                                                <option v-for="(country, index) in country_and_states.countries" :value="country.code">@{{ country.name }}</option>
                                                            </select>
                                                        </div>

                                                        <input type="text" class="control" name="cart_attributes[]" v-model="conditions_list[index].value" placeholder="Enter Value" v-if='conditions_list[index].attribute != "shipping_state" && conditions_list[index].attribute != "shipping_country"'>
                                                    </div>

                                                    <div v-if='conditions_list[index].type == "numeric"'>
                                                        <select class="control" name="attributes[]" v-model="conditions_list[index].condition" style="margin-right: 15px;">
                                                            <option v-for="(condition, index) in conditions.numeric" :value="index" :key="index">@{{ condition }}</option>
                                                        </select>

                                                        <input type="number" step="0.1000" class="control" name="cart_attributes[]" v-model="conditions_list[index].value" placeholder="Enter Value">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <span class="btn btn-primary btn-lg mt-20" v-on:click="addCondition">Add Condition</span>
                                </div>
                            </accordian>

                            <accordian :active="false" title="Actions">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('action_type') ? 'has-error' : '']">
                                        <label for="action_type" class="required">Apply</label>

                                        <select class="control" name="action_type" v-model="action_type" v-validate="'required'" value="{{ old('action_type') }}" data-vv-as="&quot;Apply As&quot;" v-on:change="detectApply">
                                            <option v-for="(action, index) in actions" :value="index">@{{ action }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('action_type')">@{{ errors.first('action_type') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('disc_amount') ? 'has-error' : '']">
                                        <label for="disc_amount" class="required">{{ __('admin::app.promotion.general-info.disc_amt') }}</label>

                                        <input type="number" step="0.5000" class="control" name="disc_amount" v-model="disc_amount" v-validate="'required|min_value:0.0001'" value="{{ old('disc_amount') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_amt') }}&quot;">

                                        <span class="control-error" v-if="errors.has('disc_amount')">@{{ errors.first('disc_amount') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('disc_threshold') ? 'has-error' : '']">
                                        <label for="disc_threshold" class="required">{{ __('admin::app.promotion.cart.buy-atleast') }}</label>

                                        <input type="number" step="1" class="control" name="disc_threshold" v-model="disc_threshold" v-validate="'required|numeric|min_value:1'" value="{{ old('disc_threshold') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.buy-atleast') }}&quot;">

                                        <span class="control-error" v-if="errors.has('disc_threshold')">@{{ errors.first('disc_threshold') }}</span>
                                    </div>

                                    <div class="control-group" :class="[errors.has('disc_quantity') ? 'has-error' : '']">
                                        <label for="disc_quantity" class="required">{{ __('admin::app.promotion.general-info.disc_qty') }}</label>

                                        <input type="number" step="1" class="control" name="disc_quantity" v-model="disc_quantity" v-validate="'required|decimal|min_value:1'" value="{{ old('disc_quantity') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_qty') }}&quot;">

                                        <span class="control-error" v-if="errors.has('disc_quantity')">@{{ errors.first('disc_quantity') }}</span>
                                    </div>

                                    <div class="boolean-control-container">
                                        <div class="control-group" :class="[errors.has('free_shipping') ? 'has-error' : '']">
                                            <label for="free_shipping" class="required">{{ __('admin::app.promotion.general-info.free-shipping') }}</label>

                                            <select type="text" class="control" name="free_shipping" v-model="free_shipping" v-validate="'required'" value="{{ old('free_shipping') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.free-shipping') }}&quot;">
                                                <option value="0" :selected="free_shipping == 0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>

                                                <option value="1" :selected="free_shipping == 1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('free_shipping')">@{{ errors.first('free_shipping') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('apply_to_shipping') ? 'has-error' : '']">
                                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.cart.apply-to-shipping') }}</label>

                                            <select type="text" class="control" name="apply_to_shipping" v-model="apply_to_shipping" v-validate="'required'" value="{{ old('apply_to_shipping') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.apply-to-shipping') }}&quot;">
                                                <option value="0" :selected="apply_to_shipping == 0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>

                                                <option value="1" :selected="apply_to_shipping == 1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('apply_to_shipping')">@{{ errors.first('apply_to_shipping') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('end_other_rules') ? 'has-error' : '']">
                                            <label for="end_other_rules" class="required">{{ __('admin::app.promotion.general-info.end-other-rules') }}</label>

                                            <select type="text" class="control" name="end_other_rules" v-model="end_other_rules" v-validate="'required'" value="{{ old('end_other_rules')}}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.end-other-rules') }}&quot;">
                                                <option value="1" :selected="end_other_rules == 1">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>
                                                <option value="0" :selected="end_other_rules == 0">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                                            </select>

                                            <span class="control-error" v-if="errors.has('end_other_rules')">@{{ errors.first('end_other_rules') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </accordian>

                            <accordian :active="false" title="Coupons" v-if="use_coupon == 1">
                                <div slot="body">

                                    {{-- <div v-if="!auto_generation">
                                        <div class="control-group" :class="[errors.has('prefix') ? 'has-error' : '']">
                                            <label for="prefix" class="required">Prefix</label>

                                            <input type="text" class="control" name="prefix" v-model="prefix" v-validate="'alpha'" value="{{ old('prefix') }}" data-vv-as="&quot;Prefix&quot;">

                                            <span class="control-error" v-if="errors.has('prefix')">@{{ errors.first('prefix') }}</span>
                                        </div>

                                        <div class="control-group" :class="[errors.has('suffix') ? 'has-error' : '']"">
                                            <label for="suffix" class="required">Suffix</label>

                                            <input type="text" class="control" name="suffix" v-model="suffix" v-validate="'alpha'" value="{{ old('suffix') }}" data-vv-as="&quot;suffix&quot;">

                                            <span class="control-error" v-if="errors.has('suffix')">@{{ errors.first('suffix') }}</span>
                                        </div>
                                    </div> --}}

                                    <div>
                                        <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                            <label for="code" class="required">Code</label>

                                            <input type="text" class="control" name="code" v-model="code" v-validate="'required'" value="{{ old('code') }}" data-vv-as="&quot;Code&quot;">

                                            <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </accordian>

                            <accordian :active="false" :title="'{{ __('admin::app.promotion.general-info.lables') }}'">
                                <div slot="body">
                                    <div class="control-group" :class="[errors.has('label') ? 'has-error' : '']" v-if="dedicated_label">
                                        <label for="label">Global Label</label>

                                        <input type="text" class="control" name="label[global]" v-model="label.global" data-vv-as="&quot;label&quot;">

                                        <span class="control-error" v-if="errors.has('label')">@{{ errors.first('label') }}</span>
                                    </div>

                                    <div v-if="label.global == null || label.global == ''">
                                    @foreach(core()->getAllChannels() as $channel)
                                        <span>[{{ $channel->code }}]</span>
                                        @foreach($channel->locales as $locale)
                                            <div class="control-group" :class="[errors.has('label') ? 'has-error' : '']">
                                                <label for="code">{{ $locale->code }}</label>

                                                <input type="text" class="control" name="label[{{ $channel->code }}][{{ $locale->code }}]" v-model="label.{{ $channel->code }}.{{ $locale->code }}" data-vv-as="&quot;Label&quot;">

                                                <span class="control-error" v-if="errors.has('label')">@{{ errors.first('label') }}</span>
                                            </div>
                                        @endforeach
                                    @endforeach
                                    </div>
                                </div>
                            </accordian>
                        </div>
                    </div>
                </div>
            </form>
        </script>

        <script>
            Vue.component('cart-rule', {
                template: '#cart-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        name: null,
                        description: null,
                        conditions_list: [],
                        channels: [],
                        customer_groups: [],
                        ends_till: null,
                        starts_from: null,
                        priority: 0,
                        per_customer: 0,
                        status: null,
                        use_coupon: null,
                        auto_generation: false,
                        usage_limit: 0,
                        is_guest: 0,

                        action_type: null,
                        apply: null,
                        apply_amt: false,
                        apply_prct: false,
                        apply_to_shipping: null,
                        disc_amount: null,
                        disc_threshold: null,
                        disc_quantity: null,
                        end_other_rules: null,
                        coupon_type: null,
                        free_shipping: null,

                        all_conditions: [],
                        match_criteria: 'all_are_true',

                        code: null,
                        suffix: null,
                        prefix: null,
                        dedicated_label: true,

                        label: {
                            global: null,
                            @foreach(core()->getAllChannels() as $channel)
                                @foreach($channel->locales as $locale)
                                    {{ trim($channel->code) }} : {
                                        {{ trim($locale->code) }}: ''
                                    },
                                @endforeach
                            @endforeach
                        },

                        criteria: null,
                        conditions: @json($cart_rule[0]).conditions,
                        cart_input: @json($cart_rule[0]).attributes,
                        actions: @json($cart_rule[0]).actions,
                        conditions_list:[],
                        cart_object: {
                            attribute: null,
                            condition: null,
                            value: []
                        },
                        country_and_states: @json($cart_rule[2])
                    }
                },


                methods: {
                    addCondition () {
                        if (this.criteria == 'product_subselection' || this.criteria == 'cart') {
                            this.condition_on = this.criteria;
                        } else {
                            alert('Please select type of condition');

                            return false;
                        }

                        if (this.condition_on == 'cart') {
                            this.conditions_list.push(this.cart_object);

                            this.cart_object = {
                                attribute: null,
                                condition: null,
                                value: []
                            };
                        }
                    },

                    checkAutogen() {
                    },

                    detectApply() {
                        if (this.apply == 'percent_of_product' || this.apply == 'buy_a_get_b') {
                            this.apply_prct = true;
                            this.apply_amt = false;
                        } else if (this.apply == 'fixed_amount' || this.apply == 'fixed_amount_cart') {
                            this.apply_prct = false;
                            this.apply_amt = true;
                        }
                    },

                    enableCondition(event, index) {
                        selectedIndex = event.target.selectedIndex - 1;

                        for (i in this.cart_input) {
                            if (i == selectedIndex) {
                                this.conditions_list[index].type = this.cart_input[i].type;
                            }
                        }
                    },

                    // useCoupon() {
                    //     if (this.use_coupon == 0) {
                    //         this.auto_generation = null;
                    //     } else {
                    //         this.auto_generation = true;
                    //     }
                    // },

                    removeCartAttr(index) {
                        this.conditions_list.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    },

                    onSubmit: function (e) {
                        if (this.conditions_list.length != 0) {
                            this.conditions_list.push({'criteria': this.match_criteria});

                            this.all_conditions = JSON.stringify(this.conditions_list);
                        }

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