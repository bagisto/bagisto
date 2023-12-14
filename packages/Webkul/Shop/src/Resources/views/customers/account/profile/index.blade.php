<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile"></x-shop::breadcrumbs>
    @endSection

    <div class="flex justify-between items-center">
        <h2 class="text-[26px] font-medium">
            @lang('shop::app.customers.account.profile.title')
        </h2>

        <a
            href="{{ route('shop.customers.account.profile.edit') }}"
            class="secondary-button py-3 px-5 border-[#E9E9E9] font-normal"
        >
            @lang('shop::app.customers.account.profile.edit')
        </a>
    </div>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 gap-y-6 mt-8">
        <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.first-name')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->first_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.last-name')
            </p>

            <p class="text-[14px] font-medium text-[#6E6E6E]">
                {{ $customer->last_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.gender')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->gender ?? '-'}}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.dob')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->date_of_birth ?? '-' }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.email')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->email }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.delete.before') !!}

        <!-- Profile Delete modal -->
        <x-shop::form
            action="{{ route('shop.customers.account.profile.destroy') }}"
        >
            <x-shop::modal>
                <x-slot:toggle>
                    <div
                        class="primary-button py-3 px-11 rounded-[18px]"
                    >
                        @lang('shop::app.customers.account.profile.delete-profile')
                    </div>
                </x-slot:toggle>

                <x-slot:header>
                    <h2 class="text-[25px] font-medium max-sm:text-[22px]">
                        @lang('shop::app.customers.account.profile.enter-password')
                    </h2>
                </x-slot:header>

                <x-slot:content>
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            class="py-5 px-6"
                            rules="required"
                            placeholder="Enter your password"
                        />

                        <x-shop::form.control-group.error
                            class=" text-left"
                            control-name="password"
                        >
                        </x-shop::form.control-group.error>
                    </x-shop::form.control-group>
                </x-slot:content>

                <!-- Modal Footer -->
                <x-slot:footer>
                    <button
                        type="submit"
                        class="primary-button flex py-3 px-11 rounded-[18px] max-sm:text-[14px] max-sm:px-6"
                    >
                        @lang('shop::app.customers.account.profile.delete')
                    </button>
                </x-slot:footer>
            </x-shop::modal>
        </x-shop::form>

        {!! view_render_event('bagisto.shop.customers.account.profile.delete.after') !!}

    </div>
</x-shop::layouts.account>
