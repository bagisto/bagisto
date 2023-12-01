<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.marketing.communications.subscribers.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
            @lang('admin::app.marketing.communications.subscribers.index.title')
        </p>
    </div>

    <v-subscribers>
        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid/>
    </v-subscribers>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-subscribers-template">
            <div>
                <!-- DataGrid -->
                <x-admin::datagrid
                    src="{{ route('admin.marketing.communications.subscribers.index') }}"
                    ref="datagrid"
                >
                    <!-- DataGrid Body -->
                    <template #body="{ columns, records, performAction }">
                        <div
                            v-for="record in records"
                            class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Id -->
                            <p v-text="record.id"></p>

                            <!-- Status -->
                            <p v-text="record.status"></p>

                            <!-- Email -->
                            <p v-text="record.email"></p>

                            <!-- Actions -->
                            @if (
                                bouncer()->hasPermission('marketing.communications.subscribers.edit') 
                                || bouncer()->hasPermission('marketing.communications.subscribers.delete')
                            )
                                <div class="flex justify-end">
                                    @if (bouncer()->hasPermission('marketing.communications.subscribers.edit'))
                                        <a @click="editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                            <span
                                                :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                                class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                            >
                                            </span>
                                        </a>
                                    @endif

                                    @if (bouncer()->hasPermission('marketing.communications.subscribers.delete'))
                                        <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                            <span
                                                :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                                class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                            >
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </template>
                </x-admin::datagrid>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="modalForm"
                >
                    <form
                        @submit="handleSubmit($event, update)"
                        ref="subscriberCreateForm"
                    >
                        <!-- Create Group Modal -->
                        <x-admin::modal ref="groupCreateModal">
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                    @lang('admin::app.marketing.communications.subscribers.index.edit.title')
                                </p>
                            </x-slot:header>

                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <!-- Id -->
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="id"
                                    >
                                    </x-admin::form.control-group.control>

                                    <!-- Email -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.communications.subscribers.index.edit.email')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            v-model="selectedSubscriber.id"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="email"
                                            :value="old('email')"
                                            rules="required"
                                            class="mb-1 cursor-not-allowed"
                                            v-model="selectedSubscriber.email"
                                            :label="trans('admin::app.marketing.communications.subscribers.index.edit.email')"
                                            disabled
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="email"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Subscribed -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.communications.subscribers.index.edit.subscribed')
                                        </x-admin::form.control-group.label>

                                        @php
                                            $selectedOption = old('status');
                                        @endphp

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="is_subscribed"
                                            class="cursor-pointer mb-1"
                                            rules="required"
                                            v-model="selectedSubscriber.is_subscribed"
                                            :label="trans('admin::app.marketing.communications.subscribers.index.edit.subscribed')"
                                        >
                                            @foreach (['true', 'false'] as $state)
                                                <option
                                                    value="{{ $state == 'true' ? 1 : 0 }}"
                                                >
                                                    @lang('admin::app.marketing.communications.subscribers.index.edit.' . $state)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="is_subscribed"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>

                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-[10px] items-center">
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('admin::app.marketing.communications.subscribers.index.edit.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-subscribers', {
                template: '#v-subscribers-template',

                data() {
                    return {
                        selectedSubscriber: {},
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
                    update(params, { resetForm, setErrors  }) {
                        let formData = new FormData(this.$refs.subscriberCreateForm);

                        formData.append('_method', 'put');

                        this.$axios.post("{{ route('admin.marketing.communications.subscribers.update') }}", formData)
                        .then((response) => {
                            this.$refs.groupCreateModal.close();

                            this.$refs.datagrid.get();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status ==422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.selectedSubscriber = response.data.data;

                                this.$refs.groupCreateModal.toggle();
                            })
                            .catch(error => [
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message })
                            ]);
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
