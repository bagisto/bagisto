@extends('shop::customers.account.index')

@section('page_title')
    {{ __('shop::app.customer.account.address.create.page-title') }}
@endsection

@section('page-detail-wrapper')
    <div class="account-head mb-15">
        <span class="back-icon"><a href="{{ route('customer.account.index') }}"><i class="icon icon-menu-back"></i></a></span>
        <span class="account-heading">{{ __('shop::app.customer.account.address.create.title') }}</span>
        <span></span>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.address.create.before') !!}

        <form method="post" action="{{ route('customer.address.create') }}" @submit.prevent="onSubmit">

            <div class="account-table-content">
                @csrf

                {!! view_render_event('bagisto.shop.customers.account.address.create_form_controls.before') !!}

                <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                    <label for="company_name">{{ __('shop::app.customer.account.address.create.company_name') }}</label>
                    <input type="text" class="control" name="company_name" value="{{ old('company_name') }}" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.company_name') }}&quot;">
                    <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <label for="first_name" class="mandatory">{{ __('shop::app.customer.account.address.create.first_name') }}</label>
                    <input type="text" class="control" name="first_name" value="{{ old('first_name') }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.first_name') }}&quot;">
                    <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <label for="last_name" class="mandatory">{{ __('shop::app.customer.account.address.create.last_name') }}</label>
                    <input type="text" class="control" name="last_name" value="{{ old('last_name') }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.last_name') }}&quot;">
                    <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('vat_id') ? 'has-error' : '']">
                    <label for="vat_id">{{ __('shop::app.customer.account.address.create.vat_id') }}</label>
                    <input type="text" class="control" name="vat_id" value="{{ old('vat_id') }}" v-validate="" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.vat_id') }}&quot;">
                    <span class="control-error" v-if="errors.has('vat_id')">@{{ errors.first('vat_id') }}</span>
                </div>

                <?php $addresses = explode(PHP_EOL, (old('address1') ?? '')); ?>

                <div class="control-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                    <label for="address_0" class="mandatory">{{ __('shop::app.customer.account.address.create.street-address') }}</label>
                    <input type="text" class="control" name="address1[]" id="address_0" value="{{ $addresses[0] ?: '' }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.street-address') }}&quot;">
                    <span class="control-error" v-if="errors.has('address1[]')">@{{ errors.first('address1[]') }}</span>
                </div>

                @if (core()->getConfigData('customer.settings.address.street_lines') && core()->getConfigData('customer.settings.address.street_lines') > 1)
                    <div class="control-group" style="margin-top: -25px;">
                        @for ($i = 1; $i < core()->getConfigData('customer.settings.address.street_lines'); $i++)
                            <input type="text" class="control" name="address1[{{ $i }}]" id="address_{{ $i }}" value="{{ $addresses[$i] ?? '' }}">
                        @endfor
                    </div>
                @endif

                @include ('shop::customers.account.address.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')])

                <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                    <label for="city" class="mandatory">{{ __('shop::app.customer.account.address.create.city') }}</label>
                    <input type="text" class="control" name="city" value="{{ old('city') }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.city') }}&quot;">
                    <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                    <label for="postcode" class="mandatory">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
                    <input type="text" class="control" name="postcode" value="{{ old('postcode') }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.postcode') }}&quot;">
                    <span class="control-error" v-if="errors.has('postcode')">@{{ errors.first('postcode') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                    <label for="phone" class="mandatory">{{ __('shop::app.customer.account.address.create.phone') }}</label>
                    <input type="text" class="control" name="phone" value="{{ old('phone') }}" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;">
                    <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.address.create_form_controls.after') !!}

                <div class="button-group">
                    <button class="theme-btn" type="submit">
                        {{ __('shop::app.customer.account.address.create.submit') }}
                    </button>
                </div>
            </div>
        </form>

    {!! view_render_event('bagisto.shop.customers.account.address.create.after') !!}
@endsection