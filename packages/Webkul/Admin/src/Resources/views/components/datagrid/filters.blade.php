<div class="px-[16px] py-[23px] border border-gray-300 rounded-[4px] bg-white shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)]">
    <!-- Custom Filter -->
    <div v-for="column in available.columns">
        <div v-if="column.type === 'date_range' || column.type === 'datetime_range'">
            <div class="flex justify-between items-center">
                <p
                    class="text-gray-800 font-medium leading-[24px]"
                    v-text="column.label"
                >
                </p>

                <div class="flex gap-x-[5px] items-center">
                    <p class="text-[12px] text-blue-600 font-medium leading-[24px]">
                        Clear All
                    </p>

                    <span class="icon-arrow-up text-[24px]"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 my-[16px] gap-[5px]">
                <p
                    class="text-gray-600 font-medium text-center leading-[24px] px-[8px] py-[6px] border border-gray-300 rounded-[6px] cursor-pointer"
                    v-for="option in column.options"
                    v-text="option.label"
                    @click="filterPage(
                        $event,
                        column,
                        { quickFilter: { isActive: true, selectedFilter: option } }
                    )"
                >
                </p>

                <div class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black ">
                    <input
                        :type="column.input_type"
                        :name="`${column.index}[from]`"
                        value=""
                        :placeholder="column.label"
                        :ref="`${column.index}[from]`"
                        @change="filterPage(
                            $event,
                            column,
                            { range: { name: 'from' }, quickFilter: { isActive: false } }
                        )"
                    />
                </div>

                <div class="inline-flex gap-x-[8px] items-center justify-between text-[14px] text-gray-400 py-[6px] px-[10px] text-center leading-[24px] w-full bg-white border border-gray-300 rounded-[6px] cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black ">
                    <input
                        type="column.input_type"
                        :name="`${column.index}[to]`"
                        value=""
                        :placeholder="column.label"
                        :ref="`${column.index}[from]`"
                        @change="filterPage(
                            $event,
                            column,
                            { range: { name: 'to' }, quickFilter: { isActive: false } }
                        )"
                    />
                </div>

                <div class="flex gap-2">
                    <div
                        class="inline-flex gap-2 p-1 border border-black"
                        v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                    >
                        <span v-text="appliedColumnValue.join(' to ')"></span>

                        <span
                            class="cursor-pointer"
                            @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                        >
                            X
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="flex justify-between items-center">
                <p
                    class="text-gray-800 font-medium leading-[24px]"
                    v-text="column.label"
                >
                </p>

                <span class="icon-arrow-up text-[24px]"></span>
            </div>

            <div class="grid my-[16px]">
                <input
                    type="text"
                    :name="column.index"
                    class="text-[14px] bg-white border border-gray-300 rounded-[6px] block w-full px-[8px] py-[6px] leading-[24px] text-gray-400"
                    :placeholder="column.label"
                    @keyup.enter="filterPage($event, column)"
                />
            </div>

            <div class="flex gap-2">
                <div
                    class="inline-flex gap-2 p-1 border border-black"
                    v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                >
                    <span v-text="appliedColumnValue"></span>

                    <span
                        class="cursor-pointer"
                        @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                    >
                        X
                    </span>
                </div>
            </div>
        </div>

        <span class="block w-full my-[5px] border border-[#E9E9E9]"></span>
    </div>
</div>
