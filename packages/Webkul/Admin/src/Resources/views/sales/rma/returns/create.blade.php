@php
    $customAttributes = app('Webkul\RMA\Repositories\RMACustomFieldRepository')->with('options')->where('status', 1)->get();
@endphp

<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.rma.create-rma.create-title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <!-- Heading -->
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.sales.rma.create-rma.create-title')
        </h1>

        <div class="flex items-center gap-x-2.5">
            <a
                href="{{ route('admin.sales.rma.index') }}"
                class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
            >
                @lang('admin::app.settings.channels.edit.back-btn')
            </a>
        </div>
    </div>

    {!! view_render_event('bagisto.admin.rma.create.list.before') !!}

    <v-admin-new-rma></v-admin-new-rma>

    {!! view_render_event('bagisto.admin.rma.create.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-admin-new-rma-template"
        >
            <div class="w-full overflow-auto">
                <x-admin::datagrid :src="route('admin.sales.rma.create')" >
                    <template #header="{
                        isLoading,
                        available,
                        applied,
                        selectAll,
                        sort,
                        performAction
                    }">
                        <template v-if="! isLoading">
                            <div class="row grid grid-cols-[0.5fr_1fr_1fr_0.5fr_1fr_1fr_0.1fr] grid-rows-1 min-h-[47px] items-center gap-2.5 border-b bg-gray-50 px-4 py-2.5 font-semibold text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                <div
                                    class="flex gap-2.5 items-center select-none"
                                    v-for="(columnGroup, index) in [['increment_id'], ['customer_name'], ['status'], ['grand_total'], ['method_title'], ['created_at']]"
                                >
                                    <p class="text-gray-600 dark:text-gray-300">
                                        <span class="[&>*]:after:content-['_/_']">
                                            <template v-for="column in columnGroup">
                                                <span
                                                    class="after:content-['/'] last:after:content-['']"
                                                    :class="{
                                                        'text-gray-800 dark:text-white font-medium': applied.sort.column == column,
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
                                            class="ltr:ml-1.5 rtl:mr-1.5 text-base text-gray-800 dark:text-white align-text-bottom"
                                            :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                            v-if="columnGroup.includes(applied.sort.column)"
                                        ></i>
                                    </p>
                                </div>

                                <p class="flex justify-end text-gray-600 cursor-pointer">
                                    @lang('admin::app.settings.data-transfer.imports.edit.action')
                                </p>
                            </div>
                        </template>

                        <!-- Datagrid Head Shimmer -->
                        <template v-else>
                            <x-admin::shimmer.datagrid.table.head :isMultiRow="true" />
                        </template>
                    </template>

                    <!-- Datagrid Body -->
                    <template #body="{
                        isLoading,
                        available,
                        applied,
                        selectAll,
                        sort,
                        performAction
                    }">
                        <template v-if="! isLoading">
                            <div
                                class="row grid grid-cols-[0.5fr_1fr_1fr_0.5fr_1fr_1fr_0.1fr] grid-rows-1 px-4 py-2.5 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                                v-for="record in available.records"
                            >
                                <!-- Name, SKU, Attribute Family Columns -->
                                <div class="flex gap-2.5">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-html="record.increment_id"
                                    >
                                    </p>
                                </div>

                                <!-- Image, Price, Id, Stock Columns -->
                                <div class="flex gap-1.5">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-html="record.customer_name"
                                    >
                                    </p>
                                </div>

                                <div class="flex gap-1.5 ">
                                    <p v-html="record.status"></p>
                                </div>

                                <div class="flex gap-1.5">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-html="record.grand_total"
                                    >
                                    </p>
                                </div>

                                <div class="flex gap-1.5">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-html="record.method_title"
                                    >
                                    </p>
                                </div>

                                <div class="flex gap-1.5">
                                    <p
                                        class="text-gray-600 dark:text-gray-300"
                                        v-html="record.created_at"
                                    >
                                    </p>
                                </div>

                                <div class="flex gap-1.5 items-center">
                                    <a
                                        class="icon-edit text-2xl cursor-pointer"
                                        @click="productAvail(record)"
                                    >
                                    </a>
                                </div>
                            </div>
                        </template>

                        <!-- Datagrid Body Shimmer -->
                        <template v-else>
                            <x-admin::shimmer.datagrid.table.body :isMultiRow="true" />
                        </template>
                    </template>
                </x-admin::datagrid>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form
                        @submit="handleSubmit($event, rmaSubmit)"
                        enctype="multipart/form-data"
                        ref="rmaSubmit"
                    >
                        <x-admin::modal ref="rmaModel">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <h2 class="text-base font-medium max-md:text-base dark:text-gray-300">
                                    @lang('admin::app.sales.rma.create-rma.create-title')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content class="p-4 max-sm:p-3">
                                <div class="overflow-auto dark:text-gray-300" style="min-height: 400px; max-height: 400px;">
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

                                        @lang('admin::app.sales.rma.create-rma.save-btn')
                                    </button>
                                </div>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-order-items-list-template"
        >
            <div v-if="products.length > 0">
                <x-admin::form.control-group.control
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
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            ::name="'order_item_id[' + getProductId(product) + ']'"
                                            :label="trans('admin::app.sales.rma.create-rma.quantity')"
                                            :placeholder="trans('admin::app.sales.rma.create-rma.quantity')"
                                            ::value="product.order_item_id"
                                        />
                                    </x-admin::form.control-group>
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
                                        src="{{ bagisto_asset('images/medium-product-placeholder.webp', 'shop') }}"
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
                                            class="text-blue-500 text-lg"
                                        >
                                            @{{ product.name }}

                                        </a>
                                    </template>
                                    <template v-else>
                                            @{{ product.name }}
                                    </template>
                                </p>

                                <p class="flex text-sm justify-between whitespace-nowrap">
                                    <span>
                                        @lang('admin::app.catalog.products.index.create.sku'):
                                    </span>

                                    <span>@{{ product.sku }}</span>
                                </p>

                                <p class="flex text-sm justify-between whitespace-nowrap">
                                    <span>
                                        @lang('admin::app.catalog.attributes.create.price'):
                                    </span>

                                    <span>@{{ formatPrice(product.price) }}</span>
                                </p>

                                <p class="flex text-sm justify-between whitespace-nowrap">
                                    <span>
                                        @lang('admin::app.configuration.index.sales.rma.current-order-quantity'):
                                    </span>

                                    <span>
                                        @{{ product.currentQuantity }}
                                    </span>
                                </p>

                                <template v-if="product.rma_rules && products['0'].order_status != 'pending'">
                                    <p
                                        v-if="resolutionType[getProductId(product)] == 'return'"
                                        class="flex text-sm justify-between gap-3 whitespace-nowrap"
                                    >
                                        <span>
                                            @lang('admin::app.sales.rma.all-rma.index.datagrid.return-window'):
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
                                        @lang('admin::app.sales.rma.all-rma.index.datagrid.return-window'):
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
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required text-sm flex">
                                        @lang('admin::app.sales.rma.create-rma.quantity')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        ::name="'rma_qty[' + getProductId(product) + ']'"
                                        ::rules="'min_value:1|required|max_value:' + product.currentQuantity"
                                        :label="trans('admin::app.sales.rma.create-rma.quantity')"
                                        :placeholder="trans('admin::app.sales.rma.create-rma.quantity')"
                                        v-model="rma_qty[getProductId(product)]"
                                    />

                                    <x-admin::form.control-group.error ::name="'rma_qty[' + getProductId(product) + ']'" class="flex"/>
                                </x-admin::form.control-group>
                            </div>

                            <div
                                v-if="product.currentQuantity <= '0'"
                                class="text-sm text-red-600 flex mb-2"
                            >
                                @lang('admin::app.configuration.index.sales.rma.product-already-raw')
                            </div>
                        </p>

                        <div class="flex gap-3" v-if="! notAllowed">
                            <!-- Resolution Type for rules product -->
                            <p class="w-full" v-if="product.rma_return_period">
                                <div v-if="isChecked[getProductId(product)] && product.currentQuantity > '0'">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required text-sm flex">
                                            @lang('admin::app.configuration.index.sales.rma.resolution-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            ::name="'resolution_type[' + getProductId(product) + ']'"
                                            rules="required"
                                            v-model="resolutionType[getProductId(product)]"
                                            @change="getResolutionReason(getProductId(product))"
                                            :label="trans('admin::app.configuration.index.sales.rma.resolution-type')"
                                        >
                                            <option value="">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.select')
                                            </option>

                                            <option
                                                v-if="product.qty_ordered == product.qty_shipped && product.rma_return_period"
                                                value="return"
                                            >
                                                @lang('admin::app.configuration.index.sales.rma.return')
                                            </option>

                                            <option
                                                v-if="product.order_status == 'pending' || product.order_status == 'processing'"
                                                value="cancel-items"
                                            >
                                                @lang('admin::app.configuration.index.sales.rma.cancel-items')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error ::name="'resolution_type[' + getProductId(product) + ']'" class="flex"/>
                                    </x-admin::form.control-group>
                                </div>
                            </p>

                            <!-- Resolution Type -->
                            <p class="w-full" v-else>
                                <div v-if="isChecked[getProductId(product)] && product.currentQuantity > '0'">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required text-sm flex">
                                            @lang('admin::app.configuration.index.sales.rma.resolution-type')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            ::name="'resolution_type[' + getProductId(product) + ']'"
                                            rules="required"
                                            v-model="resolutionType[getProductId(product)]"
                                            @change="getResolutionReason(getProductId(product))"
                                            :label="trans('admin::app.configuration.index.sales.rma.resolution-type')"
                                        >
                                            <option value="">
                                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.select')
                                            </option>

                                            <option
                                                v-if="product.qty_ordered == product.qty_shipped"
                                                value="return"
                                            >
                                                @lang('admin::app.configuration.index.sales.rma.return')
                                            </option>

                                            <option
                                                v-if="(product.order_status == 'pending' || product.order_status == 'processing') && product.qty_ordered != product.qty_shipped"
                                                value="cancel-items"
                                            >
                                                @lang('admin::app.configuration.index.sales.rma.cancel-items')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error ::name="'resolution_type[' + getProductId(product) + ']'" class="flex"/>
                                    </x-admin::form.control-group>
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
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required text-sm flex">
                                            @lang('admin::app.sales.rma.create-rma.reason')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            ::name="'rma_reason_id[' + getProductId(product) + ']'"
                                            v-model="rma_reason_id[getProductId(product)]"
                                            rules="required"
                                            :label="trans('admin::app.sales.rma.create-rma.reason')"
                                        >
                                            <option
                                                v-for="reason in resolutionReason[getProductId(product)]"
                                                :value="reason.id"
                                                :key="reason.id"
                                            >
                                                @{{ formatTitle(reason.title) }}
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error ::name="'rma_reason_id[' + getProductId(product) + ']'" class="flex"/>
                                    </x-admin::form.control-group>
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
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required text-sm mt-4 flex">
                            @lang('admin::app.configuration.index.sales.rma.product-delivery-status')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="order_status"
                            rules="required"
                            v-model="orderStatus"
                            :label="trans('admin::app.configuration.index.sales.rma.product-delivery-status')"
                        >
                            <option value="">
                                @lang('admin::app.catalog.products.edit.types.bundle.update-create.select')
                            </option>

                            <option
                                v-if="products['0'].order_status != 'pending' && products['0'].order_status != 'processing'"
                                value="1"
                            >
                                @lang('admin::app.sales.rma.all-rma.index.datagrid.delivered')
                            </option>

                            <option value="0">
                                @lang('admin::app.sales.rma.all-rma.index.datagrid.undelivered')
                            </option>
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error name="order_status" class="flex"/>
                    </x-admin::form.control-group>

                    <div v-if="orderStatus == '1'">
                        <!-- Delivery Status -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required text-sm mt-4 flex">
                                @lang('admin::app.configuration.index.sales.rma.package-condition')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="package_condition"
                                rules="required"
                                v-model="packageCondition"
                                :label="trans('admin::app.configuration.index.sales.rma.package-condition')"
                            >
                                <option value="">
                                    @lang('admin::app.catalog.products.edit.types.bundle.update-create.select')
                                </option>

                                <option value="open">
                                    @lang('admin::app.configuration.index.sales.rma.open')
                                </option>

                                <option value="packed">
                                    @lang('admin::app.configuration.index.sales.rma.packed')
                                </option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error name="package_condition" class="flex"/>
                        </x-admin::form.control-group>

                        <!-- Additionally -->
                        @foreach ($customAttributes as $attribute)
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="flex text-sm mt-4">
                                    {!! $attribute->label . ($attribute->is_required == '1' ? '<span class="required"></span>' : '') !!}
                                </x-admin::form.control-group.label>

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
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                            placeholder="{{ $attribute->label }}"
                                        />

                                        <x-admin::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />
                                        @break

                                    @case('textarea')
                                        <x-admin::form.control-group.control
                                            type="textarea"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                            placeholder="{{ $attribute->label }}"
                                            rows="12"
                                        />

                                        <x-admin::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />
                                        @break

                                    @case('date')
                                        <x-admin::form.control-group.control
                                            type="date"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            rules="{{ $attribute->is_required }}"
                                            :value="old($attribute->code)"
                                            label="{{ $attribute->label }}"
                                            placeholder="{{ $attribute->label }}"
                                        />

                                        <x-admin::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"

                                                />

                                    @break

                                    @case('select')
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            id="{{ $attribute->code }}"
                                            class="cursor-pointer"
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
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />

                                    @break

                                    @case('multiselect')
                                        <x-admin::form.control-group.control
                                            type="multiselect"
                                            name="customAttributes[{{ $attribute->code }}]"
                                            id="{{ $attribute->code }}"
                                            class="cursor-pointer"
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
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            class="flex"
                                            control-name="customAttributes[{{ $attribute->code }}]"
                                        />

                                    @break

                                    @case('checkbox')
                                        @foreach($attribute->options ?? [] as $option)
                                            <x-admin::form.control-group class="flex gap-2.5 items-center !mb-2 select-none">
                                                <x-admin::form.control-group.control
                                                    type="checkbox"
                                                    id="{{ $attribute->code }}"
                                                    name="customAttributes[{{ $attribute->code }}]"
                                                    value="{{ $option->value }}"
                                                    for="{{ $attribute->code }}"
                                                />

                                                <label
                                                    class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                                    for="{{ $option->name }}"
                                                >
                                                    {{$option->name}}
                                                </label>
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group.error
                                                class="flex"
                                                control-name="customAttributes[{{ $attribute->code }}]"
                                            />
                                        @endforeach

                                    @break

                                    @case('radio')
                                        @foreach($attribute->options ?? [] as $key => $option)
                                            <div class="flex items-center gap-2.5">
                                                <x-admin::form.control-group class="!mb-0">
                                                    <x-admin::form.control-group.control
                                                        type="radio"
                                                        name="customAttributes[{{ $attribute->code }}]"
                                                        id="{{ $attribute->code }}_{{ $key }}"
                                                        value="{{ $option->value }}"
                                                        rules="{{ $attribute->is_required }}"
                                                        for="{{ $attribute->code }}_{{ $key }}"
                                                    />

                                                    <label
                                                        class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                                        for="{{ $attribute->code }}_{{ $key }}"
                                                    >
                                                        {{ $option->name }}
                                                    </label>
                                                </x-admin::form.control-group>
                                            </div>
                                        @endforeach

                                        <x-admin::form.control-group.error control-name="customAttributes[{{ $attribute->code }}]"/>
                                    @break

                                @endswitch
                            </x-admin::form.control-group>
                        @endforeach
                    </div>

                    <!-- Additional information -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="text-sm flex">
                            @lang('admin::app.sales.rma.all-rma.view.additional-information')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="information"
                            id="information"
                            :label="trans('admin::app.sales.rma.all-rma.view.additional-information')"
                            :placeholder="trans('admin::app.sales.rma.all-rma.view.additional-information')"
                            rows="4"
                            maxlength="250"
                        />

                        <x-admin::form.control-group.error control-name="information" class="flex"/>
                    </x-admin::form.control-group>

                    <!-- Images -->
                    <x-admin::form.control-group class="mt-4">
                        <x-admin::form.control-group.label class="text-sm flex">
                            @lang('admin::app.catalog.products.edit.images.title')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="image"
                            class="!p-0 rounded-xl text-gray-700 mb-0"
                            name="images"
                            :label="trans('admin::app.catalog.products.edit.images.title')"
                            :is-multiple="false"
                            accepted-types="{{core()->getConfigData('sales.rma.setting.allowed_file_extension')}}"
                        />

                        <x-admin::form.control-group.error control-name="images[]" class="flex"/>
                    </x-admin::form.control-group>
                </div>
            </div>

            <div v-else>
                @lang('admin::app.sales.rma.create-rma.rma-not-available-quotes')
            </div>
        </script>

        <script type="module">
            app.component('v-admin-new-rma', {
                template: '#v-admin-new-rma-template',

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
                            const response = await this.$axios.post("{{ route('admin.sales.rma.store') }}", formData);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.messages });

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } catch (error) {
                            this.rmaFormSubmit = true;
                            if (error.response && error.response.data && error.response.data.errors) {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
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
                            this.$axios.get('{{ route("admin.sales.rma.get-order-product", "") }}' + '/' + this.orderId)
                                .then(response => {
                                    this.isLoading = false;

                                    this.products = response.data;
                                }).catch(error => {
                                    console.log(error);
                                });
                        }
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

                        let url = '{{route("admin.sales.rma.get-resolution-reason", ":resolutionType")}}';

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
</x-admin::layouts>
