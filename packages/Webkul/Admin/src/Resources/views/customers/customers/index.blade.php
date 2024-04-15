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
                <!-- Included customer create blade file -->
                @if (bouncer()->hasPermission('customers.customers.create'))
                    {!! view_render_event('bagisto.admin.customers.customers.create.before') !!}

                    @include('admin::customers.customers.index.create')

                    <v-create-customer-form
                        ref="createCustomerComponent"
                        @customer-created="$refs.customerDatagrid.get()"
                    ></v-create-customer-form>

                    {!! view_render_event('bagisto.admin.customers.customers.create.after') !!}

                    <button
                        class="primary-button"
                        @click="$refs.createCustomerComponent.openModal()"
                    >
                        @lang('admin::app.customers.customers.index.create.create-btn')
                    </button>
                @endif
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.customers.customers.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.customers.customers.index') }}" ref="customerDatagrid" :isMultiRow="true">
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
</x-admin::layouts>
