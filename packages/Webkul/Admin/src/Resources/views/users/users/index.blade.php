<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.users.users.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.users.users.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            {{-- Create User Vue Component --}}
            <v-create-user-form></v-create-user-form>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-user-form-template">
            <div>
                <!-- User Create Button -->
                @if (bouncer()->hasPermission('settings.users.users.create'))
                    <button
                        type="button"
                        class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                        @click="$refs.customerCreateModal.open()"
                    >
                        @lang('admin::app.users.users.index.create.title')
                    </button>
                @endif

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- User Create Modal -->
                        <x-admin::modal ref="customerCreateModal">
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.users.users.index.create.title')
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Name -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.users.index.create.name')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            id="name"
                                            rules="required"
                                            :label="trans('admin::app.users.users.index.create.name')" 
                                            :placeholder="trans('admin::app.users.users.index.create.name')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="name"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Email -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.users.index.create.email')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="email"
                                            name="email"
                                            id="email"
                                            rules="required|email"
                                            label="{{ trans('admin::app.users.users.index.create.email') }}"
                                            placeholder="email@example.com"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="email"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Password -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.users.index.create.password')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="password"
                                            name="password"
                                            id="password" 
                                            ref="password"
                                            rules="required|min:6"
                                            :label="trans('admin::app.users.users.index.create.password')"
                                            :placeholder="trans('admin::app.users.users.index.create.password')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="password"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Confirm Password -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.users.index.create.confirm-password')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation" 
                                            rules="confirmed:@password"
                                            :label="trans('admin::app.users.users.index.create.password')"
                                            :placeholder="trans('admin::app.users.users.index.create.confirm-password')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="password_confirmation"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Role -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.users.index.create.role')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="role_id"
                                            rules="required"
                                            :label="trans('admin::app.users.users.index.create.role')"
                                            :placeholder="trans('admin::app.users.users.index.create.role')"
                                        >
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="role_id"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Status -->
                                    <x-admin::form.control-group class="!mb-[0px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.users.users.index.create.status')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="switch"
                                            name="status"
                                            :value="1"
                                            :checked="old('status')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="status"
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
                                        @lang('admin::app.users.users.index.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-user-form', {
                template: '#v-create-user-form-template',

                methods: {
                    create(params, { resetForm, setErrors }) {
                        this.$axios.post("{{ route('admin.users.store') }}", params)
                            .then((response) => {
                                this.$refs.customerCreateModal.close();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

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