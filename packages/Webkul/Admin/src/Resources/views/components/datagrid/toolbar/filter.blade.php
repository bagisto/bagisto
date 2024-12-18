<v-datagrid-filter
    :src="src"
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @applyFilters="filter"
    @applySavedFilter="applySavedFilter"
>
    {{ $slot }}
</v-datagrid-filter>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-filter-template"
    >
        <!-- Empty slot for right toolbar before -->
        <slot name="right-toolbar-left-before"></slot>

        <slot
            name="filter"
            :available="available"
            :applied="applied"
            :filters="filters"
            :apply-filters="applyFilters"
            :apply-column-values="applyColumnValues"
            :find-applied-column="findAppliedColumn"
            :has-any-applied-column-values="hasAnyAppliedColumnValues"
            :get-applied-column-values="getAppliedColumnValues"
            :remove-applied-column-value="removeAppliedColumnValue"
            :remove-applied-column-all-values="removeAppliedColumnAllValues"
        >
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.toolbar.filter />
            </template>

            <template v-else>
                <x-admin::drawer
                    width="350px"
                    ref="filterDrawer"
                >
                    <x-slot:toggle>
                        <div>
                            <div
                                class="relative inline-flex w-full max-w-max cursor-pointer select-none appearance-none items-center justify-between gap-x-1 rounded-md border bg-white px-1 py-1.5 text-center text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 ltr:pl-3 ltr:pr-5 rtl:pl-5 rtl:pr-3"
                                :class="{'[&>*]:text-blue-600 [&>*]:dark:text-white': hasAnyAppliedColumn() }"
                            >
                                <span class="icon-filter text-2xl"></span>

                                <span>
                                    @lang('admin::app.components.datagrid.toolbar.filter.title')
                                </span>

                                <span
                                    class="icon-dot absolute right-2 top-1.5 text-sm font-bold"
                                    v-if="hasAnyAppliedColumn()"
                                >
                                </span>
                            </div>

                            <div class="z-10 hidden w-full divide-y divide-gray-100 rounded bg-white shadow dark:bg-gray-900">
                            </div>
                        </div>
                    </x-slot>

                    <x-slot:header>
                        <!-- Apply Filter Title -->
                        <div
                            v-if="! isShowSavedFilters"
                            class="flex items-center justify-between px-1 py-2"
                        >
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.components.datagrid.filters.title')
                            </p>
                        </div>

                        <!-- Save Filter Title -->
                        <div v-else class="flex items-center gap-x-2">
                            <span
                                class="icon-arrow-right rtl:icon-arrow-left mt-0.5 cursor-pointer text-3xl hover:rounded-md hover:bg-gray-100 dark:hover:bg-gray-950"
                                @click="backToFilters"
                            >
                            </span>

                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                @{{ applied.savedFilterId ? '@lang('admin::app.components.datagrid.toolbar.filter.update-filter')' : '@lang('admin::app.components.datagrid.toolbar.filter.save-filter')' }}
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content class="!p-0">
                        <template v-if="! isShowSavedFilters">
                            <!-- Quick Filters Accordion -->
                            <x-admin::accordion
                                class="select-none rounded-none !border-none !shadow-none"
                                v-if="savedFilters.available.length > 0"
                            >
                                <x-slot:header class="px-4">
                                    <p class="w-full text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.components.datagrid.toolbar.filter.quick-filters')
                                    </p>
                                </x-slot>

                                <x-slot:content class="border-b !p-0 dark:border-gray-800">
                                    <div class="grid !p-0">
                                        <!-- Listing of Quick Filters (Saved Filters) -->
                                        <div v-for="(filter,index) in savedFilters.available">
                                            <div
                                                class="flex cursor-pointer items-center justify-between px-4 py-1.5 text-gray-700 hover:bg-gray-50 dark:text-white dark:hover:bg-gray-950"
                                                :class="{ 'bg-gray-50 dark:bg-gray-950 font-semibold': applied.savedFilterId == filter.id }"
                                                @click="applySavedFilter(filter)"
                                            >
                                                <span class="text-xs font-medium text-gray-800 dark:text-white">@{{ filter.name }}</span>

                                                <span
                                                    class="icon-cross rounded p-1.5 text-lg hover:bg-gray-200 dark:hover:bg-gray-800"
                                                    @click.stop="deleteSavedFilter(filter)"
                                                >
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </x-slot>
                            </x-admin::accordion>

                            <!-- Filters Accordion -->
                            <x-admin::accordion class="select-none !rounded-none !border-none !shadow-none">
                                <x-slot:header class="px-4">
                                    <p class="text-base font-semibold text-gray-800 dark:text-white">
                                        @lang('admin::app.components.datagrid.toolbar.filter.custom-filters')
                                    </p>

                                    <div
                                        v-if="hasAnyAppliedColumn() || isFilterDirty"
                                        class="cursor-pointer text-xs font-medium leading-6 text-blue-600 transition-all hover:underline ltr:ml-20 rtl:mr-20"
                                        @click="removeAllAppliedFilters()"
                                    >
                                        @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                    </div>
                                </x-slot>

                                <x-slot:content class="!p-4">
                                    <!-- All Filters -->
                                    <div v-for="column in available.columns">
                                        <div v-if="column.filterable">
                                            <!-- Boolean -->
                                            <div v-if="column.type === 'boolean'">
                                                <!-- Dropdown -->
                                                <template v-if="column.filterable_type === 'dropdown'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2 mt-1.5">
                                                        <x-admin::dropdown>
                                                            <x-slot:toggle>
                                                                <button
                                                                    type="button"
                                                                    class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                                >
                                                                    <!-- If Allow Multiple Values -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="'@lang('admin::app.components.datagrid.filters.select')'"
                                                                        v-if="column.allow_multiple_values"
                                                                    >
                                                                    </span>

                                                                    <!-- If Allow Single Value -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="column.filterable_options.find((option => option.value === getAppliedColumnValues(column.index)))?.label ?? '@lang('admin::app.components.datagrid.filters.select')'"
                                                                        v-else
                                                                    >
                                                                    </span>

                                                                    <span class="icon-sort-down text-2xl"></span>
                                                                </button>
                                                            </x-slot>

                                                            <x-slot:menu class="max-h-[200px] overflow-auto">
                                                                <x-admin::dropdown.menu.item
                                                                    v-for="option in column.filterable_options"
                                                                    v-text="option.label"
                                                                    @click="addFilter(option.value, column)"
                                                                >
                                                                </x-admin::dropdown.menu.item>
                                                            </x-slot>
                                                        </x-admin::dropdown>
                                                    </div>

                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <!-- If Allow Multiple Values -->
                                                        <template v-if="column.allow_multiple_values">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                            >
                                                                <!-- Retrieving the label from the options based on the applied column value. -->
                                                                <span v-text="column.filterable_options.find((option => option.value == appliedColumnValue)).label"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>
                                                    </div>
                                                </template>

                                                <!-- Basic (If Needed) -->
                                                <template v-else></template>
                                            </div>

                                            <!-- Date -->
                                            <div v-else-if="column.type === 'date'">
                                                <!-- Range -->
                                                <template v-if="column.filterable_type === 'date_range'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-1.5 grid grid-cols-2 gap-1.5">
                                                        <p
                                                            class="cursor-pointer rounded-md border px-3 py-2 text-center text-sm font-medium leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:hover:border-gray-400"
                                                            v-for="option in column.filterable_options"
                                                            v-text="option.label"
                                                            @click="addFilter(
                                                                $event,
                                                                column,
                                                                { quickFilter: { isActive: true, selectedFilter: option } }
                                                            )"
                                                        >
                                                        </p>

                                                        <x-admin::flat-picker.date ::allow-input="false">
                                                            <input
                                                                type="date"
                                                                :name="`${column.index}[from]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                                )"
                                                            />
                                                        </x-admin::flat-picker.date>

                                                        <x-admin::flat-picker.date ::allow-input="false">
                                                            <input
                                                                type="date"
                                                                :name="`${column.index}[to]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                                )"
                                                            />
                                                        </x-admin::flat-picker.date>

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    @{{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Basic -->
                                                <template v-else>
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-1.5 grid">
                                                        <x-admin::flat-picker.date ::allow-input="false">
                                                            <input
                                                                type="date"
                                                                :name="column.index"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="column.index"
                                                                @change="addFilter($event, column)"
                                                            />
                                                        </x-admin::flat-picker.date>

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    @{{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Date Time -->
                                            <div v-else-if="column.type === 'datetime'">
                                                <!-- Range -->
                                                <template v-if="column.filterable_type === 'datetime_range'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="my-4 grid grid-cols-2 gap-1.5">
                                                        <p
                                                            class="cursor-pointer rounded-md border px-3 py-2 text-center text-sm font-medium leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300 dark:hover:border-gray-400"
                                                            v-for="option in column.filterable_options"
                                                            v-text="option.label"
                                                            @click="addFilter(
                                                                $event,
                                                                column,
                                                                { quickFilter: { isActive: true, selectedFilter: option } }
                                                            )"
                                                        >
                                                        </p>

                                                        <x-admin::flat-picker.datetime ::allow-input="false">
                                                            <input
                                                                type="datetime-local"
                                                                :name="`${column.index}[from]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                                                )"
                                                            />
                                                        </x-admin::flat-picker.datetime>

                                                        <x-admin::flat-picker.datetime ::allow-input="false">
                                                            <input
                                                                type="datetime-local"
                                                                :name="`${column.index}[to]`"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="`${column.index}[from]`"
                                                                @change="addFilter(
                                                                    $event,
                                                                    column,
                                                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                                                )"
                                                            />
                                                        </x-admin::flat-picker.datetime>

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    @{{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Basic -->
                                                <template v-else>
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="my-4 grid">
                                                        <x-admin::flat-picker.datetime ::allow-input="false">
                                                            <input
                                                                type="datetime-local"
                                                                :name="column.index"
                                                                value=""
                                                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                                                                :placeholder="column.label"
                                                                :ref="column.index"
                                                                @change="addFilter($event, column)"
                                                            />
                                                        </x-admin::flat-picker.datetime>

                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="findAppliedColumn(column.index)"
                                                            >
                                                                <span>
                                                                    @{{ getFormattedDates(findAppliedColumn(column.index)) }}
                                                                </span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Rest -->
                                            <div v-else>
                                                <!-- Dropdown -->
                                                <template v-if="column.filterable_type === 'dropdown'">
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2 mt-1.5">
                                                        <x-admin::dropdown>
                                                            <x-slot:toggle>
                                                                <button
                                                                    type="button"
                                                                    class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                                >
                                                                    <!-- If Allow Multiple Values -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="'@lang('admin::app.components.datagrid.filters.select')'"
                                                                        v-if="column.allow_multiple_values"
                                                                    >
                                                                    </span>

                                                                    <!-- If Allow Single Value -->
                                                                    <span
                                                                        class="text-sm text-gray-400 dark:text-gray-400"
                                                                        v-text="column.filterable_options.find((option => option.value === getAppliedColumnValues(column.index)))?.label ?? '@lang('admin::app.components.datagrid.filters.select')'"
                                                                        v-else
                                                                    >
                                                                    </span>

                                                                    <span class="icon-sort-down text-2xl"></span>
                                                                </button>
                                                            </x-slot>

                                                            <x-slot:menu class="max-h-[200px] overflow-auto">
                                                                <x-admin::dropdown.menu.item
                                                                    v-for="option in column.filterable_options"
                                                                    v-text="option.label"
                                                                    @click="addFilter(option.value, column)"
                                                                >
                                                                </x-admin::dropdown.menu.item>
                                                            </x-slot>
                                                        </x-admin::dropdown>
                                                    </div>

                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <!-- If Allow Multiple Values -->
                                                        <template v-if="column.allow_multiple_values">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                            >
                                                                <!-- Retrieving the label from the options based on the applied column value. -->
                                                                <span v-text="column.filterable_options.find((option => option.value == appliedColumnValue)).label"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>
                                                    </div>
                                                </template>

                                                <!-- Basic -->
                                                <template v-else>
                                                    <div class="flex items-center justify-between">
                                                        <p
                                                            class="text-xs font-medium text-gray-800 dark:text-white"
                                                            v-text="column.label"
                                                        >
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-x-1.5"
                                                            @click="removeAppliedColumnAllValues(column.index)"
                                                        >
                                                            <p
                                                                class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                                                                v-if="hasAnyAppliedColumnValues(column.index)"
                                                            >
                                                                @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Text type Input field -->
                                                    <div class="mb-2 mt-1.5 grid">
                                                        <input
                                                            type="text"
                                                            class="block w-full rounded-md border bg-white px-2 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                                            :name="column.index"
                                                            :placeholder="column.label"
                                                            @change="addFilter($event, column)"
                                                        />
                                                    </div>

                                                    <div class="mb-4 flex flex-wrap gap-2">
                                                        <!-- If Allow Multiple Values -->
                                                        <template v-if="column.allow_multiple_values">
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                                                            >
                                                                <span v-text="appliedColumnValue"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>

                                                        <!-- If Allow Single Value -->
                                                        <template v-else>
                                                            <p
                                                                class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                v-if="getAppliedColumnValues(column.index) !== ''"
                                                            >
                                                                <span v-text="getAppliedColumnValues(column.index)"></span>

                                                                <span
                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                    @click="removeAppliedColumnValue(column.index, getAppliedColumnValues(column.index))"
                                                                >
                                                                </span>
                                                            </p>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons Panel -->
                                    <div class="flex gap-2">
                                        <!-- Apply Filter Button -->
                                        <button
                                            type="button"
                                            class="secondary-button w-full"
                                            @click="applyFilters"
                                            :disabled="! isFilterDirty"
                                        >
                                            @lang('admin::app.components.datagrid.toolbar.filter.apply-filters-btn')
                                        </button>

                                        <!-- Save Filter Button -->
                                        <button
                                            type="button"
                                            v-if="hasAnyColumn"
                                            class="secondary-button w-full"
                                            @click="isShowSavedFilters = ! isShowSavedFilters"
                                            :disabled="isFilterDirty || ! filters.columns.length > 0"
                                        >
                                            @{{ applied.savedFilterId ? '@lang('admin::app.components.datagrid.toolbar.filter.update-filter')' : '@lang('admin::app.components.datagrid.toolbar.filter.save-filter')' }}
                                        </button>
                                    </div>
                                </x-slot>
                            </x-admin::accordion>
                        </template>

                        <!-- Save Filter Section -->
                        <template v-else>
                            <div class="flex items-center justify-between px-4 py-4">
                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                    @{{ applied.savedFilterId ? '@lang('admin::app.components.datagrid.toolbar.filter.update-filter')' : '@lang('admin::app.components.datagrid.toolbar.filter.create-new-filter')' }}
                                </p>
                            </div>

                            <div v-if="hasAnyColumn">
                                <!-- Save Filter Form -->
                                <x-admin::form
                                    v-slot="{ meta, errors, handleSubmit }"
                                    as="div"
                                >
                                    <form @submit="handleSubmit($event, createOrUpdateFilter)">
                                        <div class="flex flex-col gap-4">
                                            <!-- Save Filter Name Input Field -->
                                            <div class="flex flex-col gap-2 border-b px-4 dark:border-gray-800">
                                                <x-admin::form.control-group>
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('admin::app.components.datagrid.toolbar.filter.name')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="hidden"
                                                        name="id"
                                                        ::value="applied.savedFilterId"
                                                    />

                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="name"
                                                        id="name"
                                                        ::value="getAppliedSavedFilter?.name"
                                                        rules="required"
                                                        :label="trans('admin::app.components.datagrid.toolbar.filter.name')"
                                                        :placeholder="trans('admin::app.components.datagrid.toolbar.filter.name')"
                                                    />

                                                    <x-admin::form.control-group.error control-name="name" />
                                                </x-admin::form.control-group>

                                                <!-- Save Filter Form Submit Button -->
                                                <div class="mb-4 flex content-end items-center justify-end">
                                                    <button
                                                        type="submit"
                                                        class="primary-button"
                                                        aria-label="@lang('admin::app.components.datagrid.toolbar.filter.save-btn')"
                                                        :disabled="savedFilters.params.filters.columns.every(column => column.value.length === 0)"
                                                    >
                                                        @{{ applied.savedFilterId ? '@lang('admin::app.components.datagrid.toolbar.filter.update-filter')' : '@lang('admin::app.components.datagrid.toolbar.filter.save-filter')' }}
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="flex flex-col gap-4 px-4">
                                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                                    @lang('admin::app.components.datagrid.toolbar.filter.selected-filters')
                                                </p>

                                                <div v-if="! savedFilters.params.filters.columns.every(column => column.value.length === 0)">
                                                    <!-- Applied filters label and value listing for saving custom filter. -->
                                                    <div v-for="column in savedFilters.params.filters.columns">
                                                        <div
                                                            class="flex flex-col gap-2"
                                                            v-if="hasAnyValue(column)"
                                                        >
                                                            <p class="text-xs font-medium text-gray-800 dark:text-white">
                                                                @{{ column.label }}
                                                            </p>

                                                            <div class="mb-4 flex flex-wrap gap-2">
                                                                <!-- Date & Date Time Case -->
                                                                <template v-if="column.type === 'date' || column.type === 'datetime'">
                                                                    <p class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white">
                                                                        <span>
                                                                            @{{ getFormattedDates(column) }}
                                                                        </span>

                                                                        <div>
                                                                            <span
                                                                                class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                                @click="removeSavedFilterColumnValue(column, appliedColumnValue)"
                                                                            >
                                                                            </span>
                                                                        </div>
                                                                    </p>
                                                                </template>

                                                                <!-- Rest Case -->
                                                                <template v-else>
                                                                    <!-- If Allow Multiple Values -->
                                                                    <template v-if="column.allow_multiple_values">
                                                                        <p
                                                                            v-for="appliedColumnValue in column.value"
                                                                            class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                                                                        >
                                                                            <span>
                                                                                @{{ appliedColumnValue }}
                                                                            </span>

                                                                            <div>
                                                                                <span
                                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                                    @click="removeSavedFilterColumnValue(column, appliedColumnValue)"
                                                                                >
                                                                                </span>
                                                                            </div>
                                                                        </p>
                                                                    </template>

                                                                    <!-- If Allow Single Value -->
                                                                    <template v-else>
                                                                        <p class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white">
                                                                            <span>
                                                                                @{{ column.value }}
                                                                            </span>

                                                                            <div>
                                                                                <span
                                                                                    class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"
                                                                                    @click="removeSavedFilterColumnValue(column, column.value)"
                                                                                >
                                                                                </span>
                                                                            </div>
                                                                        </p>
                                                                    </template>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Save Filter Empty Value Placeholder -->
                                                <div v-else>
                                                    <div class="mb-4 flex content-end items-center justify-end">
                                                        <div class="grid">
                                                            <div class="flex items-center gap-5 py-2.5">
                                                                <img
                                                                    src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                                                    class="h-20 w-20 dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                                                                >

                                                                <div class="flex flex-col gap-1.5">
                                                                    <p class="text-base font-semibold text-gray-400">
                                                                        @lang('admin::app.components.datagrid.toolbar.filter.empty-title')
                                                                    </p>

                                                                    <p class="text-gray-400">
                                                                        @lang('admin::app.components.datagrid.toolbar.filter.empty-description')
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </x-admin::form>
                            </div>
                        </template>
                    </x-slot>
                </x-admin::drawer>
            </template>
        </slot>
    </script>

    <script type="module">
        app.component('v-datagrid-filter', {
            template: '#v-datagrid-filter-template',

            props: ['isLoading', 'available', 'applied', 'src'],

            emits: ['applyFilters', 'applySavedFilter'],

            data() {
                return {
                    savedFilters: {
                        available: [],

                        applied: null,

                        params: {
                            filters: {
                                columns: [],
                            },
                        },
                    },

                    filters: {
                        columns: [],
                    },

                    isShowSavedFilters: false,

                    isFilterDirty: false,
                };
            },

            mounted() {
                this.filters.columns = this.getAppliedColumns();

                this.savedFilters.params.filters.columns = JSON.parse(JSON.stringify(this.filters.columns));

                this.getSavedFilters();
            },

            computed: {
                getAppliedSavedFilter() {
                    return this.savedFilters.available.find((filter) => filter.id == this.applied.savedFilterId);
                },
            },

            methods: {
                /**
                 * Has any column.
                 *
                 * @returns {boolean}
                 */
                hasAnyColumn() {
                    return filters.columns.length;
                },

                /**
                 * Get applied columns.
                 *
                 * @returns {object}
                 */
                getAppliedColumns() {
                    return this.applied.filters.columns.filter((column) => column.index !== 'all');
                },

                /**
                 * Has any applied column.
                 *
                 * @returns {boolean}
                 */
                hasAnyAppliedColumn() {
                    return this.getAppliedColumns().length > 0;
                },

                /**
                 * Go back to filters.
                 *
                 * @returns {void}
                 */
                backToFilters() {
                    this.savedFilters.params.filters.columns = JSON.parse(JSON.stringify(this.filters.columns));

                    this.isShowSavedFilters = ! this.isShowSavedFilters;
                },

                /**
                 * Applies the saved filter.
                 *
                 * @param {Object} filter - The filter to be applied.
                 */
                applySavedFilter(filter) {
                    this.$emit('applySavedFilter', filter);
                },

                /**
                 * Remove all applied filters.
                 *
                 * @returns {void}
                 */
                removeAllAppliedFilters() {
                    this.filters = {
                        columns: [],
                    };

                    this.isFilterDirty = true;
                },

                /**
                 * Remove filter option from save filters screen.
                 *
                 * @returns {void}
                 */
                removeSavedFilterColumnValue(column, value) {
                    if (column.allow_multiple_values) {
                        column.value = column.value.filter((columnValue) => columnValue !== value);
                    } else {
                        column.value = '';
                    }
                },

                /**
                 * Save filters to the database.
                 *
                 * @returns {void}
                 */
                createOrUpdateFilter(params, { setErrors }) {
                    let applied = JSON.parse(JSON.stringify(this.applied));

                    applied.filters.columns = this.savedFilters.params.filters.columns.filter((column) => this.hasAnyValue(column));

                    if (params.id) {
                        params._method = 'PUT';
                    }

                    this.$axios.post(params.id ? `{{ route('admin.datagrid.saved_filters.update', '') }}/${params.id}` : "{{ route('admin.datagrid.saved_filters.store') }}", {
                        src: this.src,
                        applied,
                        ...params,
                    })
                        .then(response => {
                            if (! params.id) {
                                this.savedFilters.available.push(response.data.data);
                            } else {
                                this.savedFilters.available = this.savedFilters.available.map((filter) => {
                                    if (filter.id == response.data.data.id) {
                                        return response.data.data;
                                    }

                                    return filter;
                                });
                            }

                            this.savedFilters.name = '';

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.isShowSavedFilters = false;
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            } else {
                                this.$emitter.emit('add-flash', { type: 'error',  message: response.data.message });
                            }
                        });
                },

                /**
                 * Retrieves the saved filters.
                 *
                 * @returns {void}
                 */
                getSavedFilters() {
                    this.$axios
                        .get('{{ route('admin.datagrid.saved_filters.index') }}', {
                            params: { src: this.src }
                        })
                        .then(response => {
                            this.savedFilters.available = response.data.data;
                        })
                        .catch(error => {});
                },

                /**
                 * Delete the saved filter.
                 *
                 * @returns {void}
                 */
                deleteSavedFilter(filter) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.delete(`{{ route('admin.datagrid.saved_filters.destroy', '') }}/${filter.id}`)
                                .then(response => {
                                    this.applySavedFilter(null);

                                    this.savedFilters.available = this.savedFilters.available.filter((savedFilter) => savedFilter.id !== filter.id);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                })
                                .catch(error => {
                                    this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                                });
                        }
                    });
                },

                /**
                 * Apply all added filters.
                 *
                 * @returns {void}
                 */
                applyFilters() {
                    this.$emit('applyFilters', this.filters);

                    this.$refs.filterDrawer.close();
                },

                /**
                 * Add filter.
                 *
                 * @param {Event} $event
                 * @param {object} column
                 * @param {object} additional
                 * @returns {void}
                 */
                addFilter($event, column = null, additional = {}) {
                    let quickFilter = additional?.quickFilter;

                    if (quickFilter?.isActive) {
                        let options = quickFilter.selectedFilter;

                        switch (column.type) {
                            case 'date':
                            case 'datetime':
                                this.applyColumnValues(column, options.name);

                                break;

                            default:
                                break;
                        }
                    } else {
                        /**
                         * Here, either a real event will come or a string value. If a string value is present, then
                         * we create a similar event-like structure to avoid any breakage and make it easy to use.
                         */
                        if ($event?.target?.value === undefined) {
                            $event = {
                                target: {
                                    value: $event,
                                }
                            };
                        }

                        this.applyColumnValues(column, $event.target.value, additional);

                        if (column) {
                            $event.target.value = '';
                        }
                    }
                },

                /**
                 * Apply column values.
                 *
                 * @param {object} column
                 * @param {string} requestedValue
                 * @param {object} additional
                 * @returns {void}
                 */
                applyColumnValues(column, requestedValue, additional = {}) {
                    let appliedColumn = this.findAppliedColumn(column?.index);

                    if (
                        requestedValue === undefined ||
                        requestedValue === '' ||
                        (appliedColumn?.allow_multiple_values && appliedColumn?.value.includes(requestedValue)) ||
                        (! appliedColumn?.allow_multiple_values && appliedColumn?.value === requestedValue)
                    ) {
                        return;
                    }

                    switch (column.type) {
                        case 'date':
                        case 'datetime':
                            let { range } = additional;

                            if (appliedColumn) {
                                if (range) {
                                    let appliedRanges = ['', ''];

                                    if (typeof appliedColumn.value !== 'string') {
                                        appliedRanges = appliedColumn.value[0];
                                    }

                                    if (range.name == 'from') {
                                        appliedRanges[0] = requestedValue;
                                    }

                                    if (range.name == 'to') {
                                        appliedRanges[1] = requestedValue;
                                    }

                                    appliedColumn.value = [appliedRanges];
                                } else {
                                    appliedColumn.value = requestedValue;
                                }
                            } else {
                                if (range) {
                                    let appliedRanges = ['', ''];

                                    if (range.name == 'from') {
                                        appliedRanges[0] = requestedValue;
                                    }

                                    if (range.name == 'to') {
                                        appliedRanges[1] = requestedValue;
                                    }

                                    this.filters.columns.push({
                                        index: column.index,
                                        label: column.label,
                                        type: column.type,
                                        value: [appliedRanges]
                                    });
                                } else {
                                    this.filters.columns.push({
                                        index: column.index,
                                        label: column.label,
                                        type: column.type,
                                        value: requestedValue
                                    });
                                }
                            }

                            break;

                        default:
                            if (appliedColumn) {
                                if (appliedColumn.allow_multiple_values) {
                                    appliedColumn.value.push(requestedValue);
                                } else {
                                    appliedColumn.value = requestedValue;
                                }
                            } else {
                                this.filters.columns.push({
                                    index: column.index,
                                    label: column.label,
                                    type: column.type,
                                    value: column.allow_multiple_values ? [requestedValue] : requestedValue,
                                    allow_multiple_values: column.allow_multiple_values,
                                });
                            }

                            break;
                    }

                    this.isFilterDirty = true;
                },

                /**
                 * Get formatted dates.
                 *
                 * @param {object} appliedColumn
                 * @returns {string}
                 */
                getFormattedDates(appliedColumn)
                {
                    if (! appliedColumn) {
                        return '';
                    }

                    if (typeof appliedColumn.value === 'string') {
                        const availableColumn = this.available.columns.find(column => column.index === appliedColumn.index);

                        if (availableColumn.filterable_type === 'date_range' || availableColumn.filterable_type === 'datetime_range') {
                            const option = availableColumn.filterable_options.find(option => option.name === appliedColumn.value);

                            return option.label;
                        }

                        return appliedColumn.value;
                    }

                    if (! appliedColumn.value.length) {
                        return '';
                    }

                    return appliedColumn.value[0].join(' to ');
                },

                /**
                 * Check if any values are applied for the specified column.
                 *
                 * @param {object} column
                 * @returns {boolean}
                 */
                hasAnyValue(column) {
                    if (column.allow_multiple_values) {
                        return column.value.length > 0;
                    }

                    return column.value !== '';
                },

                /**
                 * Find applied column.
                 *
                 * @param {string} columnIndex
                 * @returns {object}
                 */
                findAppliedColumn(columnIndex) {
                    return this.filters.columns.find(column => column.index === columnIndex);
                },

                /**
                 * Check if any values are applied for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {boolean}
                 */
                hasAnyAppliedColumnValues(columnIndex) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    if (! appliedColumn) {
                        return false;
                    }

                    return this.hasAnyValue(appliedColumn);
                },

                /**
                 * Get applied values for the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {Array}
                 */
                getAppliedColumnValues(columnIndex) {
                    const appliedColumn = this.findAppliedColumn(columnIndex);

                    if (appliedColumn?.allow_multiple_values) {
                        return appliedColumn?.value ?? [];
                    }

                    return appliedColumn?.value ?? '';
                },

                /**
                 * Remove a specific value from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @param {any} appliedColumnValue
                 * @returns {void}
                 */
                removeAppliedColumnValue(columnIndex, appliedColumnValue) {
                    let appliedColumn = this.findAppliedColumn(columnIndex);

                    if (appliedColumn?.type === 'date' || appliedColumn?.type === 'datetime') {
                        appliedColumn.value = [];
                    } else {
                        if (appliedColumn.allow_multiple_values) {
                            appliedColumn.value = appliedColumn?.value.filter(value => value !== appliedColumnValue);
                        } else {
                            appliedColumn.value = '';
                        }
                    }

                    /**
                     * Clean up is done here. If there are no applied values present, there is no point in including the applied column as well.
                     */
                    if (! appliedColumn.value.length) {
                        this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);
                    }

                    this.isFilterDirty = true;
                },

                /**
                 * Remove all values from the applied values of the specified column.
                 *
                 * @param {string} columnIndex
                 * @returns {void}
                 */
                removeAppliedColumnAllValues(columnIndex) {
                    this.filters.columns = this.filters.columns.filter(column => column.index !== columnIndex);

                    this.isFilterDirty = true;
                },
            },
        });
    </script>
@endpushOnce
