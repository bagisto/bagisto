<template v-if="isLoading">
    <x-shop::shimmer.datagrid.toolbar />
</template>

<template v-else>
    <div class="mt-7 flex items-center justify-between gap-4 max-md:block">
        <!-- Left Toolbar -->
        <div class="flex w-full gap-x-1">
            <!-- Mass Actions Panel -->
            <template v-if="applied.massActions.indices.length">
                <x-shop::datagrid.toolbar.mass-action>
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
                </x-shop::datagrid.toolbar.mass-action>
            </template>

            <!-- Search Panel -->
            <template v-else>
                <x-shop::datagrid.toolbar.search>
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
                </x-shop::datagrid.toolbar.search>

                <div class="hidden w-11 max-md:block">
                    <x-shop::datagrid.toolbar.filter>
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
                    </x-shop::datagrid.toolbar.filter>
                </div>
            </template>
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-4 max-md:my-4 max-md:items-center max-md:justify-between">
            <!-- Pagination Panel -->
            <x-shop::datagrid.toolbar.pagination>
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
            </x-shop::datagrid.toolbar.pagination>                

            <div class="hidden max-md:block ltr:pl-2.5 rtl:pr-2.5">
                <p class="text-sm font-light text-gray-800 max-md:w-full">
                    @{{ "@lang('shop::app.components.datagrid.toolbar.results')".replace(':total', available.meta.total) }}
                </p>
            </div>

            <!-- Filter Panel -->
            <div class="max-md:hidden">
                <x-shop::datagrid.toolbar.filter>
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
                </x-shop::datagrid.toolbar.filter>
            </div>
        </div>
    </div>
</template>
