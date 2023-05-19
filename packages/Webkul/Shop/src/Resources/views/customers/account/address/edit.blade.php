<x-shop::layouts.account>
    <h2 class="text-[26px] font-medium">Edit Address</h2>
{{-- @dd($address); --}}
    <x-shop::form
        :action="route('shop.customer.addresses.update',  $address->id)"
        class="rounded mt-[30px]"
    >
        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.comapny-name')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="company_name"
                :value="old('company_name') ?? $address->company_name"
                label="Company name"
                placeholder="Company Name"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="company_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.first-name')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="first_name"
                :value="old('first_name') ?? $address->first_name"
                rules="required"
                label="First Name"
                placeholder="First Name"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="first_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.last-name')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="last_name"
                :value="old('last_name') ?? $address->last_name"
                rules="required"
                label="Last Name"
                placeholder="Last Name"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="last_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.vat-id')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="vat_id"
                :value="old('vat_id') ?? $address->vat_id"
                label="Vat Id"
                placeholder="Vat Id"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="vat_id"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.street-address')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="address1"
                :value="old('address1') ?? $address->address1"
                rules="required"
                label="Street Address"
                placeholder="Street Address"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="address1"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.country')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="select"
                name="country"
                :value="old('gender') ?? $address->country"
                class="mb-4"
                rules="required"
                label="Country"
            >
                <option value="">@lang('Select Country')</option>

                @foreach (core()->countries() as $country)
                    <option {{ $country->code === $defaultCountry ? 'selected' : '' }}  value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="country"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.state')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="state"
                :value="old('state') ?? $address->state"
                rules="required"
                label="State"
                placeholder="State"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="state"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.city')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="city"
                :value="old('city') ?? $address->city"
                rules="required"
                label="City"
                placeholder="City"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="city"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                {{trans('shop::app.customers.account.address.post-code')}}
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="postcode"
                :value="old('postal-code') ?? $address->postcode"
                rules="required|integer"
                label="Confirm Password"
                placeholder="Confirm Password"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="postcode"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <button
            type="submit"
            class="m-0 block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
        >
            Save
        </button>

    </x-shop::form>
</x-shop::layouts.account>
