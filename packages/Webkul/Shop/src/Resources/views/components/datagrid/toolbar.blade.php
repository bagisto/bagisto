<template v-if="isLoading">
    <x-shop::shimmer.datagrid.toolbar />
</template>

<template v-else>
    <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
        <!-- Left Toolbar -->
        <div class="flex gap-x-1">
            <!-- Mass Actions Panel -->
            <div
                class="flex w-full items-center gap-x-1"
                v-if="applied.massActions.indices.length"
            >
                <!-- Mass Action Dropdown -->
                <x-shop::dropdown position="bottom-left">
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black">
                            <span>
                                @lang('shop::app.components.datagrid.toolbar.mass-actions.select-action')
                            </span>

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:menu class="border-gray-300 !p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)]">
                        <template v-for="massAction in available.massActions">
                            <li
                                class="group/item relative overflow-visible"
                                v-if="massAction?.options?.length"
                            >
                                <a
                                    class="whitespace-no-wrap !rounded-0 flex cursor-not-allowed justify-between gap-1.5 bg-white px-4 py-2 hover:bg-gray-100"
                                    href="javascript:void(0);"
                                >
                                    <i
                                        class="text-2xl"
                                        :class="massAction.icon"
                                        v-if="massAction?.icon"
                                    >
                                    </i>

                                    <span>
                                        @{{ massAction.title }}
                                    </span>

                                    <i class="!icon-arrow-right text-2xl"></i>
                                </a>

                                <ul class="absolute top-0 z-10 hidden w-max min-w-[150px] rounded border border-gray-300 bg-white shadow-[0_5px_20px_rgba(0,0,0,0.15)] group-hover/item:block ltr:left-full rtl:right-full">
                                    <li v-for="option in massAction.options">
                                        <a
                                            class="whitespace-no-wrap block rounded-t px-4 py-2 hover:bg-gray-100"
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
                                    class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 hover:bg-gray-100"
                                    href="javascript:void(0);"
                                    @click="performMassAction(massAction)"
                                >
                                    <i
                                        class="text-2xl"
                                        :class="massAction.icon"
                                        v-if="massAction?.icon"
                                    >
                                    </i>

                                    @{{ massAction.title }}
                                </a>
                            </li>
                        </template>
                    </x-slot>
                </x-shop::dropdown>

                <div class="ltr:pl-2.5 rtl:pr-2.5">
                    <p class="text-sm font-light text-gray-800">
                        <!-- Need to manage this translation. -->
                        @{{ applied.massActions.indices.length }} of @{{ available.meta.total }} Selected
                    </p>
                </div>
            </div>

            <!-- Search Panel -->
            <div
                class="flex w-full items-center gap-x-1"
                v-else
            >
                <!-- Search Panel -->
                <div class="flex max-w-[445px] items-center max-sm:w-full max-sm:max-w-full">
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="search"
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-base text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 ltr:pr-8 rtl:pl-8"
                            :value="getAppliedColumnValues('all')"
                            placeholder="@lang('shop::app.components.datagrid.toolbar.search.title')"
                            @keyup.enter="filterPage"
                        >

                        <div class="icon-search pointer-events-none absolute top-2 flex items-center text-xl ltr:right-2.5 rtl:left-2.5">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-4">
            <!-- Items Per Page Dropdown -->
            <x-shop::dropdown position="bottom-left">
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button class="flex w-full max-w-[200px] cursor-pointer items-center justify-between gap-4 rounded-lg border border-[#E9E9E9] bg-white py-2 text-sm transition-all hover:border-gray-400 focus:border-gray-400 ltr:pl-4 ltr:pr-3 max-md:ltr:pl-2.5 max-md:ltr:pr-2.5 rtl:pl-3 rtl:pr-4 max-md:rtl:pl-2.5 max-md:rtl:pr-2.5">
                        <span v-text="applied.pagination.perPage"></span>

                        <span class="icon-arrow-down text-2xl"></span>
                    </button>
                </x-slot>

                <!-- Dropdown Content -->
                <x-slot:menu>
                    <x-shop::dropdown.menu.item
                        v-for="perPageOption in available.meta.per_page_options"
                        v-text="perPageOption"
                        @click="changePerPageOption(perPageOption)"
                    />
                </x-slot>
            </x-shop::dropdown>

            <!-- Filters Activation Button -->
            <x-shop::drawer width="350px" ref="filterDrawer">
                <x-slot:toggle>
                    <button 
                        class="flex w-full max-w-[200px] cursor-pointer items-center justify-between gap-4 rounded-lg border border-[#E9E9E9] bg-white py-2 text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:w-[110px] ltr:pl-3 ltr:pr-4 max-md:ltr:pl-2.5 max-md:ltr:pr-2.5 rtl:pl-4 rtl:pr-3 max-md:rtl:pl-2.5 max-md:rtl:pr-2.5"
                        :class="{'[&>*]:text-blue-600': applied.filters.columns.length > 1}"
                    >
                        <span class="flex items-center justify-between gap-1.5">
                            <span class="icon-filter text-2xl"></span>

                            @lang('shop::app.components.datagrid.toolbar.filter.title')
                        </span>
                    </button>
                </x-slot>

                <x-slot:header class="border-b border-[#E9E9E9]">
                    @lang('shop::app.components.datagrid.filters.title')
                </x-slot>

                <x-slot:content>
                    <x-shop::datagrid.filters />
                </x-slot>
            </x-shop::drawer>
        </div>
    </div>
</template>
