<v-create-group></v-create-group>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-group-template">
        <div>
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!--  Create Group Modal -->
                    <x-admin::modal ref="groupCreateModal">
                        <x-slot:toggle>
                            <!-- Group Create Button -->
                            @if (bouncer()->hasPermission('customers.groups.create'))
                                <button 
                                    type="button"
                                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                                >
                                   @lang('admin::app.customers.groups.create.add-group')
                                </button>
                            @endif
                        </x-slot:toggle>
        
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.customers.groups.create.add-group')
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px]">
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.groups.create.code')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        id="code"
                                        label="Code"
                                        rules="required"
                                        placeholder="Code"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
        
                                {!! view_render_event('bagisto.admin.customers.create.first_name.after') !!}
        
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.groups.create.name')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        id="last_name"
                                        :value="old('name')"
                                        label="Name"
                                        rules="required"
                                        placeholder="Name"
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
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.customers.groups.create.save-group')
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
                create(params, { resetForm }) {
                    this.$axios.post("{{ route('admin.groups.store') }}", params)
                        .then((response) => {
                            this.$refs.groupCreateModal.toggle();

                            resetForm();
                        })
                        .catch(error => {});
                }
            }
        })
    </script>
@endPushOnce
