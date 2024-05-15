<x-shop::dropdown position="bottom-left">
    <x-slot:toggle>
        <button class="flex w-full max-w-[200px] cursor-pointer items-center justify-between gap-4 rounded-lg border border-[#E9E9E9] bg-white px-4 py-2 text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-sm:justify-center max-sm:gap-3 max-sm:px-3">
            <span v-text="applied.pagination.perPage"></span>

            <span class="icon-arrow-down text-2xl"></span>
        </button>
    </x-slot>

    <x-slot:menu>
        <x-shop::dropdown.menu.item
            v-for="perPageOption in available.meta.per_page_options"
            v-text="perPageOption"
            @click="changePerPageOption(perPageOption)"
        />
    </x-slot>
</x-shop::dropdown>
