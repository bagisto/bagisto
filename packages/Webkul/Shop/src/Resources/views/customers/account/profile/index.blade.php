<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile" />
    @endSection

    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-medium">
            @lang('shop::app.customers.account.profile.title')
        </h2>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.before') !!}

        <a
            href="{{ route('shop.customers.account.profile.edit') }}"
            class="secondary-button border-[#E9E9E9] px-5 py-3 font-normal"
        >
            @lang('shop::app.customers.account.profile.edit')
        </a>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.after') !!}
    </div>

    <!-- Profile Information -->
    <div class="mt-8 grid grid-cols-1 gap-y-6">
        {!! view_render_event('bagisto.shop.customers.account.profile.first_name.before') !!}

        <div class="grid w-full grid-cols-[2fr_3fr] border-b border-[#E9E9E9] px-8 py-3">
            <p class="text-sm font-medium">
                @lang('shop::app.customers.account.profile.first-name')
            </p>

            <p class="text-sm font-medium text-[#6E6E6E]">
                {{ $customer->first_name }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.first_name.after') !!}

        {!! view_render_event('bagisto.shop.customers.account.profile.last_name.before') !!}

        <div class="grid w-full grid-cols-[2fr_3fr] border-b border-[#E9E9E9] px-8 py-3">
            <p class="text-sm font-medium">
                @lang('shop::app.customers.account.profile.last-name')
            </p>

            <p class="text-sm font-medium text-[#6E6E6E]">
                {{ $customer->last_name }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.last_name.after') !!}

        {!! view_render_event('bagisto.shop.customers.account.profile.gender.before') !!}

        <div class="grid w-full grid-cols-[2fr_3fr] border-b border-[#E9E9E9] px-8 py-3">
            <p class="text-sm font-medium">
                @lang('shop::app.customers.account.profile.gender')
            </p>

            <p class="text-sm font-medium text-[#6E6E6E]">
                {{ $customer->gender ?? '-'}}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.gender.after') !!}

        {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.before') !!}

        <div class="grid w-full grid-cols-[2fr_3fr] border-b border-[#E9E9E9] px-8 py-3">
            <p class="text-sm font-medium">
                @lang('shop::app.customers.account.profile.dob')
            </p>

            <p class="text-sm font-medium text-[#6E6E6E]">
                {{ $customer->date_of_birth ?? '-' }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.after') !!}

        {!! view_render_event('bagisto.shop.customers.account.profile.email.before') !!}

        <div class="grid w-full grid-cols-[2fr_3fr] border-b border-[#E9E9E9] px-8 py-3">
            <p class="text-sm font-medium">
                @lang('shop::app.customers.account.profile.email')
            </p>

            <p class="text-sm font-medium text-[#6E6E6E]">
                {{ $customer->email }}
            </p>
        </div>
        
        {!! view_render_event('bagisto.shop.customers.account.profile.email.after') !!}

        {!! view_render_event('bagisto.shop.customers.account.profile.delete.before') !!}

        <!-- Profile Delete modal -->
        <x-shop::form
            action="{{ route('shop.customers.account.profile.destroy') }}"
        >
            <x-shop::modal>
                <x-slot:toggle>
                    <div
                        class="primary-button rounded-2xl px-11 py-3"
                    >
                        @lang('shop::app.customers.account.profile.delete-profile')
                    </div>
                </x-slot>

                <x-slot:header>
                    <h2 class="text-2xl font-medium max-sm:text-xl">
                        @lang('shop::app.customers.account.profile.enter-password')
                    </h2>
                </x-slot>

                <x-slot:content>
                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            class="px-6 py-5"
                            rules="required"
                            placeholder="Enter your password"
                        />

                        <x-shop::form.control-group.error
                            class="text-left"
                            control-name="password"
                        />
                    </x-shop::form.control-group>
                </x-slot>

                <!-- Modal Footer -->
                <x-slot:footer>
                    <button
                        type="submit"
                        class="primary-button flex rounded-2xl px-11 py-3 max-sm:px-6 max-sm:text-sm"
                    >
                        @lang('shop::app.customers.account.profile.delete')
                    </button>
                </x-slot>
            </x-shop::modal>
        </x-shop::form>

        {!! view_render_event('bagisto.shop.customers.account.profile.delete.after') !!}
    </div>
</x-shop::layouts.account>
