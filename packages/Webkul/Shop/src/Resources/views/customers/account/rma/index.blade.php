<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.rma.customer.title')
    </x-slot:title>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="rma"></x-shop::breadcrumbs>
    @endSection

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-medium">
                @lang('shop::app.rma.customer-rma-index.heading')
            </h2>

            <a
                href="{{ route('shop.customers.account.rma.create') }}"
                class="secondary-button flex items-center gap-x-2 border-[#E9E9E9] px-5 py-3 font-normal"
            >
                @lang('shop::app.rma.customer.create.heading')
            </a>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.rma.list.before') !!}

        <!-- Datagrid -->
        <div class="max-md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.rma.index')" />
        </div>

        <div class="md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.rma.index')">
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
                        <template v-for="record in available.records">
                            <div class="mb-4 w-full rounded-lg border p-4 transition-all last:mb-0 hover:bg-gray-50">
                                <div class="block space-y-3">
                                    <!-- Row 1 -->
                                    <div class="flex items-start justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500">
                                                @lang('shop::app.customers.account.rma.index.datagrid.id')
                                            </span>
                                            
                                            <span class="text-sm font-semibold text-gray-900">
                                                #@{{ record.id }}
                                            </span>
                                        </div>

                                        <div class="flex flex-col gap-1 text-right">
                                            <span class="text-xs text-gray-500">
                                                @lang('shop::app.customers.account.rma.index.datagrid.order-ref')
                                            </span>
                                            
                                            <span class="text-sm font-semibold text-gray-900"
                                                v-html="record.order_id">
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Row 2 -->
                                    <div class="flex items-start justify-between">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs text-gray-500">
                                                @lang('shop::app.customers.account.rma.index.datagrid.rma-status')
                                            </span>
                                            
                                            <span class="text-sm font-semibold text-gray-900"
                                                v-html="record.title">
                                            </span>
                                        </div>

                                        <div class="flex flex-col gap-1 text-right">
                                            <span class="text-xs text-gray-500">
                                                @lang('shop::app.customers.account.rma.index.datagrid.quantity')
                                            </span>

                                            <span class="text-sm font-semibold text-gray-900"
                                                v-html="record.total_quantity">
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Row 3 -->
                                    <div class="flex items-center justify-between border-t pt-2">
                                        <div class="mt-1 flex flex-col gap-2">
                                            <span class="text-xs text-gray-500">
                                                @lang('shop::app.customers.account.rma.index.datagrid.create')
                                            </span>
                                            
                                            <p class="text-sm text-gray-900">
                                                @{{ record.created_at }}
                                            </p>
                                        </div>

                                        <p
                                            class="mt-1 flex items-center gap-1.5"
                                            v-if="available.actions.length"
                                        >
                                            <span
                                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                :class="action.icon"
                                                v-text="! action.icon ? action.title : ''"
                                                v-for="action in record.actions"
                                                @click="performAction(action)"
                                            >
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                </template>
            </x-shop::datagrid>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.rma.list.after') !!}
    </div>
</x-shop::layouts.account>
