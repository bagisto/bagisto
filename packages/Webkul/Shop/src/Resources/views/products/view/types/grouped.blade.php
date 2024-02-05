@if ($product->type == 'grouped')
    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}

    <div class="w-[455px] max-w-full">
        @php
            $groupedProducts = $product->grouped_products()->orderBy('sort_order')->get();
        @endphp

        @if ($groupedProducts->count())
            <div class="grid gap-5 mt-8">
                @foreach ($groupedProducts as $groupedProduct)
                    @if ($groupedProduct->associated_product->getTypeInstance()->isSaleable())
                        <div class="flex gap-5 justify-between items-center">
                            <div class="text-sm font-medium">
                                <p class="">
                                    @lang('shop::app.products.view.type.grouped.name')
                                </p>

                                <p class="text-[#6E6E6E] mt-1.5">
                                    {{ $groupedProduct->associated_product->name . ' + ' . core()->currency($groupedProduct->associated_product->getTypeInstance()->getFinalPrice()) }}
                                </p>

                            </div>

                            <x-shop::quantity-changer
                                name="qty[{{$groupedProduct->associated_product_id}}]"
                                :value="$groupedProduct->qty"
                                class="gap-x-4 py-2.5 px-3 rounded-xl"
                                @change="updateItem($event)"
                            />
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        
    </div>

    {!! view_render_event('bagisto.shop.products.view.grouped_products.before', ['product' => $product]) !!}
@endif