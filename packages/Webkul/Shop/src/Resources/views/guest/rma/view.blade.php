<x-shop::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('shop::app.rma.guest.view.title')
    </x-slot>

    @php
        $canCloseRma = true;
        $canReopenRma = false;
        
        if (
            is_null($rma->request_status) ||
            in_array($rma->request_status, ['received_package', 'solved', 'item_canceled', 'declined', 'canceled']) ||
            in_array($rma->order->status, ['canceled', 'closed']) ||
            $rma->status == 1
        ) {
            $canCloseRma = false;
        }

        if (
            core()->getConfigData('sales.rma.setting.allowed_new_rma_request_for_cancelled_request') == 'yes' &&
            $rma->request_status == 'canceled'
        ) {
            $canReopenRma = true;
        }

        $expireDays = intval(core()->getConfigData('sales.rma.setting.default_allow_days'));
        $daysSinceCreation = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($rma->created_at));
        $isExpired = $daysSinceCreation > $expireDays && $daysSinceCreation != 0;

        $rmaStatus = app('Webkul\RMA\Repositories\RMAStatusRepository')
            ->where('title', $rma->request_status)
            ->first();
        $statusColor = $rmaStatus?->color ?? '';

        $orderCustomer = app('Webkul\Sales\Repositories\OrderRepository')->find(session()->get('guestOrderId'));
    @endphp

    <div class="container mx-auto max-md:px-0 my-8 flex max-md:flex-col">
        <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
            <!-- Page Header -->
            <div class="mb-8 flex gap-4 items-start justify-between">
                <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base">
                    @lang('admin::app.sales.rma.all-rma.index.datagrid.id') #{{ $rma->id }}
                </h2>

                <a
                    href="javascript:history.back();"
                    class="secondary-button flex items-center gap-x-2 border-[#E9E9E9] px-5 py-3 font-normal max-sm:px-3 max-sm:py-2 max-sm:text-sm w-full max-md:w-auto max-md:justify-center"
                >
                    @lang('shop::app.checkout.onepage.address.back')
                </a>
            </div>

            <!-- RMA Information -->
            <div class="mt-8">
                <h2 class="text-xl font-medium mb-5">
                    @lang('shop::app.rma.view-customer-rma.heading')
                </h2>

                <div class="rounded-xl border overflow-hidden">
                    <div class="p-6 max-sm:p-4 space-y-4">
                        <!-- Request Date -->
                        <div class="grid grid-cols-[200px_1fr] gap-4 max-md:grid-cols-1 max-sm:gap-2">
                            <span class="font-medium max-sm:text-sm">@lang('shop::app.rma.view-customer-rma-content.request-on')</span>
                            <span class="text-gray-600 max-sm:text-sm">{{ date("F j, Y, h:i:s A", strtotime($rma->created_at)) }}</span>
                        </div>

                        <!-- Order ID -->
                        <div class="grid grid-cols-[200px_1fr] gap-4 max-md:grid-cols-1 max-sm:gap-2">
                            <span class="font-medium max-sm:text-sm">@lang('shop::app.rma.view-customer-rma.order-id')</span>
                            <span class="text-gray-600 max-sm:text-sm">#{{ $rma->order_id }}</span>
                        </div>

                        <!-- Additional Fields -->
                        @if (! empty($rma->additionalFields))
                            @foreach ($rma->additionalFields as $field)
                                <div class="grid grid-cols-[200px_1fr] gap-4 max-md:grid-cols-1 max-sm:gap-2">
                                    <span class="font-medium max-sm:text-sm">{{ $field->customField->label }}</span>
                                    <span class="text-gray-600 max-sm:text-sm">{{ $field->field_value }}</span>
                                </div>
                            @endforeach
                        @endif

                        <!-- Additional Information -->
                        @if (!empty($rma->information))
                            <div class="grid grid-cols-[200px_1fr] gap-4 max-md:grid-cols-1 max-sm:gap-2">
                                <span class="font-medium max-sm:text-sm">@lang('shop::app.rma.view-customer-rma.additional-information')</span>
                                <span class="text-gray-600 max-sm:text-sm">{{ $rma->information }}</span>
                            </div>
                        @endif

                        <!-- Images -->
                        @if (! empty($rma->images) && $rma->images->count() > 0)
                            <div class="grid grid-cols-[200px_1fr] gap-4 max-md:grid-cols-1 max-sm:gap-2">
                                <span class="font-medium max-sm:text-sm">@lang('shop::app.rma.view-customer-rma.images')</span>

                                <div class="flex gap-2 flex-wrap">
                                    @foreach ($rma->images as $image)
                                        <a href="{{ Storage::url($image['path']) }}" target="_blank">
                                            <img src="{{ Storage::url($image['path']) }}" 
                                                class="w-24 h-24 object-cover rounded border shadow-sm hover:shadow-md transition max-sm:w-20 max-sm:h-20">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RMA Items -->
            <div class="mt-8">
                <h2 class="text-xl font-medium mb-5">
                    @lang('shop::app.rma.view-customer-rma.items-request')
                </h2>

                <div class="rounded-xl border overflow-x-auto">
                    <!-- Desktop Table View -->
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                                    @lang('shop::app.rma.table-heading.image') / @lang('shop::app.rma.table-heading.product-name')
                                </th>
                                
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                                    @lang('shop::app.rma.table-heading.sku')
                                </th>
                                
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                                    @lang('shop::app.rma.table-heading.price')
                                </th>
                                
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                                    @lang('shop::app.rma.table-heading.rma-qty')
                                </th>
                                
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                                    @lang('shop::app.rma.table-heading.resolution-type')
                                </th>
                                
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                                    @lang('shop::app.rma.table-heading.reason')
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach($rma->items as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($item->orderItem->product?->images?->first())
                                                <img 
                                                    src="{{ asset('storage/' . $item->orderItem->product->images->first()->path) }}" 
                                                    class="w-16 h-16 object-cover rounded border"
                                                />
                                            @else
                                                <div class="w-16 h-16 border border-dashed rounded flex items-center justify-center text-gray-300">
                                                    <span class="text-xs">No Image</span>
                                                </div>
                                            @endif

                                            <div>
                                                <a 
                                                    href="{{ route('shop.product_or_category.index', $item->orderItem->product->url_key) }}" 
                                                    class="text-blue-600 hover:underline text-sm" 
                                                    target="_blank"
                                                >
                                                    {{ $item->orderItem->name }}
                                                </a>

                                                {!! app('Webkul\RMA\Helpers\Helper')->getOptionDetailHtml($item->orderItem->additional['attributes'] ?? []) !!}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $item->orderItem->product->sku }}
                                    </td>
                                    
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {!! core()->formatPrice($item->orderItem->product->price, $item->orderItem->order->order_currency_code) !!}
                                    </td>
                                    
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $item->quantity }} / {{ $item->orderItem->qty_ordered }}
                                    </td>
                                    
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ ucwords($item->resolution) }}
                                    </td>
                                    
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $item->getReasons->title }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-4 p-4">
                        @foreach($rma->items as $item)
                            <div class="border rounded-lg p-4 space-y-3">
                                <!-- Product Image and Name -->
                                <div class="flex gap-3">
                                    @if ($item->orderItem->product?->images?->first())
                                        <img 
                                            src="{{ asset('storage/' . $item->orderItem->product->images->first()->path) }}" 
                                            class="w-16 h-16 object-cover rounded border"
                                        />
                                    @else
                                        <div class="w-16 h-16 border border-dashed rounded flex items-center justify-center text-gray-300 flex-shrink-0">
                                            <span class="text-xs text-center">No Image</span>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <a 
                                            href="{{ route('shop.product_or_category.index', $item->orderItem->product->url_key) }}" 
                                            class="text-blue-600 hover:underline text-sm font-medium break-words" 
                                            target="_blank"
                                        >
                                            {{ $item->orderItem->name }}
                                        </a>

                                        {!! app('Webkul\RMA\Helpers\Helper')->getOptionDetailHtml($item->orderItem->additional['attributes'] ?? []) !!}
                                    </div>
                                </div>

                                <!-- SKU -->
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.sku'):</span>
                                    <span class="text-gray-800">{{ $item->orderItem->product->sku }}</span>
                                </div>

                                <!-- Price -->
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.price'):</span>
                                    <span class="text-gray-800">{!! core()->formatPrice($item->orderItem->product->price, $item->orderItem->order->order_currency_code) !!}</span>
                                </div>

                                <!-- Quantity -->
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.rma-qty'):</span>
                                    <span class="text-gray-800">{{ $item->quantity }} / {{ $item->orderItem->qty_ordered }}</span>
                                </div>

                                <!-- Resolution Type -->
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.resolution-type'):</span>
                                    <span class="text-gray-800">{{ ucwords($item->resolution) }}</span>
                                </div>

                                <!-- Reason -->
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.reason'):</span>
                                    <span class="text-gray-800">{{ $item->getReasons->title }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Status Details -->
            <div class="mt-8">
                <h2 class="text-xl font-medium mb-5">
                    @lang('shop::app.rma.view-customer-rma.status-details')
                </h2>

                <div class="rounded-xl border p-6 max-sm:p-4 space-y-4">
                    <!-- RMA Status -->
                    <div class="grid grid-cols-[200px_1fr] gap-4 max-md:grid-cols-1 max-sm:gap-2">
                        <span class="font-medium max-sm:text-sm">
                            @lang('shop::app.rma.view-customer-rma-content.rma-status')
                        </span>

                        <div>
                            @if ($rma->status == 1 || $rma->request_status == 'solved')
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    @lang('shop::app.rma.status.status-name.solved')
                                </span>
                            @elseif(in_array($rma->order->status, ['canceled', 'closed']))
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    @lang('shop::app.rma.status.status-name.item-canceled')
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full" style="background: {{ $statusColor }}20; color: {{ $statusColor }}">
                                    {{ format_title_case($rma->request_status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="grid grid-cols-[200px_1fr] gap-4 max-md:grid-cols-1 max-sm:gap-2">
                        <span class="font-medium max-sm:text-sm">
                            @lang('shop::app.rma.view-customer-rma-content.order-status')
                        </span>
                        
                        <span class="px-3 py-1 text-xs rounded-full w-fit {{ $rma->order_status == '1' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            @if ($rma->order_status == '1')
                                @lang('shop::app.rma.customer.delivered')
                            @else
                                @lang('shop::app.rma.customer.undelivered')
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <option-wrapper></option-wrapper>
        </div>
    </div>

    @push('scripts')
        <script type="text/x-template" id="option-wrapper-template">
            @if (! $isExpired)
                @if ($canCloseRma)
                    <div class="relative mt-3 overflow-x-auto rounded-xl border px-8 py-4 max-sm:px-4 max-sm:py-3">
                        <div class="mt-2">
                            <p class="text-xl font-medium max-sm:text-lg">
                                @lang('shop::app.rma.view-customer-rma.close-rma')
                            </p>
                        </div>

                        <div class="grid w-full py-3">
                            <x-shop::form
                                @submit="validateForm"
                                id="check-form"
                                enctype="multipart/form-data"
                                :action="route('shop.rma.action.close')"
                            >
                                @csrf
                                <div class="grid w-full gap-4">
                                    <div>
                                        <input type="hidden" name="rma_id" value="{{ $rma->id }}">

                                        <x-shop::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                            <x-shop::form.control-group.control
                                                type="checkbox"
                                                id="close_rma"
                                                name="close_rma"
                                                @change="closeRmaChecked = !closeRmaChecked"
                                                for="close_rma"
                                                value="1"
                                            />

                                            <label class="cursor-pointer text-xs font-medium text-gray-600 max-sm:text-xs" for="close_rma">
                                                @lang('shop::app.rma.view-customer-rma.status-quotes')
                                            </label>
                                        </x-shop::form.control-group>
                                    </div>

                                    <div>
                                        <button
                                            type="submit"
                                            class="primary-button m-0 block w-max rounded-2xl px-11 py-3 text-center text-base max-sm:px-6 max-sm:py-2 max-sm:text-sm"
                                            v-if="closeRmaChecked"
                                        >
                                            @lang('shop::app.rma.view-customer-rma.save-btn')
                                        </button>
                                    </div>
                                </div>
                            </x-shop::form>
                        </div>
                    </div>
                @else
                    @if ($canReopenRma)
                        <div class="relative mt-3 overflow-x-auto rounded-xl border px-8 py-4 max-sm:px-4 max-sm:py-3">
                            <div class="mt-2">
                                <p class="text-xl font-medium max-sm:text-lg">
                                    @lang('shop::app.rma.view-customer-rma.status-reopen')
                                </p>
                            </div>

                            <div class="grid w-full py-3">
                                <x-shop::form
                                    @submit="validateForm"
                                    id="check-form"
                                    enctype="multipart/form-data"
                                    :action="route('shop.rma.action.re-open')"
                                >
                                    @csrf
                                    <div class="flex flex-col w-full gap-4 max-sm:gap-3">
                                        <div>
                                            <input type="hidden" name="rma_id" value="{{ $rma->id }}">

                                            <input
                                                type="checkbox"
                                                id="close_rma"
                                                name="close_rma"
                                                v-model="closeRmaChecked"
                                                v-validate="'required'"
                                                data-vv-as="{{ trans('shop::app.rma.validation.close-rma') }}"
                                            >

                                            <label for="close_rma" class="required text-xs font-medium max-sm:text-xs">
                                                @lang('shop::app.rma.customer.create.reopen-request')
                                            </label>

                                            <p v-if="error" style="color:red;" class="text-sm max-sm:text-xs">
                                                @lang('shop::app.rma.view-customer-rma.term')
                                            </p>
                                        </div>

                                        <button
                                            type="submit"
                                            class="primary-button m-0 block w-max rounded-2xl px-11 py-3 text-center text-base max-sm:px-6 max-sm:py-2 max-sm:text-sm"
                                            v-if="closeRmaChecked"
                                        >
                                            @lang('shop::app.rma.view-customer-rma.save-btn')
                                        </button>
                                    </div>
                                </x-shop::form>
                            </div>
                        </div>
                    @endif
                @endif
            @endif

            <!-- Conversations -->
            <div class="mt-8">
                <p class="required text-xl font-medium max-sm:text-lg">
                    @lang('shop::app.rma.view-customer-rma.conversations')
                </p>
            </div>

            <div class="relative mt-3 overflow-x-auto rounded-xl border p-2">
                <div class="border rounded-lg p-3 max-sm:p-2">
                    <x-shop::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, chatSubmit)" ref="chatForm">
                            <input type="hidden" name="is_admin" value="0"/>
                            <input type="hidden" name="rma_id" value="{{ $rma->id }}"/>

                            <x-shop::form.control-group>
                                <div class="bg-white !pl-0 !pt-2">
                                    <x-shop::form.control-group.control
                                        type="textarea"
                                        name="message"
                                        class="!mb-1 px-5 py-5 max-sm:px-3 max-sm:py-3 max-sm:text-sm"
                                        rules="required"
                                        maxlength="250"
                                        :placeholder="trans('admin::app.sales.rma.all-rma.view.enter-message')"
                                        v-model="message"
                                        ::disabled="!isChatSend"
                                    >
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error class="flex" control-name="message">
                                    </x-shop::form.control-group.error>
                                </div>
                            </x-shop::form.control-group>

                            <div class="mb-4">
                                <button type="button" id="newFileInput" class="transparent-button text-sm hover:bg-gray-200 relative max-sm:text-xs">
                                    + @lang('admin::app.sales.rma.all-rma.view.add-attachments')

                                    <input
                                        type="file"
                                        id="file"
                                        class="opacity-0 absolute top-0 left-0 w-full h-full cursor-pointer"
                                        name="file"
                                        @change="handleFileSelect($event)"
                                    />
                                </button>

                                <input type="hidden" name="removed_key" id="removed_key" />
                                <div id="attachmentPreview"></div>
                            </div>

                            <div class="flex justify-end">
                                <button class="primary-button max-sm:px-4 max-sm:py-2 max-sm:text-sm" :disabled="!isChatSend">
                                    <svg v-if="!isChatSend" aria-hidden="true" class="w-5 h-5 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                    </svg>
                                    @lang('admin::app.sales.rma.all-rma.view.send-message-btn')
                                </button>
                            </div>
                        </form>
                    </x-shop::form>
                </div>

                <!-- View conversations -->
                <div class="border rounded-lg mt-2 p-3 max-sm:p-2">
                    <div
                        class="h-80 overflow-x-auto p-5 max-sm:p-3 max-sm:h-64"
                        @wheel="getNewMessage()"
                        :class="! messages.length ? 'flex justify-center items-center' : ''"
                    >
                        <div
                            v-if="messages.length"
                            v-for="message in messages"
                            class="rounded max-sm:text-sm"
                            style="padding: 10px; margin: 10px;"
                            :style="message.is_admin == 1 ? 'text-align:left; background-color: #a7a7a7' : 'text-align:right; background-color: #F0F0F0'"
                        >
                            <div class="title text-xs max-sm:text-xs">
                                @lang('shop::app.rma.conversation-texts.by')
                                <strong v-if="message.is_admin == 1">@lang('shop::app.rma.view-customer-rma.admin')</strong>
                                <strong v-else>{{ $orderCustomer->customer_first_name }} {{ $orderCustomer->customer_last_name }}</strong>
                                @lang('shop::app.rma.conversation-texts.on')
                                @{{ dateFormat(message.created_at) }}
                            </div>

                            <div class="value" style="margin-top:10px; word-break: break-all;" v-html="message.message"></div>

                            <hr v-if="message.attachment"/>

                            <a
                                @click="viewAttachmentModal(message.attachment_path)"
                                v-if="message.attachment"
                                class="icon-hamburger text-sm font-normal cursor-pointer max-sm:text-xs"
                            >
                                <span class="text-sm hover:underline cursor-pointer ml-2 max-sm:text-xs">
                                    @{{ message.attachment }}
                                </span>
                            </a>
                        </div>

                        <div v-else>
                            <div class="icon-listing text-6xl max-sm:text-5xl" style="color:#d7d7d7;"></div>
                            <p class="flex justify-center text-gray-300 text-sm max-sm:text-xs">
                                @lang('shop::app.rma.conversation-texts.no-record')
                            </p>
                        </div>
                    </div>
                </div>

                <x-shop::modal ref="attachmentModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 max-sm:text-base">
                            @lang('admin::app.sales.rma.all-rma.view.attachment')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <img
                            v-if="messagePath && (getAttachmentExtension === 'jpg' || getAttachmentExtension === 'jpeg' || getAttachmentExtension === 'png' || getAttachmentExtension === 'gif')"
                            :src="'{{ config('app.url') }}' + '/storage/' + messagePath"
                            class="min-h-[500px] min-w-[500px] max-h-[500px] max-w-[500px] rounded m-auto max-sm:min-h-[300px] max-sm:min-w-[300px] max-sm:max-h-[300px] max-sm:max-w-[300px]"
                        />

                        <embed
                            v-if="messagePath && getAttachmentExtension === 'pdf'"
                            :src="'{{ config('app.url') }}' + '/storage/' + messagePath"
                            width="100%" height="500px"
                            type="application/pdf"
                            class="max-sm:max-h-[400px]"
                        />

                        <video
                            v-if="messagePath && (getAttachmentExtension === 'mp4' || getAttachmentExtension === 'webm' || getAttachmentExtension === 'ogg')"
                            controls
                            class="w-full h-auto max-h-[500px] rounded m-auto max-sm:max-h-[300px]"
                        >
                            <source :src="'{{ config('app.url') }}' + '/storage/' + messagePath" />
                            Your browser does not support the video tag.
                        </video>
                    </x-slot>

                    <x-slot:footer>
                        <div class="flex items-center gap-x-2.5">
                            <button @click="downloadAttachment(messagePath)" class="transparent-button max-sm:text-sm">
                                @lang('admin::app.export.download')
                            </button>
                        </div>
                    </x-slot>
                </x-shop::modal>
            </div>
        </script>

        <script type="module">
            app.component('option-wrapper', {
                template: '#option-wrapper-template',
                inject: ['$validator'],
                
                data() {
                    return {
                        error: false,
                        closeRmaChecked: false,
                        isChatSend: true,
                        messages: {},
                        message: '',
                        rma: @json($rma),
                        limit: 5,
                        allowedFileTypes: @json(core()->getConfigData('sales.rma.setting.allowed_file_extension')),
                        messagePath: '',
                        getAttachmentExtension: ''
                    };
                },

                mounted() {
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
                        this.$axios.get(`{{ route('shop.rma.action.get.messages') }}`, {
                            params: { id: this.rma.id, limit: this.limit }
                        })
                        .then(response => {
                            this.messages = response.data.messages.data;
                        }).catch(error => {});
                    },

                    chatSubmit(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.chatForm);
                        const messageInput = formData.get('message');
                        const sanitizedMessage = this.sanitizeInput(messageInput);
                        formData.set('message', sanitizedMessage);
                        this.isChatSend = false;

                        this.$axios.post("{{ route('shop.rma.action.send.message') }}", formData)
                            .then((response) => {
                                const attachmentPreview = document.getElementById('attachmentPreview');
                                attachmentPreview.innerHTML = '';
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.messages });
                                this.getNewMessage();
                                resetForm();
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
                        this.isChatSend = true;
                    },

                    resetForm() {
                        this.message = '';
                    },

                    validateForm(scope) {
                        if (!this.closeRmaChecked) {
                            this.error = true;
                            return;
                        }
                        this.error = false;
                        document.getElementById('check-form').submit();
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
                                this.allowedFileTypesArray.includes(extension)
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
</x-shop::layouts>