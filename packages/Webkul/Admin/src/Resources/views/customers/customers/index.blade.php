<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.customers.customers.index.title')
    </x-slot>

    <div class="flex justify-between items-center">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.customers.customers.index.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            <!-- Export Modal -->
            <x-admin::datagrid.export src="{{ route('admin.customers.customers.index') }}" />

            <div class="flex gap-x-2.5 items-center">
                <!-- Customer Create Vue Component -->

                {!! view_render_event('admin.customers.customers.create.before') !!}

                <v-create-customer-form>
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.customers.customers.index.create.create-btn')
                    </button>
                </v-create-customer-form>

                {!! view_render_event('admin.customers.customers.create.after') !!}

            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.customers.customers.index') }}" ref="customer_data" :isMultiRow="true">
        @php 
            $hasPermission = bouncer()->hasPermission('customers.customers.edit') || bouncer()->hasPermission('customers.customers.delete');
        @endphp

        <!-- Datagrid Header -->
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center px-4 py-2.5 border-b dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['full_name', 'email', 'phone'], ['status', 'gender', 'group'], ['revenue', 'order_count', 'address_count']]"
                    >
                        @if ($hasPermission)
                            <label
                                class="flex gap-1 items-center w-max cursor-pointer select-none"
                                for="mass_action_select_all_records"
                                v-if="! index"
                            >
                                <input
                                    type="checkbox"
                                    name="mass_action_select_all_records"
                                    id="mass_action_select_all_records"
                                    class="hidden peer"
                                    :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                    @change="selectAllRecords"
                                >

                                <span
                                    class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                    :class="[
                                        applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:text-blue-600' : (
                                            applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:text-blue-600' : ''
                                        ),
                                    ]"
                                >
                                </span>
                            </label>
                        @endif

                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === column)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span>

                            <i
                                class="ltr:ml-1.5 rtl:mr-1.5 text-base  text-gray-800 dark:text-white align-text-bottom"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>

            <!-- Datagrid Head Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
            </template>
        </template>

        <!-- Datagrid Body -->
        <template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading }">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[minmax(150px,_2fr)_1fr_1fr] px-4 py-2.5 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="record in records"
                >
                    <div class="flex gap-2.5">
                        @if ($hasPermission)
                            <input
                                type="checkbox"
                                :name="`mass_action_select_record_${record.customer_id}`"
                                :id="`mass_action_select_record_${record.customer_id}`"
                                :value="record.customer_id"
                                class="hidden peer"
                                v-model="applied.massActions.indices"
                                @change="setCurrentSelectionMode"
                            >

                            <label
                                class="icon-uncheckbox rounded-md text-2xl cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                                :for="`mass_action_select_record_${record.customer_id}`"
                            >
                            </label>
                        @endif

                        <div class="flex flex-col gap-1.5">
                            <p
                                class="text-base text-gray-800 dark:text-white font-semibold"
                                v-text="record.full_name"
                            >
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.email"
                            >
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.phone ?? 'N/A'"
                            >
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <div class="flex gap-1.5">
                            <span
                                :class="{
                                    'label-canceled': record.status == '',
                                    'label-active': record.status === 1,
                                }"
                            >
                                @{{ record.status ? '@lang('admin::app.customers.customers.index.datagrid.active')' : '@lang('admin::app.customers.customers.index.datagrid.inactive')' }}
                            </span>

                            <span
                                :class="{
                                    'label-canceled': record.is_suspended === 1,
                                }"
                            >
                                @{{ record.is_suspended ?  '@lang('admin::app.customers.customers.index.datagrid.suspended')' : '' }}
                            </span>
                        </div>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.gender ?? 'N/A'"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.group ?? 'N/A'"
                        >
                        </p>
                    </div>

                    <div class="flex gap-x-4 justify-between items-center">
                        <div class="flex flex-col gap-1.5">
                            <p
                                class="text-base text-gray-800 dark:text-white font-semibold"
                                v-text="$admin.formatPrice(record.revenue)"
                            >
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.index.datagrid.order')".replace(':order', record.order_count) }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.index.datagrid.address')".replace(':address', record.address_count) }}
                            </p>
                        </div>

                        <div class="flex items-center">
                            <a
                                class="icon-login text-2xl ltr:ml-1 rtl:mr-1 p-1.5 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                                :href=`{{ route('admin.customers.customers.login_as_customer', '') }}/${record.customer_id}`
                                target="_blank"
                            >
                            </a>

                            <a
                                class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                                :href=`{{ route('admin.customers.customers.view', '') }}/${record.customer_id}`
                            >
                            </a>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Datagrid Body Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
            </template>
        </template>
    </x-admin::datagrid>

    {!! view_render_event('bagisto.admin.customers.customers.list.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-customer-form-template">
            <div>
                <!-- Create Button -->
                @if (bouncer()->hasPermission('customers.customers.create'))
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.customerCreateModal.toggle()"
                    >
                        @lang('admin::app.customers.customers.index.create.create-btn')
                    </button>
                @endif

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Customer Create Modal -->
                        <x-admin::modal ref="customerCreateModal">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p class="text-lg text-gray-800 dark:text-white font-bold">
                                    @lang('admin::app.customers.customers.index.create.title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                {!! view_render_event('bagisto.admin.customers.create.before') !!}

                                <div class="flex gap-4 max-sm:flex-wrap">
                                    <!-- First Name -->
                                    <x-admin::form.control-group class="w-full mb-2.5">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.customers.customers.index.create.first-name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="first_name"
                                            name="first_name"
                                            rules="required"
                                            :label="trans('admin::app.customers.customers.index.create.first-name')"
                                            :placeholder="trans('admin::app.customers.customers.index.create.first-name')"
                                        />

                                        <x-admin::form.control-group.error control-name="first_name" />
                                    </x-admin::form.control-group>

                                    <!-- Last Name -->
                                    <x-admin::form.control-group class="w-full mb-2.5">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.customers.customers.index.create.last-name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="last_name"
                                            name="last_name"
                                            rules="required"
                                            :label="trans('admin::app.customers.customers.index.create.last-name')"
                                            :placeholder="trans('admin::app.customers.customers.index.create.last-name')"
                                        />

                                        <x-admin::form.control-group.error control-name="last_name" />
                                    </x-admin::form.control-group>
                                </div>

                                <!-- Email -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.customers.index.create.email')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="email"
                                        id="email"
                                        name="email"
                                        rules="required|email"
                                        :label="trans('admin::app.customers.customers.index.create.email')"
                                        placeholder="email@example.com"
                                    />

                                    <x-admin::form.control-group.error control-name="email" />
                                </x-admin::form.control-group>

                                <!-- Contact Number -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.customers.index.create.contact-number')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        rules="integer"
                                        :label="trans('admin::app.customers.customers.index.create.contact-number')"
                                        :placeholder="trans('admin::app.customers.customers.index.create.contact-number')"
                                    />

                                    <x-admin::form.control-group.error control-name="phone" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.customers.index.create.date-of-birth')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="date"
                                        id="dob"
                                        name="date_of_birth"
                                        :label="trans('admin::app.customers.customers.index.create.date-of-birth')"
                                        :placeholder="trans('admin::app.customers.customers.index.create.date-of-birth')"
                                    />

                                    <x-admin::form.control-group.error control-name="date_of_birth" />
                                </x-admin::form.control-group>

                                <div class="flex gap-4 max-sm:flex-wrap">
                                    <!-- Gender -->
                                    <x-admin::form.control-group class="w-full">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.customers.customers.index.create.gender')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="gender"
                                            name="gender"
                                            rules="required"
                                            :label="trans('admin::app.customers.customers.index.create.gender')"
                                        >
                                            <option value="">
                                                @lang('admin::app.customers.customers.index.create.select-gender')
                                            </option>

                                            <option value="Male">
                                                @lang('admin::app.customers.customers.index.create.male')
                                            </option>

                                            <option value="Female">
                                                @lang('admin::app.customers.customers.index.create.female')
                                            </option>

                                            <option value="Other">
                                                @lang('admin::app.customers.customers.index.create.other')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="gender" />
                                    </x-admin::form.control-group>

                                    <!-- Customer Group -->
                                    <x-admin::form.control-group class="w-full">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.customers.index.create.customer-group')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            id="customerGroup"
                                            name="customer_group_id"
                                            :label="trans('admin::app.customers.customers.index.create.customer-group')"
                                            ::value="groups[0]?.id"
                                        >
                                            <option 
                                                v-for="group in groups" 
                                                :value="group.id"
                                                selected
                                            > 
                                                @{{ group.name }} 
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="customer_group_id" />
                                    </x-admin::form.control-group>
                                </div>

                                {!! view_render_event('bagisto.admin.customers.create.after') !!}
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-2.5 items-center">
                                    <!-- Save Button -->
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('admin::app.customers.customers.index.create.save-btn')
                                    </button>
                                </div>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-customer-form', {
                template: '#v-create-customer-form-template',

                data() {
                    return {
                        groups: @json($groups),
                    };
                },

                methods: {
                    create(params, { resetForm, setErrors }) {
                        this.$axios.post("{{ route('admin.customers.customers.store') }}", params)
                            .then((response) => {
                                this.$refs.customerCreateModal.close();

                                this.$root.$refs.customer_data.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

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
