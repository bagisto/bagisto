<x-admin::layouts>
    
    <div class="grid">
        <div class="flex items-center cursor-pointer">
            <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 text-center w-full max-w-max rounded-[6px]">
                <span class="icon-sort-left text-[24px]"></span>
            </div>
            <p class="text-gray-600">Customers</p>
        </div>
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                {{ $customer->first_name . " " . $customer->last_name }}
            <div class="flex gap-x-[10px] items-center">
                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all hover:bg-gray-200">
                    <span class="icon-arrow-right text-[24px]"></span>
                </div>
                <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all hover:bg-gray-200">
                    <span class="icon-arrow-left text-[24px]"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Filter row -->
    <div class="flex gap-x-[4px] gap-y-[8px] items-center flex-wrap mt-[28px]">
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-printer text-[24px] "></span> Print
        </div>
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-mail text-[24px] "></span> Email
        </div>
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-location text-[24px] "></span> Add New Address
        </div>
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-cart text-[24px] "></span> Create Order
        </div>
        <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
            <span class="icon-cancel text-[24px] "></span> Delete Account
        </div>
    </div>
    <!-- body content -->
    <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
        <!-- Left sub-component -->
        <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">

            <!-- Orders row -->
            <div class=" bg-white rounded-[4px] box-shadow">
                <div class=" p-[16px] flex justify-between">
                    <p class="text-[16px] text-gray-800 font-semibold">Orders(3)</p>
                    <p class="text-[16px] text-gray-800 font-semibold">Total Revenue - $380.00</p>
                </div>
                <div class="table-responsive grid w-full">
                    @foreach ($orders as $order)
                   
                        <div class="flex justify-between items-center px-[16px] py-[16px]">
                            <div class="row grid grid-cols-3 w-full">
                                <div class="">
                                    <div class="flex gap-[10px]">
                                        <span class="icon-uncheckbox text-[24px]"></span>
                                        <div class="flex flex-col gap-[6px]">
                                            <p class="text-[16px] text-gray-800 font-semibold">#{{ $order->id }}</p>
                                            <p class="text-gray-600">{{ $order->created_at }}</p>
                                            <p class="label-pending">{{ $order->status }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800 font-semibold">{{ core()->currency($order->grand_total ) }}</p>
                                        <p class="text-gray-600">Pay by - {{ core()->getConfigData('sales.paymentmethods.' . $order->payment->method . '.title') }}</p>
                                        <p class="text-gray-600">Online Store</p>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="flex flex-col gap-[6px]">
                                        <p class="text-[16px] text-gray-800">{{ $customer->first_name . " " . $customer->last_name }}</p>
                                        <p class="text-gray-600">john@deo.com</p>
                                        <p class="text-gray-600">Broadway, New York</p>
                                    </div>
                                </div>
                            </div>
                            <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                        </div>
                    @endforeach
                    <!-- Body -->
                    <!-- single row -->
                
                    <span class="border-b-[1px] border-gray-300"></span>

                </div>
            </div>

            <!-- Invoice row -->
            <div class="bg-white rounded box-shadow">
                <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">Invoice (3)</p>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left min-w-[800px]">
                        <thead class="text-[14px] text-gray-600 bg-gray-50 border-b-[1px] border-gray-200  ">
                            <tr>
                                <th scope="col" class="px-6 py-[16px] font-semibold"> Invoice ID </th>
                                <th scope="col" class="px-6 py-[16px] font-semibold"> Invoice Date </th>
                                <th scope="col" class="px-6 py-[16px] font-semibold"> Invoice Amount </th>
                                <th scope="col" class="px-6 py-[16px] font-semibold"> Order ID </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b ">
                                <td class="px-6 py-[16px] text-gray-600">#274778</td>
                                <td class="px-6 py-[16px] text-gray-600 whitespace-nowrap"> 23 Mar 2023, 01:00:00</td>
                                <td scope="row" class="px-6 py-[16px] text-gray-600"> $ 25.00 </td>
                                <td class="px-6 py-[16px] text-gray-600">#02153</td>
                            </tr>
                            <tr class="bg-white border-b ">
                                <td class="px-6 py-[16px] text-gray-600">#274778</td>
                                <td class="px-6 py-[16px] text-gray-600 whitespace-nowrap"> 23 Mar 2023, 01:00:00</td>
                                <td scope="row" class="px-6 py-[16px] text-gray-600"> $ 25.00 </td>
                                <td class="px-6 py-[16px] text-gray-600">#02153</td>
                            </tr>
                            <tr class="bg-white border-b ">
                                <td class="px-6 py-[16px] text-gray-600">#274778</td>
                                <td class="px-6 py-[16px] text-gray-600 whitespace-nowrap"> 23 Mar 2023, 01:00:00</td>
                                <td scope="row" class="px-6 py-[16px] text-gray-600"> $ 25.00 </td>
                                <td class="px-6 py-[16px] text-gray-600">#02153</td>
                            </tr>
                            <tr class="bg-white border-b ">
                                <td class="px-6 py-[16px] text-gray-600">#274778</td>
                                <td class="px-6 py-[16px] text-gray-600 whitespace-nowrap"> 23 Mar 2023, 01:00:00</td>
                                <td scope="row" class="px-6 py-[16px] text-gray-600"> $ 25.00 </td>
                                <td class="px-6 py-[16px] text-gray-600">#02153</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Review -->
            <div class="bg-white rounded box-shadow">
                <p class=" p-[16px] text-[16px] text-gray-800 font-semibold">Review (3)</p>
                <div class="">
                    <div class="grid gap-y-[16px] p-[16px]">
                        <div class="flex justify-start [&amp;>*]:flex-1">
                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-800 font-semibold">John Doe</p>
                                <p class="text-gray-600">23 Mar 2023, 01:00:00</p>
                                <p class="label-pending">Pending</p>
                            </div>
                            <div class="flex flex-col gap-[6px]">
                                <div class="flex">
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-gray-300"></span>
                                </div>
                                <p class="text-gray-600">23 Mar 2023, 01:00:00</p>
                                <p class="text-gray-600">ID - 25498</p>
                            </div>
                        </div>
                        <div class="flex gap-x-[16px] items-center">
                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-800 font-semibold">John Doe</p>
                                <p class="text-gray-600">I really like the design of these shoes. They're very stylish and true to size, but they're also super affordable. These are definitely the shoes for me.</p>
                            </div>
                            <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                        </div>
                    </div>
                    <span class="block w-full border-b-[1px] border-gray-300"></span>
                    <div class="grid gap-y-[16px] p-[16px]">
                        <div class="flex justify-start [&amp;>*]:flex-1">
                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-800 font-semibold">John Doe</p>
                                <p class="text-gray-600">23 Mar 2023, 01:00:00</p>
                                <p class="label-pending">Pending</p>
                            </div>
                            <div class="flex flex-col gap-[6px]">
                                <div class="flex">
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-amber-500"></span>
                                    <span class="icon-star text-[18px] text-gray-300"></span>
                                </div>
                                <p class="text-gray-600">23 Mar 2023, 01:00:00</p>
                                <p class="text-gray-600">ID - 25498</p>
                            </div>
                        </div>
                        <div class="flex gap-x-[16px] items-center">
                            <div class="flex flex-col gap-[6px]">
                                <p class="text-[16px] text-gray-800 font-semibold">John Doe</p>
                                <p class="text-gray-600">I really like the design of these shoes. They're very stylish and true to size, but
                                    they're also super affordable. These are definitely the shoes for me.</p>
                            </div>
                            <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice row -->
            <div class="bg-white rounded box-shadow">
                <p class=" p-[16px] pb-0 text-[16px] text-gray-800 font-semibold">Comments</p>

                <div class="p-[16px]">
                    <div class="mb-[10px]">
                        <textarea class="text-[14px] text-gray-600 appearance-none border rounded-[6px] w-full py-2 px-3 transition-all hover:border-gray-400" type="text" placeholder="Write your comment"></textarea>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="flex gap-x-[4px] items-center"><span class="icon-uncheckbox text-[24px] rounded-[6px] cursor-pointer"></span>Notify Customer</p>
                        <div class="text-blue-600 font-semibold whitespace-nowrap px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] cursor-pointer">
                        Submit Comment</div>
                    </div>
                </div>
                <span class="block w-full border-b-[1px] border-gray-300"></span>
                <div class="grid gap-[6px] p-[16px]">
                    <p class="text-[16px] text-gray-800">The product you order some are available some are not, then can I go wor partial shipment.</p>
                    <p class="text-gray-600">2023-03-24 19:36:38 | Customer Not Notified</p>
                </div>
                <span class="block w-full border-b-[1px] border-gray-300"></span>
                <div class="grid gap-[6px] p-[16px]">
                    <p class="text-[16px] text-gray-800">The product you order some are available some are not, then can I go wor
                        partial shipment.</p>
                    <p class="text-gray-600">2023-03-24 19:36:38 | Customer Not Notified</p>
                </div>

            </div>

        </div>
        <!-- Right sub-component -->
        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
            <!-- component 1 -->
            <div class="bg-white rounded-[4px] box-shadow">
                <div class="flex items-center justify-between p-[6px]">
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Customer</p>
                    <div class="flex gap-[6px] items-center">
                        <p class="text-blue-600">Edit</p>
                        <span class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </div>
                </div>
                <div class="grid gap-y-[10px] px-[16px] pb-[16px]">
                    <div class="">
                        <p class="text-gray-800 font-semibold">John Doe</p>
                        <p class="text-gray-600">test@driver.com</p>
                        <p class="text-gray-600">No Contact Number</p>
                    </div>
                    <div class="">
                        <p class="text-gray-600">Gender : Male</p>
                        <p class="text-gray-600">DOB : 25 Nov, 1988</p>
                    </div>
                    <div class="">
                        <p class="text-gray-600">General Group</p>
                    </div>
                </div>
            </div>
            <!-- component 2 -->
            <div class="bg-white rounded-[4px] box-shadow">
                <div class="flex items-center justify-between p-[6px]">
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Active Status</p>
                    <div class="flex gap-[6px] items-center">
                        <p class="text-blue-600">Edit</p>
                        <span class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </div>
                </div>
                <div class="grid gap-y-[10px] px-[16px] pb-[16px]">
                    <div class="flex gap-[10px] p-[6px] items-center cursor-pointer hover:bg-gray-100 hover:rounded-[8px]">
                        <span class="icon-checked text-[24px] text-blue-600 rounded-[6px] cursor-pointer"></span>
                        <p class="text-gray-600 font-semibold">Customer Status</p>
                    </div>
                    <div class="flex gap-[10px] p-[6px] items-center cursor-pointer hover:bg-gray-100 hover:rounded-[8px]">
                        <span class="icon-uncheckbox text-[24px] rounded-[6px] cursor-pointer"></span>
                        <p class="text-gray-600 font-semibold">Suspend</p>
                    </div>
                </div>
            </div>
            <!-- component 3 -->
            <div class="bg-white rounded-[4px] box-shadow">
                <div class="flex items-center justify-between p-[6px]">
                    <p class="text-gray-600 text-[16px] p-[10px] font-semibold">Address (2)</p>
                    <div class="flex gap-[6px] items-center">
                        <p class="text-blue-600">Edit</p>
                        <span class="icon-arrow-up text-[24px] p-[6px]  rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </div>
                </div>
                <div class="grid gap-y-[10px] px-[16px] pb-[16px]">
                    <p class="label-pending">Default Address</p>
                    <div class="">
                        <p class="text-gray-800 font-semibold">Emel John</p>
                        <p class="text-gray-600">Emel JohnFirs Cottage</p>
                        <p class="text-gray-600">Kirk Langley,DE6 4LW</p>
                    </div>
                    <div class="">
                        <p class="text-gray-600">M : 3698741254</p>
                    </div>
                    <div class="flex gap-[10px]">
                        <p class="text-blue-600">Edit</p>
                        <p class="text-blue-600">Delete</p>
                    </div>
                </div>
                <span class="block w-full mb-[16px] border-b-[1px] border-gray-300"></span>
                <div class="grid gap-y-[10px] px-[16px] pb-[16px]">
                    <div class="">
                        <p class="text-gray-800 font-semibold">John Doe</p>
                        <p class="text-gray-600">test@driver.com</p>
                        <p class="text-gray-600">No Contact Number</p>
                    </div>
                    <div class="">
                        <p class="text-gray-600">Gender : Male</p>
                        <p class="text-gray-600">DOB : 25 Nov, 1988</p>
                    </div>
                    <div class="flex gap-[10px]">
                        <p class="text-blue-600">Edit</p>
                        <p class="text-blue-600">Delete</p>
                        <p class="text-blue-600">Set as Defualt</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin::layouts>
    