<x-shop::layouts.account>
    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile"></x-shop::breadcrumbs>
    @endSection

    <div class="flex justify-between items-center">
        <h2 class="text-[26px] font-medium">
            @lang('shop::app.customers.account.title')
        </h2>

        <a
            href="{{ route('shop.customers.account.profile.edit') }}"
            class="bs-secondary-button font-normal border-[#E9E9E9] py-[12px] px-[20px]"
        >
            @lang('shop::app.customers.account.profile.edit')
        </a>
    </div>

    {{-- Profile Information --}}
    <div class="grid grid-cols-1 gap-y-[25px] mt-[30px]">
        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.first-name')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->first_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.last-name')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->last_name }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.gender')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->gender }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.dob')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->date_of_birth }}
            </p>
        </div>

        <div class="grid grid-cols-[2fr_3fr] border-b-[1px] border-[#E9E9E9] w-full px-[30px] py-[12px]">
            <p class="text-[14px] font-medium">
                @lang('shop::app.customers.account.profile.email')
            </p>

            <p class="text-[14px] font-medium text-[#7D7D7D]">
                {{ $customer->email }}
            </p>
        </div>

        {{-- Profile Delete modal --}}
        <x-shop::modal>
            <x-slot:toggle>
                <div
                    class="bs-primary-button m-0 ml-[0px] block mx-auto text-base w-max py-[11px] px-[43px] rounded-[18px] text-center"
                >
                    @lang('shop::app.customers.account.profile.delete-profile')
                </div>
            </x-slot:toggle>

            <x-slot:header>
                <h2 class="text-[25px] font-medium max-sm:text-[22px]">
                    @lang('shop::app.customers.account.enter-password')
                </h2>
            </x-slot:header>

            <x-slot:content>
                <x-form
                    action="{{ route('shop.customers.account.profile.destroy') }}"
                >
                    <x-form.control-group>
                        <div class="p-[30px] bg-white">
                            <x-form.control-group.control
                                type="password"
                                name="password"
                                class="py-[20px] px-[25px]"
                                placeholder="Enter your password"
                            />

                            <x-form.control-group.error
                                control-name="password"
                            >
                            </x-form.control-group.error>
                        </div>
                    </x-form.control-group>

                    <div class="p-[30px] bg-white mt-[20px]">
                        <button
                            type="submit"
                            class="block flex-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer max-sm:text-[14px] max-sm:px-[25px]"
                        >
                            @lang('shop::app.customers.account.delete')
                        </button>
                    </div>
                </x-form>
            </x-slot:content>
        </x-shop::modal>
    </div>
</x-shop::layouts.account>
