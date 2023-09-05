<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.communications.subscribers.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.marketing.communications.subscribers.index.title')
        </p>
    </div>

    <v-subscribers>
        {{-- DataGrid Shimmer --}}
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
                    <!-- DataGrid Header -->
                    <template #header="{ columns, records, sortPage, applied}">
                        <div class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] border-gray-300 text-gray-600 bg-gray-50 font-semibold">
                            <div
                                class="flex gap-[10px] cursor-pointer"
                                v-for="(columnGroup, index) in ['id', 'status', 'email']"
                            >
                                <p class="text-gray-600">
                                    <span class="[&>*]:after:content-['_/_']">
                                        <span
                                            class="after:content-['/'] last:after:content-['']"
                                            :class="{
                                                'text-gray-800 font-medium': applied.sort.column == columnGroup,
                                                'cursor-pointer hover:text-gray-800': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
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
                                        class="ltr:ml-[5px] rtl:mr-[5px] text-[16px] text-gray-800 align-text-bottom"
                                        :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                        v-if="columnGroup.includes(applied.sort.column)"
                                    ></i>
                                </p>
                            </div>
    
                            <!-- Actions -->
                            <p class="flex gap-[10px] justify-end">
                                @lang('admin::app.components.datagrid.table.actions')
                            </p>
                        </div>
                    </template>
        
                    <!-- DataGrid Body -->
                    <template #body="{ columns, records }">
                        <div
                            v-for="record in records"
                            class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all hover:bg-gray-100"
                            style="grid-template-columns: repeat(4, 1fr);"
                        >
                            <!-- Id -->
                            <p v-text="record.id"></p>
            
                            <!-- Status -->
                            <p v-text="record.status"></p>
            
                            <!-- Email -->
                            <p v-text="record.email"></p>
            
                            <!-- Actions -->
                            <div class="flex justify-end">
                                <a @click="editModal(record.id)">
                                    <span
                                        :class="record.actions['0'].icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                        :title="record.actions['0'].title"
                                    >
                                    </span>
                                </a>
            
                                <a @click="deleteModal(record.actions['1']?.url)">
                                    <span
                                        :class="record.actions['1'].icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                        :title="record.actions['1'].title"
                                    >
                                    </span>
                                </a>
                            </div>
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
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.marketing.communications.subscribers.index.edit.title')
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
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

                    editModal(id) {

                        this.$axios.get(`{{ route('admin.marketing.communications.subscribers.edit', '') }}/${id}`)
                            .then((response) => {
                                this.selectedSubscriber = response.data.data;

                                this.$refs.groupCreateModal.toggle();
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    deleteModal(url) {
                        if (! confirm("@lang('admin::app.marketing.communications.subscribers.delete-warning')")) {
                            return;
                        }

                        this.$axios.post(url, {
                                '_method': 'DELETE'
                            })
                            .then((response) => {
                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    }
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>