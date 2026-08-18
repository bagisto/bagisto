
<template v-if="isLoading">
    <x-admin::shimmer.datagrid.toolbar />
</template>

<template v-else>
    <div class="mt-7 flex items-center gap-4 max-md:flex-wrap max-md:gap-y-3">
        <!-- Search + Results. `me-auto` keeps the search to the left on desktop, pushing filter and pagination to the right edge; on mobile it is the full first row. -->
        <div class="flex gap-x-1 md:me-auto max-md:w-full">
            <!-- Mass Actions Panel -->
            <template v-if="applied.massActions.indices.length">
                <x-admin::datagrid.toolbar.mass-action>
                    <template #mass-action="{
                        available,
                        applied,
                        massActions,
                        validateMassAction,
                        performMassAction
                    }">
                        <slot
                            name="mass-action"
                            :available="available"
                            :applied="applied"
                            :mass-actions="massActions"
                            :validate-mass-action="validateMassAction"
                            :perform-mass-action="performMassAction"
                        >
                        </slot>
                    </template>
                </x-admin::datagrid.toolbar.mass-action>
            </template>

            <!-- Search Panel -->
            <template v-else>
                <x-admin::datagrid.toolbar.search>
                    <template #search="{
                        available,
                        applied,
                        search,
                        getSearchedValues
                    }">
                        <slot
                            name="search"
                            :available="available"
                            :applied="applied"
                            :search="search"
                            :get-searched-values="getSearchedValues"
                        >
                        </slot>
                    </template>
                </x-admin::datagrid.toolbar.search>
            </template>
        </div>

        <!-- Filter + Sort. On mobile this row becomes the FILTER | SORT bar fixed to the foot of the screen, so the toolbar above keeps just two rows (see `.dg-fsbar` in the admin stylesheet); on desktop it stays inline. -->
        <div class="dg-fsbar flex items-center gap-x-4 max-md:w-full max-md:justify-center">
            <!-- Filter -->
            <div class="dg-fsbar-filter">
                <x-admin::datagrid.toolbar.filter>
                    <template #filter="{
                        available,
                        applied,
                        filters,
                        applyFilter,
                        applyColumnValues,
                        findAppliedColumn,
                        hasAnyAppliedColumnValues,
                        getAppliedColumnValues,
                        removeAppliedColumnValue,
                        removeAppliedColumnAllValues
                    }">
                        <slot
                            name="filter"
                            :available="available"
                            :applied="applied"
                            :filters="filters"
                            :apply-filter="applyFilter"
                            :apply-column-values="applyColumnValues"
                            :find-applied-column="findAppliedColumn"
                            :has-any-applied-column-values="hasAnyAppliedColumnValues"
                            :get-applied-column-values="getAppliedColumnValues"
                            :remove-applied-column-value="removeAppliedColumnValue"
                            :remove-applied-column-all-values="removeAppliedColumnAllValues"
                        >
                        </slot>
                    </template>
                </x-admin::datagrid.toolbar.filter>
            </div>

            <!-- Divider between the two bottom-bar segments (Only rendered inside the mobile bar.) -->
            <span class="dg-fsbar-sep hidden"></span>

            <!-- Sort. Column headers are hard to reach on a phone, so sorting also lives in a slide-up drawer opened from the bottom bar; hidden by default and revealed only there, since desktop keeps the column-header sort. -->
            <div class="dg-fsbar-sort hidden">
                <x-admin::drawer
                    position="bottom"
                    width="100%"
                    ref="sortDrawer"
                >
                    <x-slot:toggle>
                        <div
                            class="flex cursor-pointer items-center justify-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase text-gray-600 dark:text-gray-300"
                            title="@lang('admin::app.components.datagrid.toolbar.sort-by')"
                        >
                            <span class="icon-sort-up-down text-2xl"></span>

                            @lang('admin::app.components.datagrid.toolbar.sort-by')
                        </div>
                    </x-slot>

                    <x-slot:header class="!px-5">
                        <div class="flex min-h-9 items-center">
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.components.datagrid.toolbar.sort-by')
                            </p>
                        </div>
                    </x-slot>

                    <x-slot:content class="!p-0">
                        <div class="py-1.5">
                            <template v-for="column in available.columns.filter(col => col.sortable)" :key="column.index">
                                <div
                                    class="flex cursor-pointer items-center justify-between gap-3 px-5 py-3 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    :class="{ 'font-medium text-blue-600 dark:text-blue-300': applied.sort.column === column.index }"
                                    @click="sort(column); $refs.sortDrawer.close();"
                                >
                                    <span class="text-gray-600 dark:text-gray-300" v-text="column.label"></span>

                                    <i
                                        v-if="applied.sort.column === column.index"
                                        class="text-xl leading-none text-gray-800 dark:text-white"
                                        :class="applied.sort.order === 'asc' ? 'icon-down-stat' : 'icon-up-stat'"
                                    ></i>
                                </div>
                            </template>
                        </div>
                    </x-slot>
                </x-admin::drawer>
            </div>
        </div>

        <!-- Pagination (third row on mobile, centered). -->
        <div class="max-md:flex max-md:w-full max-md:justify-center">
            <!-- Pagination Panel -->
            <x-admin::datagrid.toolbar.pagination>
                <template #pagination="{
                    available,
                    applied,
                    changePage,
                    changePerPageOption
                }">
                    <slot
                        name="pagination"
                        :available="available"
                        :applied="applied"
                        :change-page="changePage"
                        :change-per-page-option="changePerPageOption"
                    >
                    </slot>
                </template>
            </x-admin::datagrid.toolbar.pagination>
        </div>
    </div>
</template>
