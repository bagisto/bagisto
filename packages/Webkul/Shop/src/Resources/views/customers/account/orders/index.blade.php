<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="orders" />
    @endSection

    <div class="flex items-center justify-between">
        <div class="">
            <h2 class="text-2xl font-medium max-sm:text-xl">
                @lang('shop::app.customers.account.orders.title')
            </h2>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

    <!-- For Desktop View -->
    <div class="max-sm:hidden">
        <x-shop::datagrid :src="route('shop.customers.account.orders.index')" />
    </div>

    <!-- For Mobile View -->
    <div class="hidden max-sm:block">
        <x-shop::datagrid :src="route('shop.customers.account.orders.index')">
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
                        <div class="w-full p-4 border transition-all hover:bg-gray-50 [&>*]:border-0 mb-4 last:mb-0">
                            <a :href="record.actions[0].url">
                                <div class="flex justify-between">
                                    <div class="text-sm font-semibold">
                                        <p>@lang('Order Id:')#@{{ record.id }}</p>
    
                                        <p class="text-xs font-normal" v-text="record.created_at"></p>
                                    </div>
    
                                    <p v-html="record.status"></p>
                                </div>
        
                                <div class="mt-2.5 text-xs font-normal">
                                    <p>@lang('Subtotal')</p>
    
                                    <p class="text-xl font-semibold" v-text="record.grand_total"></p>
                                </div>
                            </a>
                        </div>
                    </template>
                </template>
            </template>
        </x-shop::datagrid>
    </div>
    
    {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}

</x-shop::layouts.account>
