<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.events.index.title')
    </x-slot:title>

    <v-events>
        <div class="flex gap-[16px] justify-between max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.email-marketing.events.index.title')
            </p>
    
            <div class="flex gap-x-[10px] items-center">
                <!-- Create Button -->
                <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                    @lang('admin::app.marketing.email-marketing.events.index.create-btn')
                </div>
            </div>
        </div>

        {{-- Added For Shimmer --}}
        <x-admin::datagrid src="{{ route('admin.events.index') }}"></x-admin::datagrid>
    </v-events>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-events-template"
        >
            <div class="flex gap-[16px] justify-between max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.marketing.email-marketing.events.index.title')
                </p>
        
                <div class="flex gap-x-[10px] items-center">
                    <!-- Create Button -->
                    <div
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        @click="id=0; $refs.emailEvents.toggle()"
                    >
                        @lang('admin::app.marketing.email-marketing.events.index.create-btn')
                    </div>
                </div>
            </div>

            <!-- Datagrid -->
            <x-admin::datagrid
                src="{{ route('admin.events.index') }}"
                ref="datagrid"
            >
                <!-- Datagrid Header -->
                <template #header="{ columns, records, sortPage}">
                    <div class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] text-gray-600 bg-gray-50 font-semibold">
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'id'))"
                        >
                            <div class="flex gap-[10px]">
                                <p class="text-gray-600">ID</p>
                            </div>
                        </div>
        
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'name'))"
                        >
                            <p class="text-gray-600">Name</p>
                        </div>
        
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'date'))"
                        >
                            <p class="text-gray-600">Date</p>
                        </div>
        
                        <div class="cursor-pointer flex justify-end">
                            <p class="text-gray-600">Actions</p>
                        </div>
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
                        <p v-text="record.name"></p>
        
                        <!-- Email -->
                        <p v-text="record.date"></p>
        
                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.id)">
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

            <!-- Email Events form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, createEmailEvents)">
                    <x-admin::modal ref="emailEvents">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                <span v-if="id">
                                    @lang('admin::app.marketing.email-marketing.events.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.marketing.email-marketing.events.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <!-- Event Name -->
                                <x-admin::form.control-group class="mb-4">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.email-marketing.events.index.create.name')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        rules="required"
                                        :label="trans('admin::app.marketing.email-marketing.events.index.create.name')"
                                        :placeholder="trans('admin::app.marketing.email-marketing.events.index.create.name')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
        
                                <!-- Event Description -->
                                <x-admin::form.control-group class="mb-4">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.email-marketing.events.index.create.description')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="description"
                                        :value="old('description')"
                                        rules="required"
                                        id="description"
                                        class="h-[100px]"
                                        :label="trans('admin::app.marketing.email-marketing.events.index.create.description')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error 
                                        control-name="description"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Event Date -->
                                <x-admin::form.control-group class="mb-4">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.email-marketing.events.index.create.date')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="date"
                                        class="cursor-pointer"
                                        :value="old('date')"
                                        rules="required"
                                        :label="trans('admin::app.marketing.email-marketing.events.index.create.date')"
                                        :placeholder="trans('admin::app.marketing.email-marketing.events.index.create.date')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="date"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
                        
                        <x-slot:footer>
                            <!-- Save Button -->
                            <button class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                                @lang('admin::app.marketing.email-marketing.events.index.create.save-btn')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-events', {
                template: '#v-events-template',

                data() {
                    return {
                        id: 0,
                    }
                },

                methods: {
                    createEmailEvents(params, { resetForm, setErrors }) {
                        if (params.id) {
                            this.$axios.post("{{ route('admin.events.update')}}", params)
                                .then((response) => {
                                    this.$refs.emailEvents.toggle();

                                    this.$refs.datagrid.get();

                                    this.$emitter.emit('add-flash', { type: 'success', message: 'Event Updated successfully' });
                                })
                                .catch(error => {
                                    if (error.response.status ==422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                            
                        } else {
                            this.$axios.post("{{ route('admin.events.store') }}", params)
                                .then((response) => {
                                    this.$refs.emailEvents.toggle();
    
                                    resetForm();
                                })
                                .catch(error => {
                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        }
                    },

                    editModal(id) {
                        this.$axios.get(`{{ route('admin.events.edit', '') }}/${id}`)
                            .then((response) => {

                                let values = {
                                    id: response.data.data.id,
                                    name: response.data.data.name,
                                    date: response.data.data.date,
                                    description: response.data.data.description,
                                };

                                this.$refs.emailEvents.toggle();

                                this.$refs.modalForm.setValues(values);
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    deleteModal(url) {
                        if (! confirm('Are you sure, you want to perform this action?')) {
                            return;
                        }

                        this.$axios.post(url, {
                            '_method': 'DELETE'
                        })
                            .then((response) => {
                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: 'Event Deleted successfully' });
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