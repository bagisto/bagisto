<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.events.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.marketing.email-marketing.events.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <v-create-email-events><v-create-email-events/>
        </div>
    </div>

    <x-admin::datagrid src="{{ route('admin.events.index') }}"></x-admin::datagrid>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-create-email-events-template"
        >
            <div>
                <!-- Create Button -->
                <div
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                    @click="$refs.emailEvents.toggle()"
                >
                    @lang('admin::app.marketing.email-marketing.events.index.create-btn')
                </div>

                <!-- Email Events form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, createEmailEvents)">
                        <x-admin::modal ref="emailEvents">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.marketing.email-marketing.events.create.general')
                                </p>
                            </x-slot:header>

                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Event Name -->
                                    <x-admin::form.control-group class="mb-4">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.email-marketing.events.create.name')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            rules="required"
                                            :value="old('name')"
                                            :label="trans('admin::app.marketing.email-marketing.events.create.name')"
                                            :placeholder="trans('admin::app.marketing.email-marketing.events.create.name')"
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
                                            @lang('admin::app.marketing.email-marketing.events.create.description')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="textarea"
                                            name="description"
                                            id="description"
                                            class="h-[100px]"
                                            :value="old('description')"
                                            rules="required"
                                            :label="trans('admin::app.marketing.email-marketing.events.create.description')"
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
                                            @lang('admin::app.marketing.email-marketing.events.create.date')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="date"
                                            name="date"
                                            class="cursor-pointer"
                                            :value="old('date')"
                                            rules="required"
                                            :label="trans('admin::app.marketing.email-marketing.events.create.date')"
                                            :placeholder="trans('admin::app.marketing.email-marketing.events.create.date')"
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
                                    @lang('admin::app.marketing.email-marketing.events.create.save-btn')
                                </button>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-email-events', {
                template: '#v-create-email-events-template',

                methods: {
                    createEmailEvents(params, { resetForm, setErrors }) {
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
                    },
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>