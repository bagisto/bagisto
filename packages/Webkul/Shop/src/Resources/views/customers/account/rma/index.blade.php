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

        <v-customer-rma></v-customer-rma>

        {!! view_render_event('bagisto.shop.customers.account.rma.list.after') !!}
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-customer-rma-template"
        >
            <!-- Datagrid -->
            <div class="max-md:hidden">
                <x-shop::datagrid :src="route('shop.customers.account.rma.index')" >
                    <!-- Datagrid Header -->
                    <template #header="{
                        isLoading,
                        available,
                        applied,
                        selectAll,
                        sort,
                        performAction
                    }">
                        <template v-if="isLoading">
                            <x-shop::shimmer.datagrid.table.head :isMultiRow="true"/>
                        </template>

                        <template v-else>
                            <div
                                class="row grid items-center gap-2.5 border-b border-zinc-200 bg-zinc-100 px-6 py-4 text-sm font-medium text-black max-md:p-4"
                                style="grid-template-columns: repeat(6, minmax(0, 1fr));"
                            >
                                <div
                                    class="flex gap-2.5 items-center select-none"
                                    v-for="(columnGroup, index) in [['id'], ['order_id'], ['title'], ['total_quantity'], ['created_at']]"
                                >
                                    <p class="text-gray-600 text-base">
                                        <span class="[&>*]:after:content-['_/_']">
                                            <template v-for="column in columnGroup">
                                                <span
                                                    class="after:content-[''] last:after:content-['']"
                                                    :class="{
                                                        'text-gray-800 font-medium': applied.sort.column == column,
                                                        'cursor-pointer hover:text-gray-800': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
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
                                            class="align-text-bottom text-base text-gray-800 ltr:ml-1.5 rtl:mr-1.5"
                                            :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                            v-if="columnGroup.includes(applied.sort.column)"
                                        ></i>
                                    </p>
                                </div>

                                <p class="flex justify-end text-gray-600 hover:text-gray-800 cursor-pointer">
                                    @lang('shop::app.customers.account.rma.index.action')
                                </p>
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
                            <x-shop::shimmer.datagrid.table.body :isMultiRow="true"/>
                        </template>

                        <template v-else>
                            <div
                                class="row grid px-4 py-2.5 border-b transition-all hover:bg-gray-50"
                                style="grid-template-columns: repeat(6, minmax(0, 1fr));"
                                v-for="record in available.records"
                            >
                                <div class="flex gap-x-4 justify-between items-center">
                                    <div class="flex flex-col gap-1.5">
                                        <p
                                            class="text-gray-600"
                                            v-html="record.id ?? 'N/A'"
                                        >
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-4 justify-between items-center">
                                    <div class="flex flex-col gap-1.5">
                                        <p
                                            class="text-gray-600"
                                            v-html="record.order_id"
                                        >
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-4 justify-between items-center">
                                    <div class="flex flex-col gap-1.5">
                                        <p
                                            class="text-gray-600"
                                            v-html="record.title"
                                        >
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-4 justify-between items-center">
                                    <div class="flex flex-col gap-1.5">
                                        <p
                                            class="text-gray-600 "
                                            v-html="record.total_quantity"
                                        >
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-4 justify-between items-center">
                                    <div class="flex flex-col gap-1.5">
                                        <p class="text-gray-600 text-sm" v-html="record.created_at"></p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <p class="flex justify-end">
                                        <!-- Arrow -->
                                        <a :href="`{{{ route('shop.customers.account.rma.view', '') }}}/${record.id}`">
                                            <span class="icon-eye text-2xl ltr:ml-1 rtl:mr-1 rounded-md cursor-pointer transition-all hover:bg-gray-200"></span>
                                        </a>

                                        <span v-if="record.rmaStatus != 'Canceled'">
                                            <span v-if="record.rmaStatus != 'Item Canceled'">
                                                <span v-if="record.rmaStatus != 'Declined'">
                                                    <span v-if="record.rmaStatus != 'Solved'">
                                                        <span v-if="record.rmaStatus != 'Received Package'">
                                                            <a @click="cancelStatus(record.id)">
                                                                <span class="icon-cancel text-2xl ltr:ml-1 rtl:mr-1 rounded-md cursor-pointer transition-all hover:bg-gray-200"></span>
                                                            </a>
                                                        </span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </template>
                    </template>
                </x-shop::datagrid>
            </div>

            <!-- For Mobile View -->
            <div class="md:hidden">
                <x-shop::datagrid :src="route('shop.customers.account.rma.index')" >
                    <!-- Datagrid Header -->
                    <template #header="{
                        isLoading,
                        available,
                        applied,
                        selectAll,
                        sort,
                        performAction
                    }">
                        <template v-if="isLoading">
                            <x-shop::shimmer.datagrid.table.head :isMultiRow="true"/>
                        </template>

                        <template v-else>
                            <div
                                class="row grid items-center gap-2.5 border-b border-zinc-200 bg-zinc-100 px-6 py-4 text-sm font-medium text-black max-md:p-4"
                                style="grid-template-columns: repeat(2, minmax(0, 1fr));"
                            >
                                <div
                                    class="flex gap-2.5 items-center select-none"
                                    v-for="(columnGroup, index) in [['id', 'order_id', 'title'], ['total_quantity', 'created_at']]"
                                >
                                    <p class="text-gray-600 text-base">
                                        <span class="[&>*]:after:content-['_/_']">
                                            <template v-for="column in columnGroup">
                                                <span
                                                    class="after:content-['/'] last:after:content-['']"
                                                    :class="{
                                                        'text-gray-800 font-medium': applied.sort.column == column,
                                                        'cursor-pointer hover:text-gray-800': available.columns.find(columnTemp => columnTemp.index === column)?.sortable,
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
                                            class="align-text-bottom text-base text-gray-800 ltr:ml-1.5 rtl:mr-1.5"
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
                            <x-shop::shimmer.datagrid.table.body :isMultiRow="true"/>
                        </template>

                        <template v-else>
                            <div
                                class="row grid px-4 py-2.5 border-b transition-all hover:bg-gray-50"
                                style="grid-template-columns: repeat(2, minmax(0, 1fr));"
                                v-for="record in available.records"
                            >
                                <div class="flex gap-x-4 justify-between items-center">
                                    <div class="flex flex-col gap-1.5">
                                        <p
                                            class="text-gray-600"
                                            v-html="record.id ?? 'N/A'"
                                        >
                                        </p>

                                        <p
                                            class="text-gray-600"
                                            v-html="record.order_id"
                                        >
                                        </p>

                                        <p
                                            class="text-gray-600"
                                            v-html="record.title"
                                        >
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-x-4 justify-between items-center">
                                    <div class="flex flex-col gap-1.5">

                                    <p
                                        class="text-gray-600 "
                                        v-html="record.total_quantity"
                                    >
                                    </p>

                                    <p class="text-gray-600 text-sm" v-html="record.created_at"></p>

                                    <p class="flex justify-start">
                                        <!-- Arrow -->
                                        <a :href="`{{{ route('shop.customers.account.rma.view', '') }}}/${record.id}`">
                                            <span class="icon-eye text-2xl ltr:ml-1 rtl:mr-1 rounded-md cursor-pointer transition-all hover:bg-gray-200"></span>
                                        </a>

                                        <span v-if="record.rmaStatus != 'Canceled'">
                                            <span v-if="record.rmaStatus != 'Item Canceled'">
                                                <span v-if="record.rmaStatus != 'Declined'">
                                                    <span v-if="record.rmaStatus != 'Solved'">
                                                        <span v-if="record.rmaStatus != 'Received Package'">
                                                            <a @click="cancelStatus(record.id)">
                                                                <span class="icon-cancel text-2xl ltr:ml-1 rtl:mr-1 rounded-md cursor-pointer transition-all hover:bg-gray-200"></span>
                                                            </a>
                                                        </span>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            </div>
                        </template>
                    </template>
                </x-shop::datagrid>
            </div>
        </script>

        <script type="module">
            app.component('v-customer-rma', {
                template: '#v-customer-rma-template',

                data() {
                    return {

                    }
                },

                methods: {
                    cancelStatus(recordId) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.get(`{{ route('shop.customers.account.rma.cancel', '') }}/${recordId}`)
                                    .then(response => {
                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 2000);

                                    }).catch(error => {
                                        console.log(error);
                                    });
                            }
                        });
                    }
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts.account>
