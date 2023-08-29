<div>
    <!-- Custom Filter -->
    <x-shop::accordion>
        <x-slot:header>
            <p class="text-[16px] font-medium text-gray-800">
                @lang('shop::app.components.datagrid.filters.custom-filters.title')
            </p>
        </x-slot:header>

        <x-slot:content>
            <div v-for="column in available.columns">
                {{-- Date Range --}}
                <div v-if="column.type === 'date_range'">
                    <div class="flex items-center justify-between">
                        <p
                            class="text-[14px] font-medium leading-[24px] text-gray-800"
                            v-text="column.label"
                        >
                        </p>

                        <div
                            class="flex items-center gap-x-[5px]"
                            @click="removeAppliedColumnAllValues(column.index)"
                        >
                            <p
                                class="cursor-pointer text-[12px] font-medium leading-[24px] text-blue-600"
                                v-if="hasAnyAppliedColumnValues(column.index)"
                            >
                                @lang('shop::app.components.datagrid.filters.custom-filters.clear-all')
                            </p>
                        </div>
                    </div>

                    <div class="mt-[16px] grid grid-cols-2 gap-[5px]">
                        <p
                            class="cursor-pointer rounded-[6px] border border-gray-300 px-[8px] py-[6px] text-center font-medium leading-[24px] text-gray-600"
                            v-for="option in column.options"
                            v-text="option.label"
                            @click="filterPage(
                                $event,
                                column,
                                { quickFilter: { isActive: true, selectedFilter: option } }
                            )"
                        >
                        </p>

                        <x-shop::flat-picker.date ::allow-input="false">
                            <input
                                value=""
                                class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                :type="column.input_type"
                                :name="`${column.index}[from]`"
                                :placeholder="column.label"
                                :ref="`${column.index}[from]`"
                                @change="filterPage(
                                    $event,
                                    column,
                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                )"
                            />
                        </x-shop::flat-picker.date>

                        <x-shop::flat-picker.date ::allow-input="false">
                            <input
                                type="column.input_type"
                                value=""
                                class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                :name="`${column.index}[to]`"
                                :placeholder="column.label"
                                :ref="`${column.index}[from]`"
                                @change="filterPage(
                                    $event,
                                    column,
                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                )"
                            />
                        </x-shop::flat-picker.date>

                        <div class="mb-[16px] flex gap-2">
                            <p
                                class="flex items-center rounded-[3px] bg-gray-600 px-[8px] py-[3px] font-semibold text-white"
                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                            >
                                <span v-text="appliedColumnValue.join(' to ')"></span>

                                <span
                                    class="icon-cancel ml-[5px] cursor-pointer text-[18px] text-white"
                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                >
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Date Time Range --}}
                <div v-else-if="column.type === 'datetime_range'">
                    <div class="flex items-center justify-between">
                        <p
                            class="text-[14px] font-medium leading-[24px] text-gray-800"
                            v-text="column.label"
                        >
                        </p>

                        <div
                            class="flex items-center gap-x-[5px]"
                            @click="removeAppliedColumnAllValues(column.index)"
                        >
                            <p
                                class="cursor-pointer text-[12px] font-medium leading-[24px] text-blue-600"
                                v-if="hasAnyAppliedColumnValues(column.index)"
                            >
                                @lang('shop::app.components.datagrid.filters.custom-filters.clear-all')
                            </p>
                        </div>
                    </div>

                    <div class="my-[16px] grid grid-cols-2 gap-[5px]">
                        <p
                            class="cursor-pointer rounded-[6px] border border-gray-300 px-[8px] py-[6px] text-center font-medium leading-[24px] text-gray-600"
                            v-for="option in column.options"
                            v-text="option.label"
                            @click="filterPage(
                                $event,
                                column,
                                { quickFilter: { isActive: true, selectedFilter: option } }
                            )"
                        >
                        </p>

                        <x-shop::flat-picker.datetime ::allow-input="false">
                            <input
                                value=""
                                class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                :type="column.input_type"
                                :name="`${column.index}[from]`"
                                :placeholder="column.label"
                                :ref="`${column.index}[from]`"
                                @change="filterPage(
                                    $event,
                                    column,
                                    { range: { name: 'from' }, quickFilter: { isActive: false } }
                                )"
                            />
                        </x-shop::flat-picker.datetime>

                        <x-shop::flat-picker.datetime ::allow-input="false">
                            <input
                                type="column.input_type"
                                value=""
                                class="flex min-h-[39px] w-full rounded-[6px] border px-3 py-2 text-[14px] text-gray-600 transition-all hover:border-gray-400"
                                :name="`${column.index}[to]`"
                                :placeholder="column.label"
                                :ref="`${column.index}[from]`"
                                @change="filterPage(
                                    $event,
                                    column,
                                    { range: { name: 'to' }, quickFilter: { isActive: false } }
                                )"
                            />
                        </x-shop::flat-picker.datetime>

                        <div class="mb-[16px] flex gap-2">
                            <p
                                class="flex items-center rounded-[3px] bg-gray-600 px-[8px] py-[3px] font-semibold text-white"
                                v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                            >
                                <span v-text="appliedColumnValue.join(' to ')"></span>

                                <span
                                    class="icon-cancel ml-[5px] cursor-pointer text-[18px] text-white"
                                    @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                                >
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Rest --}}
                <div v-else>
                    <div class="flex items-center justify-between">
                        <p
                            class="text-[14px] font-medium leading-[24px] text-gray-800"
                            v-text="column.label"
                        >
                        </p>

                        <div
                            class="flex items-center gap-x-[5px]"
                            @click="removeAppliedColumnAllValues(column.index)"
                        >
                            <p
                                class="cursor-pointer text-[12px] font-medium leading-[24px] text-blue-600"
                                v-if="hasAnyAppliedColumnValues(column.index)"
                            >
                                @lang('shop::app.components.datagrid.filters.custom-filters.clear-all')
                            </p>
                        </div>
                    </div>

                    <div class="mb-[8px] mt-[5px] grid">
                        <input
                            type="text"
                            class="w-full mb-3 py-2 px-3 shadow border rounded text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                            :name="column.index"
                            :placeholder="column.label"
                            @keyup.enter="filterPage($event, column)"
                        />
                    </div>

                    <div class="mb-[16px] flex gap-2">
                        <p
                            class="flex items-center rounded-[3px] bg-gray-600 px-[8px] py-[3px] font-semibold text-white"
                            v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                        >
                            <span v-text="appliedColumnValue"></span>

                            <span
                                class="icon-cancel ml-[5px] cursor-pointer text-[18px] text-white"
                                @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                            >
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </x-slot:content>
    </x-shop::accordion>
</div>
