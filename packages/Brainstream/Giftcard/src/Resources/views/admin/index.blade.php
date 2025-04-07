<x-admin::layouts>
    <x-slot:title>
        @lang('giftcard::app.giftcard.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.settings.giftcard.create.before') !!}

    <v-giftcards>
        <div class="flex  gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('giftcard::app.giftcard.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Create Giftcard Button -->
                @if (bouncer()->hasPermission('settings.giftcard.create'))
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('giftcard::app.giftcard.create.create-btn')
                    </button>
                @endif
            </div>
        </div>

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
                :src="route('admin.giftcard.index')" 
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

                        <!-- Giftcardamount -->
                        <p v-text="record.giftcard_amount"></p>

                        <!-- Giftcardstatus -->
                        <p v-text="record.giftcard_status"></p>

                        <!-- Creationdate -->
                        <p v-text="record.creationdate"></p>

                        <!-- Expirationdate -->
                        <p v-text="record.expirationdate"></p>

                        <!-- Expirein -->
                        <p v-text="record.expirein"></p>

                        <!-- Sendername -->
                        <p v-text="record.sendername"></p>

                        <!-- Senderemail -->
                        <div class="flex flex-col">
                            <p class="whitespace-nowrap overflow-auto overflow-ellipsis" v-text="record.senderemail"></p>
                        </div>

                        <!-- Recipientname -->
                        <p v-text="record.recipientname"></p>
                        
                        <!-- Recipientemail -->
                        <div class="flex flex-col">
                            <p class="whitespace-nowrap overflow-auto overflow-ellipsis" v-text="record.recipientemail"></p>
                        </div>

                        <!-- Actions -->
                        @if (
                            bouncer()->hasPermission('settings.giftcard.edit') 
                            || bouncer()->hasPermission('settings.giftcarddelete')
                        )
                        <div class="flex justify-end">
                            @if (bouncer()->hasPermission('settings.giftcard.edit'))
                                <a @click="selectedGiftcards=1; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.index === 'edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif

                            @if (bouncer()->hasPermission('settings.giftcard.delete'))
                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif
                        </div>
                      @endif
                    </div>
                </template>
            </x-admin::datagrid>


            <!-- Modal Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="giftcardCreateForm"
                >
                    <x-admin::modal ref="giftcardUpdateOrCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-if="selectedGiftcards"
                            >
                                @lang('giftcard::app.giftcard.edit-giftcard')
                            </p>

                            <p
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('giftcard::app.giftcard.create-giftcard')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            {!! view_render_event('bagisto.admin.settings.giftcard.create.bwfore') !!}
                            <!-- Giftcard Number -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('giftcard::app.giftcard.giftcard_number')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="giftcard_number"
                                    disabled="disabled"
                                    style="cursor: not-allowed;"
                                    v-model="selectedGiftcard.giftcard_number"
                                />
                            </x-admin::form.control-group>

                        <!-- Giftcard Amount -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('giftcard::app.giftcard.amount')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="giftcard_amount"
                                rules="required|numeric"
                                :value="old('giftcard_amount')"
                                v-model="selectedGiftcard.giftcard_amount"
                                :label="trans('giftcard::app.giftcard.amount')"
                                :placeholder="trans('giftcard::app.giftcard.amount')"
                            />

                            <x-admin::form.control-group.error control-name="giftcard_amount" />
                        </x-admin::form.control-group>

                        <!-- Creation Date -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('giftcard::app.giftcard.creationdate')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="date"
                                name="creationdate"
                                rules="required"
                                :value="old('creationdate')"
                                v-model="selectedGiftcard.creationdate"
                                :label="trans('giftcard::app.giftcard.creationdate')"
                                :placeholder="trans('giftcard::app.giftcard.creationdate')"
                                :min="date('Y-m-d')"  
                            />

                            <x-admin::form.control-group.error control-name="creationdate" />
                        </x-admin::form.control-group>

                        <!-- Expiration Date -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('giftcard::app.giftcard.expirationdate')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="date"
                                name="expirationdate"
                                rules="required"
                                :value="old('expirationdate')"
                                v-model="selectedGiftcard.expirationdate"
                                :label="trans('giftcard::app.giftcard.expirationdate')"
                                :placeholder="trans('giftcard::app.giftcard.expirationdate')"
                                :min="date('Y-m-d')"  
                            />

                            <x-admin::form.control-group.error control-name="expirationdate" />
                        </x-admin::form.control-group>

                         <!-- Giftcard Quantity -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('giftcard::app.giftcard.giftcard_quantity')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="number"
                                    name="giftcard_quantity"
                                    rules="required|integer|min:1"
                                    :value="old('giftcard_quantity', 1)"
                                    v-model="selectedGiftcard.quantity"
                                    :label="trans('giftcard::app.giftcard.giftcard_quantity')"
                                    :placeholder="trans('giftcard::app.giftcard.giftcard_quantity')"
                                />

                                <x-admin::form.control-group.error control-name="giftcard_quantity" />
                            </x-admin::form.control-group>

                        <!-- Giftcard Status -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('giftcard::app.giftcard.giftcard_status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="switch"
                                name="giftcard_status"
                                rules="required"
                                :value="1"
                                v-model="selectedGiftcard.giftcard_status"
                                :label="trans('giftcard::app.giftcard.giftcard_status')"
                                :placeholder="trans('giftcard::app.giftcard.giftcard_status')"
                                :checked="(bool) old('giftcard_status')"
                            />

                            <x-admin::form.control-group.error control-name="giftcard_status" />
                        </x-admin::form.control-group>

                        <!-- Sender Name -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('giftcard::app.giftcard.sendername')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="sendername"
                                rules="required"
                                :value="old('sendername')"
                                v-model="selectedGiftcard.sendername"
                                :label="trans('giftcard::app.giftcard.sendername')"
                                :placeholder="trans('giftcard::app.giftcard.sendername')"
                            />

                            <x-admin::form.control-group.error control-name="sendername" />
                        </x-admin::form.control-group>

                        {!! view_render_event('bagisto.admin.settings.giftcard.create.after') !!}
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <div class="flex gap-x-2.5 items-center">
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('giftcard::app.giftcard.save-btn')
                                </button>
                            </div>
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-giftcards', {
                template: '#v-giftcards-template',

                data() {
                    return {
                        selectedGiftcard: {},

                        selectedGiftcards: 0,
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
                    updateOrCreate(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.giftcardCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.giftcard.update') }}" : "{{ route('admin.giftcard.store') }}", formData)
                            .then((response) => {
                                this.$refs.giftcardUpdateOrCreateModal.close();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.selectedGiftcard = response.data;

                                this.$refs.giftcardUpdateOrCreateModal.toggle();
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    },
                }
            })
        </script>

    @endPushOnce
</x-admin::layouts>
