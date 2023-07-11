<x-shop::layouts.account>
    {{-- breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="orders"></x-shop::breadcrumbs>
    @endSection

    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.orders.title')
            </h2>
        </div>
    </div>

    @if (! $orders->isEmpty())
        {{-- Orders Information --}}
        <div class="relative overflow-x-auto border rounded-[12px] mt-[30px]">
            <table class="w-full text-sm text-left">
                <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]  ">
                    <tr>
                        <th 
                            scope="col" 
                            class="px-6 py-[16px] font-medium"
                        >
                            @lang('shop::app.customers.account.orders.order_id')
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-[16px] font-medium"
                        >
                            @lang('shop::app.customers.account.orders.order_date')
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-[16px] font-medium"
                        >
                            @lang('shop::app.customers.account.orders.total')
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-[16px] font-medium"
                        >
                            @lang('shop::app.customers.account.orders.status')
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-[16px] font-medium"
                        >
                            @lang('shop::app.customers.account.orders.action')
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                    {{-- @dd($order) --}}
                        <tr class="bg-white border-b">
                            <th
                                scope="row"
                                class="px-6 py-[16px] font-medium whitespace-nowrap text-black"
                            >
                                {{ $order->id}}
                            </th>

                            <td class="px-6 py-[16px] text-black font-medium">
                                {{ $order->created_at}}
                            </td>

                            <td class="px-6 py-[16px] text-black font-medium">
                                ₹ {{ $order->grand_total}}
                            </td>

                            <td class="px-6 py-[16px] text-black font-medium"> 
                                @switch($order->status)
                                    @case('processing')

                                        <span class=" text-white text-[12px] px-[10px] py-[4px] rounded-[12px] bg-[#5BA34B]">
                                            {{  $order->status }}
                                        </span>        
                                        @break

                                    @case('completed')

                                        <span class=" text-white text-[12px] px-[10px] py-[4px] rounded-[12px] bg-[#5BA34B]">
                                            {{ $order->status}}
                                        </span> 
                                        @break

                                    @case('pending')

                                        <span class=" text-white text-[12px] px-[10px] py-[4px] rounded-[12px] bg-[#FDB60C]">
                                            {{ $order->status }}
                                        </span> 
                                        @break

                                    @case('canceled')

                                        <span class=" text-white text-[12px] px-[10px] py-[4px] rounded-[12px] bg-[#FDB60C]">
                                            {{ $order->status }}
                                        </span> 
                                        @break

                                @endswitch
                            </td>

                            <td class="px-6 py-[16px] text-black">
                                <a href="{{ route('shop.customers.account.orders.view', $order->id) }}">
                                    <span class="icon-eye text-[24px]"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-between items-center p-[30px]">
                <p class="text-[12px] font-medium">
                    Showing 1 to 8 of 40 entries
                </p>

                <nav aria-label="Page navigation">
                    <ul class="inline-flex items-center -space-x-px">
                        <li>
                            <a 
                                href="#"
                                class="flex items-center justify-center w-[35px] h-[37px] leading-normal font-medium border border-[#E9E9E9] rounded-l-lg hover:bg-gray-100"
                            >
                                <span class="icon-arrow-left text-[24px]"></span>
                            </a>
                        </li>

                        <li>
                            <a 
                                href="#"
                                class="px-[15px] py-[6px] leading-normal text-black font-medium border border-[#E9E9E9] hover:bg-gray-100"
                            >
                                1
                            </a>
                        </li>

                        <li>
                            <a 
                                href="#"
                                class="flex items-center justify-center w-[35px] h-[37px] leading-normal font-medium border border-[#E9E9E9] rounded-r-lg hover:bg-gray-100"
                            >
                                <span class="icon-arrow-right text-[24px]"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @else
        {{-- Orders Empty Page --}}
        <div class="grid items-center justify-items-center w-[100%] m-auto h-[476px] place-content-center text-center">
            <img 
                class="" 
                src="{{ bagisto_asset('images/empty-dwn-product.png') }}" 
                alt="" 
                title=""
            >
            
            <p class="text-[20px]">
                @lang('shop::app.customers.account.orders.empty-order')
            </p>
        </div>
    @endif
</x-shop::layouts.account>