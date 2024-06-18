<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.sales.transactions.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sales.transactions.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">

            <!-- Export Modal -->
            <x-admin::datagrid.export :src="route('admin.sales.transactions.index')" />

            <v-create-transaction-form>
                <button
                    type="button"
                    class="primary-button"
                >
                    @lang('admin::app.sales.transactions.index.create.create-transaction')
                </button>
            </v-create-transaction-form>
        </div>
    </div>

    <v-transaction-drawer ref="transactionDrawer"/>

    <!-- Transaction View Component -->
    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-transaction-drawer-template"
        >
            <x-admin::datagrid
                :src="route('admin.sales.transactions.index')"
                :isMultiRow="true"
                ref="datagrid"
            >
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <div
                            v-for="record in available.records"
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- ID -->
                            <p
                                class="break-words"
                                v-text="record.id"
                            >
                            </p>

                            <!-- Transaction ID -->
                            <p
                                class="break-words"
                                v-text="record.transaction_id"
                            >
                            </p>

                            <!-- Amount -->
                            <p class="break-words">
                                @{{ $admin.formatPrice(record.amount) }}
                            </p>

                            <!-- Invoice ID -->
                            <p
                                class="break-words"
                                v-text="record.invoice_id"
                            >
                            </p>

                            <!-- Order ID -->
                            <p
                                class="break-words"
                                v-text="record.order_id"
                            >
                            </p>

                            <!-- Status -->
                            <p
                                class="break-words"
                                v-html="record.status"
                            >
                            </p>

                            <!-- Date -->
                            <p
                                class="break-words"
                                v-text="record.created_at"
                            >
                            </p>

                            <!-- Actions -->
                            @if (bouncer()->hasPermission('sales.transactions.view'))
                                <div class="flex justify-end">
                                    <a
                                        v-if="record.actions.find(action => action.title === '@lang('admin::app.sales.transactions.index.datagrid.view')')"
                                        @click="view(record.actions.find(action => action.title === '@lang('admin::app.sales.transactions.index.datagrid.view')')?.url)"
                                    >
                                        <span
                                            class="icon-sort-right rtl:icon-sort-left cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                            role="button"
                                            tabindex="0"
                                        >
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </template>
                </template>
            </x-admin::datagrid>

            <!-- Drawer content -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <x-admin::drawer ref="transaction">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid gap-y-2.5 py-3 dark:border-gray-800 max-sm:px-4">
                            <p class="text-xl font-medium dark:text-white">
                                @lang('admin::app.sales.transactions.index.view.title')
                            </p>
                        </div>
                    </x-slot>

                    <!-- Drawer Content -->
                    <x-slot:content>
                        <div class="flex flex-col gap-4 px-1.5 py-2.5">
                            <p class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.transactions.index.view.transaction-data')
                            </p>

                            <div class="flex w-full justify-between p-4">
                                <div class="flex flex-col gap-y-1.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.transaction-id')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.transactions.index.view.order-id')
                                    </p>

                                    <p
                                        v-if="data.invoice_id"
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

                                <div class="flex flex-col gap-y-1.5">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.transaction_id"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.order_id"
                                    >
                                    </p>

                                    <p
                                        v-if="data.invoice_id"
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.invoice_id"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.payment_title"
                                    >
                                    </p>


                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.status"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.created_at ?? 'N/A'"
                                    >
                                    </p>

                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-text="data.amount"
                                    >
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-admin::drawer>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-create-transaction-form-template"
        >
            <div>
                <button
                    type="button"
                    class="primary-button"
                    @click="$refs.transactionModel.toggle()"
                >
                    @lang('admin::app.sales.transactions.index.create.create-transaction')
                </button>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, store)">
                        <!-- Customer Create Modal -->
                        <x-admin::modal ref="transactionModel">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('admin::app.sales.transactions.index.create.create-transaction')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <!-- Invoice Id -->
                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.sales.transactions.index.create.invoice-id')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="invoice_id"
                                        name="invoice_id"
                                        rules="required"
                                        :label="trans('admin::app.sales.transactions.index.create.invoice-id')"
                                        :placeholder="trans('admin::app.sales.transactions.index.create.invoice-id')"
                                    />

                                    <x-admin::form.control-group.error control-name="invoice_id" />
                                </x-admin::form.control-group>

                                <!-- Payment Method -->
                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.sales.transactions.index.create.payment-method')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="payment_method"
                                        name="payment_method"
                                        rules="required"
                                        :label="trans('admin::app.sales.transactions.index.create.payment-method')"
                                        :placeholder="trans('admin::app.sales.transactions.index.create.payment-method')"
                                    >
                                        <option
                                            v-for="paymentMethod in paymentMethods"
                                            :value="paymentMethod.method"
                                            v-text="paymentMethod.method_title"
                                        >
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="payment_method" />
                                </x-admin::form.control-group>

                                <!-- Amount -->
                                <x-admin::form.control-group class="mb-2.5 w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.sales.transactions.index.create.amount')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="amount"
                                        name="amount"
                                        rules="required"
                                        :label="trans('admin::app.sales.transactions.index.create.amount')"
                                        :placeholder="trans('admin::app.sales.transactions.index.create.amount')"
                                    />

                                    <x-admin::form.control-group.error control-name="amount" />
                                </x-admin::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex items-center gap-x-2.5">
                                    <!-- Save Button -->
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('admin::app.sales.transactions.index.create.save-transaction')
                                    </button>
                                </div>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
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

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
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

        <script type="module">
            app.component('v-create-transaction-form', {
                template: '#v-create-transaction-form-template',

                data() {
                    return {
                        paymentMethods: @json(payment()->getSupportedPaymentMethods()['payment_methods']),
                    };
                },

                methods: {
                    store(params, { setErrors, resetForm }) {
                        this.$axios.post('{{ route('admin.sales.transactions.store') }}', params)
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.$refs.transactionModel.toggle();

                                this.$parent.$refs.transactionDrawer.$refs.datagrid.get()

                                resetForm();
                            })
                            .catch((error) => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                                }
                            });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
