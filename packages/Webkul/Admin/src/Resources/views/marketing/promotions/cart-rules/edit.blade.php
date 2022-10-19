@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotions.cart-rules.edit-title') }}
@stop

@section('content')
    <div class="content">
        <cart-rule></cart-rule>
    </div>
@stop

@push('scripts')
    <script type="text/x-template" id="cart-rule-template">
        <div>
            <form method="POST" action="{{ route('admin.cart_rules.update', $cartRule->id) }}" @submit.prevent="onSubmit">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link"
                            onclick="window.location = '{{ route('admin.cart_rules.index') }}'"></i>

                            {{ __('admin::app.promotions.cart-rules.edit-title') }}
                        </h1>
                    </div>

                    <div class="page-action">
                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('admin::app.promotions.cart-rules.save-btn-title') }}
                        </button>
                    </div>
                </div>

                <div class="page-content">
                    <div class="form-container">
                        @csrf()

                        {!! view_render_event('bagisto.admin.promotions.cart-rules.create.before') !!}

                        <accordian title="{{ __('admin::app.promotions.cart-rules.rule-information') }}" :active="true">
                            <div slot="body">
                                <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                    <label for="name" class="required">{{ __('admin::app.promotions.cart-rules.name') }}</label>

                                    <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.name') }}&quot;" value="{{ old('name') ?: $cartRule->name }}"/>

                                    <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                                </div>

                                <div class="control-group">
                                    <label for="description">{{ __('admin::app.promotions.cart-rules.description') }}</label>

                                    <textarea class="control" id="description" name="description">{{ old('description') ?: $cartRule->description }}</textarea>
                                </div>

                                <div class="control-group">
                                    <label for="status">{{ __('admin::app.promotions.cart-rules.status') }}</label>

                                    <label class="switch">
                                        <input type="checkbox" id="status" name="status" value="{{ $cartRule->status }}" {{ $cartRule->status ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="control-group multi-select" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                    <label for="channels" class="required">{{ __('admin::app.promotions.cart-rules.channels') }}</label>

                                    @php
                                        $selectedOptionIds = old('channels') ?: $cartRule->channels->pluck('id')->toArray();
                                    @endphp

                                    <select class="control" id="channels" name="channels[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.channels') }}&quot;" multiple="multiple">

                                        @foreach(core()->getAllChannels() as $channel)
                                            <option value="{{ $channel->id }}" {{ in_array($channel->id, $selectedOptionIds) ? 'selected' : '' }}>
                                                {{ core()->getChannelName($channel) }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('channels[]') }}</span>
                                </div>

                                <div class="control-group" :class="[errors.has('customer_groups[]') ? 'has-error' : '']">
                                    <label for="customer_groups" class="required">{{ __('admin::app.promotions.cart-rules.customer-groups') }}</label>

                                    @php
                                        $selectedOptionIds = old('customer_groups') ?: $cartRule->customer_groups->pluck('id')->toArray();
                                    @endphp

                                    <select class="control" id="customer_groups" name="customer_groups[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.customer-groups') }}&quot;" multiple="multiple">

                                        @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $customerGroup)
                                            <option value="{{ $customerGroup->id }}" {{ in_array($customerGroup->id, $selectedOptionIds) ? 'selected' : '' }}>
                                                {{ $customerGroup->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups[]') }}</span>
                                </div>

                                <div class="control-group" :class="[errors.has('coupon_type') ? 'has-error' : '']">
                                    <label for="coupon_type" class="required">{{ __('admin::app.promotions.cart-rules.coupon-type') }}</label>

                                    <select class="control" id="coupon_type" name="coupon_type" v-model="coupon_type" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.coupon-type') }}&quot;">
                                        <option value="0" {{ old('coupon_type') == 0 ? 'selected' : '' }}>{{ __('admin::app.promotions.cart-rules.no-coupon') }}</option>
                                        <option value="1" {{ old('coupon_type') == 1 ? 'selected' : '' }}>{{ __('admin::app.promotions.cart-rules.specific-coupon') }}</option>
                                    </select>

                                    <span class="control-error" v-if="errors.has('coupon_type')">@{{ errors.first('coupon_type') }}</span>
                                </div>

                                <div v-if="parseInt(coupon_type)">
                                    <div class="control-group" :class="[errors.has('use_auto_generation') ? 'has-error' : '']">
                                        <label for="use_auto_generation" class="required">{{ __('admin::app.promotions.cart-rules.auto-generate-coupon') }}</label>

                                        <select class="control" id="use_auto_generation" name="use_auto_generation" v-model="use_auto_generation" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.auto-generate-coupon') }}&quot;">
                                            <option value="0">{{ __('admin::app.promotions.cart-rules.no') }}</option>
                                            <option value="1">{{ __('admin::app.promotions.cart-rules.yes') }}</option>
                                        </select>

                                        <span class="control-error" v-if="errors.has('use_auto_generation')">@{{ errors.first('use_auto_generation') }}</span>
                                    </div>

                                    <div v-if="! parseInt(use_auto_generation)">
                                        <div class="control-group" :class="[errors.has('coupon_code') ? 'has-error' : '']">
                                            <label for="coupon_code" class="required">{{ __('admin::app.promotions.cart-rules.coupon-code') }}</label>

                                            <input v-validate="'required'" class="control" id="coupon_code" name="coupon_code" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.coupon-code') }}&quot;" value="{{ old('coupon_code') ?: $cartRule->coupon_code }}"/>

                                            <span class="control-error" v-if="errors.has('coupon_code')">@{{ errors.first('coupon_code') }}</span>
                                        </div>
                                    </div>

                                    <div class="control-group" :class="[errors.has('uses_per_coupon') ? 'has-error' : '']">
                                        <label for="uses_per_coupon">{{ __('admin::app.promotions.cart-rules.uses-per-coupon') }}</label>

                                        <input  v-validate="'numeric'" class="control" id="uses_per_coupon" name="uses_per_coupon" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.uses-per-coupon') }}&quot;" value="{{ old('uses_per_coupon') ?: $cartRule->uses_per_coupon }}"/>

                                        <span class="control-error" v-if="errors.has('uses_per_coupon')">@{{ errors.first('uses_per_coupon') }}</span>
                                    </div>
                                </div>

                                <div class="control-group" :class="[errors.has('usage_per_customer') ? 'has-error' : '']">
                                    <label for="usage_per_customer">{{ __('admin::app.promotions.cart-rules.uses-per-customer') }}</label>

                                    <input v-validate="'numeric'" class="control" id="usage_per_customer" name="usage_per_customer" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.uses-per-customer') }}&quot;"  value="{{ old('usage_per_customer') ?: $cartRule->usage_per_customer }}"/>

                                    <span class="control-info">{{ __('admin::app.promotions.cart-rules.uses-per-customer-control-info') }}</span>
                                    <span class="control-error" v-if="errors.has('usage_per_customer')">@{{ errors.first('usage_per_customer') }}</span>
                                </div>

                                <div class="control-group date">
                                    <label for="starts_from">{{ __('admin::app.promotions.cart-rules.from') }}</label>

                                    <datetime>
                                        <input type="text" name="starts_from" class="control" value="{{ old('starts_from') ?: $cartRule->starts_from }}"/>
                                    </datetime>
                                </div>

                                <div class="control-group date">
                                    <label for="ends_till">{{ __('admin::app.promotions.cart-rules.to') }}</label>

                                    <datetime>
                                        <input type="text" name="ends_till" class="control" value="{{ old('ends_till') ?: $cartRule->ends_till }}"/>
                                    </datetime>
                                </div>

                                <div class="control-group">
                                    <label for="sort_order">{{ __('admin::app.promotions.cart-rules.priority') }}</label>

                                    <input type="text" class="control" id="sort_order" name="sort_order" value="{{ $cartRule->sort_order }}" {{ $cartRule->sort_order ? 'checked' : '' }}/>
                                </div>
                            </div>
                        </accordian>

                        <accordian title="{{ __('admin::app.promotions.cart-rules.conditions') }}" :active="false">
                            <div slot="body">
                                <div class="control-group">
                                    <label for="condition_type">{{ __('admin::app.promotions.cart-rules.condition-type') }}</label>

                                    <select class="control" id="condition_type" name="condition_type" v-model="condition_type">
                                        <option value="1">{{ __('admin::app.promotions.cart-rules.all-conditions-true') }}</option>
                                        <option value="2">{{ __('admin::app.promotions.cart-rules.any-condition-true') }}</option>
                                    </select>
                                </div>

                                <cart-rule-condition-item
                                    v-for='(condition, index) in conditions'
                                    :condition="condition"
                                    :key="index"
                                    :index="index"
                                    @onRemoveCondition="removeCondition($event)">
                                </cart-rule-condition-item>

                                <button type="button" class="btn btn-lg btn-primary" style="margin-top: 20px;" @click="addCondition">
                                    {{ __('admin::app.promotions.cart-rules.add-condition') }}
                                </button>
                            </div>
                        </accordian>

                        <accordian title="{{ __('admin::app.promotions.cart-rules.actions') }}" :active="false">
                            <div slot="body">
                                <div class="control-group" :class="[errors.has('action_type') ? 'has-error' : '']">
                                    <label for="action_type" class="required">{{ __('admin::app.promotions.cart-rules.action-type') }}</label>

                                    @php
                                        $selectedOption = old('action_type') ?: $cartRule->action_type;
                                    @endphp

                                    <select class="control" id="action_type" name="action_type" v-validate="'required'" v-model="action_type" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.action-type') }}&quot;">
                                        <option value="by_percent" {{ $selectedOption == 'by_percent' ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.percentage-product-price') }}
                                        </option>

                                        <option value="by_fixed" {{ $selectedOption == 'by_fixed' ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.fixed-amount') }}
                                        </option>

                                        <option value="cart_fixed" {{ $selectedOption == 'cart_fixed' ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.fixed-amount-whole-cart') }}
                                        </option>

                                        <option value="buy_x_get_y" {{ $selectedOption == 'buy_x_get_y' ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.buy-x-get-y-free') }}
                                        </option>
                                    </select>

                                    <span class="control-error" v-if="errors.has('action_type')">@{{ errors.first('action_type') }}</span>
                                </div>

                                <div class="control-group" :class="[errors.has('discount_amount') ? 'has-error' : '']">
                                    <label for="discount_amount" class="required">{{ __('admin::app.promotions.cart-rules.discount-amount') }}</label>

                                    <input v-validate="'required'" class="control" id="discount_amount" name="discount_amount" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.discount-amount') }}&quot;" value="{{ old('discount_amount') ?: $cartRule->discount_amount }}"/>

                                    <span class="control-error" v-if="errors.has('discount_amount')">@{{ errors.first('discount_amount') }}</span>
                                </div>

                                <div class="control-group">
                                    <label for="discount_quantity">{{ __('admin::app.promotions.cart-rules.discount-quantity') }}</label>

                                    <input class="control" id="discount_quantity" name="discount_quantity" value="{{ old('discount_quantity') ?: $cartRule->discount_quantity }}"/>
                                </div>

                                <div class="control-group">
                                    <label for="discount_step">{{ __('admin::app.promotions.cart-rules.discount-step') }}</label>

                                    <input class="control" id="discount_step" name="discount_step" value="{{ old('discount_step') ?: $cartRule->discount_step }}"/>
                                </div>

                                <div class="control-group">
                                    <label for="apply_to_shipping">{{ __('admin::app.promotions.cart-rules.apply-to-shipping') }}</label>

                                    @php
                                        $selectedOption = old('apply_to_shipping') ?: $cartRule->apply_to_shipping;
                                    @endphp

                                    <select class="control" id="apply_to_shipping" name="apply_to_shipping" :disabled="action_type == 'cart_fixed'">
                                        <option value="0" {{ ! $selectedOption ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.no') }}
                                        </option>

                                        <option value="1" {{ $selectedOption ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.yes') }}
                                        </option>
                                    </select>
                                </div>

                                <div class="control-group">
                                    <label for="free_shipping">{{ __('admin::app.promotions.cart-rules.free-shipping') }}</label>

                                    @php
                                        $selectedOption = old('free_shipping') ?: $cartRule->free_shipping;
                                    @endphp

                                    <select class="control" id="free_shipping" name="free_shipping">
                                        <option value="0" {{ ! $selectedOption ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.no') }}
                                        </option>

                                        <option value="1" {{ $selectedOption ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.yes') }}
                                        </option>
                                    </select>
                                </div>

                                <div class="control-group">
                                    <label for="end_other_rules">{{ __('admin::app.promotions.cart-rules.end-other-rules') }}</label>

                                    @php
                                        $selectedOption = old('end_other_rules') ?: $cartRule->end_other_rules;
                                    @endphp

                                    <select class="control" id="end_other_rules" name="end_other_rules">
                                        <option value="0" {{ ! $selectedOption ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.no') }}
                                        </option>

                                        <option value="1" {{ $selectedOption ? 'selected' : '' }}>
                                            {{ __('admin::app.promotions.cart-rules.yes') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </accordian>

                        {!! view_render_event('bagisto.admin.promotions.cart-rules.create.after') !!}

                        <accordian title="{{ __('admin::app.promotions.cart-rules.coupon-codes') }}" :active="false" v-if="parseInt(use_auto_generation) && parseInt(coupon_type)">
                            <div slot="body">
                                <create-coupon-form></create-coupon-form>
                            </div>
                        </accordian>
                    </div>
                </div>
            </form>
        </div>
    </script>

    <script type="text/x-template" id="cart-rule-condition-item-template">
        <div class="cart-rule-conditions">
            <div class="attribute">
                <div class="control-group">
                    <select :name="['conditions[' + index + '][attribute]']" class="control" v-model="condition.attribute">
                        <option value="">{{ __('admin::app.promotions.cart-rules.choose-condition-to-add') }}</option>
                        <optgroup v-for='conditionAttribute in condition_attributes' :label="conditionAttribute.label">
                            <option v-for='childAttribute in conditionAttribute.children' :value="childAttribute.key">
                                @{{ childAttribute.label }}
                            </option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="operator">
                <div class="control-group" v-if="matchedAttribute">
                    <select :name="['conditions[' + index + '][operator]']" class="control" v-model="condition.operator">
                        <option v-for='operator in condition_operators[matchedAttribute.type]' :value="operator.operator">
                            @{{ operator.label }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="value">
                <div v-if="matchedAttribute">
                    <input type="hidden" :name="['conditions[' + index + '][attribute_type]']" v-model="matchedAttribute.type">

                    <div v-if="matchedAttribute.key == 'product|children::category_ids' || matchedAttribute.key == 'product|category_ids' || matchedAttribute.key == 'product|parent::category_ids'">
                        <tree-view value-field="id" id-field="id" :name-field="'conditions[' + index + '][value]'" input-type="checkbox" :items='matchedAttribute.options' :value='condition.value' :behavior="'no'" fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>
                    </div>

                    <div v-else>
                        <div class="control-group" v-if="matchedAttribute.type == 'text' || matchedAttribute.type == 'price' || matchedAttribute.type == 'decimal' || matchedAttribute.type == 'integer'">
                            <input class="control" :name="['conditions[' + index + '][value]']" v-model="condition.value"/>
                        </div>

                        <div class="control-group date" v-if="matchedAttribute.type == 'date'">
                            <date>
                                <input class="control" :name="['conditions[' + index + '][value]']" v-model="condition.value"/>
                            </date>
                        </div>

                        <div class="control-group date" v-if="matchedAttribute.type == 'datetime'">
                            <datetime>
                                <input class="control" :name="['conditions[' + index + '][value]']" v-model="condition.value"/>
                            </datetime>
                        </div>

                        <div class="control-group" v-if="matchedAttribute.type == 'boolean'">
                            <select :name="['conditions[' + index + '][value]']" class="control" v-model="condition.value">
                                <option value="1">{{ __('admin::app.promotions.cart-rules.yes') }}</option>
                                <option value="0">{{ __('admin::app.promotions.cart-rules.no') }}</option>
                            </select>
                        </div>

                        <div class="control-group" v-if="matchedAttribute.type == 'select' || matchedAttribute.type == 'radio'">
                            <select :name="['conditions[' + index + '][value]']" class="control" v-model="condition.value" v-if="matchedAttribute.key != 'cart|state'">
                                <option v-for='option in matchedAttribute.options' :value="option.id">
                                    @{{ option.admin_name }}
                                </option>
                            </select>

                            <select :name="['conditions[' + index + '][value]']" class="control" v-model="condition.value" v-else>
                                <optgroup v-for='option in matchedAttribute.options' :label="option.admin_name">
                                    <option v-for='state in option.states' :value="state.code">
                                        @{{ state.admin_name }}
                                    </option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="control-group" v-if="matchedAttribute.type == 'multiselect' || matchedAttribute.type == 'checkbox'">
                            <select :name="['conditions[' + index + '][value][]']" class="control" v-model="condition.value" multiple>
                                <option v-for='option in matchedAttribute.options' :value="option.id">
                                    @{{ option.admin_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions">
                <i class="icon trash-icon" @click="removeCondition"></i>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="create-coupon-form-template">
        <div class="">
            <form method="POST" data-vv-scope="create-coupon-form" @submit.prevent="generateCoupons('create-coupon-form')">
                <div class="control-group" :class="[errors.has('create-coupon-form.coupon_qty') ? 'has-error' : '']">
                    <label for="coupon_qty" class="required">{{ __('admin::app.promotions.cart-rules.coupon-qty') }}</label>

                    <input v-validate="'required|min_value:1'" class="control" id="coupon_qty" name="coupon_qty" v-model="coupon_format.coupon_qty" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.coupon-qty') }}&quot;"/>

                    <span class="control-error" v-if="errors.has('create-coupon-form.coupon_qty')">
                        @{{ errors.first('create-coupon-form.coupon_qty') }}
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('create-coupon-form.code_length') ? 'has-error' : '']">
                    <label for="code_length" class="required">{{ __('admin::app.promotions.cart-rules.code-length') }}</label>

                    <input v-validate="'required|min_value:10'" class="control" id="code_length" name="code_length" v-model="coupon_format.code_length" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.code-length') }}&quot;"/>

                    <span class="control-error" v-if="errors.has('create-coupon-form.code_length')">
                        @{{ errors.first('create-coupon-form.code_length') }}
                    </span>
                </div>

                <div class="control-group" :class="[errors.has('create-coupon-form.code_format') ? 'has-error' : '']">
                    <label for="code_format" class="required">{{ __('admin::app.promotions.cart-rules.code-format') }}</label>

                    <select class="control" id="code_format" name="code_format" v-model="coupon_format.code_format" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.promotions.cart-rules.code-format') }}&quot;">
                        <option value="alphanumeric">{{ __('admin::app.promotions.cart-rules.alphanumeric') }}</option>
                        <option value="alphabetical">{{ __('admin::app.promotions.cart-rules.alphabetical') }}</option>
                        <option value="numeric">{{ __('admin::app.promotions.cart-rules.numeric') }}</option>
                    </select>

                    <span class="control-error" v-if="errors.has('create-coupon-form.code_format')">
                        @{{ errors.first('create-coupon-form.code_format') }}
                    </span>
                </div>

                <div class="control-group">
                    <label for="code_prefix">{{ __('admin::app.promotions.cart-rules.code-prefix') }}</label>
                    <input class="control" id="code_prefix" name="code_prefix" v-model="coupon_format.code_prefix"/>
                </div>

                <div class="control-group">
                    <label for="code_suffix">{{ __('admin::app.promotions.cart-rules.code-suffix') }}</label>
                    <input class="control" id="code_suffix" name="code_suffix" v-model="coupon_format.code_suffix"/>
                </div>

                <div class="button-group">
                    <button class="btn btn-xl btn-primary">{{ __('admin::app.promotions.cart-rules.generate') }}</button>
                </div>
            </form>

            <div class="content">
                <div class="page-header">
                    <div class="page-action">
                        <div class="export-import" @click="showModal('downloadDataGrid')">
                            <i class="export-icon"></i>
                            <span >
                                {{ __('admin::app.export.export') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <modal id="downloadDataGrid" :is-open="this.$root.modalIds.downloadDataGrid">
                <h3 slot="header">{{ __('admin::app.export.download') }}</h3>

                <div slot="body">
                    <export-form></export-form>
                </div>
            </modal>

            <datagrid-plus src="{{ route('admin.cart_rules-coupons.index', $cartRule->id) }}"></datagrid-plus>
        </div>
    </script>

    @push('scripts')
        @include('admin::export.export', ['gridName' => app('Webkul\Admin\DataGrids\CartRuleCouponDataGrid')])
    @endpush

    <script>
        Vue.component('cart-rule', {
            template: '#cart-rule-template',

            inject: ['$validator'],

            data: function() {
                return {
                    coupon_type: {{ old('coupon_type') ?: $cartRule->coupon_type }},

                    use_auto_generation: {{ old('use_auto_generation') ?: $cartRule->use_auto_generation }},

                    condition_type: {{ old('condition_type') ?: $cartRule->condition_type }},

                    conditions: @json($cartRule->conditions ?: []),

                    action_type: "{{ old('action_type') ?: $cartRule->action_type }}"
                }
            },

            methods: {
                addCondition: function() {
                    this.conditions.push({
                        'attribute': '',
                        'operator': '==',
                        'value': '',
                    });
                },

                removeCondition: function(condition) {
                    let index = this.conditions.indexOf(condition)

                    this.conditions.splice(index, 1)
                },

                onSubmit: function(e) {
                    this.$root.onSubmit(e)
                },

                redirectBack: function(fallbackUrl) {
                    this.$root.redirectBack(fallbackUrl)
                }
            }
        });

        Vue.component('cart-rule-condition-item', {
            template: '#cart-rule-condition-item-template',

            props: ['index', 'condition'],

            data: function() {
                return {
                    condition_attributes: @json(app('\Webkul\CartRule\Repositories\CartRuleRepository')->getConditionAttributes()),

                    attribute_type_indexes: {
                        'cart': 0,

                        'cart_item': 1,

                        'product': 2
                    },

                    condition_operators: {
                        'price': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                        'decimal': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                        'integer': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                        'text': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.contain') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.does-not-contain') }}'
                            }],
                        'boolean': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }],
                        'date': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                        'datetime': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }, {
                                'operator': '>=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-greater-than') }}'
                            }, {
                                'operator': '<=',
                                'label': '{{ __('admin::app.promotions.cart-rules.equals-or-less-than') }}'
                            }, {
                                'operator': '>',
                                'label': '{{ __('admin::app.promotions.cart-rules.greater-than') }}'
                            }, {
                                'operator': '<',
                                'label': '{{ __('admin::app.promotions.cart-rules.less-than') }}'
                            }],
                        'select': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }],
                        'radio': [{
                                'operator': '==',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-equal-to') }}'
                            }, {
                                'operator': '!=',
                                'label': '{{ __('admin::app.promotions.cart-rules.is-not-equal-to') }}'
                            }],
                        'multiselect': [{
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.contains') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.does-not-contain') }}'
                            }],
                        'checkbox': [{
                                'operator': '{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.contains') }}'
                            }, {
                                'operator': '!{}',
                                'label': '{{ __('admin::app.promotions.cart-rules.does-not-contain') }}'
                            }]
                    }
                }
            },

            computed: {
                matchedAttribute: function () {
                    if (this.condition.attribute == '')
                        return;

                    let self = this;

                    let attributeIndex = this.attribute_type_indexes[this.condition.attribute.split("|")[0]];

                    matchedAttribute = this.condition_attributes[attributeIndex]['children'].filter(function (attribute) {
                        return attribute.key == self.condition.attribute;
                    });

                    if (matchedAttribute[0]['type'] == 'multiselect' || matchedAttribute[0]['type'] == 'checkbox') {
                        this.condition.operator = '{}';

                        this.condition.value = this.condition.value == '' && this.condition.value != undefined
                                ? []
                                : Array.isArray(this.condition.value) ? this.condition.value : [];
                    }

                    return matchedAttribute[0];
                }
            },

            methods: {
                removeCondition: function() {
                    this.$emit('onRemoveCondition', this.condition)
                }
            }
        });

        Vue.component('create-coupon-form', {
            template: '#create-coupon-form-template',

            inject: ['$validator'],

            data: function() {
                return {
                    coupon_format: {
                        coupon_qty: '',

                        code_length: 12,

                        code_format: 'alphanumeric',

                        code_prefix: '',

                        code_suffix: ''
                    }
                }
            },

            methods: {
                generateCoupons: function(formScope) {
                    let self = this;

                    this.$validator.validateAll(formScope).then(function (result) {
                        if (result) {
                            self.$http.post("{{ route('admin.cart_rules-coupons.store', $cartRule->id) }}", self.coupon_format)
                                .then(function(response) {
                                    window.flashMessages = [{
                                        'type': 'alert-success',
                                        'message': response.data.message
                                    }];

                                    self.$root.addFlashMessages();
                                })
                                .catch(function (error) {
                                    window.flashMessages = [{
                                        'type': 'alert-error',
                                        'message': error.response.data.message
                                    }];

                                    self.$root.addFlashMessages();
                                });
                        }
                    });
                },

                showModal(id) {
                    this.$root.$set(this.$root.modalIds, id, true);
                },
            }
        });
    </script>
@endpush
