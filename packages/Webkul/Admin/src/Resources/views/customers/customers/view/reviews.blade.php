<div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <div class="flex justify-between">
        <!-- Total Reviews Count -->
        <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
            @lang('admin::app.customers.customers.view.reviews.count', ['count' => count($customer->reviews)])
        </p>
    </div>

    <x-admin::datagrid
        :src="route('admin.customers.customers.view', [
            'id'   => $customer->id,
            'type' => 'reviews'
        ])"
    >
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading, available }">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['product_name', 'status', 'title', 'comment'], ['rating', 'created_at', 'product_review_id']]"
                    >
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === column)?.sortable,
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
                                class="ltr:ml-1.5 rtl:mr-1.5 text-base  text-gray-800 dark:text-white align-text-bottom"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>

            <!-- Datagrid Head Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
            </template>
        </template>

        <template #body="{ columns, records, performAction, available, isLoading}">
            <template v-if="! isLoading">
                <div 
                    v-if="available.meta.total"
                    class="grid gap-y-4 p-4 pt-0 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="(record, index) in records"
                >
                    <div class="flex justify-start [&amp;>*]:flex-1">
                        <div class="flex flex-col gap-1.5">
                            <!-- Review Name -->
                            <p  
                                class="text-base text-gray-800 leading-none dark:text-white font-semibold"
                                v-text="record.name"
                            >
                            </p>

                            <!-- Product Name -->
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-html="record.product_name"
                            >
                            </p>

                            <!-- Review Status -->
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-html="record.status"
                            >
                            </p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <!-- Review Rating -->
                            <div class="flex">
                                <x-admin::star-rating
                                    :is-editable="false"
                                    ::value="record.rating"
                                />
                            </div>

                            <!-- Review Created At -->
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-html="record.created_at"
                            >
                            </p>

                            <!-- Review ID -->
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('admin::app.customers.customers.view.reviews.id')".replace(':id', record.product_review_id) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between gap-x-4 items-center">
                        <div class="flex flex-col gap-1.5">
                            <!-- Review Title -->
                            <p
                                class="text-base text-gray-800 leading-none dark:text-white font-semibold"
                                v-text="record.title"
                            >
                            </p>

                            <!-- Review Comment -->
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.comment"
                            >
                            </p>
                        </div>

                        <!-- Review associated with product -->
                        <a 
                            :href="`{{ route('admin.catalog.products.edit', '') }}/${record.product_id}`"
                            target="_blank"
                            class="icon-sort-right text-2xl ltr:ml-1 rtl:mr-1 p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800"
                        >
                        </a>
                    </div>

                    <span
                        v-if="index != records.length - 1"
                        class="block w-full border-b dark:border-gray-800"
                    >
                    </span>
                </div>

                <div    
                    v-else
                    class="table-responsive grid w-full"
                >
                    <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/empty-placeholders/reviews.svg') }}"
                            class="w-20 h-20 dark:invert dark:mix-blend-exclusion"
                        />

                        <div class="flex flex-col items-center">
                            <p class="text-base text-gray-400 font-semibold">
                                @lang('admin::app.customers.customers.view.datagrid.reviews.empty-reviews')
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
            </template>
        </template>
    </x-admin::datagrid>
</div>