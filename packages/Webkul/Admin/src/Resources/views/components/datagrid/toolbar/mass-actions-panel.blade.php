<v-datagrid-mass-actions-panel
    :available="available"
    :applied="applied"
    @massAction="performMassAction"
>
</v-datagrid-mass-actions-panel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-mass-actions-panel-template"
    >
        <div class="flex w-full items-center gap-x-1">
            <x-admin::dropdown>
                <x-slot:toggle>
                    <button
                        type="button"
                        class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border dark:border-gray-800 bg-white dark:bg-gray-900 px-2.5 py-1.5 text-center leading-6 text-gray-600 dark:text-gray-300 transition-all marker:shadow hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 focus:ring-black"
                    >
                        <span>
                            @lang('admin::app.components.datagrid.toolbar.mass-actions.select-action')
                        </span>

                        <span class="icon-sort-down text-2xl"></span>
                    </button>
                </x-slot>

                <x-slot:menu class="!p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)] dark:border-gray-800">
                    <template v-for="massAction in available.massActions">
                        <li
                            class="group/item relative overflow-visible"
                            v-if="massAction?.options?.length"
                        >
                            <a
                                class="flex gap-1.5 justify-between whitespace-no-wrap cursor-not-allowed rounded-t px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-950"
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

                                <i class="icon-arrow-left text-xl -mt-px"></i>
                            </a>

                            <ul class="absolute ltr:left-full rtl:right-full top-0 z-10 hidden w-max min-w-[150px] border dark:border-gray-800 rounded bg-white dark:bg-gray-900 shadow-[0_5px_20px_rgba(0,0,0,0.15)] group-hover/item:block">
                                <li v-for="option in massAction.options">
                                    <a
                                        class="whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-950"
                                        href="javascript:void(0);"
                                        @click="performMassAction(massAction, option)"
                                    >
                                        @{{ option.label }}
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li v-else>
                            <a
                                class="flex gap-1.5 whitespace-no-wrap rounded-b px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-950"
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
            </x-admin::dropdown>

            <div class="ltr:pl-2.5 rtl:pr-2.5">
                <p class="text-sm font-light text-gray-800 dark:text-white">
                    @{{ "@lang('admin::app.components.datagrid.toolbar.length-of')".replace(':length', applied.massActions.indices.length) }}

                    @{{ "@lang('admin::app.components.datagrid.toolbar.selected')".replace(':total', available.meta.total) }}
                </p>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid-mass-actions-panel', {
            template: '#v-datagrid-mass-actions-panel-template',

            props: ['available', 'applied'],

            methods: {
                performMassAction(currentAction, currentOption = null) {
                    this.$emit('massAction', { currentAction, currentOption });
                },
            },
        });
    </script>
@endPushOnce
