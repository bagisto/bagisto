<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.customers.groups.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.customers.groups.index.title')
        </p>
        
        <div class="flex gap-x-[10px] items-center">
            {{-- Create a new Group --}}
            <v-create-group></v-create-group>
        </div>
    </div>
    
    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-group-template">
            <div>
                @if (bouncer()->hasPermission('customers.groups.create'))
                    <button 
                        type="button"
                        class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                        @click="$refs.groupCreateModal.toggle()"
                    >
                        @lang('admin::app.customers.groups.index.modal.create.create-btn')
                    </button>
                @endif

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        {{-- Create Group Modal --}}
                        <x-admin::modal ref="groupCreateModal">          
                            <x-slot:header>
                                {{-- Modal Header --}}
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.customers.groups.index.modal.create.title')
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                {{-- Modal Content --}}
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.groups.index.modal.create.code')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="code"
                                            id="code"
                                            rules="required"
                                            :label="trans('admin::app.customers.groups.index.modal.create.code')"
                                            :placeholder="trans('admin::app.customers.groups.index.modal.create.code')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
            
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.groups.index.modal.create.name')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            id="last_name"
                                            rules="required"
                                            :label="trans('admin::app.customers.groups.index.modal.create.name')"
                                            :placeholder="trans('admin::app.customers.groups.index.modal.create.name')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="name"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>
            
                            <x-slot:footer>
                                {{-- Modal Submission --}}
                                <div class="flex gap-x-[10px] items-center">
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.customers.groups.index.modal.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-group', {
                template: '#v-create-group-template',

                methods: {
                    create(params, { resetForm, setErrors  }) {
                        this.$axios.post("{{ route('admin.groups.store') }}", params)
                            .then((response) => {
                                this.$refs.groupCreateModal.toggle();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                resetForm();
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