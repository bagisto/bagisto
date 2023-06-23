@if ($product->type == 'grouped')
    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}

    <div class="col-12 grouped-product-container">
        @php
            $groupedProducts = $product->grouped_products()->orderBy('sort_order')->get();
        @endphp

        @if ($groupedProducts->count())
            <div class="grid gap-[20px] mt-[30px]">
                @foreach ($groupedProducts as $groupedProduct)
                    @if ($groupedProduct->associated_product->getTypeInstance()->isSaleable())
                        <div class="flex justify-between items-center">
                            <div class="text-[14px] font-medium">
                                <p class="">
                                    @lang('shop::app.products.view.type.grouped.name')
                                </p>

                                <p class="text-[#7D7D7D] mt-[5px]">
                                    {{ $groupedProduct->associated_product->name . ' + ' . core()->currency($groupedProduct->associated_product->getTypeInstance()->getFinalPrice()) }}
                                </p>

                            </div>

                            <x-shop::quantity-changer
                                name="qty[{{$groupedProduct->associated_product_id}}]"
                                :value="$groupedProduct->qty"
                                class="gap-x-[16px] rounded-[12px] py-[15px] px-[26px]"
                                @change="updateItem($event)"
                            >
                            </x-shop::quantity-changer>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        
    </div>

    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}
@endif