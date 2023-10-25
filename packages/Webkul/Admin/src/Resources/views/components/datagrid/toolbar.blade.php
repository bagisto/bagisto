<template v-if="isLoading">
    <x-admin::shimmer.datagrid.toolbar/>
</template>

<template v-else>
    <div class="mt-[28px] flex items-center justify-between gap-[16px] max-md:flex-wrap">
        <!-- Left Toolbar -->
        <div class="flex gap-x-[4px]">
            <!-- Mass Actions Panel -->
            <div
                class="flex w-full items-center gap-x-[4px]"
                v-if="applied.massActions.indices.length"
            >
                <!-- Mass Action Dropdown -->
                <x-admin::dropdown>
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[8px] rounded-[6px] border dark:border-gray-800 bg-white dark:bg-gray-900 px-[10px] py-[6px] text-center leading-[24px] text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 focus:ring-black"
                        >
                            <span>
                                @lang('admin::app.components.datagrid.toolbar.mass-actions.select-action')
                            </span>

                            <span class="icon-sort-down text-[24px]"></span>
                        </button>
                    </x-slot:toggle>

                    <!-- Dropdown Content -->
                    <x-slot:menu class="!p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)] dark:border-gray-800">
                        <template v-for="massAction in available.massActions">
                            <li
                                class="group/item relative overflow-visible"
                                v-if="massAction?.options?.length"
                            >
                                <a
                                    class="flex gap-[5px] justify-between whitespace-no-wrap cursor-not-allowed rounded-t px-4 py-2 text-[14px] text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-950"
                                    href="javascript:void(0);"
                                >
                                    <i
                                        class="text-[24px]"
                                        :class="massAction.icon"
                                        v-if="massAction?.icon"
                                    >
                                    </i>

                                    <span>
                                        @{{ massAction.title }}
                                    </span>

                                    <i class="icon-arrow-left text-[20px] -mt-[1px]"></i>
                                </a>

                                <ul class="absolute ltr:left-full rtl:right-full top-0 z-10 hidden w-max min-w-[150px] border dark:border-gray-800 rounded-[4px] bg-white dark:bg-gray-900 shadow-[0_5px_20px_rgba(0,0,0,0.15)] group-hover/item:block">
                                    <li v-for="option in massAction.options">
                                        <a
                                            class="whitespace-no-wrap block rounded-t px-4 py-2 text-[14px] text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-950"
                                            href="javascript:void(0);"
                                            v-text="option.label"
                                            @click="performMassAction(massAction, option)"
                                        >
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li v-else>
                                <a
                                    class="flex gap-[5px] whitespace-no-wrap rounded-b px-4 py-2 text-[14px] text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-950"
                                    href="javascript:void(0);"
                                    @click="performMassAction(massAction)"
                                >
                                    <i
                                        class="text-[24px]"
                                        :class="massAction.icon"
                                        v-if="massAction?.icon"
                                    >
                                    </i>

                                    @{{ massAction.title }}
                                </a>
                            </li>
                        </template>
                    </x-slot:menu>
                </x-admin::dropdown>

                <div class="ltr:pl-[10px] rtl:pr-[10px]">
                    <p class="text-[14px] font-light text-gray-800 dark:text-white">
                        <!-- Need to manage this translation. -->
                        @{{ applied.massActions.indices.length }} of @{{ available.meta.total }} Selected
                    </p>
                </div>
            </div>

            <!-- Search Panel -->
            <div
                class="flex w-full items-center gap-x-[4px]"
                v-else
            >
                <!-- Search Panel -->
                <div class="flex max-w-[445px] items-center max-sm:w-full max-sm:max-w-full">
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="search"
                            :value="getAppliedColumnValues('all')"
                            class="block w-full rounded-lg border dark:border-gray-800 bg-white dark:bg-gray-900 py-[6px] ltr:pl-[12px] rtl:pr-[12px] ltr:pr-[40px] rtl:pl-[40px] leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400  dark:focus:border-gray-400"
                            placeholder="@lang('admin::app.components.datagrid.toolbar.search.title')"
                            autocomplete="off"
                            @keyup.enter="filterPage"
                        >

                        <div class="icon-search pointer-events-none absolute ltr:right-[10px] rtl:left-[10px] top-[8px] flex items-center text-[22px]">
                        </div>
                    </div>
                </div>

                <!-- Information Panel -->
                <div class="ltr:pl-[10px] rtl:pr-[10px]">
                    <p class="text-[14px] font-light text-gray-800 dark:text-white">
                        <!-- Need to manage this translation. -->
                        @{{ available.meta.total }} Results
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-[16px]">
            <!-- Filters Activation Button -->
            <x-admin::drawer width="350px" ref="filterDrawer">
                <x-slot:toggle>
                    <div>
                        <div
                            class="relative inline-flex w-full max-w-max ltr:pl-[12px] rtl:pr-[12px] ltr:pr-[18px] rtl:pl-[18px] cursor-pointer select-none appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border dark:border-gray-800 bg-white dark:bg-gray-900 px-[4px] py-[6px] text-center text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:border-gray-400 dark:hover:border-gray-400 focus:outline-none focus:ring-2"
                            :class="{'[&>*]:text-blue-600 [&>*]:dark:text-white': applied.filters.columns.length > 1}"
                        >
                            <span class="icon-filter text-[22px]"></span>

                            <span>
                                @lang('admin::app.components.datagrid.toolbar.filter.title')
                            </span>

                            <span
                                class="icon-dot absolute top-[5px] right-[8px] text-[14px] font-bold"
                                v-if="applied.filters.columns.length > 1"
                            ></span>
                        </div>

                        <div class="z-10 hidden w-full divide-y divide-gray-100 rounded bg-white dark:bg-gray-900 shadow">
                        </div>
                    </div>
                </x-slot:toggle>

                <!-- Drawer Header -->
                <x-slot:header>
                    <div class="flex justify-between items-center p-3">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('admin::app.components.datagrid.filters.title')
                        </p>
                    </div>
                </x-slot:header>

                <!-- Drawer Content -->
                <x-slot:content class="!p-[20px]">
                    <x-admin::datagrid.filters></x-admin::datagrid.filters>
                </x-slot:content>
            </x-admin::drawer>

            <div class="flex items-center gap-x-[8px]">
                <x-admin::dropdown>
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[8px] rounded-[6px] border dark:border-gray-800 bg-white dark:bg-gray-900 px-[10px] py-[6px] text-center leading-[24px] text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400"
                        >
                            <span v-text="applied.pagination.perPage"></span>

                            <span class="icon-sort-down text-[24px]"></span>
                        </button>
                    </x-slot:toggle>

                    <!-- Dropdown Content -->
                    <x-slot:menu>
                        <x-admin::dropdown.menu.item
                            v-for="perPageOption in available.meta.per_page_options"
                            v-text="perPageOption"
                            @click="changePerPageOption(perPageOption)"
                        >
                        </x-admin::dropdown.menu.item>
                    </x-slot:menu>
                </x-admin::dropdown>

                <p class="whitespace-nowrap text-gray-600 dark:text-gray-300 max-sm:hidden">per page</p>

                <input
                    type="text"
                    class="ltr:ml-[8px] rtl:mr-[8px] inline-flex min-h-[38px] max-w-[40px] appearance-none items-center justify-center gap-x-[4px] rounded-[6px] border dark:border-gray-800 bg-white dark:bg-gray-900 px-[12px] py-[6px] text-center leading-[24px] text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:border-gray-400 dark:hover:border-gray-400 focus:outline-none focus:border-gray-400 dark:focus:border-gray-400 max-sm:hidden"
                    :value="available.meta.current_page"
                    @change="changePage(parseInt($event.target.value))"
                >

                <div class="whitespace-nowrap text-gray-600 dark:text-gray-300">
                    <span>of </span>

                    <span v-text="available.meta.last_page"></span>
                </div>

                <!-- Pagination -->
                <div class="flex items-center gap-[4px]">
                    <div
                        class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border border-transparent p-[6px] text-center text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:bg-gray-200 dark:hover:bg-gray-800 active:border-gray-300"
                        @click="changePage('previous')"
                    >
                        <span class="icon-sort-left text-[24px]"></span>
                    </div>

                    <div
                        class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border border-transparent p-[6px] text-center text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:bg-gray-200 dark:hover:bg-gray-800 active:border-gray-300"
                        @click="changePage('next')"
                    >
                        <span class="icon-sort-right text-[24px]"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
