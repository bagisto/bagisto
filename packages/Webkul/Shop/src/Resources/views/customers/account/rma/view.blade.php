<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.rma.customer.create.view')
    </x-slot>

    @section('breadcrumbs')
        <x-shop::breadcrumbs name="rma.view"></x-shop::breadcrumbs>
    @endSection

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <!-- Back Button -->
            <a
                class="grid md:hidden"
                href="{{ route('shop.customers.account.index') }}"
            >
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.rma.view.id') #{{ $rma->id }}
            </h2>
        </div>

        <!-- RMA Information -->
        <div class="mt-8">
            <h2 class="text-xl font-medium mb-5">
                @lang('shop::app.rma.view-customer-rma.heading')
            </h2>

            <div class="rounded-xl border overflow-hidden">
                <div class="p-6 space-y-4 max-md:p-4">
                    <!-- Request Date -->
                    <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                        <span class="font-medium">
                            @lang('shop::app.rma.view-customer-rma-content.request-on')
                        </span>
                        
                        <span class="text-gray-600">
                            {{ date("F j, Y, h:i:s A", strtotime($rma->created_at)) }}
                        </span>
                    </div>

                    <!-- Order ID -->
                    <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                        <span class="font-medium">@lang('shop::app.rma.view-customer-rma.order-id')</span>

                        <a 
                            href="{{ route('shop.customers.account.orders.view', $rma->order_id) }}" 
                            class="text-blue-600 hover:underline" target="_blank"
                        >
                            #{{ $rma->order_id }}
                        </a>
                    </div>

                    <!-- Additional Fields -->
                    @if (! empty($rma->additionalFields))
                        @foreach ($rma->additionalFields as $field)
                            <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                                <span class="font-medium">{{ $field->customField->label }}</span>

                                <span class="text-gray-600">{{ $field->field_value }}</span>
                            </div>
                        @endforeach
                    @endif

                    <!-- Additional Information -->
                    @if (! empty($rma->information))
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                            <span class="font-medium">@lang('shop::app.rma.view-customer-rma.additional-information')</span>

                            <span class="text-gray-600">{{ $rma->information }}</span>
                        </div>
                    @endif

                    <!-- Images -->
                    @if ($rma->images->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                            <span class="font-medium">@lang('shop::app.rma.view-customer-rma.images')</span>

                            <div class="flex gap-2 flex-wrap">
                                @foreach ($rma->images as $image)
                                    <a href="{{ Storage::url($image['path']) }}" target="_blank">
                                        <img 
                                            src="{{ Storage::url($image['path']) }}" 
                                            class="w-24 h-24 max-sm:w-20 max-sm:h-20 object-cover rounded border shadow-sm hover:shadow-md transition"
                                        />
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

            <!-- Desktop Table View -->
            <div class="rounded-xl border overflow-x-auto hidden md:block">
                <table class="w-full">
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
                                    {{ $item->reason->title }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4">
                @foreach($rma->items as $item)
                    <div class="rounded-xl border p-4 space-y-3">
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

                            <div class="flex-1">
                                <a 
                                    href="{{ route('shop.product_or_category.index', $item->orderItem->product->url_key) }}" 
                                    class="text-blue-600 hover:underline text-sm font-medium" 
                                    target="_blank"
                                >
                                    {{ $item->orderItem->name }}
                                </a>

                                {!! app('Webkul\RMA\Helpers\Helper')->getOptionDetailHtml($item->orderItem->additional['attributes'] ?? []) !!}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.sku')</span>
                                <p class="text-gray-600">{{ $item->orderItem->product->sku }}</p>
                            </div>

                            <div>
                                <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.price')</span>
                                <p class="text-gray-600">{!! core()->formatPrice($item->orderItem->product->price, $item->orderItem->order->order_currency_code) !!}</p>
                            </div>

                            <div>
                                <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.rma-qty')</span>
                                <p class="text-gray-600">{{ $item->quantity }} / {{ $item->orderItem->qty_ordered }}</p>
                            </div>

                            <div>
                                <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.resolution-type')</span>
                                <p class="text-gray-600">{{ ucwords($item->resolution) }}</p>
                            </div>
                        </div>

                        <div>
                            <span class="font-medium text-gray-600">@lang('shop::app.rma.table-heading.reason')</span>

                            <p class="text-gray-600">{{ $item->reason->title }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Status Details -->
        <div class="mt-8">
            <h2 class="text-xl font-medium mb-5">
                @lang('shop::app.rma.view-customer-rma.status-details')
            </h2>

            <div class="rounded-xl border p-6 max-md:p-4 space-y-4">
                <!-- RMA Status -->
                <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                    <span class="font-medium">
                        @lang('shop::app.rma.view-customer-rma-content.rma-status')
                    </span>

                    <div>
                        <span class="px-3 py-1 text-xs rounded-full" style="background: {{ $rmaStatus->color }}20; color: {{ $rmaStatus->color }}">
                            {{ $rmaStatus->title }}
                        </span>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                    <span class="font-medium">
                        @lang('shop::app.rma.view-customer-rma-content.order-status')
                    </span>
                    
                    <span class="px-3 py-1 text-xs rounded-full w-fit {{ $rma->order_status == '1' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        @if (strtolower($rma->order->status) == 'canceled' || strtolower($rma->order->status) == 'closed')
                            {{ $rma->order->status }}
                        @elseif ($rma->order_status == '1')
                            @lang('shop::app.rma.customer.delivered')
                        @else
                            @lang('shop::app.rma.customer.undelivered')
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <rma-status-and-conversation></rma-status-and-conversation>
    </div>

    @push('scripts')
        <script type="text/x-template" id="rma-status-and-conversation-template">
            @if (! $isExpired)
                @if ($canCloseRma)
                    <div class="relative mt-3 overflow-x-auto rounded-xl border p-6 max-md:p-4">
                        <div class="mt-2">
                            <p class="text-xl max-sm:text-lg font-medium">
                                @lang('shop::app.rma.view-customer-rma.close-rma')
                            </p>
                        </div>

                        <div class="grid w-full py-3">
                            <x-shop::form
                                @submit="validateForm"
                                id="check-form"
                                enctype="multipart/form-data"
                                :action="route('shop.customers.account.rma.update-status', $rma->id)"
                            >
                                <div class="grid w-full gap-4">
                                    <div>
                                        <x-shop::form.control-group class="!mb-2 flex select-none items-center gap-2.5">
                                            <x-shop::form.control-group.control
                                                type="checkbox"
                                                id="close_rma"
                                                name="close_rma"
                                                @change="closeRmaChecked = !closeRmaChecked"
                                                for="close_rma"
                                                value="1"
                                            />

                                            <label class="cursor-pointer text-xs font-medium text-gray-600" for="close_rma">
                                                @lang('shop::app.rma.view-customer-rma.status-quotes')
                                            </label>
                                        </x-shop::form.control-group>
                                    </div>

                                    <div>
                                        <button
                                            type="submit"
                                            class="primary-button m-0 block w-max rounded-2xl px-11 max-sm:px-6 py-3 text-center text-base max-sm:text-sm"
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
                        <div class="relative mt-3 overflow-x-auto rounded-xl border p-6 max-md:p-4">
                            <div class="mt-2">
                                <p class="text-xl max-sm:text-lg font-medium">
                                    @lang('shop::app.rma.view-customer-rma.status-reopen')
                                </p>
                            </div>

                            <div class="grid w-full py-3">
                                <x-shop::form
                                    @submit="validateForm"
                                    id="check-form"
                                    enctype="multipart/form-data"
                                    :action="route('shop.customers.account.rma.re-open', $rma->id)"
                                >
                                    <div class="flex flex-col max-md:flex-col w-full gap-4">
                                        <div>
                                            <input
                                                type="checkbox"
                                                id="reopen_rma"
                                                name="reopen_rma"
                                                v-model="closeRmaChecked"
                                                v-validate="'required'"
                                                data-vv-as="{{ trans('shop::app.rma.validation.close-rma') }}"
                                            >

                                            <label for="reopen_rma" class="required text-xs font-medium">
                                                @lang('shop::app.rma.customer.create.reopen-request')
                                            </label>

                                            <p v-if="error" style="color:red;">
                                                @lang('shop::app.rma.view-customer-rma.term')
                                            </p>
                                        </div>

                                        <button
                                            type="submit"
                                            class="primary-button m-0 block w-max rounded-2xl px-11 max-sm:px-6 py-3 text-center text-base max-sm:text-sm"
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
                <p class="required text-xl max-sm:text-lg font-medium">
                    @lang('shop::app.rma.view-customer-rma.conversations')
                </p>
            </div>

            <div class="relative mt-3 overflow-x-auto rounded-xl border p-2 max-md:p-1">
                <div class="border rounded-lg p-3 max-md:p-2">
                    <x-shop::form 
                        v-slot="{ meta, errors, handleSubmit }" 
                        as="div"
                    >
                        <form 
                            @submit="handleSubmit($event, chatSubmit)" 
                            ref="chatForm"
                        >
                            <input 
                                type="hidden" 
                                name="is_admin" 
                                value="0"
                            />

                            <input 
                                type="hidden" 
                                name="rma_id" 
                                value="{{ $rma->id }}"
                            />

                            <x-shop::form.control-group>
                                <div class="bg-white !pl-0 !pt-2">
                                    <x-shop::form.control-group.control
                                        type="textarea"
                                        name="message"
                                        class="!mb-1 px-5 max-md:px-3 py-5 max-md:py-3"
                                        rules="required"
                                        maxlength="250"
                                        :placeholder="trans('shop::app.customers.account.rma.view.enter-message')"
                                        v-model="message"
                                        ::disabled="!isChatSend"
                                    >
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error class="flex" control-name="message" />
                                </div>
                            </x-shop::form.control-group>

                            <div class="mb-4 max-md:mb-2">
                                <button type="button" id="newFileInput" class="transparent-button text-sm max-sm:text-xs hover:bg-gray-200 relative">
                                    + @lang('shop::app.customers.account.rma.view.add-attachments')

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
                                <button class="primary-button text-sm max-sm:text-xs max-sm:px-4 max-sm:py-2" :disabled="!isChatSend">
                                    <svg v-if="!isChatSend" aria-hidden="true" class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                    </svg>

                                    @lang('shop::app.customers.account.rma.view.send-message-btn')
                                </button>
                            </div>
                        </form>
                    </x-shop::form>
                </div>

                <!-- View conversations -->
                <div class="border rounded-lg mt-2 p-3 max-md:p-2">
                    <div
                        class="h-80 max-md:h-60 overflow-x-auto p-5 max-md:p-3"
                        @wheel="getNewMessage()"
                        :class="! messages.length ? 'flex justify-center items-center' : ''"
                    >
                        <div
                            v-if="messages.length"
                            v-for="message in messages"
                            class="rounded text-sm max-sm:text-xs"
                            style="padding: 10px; margin: 10px; margin-top: 5px; margin-bottom: 5px;"
                            :style="message.is_admin == 1 ? 'text-align:left; background-color: #a7a7a7' : 'text-align:right; background-color: #F0F0F0'"
                        >
                            <div class="title font-medium">
                                @lang('shop::app.rma.conversation-texts.by')

                                <strong v-if="message.is_admin == 1">@lang('shop::app.rma.view-customer-rma.admin')</strong>

                                <strong v-else>{{ auth()->guard('customer')->user()->name }}</strong>

                                @lang('shop::app.rma.conversation-texts.on')

                                @{{ dateFormat(message.created_at) }}
                            </div>

                            <div class="value" style="margin-top:10px; word-break: break-all;" v-html="message.message"></div>

                            <hr v-if="message.attachment"/>

                            <a
                                @click="viewAttachmentModal(message.attachment_path)"
                                v-if="message.attachment"
                                class="icon-hamburger text-sm font-normal cursor-pointer"
                            >
                                <span class="text-xs max-sm:text-xs hover:underline cursor-pointer ml-2">
                                    @{{ message.attachment }}
                                </span>
                            </a>
                        </div>

                        <div v-else>
                            <div class="icon-listing" style="font-size:150px; color:#d7d7d7;"></div>
                            
                            <p class="flex justify-center text-gray-300">
                                @lang('shop::app.rma.conversation-texts.no-record')
                            </p>
                        </div>
                    </div>
                </div>

                <x-shop::modal ref="attachmentModal">
                    <x-slot:header>
                        <p class="text-lg max-md:text-base font-bold text-gray-800">
                            @lang('shop::app.customers.account.rma.view.attachment')
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <img
                            v-if="messagePath && (getAttachmentExtension === 'jpg' || getAttachmentExtension === 'jpeg' || getAttachmentExtension === 'png' || getAttachmentExtension === 'gif')"
                            :src="'{{ config('app.url') }}' + '/storage/' + messagePath"
                            class="min-h-[500px] min-w-[500px] max-h-[500px] max-w-[500px] max-md:min-h-[300px] max-md:min-w-[300px] max-md:max-h-[300px] max-md:max-w-[300px] rounded m-auto"
                        />

                        <embed
                            v-if="messagePath && getAttachmentExtension === 'pdf'"
                            :src="'{{ config('app.url') }}' + '/storage/' + messagePath"
                            width="100%" height="500px"
                            type="application/pdf"
                        />

                        <video
                            v-if="messagePath && (getAttachmentExtension === 'mp4' || getAttachmentExtension === 'webm' || getAttachmentExtension === 'ogg')"
                            controls
                            class="w-full h-auto max-h-[500px] max-md:max-h-[300px] rounded m-auto"
                        >
                            <source :src="'{{ config('app.url') }}' + '/storage/' + messagePath" />
                            Your browser does not support the video tag.
                        </video>
                    </x-slot>

                    <x-slot:footer>
                        <div class="flex items-center gap-x-2.5">
                            <button @click="downloadAttachment(messagePath)" class="transparent-button text-sm max-sm:text-xs">
                                @lang('shop::app.customers.account.rma.view.download')
                            </button>
                        </div>
                    </x-slot>
                </x-shop::modal>
            </div>
        </script>

        <script type="module">
            app.component('rma-status-and-conversation', {
                template: '#rma-status-and-conversation-template',
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
                        this.$axios.get(`{{ route('shop.customers.account.rma.get-messages') }}`, {
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

                        this.$axios.post("{{ route('shop.customers.account.rma.send-message') }}", formData)
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

                            if (!hasAllowedFileType) {
                                this.$emitter.emit('add-flash', {
                                    type: 'warning',
                                    message: "@lang('shop::app.customers.account.rma.view.allowed-file-types')"
                                });
                                event.target.value = '';
                                inputElement.value = '';
                                return;
                            }
                        }

                        const fileParagraph = document.createElement('p');
                        fileParagraph.classList.add('attachmentPreview', 'border', 'p-3', 'my-2', 'rounded-md');
                        fileParagraph.innerHTML = fileNames;

                        const removeButton = document.createElement('button');
                        removeButton.classList.add('removeFile', 'text-blue-600');
                        removeButton.textContent = "@lang('shop::app.customers.account.rma.view.remove')";
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
</x-shop::layouts.account>