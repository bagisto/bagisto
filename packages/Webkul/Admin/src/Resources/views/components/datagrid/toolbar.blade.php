<div class="mt-[28px] flex items-center justify-between gap-[16px] max-md:flex-wrap">
    <!-- Left Toolbar -->
    <div class="flex w-full items-center gap-x-[4px]">
        <!-- Filters Activation Button -->
        <div
            class=""
            @click="toggleFilters"
        >
            <div class="focus:ring-gratext-gray-600 inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border border-gray-300 bg-white px-[4px] py-[6px] text-center font-semibold text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2">
                <span class="icon-filter text-[24px]"></span>

                <span>Filter</span>

                <span class="icon-arrow-up text-[24px]"></span>
            </div>

            <div class="z-10 hidden w-full divide-y divide-gray-100 rounded bg-white shadow">
            </div>
        </div>

        <!-- Search Panel -->
        <div class="flex max-w-[445px] items-center max-sm:w-full max-sm:max-w-full">
            <label
                class="sr-only"
                for="organic-search"
            >
                Search
            </label>

            <div class="relative w-full">
                <input
                    type="text"
                    name="search"
                    value=""
                    class="block w-full rounded-lg border border-gray-300 bg-white py-[6px] pl-[12px] leading-6 text-gray-400 transition-all hover:border-gray-400"
                    placeholder="Search"
                    @keyup.enter="filterPage"
                >

                <div class="icon-search pointer-events-none absolute right-[10px] top-[8px] flex items-center text-[22px]">
                </div>
            </div>
        </div>

        <div class="pl-[10px]">
            <p class="text-[14px] font-light">
                @{{ available.meta.total }} Results
            </p>
        </div>
    </div>

    <!-- Right Toolbar -->
    <div class="flex gap-x-[16px] px-[12px]">
        <span class="icon-settings cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100"></span>

        <div class="flex items-center gap-x-[8px]">
            <x-admin::dropdown>
                <!-- Dropdown Toggler -->
                <x-slot:toggle>
                    <button class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[8px] rounded-[6px] border border-gray-300 bg-white px-[10px] py-[6px] text-center leading-[24px] text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black">
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
                class="ml-[8px] inline-flex w-full max-w-max appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border border-gray-300 bg-white px-[12px] py-[6px] text-center leading-[24px] text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black max-sm:hidden"
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
                    class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border border-transparent p-[6px] text-center text-gray-600 transition-all marker:shadow hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black active:border-gray-300"
                    @click="changePage('previous')"
                >
                    <span class="icon-sort-left text-[24px]"></span>
                </div>

                <div
                    class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-[4px] rounded-[6px] border border-transparent p-[6px] text-center text-gray-600 transition-all marker:shadow hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black active:border-gray-300"
                    @click="changePage('next')"
                >
                    <span class="icon-sort-right text-[24px]"></span>
                </div>
            </div>
        </div>
    </div>
</div>
