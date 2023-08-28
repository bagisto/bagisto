<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.users.users.index.title')
    </x-slot:title>

    <v-users>
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.users.users.index.title')
            </p>
    
            <div class="flex gap-x-[10px] items-center">
                {{-- Create User Button --}}
                <button
                    type="button"
                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                >
                    @lang('admin::app.users.users.index.create.title')
                </button>
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid></x-admin::shimmer.datagrid>
    </v-users>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-users-template">

            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.users.users.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
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
                </div>
            </div>

            <!-- Datagrid -->
            <x-admin::datagrid
                :src="route('admin.settings.users.index')"
                ref="datagrid"
            >
                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div class="row grid grid-cols-6 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] border-gray-300 text-gray-600 bg-gray-50 font-semibold">
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['user_id', 'user_name', 'status', 'email', 'role_name']"
                        >
                            <p class="text-gray-600">
                                <span class="[&>*]:after:content-['_/_']">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 font-medium': applied.sort.column == columnGroup,
                                            'cursor-pointer': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
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
                                    class="ml-[5px] text-[16px] text-gray-800 align-text-bottom"
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
                        style="grid-template-columns: repeat(6, 1fr);"
                    >
                        <!-- Id -->
                        <p v-text="record.user_id"></p>

                        <!-- User Profile -->
                        <p>
                            <div class="flex gap-[10px] items-center">
                                <div class="inline-block w-[36px] h-[36px] rounded-full border-3 border-gray-800 align-middle text-center mr-2 overflow-hidden">
                                    <img
                                        class="w-[36px] h-[36px]"
                                        :src="record.user_img"
                                        alt="record.user_name"
                                    />
                                </div>
        
                                <div
                                    class="text-sm"
                                    v-text="record.user_name"
                                >
                                </div> 
                            </div>
                        </p>

                        <!-- Status -->
                        <p v-text="record.status"></p>

                        <!-- Email -->
                        <p v-text="record.email"></p>

                        <!-- Role -->
                        <p v-text="record.role_name"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.user_id)">
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

            <!-- Modal Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- User Create Modal -->
                    <x-admin::modal ref="customerCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                <span v-if="id">
                                    @lang('admin::app.users.users.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.users.users.index.create.title')
                                </span>
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <!-- Name -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
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
                                    <x-admin::form.control-group.label class="required">
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
                                    <x-admin::form.control-group.label class="required">
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
                                    <x-admin::form.control-group.label class="required">
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
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.users.users.index.create.role')
                                    </x-admin::form.control-group.label>

                                    <!-- For New Form -->
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="role_id"
                                        v-if="! id"
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

                                    <!-- For Edit Form -->
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="role_id"
                                        v-if="id"
                                        rules="required"
                                        ::value="user.role_id"
                                        :label="trans('admin::app.users.users.index.create.role')"
                                        :placeholder="trans('admin::app.users.users.index.create.role')"
                                    >
                                        <option
                                            v-for="role in roles"
                                            :value="role.id"
                                        >
                                            @{{ role.name }}
                                        </option>                                        

                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="role_id"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Status -->
                                <x-admin::form.control-group v-if="! id" class="!mb-[0px]">
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

                                <x-admin::form.control-group v-if="id" class="!mb-[0px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.users.users.index.create.status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        ::checked="user.status ?? old('status')"
                                        ::value="user.status ?? 1"
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
        </script>

        <script type="module">
            app.component('v-users', {
                template: '#v-users-template',

                data() {
                    return {
                        roles: {},
                        user: {},
                        id: 0,
                    }
                },

                methods: {
                    create(params, { resetForm, setErrors }) {
                        console.log(params);
                        if (params.id) {
                            this.$axios.post("{{ route('admin.settings.users.update') }}", params)
                                .then((response) => {
                                    this.$refs.customerCreateModal.close();

                                    this.$refs.datagrid.get();

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                    resetForm();
                                })
                                .catch(error => {
                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        } else {
                            this.$axios.post("{{ route('admin.settings.users.store') }}", params)
                                .then((response) => {
                                    this.$refs.customerCreateModal.close();

                                    this.$refs.datagrid.get();

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

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
                        this.$axios.get(`{{ route('admin.settings.users.edit', '') }}/${id}`)
                            .then((response) => {
                                let values = {
                                    id: response.data.data.user.id,
                                    name: response.data.data.user.name,
                                    email: response.data.data.user.email,
                                    status: response.data.data.user.sttus,
                                };

                                this.roles = response.data.data.roles;

                                this.user = response.data.data.user;

                                this.$refs.customerCreateModal.toggle();

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

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
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