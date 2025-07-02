<x-shop::dropdown position="bottom-left">
    <x-slot:toggle>
        <button class="flex w-full max-w-[200px] cursor-pointer items-center justify-between gap-4 rounded-lg border border-zinc-200 bg-white py-2 text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:py-2 max-sm:py-1.5 ltr:pl-4 ltr:pr-3 max-md:ltr:pl-2.5 max-md:ltr:pr-2.5 rtl:pl-3 rtl:pr-4 max-md:rtl:pl-2.5 max-md:rtl:pr-2.5">
            <span v-text="applied.pagination.perPage"></span>

            <span class="icon-arrow-down text-2xl"></span>
        </button>
    </x-slot>

    <x-slot:menu class="max-md:!py-0">
        <x-shop::dropdown.menu.item
            v-for="perPageOption in available.meta.per_page_options"
            v-text="perPageOption"
            @click="changePerPageOption(perPageOption)"
        />
    </x-slot>
</x-shop::dropdown>
