@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.index.title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
        <span class="account-heading">{{ __('shop::app.customer.account.profile.index.title') }}</span>
        <span></span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}

    <form
        method="POST"
        @submit.prevent="onSubmit"
        action="{{ route('customer.profile.store') }}">

        <div class="account-table-content">
            @csrf

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}

            <div :class="`row ${errors.has('first_name') ? 'has-error' : ''}`">
                <label class="col-12 mandatory">
                    {{ __('shop::app.customer.account.profile.fname') }}
                </label>

                <div class="col-12">
                    <input value="{{ $customer->first_name }}" name="first_name" type="text" v-validate="'required'" />
                    <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.first_name.after', ['customer' => $customer]) !!}

            <div class="row">
                <label class="col-12">
                    {{ __('shop::app.customer.account.profile.lname') }}
                </label>

                <div class="col-12">
                    <input value="{{ $customer->last_name }}" name="last_name" type="text" />
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.last_name.after', ['customer' => $customer]) !!}

            <div :class="`row ${errors.has('gender') ? 'has-error' : ''}`">
                <label class="col-12 mandatory">
                    {{ __('shop::app.customer.account.profile.gender') }}
                </label>

                <div class="col-12">
                    <select
                        name="gender"
                        v-validate="'required'"
                        class="control styled-select"
                        data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">

                        <option value=""  @if ($customer->gender == "") selected @endif></option>
                        <option
                            value="Other"
                            @if ($customer->gender == "Other")
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.other') }}
                        </option>

                        <option
                            value="Male"
                            @if ($customer->gender == "Male")
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.male') }}
                        </option>

                        <option
                            value="Female"
                            @if ($customer->gender == "Female")
                                selected="selected"
                            @endif>
                            {{ __('velocity::app.shop.gender.female') }}
                        </option>
                    </select>

                    <div class="select-icon-container">
                        <span class="select-icon rango-arrow-down"></span>
                    </div>

                    <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.gender.after', ['customer' => $customer]) !!}

            <div :class="`row ${errors.has('date_of_birth') ? 'has-error' : ''}`">
                <label class="col-12">
                    {{ __('shop::app.customer.account.profile.dob') }}
                </label>

                <div class="col-12">
                    <input
                        type="date"
                        name="date_of_birth"
                        placeholder="dd/mm/yyyy"
                        value="{{ old('date_of_birth') ?? $customer->date_of_birth }}"
                        v-validate="" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.dob') }}&quot;" />

                        <span class="control-error" v-if="errors.has('date_of_birth')">
                            @{{ errors.first('date_of_birth') }}
                        </span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.date_of_birth.after', ['customer' => $customer]) !!}

            <div class="row">
                <label class="col-12 mandatory">
                    {{ __('shop::app.customer.account.profile.email') }}
                </label>

                <div class="col-12">
                    <input value="{{ $customer->email }}" name="email" type="text" v-validate="'required'" />
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.email.after', ['customer' => $customer]) !!}

            <div class="row">
                <label class="col-12">
                    {{ __('shop::app.customer.account.profile.phone') }}
                </label>

                <div class="col-12">
                    <input value="{{ old('phone') ?? $customer->phone }}" name="phone" type="text"/>
                    <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.phone.after', ['customer' => $customer]) !!}

            <div class="row">
                <label class="col-12">
                    {{ __('velocity::app.shop.general.enter-current-password') }}
                </label>

                <div :class="`col-12 ${errors.has('oldpassword') ? 'has-error' : ''}`">
                    <input value="" name="oldpassword" type="password" />
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.oldpassword.after', ['customer' => $customer]) !!}

            <div class="row">
                <label class="col-12">
                    {{ __('velocity::app.shop.general.new-password') }}
                </label>

                <div :class="`col-12 ${errors.has('password') ? 'has-error' : ''}`">
                    <input
                        value=""
                        name="password"
                        ref="password"
                        type="password"
                        v-validate="'min:6|max:18'" />

                    <span class="control-error" v-if="errors.has('password')">
                        @{{ errors.first('password') }}
                    </span>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit.password.after', ['customer' => $customer]) !!}

            <div class="row">
                <label class="col-12">
                    {{ __('velocity::app.shop.general.confirm-new-password') }}
                </label>

                <div :class="`col-12 ${errors.has('password_confirmation') ? 'has-error' : ''}`">
                    <input value="" name="password_confirmation" type="password"
                    v-validate="'min:6|confirmed:password'" data-vv-as="confirm password" />

                    <span class="control-error" v-if="errors.has('password_confirmation')">
                        @{{ errors.first('password_confirmation') }}
                    </span>
                </div>
            </div>

            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div class="control-group">
                    <input type="checkbox" id="checkbox2" name="subscribed_to_news_letter" @if (isset($customer->subscription)) value="{{ $customer->subscription->is_subscribed }}" {{ $customer->subscription->is_subscribed ? 'checked' : ''}} @endif  style="width: auto;">
                    <span>{{ __('shop::app.customer.signup-form.subscribe-to-newsletter') }}</span>
                </div>
            @endif

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

            <button
                type="submit"
                class="theme-btn mb20">
                {{ __('velocity::app.shop.general.update') }}
            </button>
        </div>
    </form>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}
@endsection