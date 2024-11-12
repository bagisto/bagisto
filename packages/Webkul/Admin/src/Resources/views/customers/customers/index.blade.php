<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.customers.customers.index.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.customers.customers.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Export Modal -->
            <x-admin::datagrid.export src="{{ route('admin.customers.customers.index') }}" />

            <div class="flex items-center gap-x-2.5">
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

    <x-admin::datagrid
        :src="route('admin.customers.customers.index')"
        ref="customerDatagrid"
        :isMultiRow="true"
    >
        @php
            $hasPermission = bouncer()->hasPermission('customers.customers.edit') || bouncer()->hasPermission('customers.customers.delete');
        @endphp

        <template #header="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
            </template>

            <template v-else>
                <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center border-b px-4 py-2.5 dark:border-gray-800">
                    <div
                        class="flex select-none items-center gap-2.5"
                        v-for="(columnGroup, index) in [['full_name', 'email', 'phone'], ['status', 'gender', 'group', 'customer_id'], ['revenue', 'order_count', 'address_count']]"
                    >
                        @if ($hasPermission)
                            <label
                                class="flex w-max cursor-pointer select-none items-center gap-1"
                                for="mass_action_select_all_records"
                                v-if="! index"
                            >
                                <input
                                    type="checkbox"
                                    name="mass_action_select_all_records"
                                    id="mass_action_select_all_records"
                                    class="peer hidden"
                                    :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                    @change="selectAll"
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
                                            'font-medium text-gray-800 dark:text-white': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            available.columns.find(columnTemp => columnTemp.index === column)?.sortable ? sort(available.columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ available.columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span>

                            <i
                                class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>
        </template>

        <template #body="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
            </template>

            <template v-else>
                <div
                    class="row grid grid-cols-[minmax(150px,_2fr)_1fr_1fr] border-b px-4 py-2.5 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                    v-for="record in available.records"
                >
                    <div class="flex gap-2.5">
                        @if ($hasPermission)
                            <input
                                type="checkbox"
                                :name="`mass_action_select_record_${record.customer_id}`"
                                :id="`mass_action_select_record_${record.customer_id}`"
                                :value="record.customer_id"
                                class="peer hidden"
                                v-model="applied.massActions.indices"
                                @change="setCurrentSelectionMode"
                            >

                            <label
                                class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"
                                :for="`mass_action_select_record_${record.customer_id}`"
                            >
                            </label>
                        @endif

                        <div class="flex flex-col gap-1.5">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @{{ record.full_name }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ record.email }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ record.phone ?? 'N/A' }}
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

                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ record.gender ?? 'N/A' }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ record.group ?? 'N/A' }}
                        </p>

                        <p class="text-gray-600 dark:text-gray-300">
                            @{{ "@lang('admin::app.customers.customers.index.datagrid.id-value')".replace(':id', record.customer_id) }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-x-4">
                        <div class="flex flex-col gap-1.5">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @{{ $admin.formatPrice(record.revenue) }}
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
                                class="icon-login cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                :href=`{{ route('admin.customers.customers.login_as_customer', '') }}/${record.customer_id}`
                                target="_blank"
                            >
                            </a>

                            <a
                                class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                                :href=`{{ route('admin.customers.customers.view', '') }}/${record.customer_id}`
                            >
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </template>
    </x-admin::datagrid>

    {!! view_render_event('bagisto.admin.customers.customers.list.after') !!}
</x-admin::layouts>
