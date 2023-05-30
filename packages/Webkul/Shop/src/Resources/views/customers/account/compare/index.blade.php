<x-shop::layouts.account>
    <div class="flex-auto">
        <div class="max-lg:hidden">
            <div class="flex justify-between items-center">
                <div class="">
                    <div class="flex gap-x-[4px] items-center mb-[10px]">
                        <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']">
                            @lang('shop::app.customers.account.title')
                        </p>

                        <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                            @lang('shop::app.customers.account.compare.compare-similar-items')
                        </p>
                    </div>

                    <h2 class="text-[26px] font-medium">
                        @lang('shop::app.customers.account.compare.compare-similar-items')
                    </h2>
                </div>

                <div
                    class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
                >
                    <span class="icon-bin text-[24px]"></span>
                    @lang('shop::app.customers.account.compare.delete-all')
                </div>
            </div>

            <div class="">
                <div class="grid w-full border rounded-[8px] [&>*:nth-child(odd)]:bg-[#F5F5F5] mt-[30px]">
                    <div
                        class="flex justify-between items-center w-full px-[28px] py-[15px] border-b-[1px] border-[#E9E9E9]">
                        <p class="text-[14px] font-medium">Brand Name</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">Women’s green cloth</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">₹ 7,383.00</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">Prada</p>
                    </div>
                    <div
                        class="flex justify-between items-center w-full px-[28px] py-[15px] border-b-[1px] border-[#E9E9E9]">
                        <p class="text-[14px] font-medium">Brand Name</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">Women’s green cloth</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">₹ 7,383.00</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">Prada</p>
                    </div>
                    <div
                        class="flex justify-between items-center w-full px-[28px] py-[15px] border-b-[1px] border-[#E9E9E9] last-of-type:border-none">
                        <p class="text-[14px] font-medium">Brand Name</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">Women’s green cloth</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">₹ 7,383.00</p>
                        <p class="text-[14px] font-medium text-[#3A3A3A]">Prada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop::layouts.account>