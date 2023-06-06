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

                @if ($compareItem->count())
                    <div
                        class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
                    >
                        <span class="icon-bin text-[24px]"></span>

                        <x-shop::form
                            :action="route('shop.customers.account.compare.destroyAll')"
                            method="DELETE"
                        >
                            <button>                            
                                @lang('shop::app.customers.account.compare.delete-all')
                            </button>
                        </x-shop::form>
                    </div>
                @endif
            </div>

            <div class="">
                <div class="grid w-full border rounded-[8px] [&>*:nth-child(odd)]:bg-[#F5F5F5] mt-[30px]">
                    @foreach ($compareItem as $item)
                        <div class="flex justify-between items-center w-full px-[28px] py-[15px] border-b-[1px] border-[#E9E9E9]">
                            <p class="text-[14px] font-medium">
                                {{ $item->product->name }}
                            </p>

                            <p class="text-[14px] font-medium text-[#3A3A3A]">
                                {{ $item->product->description }}
                            </p>

                            <p class="text-[14px] font-medium text-[#3A3A3A]">
                                ₹ {{ $item->product->price }}
                            </p>

                            <p class="text-[14px] font-medium text-[#3A3A3A]">
                                <x-shop::form
                                    :action="route('shop.customers.account.compare.destroy', $item->product_id)"
                                    method="DELETE"
                                >
                                    <button>                            
                                        @lang('shop::app.customers.account.compare.delete')
                                    </button>
                                </x-shop::form>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-shop::layouts.account>