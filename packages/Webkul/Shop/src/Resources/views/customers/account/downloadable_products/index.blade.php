<x-shop::layouts.account>
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="downloadable-products"></x-shop::breadcrumbs>
    @endSection

    <div class="flex-auto">
        <div class="max-lg:hidden">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.downloadable-products.name')
            </h2>

            @if (! $downloadableLinkPurchased->isEmpty())
                <div class="relative overflow-x-auto border border-b-0  rounded-[12px] mt-[30px]">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[14px] text-black bg-[#F5F5F5] border-b-[1px] border-[#E9E9E9]">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.downloadable-products.orderId')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.downloadable-products.title')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.downloadable-products.date')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.downloadable-products.status')
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-[16px] font-medium"
                                >
                                    @lang('shop::app.customers.account.downloadable-products.remaining-downloads')
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($downloadableLinkPurchased as $item)
                                <tr class="bg-white border-b">
                                    <th 
                                        scope="row" 
                                        class="px-6 py-[16px] font-medium whitespace-nowrap text-black"
                                    >
                                        {{ $item->order_id }}
                                    </th>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium "
                                    >
                                        @if ($item->status == 'available')
                                            <a  
                                                class="text-blue-600"
                                                href="{{ route('shop.customers.account.downloadable_products.download', $item->id) }}" 
                                                target="_blank"
                                            >
                                                {{ $item->product_name }}
                                            </a>
                                        @else 
                                            {{ $item->product_name }}
                                        @endif
                                    </td>

                                    <td class="px-6 py-[16px] text-black font-medium ">
                                        {{ $item->created_at }}
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium "
                                    > 
                                        @switch($item->status)
                                            @case('completed')

                                                <span class=" text-white text-[12px] px-[10px] py-[4px] rounded-[12px] bg-[#5BA34B]">
                                                    {{ $item->status }}
                                                </span>
                                                @break

                                            @case('pending')

                                                <span class=" text-white text-[12px] px-[10px] py-[4px] rounded-[12px] bg-[#FDB60C]">
                                                    {{ $item->status }}
                                                </span>
                                                @break

                                            @case('available')
                                                <span class="text-white text-[12px] px-[10px] py-[4px] rounded-[12px] bg-[#5BA34B]">
                                                    {{ $item->status }}
                                                </span>
                                                @break
                                        @endswitch
                                    </td>

                                    <td 
                                        class="px-6 py-[16px] text-black font-medium "
                                    > 
                                        {{ $item->download_bought }} - {{ $item->download_used }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="text-[14px] text-right font-medium mt-[20px]"> 
                    {{ $downloadableLinkPurchased->count() }}
                    
                    @lang('shop::app.customers.account.downloadable-products.records-found')
                </p>
            @else
                <div class="grid items-center justify-items-center w-max m-auto h-[476px] place-content-center">
                    <img
                        src="{{ bagisto_asset('images/empty-dwn-product.png')}}"
                        class=""
                        alt=""
                        title=""
                    >

                    <p class="text-[20px]">
                        @lang('shop::app.customers.account.downloadable-products.empty-product')
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-shop::layouts.account>
