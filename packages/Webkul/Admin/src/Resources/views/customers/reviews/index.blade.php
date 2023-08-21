<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.customers.reviews.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 font-bold">
            @lang('admin::app.customers.reviews.index.title')
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
    
    <x-admin::datagrid src="{{ route('admin.customer.review.index') }}">
        <template #header="{ columns, records, sortPage }">
            <div class="row grid px-[16px] py-[10px] border-b-[1px] border-gray-300 grid-cols-4 grid-rows-1">
                <div
                    class="cursor-pointer"
                    @click="sortPage(columns.find(column => column.index === 'product_review_status'))"
                >
                    <div class="flex gap-[10px]">
                        <p class="text-gray-600">Name / Product / Status</p>
                    </div>
                </div>

                <div
                    class="cursor-pointer"
                    @click="sortPage(columns.find(column => column.index === 'product_review_id'))"
                >
                    <p class="text-gray-600">Rating / Date / ID</p>
                </div>

                <div class="cursor-pointer">
                    <p class="text-gray-600">Title / Description</p>
                </div>
            </div>
        </template>

        <template #body="{ columns, records }">
            <div
                class="row grid grid-cols-[1fr_1fr_minmax(150px,_2fr)] px-[16px] py-[10px] border-b-[1px] border-gray-300"
                v-for="record in records"
            >
                {{-- Name, Product, Description --}}
                <div class="">
                    <div class="flex gap-[10px]">
                        <div class="flex flex-col gap-[6px]">
                            <p
                                class="text-[16px] text-gray-800 font-semibold"
                                v-text="record.customer_full_name"
                            >
                            </p>

                            <p
                                class="text-gray-600"
                                v-text="record.product_name"
                            >
                            </p>

                            <p
                                :class="{
                                    'label-cancelled': record.product_review_status === 'disapproved',
                                    'label-pending': record.product_review_status === 'pending',
                                    'label-active': record.product_review_status === 'approved',
                                }"
                                v-text="record.product_review_status"
                            >
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rating, Date, Id Section --}}
                <div class="">
                    <div class="flex flex-col gap-[6px]">
                        <div class="flex">
                            <template v-for="(rating, index) in record.rating">
                                <span class="icon-star text-[18px] text-amber-500"></span>
                            </template>
                        </div>

                        <p
                            class="text-gray-600"
                            v-text="record.created_at"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.product_review_id"
                        >
                        </p>
                    </div>
                </div>

                {{-- Title, Description --}}
                <div class="flex gap-x-[16px] justify-between items-center">
                    <div class="flex flex-col gap-[6px]">
                        <p
                            class="text-[16px] text-gray-800 font-semibold"
                            v-text="record.title"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.comment"
                        >
                        </p>
                    </div>

                    <a :href=`{{ route('admin.customer.review.delete', '') }}/${record.product_review_id}`>
                        <span class="icon-delete text-[24px] ml-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </a>

                    <a :href=`{{ route('admin.customer.review.edit', '') }}/${record.product_review_id}`>
                        <span class="icon-sort-right text-[24px] ml-[4px]p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                    </a>
                </div>

            </div>
        </template>
    </x-admin::datagrid>
</x-admin::layouts>