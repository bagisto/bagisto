<div
    class="flex w-full items-center gap-x-1"
    v-else
>
    <div class="flex max-w-[445px] items-center max-sm:w-full max-sm:max-w-full">
        <div class="relative w-full">
            <input
                type="text"
                name="search"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 ltr:pr-8 rtl:pl-8 text-base text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                :value="getAppliedColumnValues('all')"
                placeholder="@lang('shop::app.components.datagrid.toolbar.search.title')"
                @keyup.enter="filterPage"
            >

            <div class="icon-search pointer-events-none absolute rtl:left-2.5 ltr:right-2.5 top-2 flex items-center text-xl">
            </div>
        </div>
    </div>
</div>
