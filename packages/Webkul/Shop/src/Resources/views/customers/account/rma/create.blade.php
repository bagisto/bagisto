@php
    $customAttributes = app('Webkul\RMA\Repositories\RMACustomFieldRepository')->with('options')->where('status', 1)->get();
@endphp

<x-shop::layouts.account>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('shop::app.rma.customer.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="rma.create"></x-shop::breadcrumbs>
    @endSection

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>
    
    <!--Customers logout-->
    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <!-- Heading of the page -->
        <div class="flex items-center justify-between">
            <div class="mb-8 flex items-center max-md:mb-5">
                <!-- Back Button -->
                <a
                    class="grid md:hidden"
                    href="{{ route('shop.customers.account.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                </a>

                <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                    @lang('shop::app.rma.customer.create.heading')
                </h2>
            </div>

            <a
                href="{{ route('shop.customers.account.rma.index') }}"
                class="secondary-button flex items-center gap-x-2 border-[#E9E9E9] px-5 max-lg:px-3 max-lg:text-xs py-3 font-normal"
            >
                @lang('shop::app.checkout.onepage.address.back')
            </a>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.new-rma.list.before') !!}

        <v-customer-new-rma></v-customer-new-rma>

        {!! view_render_event('bagisto.shop.customers.account.new-rma.list.after') !!}
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-customer-new-rma-template"
        >
            <div class="w-full overflow-auto">
                <div class="max-md:hidden">
                    <x-shop::datagrid :src="route('shop.customers.account.rma.create')" >
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
                                        v-for="(columnGroup, index) in [['increment_id'], ['status'], ['grand_total'], ['method_title'], ['created_at']]"
                                    >
                                        <p class="text-gray-600">
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

                                    <p class="flex justify-end text-gray-600 cursor-pointer">
                                        @lang('shop::app.customers.account.rma.create.action')
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
                                        <!-- Order Id, Created -->
                                        <p
                                            class="text-base text-gray-800 "
                                            v-html="record.increment_id"
                                        >
                                        </p>

                                        <p v-html="record.status"></p>

                                        <!--  Grand Total, Method Title -->
                                        <p
                                            class="text-base text-gray-800 font-semibold"
                                            v-html="record.grand_total"
                                        >
                                        </p>

                                        <p
                                            class="text-gray-600 "
                                            v-html="record.method_title"
                                        >
                                        </p>

                                        <p
                                            class="text-gray-600"
                                            v-html="record.created_at"
                                        >
                                        </p>

                                        <p class="flex justify-end">
                                            <!-- Arrow -->
                                            <a
                                                class="icon-edit text-2xl"
                                                @click="productAvail(record)"
                                            >
                                            </a>
                                        </p>
                                </div>
                            </template>
                        </template>
                    </x-shop::datagrid>
                </div>

                <div class="md:hidden">
                    <x-shop::datagrid :src="route('shop.customers.account.rma.create')" >
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
                                        v-for="(columnGroup, index) in [['increment_id', 'created_at', 'grand_total'], ['method_title', 'status']]"
                                    >
                                        <p class="text-gray-600">
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
                                                v-html="record.increment_id ?? 'N/A'"
                                            >
                                            </p>

                                            <p
                                                class="text-gray-600"
                                                v-html="record.created_at"
                                            >
                                            </p>

                                            <p
                                                class="text-gray-600"
                                                v-html="record.grand_total"
                                            >
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex gap-x-4 justify-between items-center">
                                        <div class="flex flex-col gap-1.5">

                                        <p
                                            class="text-gray-600 "
                                            v-html="record.method_title"
                                        >
                                        </p>

                                        <p v-html="record.status"></p>

                                        <p class="flex justify-end">
                                            <!-- Arrow -->
                                            <a
                                                class="icon-edit text-2xl"
                                                @click="productAvail(record)"
                                            >
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                </div>
                            </template>
                        </template>
                    </x-shop::datagrid>
                </div>

                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form
                        @submit="handleSubmit($event, rmaSubmit)"
                        ref="rmaSubmit"
                    >
                        <x-shop::modal ref="rmaModel">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <h2 class="text-base font-medium max-md:text-base">
                                    @lang('shop::app.rma.customer.create.heading')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content class="bg-white p-4 max-sm:p-3">
                                <div class="overflow-auto" style="min-height: 400px; max-height: 400px;">
                                    <v-order-items-list :key="refreshComponent" :order-id="isSelect"></v-order-items-list>
                                </div>
                            </x-slot>

                            <x-slot:footer>
                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        :disabled="!rmaFormButton || !rmaFormSubmit"
                                        class="primary-button"
                                    >
                                        <svg v-if="!rmaFormSubmit" aria-hidden="true" class="w-5 h-5 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                        </svg>

                                        @lang('shop::app.rma.customer.submit-req')
                                    </button>
                                </div>
                            </x-slot>
                        </x-shop::modal>
                    </form>
                </x-shop::form>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-order-items-list-template"
        >
            <div v-if="products.length > 0">
                <x-shop::form.control-group.control
                    type="hidden"
                    name="order_id"
                    ::value="orderId"
                />

                <div v-for="product in products">
                    <div class="flex-row gap-2.5 border-b mt-2 mb-2">
                        <div class="flex gap-2.5 mb-3">
                            <!-- Checkbox -->
                            <p>
                                <div v-if="product.currentQuantity > '0'">
                                    <input
                                        type="checkbox"
                                        :name="'isChecked[' + getProductId(product) + ']'"
                                        :id="'isChecked[' + getProductId(product) + ']'"
                                        class="mt-6"
                                        v-model="isChecked[getProductId(product)]"
                                    >
                                </div>

                                <div v-else>
                                    <div class="ltr:ml-3 rtl:mr-3"></div>
                                </div>

                                <div v-if="isChecked[getProductId(product)]">
                                    <x-shop::form.control-group.control
                                        type="hidden"
                                        ::name="'order_item_id[' + getProductId(product) + ']'"
                                        ::value="product.order_item_id"
                                    />
                                </div>
                            </p>

                            <!-- Image -->
                            <p>
                                <template v-if="product.base_image">
                                    <img
                                        class="min-h-[80px] max-h-[80px] min-w-[80px] max-w-[80px] rounded"
                                        :src="`${baseImageUrl}${product.base_image}`"
                                        :alt="`${product.base_image}`"
                                    />
                                </template>

                                <template v-else>
                                    <img
                                        class="min-h-[80px] max-h-[80px] min-w-[80px] max-w-[80px] rounded"
                                        src="{{ bagisto_asset('images/medium-product-placeholder.webp') }}"
                                        alt="medium-product-placeholder.webp"
                                    >
                                </template>
                            </p>

                            <!-- Sku, Price, Return Window -->
                            <p class="w-full">
                                <p class="flex text-sm justify-between">
                                    <template v-if="product.url_key && product.visible_individually">
                                        <a
                                            :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`"
                                            target='_blank'
                                            class="text-blue-500 text-lg hover:underline"
                                        >
                                            @{{ product.name }}

                                        </a>
                                    </template>

                                    <template v-else>
                                            @{{ product.name }}
                                    </template>
                                </p>

                                <p
                                    v-for="(attribute) in product.attributes" v-if="product.attributes"
                                    class="flex text-sm justify-between whitespace-nowrap"
                                    >
                                    <span>
                                        @{{ attribute.attribute_name }}:
                                    </span>

                                    <span>@{{ attribute.option_label }}</span>
                                </p>

                                <p class="flex text-sm justify-between whitespace-nowrap">
                                    <span>
                                        @lang('shop::app.customers.account.rma.create.sku'):
                                    </span>

                                    <span>@{{ product.sku }}</span>
                                </p>

                                <p class="flex text-sm justify-between whitespace-nowrap">
                                    <span>
                                        @lang('shop::app.customers.account.rma.create.price'):
                                    </span>

                                    <span>@{{ formatPrice(product.price) }}</span>
                                </p>

                                <p class="flex text-sm justify-between whitespace-nowrap">
                                    <span>
                                        @lang('shop::app.customers.account.rma.create.current-order-quantity'):
                                    </span>

                                    <span>
                                        @{{ product.currentQuantity }}
                                    </span>
                                </p>

                                <template v-if="product.rma_rules">
                                    <p
                                        v-if="resolutionType[getProductId(product)] == 'return'"
                                        class="flex text-sm justify-between whitespace-nowrap"
                                    >
                                        <span>
                                            @lang('shop::app.rma.customer.create.return-window'):
                                        </span>

                                        <span>
                                            @{{ calculateDeliveredReturnWindow(product.created_at, product.rma_return_period) }}
                                        </span>
                                    </p>
                                </template>
                                <p
                                    v-else-if="! product.rma_return_period"
                                    class="flex text-sm justify-between gap-3 whitespace-nowrap"
                                    >
                                    <span>
                                        @lang('shop::app.rma.customer.create.return-window'):
                                    </span>

                                    <span>
                                        @{{ calculateReturnWindow(product.created_at) }}
                                    </span>
                                </p>
                            </p>
                        </div>

                        <!-- RMA QTY -->
                        <p class="w-full" v-if="! notAllowed">
                            <div v-if="isChecked[getProductId(product)] && product.currentQuantity > '0'">
                                <!-- RMA Quantity -->
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required text-sm flex">
                                        @lang('shop::app.rma.customer.rma-qty')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        ::name="'rma_qty[' + getProductId(product) + ']'"
                                        ::rules="'min_value:1|required|max_value:' + product.currentQuantity"
                                        :label="trans('shop::app.rma.customer.rma-qty')"
                                        :placeholder="trans('shop::app.rma.customer.rma-qty')"
                                        v-model="rma_qty[getProductId(product)]"
                                    />

                                    <x-shop::form.control-group.error ::name="'rma_qty[' + getProductId(product) + ']'" class="flex" />
                                </x-shop::form.control-group>
                            </div>

                            <div
                                v-if="product.currentQuantity <= '0'"
                                class="text-sm text-red-600 flex mb-2"
                            >
                                @lang('shop::app.customers.account.rma.create.product-already-raw')
                            </div>
                        </p>

                        <div class="flex gap-3" v-if="! notAllowed">
                            <!-- Resolution Type for rules product -->
                            <p class="w-full" v-if="product.rma_return_period">
                                <div v-if="isChecked[getProductId(product)] && product.currentQuantity > '0'">
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label class="required text-sm flex">
                                            @lang('shop::app.customers.account.rma.create.resolution-type')
                                        </x-shop::form.control-group.label>

                                        <x-shop::form.control-group.control
                                            type="select"
                                            ::name="'resolution_type[' + getProductId(product) + ']'"
                                            rules="required"
                                            v-model="resolutionType[getProductId(product)]"
                                            @change="getResolutionReason(getProductId(product))"
                                            :label="trans('shop::app.customers.account.rma.create.resolution-type')"
                                        >
                                            <option value="">
                                                @lang('shop::app.customers.account.rma.create.select')
                                            </option>

                                            <option
                                                v-if="product.qty_ordered == product.qty_shipped && product.rma_return_period"
                                                value="return"
                                            >
                                                @lang('shop::app.customers.account.rma.create.return')
                                            </option>

                                            <option
                                                v-if="(product.order_status == 'pending' || product.order_status == 'processing') && product.qty_ordered != product.qty_shipped"
                                                value="cancel-items"
                                            >
                                                @lang('shop::app.customers.account.rma.create.cancel-items')
                                            </option>
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.error ::name="'resolution_type[' + getProductId(product) + ']'" class="flex" />
                                    </x-shop::form.control-group>
                                </div>
                            </p>

                            <!-- Resolution Type -->
                            <p class="w-full" v-else>
                                <div v-if="isChecked[getProductId(product)] && product.currentQuantity > '0'">
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label class="required text-sm flex">
                                            @lang('shop::app.customers.account.rma.create.resolution-type')
                                        </x-shop::form.control-group.label>

                                        <x-shop::form.control-group.control
                                            type="select"
                                            ::name="'resolution_type[' + getProductId(product) + ']'"
                                            rules="required"
                                            v-model="resolutionType[getProductId(product)]"
                                            @change="getResolutionReason(getProductId(product))"
                                            :label="trans('shop::app.customers.account.rma.create.resolution-type')"
                                        >
                                            <option value="">
                                                @lang('shop::app.customers.account.rma.create.select')
                                            </option>

                                            <option
                                                v-if="product.qty_ordered == product.qty_shipped"
                                                value="return"
                                            >
                                                @lang('shop::app.customers.account.rma.create.return')
                                            </option>

                                            <option
                                                v-if="(product.order_status == 'pending' || product.order_status == 'processing') && product.qty_ordered != product.qty_shipped"
                                                value="cancel-items"
                                            >
                                                @lang('shop::app.customers.account.rma.create.cancel-items')
                                            </option>
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.error ::name="'resolution_type[' + getProductId(product) + ']'" class="flex" />
                                    </x-shop::form.control-group>
                                </div>
                            </p>

                            <!-- Reasons -->
                            <p class="w-full">
                                <div
                                    v-if="isChecked[getProductId(product)]
                                        && product.currentQuantity > '0'
                                        && resolutionType[getProductId(product)]
                                        && resolutionReason[getProductId(product)]
                                        && resolutionReason[getProductId(product)].length"
                                >
                                    <x-shop::form.control-group>
                                        <x-shop::form.control-group.label class="required text-sm flex">
                                            @lang('shop::app.rma.customer.create.reason')
                                        </x-shop::form.control-group.label>

                                        <x-shop::form.control-group.control
                                            type="select"
                                            ::name="'rma_reason_id[' + getProductId(product) + ']'"
                                            v-model="rma_reason_id[getProductId(product)]"
                                            rules="required"
                                            :label="trans('shop::app.rma.customer.create.reason')"
                                        >
                                            <option
                                                v-for="reason in resolutionReason[getProductId(product)]"
                                                :value="reason.id"
                                                :key="reason.id"
                                            >
                                                @{{ formatTitle(reason.title) }}
                                            </option>
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.error ::name="'rma_reason_id[' + getProductId(product) + ']'" class="flex" />
                                    </x-shop::form.control-group>
                                </div>
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="gap-5"
                    v-if="isChecked.length == rma_reason_id.length && rma_reason_id.length && rma_qty.length"
                >
                    <!-- Delivery Status -->
                    <x-shop::form.control-group v-if="products[0].order_status != 'pending' && products[0].order_status != 'processing'">
                        <x-shop::form.control-group.label class="required text-sm mt-4 flex">
                            @lang('shop::app.customers.account.rma.create.product-delivery-status')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="select"
                            name="order_status"
                            rules="required"
                            v-model="orderStatus"
                            :label="trans('shop::app.customers.account.rma.create.product-delivery-status')"
                        >
                            <option value="1">
                                @lang('shop::app.rma.customer.delivered')
                            </option>
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error name="order_status" class="flex" />
                    </x-shop::form.control-group>

                    <input v-else type="hidden" name="order_status" value="0" />

                    <template v-if="orderStatus == '1'">
                        <!-- Delivery Status -->
                        <x-shop::form.control-group>
                            <x-shop::form.control-group.label class="required text-sm mt-4 flex">
                                @lang('shop::app.customers.account.rma.create.package-condition')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="select"
                                name="package_condition"
                                rules="required"
                                v-model="packageCondition"
                                :label="trans('shop::app.customers.account.rma.create.package-condition')"
                            >
                                <option value="">
                                    @lang('shop::app.customers.account.rma.create.select')
                                </option>

                                <option value="open">
                                    @lang('shop::app.customers.account.rma.create.open')
                                </option>

                                <option value="packed">
                                    @lang('shop::app.customers.account.rma.create.packed')
                                </option>
                            </x-shop::form.control-group.control>

                            <x-shop::form.control-group.error name="package_condition" class="flex" />
                        </x-shop::form.control-group>

                        <!-- Additionally -->
                        @foreach ($customAttributes as $attribute)
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="flex text-sm mt-4">
                                    {!! $attribute->label . ($attribute->is_required == '1' ? '<span class="required"></span>' : '') !!}
                                </x-shop::form.control-group.label>

                                @if ($attribute->is_required == '1')
                                   @php
                                        $attribute->is_required = 'required';
                                    @endphp
                                @elseif ($attribute->is_required == '0')
                                    @php
                                        $attribute->is_required = '';
                                    @endphp
                                @endif

                                @switch($attribute->type)
                                    @case('text')
                                        <x-shop::form.control-group.control
                                            type="text"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                            placeholder="{{ $attribute->label }}"
                                        />

                                        <x-shop::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />

                                        @break

                                    @case('textarea')
                                        <x-shop::form.control-group.control
                                            type="textarea"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                            placeholder="{{ $attribute->label }}"
                                            rows="12"
                                        />

                                        <x-shop::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />

                                        @break

                                    @case('date')
                                        <x-shop::form.control-group.control
                                            type="date"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                            placeholder="{{ $attribute->label }}"
                                        />

                                        <x-shop::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />

                                        @break

                                    @case('select')
                                        <x-shop::form.control-group.control
                                            type="select"
                                            id="{{ $attribute->code }}"
                                            class="cursor-pointer"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                        >
                                            <!-- Here! All Needed types are defined -->
                                            @foreach($attribute->options ?? [] as $option)
                                                <option
                                                    value="{{ $option->value }}"
                                                >
                                                    {{ $option->name }}
                                                </option>
                                            @endforeach
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />

                                        @break

                                    @case('multiselect')
                                        <x-shop::form.control-group.control
                                            type="multiselect"
                                            id="{{ $attribute->code }}"
                                            class="cursor-pointer"
                                            name="{{ $attribute->code }}[]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                        >
                                            <!-- Here! All Needed types are defined -->
                                            @foreach($attribute->options ?? [] as $option)
                                                <option
                                                    value="{{ $option->value }}"
                                                >
                                                    {{ $option->name }}
                                                </option>
                                            @endforeach
                                        </x-shop::form.control-group.control>

                                        <x-shop::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />

                                        @break

                                    @case('checkbox')
                                        @foreach($attribute->options ?? [] as $index => $option)
                                            <label class="relative mb-2 flex cursor-pointer items-start">
                                                <v-field
                                                    type="checkbox"
                                                    class="flex"
                                                    name="customAttributes[{{ $attribute->code }}]"
                                                    rules="{{ $attribute->is_required }}"
                                                    v-slot="{ field }"
                                                    value="{{ $option->value }}"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        class="peer sr-only"
                                                        id="{{ $attribute->code }}-{{ $index }}"
                                                        rules="required"
                                                        name="customAttributes[{{ $attribute->code }}]"
                                                        value="{{ $option->value }}"
                                                        v-bind="field"
                                                    />
                                                </v-field>

                                                <label
                                                    class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-base peer-checked:text-navyBlue"
                                                    for="{{ $attribute->code }}-{{ $index }}"
                                                >
                                                    {{ $option->name }}
                                                </label>
                                            </label>

                                            <x-shop::form.control-group.error
                                                control-name="customAttributes[{{ $attribute->code }}]"
                                                class="flex"
                                                />
                                        @endforeach

                                        @break

                                    @case('radio')
                                        @foreach($attribute->options ?? [] as $option)
                                            <label class="relative mb-2 flex cursor-pointer items-start">
                                                <v-field
                                                    type="radio"
                                                    class="flex"
                                                    name="customAttributes[{{ $attribute->code }}]"
                                                    rules="{{ $attribute->is_required }}"
                                                    v-slot="{ field }"
                                                    value="{{ $option->name }}"
                                                >
                                                    <input
                                                        type="radio"
                                                        class="sr-only peer"
                                                        id="option_{{ $loop->index }}"
                                                        rules="{{ $attribute->is_required }}"
                                                        name="customAttributes[{{ $attribute->code }}]"
                                                        value="{{ $option->name }}"
                                                        v-bind="field"
                                                    />
                                                </v-field>

                                                <label
                                                    class="icon-radio-unselect text-base peer-checked:icon-radio-select peer-checked:text-navyBlue cursor-pointer"
                                                    for="option_{{ $loop->index }}"
                                                >
                                                    {{ $option->name }}
                                                </label>
                                            </label>
                                        @endforeach

                                        <x-shop::form.control-group.error
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                            class="flex"
                                        />

                                    @break

                                @endswitch
                            </x-shop::form.control-group>
                        @endforeach
                    </template>

                    <!-- Additional information -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="text-sm flex">
                            @lang('shop::app.rma.customer.create.information')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="textarea"
                            name="information"
                            id="information"
                            v-model="information"
                            :label="trans('shop::app.rma.customer.create.information')"
                            :placeholder="trans('shop::app.rma.customer.create.information')"
                            @input="sanitizeTextarea"
                            rows="4"
                            maxlength="250"
                        />

                        <x-shop::form.control-group.error control-name="information" class="flex" />
                    </x-shop::form.control-group>

                    <!-- Images -->
                    <x-shop::form.control-group class="mt-4">
                        <x-shop::form.control-group.label class="text-sm flex">
                            @lang('shop::app.customers.account.rma.create.images')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="image"
                            class="!p-0 rounded-xl text-gray-700 mb-0"
                            name="images[]"
                            :label="trans('shop::app.customers.account.rma.create.images')"
                            :is-multiple="false"
                            accepted-types="{{ core()->getConfigData('sales.rma.setting.allowed_file_extension') }}"
                        />

                        <x-shop::form.control-group.error control-name="images[]" class="flex" />
                    </x-shop::form.control-group>

                    @include('shop::customers.account.rma.terms')
                </div>
            </div>

            <div v-else-if="isLoading">
                <!-- Loading Shimmer -->
                <div>
                    <div class="flex gap-5 mt-2">
                        <x-shop::media.images.lazy class="h-[95px] max-h-[95px] w-28 min-w-32 max-w-24 rounded-xl max-md:w-18 max-md:min-w-18" />
                        
                        <div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex gap-5 mt-2">
                        <x-shop::media.images.lazy class="h-[95px] max-h-[95px] w-28 min-w-32 max-w-24 rounded-xl max-md:w-18 max-md:min-w-18" />
                        
                        <div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                            <div class="shimmer w-32 min-w-32 h-4 mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div
                v-else
                class="text-center text-red-600 font-semibold mt-4"
            >
                @lang('shop::app.rma.customer.create.rma-not-available-quotes')
            </div>
        </script>

        <script type="module">
            app.component('v-customer-new-rma', {
                template: '#v-customer-new-rma-template',

                data() {
                    return {
                        isSelect: 0,
                        refreshComponent: 1,
                        rmaFormButton: false,
                        rmaFormSubmit: true,
                    }
                },

                mounted() {
                    this.$emitter.on('valid-rma', (data) => {
                        if (data.isValid) {
                            this.rmaFormButton = true;
                        }
                    })
                },

                methods: {
                    productAvail(record) {
                        this.isSelect = record.id;

                        ++this.refreshComponent;

                        this.$refs.rmaModel.toggle();
                    },

                    async rmaSubmit(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.rmaSubmit);

                        this.rmaFormSubmit = false;

                        try {
                            const response = await this.$axios.post("{{ route('shop.customers.account.rma.store') }}", formData);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.messages });

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } catch (error) {
                            this.rmaFormSubmit = true;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        }
                    },
                }
            });

            app.component('v-order-items-list', {
                template: '#v-order-items-list-template',

                props: ['orderId'],

                data() {
                    return {
                        isLoading: true,

                        isChecked: [],

                        orderStatus: '',

                        resolutionReason: [],

                        rma: [],

                        rma_qty: [],

                        rma_reason_id: [],

                        products: '',

                        resolutionType: [],

                        notAllowed: false,

                        baseImageUrl: '{{ Storage::url('') }}',

                        returnWindowDays: parseInt('{{ core()->getConfigData('sales.rma.setting.default_allow_days') }}'),
                    }
                },

                updated() {
                    let isValid = this.isChecked.length == this.rma_reason_id.length && this.rma_reason_id.length && this.rma_qty.length;

                    this.$emitter.emit('valid-rma', {
                        isValid: isValid,
                    });
                },

                mounted() {
                    this.getOrderItems();
                },

                methods: {
                    formatPrice(price) {
                        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price);
                    },

                    getProductId(product) {
                        return product.type === 'configurable'
                            ? product.additional.selected_configurable_option
                            : product.product_id;
                    },

                    getOrderItems(orderId) {
                        if (this.orderId) {
                            this.$axios.get('{{ route("shop.customers.account.create.get-order-items", "") }}/' + this.orderId)
                                .then(response => {
                                    this.isLoading = false;

                                    this.products = response.data;

                                }).catch(error => {
                                    console.log(error);
                                });
                        }
                    },

                    sanitizeTextarea(event) {
                        this.information = this.sanitizeInput(event.target.value);
                    },

                    sanitizeInput(value) {
                        if (!value) return '';

                        return String(value)
                            .replace(/[<>]/g, '')
                            .replace(/&/g, '&amp;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#39;');
                    },

                    calculateReturnWindow(createdAt) {
                        const createdDate = new Date(createdAt);
                        const returnDate = new Date(createdDate);
                        returnDate.setDate(createdDate.getDate() + this.returnWindowDays);

                        const currentDate = new Date();

                        if (returnDate < currentDate) {
                            this.notAllowed = true;

                            return 'Not Allowed';
                        }

                        return new Intl.DateTimeFormat('en-GB', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        }).format(returnDate);
                    },

                    calculateDeliveredReturnWindow(created_At, rulesDays) {
                        const createdAt = new Date(created_At);
                        const returnDate = new Date(
                            createdAt.getTime() + rulesDays * 24 * 60 * 60 * 1000
                        );

                        returnDate.setUTCDate(returnDate.getDate());

                        const currentDate = new Date();

                        if (returnDate < currentDate) {
                            this.notAllowed = true;

                            return 'Not Allowed';
                        }

                        return new Intl.DateTimeFormat('en-GB', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric',
                        }).format(returnDate);
                    },

                    getResolutionReason(product_id) {
                        let resolutionType = this.resolutionType[product_id];

                        let url = '{{ route("shop.customers.account.rma.get-resolution-reasons", ":resolutionType") }}';

                        url = url.replace(':resolutionType', resolutionType);

                        if (resolutionType) {
                            this.$axios.get(url)
                                .then(response => {
                                    if (response.data['0'] == null) {
                                        this.resolutionReason[product_id] = null;

                                        return;
                                    }

                                    this.resolutionReason[product_id] = response.data;

                                }).catch(error => {
                                    console.log(error);
                                });
                        }
                    },

                    formatTitle(title) {
                        if (title.length > 100) {
                            return title.slice(0, 100) + '...';
                        }

                        return title;
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts.account>
