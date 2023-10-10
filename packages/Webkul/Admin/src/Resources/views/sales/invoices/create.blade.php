<v-create-invoices>
    <div
        class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]"
    >
        <span class="icon-sales text-[24px]"></span> 

        @lang('admin::app.sales.invoices.create.invoice')     
    </div>
</v-create-invoices>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-invoices-template">
        <div>
            <div
                class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]"
                    @click="$refs.invoice.open()"
                >
                    <span class="icon-sales text-[24px]"></span> 
            
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
                        <div class="grid gap-[12px]">
                            <div class="flex justify-between items-center">
                                <p class="text-[20px] font-medium dark:text-white">
                                    @lang('admin::app.sales.invoices.create.new-invoice')         
                                </p>
    
                                <button
                                    type="submit"
                                    class="mr-[45px] primary-button"
                                >
                                @lang('admin::app.sales.invoices.create.create-invoice')        
                                </button>
                            </div>
                        </div>
                    </x-slot:header>
    
                    <!-- Drawer Content -->
                    <x-slot:content class="!p-0">
                        <div class="grid">
                            <div class="p-[16px] !pt-0">
                                <div class="grid">
                                    @foreach ($order->items as $item)
                                        <div class="flex gap-[10px] justify-between py-[16px]">
                                            <div class="flex gap-[10px]">
                                                @if ($item->product?->base_image_url)
                                                    <img
                                                        class="w-full h-[60px] max-w-[60px] max-h-[60px] relative rounded-[4px]"
                                                        src="{{ $item->product?->base_image_url }}"
                                                    >
                                                @else
                                                    <div class="w-full h-[60px] max-w-[60px] max-h-[60px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion">
                                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                                        
                                                        <p class="absolute w-full bottom-[5px] text-[6px] text-gray-400 text-center font-semibold"> 
                                                            @lang('admin::app.sales.invoices.create.product-image')
                                                        </p>
                                                    </div>
                                                @endif
                
                                                <div class="grid gap-[6px] place-content-start">
                                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">{{ $item->name }}</p>
                
                                                    <div class="flex flex-col gap-[6px] place-items-start">
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
    
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('admin::app.sales.invoices.create.qty-to-invoiced')
                                                </x-admin::form.control-group.label>
            
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    :name="'invoice[items][' . $item->id . ']'"
                                                    :id="'invoice[items][' . $item->id . ']'"
                                                    :value="$item->qty_to_invoice"
                                                    rules="required|numeric|min:0" 
                                                    class="!w-[100px]"
                                                    label="Qty to invoiced"
                                                    placeholder="Qty to invoiced"
                                                >
                                                </x-admin::form.control-group.control>
            
                                                <x-admin::form.control-group.error
                                                    :control-name="'invoice[items][' . $item->id . ']'"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
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