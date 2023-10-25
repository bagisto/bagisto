<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.profile.edit-profile')
    </x-slot>

    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile.edit"></x-shop::breadcrumbs>
    @endSection

    <h2 class="text-[26px] font-medium">
        @lang('shop::app.customers.account.profile.edit-profile')
    </h2>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}

    {{-- Profile Edit Form --}}
    <x-shop::form
        :action="route('shop.customers.account.profile.store')"
        class="rounded mt-[30px]"
        enctype="multipart/form-data"
    >

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}

        <x-shop::form.control-group class="mt-[15px]">
            <x-shop::form.control-group.control
                type="image"
                name="image[]"
                class="!p-0 rounded-[12px] text-gray-700 mb-0"
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

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.image.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.first-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="first_name"
                :value="old('first_name') ?? $customer->first_name"
                rules="required"
                :label="trans('shop::app.customers.account.profile.first-name')"
                :placeholder="trans('shop::app.customers.account.profile.first-name')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="first_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.first_name.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.last-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="last_name"
                :value="old('last_name') ?? $customer->last_name"
                rules="required"
                :label="trans('shop::app.customers.account.profile.last-name')"
                :placeholder="trans('shop::app.customers.account.profile.last-name')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="last_name"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.last_name.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.email')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="email"
                :value="old('email') ?? $customer->email"
                rules="required|email"
                :label="trans('shop::app.customers.account.profile.email')"
                :placeholder="trans('shop::app.customers.account.profile.email')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="email"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.email.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.phone')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="phone"
                :value="old('phone') ?? $customer->phone"
                rules="required|phone"
                :label="trans('shop::app.customers.account.profile.phone')"
                :placeholder="trans('shop::app.customers.account.profile.phone')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="phone"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.phone.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.gender')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="select"
                name="gender"
                :value="old('gender') ?? $customer->gender"
                class="mb-3"
                rules="required"
                aria-label="Select Gender"
                :label="trans('shop::app.customers.account.profile.gender')"
            >
                <option value="Other">@lang('shop::app.customers.account.profile.other')</option>
                <option value="Male">@lang('shop::app.customers.account.profile.male')</option>
                <option value="Female">@lang('shop::app.customers.account.profile.female')</option>
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="gender"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.gender.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.dob')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="date"
                name="date_of_birth"
                :value="old('date_of_birth') ?? $customer->date_of_birth"
                :label="trans('shop::app.customers.account.profile.dob')"
                :placeholder="trans('shop::app.customers.account.profile.dob')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="date_of_birth"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.date_of_birth.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.current-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="current_password"
                value=""
                :label="trans('shop::app.customers.account.profile.current-password')"
                :placeholder="trans('shop::app.customers.account.profile.current-password')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="current_password"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.old_password.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.new-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="new_password"
                value=""
                :label="trans('shop::app.customers.account.profile.new-password')"
                :placeholder="trans('shop::app.customers.account.profile.new-password')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="new_password"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password.after') !!}

        <x-shop::form.control-group class="mb-4">
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.confirm-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="new_password_confirmation"
                value=""
                rules="confirmed:@new_password"
                :label="trans('shop::app.customers.account.profile.confirm-password')"
                :placeholder="trans('shop::app.customers.account.profile.confirm-password')"
            >
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error
                control-name="new_password_confirmation"
            >
            </x-shop::form.control-group.error>
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password_confirmation.after') !!}

        <div class="select-none items-center flex gap-[6px] mb-4">
            <input
                type="checkbox"
                name="subscribed_to_news_letter"
                id="is-subscribed"
                class="hidden peer"
            />

            <label
                class="icon-uncheck text-[24px] text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                for="is-subscribed"
            ></label>

            <label
                class="text-[16] text-[#6E6E6E] max-sm:text-[12px] pl-0 select-none cursor-pointer"
                for="is-subscribed"
            >
                @lang('shop::app.customers.account.profile.subscribe-to-newsletter')
            </label>
        </div>

        <button
            type="submit"
            class="primary-button block m-0 w-max py-[11px] px-[43px] rounded-[18px] text-base text-center"
        >
            @lang('shop::app.customers.account.profile.save')
        </button>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

    </x-shop::form>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}
    
</x-shop::layouts.account>
