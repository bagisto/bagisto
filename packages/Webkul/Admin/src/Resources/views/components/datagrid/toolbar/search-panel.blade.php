<v-datagrid-search-panel
    :available="available"
    :applied="applied"
    :searched-value="getAppliedColumnValues('all')"
    @search="filterPage"
>
</v-datagrid-search-panel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-search-panel-template"
    >
        <div class="flex w-full items-center gap-x-1">
            <!-- Search Panel -->
            <div class="flex max-w-[445px] items-center max-sm:w-full max-sm:max-w-full">
                <div class="relative w-full">
                    <input
                        type="text"
                        name="search"
                        :value="searchedValue"
                        class="block w-full rounded-lg border dark:border-gray-800 bg-white dark:bg-gray-900 py-1.5 ltr:pl-3 rtl:pr-3 ltr:pr-10 rtl:pl-10 leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400  dark:focus:border-gray-400"
                        placeholder="@lang('admin::app.components.datagrid.toolbar.search.title')"
                        autocomplete="off"
                        @keyup.enter="search"
                    >

                    <div class="icon-search pointer-events-none absolute ltr:right-2.5 rtl:left-2.5 top-2 flex items-center text-2xl">
                    </div>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="ltr:pl-2.5 rtl:pr-2.5">
                <p class="text-sm font-light text-gray-800 dark:text-white">
                    @{{ "@lang('admin::app.components.datagrid.toolbar.results')".replace(':total', available.meta.total) }}
                </p>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid-search-panel', {
            template: '#v-datagrid-search-panel-template',

            props: ['available', 'applied', 'searchedValue'],

            methods: {
                search($event) {
                    this.$emit('search', $event);
                },
            },
        });
    </script>
@endPushOnce
