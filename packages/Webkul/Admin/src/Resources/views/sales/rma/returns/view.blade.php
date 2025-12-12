<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.sales.rma.all-rma.view.title', ['id' => $rma->id])
    </x-slot>

    {!! view_render_event('bagisto.admin.rma.view.before') !!}
    
    <v-admin-rma-view></v-admin-rma-view>
    
    {!! view_render_event('bagisto.admin.rma.view.after') !!}

    @push('scripts')
        <script
            type="text/x-template"
            id="v-admin-rma-view-template"
        >
            <div class="grid">
                <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">

                    <div class="flex items-center gap-2.5">
                        <p class="text-xl font-bold leading-6 text-gray-800 dark:text-white">
                            @lang('admin::app.sales.rma.all-rma.index.datagrid.id') {{ '#'. $rma->id }}
                        </p>
                    </div>

                    <!-- Back Button -->
                    <div class="flex gap-x-2.5 items-center">
                        <!-- Back Button -->
                        <a
                            href="{{ route('admin.sales.rma.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                        >
                            @lang('admin::app.customers.customers.view.back-btn')
                        </a>
                    </div>
                </div>
            </div>
            <!-- new content -->
            <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
                <!-- Left Component -->
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                    <!-- RMA Details -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <div class="flex justify-between p-4">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('shop::app.rma.view-customer-rma.heading')
                            </p>
                        </div>

                        <div class="grid">
                            <div class="flex justify-between gap-2.5 border-b border-slate-300 px-4 py-6 dark:border-gray-800 !pt-0">
                                <div class="flex gap-2.5">
                                    <div class="grid place-content-start gap-1.5">
                                        <!-- Created At -->
                                        <div class="flex gap-2.5 mt-2">
                                            <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                                @lang('admin::app.sales.rma.all-rma.view.request-on')
                                            </div>

                                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ date("F j, Y, h:i:s A", strtotime($rma->created_at)) }}
                                            </div>
                                        </div>

                                        <!-- Package Condition -->
                                        @if ($rma->package_condition)
                                            <div class="flex gap-2.5 mt-2">
                                                <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                                    @lang('admin::app.configuration.index.sales.rma.package-condition'):
                                                </div>

                                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                                    {{ ucwords($rma->package_condition) }}
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Additional Fields -->
                                        @if (! empty($rma->additionalFields))
                                            @foreach ($rma->additionalFields as $key => $additionalField)
                                                <div class="flex gap-2.5 mt-2">
                                                    <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                                        {{ $additionalField?->customField?->label }} :
                                                    </div>

                                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $additionalField?->field_value }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <!-- Additional Information -->
                                        <div class="flex gap-2.5 mt-2 ">
                                            <div class="text-sm font-semibold text-gray-600 dark:text-gray-300 min-w-[160px]">
                                                @lang('admin::app.sales.rma.all-rma.view.additional-information')
                                            </div>

                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                {{ $rma?->information }}
                                            </p>
                                        </div>

                                        <!--RMA Image -->
                                        @if ($rma->images->isNotEmpty())
                                            <div class="flex gap-2.5 mt-2">
                                                <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                                    @lang('admin::app.sales.rma.all-rma.view.images')
                                                </div>

                                                <div class="flex justify-between flex-wrap gap-2">
                                                    @foreach($rma->images as $image)
                                                        <img
                                                            class="w-24 max-w-20 relative h-20 max-h-20 rounded-md"
                                                            src="{{ Storage::url($image->path) }}"
                                                        />
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RMA items -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <div class="flex justify-between p-4 !pb-0">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                               @lang('admin::app.sales.rma.all-rma.view.order-details')
                            </p>
                        </div>

                        <div class="grid">
                            @foreach ($rma->items as $rmaItem)
                                <div class="gap-2.5 border-b border-slate-300 px-4 py-6 dark:border-gray-800">
                                    <div class="flex gap-2.5">
                                        <div>
                                            @if($rmaItem?->product?->base_image_url)
                                                <img
                                                    class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded"
                                                    src="{{$rmaItem?->product->base_image_url }}"
                                                >
                                            @else
                                                <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">

                                                    <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                                        @lang('admin::app.sales.invoices.view.product-image')
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="grid gap-1 w-full">
                                            <p class="break-all text-base font-semibold text-gray-800 dark:text-white">
                                                {{ $rmaItem->product->name }}
                                            </p>

                                            <div class="flex w-full gap-x-5">
                                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                                    @lang('shop::app.rma.table-heading.price'):
                                                </p>

                                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                                    {{ core()->formatBasePrice($rmaItem->product->price) }}
                                                </p>
                                            </div>

                                            <div class="flex w-full gap-x-5">
                                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                                    @lang('shop::app.rma.table-heading.rma-qty'):
                                                </p>

                                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                                   {!! $rmaItem->quantity !!}
                                                </p>
                                            </div>

                                            <div class="flex w-full gap-x-5">
                                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                                    @lang('shop::app.rma.table-heading.order-qty'):
                                                </p>

                                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                                    {{ $rmaItem->orderItem->qty_ordered }}
                                                </p>
                                            </div>

                                            <div class="flex w-full gap-x-5">
                                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                                    @lang('shop::app.rma.table-heading.resolution-type'):
                                                </p>

                                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                                    {!! ucwords($rmaItem['resolution']) !!}
                                                </p>
                                            </div>

                                            <div class="flex w-full gap-x-5">
                                                <p class="font-semibold !leading-5 text-gray-600 dark:text-gray-300">
                                                    @lang('shop::app.rma.table-heading.reason'):
                                                </p>

                                                <p class="!leading-5 text-gray-600 dark:text-gray-300">
                                                    {!! wordwrap($rmaItem->reason->title, 50, "<br>\n") !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Conversation -->
                    <div class="box-shadow rounded bg-white dark:bg-gray-900">
                        <div class="flex justify-between p-4">
                            <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                               @lang('admin::app.sales.rma.all-rma.view.conversations')
                            </p>
                        </div>
                        <div class="grid gap-2.5 p-4">
                            <div class="mb-3 border rounded-lg p-3">
                                <x-admin::form
                                    v-slot="{ meta, errors, handleSubmit }"
                                    as="div"
                                >
                                    <form
                                        @submit="handleSubmit($event, chatSubmit)"
                                        ref="adminChatForm"
                                    >
                                        <input
                                            type="hidden"
                                            name="order_id"
                                            value="{{ $rma->order_id }}"
                                        >

                                        <input
                                            type="hidden"
                                            name="is_admin"
                                            value="1"
                                        >

                                        <input
                                            type="hidden"
                                            name="rma_id"
                                            value="{{ $rma->id }}"
                                        >

                                        <x-admin::form.control-group>
                                            <x-shop::form.control-group.label class="required flex dark:text-gray-300">
                                                @lang('admin::app.sales.rma.all-rma.view.send-message')
                                            </x-shop::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="textarea"
                                                name="message"
                                                id="message"
                                                rules="required"
                                                :label="trans('admin::app.sales.rma.all-rma.view.enter-message')"
                                                :placeholder="trans('admin::app.sales.rma.all-rma.view.enter-message')"
                                            />

                                            <div class="flex">
                                                <x-admin::form.control-group.error
                                                    control-name="message"
                                                    class="text-red-500"
                                                />
                                            </div>
                                        </x-admin::form.control-group>

                                        <div class="mb-4">
                                            <button
                                                type="button"
                                                id="newFileInput"
                                                class="transparent-button text-sm hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                                            >
                                                + @lang('admin::app.sales.rma.all-rma.view.add-attachments')

                                                <input
                                                    type="file"
                                                    id="file"
                                                    class="opacity-0 absolute w-fit cursor-pointer"
                                                    name="file"
                                                    @change="handleFileSelect($event)"
                                                />
                                            </button>

                                            <input type="hidden" name="removed_key" id="removed_key" />

                                            <div id="attachmentPreview"></div>
                                        </div>

                                        <div class="flex justify-end">
                                            <button
                                                class="primary-button"
                                                v-if="isSent"
                                                disabled
                                            >
                                                <svg  aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>

                                                @lang('admin::app.sales.rma.all-rma.view.send-message-btn')
                                            </button>

                                            <button
                                                class="primary-button"
                                                v-else
                                            >
                                                @lang('admin::app.sales.rma.all-rma.view.send-message-btn')
                                            </button>
                                        </div>
                                    </form>
                                </x-shop::form>
                            </div>

                            <div class="border rounded-lg p-3">
                                <div
                                    class="mb-3 overflow-x-auto p-5"
                                    style="height: 300px;"
                                    @wheel="getNewMessage()"
                                    :class="! messages.length ? 'flex justify-center items-center' : ''"
                                >
                                    <div
                                        v-if="messages.length"
                                        v-for="message in messages"
                                        :style="message.is_admin != 1 ? 'text-align:left; background-color: #a7a7a7' : 'text-align:right; background-color: #F0F0F0'"
                                        style="word-break: break-all;"
                                        class="mb-3 rounded-md p-4"
                                    >
                                        <div class="title">
                                            @lang('shop::app.rma.conversation-texts.by')

                                            <strong v-if="message.is_admin == 1">
                                                @lang('shop::app.rma.view-customer-rma.admin')
                                            </strong>

                                            <strong v-else>
                                            {{ $rma->order->customer_first_name }} {{ $rma->order->customer_last_name }}
                                            </strong>

                                            @lang('shop::app.rma.conversation-texts.on')

                                            @{{ dateFormat( message.created_at) }}
                                        </div>

                                        <div
                                            class="value dark:text-black-300 text-base font-medium"
                                            style="margin-top:10px; word-break: break-all;"
                                            v-html="message.message"
                                        >
                                        </div>

                                        <hr v-if="message.attachment"/>

                                        <a
                                            @click="viewAttachmentModal(message.attachment_path)"
                                            v-if="message.attachment"
                                            :style="message.is_admin != 1 ? 'color: black;' : 'color: black;'"
                                            class="icon-attribute dark:text-black-300 text-base font-normal cursor-pointer"
                                        >
                                            <span class="text-base hover:underline ml-2">
                                                @{{ message.attachment }}
                                            </span>
                                        </a>
                                    </div>

                                    <div v-else>
                                        <div
                                            class="icon-sales"
                                            style="font-size:150px; color:#d7d7d7;"
                                            >
                                        </div>

                                        <p class="flex justify-center text-gray-300">@lang('admin::app.sales.rma.all-rma.view.no-record')</p>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Component -->
                <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                    <!-- Statuses -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                                @lang('admin::app.sales.rma.all-rma.view.status')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="flex w-full justify-start gap-5">
                                <div class="flex flex-col gap-2.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.rma.all-rma.view.rma-status')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                       @lang('admin::app.sales.rma.all-rma.view.order-status')
                                    </p>

                                    @if ($rma->request_status == 'Declined')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('shop::app.rma.view-customer-rma.close-rma')
                                        </p>

                                    @endif
                                    @if ($rma->request_status == 'Item Canceled')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('shop::app.rma.view-customer-rma.close-rma')
                                        </p>
                                    @endif

                                </div>

                                <div class="flex flex-col gap-2.5">

                                    <!-- RMA Status -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        <span>
                                            @if (strtolower($rma->request_status) == 'Solved')
                                                <span class="label-active py-1">
                                                    @lang('shop::app.rma.status.status-name.solved')
                                                </span>

                                            @elseif(
                                                    strtolower($rma->request_status) == 'Canceled'
                                                    && strtolower($rma->request_status) == 'Closed'
                                                )
                                                <span
                                                    class="label-{{ $rma->request_status }} py-1 text-xs"
                                                >
                                                    @lang('shop::app.rma.status.status-name.item-canceled')
                                                </span>
                                            @else
                                                <span
                                                    class="label-active py-1 text-xs"
                                                    style="background: {{ $rmaStatusColor }}"
                                                >
                                                    {{ $rma->request_status }}
                                                </span>
                                            @endif
                                        </span>
                                    </p>

                                    <!-- RMA Order Status -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        <span
                                            @if ( $rma->order_status == '1')
                                                class="label-active py-1"
                                            @elseif ( strtolower($rma->order_status) == 'canceled' || strtolower($rma->order_status) == 'closed')
                                                class="label-{{strtolower($rma->order_status)}} py-1"
                                            @else
                                                class="label-info py-1"
                                            @endif
                                        >
                                            @if ($rma->order_status == '1')
                                                @lang('shop::app.rma.customer.delivered')
                                            @elseif ( strtolower($rma->order_status) == 'canceled' || strtolower($rma->order_status) == 'closed'
                                            )
                                                @lang('shop::app.rma.customer.'. strtolower($rma->order_status))
                                            @else
                                                @lang('shop::app.rma.customer.undelivered')
                                            @endif
                                        </span>
                                    </p>

                                    @if ($rma->request_status == 'Declined')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('shop::app.rma.status.status-quotes.declined-admin')
                                        </p>
                                    @endif

                                    @if ($rma->request_status == 'Item Canceled')
                                        <p class="text-gray-600 dark:text-gray-300">
                                            @lang('shop::app.rma.status.status-quotes.solved-by-admin')
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </x-slot>
                    </x-admin::accordion>

                    <!-- RMA change status-->
                    @if (
                        $rma->request_status != 'Solved'
                        && $rma->status != 1
                        && ! in_array($rma->order->status, ['canceled', 'closed'])
                    )
                        @if ($rma->request_status == 'Item Canceled')
                            @php($flag = 0)

                        @elseif ($rma->request_status == 'Received Package')
                            @php($flag = 0)

                        @elseif ($rma->request_status == 'Declined')
                            @php($flag = 0)


                        @elseif ($rma->request_status == 'Canceled')
                            @php($flag = 0)

                        @elseif (
                            $rma->resolution == 'Return'
                            && $rma->status == 1
                        )
                            @php($flag = 0)
                        @else
                            @php($flag = 1)
                        @endif

                        @if (
                            ! empty($flag)
                            && $flag == 1
                            && $rma->status == 0
                            && $rma->order->status != 'closed'
                        )
                            <x-admin::accordion>
                                <x-slot:header>
                                    <p class="p-3 text-base font-semibold text-gray-600 dark:text-gray-300 required">
                                        @lang('admin::app.sales.rma.all-rma.view.change-status')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <x-admin::form
                                        method="POST"
                                        :action="route('admin.sales.rma.save.status')"
                                    >
                                        <input
                                            type="hidden"
                                            name="rma_id"
                                            value="{{ $rma->id }}"
                                        >

                                        <x-admin::form.control-group class="mb-2 w-full">
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="request_status"
                                                rules="required"
                                                v-model="rmaStatus"
                                                :label="trans('admin::app.sales.rma.all-rma.index.datagrid.rma-status')"
                                                id="orderItem"
                                            >
                                                @foreach ($statusArr as $status)
                                                    <option value="{{ $status }}" {{ $rma->request_status == $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="request_status" />
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group
                                            v-if="rmaStatus == 'Received Package' || (Number({{ $rma->order->invoices->count() }}) > 0 && rmaStatus == 'Item Canceled')"
                                            class="mb-2 w-full"
                                        >
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.sales.refunds.create.refund-shipping')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="shipping"
                                                :rules="'required|min_value:0|max_value:' . $rma->order->base_shipping_invoiced - $rma->order->base_shipping_refunded"
                                                value="{{ $rma->order->base_shipping_invoiced - $rma->order->base_shipping_refunded }}"
                                                :label="trans('admin::app.sales.refunds.create.refund-shipping')"
                                                id="shipping"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="shipping" />
                                        </x-admin::form.control-group>

                                        <div class="account-action">
                                            <button
                                                type="submit"
                                                class="primary-button"
                                            >
                                                @lang('admin::app.sales.rma.all-rma.view.save-btn')
                                            </button>
                                        </div>
                                    </x-admin::form>
                                </x-slot:content>
                            </x-admin::accordion>
                        @endif

                        @if (
                            $rma->request_status == 'Item Canceled'
                            || ($rma->resolution == 'Return' && $rma->status == 1)
                            || ($rma->status == 1 && $rma->request_status != 'Declined')
                        )
                            <x-admin::accordion>
                                <x-slot:header>
                                    <p class="p-3 text-base font-semibold text-gray-600 dark:text-gray-300">
                                        @lang('shop::app.rma.view-customer-rma.change-rma-status')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <x-admin::form :action="route('admin.sales.rma.save.status')">
                                        <input
                                            type="hidden"
                                            name="rma_id"
                                            value="{{ $rma->id }}"
                                        >
                                    </x-admin::form>
                                </x-slot:content>
                            </x-admin::accordion>

                        @endif
                    @endif

                    <!-- Re open RMA -->
                    @if (
                        core()->getConfigData('sales.rma.setting.allowed_new_rma_request_for_declined_request') == 'yes'
                        && $rma->request_status == 'Declined'
                    )
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                                    @lang('shop::app.rma.view-customer-rma.status-reopen')
                                </p>
                            </x-slot>

                            <x-slot:content>
                                <div class="grid w-full py-3">
                                    <x-shop::form
                                        @submit="validateForm"
                                        id="check-form"
                                        enctype="multipart/form-data"
                                        :action="route('admin.sales.rma.save.reopen-status')"
                                    >
                                        @csrf
                                        <div class="w-full gap-4">
                                            <div>
                                                <input
                                                    type="hidden"
                                                    name="rma_id"
                                                    value="{{ $rma->id }}"
                                                >
                                                <x-admin::form.control-group class="flex gap-2.5 items-center !mb-2">
                                                    <!-- Checkbox for closing RMA -->
                                                    <x-admin::form.control-group.control
                                                        type="checkbox"
                                                        id="close_rma"
                                                        name="close_rma"
                                                        value="1"
                                                        for="close_rma"
                                                        @change="closeRmaChecked = !closeRmaChecked"
                                                    />
                                                    <label
                                                        class="text-sm text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                                        for="close_rma"
                                                    >
                                                        @lang('shop::app.rma.view-customer-rma.status-reopen')
                                                    </label>
                                                </x-admin::form.control-group>
                                            </div>

                                            <button
                                                type="submit"
                                                class="primary-button "
                                                v-if="closeRmaChecked"
                                            >
                                                @lang('shop::app.rma.view-customer-rma.save-btn')
                                            </button>
                                        </div>
                                    </x-shop::form>
                                </div>
                            </x-slot>
                        </x-admin::accordion>
                    @endif

                    <!-- Order Information -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                                @lang('shop::app.rma.view-customer-rma-content.order-details')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <div class="flex w-full justify-start gap-5">
                                <div class="flex flex-col gap-y-1.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.rma.all-rma.view.order-id')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                       @lang('admin::app.sales.rma.all-rma.view.order-total')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.rma.all-rma.view.order-date')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                         @lang('admin::app.sales.orders.view.payment-method') :
                                    </p>
                                </div>

                                <div class="flex flex-col gap-y-1.5">

                                    <!-- Order id -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        <a
                                            href="{{ route('admin.sales.orders.view', $rma->order_id) }}"
                                            target="_blank"
                                            class="cursor-pointer text-blue-600 transition-all hover:underline"
                                        >
                                            {{ '#'. $rma->order_id }}
                                        </a>
                                    </p>

                                    <!-- Order grand total -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ core()->formatBasePrice($rma->order->base_grand_total) }}
                                    </p>


                                    <!-- Order create date -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ date("F j, Y, h:i:s A" ,strtotime($rma->order->created_at)) }}
                                    </p>

                                     <!-- payment method -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $rma->order->payment->method_title }}
                                    </p>
                                </div>
                            </div>
                        </x-slot>
                    </x-admin::accordion>

                    <!-- Customer Information -->
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-base font-semibold text-gray-600 dark:text-gray-300">
                                 @lang('admin::app.sales.rma.all-rma.view.customer-details')
                            </p>
                        </x-slot>

                        <x-slot:content>
                            <div class="flex w-full justify-start gap-5">
                                <div class="flex flex-col gap-y-1.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.sales.rma.all-rma.view.customer')
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                       @lang('admin::app.sales.rma.all-rma.view.customer-email')
                                    </p>
                                </div>

                                <div class="flex flex-col gap-y-1.5">

                                    <!-- customer id -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @if (empty($rma->order->customer_id))
                                            <div class="text-sm dark:text-gray-300">
                                                {{ $rma->order->customer_first_name }} {{ $rma->order->customer_last_name }}  (@lang('shop::app.rma.view-customer-rma.guest'))
                                            </div>
                                        @else
                                            <a href="{{ route('admin.customers.customers.view', $rma->order->customer_id) }}">
                                                <div class="text-sm dark:text-gray-300">
                                                    {{ $rma->order->customer_first_name }} {{ $rma->order->customer_last_name }}
                                                </div>
                                            </a>
                                        @endif
                                    </p>

                                    <!-- Customer Email -->
                                    <p class="text-gray-600 dark:text-gray-300">
                                         {{ $rma->order->customer_email }}
                                    </p>
                                </div>
                            </div>
                        </x-slot>
                    </x-admin::accordion>
                </div>
            </div>

            <x-admin::modal ref="attachmentModal">
                <!-- Modal Header -->
                <x-slot:header>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.sales.rma.all-rma.view.attachment')
                    </p>
                </x-slot>

                <!-- Modal Content -->
                <x-slot:content>
                    {!! view_render_event('bagisto.admin.sales.rma.view.message.attachment.modal.content.before') !!}

                    <!-- Display Image -->
                    <img
                        v-if="
                            messagePath
                            && (
                                this.getAttachmentExtension === 'jpg'
                                || this.getAttachmentExtension === 'jpeg'
                                || this.getAttachmentExtension === 'png'
                                || this.getAttachmentExtension === 'gif'
                        )"
                        :src="'{{ config('app.url') }}' + '/storage/' + messagePath"
                        class="min-h-[500px] min-w-[500px] max-h-[500px] max-w-[500px] rounded m-auto"
                    />

                    <!-- Display PDF -->
                    <embed
                        v-if="
                            messagePath
                            && this.getAttachmentExtension === 'pdf'
                            "
                        :src="'{{ config('app.url') }}' + '/storage/' + messagePath"
                        width="100%" height="500px"
                        type="application/pdf"
                    />

                    <!-- Display Video -->
                    <video
                        v-if="
                            messagePath
                            && (
                                this.getAttachmentExtension === 'mp4'
                                || this.getAttachmentExtension === 'webm'
                                || this.getAttachmentExtension === 'ogg'
                            )"
                        controls
                        class="w-full h-auto max-h-[500px] rounded m-auto"
                    >
                        <source :src="'{{ config('app.url') }}' + '/storage/' + messagePath" />
                        Your browser does not support the video tag.
                    </video>

                    {!! view_render_event('bagisto.admin.sales.rma.view.message.attachment.modal.content.after') !!}
                </x-slot>

                <!-- Modal Footer -->
                <x-slot:footer>
                    <div class="flex items-center gap-x-2.5">
                        <!-- Save Button -->
                        <button
                            @click="downloadAttachment(messagePath)"
                            class="transparent-button"
                        >
                            @lang('admin::app.export.download')
                        </button>
                    </div>
                </x-slot>
            </x-admin::modal>
        </script>

        <script type="module">
            app.component('v-admin-rma-view', {
                template: '#v-admin-rma-view-template',

                data() {
                    return {
                        error: false,
                        closeRmaChecked: false,
                        messages: [],
                        message: '',
                        isSent: false,
                        rma: @json($rma),
                        limit: 5,
                        allowedFileTypes: @json(core()->getConfigData('sales.rma.setting.allowed_file_extension')),
                        rmaStatus: '',
                    };
                },

                mounted() {
                    console.log(this.allowedFileTypes);

                    this.getMessage();
                },

                computed: {
                    allowedFileTypesArray() {
                       return this.allowedFileTypes
                            .split(",")
                            .map(mime => mime.split("/")[1]?.trim())
                            .filter(Boolean);
                    }
                },

                methods: {
                    getMessage() {
                        this.$axios.get(`{{ route('admin.sales.rma.get-messages') }}`, {
                            params: {
                                id: this.rma.id,
                                limit: this.limit,
                            }
                        })
                        .then(response => {
                            this.messages =  response.data.messages.data;
                        }).catch(error => {
                        });
                    },

                    chatSubmit(params, { resetForm, setErrors  }) {
                        this.isSent = true;

                        let formData = new FormData(this.$refs.adminChatForm);

                        // Sanitize the message input
                        const messageInput = formData.get('message');

                        const sanitizedMessage = this.sanitizeInput(messageInput);

                        formData.set('message', sanitizedMessage);

                        this.$axios.post("{{ route('admin.sales.rma.send-message') }}", formData)
                            .then((response) => {
                                const attachmentPreview = document.getElementById('attachmentPreview');

                                attachmentPreview.innerHTML = '';

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.getNewMessage();

                                resetForm();

                                this.isSent = false;
                            })
                            .catch (error => {
                            });
                    },

                    sanitizeInput(input) {
                        const tempDiv = document.createElement('div');

                        tempDiv.textContent = input;

                        return tempDiv.innerHTML;
                    },

                    viewAttachmentModal(messagePath) {
                        this.messagePath = messagePath;

                        this.getAttachmentExtension = messagePath.split('.').pop().toLowerCase();

                        this.$refs.attachmentModal.toggle();
                    },

                    downloadAttachment(messagePath) {
                        const imageUrl = `{{ config('app.url') }}/storage/${messagePath}`;

                        const link = document.createElement('a');

                        link.href = imageUrl;

                        link.download = imageUrl.split('/').pop();

                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },

                    getNewMessage() {
                        this.limit += 5;

                        this.getMessage();
                    },

                    resetForm() {
                        this.message = '';
                    },

                    dateFormat(v) {
                        let date = new Date(v);

                        const day = String(date.getDate()).padStart(2, '0');
                        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                        const month = monthNames[date.getMonth()];
                        const year = date.getFullYear();
                        const hours = String(date.getHours()).padStart(2, '0');
                        const minutes = String(date.getMinutes()).padStart(2, '0');

                        return `${day}-${month}-${year} ${hours}:${minutes}`;
                    },

                    handleFileSelect($event) {
                        const attachmentPreview = document.getElementById('attachmentPreview');

                        attachmentPreview.innerHTML = '';

                        const inputElement = event.target;

                        const files = event.target.files;

                        const fileNames = Array.from(files).map(file => file.name);

                        if (this.allowedFileTypesArray.length) {
                            const fileExtensions = Array.from(files).map(file => {
                                const fileName = file.name;
                                const extension = fileName.slice(fileName.lastIndexOf('.') + 1);

                                return extension;
                            });

                            const hasAllowedFileType = fileExtensions.some(extension =>
                                this.allowedFileTypesArray.includes(extension.trim())
                            );

                            if (! hasAllowedFileType) {
                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: "@lang('admin::app.configuration.index.sales.rma.allowed-file-types')"
                                });

                                event.target.value = '';

                                inputElement.value = '';

                                return;
                            }
                        }

                        const fileParagraph = document.createElement('p');

                        fileParagraph.classList.add('attachmentPreview');

                        fileParagraph.classList.add('border', 'p-3', 'my-2', 'rounded-md');

                        fileParagraph.innerHTML = fileNames;

                        const removeButton = document.createElement('button');

                        removeButton.classList.add('removeFile');

                        removeButton.classList.add('text-blue-600');

                        removeButton.textContent = "@lang('admin::app.catalog.products.edit.remove')";

                        removeButton.style.float = 'right';

                        removeButton.addEventListener('click', function() {
                            attachmentPreview.innerHTML = '';

                            event.target.value = '';

                            inputElement.value = '';
                        });

                        fileParagraph.appendChild(removeButton);

                        attachmentPreview.appendChild(fileParagraph);
                    },
                }
            })
        </script>
    @endpush
</x-admin::layouts>
