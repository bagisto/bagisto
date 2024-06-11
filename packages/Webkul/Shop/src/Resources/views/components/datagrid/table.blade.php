@props(['isMultiRow' => false])

<v-datagrid-table
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @selectAll="selectAll"
    @sort="sort"
    @actionSuccess="get"
    @changePage="changePage"
>
    {{ $slot }}
</v-datagrid-table>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-table-template"
    >
        <div class="w-full overflow-x-auto rounded-xl border max-md:rounded-none max-md:border-0">
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white">
                <slot
                    name="header"
                    :is-loading="isLoading"
                    :available="available"
                    :applied="applied"
                    :select-all="selectAll"
                    :sort="sort"
                    :perform-action="performAction"
                >
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.head :isMultiRow="$isMultiRow" />
                    </template>

                    <template v-else>
                        <div
                            class="row grid items-center gap-2.5 border-b border-zinc-200 bg-zinc-100 px-6 py-4 text-sm font-medium text-black max-md:p-4"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Mass Actions -->
                            <p v-if="available.massActions.length">
                                <label for="mass_action_select_all_records">
                                    <input
                                        type="checkbox"
                                        name="mass_action_select_all_records"
                                        id="mass_action_select_all_records"
                                        class="peer hidden"
                                        :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                        @change="selectAll"
                                    >

                                    <span
                                        class="icon-uncheck cursor-pointer rounded-md text-2xl"
                                        :class="[
                                            applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-check-box' : (
                                                applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial' : ''
                                            ),
                                        ]"
                                    >
                                    </span>
                                </label>
                            </p>

                            <!-- Columns -->
                            <template v-for="column in available.columns">
                                <p
                                    class="flex items-center gap-1.5"
                                    :class="{'cursor-pointer select-none': column.sortable}"
                                    @click="sort(column)"
                                    v-if="column.visibility"
                                >
                                    @{{ column.label }}

                                    <i
                                        class="align-text-bottom text-base text-gray-800"
                                        :class="[applied.sort.order === 'asc' ? 'icon-arrow-down': 'icon-arrow-up']"
                                        v-if="column.index == applied.sort.column"
                                    ></i>
                                </p>
                            </template>

                            <!-- Actions -->
                            <p
                                class="place-self-end max-md:place-self-auto"
                                v-if="available.actions.length"
                            >
                                @lang('shop::app.components.datagrid.table.actions')
                            </p>
                        </div>
                    </template>
                </slot>

                <slot
                    name="body"
                    :is-loading="isLoading"
                    :available="available"
                    :applied="applied"
                    :select-all="selectAll"
                    :sort="sort"
                    :perform-action="performAction"
                >
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.body :isMultiRow="$isMultiRow" />
                    </template>

                    <template v-else>
                        <template v-if="available.records.length">
                            <div
                                class="row grid items-center gap-2.5 border-b bg-white px-6 py-4 font-medium text-gray-600 transition-all max-md:p-4 max-md:text-xs"
                                v-for="record in available.records"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                            >
                                <!-- Mass Actions -->
                                <p v-if="available.massActions.length">
                                    <label :for="`mass_action_select_record_${record[available.meta.primary_column]}`">
                                        <input
                                            type="checkbox"
                                            :name="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                            :value="record[available.meta.primary_column]"
                                            :id="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                            class="peer hidden"
                                            v-model="applied.massActions.indices"
                                        >

                                        <span class="icon-uncheck peer-checked:icon-check-box cursor-pointer rounded-md text-2xl">
                                        </span>
                                    </label>
                                </p>

                                <!-- Columns -->
                                <template v-for="column in available.columns">
                                    <p
                                        :class="!available.actions.length ? 'last:text-center' : ''"
                                        v-html="record[column.index]"
                                        v-if="column.visibility"
                                    >
                                    </p>
                                </template>

                                <!-- Actions -->
                                <p v-if="available.actions.length">
                                    <span
                                        class="float-right cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200"
                                        :class="action.icon"
                                        v-for="action in record.actions"
                                        @click="performAction(action)"
                                    >
                                        @{{ ! action.icon ? action.title : '' }}
                                    </span>
                                </p>
                            </div>
                        </template>

                        <template v-else>
                            <div class="row grid border-b border-gray-300 px-4 py-4 text-center text-gray-600">
                                <p>
                                    @lang('shop::app.components.datagrid.table.no-records-available')
                                </p>
                            </div>
                        </template>
                    </template>
                </slot>

                <slot
                    name="footer"
                    :available="available"
                    :applied="applied"
                    :change-page="changePage"
                >
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.footer />
                    </template>

                    <template v-else>
                        <!-- Information Panel -->
                        <div v-if="$parent.available.records.length" class="flex items-center justify-between p-6 max-md:p-2">
                            <p class="text-xs font-medium">
                                @{{ "@lang('shop::app.components.datagrid.table.showing')".replace(':firstItem', $parent.available.meta.from) }}
                                @{{ "@lang('shop::app.components.datagrid.table.to')".replace(':lastItem', $parent.available.meta.to) }}
                                @{{ "@lang('shop::app.components.datagrid.table.of')".replace(':total', $parent.available.meta.total) }}
                            </p>

                            <!-- Pagination -->
                            <div class="flex items-center gap-1">
                                <div
                                    class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-black active:border-gray-300"
                                    @click="changePage('previous')"
                                >
                                    <span class="icon-sort-left text-2xl"></span>
                                </div>

                                <div
                                    class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-black active:border-gray-300"
                                    @click="changePage('next')"
                                >
                                    <span class="icon-sort-right text-2xl"></span>
                                </div>
                            </div>

                            <nav aria-label="@lang('shop::app.components.datagrid.table.page-navigation')">
                                <ul class="inline-flex items-center -space-x-px rounded-lg border border-zinc-200 max-md:px-0">
                                    <li  @click="changePage('previous')">
                                        <a
                                            href="javascript:void(0);"
                                            class="flex h-10 w-9 items-center justify-center font-medium leading-normal hover:bg-gray-100 max-md:h-8 max-md:w-6 max-md:justify-normal"
                                            aria-label="@lang('shop::app.components.datagrid.table.previous-page')"
                                        >
                                            <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                                        </a>
                                    </li>

                                    <li>
                                        <input
                                            type="text"
                                            :value="$parent.available.meta.current_page"
                                            class="max-w-[42px] items-center border-l border-r px-4 py-2 font-medium leading-normal text-black hover:bg-gray-100 max-md:max-w-9 max-md:justify-normal max-md:px-0 max-md:py-1 max-md:text-center"
                                            @change="changePage(parseInt($event.target.value))"
                                            aria-label="@lang('shop::app.components.datagrid.table.page-number')"
                                        >
                                    </li>

                                    <li @click="changePage('next')">
                                        <a
                                            href="javascript:void(0);"
                                            class="flex h-10 w-9 items-center justify-center font-medium leading-normal hover:bg-gray-100 max-md:h-8 max-md:w-6 max-md:justify-normal"
                                            aria-label="@lang('shop::app.components.datagrid.table.next-page')"
                                        >
                                            <span class="icon-arrow-right rtl:icon-arrow-left text-2xl"></span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                </slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid-table', {
            template: '#v-datagrid-table-template',

            props: ['isLoading', 'available', 'applied'],

            emits: ['selectAll', 'sort', 'actionSuccess', 'changePage'],

            computed: {
                gridsCount() {
                    let count = this.available.columns.filter((column) => column.visibility).length;

                    if (this.available.actions.length) {
                        ++count;
                    }

                    if (this.available.massActions.length) {
                        ++count;
                    }

                    return count;
                },
            },

            methods: {
                /**
                 * Change Page.
                 *
                 * The reason for choosing the numeric approach over the URL approach is to prevent any conflicts with our existing
                 * URLs. If we were to use the URL approach, it would introduce additional arguments in the `get` method, necessitating
                 * the addition of a `url` prop. Instead, by using the numeric approach, we can let Axios handle all the query parameters
                 * using the `applied` prop. This allows for a cleaner and more straightforward implementation.
                 *
                 * @param {string|integer} directionOrPageNumber
                 * @returns {void}
                 */
                 changePage(directionOrPageNumber) {
                    let newPage;

                    if (typeof directionOrPageNumber === 'string') {
                        if (directionOrPageNumber === 'previous') {
                            newPage = this.available.meta.current_page - 1;
                        } else if (directionOrPageNumber === 'next') {
                            newPage = this.available.meta.current_page + 1;
                        } else {
                            console.warn('Invalid Direction Provided : ' + directionOrPageNumber);

                            return;
                        }
                    }  else if (typeof directionOrPageNumber === 'number') {
                        newPage = directionOrPageNumber;
                    } else {
                        console.warn('Invalid Input Provided: ' + directionOrPageNumber);

                        return;
                    }

                    /**
                     * Check if the `newPage` is within the valid range.
                     */
                    if (newPage >= 1 && newPage <= this.available.meta.last_page) {
                        this.$emit('changePage', newPage);
                    } else {
                        console.warn('Invalid Page Provided: ' + newPage);
                    }
                },

                /**
                 * Select all records in the datagrid.
                 *
                 * @returns {void}
                 */
                selectAll() {
                    this.$emit('selectAll');
                },

                /**
                 * Perform a sorting operation on the specified column.
                 *
                 * @param {object} column
                 * @returns {void}
                 */
                sort(column) {
                    this.$emit('sort', column);
                },

                /**
                 * Perform the specified action.
                 *
                 * @param {object} action
                 * @returns {void}
                 */
                performAction(action) {
                    const method = action.method.toLowerCase();

                    switch (method) {
                        case 'get':
                            window.location.href = action.url;

                            break;

                        case 'post':
                        case 'put':
                        case 'patch':
                        case 'delete':
                            this.$emitter.emit('open-confirm-modal', {
                                agree: () => {
                                    this.$axios[method](action.url)
                                        .then(response => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            this.$emit('actionSuccess', response.data);
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                                            this.$emit('actionError', error.response.data);
                                        });
                                }
                            });

                            break;

                        default:
                            console.error('Method not supported.');

                            break;
                    }
                },
            },
        });
    </script>
@endpushOnce
