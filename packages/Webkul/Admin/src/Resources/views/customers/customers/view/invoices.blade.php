<div class="p-4 bg-white dark:bg-gray-900  rounded box-shadow">
    <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
        @lang('admin::app.customers.customers.view.invoices.count', ['count' => count($customer->invoices)])
    </p>

    <x-admin::datagrid
        :src="route('admin.customers.customers.view', [
            'id'   => $customer->id,
            'type' => 'invoices'
        ])"
    >
        <!-- Datagrid Header -->
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading, available}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-4 grid-rows-1 items-center px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['increment_id'], ['created_at'], ['base_grand_total'], ['order_id']]"
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

        <template #body="{ columns, records, performAction, available, isLoading }">
            <template v-if="! isLoading">
                <div
                    v-if="available.meta.total"
                    class="flex justify-between items-center px-4 py-4 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="record in records"
                >
                    <div class="">
                        <div class="flex gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <!-- Id -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ "@lang('admin::app.customers.customers.view.invoices.increment-id')".replace(':increment_id', record.id) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="flex gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <!-- Created At -->
                                <p class="text-gray-600 dark:text-gray-300">
                                    @{{ record.created_at }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="flex gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <!-- Created At -->
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-text="$admin.formatPrice(record.base_grand_total)"
                                >
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="flex gap-2.5">
                            <div class="flex flex-col gap-1.5">
                                <!-- Created At -->
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-text="`# ${record.order_id}`"
                                >
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- View the order -->
                    <div class="">
                        <div class="flex flex-col gap-1.5">
                            <a
                                :href="`{{ route('admin.sales.invoices.view', '') }}/${record.id}`"
                                class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                            >
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="table-responsive grid w-full"
                >
                    <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/settings/invoice.svg') }}"
                            class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                        />

                        <div class="flex flex-col items-center">
                            <p class="text-base text-gray-400 font-semibold">
                                @lang('admin::app.customers.customers.view.datagrid.invoices.empty-invoice')
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