<div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <x-admin::datagrid
        :src="route('admin.customers.customers.view', [
            'id'   => $customer->id,
            'type' => 'orders'
        ])"
    >
        <template #header="{ available, records }">
            <div class="p-4 flex justify-between">
                <!-- Total Order Count -->
                <p
                    class="w-32 p-2.5 shimmer"
                    v-if="! available.meta.total"
                ></p>

                <p
                    class="text-base text-gray-800 leading-none dark:text-white font-semibold"
                    v-else
                >
                    @{{ "@lang('admin::app.customers.customers.view.orders')".replace(':order_count', available.meta.total ?? 'N/A') }}
                </p>
                

                <!-- Total Order Revenue -->
                <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                    @lang('admin::app.customers.customers.view.total-revenue', ['revenue' => core()->formatPrice($customer->orders->whereNotIn('status', ['canceled', 'closed'])->sum('base_grand_total_invoiced'))])
                </p>
            </div>
        </template>

        <template #body="{ columns, records, performAction, available }">
            <div
                v-for="record in records"
                class="flex justify-between items-center px-4 py-4 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
            >
                <div class="">
                    <div class="flex gap-2.5">
                        <div class="flex flex-col gap-1.5">
                            <!-- Id -->
                            <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                                @{{ "@lang('admin::app.customers.customers.view.increment-id')".replace(':increment_id', record.id) }}
                            </p>

                            <p  
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.created_at"
                            >
                            </p>

                            <p  
                                class="text-gray-600 dark:text-gray-300"
                                v-html="record.status"
                            >
                            </p>
                        </div>
                    </div>
                </div>

                <div class="">
                    <div class="flex flex-col gap-1.5">
                        <p class="text-base text-gray-800 dark:text-white font-semibold">
                            @{{ $admin.formatPrice(record.base_grand_total) }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ "@lang('admin::app.sales.orders.index.datagrid.pay-by')".replace(':method', record.method) }}
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.channel_name"
                        >
                        </p>
                    </div>
                </div>

                <!-- Customer, Email, Location Section -->
                <div class="">
                    <div class="flex flex-col gap-1.5">
                        <p
                            class="text-base  text-gray-800 dark:text-white"
                            v-text="record.full_name"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.customer_email"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.location"
                        >
                        </p>
                    </div>
                </div>

                <!-- View the order -->
                <div class="">
                    <div class="flex flex-col gap-1.5">
                        <a
                            :href="`{{ route('admin.sales.orders.view', '') }}/${record.id}`"
                            class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                        >
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </x-admin::datagrid>
</div>
