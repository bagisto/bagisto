<v-datagrid-mass-action
    :available="available"
    :applied="applied"
>
    {{ $slot }}
</v-datagrid-mass-action>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-mass-action-template"
    >
        <slot
            name="mass-action"
            :available="available"
            :applied="applied"
            :mass-actions="massActions"
            :validate-mass-action="validateMassAction"
            :perform-mass-action="performMassAction"
        >
            <div class="flex w-full items-center gap-x-1">
                <x-shop::dropdown position="bottom-left">
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black"
                        >
                            <span>
                                @lang('shop::app.components.datagrid.toolbar.mass-actions.select-action')
                            </span>

                            <span class="icon-arrow-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <x-slot:menu class="border-gray-300 !p-0 shadow-[0_5px_20px_rgba(0,0,0,0.15)]">
                        <template v-for="massAction in available.massActions">
                            <li
                                class="group/item relative overflow-visible"
                                v-if="massAction?.options?.length"
                            >
                                <a
                                    class="whitespace-no-wrap !rounded-0 flex cursor-not-allowed justify-between gap-1.5 bg-white px-4 py-2 hover:bg-gray-100"
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

                                    <i class="icon-arrow-right rtl:icon-arrow-left text-2xl"></i>
                                </a>

                                <ul class="absolute top-0 z-10 hidden w-max min-w-[150px] rounded border border-gray-300 bg-white shadow-[0_5px_20px_rgba(0,0,0,0.15)] group-hover/item:block ltr:left-full rtl:right-full">
                                    <li v-for="option in massAction.options">
                                        <a
                                            class="whitespace-no-wrap block rounded-t px-4 py-2 hover:bg-gray-100"
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
                                    class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 hover:bg-gray-100"
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
                </x-shop::dropdown>

                <div class="ltr:pl-2.5 rtl:pr-2.5">
                    <p class="text-sm font-light text-gray-800">
                        @{{ "@lang('shop::app.components.datagrid.toolbar.length-of')".replace(':length', massActions.indices.length) }}

                        @{{ "@lang('shop::app.components.datagrid.toolbar.selected')".replace(':total', available.meta.total) }}
                    </p>
                </div>
            </div>
        </slot>
    </script>

    <script type="module">
        app.component('v-datagrid-mass-action', {
            template: '#v-datagrid-mass-action-template',

            props: ['available', 'applied'],

            data() {
                return {
                    massActions: {
                        meta: {
                            mode: 'none',

                            action: null,
                        },

                        indices: [],

                        value: null,
                    },
                };
            },

            mounted() {
                this.massActions = this.applied.massActions;
            },

            methods: {
                /**
                 * Validate mass action.
                 *
                 * @param {object} filters
                 * @returns {void}
                 */
                validateMassAction() {
                    if (! this.massActions.indices.length) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.datagrid.toolbar.mass-actions.no-records-selected')" });

                        return false;
                    }

                    if (! this.massActions.meta.action) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.datagrid.toolbar.mass-actions.must-select-a-mass-action')" });

                        return false;
                    }

                    if (
                        this.massActions.meta.action?.options?.length &&
                        this.massActions.value === null
                    ) {
                        this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.datagrid.toolbar.mass-actions.must-select-a-mass-action-option')" });

                        return false;
                    }

                    return true;
                },

                /**
                 * Perform mass action.
                 *
                 * @param {object} currentAction
                 * @param {object} currentOption
                 * @returns {void}
                 */
                performMassAction(currentAction, currentOption = null) {
                    this.massActions.meta.action = currentAction;

                    if (currentOption) {
                        this.massActions.value = currentOption.value;
                    }

                    if (! this.validateMassAction()) {
                        return;
                    }

                    const { action } = this.massActions.meta;

                    const method = action.method.toLowerCase();

                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            switch (method) {
                                case 'post':
                                case 'put':
                                case 'patch':
                                    this.$axios[method](action.url, {
                                            indices: this.massActions.indices,
                                            value: this.massActions.value,
                                        })
                                        .then((response) => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            this.$parent.get();
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                                            this.$parent.get();
                                        });

                                    break;

                                case 'delete':
                                    this.$axios[method](action.url, {
                                            indices: this.massActions.indices
                                        })
                                        .then(response => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            /**
                                             * Need to check reason why this.$emit('massActionSuccess') not emitting.
                                             */
                                            this.$parent.get();
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                                            /**
                                             * Need to check reason why this.$emit('massActionSuccess') not emitting.
                                             */
                                            this.$parent.get();
                                        });

                                    break;

                                default:
                                    console.error('Method not supported.');

                                    break;
                            }

                            this.massActions.indices  = [];
                        },
                    });
                },
            },
        });
    </script>
@endPushOnce
