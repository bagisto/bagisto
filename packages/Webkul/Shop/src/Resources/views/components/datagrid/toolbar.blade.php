<template v-if="isLoading">
    <x-shop::shimmer.datagrid.toolbar />
</template>

<template v-else>
    <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
        <!-- Left Toolbar -->
        <div class="flex gap-x-1">
            <!-- Mass Action Panel -->
            <x-shop::datagrid.toolbar.mass-action />

            <!-- Search Panel -->
            <x-shop::datagrid.toolbar.search />
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-4">
            <!-- Items Per Page Panel -->
            <x-shop::datagrid.toolbar.pagination />

            <!-- Filter Panel -->
            <x-shop::datagrid.toolbar.filter />
        </div>
    </div>
</template>
