<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.catalog.products.edit.title')
    </x-slot:title>

    <x-admin::form
        v-slot="{ meta, errors, handleSubmit }"
        as="div"
    >
        <form @submit="handleSubmit($event, create)">
            <div class="grid gap-[10px]">
                <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                    <div class="grid gap-[6px]">
                        <p class="text-[20px] text-gray-800 font-bold leading-[24px]">
                            @lang('admin::app.catalog.products.edit.title')
                        </p>
                    </div>
                    
                    <div class="flex gap-x-[10px] items-center">
                        <span class="text-gray-600 leading-[24px]"> Cancel</span>
                        <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                            Save Product</div>
                    </div>
                </div>
            </div>

            <!-- Filter row -->
            <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
                <div class="flex gap-x-[4px] items-center">
                    <div class="">
                        <div class="inline-flex gap-x-[8px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-store text-[24px] "></span>Default Store<span class="icon-sort-down text-[24px]"></span>
                        </div>
                        <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow">
                        </div>
                    </div>
                    <div class="">
                        <div class="inline-flex gap-x-[4px] items-center justify-between text-gray-600 font-semibold px-[4px] py-[6px] text-center w-full max-w-max cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-gratext-gray-600">
                            <span class="icon-language text-[24px] "></span> English <span class="icon-sort-down text-[24px]"></span>
                        </div>
                        <div class="hidden w-full z-10 bg-white divide-y divide-gray-100 rounded shadow">
                        </div>
                    </div>
                </div>
            </div>

            <!-- body content -->
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                @foreach ($product->attribute_family->attribute_groups->groupBy('column') as $column => $groups)
                    <div
                        @if ($column == 1) class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto" @endif
                        @if ($column == 2) class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full" @endif
                    >
                        @foreach ($groups as $group)
                            @php
                                $customAttributes = $product->getEditableAttributes($group);
                            @endphp

                            @if (count($customAttributes))
                                <div class="p-[16px] bg-white rounded-[4px] box-shadow">
                                    <p class="text-[16px] text-gray-800 font-semibold mb-[16px]">
                                        {{ $group->name }}
                                    </p>

                                    @foreach ($customAttributes as $attribute)
                                        @if (in_array($attribute->code, ['special_price_from', 'length']))
                                            <div class="flex gap-[16px]">
                                        @endif

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                {{ $attribute->admin_name }}
                                            </x-admin::form.control-group.label>

                                            @include ('admin::catalog.products.edit.controls', [
                                                'attribute' => $attribute,
                                                'product'   => $product,
                                            ])
                
                                            <x-admin::form.control-group.error :control-name="$attribute->code"></x-admin::form.control-group.error>
                                        </x-admin::form.control-group>


                                        @if (in_array($attribute->code, ['special_price_to', 'height']))
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </form>
    </x-admin::form>
        
</x-admin::layouts>