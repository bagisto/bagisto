<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.sales.transactions.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('admin::app.sales.transactions.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">

            <v-create-transaction></v-create-transaction>

            <!-- Export Modal -->
            <x-admin::datagrid.export src="{{ route('admin.sales.transactions.index') }}"></x-admin::datagrid.export>
        </div>
    </div>



    <!-- Transaction Create Component -->
    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-transaction-template">
            <div>
                <!-- Transaction Create Button -->
                <button
                    type="button"
                    class="primary-button"
                    @click="$refs.transactionCreateModal.toggle()"
                >
                    @lang('Create Transaction')
                </button>

                <!-- Transaction Create Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Transaction Create Modal -->
                        <x-admin::modal ref="transactionCreateModal">
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                    @lang('Create Transaction')New
                                </p>
                            </x-slot:header>

                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">

                                    <!-- Invoice Id -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('Invoice Id')
                                        </x-admin::form.control-group.label>
                                    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="invoice_id"
                                            value="{{ old('invoice_id') }}"
                                            id="invoice_id"
                                            rules="required"
                                            label="Invoice Id"
                                            placeholder="Invoice Id"
                                        >
                                        </x-admin::form.control-group.control>
                                    
                                        <x-admin::form.control-group.error
                                            control-name="invoice_id"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Payment Method -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('Payment Method')
                                        </x-admin::form.control-group.label>
                                    
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="payment_method"
                                            id="payment-method"
                                            rules="required"
                                            label="Payment Method"
                                            placeholder="Payment Method"
                                        >
                                            <option value="">Select option</option>

                                            @foreach ($payment_methods["payment_methods"] as $paymentMethod)
                                                @if(in_array($paymentMethod["method"], ["cashondelivery", "moneytransfer"]))
                                                    <option value="{{ $paymentMethod["method"] }}">{{ $paymentMethod["method_title"] }}</option>
                                                @endif
                                            @endforeach

                                        </x-admin::form.control-group.control>
                                    
                                        <x-admin::form.control-group.error
                                            control-name="payment_method"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Amount -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required">
                                            @lang('Amount')
                                        </x-admin::form.control-group.label>
                                    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="amount" 
                                            value="{{ old('amount') }}"
                                            id="transaction-amount" 
                                            rules="required"
                                            label="Amount"
                                            placeholder="Amount"
                                        >
                                        </x-admin::form.control-group.control>
                                    
                                        <x-admin::form.control-group.error
                                            control-name="amount"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>

                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-[10px] items-center">
                                    <!-- Save Button -->
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('Save Transaction')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-transaction', {
                template: '#v-create-transaction-template',

                methods: {
                    create(params, {
                        resetForm,
                        setErrors
                    }) {

                        this.$axios.post("{{ route('admin.sales.transactions.store') }}", params)
                            .then((response) => {
                                this.$refs.transactionCreateModal.close();

                                this.$root.$refs.transaction_data.get();
                    
                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
