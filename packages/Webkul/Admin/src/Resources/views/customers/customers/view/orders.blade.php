<div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <div class="flex justify-between">
        <!-- Total Order Count -->
        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
            @lang('admin::app.customers.customers.view.orders.count', ['count' => count($customer->orders)])
        </p>

        <!-- Total Order Revenue -->
        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
            @lang('admin::app.customers.customers.view.orders.total-revenue', ['revenue' => core()->formatPrice($customer->orders->whereNotIn('status', ['canceled', 'closed'])->sum('base_grand_total_invoiced'))])
        </p>
    </div>

    <x-admin::datagrid
        :src="route('admin.customers.customers.view', [
            'id'   => $customer->id,
            'type' => 'orders'
        ])"
    >
        <!-- Datagrid Header -->
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading, available }">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[0.5fr_0.5fr_1fr] grid-rows-1 items-center px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['increment_id', 'created_at', 'status'], ['base_grand_total', 'method', 'channel_name'], ['full_name', 'customer_email', 'location', 'image']]"
                    >
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === column)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span>

                            <i
                                class="ltr:ml-1.5 rtl:mr-1.5 text-base  text-gray-800 dark:text-white align-text-bottom"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>

            <!-- Datagrid Head Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
            </template>
        </template>

        <template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading, available }">
            <template v-if="! isLoading">
                <div
                    v-if="available.meta.total"
                    class="row grid grid-cols-4 px-4 py-2.5 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="record in records"
                >
                    <!-- Order Id, Created, Status Section -->
                    <div class="">
                        <div class="flex gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <p
                                    class="text-base text-gray-800 dark:text-white font-semibold"
                                >
                                    @{{ "@lang('admin::app.sales.orders.index.datagrid.id')".replace(':id', record.increment_id) }}
                                </p>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
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
                                    v-text="record.status"
                                >
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount, Pay Via, Channel -->
                    <div class="">
                        <div class="flex flex-col gap-1.5">
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @{{ $admin.formatPrice(record.base_grand_total) }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.orders.index.datagrid.pay-by', ['method' => ''])@{{ record.method }}
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.channel_name"
                            >
                            </p>
                        </div>
                    </div>

                    <!-- Custoemr, Email, Location Section -->
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

                    <div class="flex gap-x-2 justify-end items-center">
                        <a :href="`{{ route('admin.sales.orders.view', '') }}/${record.id}`">
                            <span class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"></span>
                        </a>
                    </div>
                </div>

                <div v-else class="table-responsive grid w-full">
                    <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/empty-placeholders/orders.svg') }}"
                            class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                        />

                        <div class="flex flex-col items-center">
                            <p class="text-base text-gray-400 font-semibold">
                                @lang('admin::app.customers.customers.view.datagrid.orders.empty-order')
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Datagrid Body Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
            </template>
        </template>
    </x-admin::datagrid>
</div>
