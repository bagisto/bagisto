<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.edit')
        @lang('shop::app.customers.account.addresses.title') 
    </x-slot>

    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="addresses.edit" :entity="$address"></x-shop::breadcrumbs>
    @endSection

    <h2 class="text-[26px] font-medium">
        @lang('shop::app.customers.account.addresses.edit')
        @lang('shop::app.customers.account.addresses.title')
    </h2>

    {{-- Edit Address Form --}}
    <x-shop::form
        method="PUT"
        :action="route('shop.customers.account.addresses.update',  $address->id)"
        class="rounded mt-[30px]"
    >
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.addresses.company-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="company_name"
                :value="old('company_name') ?? $address->company_name"
                :label="trans('shop::app.customers.account.addresses.company-name')"
                :placeholder="trans('shop::app.customers.account.addresses.company-name')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="company_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.first-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="first_name"
                :value="old('first_name') ?? $address->first_name"
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

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.last-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="last_name"
                :value="old('last_name') ?? $address->last_name"
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

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.addresses.vat-id')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="vat_id"
                :value="old('vat_id') ?? $address->vat_id"
                :label="trans('shop::app.customers.account.addresses.vat-id')"
                :placeholder="trans('shop::app.customers.account.addresses.vat-id')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="vat_id"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.street-address')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="address1[]"
                :value="old('address1') ?? $address->address1"
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

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.country')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="select"
                name="country"
                :value="old('gender') ?? $address->country"
                class="mb-4"
                rules="required"
                :label="trans('shop::app.customers.account.addresses.country')"
            >
                <option value="">
                    @lang('shop::app.customers.account.addresses.select-country')
                </option>

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

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.state')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="state"
                :value="old('state') ?? $address->state"
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

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.city')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="city"
                :value="old('city') ?? $address->city"
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

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.post-code')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="postcode"
                :value="old('postal-code') ?? $address->postcode"
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

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.addresses.phone')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="phone"
                :value="old('phone') ?? $address->phone"
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

        <button
            type="submit"
            class="bs-primary-button m-0 block text-base w-max py-[11px] px-[43px] rounded-[18px] text-center"
        >
            @lang('shop::app.customers.account.addresses.save')
        </button>
    </x-shop::form>
</x-shop::layouts.account>
