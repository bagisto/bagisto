<x-shop::layouts.account>
    <div class="flex-auto">
        <div class="max-lg:hidden">
            <div class="flex gap-x-[4px] items-center mb-[10px]">
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/']">
                    @lang('shop::app.customers.account.title')
                </p>
                
                <p class="flex items-center gap-x-[4px] text-[#7D7D7D] text-[16px] after:content-['/'] after:last:hidden">
                    @lang('shop::app.customers.account.reviews.title')
                </p>
            </div>
            
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.reviews.title')
            </h2>

            @if (! $reviews->isEmpty())
                <div class="grid mt-[60px] gap-[20px] max-1060:grid-cols-[1fr]">
                    @foreach($reviews as $review)
                        <a href="#" id="{{ $review->product_id }}">
                            <div class="flex gap-[20px] border border-[#e5e5e5] rounded-[12px] p-[25px] max-sm:flex-wrap">
                                @php $image = product_image()->getProductBaseImage($review->product); @endphp

                                <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
                                    <img class="rounded-[12px]" src="{{ $image['small_image_url'] }}" title="" alt="">
                                </div>

                                <div class="">
                                    <div class="flex justify-between">
                                        <p class="text-[20px] font-medium max-sm:text-[16px]"> {{ $review->title}} </p>

                                        <div class="flex gap-[10px] items-center">
                                            {{-- For Active stars --}}
                                            @if ($review->rating)
                                                @for($i = 1; $i <= $review->rating; $i++)
                                                    <span class="bg-[position:-151px_-229px] bs-main-sprite w-[14px] h-[14px]"></span>
                                                @endfor
                                            @endif
                                        
                                            @php $remaining_stars = 5 - $review->rating; @endphp
                                        
                                            {{-- For Inactive stars --}}
                                            @if ($remaining_stars)
                                                @for($i = 1; $i <= $remaining_stars; $i++)
                                                    <span class="bg-[position:-151px_-253px] bs-main-sprite w-[14px] h-[14px]"></span>
                                                @endfor
                                            @endif
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
