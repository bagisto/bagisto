<x-shop::layouts.account>
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile"></x-shop::breadcrumbs>
    @endSection

    <div class="flex justify-between">
        <h2 class="text-[26px] font-medium">
            @lang('shop::app.customers.account.title')
        </h2>

        <a
            href="{{ route('shop.customers.account.profile.edit') }}"
            class="border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
        >
            @lang('shop::app.customers.account.profile.edit')
        </a>
    </div>

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

        <x-shop::modal>
            <x-slot:toggle>
                <div
                    class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                >
                    @lang('shop::app.customers.account.profile.delete-profile')
                </div>
            </x-slot:toggle>

            <x-slot:header>
                @lang('shop::app.customers.account.enter-password')
            </x-slot:header>

            <x-slot:content>
                <x-form
                    action="{{ route('shop.customers.account.profile.destroy') }}"
                >
                    <x-form.control-group>
                        <x-form.control-group.label>
                            @lang('shop::app.customers.account.password')
                        </x-form.control-group.label>

                        <x-form.control-group.control
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                        />

                        <x-form.control-group.error
                            control-name="password"
                        >
                        </x-form.control-group.error>
                    </x-form.control-group>

                    <button
                        type="submit"
                        class="m-0 block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
                    >
                        @lang('shop::app.customers.account.delete')
                    </button>
                </x-form>
            </x-slot:content>
        </x-shop::modal>
    </div>
</x-shop::layouts.account>
