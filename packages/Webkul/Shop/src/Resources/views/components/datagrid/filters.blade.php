<div v-for="column in available.columns">
    <div v-if="column.filterable">
        <!-- Boolean -->
        <div v-if="column.type === 'boolean'">
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
                        @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                    </p>
                </div>
            </div>

            <div class="mb-[8px] mt-[5px]">
                <select
                    class="custom-select block w-full py-2 px-3 shadow bg-white border border-[#E9E9E9] rounded-lg text-[16px] transition-all hover:border-gray-400 focus:border-gray-400"
                    @change="filterPage($event, column)"
                >
                    <option value="">@lang('admin::app.components.datagrid.filters.select')</option>

                    <option
                        :value="option.value"
                        v-for="option in column.options"
                        v-text="option.label"
                    >
                    </option>
                </select>
            </div>

            <div class="mb-[16px] flex gap-2">
                <p
                    class="flex items-center rounded-[4px] bg-gray-600 px-[8px] py-[4px] font-semibold text-white"
                    v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                >
                    <span v-text="appliedColumnValue"></span>

                    <span
                        class="icon-cross cursor-pointer text-[18px] text-white ltr:ml-[5px] rtl:mr-[5px]"
                        @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                    >
                    </span>
                </p>
            </div>
        </div>

        <!-- Dropdown -->
        <div v-else-if="column.type === 'dropdown'">
            <!-- Basic -->
            <div v-if="column.options.type === 'basic'">
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
                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                        </p>
                    </div>
                </div>

                <div class="mb-[8px] mt-[5px]">
                    <select
                        class="custom-select block w-full py-2 px-3 shadow bg-white border border-[#E9E9E9] rounded-lg text-[16px] transition-all hover:border-gray-400 focus:border-gray-400"
                        @change="filterPage($event, column)"
                    >
                        <option value="">@lang('admin::app.components.datagrid.filters.select')</option>

                        <option
                            :value="option.value"
                            v-for="option in column.options.params.options"
                            v-text="option.label"
                        >
                        </option>
                    </select>
                </div>

                <div class="mb-[16px] flex gap-2">
                    <p
                        class="flex items-center rounded-[4px] bg-gray-600 px-[8px] py-[4px] font-semibold text-white"
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

            <!-- Searchable -->
            <div v-else-if="column.options.type === 'searchable'">
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
                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                        </p>
                    </div>
                </div>

                <div class="mb-[8px] mt-[5px]">
                    <v-datagrid-searchable-dropdown
                        :options="column.options"
                        @select-option="filterPage($event, column)"
                    >
                    </v-datagrid-searchable-dropdown>
                </div>

                <div class="mb-[16px] flex gap-2">
                    <p
                        class="flex items-center rounded-[4px] bg-gray-600 px-[8px] py-[4px] font-semibold text-white"
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

        {{-- Date Range --}}
        <div v-else-if="column.type === 'date_range'">
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
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-datagrid-searchable-dropdown-template">
        <div class="relative">
            <div class="relative my-2 rounded border-2">
                <ul class="list-reset">
                    <li class="p-2">
                        <input
                            class="block w-full rounded-[6px] border border-gray-300 bg-white px-[8px] py-[6px] text-[14px] leading-[24px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                            @keyup="lookUp($event)"
                        >
                    </li>

                    <ul>
                        <li v-if="!isMinimumCharacters">
                            <p
                                class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                v-text="'Type atleast 2 characters......'"
                            >
                            </p>
                        </li>

                        <li v-else-if="!searchedOptions.length">
                            <p
                                class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                v-text="'No result found...'"
                            >
                            </p>
                        </li>

                        <li
                            v-for="option in searchedOptions"
                            v-else
                        >
                            <p
                                class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                v-text="option.label"
                                @click="selectOption(option)"
                            >
                            </p>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid-searchable-dropdown', {
            template: '#v-datagrid-searchable-dropdown-template',

            props: ['options'],

            data() {
                return {
                    isMinimumCharacters: false,

                    searchedOptions: [],
                };
            },

            methods: {
                lookUp($event) {
                    let params = this.options.params;

                    params['search'] = $event.target.value;

                    if (! (params['search'].length > 1)) {
                        this.searchedOptions = [];

                        this.isMinimumCharacters = false;

                        return;
                    }

                    this.$axios
                        .get('{{ route('shop.customer.datagrid.look_up') }}', {
                            params
                        })
                        .then(({
                            data
                        }) => {
                            this.isMinimumCharacters = true;

                            this.searchedOptions = data;
                        });
                },

                selectOption(option) {
                    this.searchedOptions = [];

                    this.$emit('select-option', {
                        target: {
                            value: option.value
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce
