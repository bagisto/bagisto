<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.reviews.title')
    </x-slot>

    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="reviews"></x-shop::breadcrumbs>
    @endSection

    <div class="flex-auto">
        <div class="max-md:max-w-full">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.reviews.title')
            </h2>

            @if (! $reviews->isEmpty())
                {{-- Review Information --}}
                <div class="grid gap-[20px] mt-[60px] max-1060:grid-cols-[1fr]">
                    @foreach($reviews as $review)
                        <a
                            href="{{ route('shop.product_or_category.index', $review->product->url_key) }}"
                            id="{{ $review->product_id }}"
                        >
                            <div class="flex gap-[20px] p-[25px] border border-[#e5e5e5] rounded-[12px] max-sm:flex-wrap">
                                @php $image = product_image()->getGalleryImages($review);@endphp

                                <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
                                    <img 
                                        src="{{ $image[0]['small_image_url'] ?? bagisto_asset('images/small-product-placeholder.png') }}" 
                                        class="rounded-[12px]" 
                                    >
                                </div>

                                <div class="w-full">
                                    <div class="flex justify-between">
                                        <p class="text-[20px] font-medium max-sm:text-[16px]">
                                            {{ $review->title}}
                                        </p>

                                        <div class="flex gap-[10px] items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="icon-star-fill text-[24px] {{ $review->rating >= $i ? 'text-[#ffb600]' : 'text-[#7d7d7d]' }}"></span>
                                            @endfor
                                        </div>
                                    </div>

                                    <p class="mt-[10px] text-[14px] font-medium max-sm:text-[12px]">
                                        {{ $review->created_at }}
                                    </p>

                                    <p class="mt-[20px] text-[16px] text-[#7D7D7D] max-sm:text-[12px]">
                                        {{ $review->comment }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Review Empty Page --}}
                <div class="grid items-center justify-items-center place-content-center w-[100%] m-auto h-[476px] text-center">
                    <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                    <p class="text-[20px]">
                        @lang('shop::app.customers.account.reviews.empty-review')
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-shop::layouts.account>
