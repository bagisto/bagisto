<x-admin::layouts>
    <x-slot:title>
        @lang('giftcard::app.giftcard.giftcard-payment')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.giftcard.create.before') !!}
    <v-giftcards>
        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid />
    </v-giftcards>

    {!! view_render_event('bagisto.admin.settings.giftcard.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-giftcards-template"
        >
            <x-admin::datagrid 
                :src="route('admin.giftcard.payment')" 
                ref="datagrid">

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`">

                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Giftcardnumber -->
                        <p v-text="record.giftcard_number"></p>

                        <!-- OrderID -->
                        <div class="flex flex-col">
                            <p class="whitespace-nowrap overflow-auto overflow-ellipsis" v-text="record.order_id"></p>
                        </div>
                        
                        <!-- PaymentID -->
                        <div class="flex flex-col">
                            <p class="whitespace-nowrap overflow-auto overflow-ellipsis" v-text="record.payment_id"></p>
                        </div>

                        <!-- PayerID -->
                        <div class="flex flex-col">
                            <p class="whitespace-nowrap overflow-auto overflow-ellipsis" v-text="record.payer_id"></p>
                        </div>

                        <!-- PayerEmail -->
                        <div class="flex flex-col">
                            <p class="whitespace-nowrap overflow-auto overflow-ellipsis" v-text="record.payer_email"></p>
                        </div>

                        <!-- Amount -->
                        <p v-text="record.amount"></p>

                        <!-- Currency -->
                        <p v-text="record.currency"></p>

                        <!-- Status -->
                        <p v-text="record.status"></p>

                        <!-- Paymenttype -->
                        <p v-text="record.payment_type"></p>

                    </div>
                </template>
            </x-admin::datagrid>
        </script>

        <script type="module">
            app.component('v-giftcards', {
                template: '#v-giftcards-template',

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
            })
        </script>
    @endPushOnce
</x-admin::layouts>
