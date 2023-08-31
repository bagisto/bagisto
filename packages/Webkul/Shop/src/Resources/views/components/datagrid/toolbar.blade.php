<template v-if="isLoading">
    <x-shop::shimmer.datagrid.toolbar/>
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
                <x-shop::dropdown>
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[8px] rounded-[6px] border border-gray-300 bg-white px-[10px] py-[6px] text-center leading-[24px] text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black">
                            <span>
                                @lang('shop::app.components.datagrid.toolbar.mass-actions.select-action')
                            </span>

                            <span class="icon-sort-down text-[24px]"></span>
                        </button>
                    </x-slot:toggle>

                    <!-- Dropdown Content -->
                    <x-slot:menu class="border-gray-300 !p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)]">
                        <template v-for="massAction in available.massActions">
                            <li
                                class="group/item relative overflow-visible"
                                v-if="massAction?.options?.length"
                            >
                                <a
                                    class="whitespace-no-wrap flex cursor-not-allowed justify-between gap-[5px] rounded-t px-4 py-2 hover:bg-gray-100"
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

                                    <i class="icon-arrow-right !icon-arrow-left text-[24px]"></i>
                                </a>

                                <ul class="absolute left-full top-0 z-10 hidden w-max min-w-[150px] rounded-[4px] border border-gray-300 bg-white shadow-[0_5px_20px_rgba(0,0,0,0.15)] group-hover/item:block">
                                    <li v-for="option in massAction.options">
                                        <a
                                            class="whitespace-no-wrap block rounded-t px-4 py-2 hover:bg-gray-100"
                                            href="javascript:void(0);"
                                            v-text="option.name"
                                            @click="performMassAction(massAction, option)"
                                        >
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li v-else>
                                <a
                                    class="whitespace-no-wrap flex gap-[5px] rounded-b px-4 py-2 hover:bg-gray-100"
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
                </x-shop::dropdown>

                <div class="pl-[10px]">
                    <p class="text-[14px] font-light text-gray-800">
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
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-[15px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                            :value="getAppliedColumnValues('all')"
                            placeholder="@lang('shop::app.components.datagrid.toolbar.search.title')"
                            @keyup.enter="filterPage"
                        >

                        <div class="icon-search pointer-events-none absolute right-[10px] top-[8px] flex items-center text-[22px]">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Toolbar -->
        <div class="flex gap-x-[16px]">
            <!-- Items Per Page Dropdown -->
            <x-shop::dropdown position="bottom-left">
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button class="flex justify-between items-center gap-[15px] max-w-[200px] w-full pl-[15px] pr-[12px] py-[7px] rounded-lg bg-white border border-[#E9E9E9] text-[14px] transition-all hover:border-gray-400 focus:border-gray-400 max-md:pr-[10px] max-md:pl-[10px] max-md:border-0 max-md:w-[110px] cursor-pointer">
                        <span v-text="applied.pagination.perPage"></span>

                        <span class="icon-arrow-down text-[24px]"></span>
                    </button>
                </x-slot:toggle>

                <!-- Dropdown Content -->
                <x-slot:menu>
                    <x-shop::dropdown.menu.item
                        v-for="perPageOption in available.meta.per_page_options"
                        v-text="perPageOption"
                        @click="changePerPageOption(perPageOption)"
                    >
                    </x-shop::dropdown.menu.item>
                </x-slot:menu>
            </x-shop::dropdown>

            <!-- Filters Activation Button -->
            <x-shop::drawer width="350px">
                <x-slot:toggle>
                    <button class="flex justify-between items-center gap-[15px] max-w-[200px] w-full pl-[12px] pr-[15px] py-[7px] rounded-lg bg-white border border-[#E9E9E9] text-[14px] transition-all hover:border-gray-400 focus:border-gray-400 max-md:pr-[10px] max-md:pl-[10px] max-md:border-0 max-md:w-[110px] cursor-pointer">
                        <span class="flex justify-between items-center gap-[5px]">
                            <span class="icon-filter text-[24px]"></span>

                            @lang('shop::app.components.datagrid.toolbar.filter.title')
                        </span>
                    </button>
                </x-slot:toggle>

                <x-slot:header class="border-b-[1px] border-[#E9E9E9]">
                    @lang('shop::app.components.datagrid.filters.title')
                </x-slot:header>

                <x-slot:content>
                    <x-shop::datagrid.filters></x-shop::datagrid.filters>
                </x-slot:content>
            </x-shop::drawer>
        </div>
    </div>
</template>
