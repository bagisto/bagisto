<x-shop::layouts.account>
    <div class="flex justify-between items-center">
        <div class="">
            <div class="flex gap-x-[4px] items-center mb-[10px]">
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']"> Profile</p>
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">Address</p>
            </div>
            <h2 class="text-[26px] font-medium">{{ trans('shop::app.customers.account.address.title')}}</h2>
        </div>
        <a
            href="{{ route('shop.customer.addresses.create') }}"
            class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
        >
        {{ trans('shop::app.customers.account.address.add-address') }}
        </a>
    </div>

    <div class="grid mt-[60px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr]">
        @foreach ($addresses as $address)
            <div class="border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap">
                <div class="flex justify-between items-center">
                    <p class="text-[16px] font-medium">
                        {{ $address->company_name }}
                    </p>
                    <div class="flex gap-[25px] items-center">

                        @if ($address->default_address)
                            <div class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white w-max font-medium p-[5px] rounded-[10px] text-center text-[12px]">Default Address</div>
                        @endif

                        <span class="icon-more text-[24px] text-[#7D7D7D]"></span>
                    </div>
                </div>
                <p class="text-[#7D7D7D] mt-[25px]">
                    {{ $address->address1 }} {{ $address->address2 }},
                    {{ $address->city }}, 
                    {{ $address->state }}, {{ $address->country }}, 
                    {{ $address->postcode }}
                </p>
            </div>    
        @endforeach
    </div>

</x-shop::layouts.account>
