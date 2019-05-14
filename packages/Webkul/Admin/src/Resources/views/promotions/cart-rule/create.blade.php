@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-cart-rule') }}
@stop

@section('content')
    <div class="content">
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
                    <cart-rule></cart-rule>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script type="text/x-template" id="cart-rule-form-template">
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

                            <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                            <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>

                            <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                <option disabled="disabled">Select Channels</option>
                                @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach
                            </select>

                            <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('channels') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('is_coupon') ? 'has-error' : '']">
                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.is-coupon') }}</label>

                            <select type="text" class="control" name="is_coupon" v-model="is_coupon" v-validate="'required'" value="{{ old('is_coupon') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.is-coupon') }}&quot;">
                                <option value="0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>
                                <option value="1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('is_coupon')">@{{ errors.first('is_coupon') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('uses_per_cust') ? 'has-error' : '']">
                            <label for="uses_per_cust" class="required">{{ __('admin::app.promotion.general-info.uses-per-cust') }}</label>

                            <input type="number" step="1" class="control" name="uses_per_cust" v-model="uses_per_cust" v-validate="'required|numeric|min_value:1'" value="{{ old('uses_per_cust') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.uses-per-cust') }}&quot;">

                            <span class="control-error" v-if="errors.has('uses_per_cust')">@{{ errors.first('uses_per_cust') }}</span>
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
                                    <option value="cart">Cart Attribute</option>
                                    <option value="product_subselection">Product's subselection</option>
                                </select>

                                <span class="control-error" v-if="errors.has('criteria')">@{{ errors.first('criteria') }}</span>
                            </div>

                            <span class="btn btn-primary btn-lg" v-on:click="addCondition">Add Condition</span>
                        </div>

                        <div class="condition-set">

                            <!-- Cart Attribute -->
                            <div v-for="(cart_attr, index) in cart_attrs" :key="index">
                                <div class="control-container mt-20">
                                    <div class="title-bar">
                                        <span>Cart Attribute is </span>
                                        <span class="icon cross-icon" v-on:click="removeCartAttr(index)"></span>
                                    </div>

                                    <div class="control-group mt-10" :key="index">
                                        <select class="control" name="cart_attributes[]" v-model="cart_attrs[index].attribute" v-validate="'required'" title="You Can Make Multiple Selections Here" style="margin-right: 15px;" v-on:change="enableCondition($event, index)">
                                            <option disabled="disabled">Select attribute</option>

                                            <option v-for="(cart_attribute, index1) in cart_attributes" :value="cart_attribute.name" :key="index1">@{{ cart_attribute.name }}</option>
                                        </select>

                                        <div v-if='cart_attrs[index].type == "string"'>
                                            <select class="control" name="cart_attributes[]" v-model="cart_attrs[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option v-for="(config_param, index) in config_params.text" :value="config_param" :key="index">@{{ config_param }}</option>
                                            </select>

                                            <input type="text" class="control" name="cart_attributes[]" v-model="cart_attrs[index].value" placeholder="Enter Value">
                                        </div>

                                        <div v-if='cart_attrs[index].type == "numeric"'>
                                            <select class="control" name="attributes[]" v-model="cart_attrs[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                                <option v-for="(config_param, index) in config_params.numeric" :value="config_param" :key="index">@{{ config_param }}</option>
                                            </select>

                                            <input type="number" step="0.1000" class="control" name="cart_attributes[]" v-model="cart_attrs[index].value" placeholder="Enter Value">
                                        </div>
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

                        <div class="control-group" :class="[errors.has('buy_atleast') ? 'has-error' : '']">
                            <label for="buy_atleast" class="required">{{ __('admin::app.promotion.cart.buy-atleast') }}</label>

                            <input type="number" step="1" class="control" name="buy_atleast" v-model="buy_atleast" v-validate="'required|numeric|min_value:1'" value="{{ old('buy_atleast') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.buy-atleast') }}&quot;">

                            <span class="control-error" v-if="errors.has('buy_atleast')">@{{ errors.first('buy_atleast') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('apply_to_shipping') ? 'has-error' : '']">
                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.cart.apply-to-shipping') }}</label>

                            <select type="text" class="control" name="apply_to_shipping" v-model="apply_to_shipping" v-validate="'required'" value="{{ old('apply_to_shipping') }}" data-vv-as="&quot;{{ __('admin::app.promotion.cart.apply-to-shipping') }}&quot;">
                                <option value="0">{{ __('admin::app.promotion.general-info.is-coupon-yes') }}</option>

                                <option value="1">{{ __('admin::app.promotion.general-info.is-coupon-no') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('apply_to_shipping')">@{{ errors.first('apply_to_shipping') }}</span>
                        </div>
                    </div>
                </accordian>
            </div>
        </script>

        <script>
            Vue.component('cart-rule', {
                template: '#cart-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        type: [],
                        apply: null,
                        apply_amt: false,
                        apply_prct: false,
                        apply_to_shipping: null,
                        buy_atleast: null,
                        attribute_options: @json($criteria[1]),
                        cart_attributes: @json($criteria[0]).cart,
                        cart_attr: {
                            attribute: null,
                            condition: null,
                            value: null,
                            type: null
                        },
                        cart_attrs: [],
                        cart_attrs_count: 0,
                        channels: [],
                        conditions: [],
                        config_params: @json($criteria[0]).conditions,
                        criteria: null,
                        customer_groups: [],
                        description: null,
                        disc_amount: 0.0,
                        disc_percent: 0.0,
                        ends_till: null,
                        end_other_rules: null,
                        is_coupon: null,
                        name: null,
                        priority: 0,
                        starts_from: null,
                        uses_per_cust: 0,
                    }
                },

                mounted () {
                },

                methods: {
                    addCondition () {
                        console.log(this.criteria);

                        if (this.criteria == 'product_subselection' || this.criteria == 'cart') {
                            this.condition_on = this.criteria;
                        } else {
                            alert('please try again');

                            return false;
                        }

                        if (this.condition_on == 'cart') {
                            this.cart_attrs.push(this.cart_attr);

                            this.cart_attr = {
                                attribute: null,
                                condition: null,
                                value: null,
                                type: null
                            };
                        } else if (this.condition_on == 'product_subselection') {
                            // this.cats.push(this.cat);

                            // this.cat = {
                            //     category: null,
                            //     condition: null
                            // };
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

                    enableCondition(event, pushedIndex) {
                        selectedIndex = event.target.selectedIndex - 1;

                        for (i in this.cart_attributes) {
                            if (i == selectedIndex) {
                                this.cart_attrs[pushedIndex].type = this.cart_attributes[i].type;
                            }
                        }
                    },

                    removeCartAttr(index) {
                        this.cart_attrs.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    }
                }
            });
        </script>
    @endpush
@stop