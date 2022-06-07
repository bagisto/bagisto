@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.profile.edit-profile.page-title') }}
@endsection

@section('account-content')
    <div class="account-layout">
        <div class="account-head mb-10">
            <span class="back-icon"><a href="{{ route('customer.profile.index') }}"><i class="icon icon-menu-back"></i></a></span>

            <span class="account-heading">{{ __('shop::app.customer.account.profile.edit-profile.title') }}</span>

            <span></span>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}

        <form method="post" action="{{ route('customer.profile.store') }}" @submit.prevent="onSubmit">
            <div class="edit-form">
                @csrf

                {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}

                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <label for="first_name" class="required">{{ __('shop::app.customer.account.profile.fname') }}</label>

                    <input type="text" class="control" name="first_name" value="{{ old('first_name') ?? $customer->first_name }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.fname') }}&quot;">

                    <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.first_name.after') !!}

                <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <label for="last_name" class="required">{{ __('shop::app.customer.account.profile.lname') }}</label>

                    <input type="text" class="control" name="last_name" value="{{ old('last_name') ?? $customer->last_name }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.lname') }}&quot;">

                    <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.last_name.after') !!}

                <div class="control-group" :class="[errors.has('gender') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('shop::app.customer.account.profile.gender') }}</label>

                    <select name="gender" class="control" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.gender') }}&quot;">
                        <option value=""  @if ($customer->gender == "") selected @endif>{{ __('admin::app.customers.customers.select-gender') }}</option>
                        <option value="Other"  @if ($customer->gender == "Other") selected @endif>{{ __('shop::app.customer.account.profile.other') }}</option>
                        <option value="Male"  @if ($customer->gender == "Male") selected @endif>{{ __('shop::app.customer.account.profile.male') }}</option>
                        <option value="Female" @if ($customer->gender == "Female") selected @endif>{{ __('shop::app.customer.account.profile.female') }}</option>
                    </select>

                    <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.gender.after') !!}

                <div class="control-group date"  :class="[errors.has('date_of_birth') ? 'has-error' : '']">
                    <label for="date_of_birth">{{ __('shop::app.customer.account.profile.dob') }}</label>

                    <date>
                        <input type="date" class="control" name="date_of_birth" value="{{ old('date_of_birth') ?? $customer->date_of_birth }}" v-validate="" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.dob') }}&quot;">
                    </date>

                    <span class="control-error" v-if="errors.has('date_of_birth')">@{{ errors.first('date_of_birth') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.date_of_birth.after') !!}

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('shop::app.customer.account.profile.email') }}</label>

                    <input type="email" class="control" name="email" value="{{ old('email') ?? $customer->email }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.email') }}&quot;">

                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.email.after') !!}

                <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                    <label for="phone">{{ __('shop::app.customer.account.profile.phone') }}</label>

                    <input type="text" class="control" name="phone" value="{{ old('phone') ?? $customer->phone }}" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.phone') }}&quot;">

                    <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.phone.after') !!}

                <div class="control-group" :class="[errors.has('oldpassword') ? 'has-error' : '']">
                    <label for="password">{{ __('shop::app.customer.account.profile.opassword') }}</label>

                    <input type="password" class="control" name="oldpassword" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.opassword') }}&quot;" v-validate="'min:6'">

                    <span class="control-error" v-if="errors.has('oldpassword')">@{{ errors.first('oldpassword') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.oldpassword.after') !!}

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password">{{ __('shop::app.customer.account.profile.password') }}</label>

                    <input type="password" id="password" class="control" name="password" ref="password" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.password') }}&quot;" v-validate="'min:6'">

                    <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit.password.after') !!}

                <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                    <label for="password">{{ __('shop::app.customer.account.profile.cpassword') }}</label>

                    <input type="password" id="password_confirmation" class="control" name="password_confirmation" data-vv-as="&quot;{{ __('shop::app.customer.account.profile.cpassword') }}&quot;" v-validate="'min:6|confirmed:password'">

                    <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                </div>

                @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                    <div class="control-group">
                        <input type="checkbox" id="checkbox2" name="subscribed_to_news_letter"@if (isset($customer->subscription)) value="{{ $customer->subscription->is_subscribed }}" {{ $customer->subscription->is_subscribed ? 'checked' : ''}} @endif>

                        <span>{{ __('shop::app.customer.signup-form.subscribe-to-newsletter') }}</span>
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

                <div class="button-group">
                    <input class="btn btn-primary btn-lg" type="submit" value="{{ __('shop::app.customer.account.profile.submit') }}">
                </div>
            </div>
        </form>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}
    </div>
@endsection
