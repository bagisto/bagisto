<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.downloadable-products.name')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="downloadable-products" />
    @endSection

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto">
        <div class="mb-8 flex items-center max-sm:mb-5">
            <!-- Back Button -->
            <a
                class="grid md:hidden"
                href="{{ route('shop.customers.account.index') }}"
            >
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-sm:text-xl ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.downloadable-products.name')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

            <!-- For Desktop View -->
        <div class="max-sm:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.downloadable_products.index')" />
        </div>

        <!-- For Mobile View -->
        <div class="hidden max-sm:block">
            <x-shop::datagrid :src="route('shop.customers.account.downloadable_products.index')">
                <!-- Datagrid Header -->
                <template #header="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <div class="hidden"></div>
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
                        <x-shop::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <div class="grid gap-4">
                            <template v-for="record in available.records">
                                <div class="grid w-full gap-2.5 rounded-md border p-4 transition-all">
                                    <div class="flex justify-between">
                                        <div class="text-sm font-semibold">
                                            <p>@lang('Order Id:')#@{{ record.increment_id }}</p>

                                            <p class="text-xs font-normal">
                                                @{{ record.created_at }}
                                            </p>
                                        </div>

                                        <p v-html="record.status"></p>
                                    </div>
            
                                    <div class="text-xs font-normal">
                                        <p
                                            class="text-sm font-semibold"
                                            v-html="record.product_name"
                                        >
                                        </p>

                                        <p>@lang('Remaining Downloads') : @{{ record.remaining_downloads }}</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </template>
            </x-shop::datagrid>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}

    </div>
</x-shop::layouts.account>