@props(['isMultiRow' => false])

<v-table>
    {{ $slot }}
</v-table>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-table-template"
    >
        <div class="w-full overflow-x-auto rounded-xl border">
            <!-- Main Table -->
            <div class="table-responsive box-shadow grid w-full overflow-hidden rounded bg-white">
                <slot name="header">
                    <template v-if="$parent.isLoading">
                        <x-shop::shimmer.datagrid.table.head :isMultiRow="$isMultiRow" />
                    </template>

                    <template v-else>
                        <div
                            class="row grid items-center gap-2.5 border-b border-[#E9E9E9] bg-[#F5F5F5] px-6 py-4 text-sm font-medium text-black"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Mass Actions -->
                            <p v-if="$parent.available.massActions.length">
                                <label for="mass_action_select_all_records">
                                    <input
                                        type="checkbox"
                                        name="mass_action_select_all_records"
                                        id="mass_action_select_all_records"
                                        class="peer hidden"
                                        :checked="['all', 'partial'].includes($parent.applied.massActions.meta.mode)"
                                        @change="$parent.selectAllRecords"
                                    >

                                    <label
                                        for="mass_action_select_all_records"
                                        class="icon-uncheck cursor-pointer rounded-md text-2xl"
                                        :class="[
                                            $parent.applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-check-box' : (
                                                $parent.applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial' : ''
                                            ),
                                        ]"
                                    >
                                    </label>
                                </label>
                            </p>

                            <!-- Columns -->
                            <p
                                v-for="column in $parent.available.columns"
                                class="flex items-center gap-1.5"
                                :class="{'cursor-pointer select-none': column.sortable}"
                                @click="$parent.sortPage(column)"
                            >
                                @{{ column.label }}

                                <i
                                    class="align-text-bottom text-base text-gray-800"
                                    :class="[$parent.applied.sort.order === 'asc' ? 'icon-arrow-down': 'icon-arrow-up']"
                                    v-if="column.index == $parent.applied.sort.column"
                                ></i>
                            </p>

                            <!-- Actions -->
                            <p
                                class="place-self-end"
                                v-if="$parent.available.actions.length"
                            >
                                @lang('shop::app.components.datagrid.table.actions')
                            </p>
                        </div>
                    </template>
                </slot>

                <slot name="body">
                    <template v-if="$parent.isLoading">
                        <x-shop::shimmer.datagrid.table.body :isMultiRow="$isMultiRow" />
                    </template>

                    <template v-else>
                        <template v-if="$parent.available.records.length">
                            <div>
                                <div
                                    class="row grid items-center gap-2.5 border-b bg-white px-6 py-4 font-medium text-gray-600 transition-all"
                                    v-for="record in $parent.available.records"
                                    :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                                >
                                    <!-- Mass Actions -->
                                    <p v-if="$parent.available.massActions.length">
                                        <label :for="`mass_action_select_record_${record[$parent.available.meta.primary_column]}`">
                                            <input
                                                type="checkbox"
                                                class="peer hidden"
                                                :name="`mass_action_select_record_${record[$parent.available.meta.primary_column]}`"
                                                :value="record[$parent.available.meta.primary_column]"
                                                :id="`mass_action_select_record_${record[$parent.available.meta.primary_column]}`"
                                                v-model="$parent.applied.massActions.indices"
                                                @change="$parent.setCurrentSelectionMode"
                                            >

                                            <span class="icon-uncheck peer-checked:icon-check-box cursor-pointer rounded-md text-2xl">
                                            </span>
                                        </label>
                                    </p>

                                    <!-- Columns -->
                                    <p
                                        v-if="record.is_closure"
                                        v-for="column in $parent.available.columns"
                                        v-html="record[column.index]"
                                    >
                                    </p>

                                    <p
                                        v-else
                                        v-for="column in $parent.available.columns"
                                        v-html="record[column.index]"
                                    >
                                    </p>

                                    <!-- Actions -->
                                    <p
                                        v-if="$parent.available.actions.length"
                                        class="place-self-end"
                                    >
                                        <span
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 max-sm:place-self-center"
                                            :class="action.icon"
                                            v-text="!action.icon ? action.title : ''"
                                            v-for="action in record.actions"
                                            @click="$parent.performAction(action)"
                                        >
                                        </span>
                                    </p>
                                </div>
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

                <slot name="footer">
                    <template v-if="$parent.isLoading">
                        <x-shop::shimmer.datagrid.table.footer/>
                    </template>

                    <template v-else>
                        <!-- Information Panel -->
                        <div v-if="$parent.available.records.length" class="flex items-center justify-between p-6">
                            <p class="text-xs font-medium">
                                <p class="text-xs font-medium">
                                    @{{ "@lang('shop::app.components.datagrid.table.showing')".replace(':firstItem', $parent.available.meta.from) }}
                                    @{{ "@lang('shop::app.components.datagrid.table.to')".replace(':lastItem', $parent.available.meta.to) }}
                                    @{{ "@lang('shop::app.components.datagrid.table.of')".replace(':total', $parent.available.meta.total) }}
                                </p>
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
                                <ul class="inline-flex items-center -space-x-px">
                                    <li @click="$parent.changePage('previous')">
                                        <a
                                            href="javascript:void(0);"
                                            class="flex h-[37px] w-[35px] items-center justify-center border border-[#E9E9E9] font-medium leading-normal hover:bg-gray-100 ltr:rounded-l-lg rtl:rounded-r-lg"
                                            aria-label="@lang('shop::app.components.datagrid.table.previous-page')"
                                        >
                                            <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                                        </a>
                                    </li>

                                    <li>
                                        <input
                                            type="text"
                                            :value="$parent.available.meta.current_page"
                                            class="max-w-[42px] border border-[#E9E9E9] px-4 pb-1.5 pt-1.5 text-center font-medium leading-normal text-black hover:bg-gray-100"
                                            @change="$parent.changePage(parseInt($event.target.value))"
                                            aria-label="@lang('shop::app.components.datagrid.table.page-number')"
                                        >
                                    </li>

                                    <li @click="$parent.changePage('next')">
                                        <a
                                            href="javascript:void(0);"
                                            class="flex h-[37px] w-[35px] items-center justify-center border border-[#E9E9E9] font-medium leading-normal hover:bg-gray-100 ltr:rounded-r-lg rtl:rounded-l-lg"
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
        app.component('v-table', {
            template: '#v-table-template',

            computed: {
                gridsCount() {
                    let count = this.$parent.available.columns.length;

                    if (this.$parent.available.actions.length) {
                        ++count;
                    }

                    if (this.$parent.available.massActions.length) {
                        ++count;
                    }

                    return count;
                },
            },
        });
    </script>
@endpushOnce
