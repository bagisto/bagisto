<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.orders.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 font-bold">
            @lang('admin::app.sales.orders.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Dropdown -->
            <x-admin::dropdown position="bottom-right">
                <x-slot:toggle>
                    <span class="icon-setting p-[6px] rounded-[6px] text-[24px]  cursor-pointer transition-all hover:bg-gray-100"></span>
                </x-slot:toggle>

                <x-slot:content class="w-[174px] max-w-full !p-[8PX] border border-gray-300 rounded-[4px] z-10 bg-white shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)]">
                    <div class="grid gap-[2px]">
                        <!-- Current Channel -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Channel - {{ core()->getCurrentChannel()->name }}
                            </p>
                        </div>

                        <!-- Current Locale -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Language - {{ core()->getCurrentLocale()->name }}
                            </p>
                        </div>

                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <!-- Export Modal -->
                            <x-admin::modal ref="exportModal">
                                <x-slot:toggle>
                                    <p class="text-gray-600 font-semibold leading-[24px]">
                                        Export
                                    </p>
                                </x-slot:toggle>

                                <x-slot:header>
                                    <p class="text-[18px] text-gray-800 font-bold">
                                        @lang('Download')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="p-[16px]">
                                        <x-admin::form action="">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="format"
                                                    id="format"
                                                >
                                                    <option value="xls">XLS</option>
                                                    <option value="csv">CLS</option>
                                                </x-admin::form.control-group.control>
                                            </x-admin::form.control-group>
                                        </x-admin::form>
                                    </div>
                                </x-slot:content>
                                <x-slot:footer>
                                    <!-- Save Button -->
                                    <button
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('Export')
                                    </button>
                                </x-slot:footer>
                            </x-admin::modal>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::dropdown>

            <a href="#">
                <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                    @lang('Create Order')
                </div>
            </a>
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.sales.orders.index')">
        <template #header="{ columns, records }">
            <div class="row grid px-[16px] py-[10px] border-b-[1px] border-gray-300 grid-cols-4 grid-rows-1">
                <div class="">
                    <div class="flex gap-[10px]">
                        <span class="icon-uncheckbox text-[24px]"></span>
                        <p class="text-gray-600">Order ID / Date / Status</p>
                    </div>
                </div>

                <div class="">
                    <p class="text-gray-600">Price / Pay Via / Channel</p>
                </div>

                <div class="">
                    <p class="text-gray-600">Customer / Email / Location / Image</p>
                </div>
            </div>
        </template>

        <template #body="{ columns, records }">
            <div class="row grid grid-cols-4 px-[16px] py-[10px] border-b-[1px] border-gray-300" v-for="record in records">
                <div class="">
                    <div class="flex gap-[10px]">
                        <span class="icon-uncheckbox text-[24px]"></span>

                        <div class="flex flex-col gap-[6px]">
                            <p class="text-[16px] text-gray-800 font-semibold">#02153</p>

                            <p class="text-gray-600">23 Mar 2023, 01:00:00</p>

                            <p class="label-pending">Pending</p>
                        </div>
                    </div>
                </div>

                <div class="">
                    <div class="flex flex-col gap-[6px]">
                        <p class="text-[16px] text-gray-800 font-semibold">$75.00</p>

                        <p class="text-gray-600">Pay by - Cash on Delivery</p>

                        <p class="text-gray-600">Online Store</p>
                    </div>
                </div>

                <div class="">
                    <div class="flex flex-col gap-[6px]">
                        <p class="text-[16px] text-gray-800">John Doe</p>

                        <p class="text-gray-600">john@deo.com</p>

                        <p class="text-gray-600">Broadway, New York</p>
                    </div>
                </div>

                <div class="">
                    <div class="flex  gap-[6px] items-center">
                        <div class="flex gap-[6px] items-center flex-wrap">
                            <div class="relative">
                                <img class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]" src="../images/order-1.png">

                                <span class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px] ">1</span>
                            </div>

                            <div class="relative">
                                <img class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]" src="../images/order-1.png">

                                <span class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px] ">2</span>
                            </div>

                            <div class="relative">
                                <img class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]" src="../images/order-1.png">

                                <span class="absolute bottom-[1px] left-[1px] text-[12px] font-bold text-white bg-darkPink rounded-full px-[6px] ">3</span>
                            </div>
                        </div>

                        <div class="flex gap-[6px] items-center">
                            <div class="flex items-center w-[65px] h-[65px] bg-gray-50 rounded-[4px]">
                                <p class="text-[12px] text-gray-600 text-center font-bold px-[6px] py-[6px]">2+ More Products </p>
                            </div>

                            <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </x-admin::datagrid>
</x-admin::layouts>
