<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.customers.reviews.index.title')
    </x-slot:title>

    <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="py-[11px] text-[20px] text-gray-800 font-bold">
            @lang('admin::app.customers.reviews.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Dropdown -->
            <x-admin::dropdown position="bottom-right">
                <x-slot:toggle>
                    <span class="icon-setting p-[6px] rounded-[6px] text-[24px]  cursor-pointer transition-all hover:bg-gray-100"></span>
                </x-slot:toggle>

                <x-slot:content class="w-[174px] max-w-full !p-[8PX] border border-gray-300 rounded-[4px] z-10 bg-white shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)]">
                    <div class="grid gap-[2px]">
                        <!-- Current Channel -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Channel - {{ core()->getCurrentChannel()->name }}
                            </p>
                        </div>

                        <!-- Current Locale -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Language - {{ core()->getCurrentLocale()->name }}
                            </p>
                        </div>

                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <!-- Export Modal -->
                            <x-admin::modal ref="exportModal">
                                <x-slot:toggle>
                                    <p class="text-gray-600 font-semibold leading-[24px]">
                                        Export                                            
                                    </p>
                                </x-slot:toggle>

                                <x-slot:header>
                                    <p class="text-[18px] text-gray-800 font-bold">
                                        @lang('Download')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="p-[16px]">
                                        <x-admin::form action="">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="format"
                                                    id="format"
                                                >
                                                    <option value="xls">XLS</option>
                                                    <option value="csv">CLS</option>
                                                </x-admin::form.control-group.control>
                                            </x-admin::form.control-group>
                                        </x-admin::form>
                                    </div>
                                </x-slot:content>
                                <x-slot:footer>
                                    <!-- Save Button -->
                                    <button
                                        type="submit" 
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('Export')
                                    </button>
                                </x-slot:footer>
                            </x-admin::modal>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::dropdown>
        </div>
    </div>
    
    <x-admin::datagrid
        src="{{ route('admin.customer.review.index') }}"
        :isMultiRow="true"
    >
        {{-- Datagrid Header --}}
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading }">
            <template v-if="! isLoading">
                <div class="row grid grid-rows-1 grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] items-center px-[16px] py-[10px] border-b-[1px] border-gray-300">
                    <div
                        class="flex gap-[10px] items-center"
                        v-for="(columnGroup, index) in [['name', 'product_name', 'product_review_status'], ['rating', 'created_at', 'product_review_id'], ['title', 'comment']]"
                    >
                        <label
                            class="flex gap-[4px] w-max items-center cursor-pointer select-none"
                            for="mass_action_select_all_records"
                            v-if="! index"
                        >
                            <input 
                                type="checkbox" 
                                name="mass_action_select_all_records"
                                id="mass_action_select_all_records"
                                class="hidden peer"
                                :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                @change="selectAllRecords"
                            >
                
                            <span
                                class="icon-uncheckbox cursor-pointer rounded-[6px] text-[24px]"
                                :class="[
                                    applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:text-blue-600' : (
                                        applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:text-navyBlue' : ''
                                    ),
                                ]"
                            >
                            </span>
                        </label>

                        {{-- Product Name, Review Status --}}
                        <p class="text-gray-600">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 font-medium': applied.sort.column == column,
                                            'cursor-pointer': columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === column)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span>

                            <i
                                class="ml-[5px] text-[16px] text-gray-800 align-text-bottom"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>               

             {{-- Datagrid Head Shimmer --}}
             <template v-else>
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true"></x-admin::shimmer.datagrid.table.head>
            </template>
        </template>

        <template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading }">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[2fr_1fr_minmax(150px,_4fr)_0.5fr] px-[16px] py-[10px] border-b-[1px] border-gray-300"
                    v-for="record in records"
                >
                    {{-- Name, Product, Description --}}
                    <div class="flex gap-[10px]">
                        <input 
                            type="checkbox" 
                            :name="`mass_action_select_record_${record.product_review_id}`"
                            :id="`mass_action_select_record_${record.product_review_id}`"
                            :value="record.product_review_id"
                            class="hidden peer"
                            v-model="applied.massActions.indices"
                            @change="setCurrentSelectionMode"
                        >
            
                        <label 
                            class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                            :for="`mass_action_select_record_${record.product_review_id}`"
                        ></label>

                        <div class="flex flex-col gap-[6px]">
                            <p
                                class="text-[16px] text-gray-800 font-semibold"
                                v-text="record.customer_full_name"
                            >
                            </p>

                            <p
                                class="text-gray-600"
                                v-text="record.product_name"
                            >
                            </p>

                            <p
                                :class="{
                                    'label-cancelled': record.product_review_status === 'disapproved',
                                    'label-pending': record.product_review_status === 'pending',
                                    'label-active': record.product_review_status === 'approved',
                                }"
                                v-text="record.product_review_status"
                            >
                            </p>
                        </div>
                    </div>

                    {{-- Rating, Date, Id Section --}}
                    <div class="flex flex-col gap-[6px]">
                        <div class="flex">
                            <x-admin::star-rating 
                                :is-editable="false"
                                ::value="record.rating"
                            >
                            </x-admin::star-rating>
                        </div>

                        <p
                            class="text-gray-600"
                            v-text="record.created_at"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                        >
                            @{{ "@lang('admin::app.customers.reviews.index.datagrid.review-id')".replace(':review-id', record.product_review_id) }}
                        </p>
                    </div>

                    {{-- Title, Description --}}
                    <div class="flex flex-col gap-[6px]">
                        <p
                            class="text-[16px] text-gray-800 font-semibold"
                            v-text="record.title"
                        >
                        </p>

                        <p
                            class="text-gray-600"
                            v-text="record.comment"
                        >
                        </p>
                    </div>

                    <div class="flex gap-[5px] place-content-end self-center">
                        {{-- Review Delete Button --}}
                        <a :href=`{{ route('admin.customer.review.delete', '') }}/${record.product_review_id}`>
                            <span class="icon-delete text-[24px] ml-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                        </a>

                        {{-- View Button --}}
                        <a :href=`{{ route('admin.customer.review.edit', '') }}/${record.product_review_id}`>
                            <span class="icon-sort-right text-[24px] ml-[4px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100"></span>
                        </a>
                    </div>
                </div>
            </template>

            {{-- Datagrid Body Shimmer --}}
            <template v-else>
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true"></x-admin::shimmer.datagrid.table.body>
            </template>
        </template>
    </x-admin::datagrid>
</x-admin::layouts>