<template v-if="isLoading">
    <x-admin::shimmer.datagrid.toolbar />
</template>

<template v-else>
    <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
        <!-- Left Toolbar -->
        <div class="flex gap-x-1">
            <!-- Mass Actions Panel -->
            <template v-if="applied.massActions.indices.length">
                <x-admin::datagrid.toolbar.mass-actions-panel />
            </template>

            <!-- Search Panel -->
            <template v-else>
                <x-admin::datagrid.toolbar.search-panel />
            </template>
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-4">
            <!-- Filters Panel -->
            <x-admin::datagrid.toolbar.filters-panel />

            <!-- Pagination Panel -->
            <x-admin::datagrid.toolbar.pagination-panel />
        </div>
    </div>
</template>
