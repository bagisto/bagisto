@php $reviews = $customer->reviews(); @endphp

<div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
    <x-admin::datagrid
        :src="route('admin.customers.customers.view', [
            'id'   => $customer->id,
            'type' => 'reviews'
        ])"
    >
        <template #header="{ available, records }">
            <div class="p-4 flex justify-between">
                <!-- Total Reviews Count -->
                <p class="text-base text-gray-800 leading-none dark:text-white font-semibold">
                    @{{ "@lang('Reviews (:review_count)')".replace(':review_count', available.meta.total) }}
                </p>
            </div>
        </template>

        <template #body="{ columns, records, performAction, available }">
            <div 
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
                            @{{ "@lang('admin::app.customers.customers.view.id')".replace(':id', record.id) }}
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
        </template>
    </x-admin::datagrid>
</div>