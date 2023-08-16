<div class="flex gap-[16px] mt-[28px] justify-between items-center max-md:flex-wrap">
    <!-- Left Toolbar -->
    <div class="flex gap-x-[4px]">
        <!-- Mass Actions Panel -->
        <div class="flex gap-x-[4px] w-full items-center" v-if="applied.massActions.indices.length">
            <!-- Mass Action Dropdown -->
            <x-admin::dropdown>
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button class="inline-flex gap-x-[8px] justify-between w-full max-w-max px-[10px] py-[6px] cursor-pointer appearance-none items-center rounded-[6px] border border-gray-300 bg-white text-center leading-[24px] text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black">
                        <span>
                            @lang('admin::app.components.datagrid.toolbar.mass-actions.select-action')
                        </span>

                        <span class="icon-sort-down text-[24px]"></span>
                    </button>
                </x-slot:toggle>

                <!-- Dropdown Content -->
                <x-slot:menu>
                    <x-admin::dropdown.menu.item
                        v-for="massAction in available.massActions"
                        v-text="massAction.title"
                        @click="setupMassAction(massAction)"
                    >
                    </x-admin::dropdown.menu.item>
                </x-slot:menu>
            </x-admin::dropdown>

            <!-- Mass Action's Options Dropdown -->
            <x-admin::dropdown v-if="applied.massActions.meta.action?.options?.length">
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button class="inline-flex gap-x-[8px] justify-between w-full max-w-max px-[10px] py-[6px] cursor-pointer appearance-none items-center rounded-[6px] border border-gray-300 bg-white text-center leading-[24px] text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black">
                        <span>
                            @lang('admin::app.components.datagrid.toolbar.mass-actions.select-option')
                        </span>

                        <span class="icon-sort-down text-[24px]"></span>
                    </button>
                </x-slot:toggle>

                <!-- Dropdown Content -->
                <x-slot:menu>
                    <x-admin::dropdown.menu.item
                        v-for="option in applied.massActions.meta.action.options"
                        v-text="option.name"
                        @click="setupMassActionOption(option)"
                    >
                    </x-admin::dropdown.menu.item>
                </x-slot:menu>
            </x-admin::dropdown>

            <!-- Mass Action Execution Button -->
            <button
                type="button"
                class="px-[12px] py-[6px] cursor-pointer rounded-[6px] border border-blue-700 bg-blue-600 font-semibold text-gray-50"
                @click="performMassAction"
            >
                @lang('admin::app.components.datagrid.toolbar.mass-actions.submit')
            </button>

            <div class="pl-[10px]">
                <p class="text-[14px] font-light">
                    <!-- Need to manage this translation. -->
                    @{{ applied.massActions.indices.length }} of @{{ available.meta.total }} Selected
                </p>
            </div>
        </div>

        <!-- Filters And Search Panel -->
        <div class="flex gap-x-[4px] w-full items-center" v-else>
            <!-- Filters Activation Button -->
            <div
                class=""
                @click="toggleFilters"
            >
                <div class="inline-flex w-full max-w-max px-[4px] py-[6px] cursor-pointer appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border border-gray-300 bg-white text-center font-semibold text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gratext-gray-600">
                    <span class="icon-filter text-[24px]"></span>

                    <span>
                        @lang('admin::app.components.datagrid.toolbar.filter.title')
                    </span>

                    <span class="icon-arrow-up text-[24px]"></span>
                </div>

                <div class="z-10 hidden w-full divide-y divide-gray-100 rounded bg-white shadow">
                </div>
            </div>

            <!-- Search Panel -->
            <div class="flex max-w-[445px] items-center max-sm:w-full max-sm:max-w-full">
                <div class="relative w-full">
                    <input
                        type="text"
                        name="search"
                        value=""
                        class="block w-full py-[6px] pl-[12px] rounded-lg border border-gray-300 bg-white leading-6 text-gray-400 transition-all hover:border-gray-400"
                        placeholder="@lang('admin::app.components.datagrid.toolbar.search.title')"
                        @keyup.enter="filterPage"
                    >

                    <div class="icon-search flex absolute pointer-events-none right-[10px] top-[8px] items-center text-[22px]">
                    </div>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="pl-[10px]">
                <p class="text-[14px] font-light">
                    <!-- Need to manage this translation. -->
                    @{{ available.meta.total }} Results
                </p>
            </div>
        </div>
    </div>

    <!-- Right Toolbar -->
    <div class="flex gap-x-[16px] px-[12px]">
        <span class="icon-settings cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100"></span>

        <div class="flex items-center gap-x-[8px]">
            <x-admin::dropdown>
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button class="inline-flex w-full max-w-max px-[10px] py-[6px] cursor-pointer appearance-none items-center justify-between gap-x-[8px] rounded-[6px] border border-gray-300 bg-white text-center leading-[24px] text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black">
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

            <p class="whitespace-nowrap text-gray-600 max-sm:hidden">per page</p>

            <div
                class="ml-[8px] inline-flex w-full gap-x-[4px]appearance-none justify-between items-center max-w-max px-[12px] py-[6px] rounded-[6px] border border-gray-300 bg-white text-center leading-[24px] text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black max-sm:hidden"
                v-text="available.meta.current_page"
            >
            </div>

            <div class="whitespace-nowrap text-gray-600">
                <span>of </span>

                <span v-text="available.meta.last_page"></span>
            </div>

            <!-- Pagination -->
            <div class="flex items-center gap-[4px]">
                <div
                    class="inline-flex gap-x-[4px] appearance-none items-center justify-between w-full max-w-max cursor-pointer rounded-[6px] border border-transparent p-[6px] text-center text-gray-600 transition-all marker:shadow hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black active:border-gray-300"
                    @click="changePage('previous')"
                >
                    <span class="icon-sort-left text-[24px]"></span>
                </div>

                <div
                    class="inline-flex gap-x-[4px] appearance-none items-center justify-between w-full max-w-max p-[6px] cursor-pointer rounded-[6px] border border-transparent text-center text-gray-600 transition-all marker:shadow hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black active:border-gray-300"
                    @click="changePage('next')"
                >
                    <span class="icon-sort-right text-[24px]"></span>
                </div>
            </div>
        </div>
    </div>
</div>
