@php($relatedProducts = $product->related_products()->get())

@if ($relatedProducts->count())
    <div class="attached-products-wrapper">

        <div class="flex justify-between">
			<h3 class="text-[30px] max-sm:text-[25px] font-dmserif">@lang('shop::app.products.related-product-title')</h3>
			<div class="flex justify-between items-center gap-8">
				<span class="bg-[position:-122px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
				<span class="bg-[position:-147px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
			</div>
		</div>

        <div class="flex gap-8 mt-[60px] overflow-auto scrollbar-hide max-sm:mt-[20px]">

            @foreach ($relatedProducts as $related_product)
               
                {{-- <x-shop::products.card :product="$related_product"></x-shop::products.card> --}}

            @endforeach

        </div>

    </div>
@endif