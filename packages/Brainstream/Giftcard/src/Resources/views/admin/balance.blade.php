<x-admin::layouts>
    <x-slot:title>
        @lang('giftcard::app.giftcard.giftcard-balance')
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
                :src="route('admin.giftcard.balance')" 
                ref="datagrid">

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`">

                         <!-- Id -->
                         <p v-text="record.id"></p>

                         <!-- Giftcard Number -->
                         <p v-text="record.giftcard_number"></p>
 
                         <!-- Giftcard Amount -->
                         <p v-text="record.giftcard_amount"></p>
 
                         <!-- Used Giftcard Amount -->
                         <p v-text="record.used_giftcard_amount"></p>
 
                         <!-- Remaining Giftcard Amount -->
                         <p v-text="record.remaining_giftcard_amount"></p>

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
