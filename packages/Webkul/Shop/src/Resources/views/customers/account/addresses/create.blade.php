<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.add-address')
    </x-slot>

    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="addresses.create"></x-shop::breadcrumbs>
    @endSection

    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.addresses.add-address')
            </h2>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.addresses.create.before') !!}

    {{-- Create Address Form --}}
    <x-shop::form
        :action="route('shop.customers.account.addresses.store')"
        class="rounded mt-[30px]"
    >

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.before') !!}

        {{--Company Name --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.addresses.company-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="company_name"
                :value="old('company_name')"
                :label="trans('shop::app.customers.account.addresses.company-name')"
                :placeholder="trans('shop::app.customers.account.addresses.company-name')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="company_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.company_name.after') !!}

        {{-- First Name --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.first-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="first_name"
                :value="old('first_name')"
                rules="required"
                :label="trans('shop::app.customers.account.addresses.first-name')"
                :placeholder="trans('shop::app.customers.account.addresses.first-name')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="first_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.first_name.after') !!}

        {{-- Last Name  --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.last-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="last_name"
                :value="old('last_name')"
                rules="required"
                :label="trans('shop::app.customers.account.addresses.last-name')"
                :placeholder="trans('shop::app.customers.account.addresses.last-name')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="last_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.last_name.after') !!}

        {{-- Vat Id --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.addresses.vat-id')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="vat_id"
                :value="old('vat_id')"
                :label="trans('shop::app.customers.account.addresses.vat-id')"
                :placeholder="trans('shop::app.customers.account.addresses.vat-id')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="vat_id"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.vat_id.after') !!}

        {{-- Street Address --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.street-address')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="address1[]"
                :value="old('address1[]')"
                rules="required"
                :label="trans('shop::app.customers.account.addresses.street-address')"
                :placeholder="trans('shop::app.customers.account.addresses.street-address')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="address1[]"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.street_address.after') !!}

        @if (
            core()->getConfigData('customer.address.information.street_lines')
            && core()->getConfigData('customer.address.information.street_lines') > 1
        )
            @for ($i = 2; $i <= core()->getConfigData('customer.address.information.street_lines'); $i++)
                <x-shop::form.control-group.control
                    type="text"
                    name="address{{ $i }}[]"
                    :value="old('address{{ $i }}[]')"
                    :label="trans('shop::app.customers.account.addresses.street-address')"
                    :placeholder="trans('shop::app.customers.account.addresses.street-address')"
                >
                </x-shop::form.control-group.control>
            @endfor
        @endif

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.street_address.after') !!}

        {{-- Country --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.country')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="select"
                name="country"
                :value="old('country')"
                class="mb-4"
                rules="required"
                aria-label="trans('shop::app.customers.account.addresses.country')"
                :label="trans('shop::app.customers.account.addresses.country')"
            >
                <option value="">@lang('Select Country')</option>

                @foreach (core()->countries() as $country)
                    <option 
                        {{ $country->code === config('app.default_country') ? 'selected' : '' }}  
                        value="{{ $country->code }}"
                    >
                        {{ $country->name }}
                    </option>
                @endforeach
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="country"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.country.after') !!}

        {{-- State --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.state')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="state"
                :value="old('state')"
                rules="required"
                :label="trans('shop::app.customers.account.addresses.state')"
                :placeholder="trans('shop::app.customers.account.addresses.state')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="state"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.state.after') !!}

        {{-- City --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.city')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="city"
                :value="old('city')"
                rules="required"
                :label="trans('shop::app.customers.account.addresses.city')"
                :placeholder="trans('shop::app.customers.account.addresses.city')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="city"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.city.after') !!}

        {{-- Post Code --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.post-code')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="postcode"
                :value="old('postcode')"
                rules="required|integer"
                :label="trans('shop::app.customers.account.addresses.post-code')"
                :placeholder="trans('shop::app.customers.account.addresses.post-code')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="postcode"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.postcode.after') !!}

        {{-- Contact --}}
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.phone')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="phone"
                :value="old('phone')"
                rules="required|integer"
                :label="trans('shop::app.customers.account.addresses.phone')"
                :placeholder="trans('shop::app.customers.account.addresses.phone')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="phone"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.after') !!}

        {{-- Set As Default --}}
        <div class="flex gap-x-[15px] mb-4 select-none">
            <input
                type="checkbox"
                name="default_address"
                value="1"
                id="default_address"
                class="hidden peer cursor-pointer"
            >

            <label
                class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                for="default_address"
            >
            </label>

            <label 
                class="block text-[16px] cursor-pointer"
                for="default_address"
            >
                @lang('shop::app.customers.account.addresses.set-as-default')
            </label>
        </div>

        <button
            type="submit"
            class="primary-button m-0 block text-base w-max py-[11px] px-[43px] rounded-[18px] text-center"
        >
            @lang('shop::app.customers.account.addresses.save')
        </button>

        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.after') !!}

    </x-shop::form>

    {!! view_render_event('bagisto.shop.customers.account.address.create.after') !!}

</x-shop::layouts.account>
