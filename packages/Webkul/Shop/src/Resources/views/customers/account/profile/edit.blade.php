<x-shop::layouts.account>
    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile.edit"></x-shop::breadcrumbs>
    @endSection

    <h2 class="text-[26px] font-medium">
        @lang('shop::app.customers.account.profile.edit-profile')
    </h2>

    {{-- Profile Edit Form --}}
    <x-shop::form
        :action="route('shop.customers.account.profile.store')"
        class="rounded mt-[30px]"
        enctype="multipart/form-data"
    >
        <x-shop::form.control-group class="mb-4 mt-[15px]">
            <x-shop::form.control-group.control
                type="image"
                name="image[]"
                rules="required"
                :label="trans('Image')"
                :is-multiple="false"
                accepted-types="image/*"
                :src="$customer->image_url"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="image[]"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.first-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="first_name"
                :value="old('first_name') ?? $customer->first_name"
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
                @lang('shop::app.customers.account.profile.last-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="last_name"
                :value="old('last_name') ?? $customer->last_name"
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
                @lang('shop::app.customers.account.profile.email')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="email"
                :value="old('email') ?? $customer->email"
                rules="required|email"
                label="Email"
                placeholder="Email"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="email"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.phone')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="phone"
                :value="old('phone') ?? $customer->phone"
                rules="required|phone"
                label="Phone"
                placeholder="Phone"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="phone"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.gender')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="select"
                name="gender"
                :value="old('gender') ?? $customer->gender"
                class="mb-4"
                rules="required"
                label="Gender"
            >
                <option value="">@lang('Select Gender')</option>
                <option value="Other">@lang('shop::app.customer.account.profile.other')</option>
                <option value="Male">@lang('shop::app.customer.account.profile.male')</option>
                <option value="Female">@lang('shop::app.customer.account.profile.female')</option>
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="gender"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.current-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="current_password"
                value=""
                label="Current Password"
                placeholder="Current Password"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="current_password"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.new-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="new_password"
                value=""
                label="New Password"
                placeholder="New Password"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="new_password"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.confirm-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="new_password_confirmation"
                value=""
                rules="confirmed:@new_password"
                label="Confirm Password"
                placeholder="Confirm Password"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="new_password_confirmation"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.control
                type="checkbox"
                name="subscribed_to_news_letter"
                :checked="$customer->subscribed_to_news_letter"
            >
                <span class="select-none text-[16] text-[#7d7d7d] max-sm:text-[12px]">
                    @lang('shop::app.customer.signup-form.subscribe-to-newsletter')
                </span>
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="subscribed_to_news_letter"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        <button
            type="submit"
            class="bs-primary-button m-0 block text-base w-max py-[11px] px-[43px] rounded-[18px] text-center"
        >
            @lang('shop::app.customers.account.save')
        </button>
    </x-shop::form>
</x-shop::layouts.account>
