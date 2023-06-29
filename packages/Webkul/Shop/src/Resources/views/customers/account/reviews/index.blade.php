<x-shop::layouts.account>
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="reviews"></x-shop::breadcrumbs>
    @endSection

    <div class="flex-auto">
        <div class="max-lg:hidden">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.reviews.title')
            </h2>

            @if (! $reviews->isEmpty())
                <div class="grid mt-[60px] gap-[20px] max-1060:grid-cols-[1fr]">
                    @foreach($reviews as $review)
                        <a
                            href="{{ route('shop.productOrCategory.index', $review->product->url_key) }}"
                            id="{{ $review->product_id }}"
                        >
                            <div class="flex gap-[20px] border border-[#e5e5e5] rounded-[12px] p-[25px] max-sm:flex-wrap">
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
                                            <x-shop::products.star-rating 
                                                ::name="{{ json_encode($review->name) }}" 
                                                ::value="{{ json_encode($review->rating) }}"
                                            >
                                            </x-shop::products.star-rating>
                                        </div>
                                    </div>

                                    <p class="text-[14px] font-medium mt-[10px] max-sm:text-[12px]">
                                        {{ $review->created_at }}
                                    </p>

                                    <p class="text-[16px] text-[#7D7D7D] mt-[20px] max-sm:text-[12px]">
                                        {{ $review->comment }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="grid items-center justify-items-center w-max m-auto h-[476px] place-content-center">
                    <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                    <p class="text-[20px]">
                        @lang('shop::app.customers.account.reviews.empty-review')
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-shop::layouts.account>
