<x-shop::dropdown position="bottom-left">
    <x-slot:toggle>
        <button class="flex justify-between items-center gap-4 max-w-[200px] w-full ltr:pl-4 rtl:pr-4 ltr:pr-3 rtl:pl-3 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:ltr:pr-2.5 max-md:rtl:pl-2.5 max-md:ltr:pl-2.5 max-md:rtl:pr-2.5 cursor-pointer">
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
