<x-shop::layouts.account>
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

        <div
            class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
        >
            @lang('shop::app.customers.account.profile.delete-profile')
        </div>
    </div>
</x-shop::layouts.account>
