<!-- Invoice Create Vue Component -->
<v-create-invoices>
    <div class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
        <span class="icon-sales text-2xl"></span>

        @lang('admin::app.sales.invoices.create.invoice')
    </div>
</v-create-invoices>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-create-invoices-template"
    >
        <div>
            <div
                class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    @click="$refs.invoice.open()"
                >
                    <span
                        class="icon-sales text-2xl"
                        role="presentation"
                        tabindex="0"
                    ></span> 
            
                    @lang('admin::app.sales.invoices.create.invoice')     
            </div>

            <!-- Invoice Create drawer -->
            <x-admin::form  
                method="POST"
                :action="route('admin.sales.invoices.store', $order->id)"
            >
                <x-admin::drawer ref="invoice">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid h-8 gap-3">
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-medium dark:text-white">
                                    @lang('admin::app.sales.invoices.create.new-invoice')         
                                </p>
    
                                @if (bouncer()->hasPermission('sales.invoices.create'))
                                    <button
                                        type="submit"
                                        class="primary-button ltr:mr-11 rtl:ml-11"
                                    >
                                        @lang('admin::app.sales.invoices.create.create-invoice')        
                                    </button>
                                @endif
                            </div>
                        </div>
                    </x-slot>
    
                    <!-- Drawer Content -->
                    <x-slot:content class="!p-0">
                        <div class="grid p-4 !pt-0">
                            @foreach ($order->items as $item)
                                @if ($item->qty_to_invoice)
                                    <div class="flex justify-between gap-2.5 border-b border-slate-300 py-4 dark:border-gray-800">
                                        <div class="flex gap-2.5">
                                            @if ($item->product?->base_image_url)
                                                <img
                                                    class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded"
                                                    src="{{ $item->product?->base_image_url }}"
                                                />
                                            @else
                                                <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                                    
                                                    <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400"> 
                                                        @lang('admin::app.sales.invoices.create.product-image')
                                                    </p>
                                                </div>
                                            @endif
            
                                            <div class="grid place-content-start gap-1.5">
                                                <p class="text-base font-semibold text-gray-800 dark:text-white">
                                                    {{ $item->name }}
                                                </p>
            
                                                <div class="flex flex-col place-items-start gap-1.5">
                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        @lang('admin::app.sales.invoices.create.amount-per-unit', [
                                                            'amount' => core()->formatBasePrice($item->base_price),
                                                            'qty'    => $item->qty_ordered,
                                                        ])
                                                    </p>
            
                                                    @if (isset($item->additional['attributes']))
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @foreach ($item->additional['attributes'] as $attribute)
                                                                {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                            @endforeach
                                                        </p>
                                                    @endif
            
                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        @lang('admin::app.sales.invoices.create.sku', ['sku' => $item->sku])
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid ltr:text-right rtl:text-left">
                                            <!-- Quantity Details -->
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label class="required !block">
                                                    @lang('admin::app.sales.invoices.create.qty-to-invoiced')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    class="!w-[100px]"
                                                    :id="'invoice[items][' . $item->id . ']'"
                                                    :name="'invoice[items][' . $item->id . ']'"
                                                    rules="required|numeric|min:0" 
                                                    :value="$item->qty_to_invoice"
                                                    :label="trans('admin::app.sales.invoices.create.qty-to-invoiced')"
                                                    :placeholder="trans('admin::app.sales.invoices.create.qty-to-invoiced')"
                                                />
            
                                                <x-admin::form.control-group.error :control-name="'invoice[items][' . $item->id . ']'" />
                                            </x-admin::form.control-group>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <!-- Create Transaction Button -->
                            <x-admin::form.control-group class="!mb-0 flex w-max cursor-pointer select-none items-center gap-2.5 p-1.5">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    name="can_create_transaction"
                                    id="can_create_transaction"
                                    value="1"
                                    for="can_create_transaction"
                                />

                                <label
                                    for="can_create_transaction"
                                    class="cursor-pointer !text-sm !font-semibold !text-gray-600 dark:!text-gray-300"
                                >
                                    @lang('admin::app.sales.invoices.create.create-transaction')
                                </label>
                            </x-admin::form.control-group>
                        </div>
                    </x-slot>
                </x-admin::drawer>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-create-invoices', {
            template: '#v-create-invoices-template',
        });
    </script>
@endPushOnce