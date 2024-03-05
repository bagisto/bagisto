<!-- Invoice Create Vue Component -->
<v-create-invoices>
    <div
        class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
    >
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
                class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
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
                        <div class="grid gap-3 h-8">
                            <div class="flex justify-between items-center">
                                <p class="text-xl font-medium dark:text-white">
                                    @lang('admin::app.sales.invoices.create.new-invoice')         
                                </p>
    
                                @if (bouncer()->hasPermission('sales.invoices.create'))
                                    <button
                                        type="submit"
                                        class="ltr:mr-11 rtl:ml-11 primary-button"
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
                                    <div class="flex gap-2.5 justify-between py-4 border-b border-slate-300 dark:border-gray-800">
                                        <div class="flex gap-2.5">
                                            @if ($item->product?->base_image_url)
                                                <img
                                                    class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded"
                                                    src="{{ $item->product?->base_image_url }}"
                                                />
                                            @else
                                                <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded dark:invert dark:mix-blend-exclusion">
                                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                                    
                                                    <p class="absolute w-full bottom-1.5 text-[6px] text-gray-400 text-center font-semibold"> 
                                                        @lang('admin::app.sales.invoices.create.product-image')
                                                    </p>
                                                </div>
                                            @endif
            
                                            <div class="grid gap-1.5 place-content-start">
                                                <p class="text-base text-gray-800 dark:text-white font-semibold">
                                                    {{ $item->name }}
                                                </p>
            
                                                <div class="flex flex-col gap-1.5 place-items-start">
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

                                        <!-- Quantity Details -->
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
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
                                @endif
                            @endforeach

                            <!-- Create Transaction Button -->
                            <x-admin::form.control-group class="flex gap-2.5 items-center w-max !mb-0 p-1.5 cursor-pointer select-none">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    name="can_create_transaction"
                                    id="can_create_transaction"
                                    value="1"
                                    for="can_create_transaction"
                                />

                                <label
                                    for="can_create_transaction"
                                    class="!text-sm !font-semibold !text-gray-600 dark:!text-gray-300 cursor-pointer"
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