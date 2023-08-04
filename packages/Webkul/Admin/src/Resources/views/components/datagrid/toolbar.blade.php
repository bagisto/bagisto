<div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
    <!-- Left Toolbar -->
    <div class="flex gap-x-[4px] items-center w-full">
        <!-- Filters Activation Button -->
        <div class="">
            <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600 transition-all hover:border-gray-400">
                <span class="icon-filter text-[24px]"></span>

                <span>Filter</span>

                <span class="icon-arrow-up text-[24px]"></span>
            </div>

            <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow">
            </div>
        </div>

        <!-- Search Panel -->
        <div class="flex items-center max-w-[445px] max-sm:max-w-full max-sm:w-full">
            <label for="organic-search" class="sr-only">
                Search
            </label>

            <div class="relative w-full">
                <div class="icon-serch text-[22px] absolute left-[12px] top-[6px] flex items-center pointer-events-none">
                </div>

                <input
                    type="text"
                    name="search"
                    value=""
                    class="bg-white border border-gray-300 rounded-lg block w-full px-[40px] py-[6px] leading-6 text-gray-400 transition-all hover:border-gray-400"
                    placeholder="Search"
                    @keyup.enter="filterPage"
                >

                <button
                    type="button"
                    class="icon-camera text-[22px] absolute top-[12px] right-[12px] flex items-center pr-[12px]"
                >
                </button>
            </div>
        </div>
    </div>

    <!-- Right Toolbar -->
    <div class="flex gap-x-[16px]">
        <span class="icon-settings text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>

        <div class="flex gap-x-[8px] items-center">
            <x-admin::dropdown>
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button class="inline-flex gap-x-[8px] items-center justify-between text-gray-600 py-[6px] px-[10px] text-center leading-[24px] w-full max-w-max bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400">
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

            <p class="text-gray-600 whitespace-nowrap max-sm:hidden">per page</p>

            <div
                class="inline-flex gap-x-[4px] items-center justify-between ml-[8px] text-gray-600 py-[6px] px-[8px] leading-[24px] text-center w-full max-w-max bg-white border border-gray-300 rounded-[6px] marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400 max-sm:hidden"
                v-text="available.meta.current_page"
            >
            </div>

            <div class="text-gray-600 whitespace-nowrap">
                <span>of </span>

                <span v-text="available.meta.last_page"></span>
            </div>

            <!-- Pagination -->
            <div class="flex gap-[4px] items-center">
                <div
                    class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all active:border-gray-300 hover:bg-gray-100 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black"
                    @click="changePage('previous')"
                >
                    <span class="icon-sort-left text-[24px]"></span>
                </div>

                <div
                    class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 p-[6px] text-center w-full max-w-max rounded-[6px] border border-transparent cursor-pointer transition-all active:border-gray-300 hover:bg-gray-100 marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black"
                    @click="changePage('next')"
                >
                    <span class="icon-sort-right text-[24px]"></span>
                </div>
            </div>
        </div>
    </div>
</div>
