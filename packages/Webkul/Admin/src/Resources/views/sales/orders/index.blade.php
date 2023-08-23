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
        </div>
    </div>

    <x-admin::datagrid :src="route('admin.sales.orders.index')">
        <template #header="{ columns, records, sortPage }">
            <div class="row grid px-[16px] py-[10px] border-b-[1px] border-gray-300 grid-cols-4 grid-rows-1">
                <div
                    class="cursor-pointer"
                    @click="sortPage(columns.find(column => column.index === 'increment_id'))"
                >
                    <div class="flex gap-[10px]">
                        <p class="text-gray-600">
                            @lang('admin::app.sales.orders.index.datagrid.order-id') / 
                            @lang('admin::app.sales.orders.index.datagrid.date') / 
                            @lang('admin::app.sales.orders.index.datagrid.status')
                        </p>
                    </div>
                </div>

                <div
                    class="cursor-pointer"
                    @click="sortPage(columns.find(column => column.index === 'base_grand_total'))"
                >
                    <p class="text-gray-600">
                        @lang('admin::app.sales.orders.index.datagrid.grand-total') / 
                        @lang('admin::app.sales.orders.index.datagrid.pay-via') / 
                        @lang('admin::app.sales.orders.index.datagrid.channel-name')
                    </p>
                </div>

                <div
                    class="cursor-pointer"
                    @click="sortPage(columns.find(column => column.index === 'full_name'))"
                >
                    <p class="text-gray-600">
                        @lang('admin::app.sales.orders.index.datagrid.customer') / 
                        @lang('admin::app.sales.orders.index.datagrid.email') /
                        @lang('admin::app.sales.orders.index.datagrid.location') / 
                        @lang('admin::app.sales.orders.index.datagrid.images')</p>
                </div>
            </div>
        </template>

        <template #body="{ columns, records }">
            <div
                class="row grid grid-cols-4 px-[16px] py-[10px] border-b-[1px] border-gray-300"
                v-for="record in records"
            > 
                {{-- Order Id, Created, Status Section --}}
                <div class="">
                    <div class="flex gap-[10px]">
                        <div class="flex flex-col gap-[6px]">
                            <p
                                class="text-[16px] text-gray-800 font-semibold"
                            >
                                @{{ "@lang('admin::app.sales.orders.index.datagrid.id')".replace(':id', record.increment_id) }}
                            </p>

                            <p
                                class="text-gray-600"
                                v-text="record.created_at"
                            >
                            </p>

                            <p
                                v-if="record.is_closure"
                                v-html="record.status"
                            >
                            </p>

                            <p
                                v-else
                                v-text="record.is_closure"
                            >
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Total Amount, Pay Via, Channel --}}
                <div class="">
                    <div class="flex flex-col gap-[6px]">
                        <p class="text-[16px] text-gray-800 font-semibold">
                            @{{ $admin.formatPrice(record.base_grand_total) }}
                        </p>

                        <p class="text-gray-600">
                            @lang('admin::app.sales.orders.index.datagrid.pay-by', ['method' => ''])@{{ record.method }}
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.channel_name"
                        >
                        </p>
                    </div>
                </div>

                {{-- Custoemr, Email, Location Section --}}
                <div class="">
                    <div class="flex flex-col gap-[6px]">
                        <p
                            class="text-[16px] text-gray-800"
                            v-text="record.full_name"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.customer_email"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.location"
                        >
                        </p>
                    </div>
                </div>

                {{-- Imgaes Section --}}
                <div class="flex gap-x-[16px] justify-between items-center">
                    <div class="flex flex-col gap-[6px]">
                        <p
                            v-if="record.is_closure"
                            class="text-gray-600"
                            v-html="record.image"
                        >
                        </p>

                        <p
                            v-else
                            class="text-gray-600"
                            v-html="record.image"
                        >
                        </p>
                        
                    </div>

                    <a :href=`{{ route('admin.sales.orders.view', '') }}/${record.id}`>
                        <span class="icon-sort-right text-[24px] ml-[4px] cursor-pointer"></span>
                    </a>
                </div>
            </div>
        </template>
    </x-admin::datagrid>
</x-admin::layouts>