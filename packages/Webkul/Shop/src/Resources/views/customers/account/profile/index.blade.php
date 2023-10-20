<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.profile.title')
    </x-slot>

    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile"></x-shop::breadcrumbs>
    @endSection

    <div class="flex justify-between items-center">
        <h2 class="text-[26px] font-medium">
            @lang('shop::app.customers.account.profile.title')
        </h2>

        <a
            href="{{ route('shop.customers.account.profile.edit') }}"
            class="secondary-button py-[12px] px-[20px] border-[#E9E9E9] font-normal"
        >
            @lang('shop::app.customers.account.profile.edit')
        </a>
    </div>

    {{-- Profile Information --}}
    <div class="grid grid-cols-1 gap-y-[25px] mt-[30px]">
        <div class="grid grid-cols-[2fr_3fr] w-full px-[30px] py-[12px] border-b-[1px] border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.first-name')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->first_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-[30px] py-[12px] border-b-[1px] border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.last-name')
            </p>

            <p class="text-[14px] font-medium text-[#6E6E6E]">
                {{ $customer->last_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-[30px] py-[12px] border-b-[1px] border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.gender')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->gender ?? '-'}}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-[30px] py-[12px] border-b-[1px] border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.dob')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->date_of_birth ?? '-' }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] w-full px-[30px] py-[12px] border-b-[1px] border-[#E9E9E9]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.email')
            </p>

            <p class="text-[14px] text-[#6E6E6E] font-medium">
                {{ $customer->email }}
            </p>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.delete.before') !!}

        {{-- Profile Delete modal --}}
        <x-shop::modal>
            <x-slot:toggle>
                <div
                    class="primary-button py-[11px] px-[43px] rounded-[18px]"
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
                <x-shop::form
                    action="{{ route('shop.customers.account.profile.destroy') }}"
                >
                    <x-shop::form.control-group>
                        <div class="p-[30px] bg-white">
                            <x-shop::form.control-group.control
                                type="password"
                                name="password"
                                class="py-[20px] px-[25px]"
                                rules="required"
                                placeholder="Enter your password"
                            />

                            <x-shop::form.control-group.error
                                class=" text-left"
                                control-name="password"
                            >
                            </x-shop::form.control-group.error>
                        </div>
                    </x-shop::form.control-group>

                    <div class="p-[30px] bg-white mt-[20px]">
                        <button
                            type="submit"
                            class="primary-button flex py-[11px] px-[43px] rounded-[18px] max-sm:text-[14px] max-sm:px-[25px]"
                        >
                            @lang('shop::app.customers.account.profile.delete')
                        </button>
                    </div>
                </x-shop::form>
            </x-slot:content>
        </x-shop::modal>

        {!! view_render_event('bagisto.shop.customers.account.profile.delete.after') !!}

    </div>
</x-shop::layouts.account>
