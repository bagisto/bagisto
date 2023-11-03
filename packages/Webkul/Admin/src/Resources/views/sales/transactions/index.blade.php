<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.transactions.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('admin::app.sales.transactions.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">

            <!-- Export Modal -->
            <x-admin::datagrid.export src="{{ route('admin.sales.transactions.index') }}"></x-admin::datagrid.export>
        </div>
    </div>

    <v-transaction-drawer></v-transaction-drawer>

    <!-- Transaction View Component -->
    @pushOnce('scripts')
        <script type="text/x-template" id="v-transaction-drawer-template">

            <x-admin::datagrid
                src="{{ route('admin.sales.transactions.index') }}"
                :isMultiRow="true"
                ref="transaction_data"
            >
                @php
                    $hasPermission = bouncer()->hasPermission('sales.transactions.view');
                @endphp

                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div
                        class="row grid grid-cols-{{ $hasPermission ? '8' : '7' }} grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold"
                        :style="'grid-template-columns: repeat({{ $hasPermission ? '8' : '7' }} , minmax(0, 1fr));'"
                    >
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'transaction_id', 'amount', 'invoice_id', 'order_id', 'status', 'created_at']"
                        >
                            <p class="text-gray-600 dark:text-gray-300">
                                <span class="[&>*]:after:content-['_/_']">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == columnGroup,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === columnGroup)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === columnGroup)?.label }}
                                    </span>
                                </span>

                                <!-- Filter Arrow Icon -->
                                <i
                                    class="ltr:ml-[5px] rtl:mr-[5px] text-[16px] text-gray-800 dark:text-white align-text-bottom"
                                    :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                    v-if="columnGroup.includes(applied.sort.column)"
                                ></i>
                            </p>
                        </div>

                        <!-- Actions -->
                        @if ($hasPermission)
                            <p class="flex gap-[10px] justify-end">
                                @lang('admin::app.components.datagrid.table.actions')
                            </p>
                        @endif
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="'grid-template-columns: repeat(' + (record.actions.length ? 8 : 7) + ', minmax(0, 1fr));'"
                    >
                        <!-- Id -->
                        <p
                            class="break-words"
                            v-text="record.id"
                        >
                        </p>

                        <!-- Transaction ID-->
                        <p
                            class="break-words"
                            v-text="record.transaction_id"
                        >
                        </p>

                        <!-- Amount -->
                        <p
                            class="break-words"
                            v-text="record.amount"
                        >
                        </p>

                        <!-- Invoice Id -->
                        <p
                            class="break-words"
                            v-text="record.invoice_id"
                        >
                        </p>

                        <!-- Order Id -->
                        <p
                            class="break-words"
                            v-text="record.order_id"
                        >
                        </p>

                        <!-- Status -->
                        <p
                            class="break-words"
                            v-text="record.status"
                        >
                        </p>

                        <!-- Date -->
                        <p
                            class="break-words"
                            v-text="record.created_at"
                        >
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a
                                v-if="record.actions.find(action => action.title === 'View')"
                                @click="view(record.actions.find(action => action.title === 'View')?.url)"
                            >
                                <span
                                    class="icon-sort-right text-[24px] ltr:ml-[4px] rtl:mr-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                                    role="button"
                                    tabindex="0"
                                >
                                </span>
                            </a>
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>

            <!-- Drawer content -->
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <x-admin::drawer ref="transaction">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid gap-y-[10px] py-[12px] dark:border-gray-800 max-sm:px-[15px]">
                            <p class="text-[20px] font-medium dark:text-white">
                                @lang('admin::app.sales.transactions.index.view.title')
                            </p>
                        </div>
                    </x-slot:header>

                    <!-- Drawer Content -->
                    <x-slot:content>
                        <div class="flex flex-col gap-[16px] px-[5px] py-[10px]">
                            <p class="text-[18px] text-gray-600 dark:text-gray-300 font-semibold">
                                @lang('admin::app.sales.transactions.index.view.transaction-data')
                            </p>

                            <div class="flex w-full justify-between p-[16px]">
                                <div class="flex flex-col gap-y-[6px]">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.transaction-id')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.order-id')
                                    </p>

                                    <p
                                        v-if="data.transaction.invoice_id"
                                        class="text-gray-600 dark:text-gray-300"
                                    >
                                        @lang('admin::app.sales.transactions.index.view.invoice-id')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.payment-method')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.status')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.created-at')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.amount')
                                    </p>
                                </div>

                                <div class="flex flex-col gap-y-[6px]">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction.transaction_id"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction.order_id"
                                    >
                                    </p>

                                    <p
                                        v-if="data.transaction.invoice_id"
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction.invoice_id"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction.payment_method"
                                    >
                                    </p>


                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction.status"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction.created_at"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction.amount"
                                    >
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>
            </div>
        </script>

        <script type="module">
            app.component('v-transaction-drawer', {
                template: '#v-transaction-drawer-template',

                data() {
                    return {
                        data: {},
                    }
                },

                methods: {
                    view(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.$refs.transaction.open(),

                                this.data = response.data.data
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });

                    },
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
