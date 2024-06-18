<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <div class="flex justify-between">
        <!-- Total Reviews Count -->
        <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
            @lang('admin::app.customers.customers.view.reviews.count', ['count' => count($customer->reviews)])
        </p>
    </div>

    <x-admin::datagrid
        :src="route('admin.customers.customers.view', [
            'id'   => $customer->id,
            'type' => 'reviews'
        ])"
    >
        <template #header="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
            </template>

            <template v-else>
                <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center border-b border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div
                        class="flex select-none items-center gap-2.5"
                        v-for="(columnGroup, index) in [['product_name', 'status', 'title', 'comment'], ['rating', 'created_at', 'product_review_id']]"
                    >
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'font-medium text-gray-800 dark:text-white': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            available.columns.find(columnTemp => columnTemp.index === column)?.sortable ? sort(available.columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ available.columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span>

                            <i
                                class="align-text-bottom text-base text-gray-800 dark:text-white ltr:ml-1.5 rtl:mr-1.5"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>
        </template>

        <template #body="{
            isLoading,
            available,
            applied,
            selectAll,
            sort,
            performAction
        }">
            <template v-if="isLoading">
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
            </template>

            <template v-else>
                <div 
                    v-if="available.meta.total"
                    class="grid gap-y-4 border-b p-4 transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950"
                    v-for="record in available.records"
                >
                    <div class="flex justify-start [&amp;>*]:flex-1">
                        <div class="flex flex-col gap-1.5">
                            <!-- Review Name -->
                            <p  
                                class="text-base font-semibold leading-none text-gray-800 dark:text-white"
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

                    <div class="flex items-center justify-between gap-x-4">
                        <div class="flex flex-col gap-1.5">
                            <!-- Review Title -->
                            <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
                                @{{ record.title }}
                            </p>

                            <!-- Review Comment -->
                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ record.comment }}
                            </p>
                        </div>

                        <!-- Review associated with product -->
                        <a 
                            :href="`{{ route('admin.catalog.products.edit', '') }}/${record.product_id}`"
                            target="_blank"
                            class="icon-sort-right rtl:icon-sort-left cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"
                        >
                        </a>
                    </div>
                </div>

                <div    
                    v-else
                    class="table-responsive grid w-full"
                >
                    <div class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10">
                        <!-- Placeholder Image -->
                        <img
                            src="{{ bagisto_asset('images/empty-placeholders/reviews.svg') }}"
                            class="h-20 w-20 dark:mix-blend-exclusion dark:invert"
                        />

                        <div class="flex flex-col items-center">
                            <p class="text-base font-semibold text-gray-400">
                                @lang('admin::app.customers.customers.view.datagrid.reviews.empty-reviews')
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </template>
    </x-admin::datagrid>
</div>