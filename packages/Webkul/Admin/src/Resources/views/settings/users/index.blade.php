<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.settings.users.index.title')
    </x-slot:title>

    <v-users>
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.users.index.title')
            </p>
    
            <div class="flex gap-x-[10px] items-center">
                {{-- Create User Button --}}
                <button
                    type="button"
                    class="primary-button"
                >
                    @lang('admin::app.settings.users.index.create.title')
                </button>
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid/>
    </v-users>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-users-template">
            <div class="flex justify-between items-center">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.settings.users.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <!-- User Create Button -->
                    @if (bouncer()->hasPermission('settings.users.users.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="resetForm();$refs.userUpdateOrCreateModal.open()"
                        >
                            @lang('admin::app.settings.users.index.create.title')
                        </button>
                    @endif
                </div>
            </div>

            <!-- Datagrid -->
            <x-admin::datagrid
                src="{{ route('admin.settings.users.index') }}"
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
                        style="grid-template-columns: repeat(6, 1fr);"
                    >
                        <!-- Id -->
                        <p v-text="record.user_id"></p>

                        <!-- User Profile -->
                        <p>

                            <div 
                                class="flex gap-[10px] items-center"
                            >
                                <div 
                                    class="inline-block w-[36px] h-[36px] rounded-full border-3 border-gray-800 align-middle text-center mr-2 overflow-hidden"
                                    v-if="record.user_img"
                                >
                                    <img
                                        class="w-[36px] h-[36px]"
                                        :src="record.user_img"
                                        alt="record.user_name"
                                    />
                                </div>
        
                                <div 
                                    class="profile-info-icon"
                                    v-else
                                >
                                    <button
                                        class="flex justify-center items-center w-[36px] h-[36px] bg-blue-400 rounded-full text-[14px] text-white font-semibold cursor-pointer leading-6 transition-all hover:bg-blue-500 focus:bg-blue-500">
                                        @{{ record.user_name[0].toUpperCase() }}
                                    </button>
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
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 max-sm:place-self-center"
                                    :title="record.actions['0'].title"
                                >
                                </span>
                            </a>

                            <a @click="deleteModal(record.actions['1']?.url)">
                                <span
                                    :class="record.actions['1'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 max-sm:place-self-center"
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
                <form 
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="userCreateForm"
                >
                    <!-- User Create Modal -->
                    <x-admin::modal ref="userUpdateOrCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p 
                                class="text-[18px] text-gray-800 font-bold"
                                v-if="isUpdating"
                            >
                                @lang('admin::app.settings.users.index.edit.title')
                            </p>    

                            <p 
                                class="text-[18px] text-gray-800 font-bold"
                                v-else
                            >
                                @lang('admin::app.settings.users.index.create.title')
                            </p>    
                            
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <!-- Name -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.users.index.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="id"
                                        v-model="data.user.id"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.users.index.create.name')" 
                                        :placeholder="trans('admin::app.settings.users.index.create.name')"
                                        v-model="data.user.name"
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
                                        @lang('admin::app.settings.users.index.create.email')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="email"
                                        name="email"
                                        id="email"
                                        rules="required|email"
                                        label="{{ trans('admin::app.settings.users.index.create.email') }}"
                                        placeholder="email@example.com"
                                        v-model="data.user.email"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="email"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <div class="flex gap-[16px]">
                                    <!-- Password -->
                                    <x-admin::form.control-group class="flex-1 mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.users.index.create.password')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="password"
                                            name="password"
                                            id="password" 
                                            ref="password"
                                            rules="required|min:6"
                                            :label="trans('admin::app.settings.users.index.create.password')"
                                            :placeholder="trans('admin::app.settings.users.index.create.password')"
                                            v-model="data.user.password"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="password"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
    
                                    <!-- Confirm Password -->
                                    <x-admin::form.control-group class="flex-1 mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.users.index.create.confirm-password')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation" 
                                            rules="confirmed:@password"
                                            :label="trans('admin::app.settings.users.index.create.password')"
                                            :placeholder="trans('admin::app.settings.users.index.create.confirm-password')"
                                            v-model="data.user.password_confirmation"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="password_confirmation"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>

                                <div class="flex gap-[16px]">

                                    <!-- Role -->
                                    <x-admin::form.control-group class="flex-1 w-full mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.users.index.create.role')
                                        </x-admin::form.control-group.label>

                                        <v-field
                                            name="role_id" 
                                            rules="required"
                                            label="@lang('admin::app.settings.users.index.create.role')"
                                            v-model="data.user.role_id"
                                        >
                                            <select
                                                name="role_id" 
                                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                                :class="[errors['options[sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                                v-model="data.user.role_id"
                                            >
                                                <option value="" disabled>@lang('admin::app.settings.taxes.categories.index.create.select')</option>

                                                <option 
                                                    v-for="role in roles"
                                                    :value="role.id"
                                                    :text="role.name"
                                                >
                                                </option>
                                            </select>
                                        </v-field>
            
                                        <x-admin::form.control-group.error
                                            control-name="role_id"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Status -->
                                    <x-admin::form.control-group class="w-full flex-1 !mb-[0px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.users.index.create.status')
                                        </x-admin::form.control-group.label>

                                        <div class="gap-[10px] w-full mt-[10px]">    
                                            <x-admin::form.control-group.control
                                                type="switch"
                                                name="status"
                                                ::checked="data.user.status"
                                                v-model="data.user.status"
                                            >
                                            </x-admin::form.control-group.control>
            
                                            <x-admin::form.control-group.error
                                                control-name="status"
                                            >
                                            </x-admin::form.control-group.error>
                                        </div>
                                    </x-admin::form.control-group>
                                </div>

                                <x-admin::form.control-group>
                                    <x-admin::media.images
                                        name="image"
                                        ::uploaded-images='data.images'
                                    >
                                    </x-admin::media.images>

                                    <p class="required my-3 text-[14px] text-gray-400">
                                        @lang('admin::app.settings.users.index.create.upload-image-info')
                                    </p>
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
                                    @lang('admin::app.settings.users.index.create.save-btn')
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
                        isUpdating: false,
                        
                        roles: @json($roles),
                        
                        data: {
                            user: {},
                            images: [],
                        },
                    }
                },

                methods: {
                    updateOrCreate(params, { setErrors }) {
                        let formData = new FormData(this.$refs.userCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.settings.users.update') }}" : "{{ route('admin.settings.users.store') }}", formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                }
                            })
                            .then((response) => {
                                this.$refs.userUpdateOrCreateModal.close();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                this.resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(id) {
                        this.isUpdating = true;

                        this.$axios.get(`{{ route('admin.settings.users.edit', '') }}/${id}`)
                            .then((response) => {
                                this.data = {
                                    ...response.data.data,
                                        images: response.data.data.user.image_url
                                        ? [{ id: 'image', url: response.data.data.user.image_url }]
                                        : [],
                                };

                                this.$refs.modalForm.setValues(response.data.data.user);

                                this.$refs.userUpdateOrCreateModal.toggle();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    deleteModal(url) {
                        if (! confirm('@lang('admin::app.settings.users.index.delete-warning')')) {
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
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    resetForm() {
                        this.isUpdating = false;
                        
                        this.data = {
                            user: {},
                            images: [],
                        };
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>