<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.reviews.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="reviews" />
    @endSection

    <!-- Reviews Vue Component -->
    <v-product-reviews>
        <!-- Reviews Shimmer Effect -->
        <x-shop::shimmer.customers.account.reviews :count="4" />
    </v-product-reviews>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-reviews-template"
        >
            <div>
                <!-- Reviews Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.reviews :count="4" />
                </template>

                {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

                <!-- Reviews Information -->
                <template v-else>
                    <div class="flex-auto">
                        <div class="max-md:max-w-full">
                            <h2 class="text-2xl font-medium">
                                @lang('shop::app.customers.account.reviews.title')
                            </h2>
                
                            @if (! $reviews->isEmpty())
                                <!-- Review Information -->
                                <div class="grid gap-5 mt-14 max-1060:grid-cols-[1fr]">
                                    @foreach($reviews as $review)
                                        <a
                                            href="{{ route('shop.product_or_category.index', $review->product->url_key) }}"
                                            id="{{ $review->product_id }}"
                                        >
                                            <div class="flex gap-5 p-6 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap">
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.image.before', ['reviews' => $reviews]) !!}

                                                <x-shop::media.images.lazy
                                                    class="max-w-[128px] max-h-[146px] min-w-[128px] w-[128px] h-[146px] rounded-xl" 
                                                    src="{{ $review->product->base_image_url ?? bagisto_asset('images/small-product-placeholder.webp') }}"
                                                    alt="Review Image"                   
                                                />

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.image.after', ['reviews' => $reviews]) !!}
                
                                                <div class="w-full">
                                                    <div class="flex justify-between">
                                                        {!! view_render_event('bagisto.shop.customers.account.reviews.title.before', ['reviews' => $reviews]) !!}

                                                        <p class="text-xl font-medium max-sm:text-base">
                                                            {{ $review->title}}
                                                        </p>

                                                        {!! view_render_event('bagisto.shop.customers.account.reviews.title.after', ['reviews' => $reviews]) !!}
                
                                                        {!! view_render_event('bagisto.shop.customers.account.reviews.rating.before', ['reviews' => $reviews]) !!}

                                                        <div class="flex gap-0.5 items-center">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <span class="icon-star-fill text-2xl {{ $review->rating >= $i ? 'text-[#ffb600]' : 'text-[#6E6E6E]' }}"></span>
                                                            @endfor
                                                        </div>

                                                        {!! view_render_event('bagisto.shop.customers.account.reviews.rating.after', ['reviews' => $reviews]) !!}
                                                    </div>
                
                                                    {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.before', ['reviews' => $reviews]) !!}

                                                    <p class="mt-2.5 text-sm font-medium max-sm:text-xs">
                                                        {{ $review->created_at }}
                                                    </p>
                
                                                    {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.after', ['reviews' => $reviews]) !!}

                                                    {!! view_render_event('bagisto.shop.customers.account.reviews.comment.before', ['reviews' => $reviews]) !!}

                                                    <p class="mt-5 text-base text-[#6E6E6E] max-sm:text-xs">
                                                        {{ $review->comment }}
                                                    </p>

                                                    {!! view_render_event('bagisto.shop.customers.account.reviews.comment.after', ['reviews' => $reviews]) !!}
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <!-- Review Empty Page -->
                                <div class="grid items-center justify-items-center place-content-center w-full m-auto h-[476px] text-center">
                                    <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">
                
                                    <p class="text-xl">
                                        @lang('shop::app.customers.account.reviews.empty-review')
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </template>

                {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}

            </div>
        </script>

        <script type="module">
            app.component("v-product-reviews", {
                template: '#v-product-reviews-template',

                data() {
                    return {
                        isLoading: true,
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('shop.customers.account.reviews.index') }}")
                            .then(response => {
                                this.isLoading = false;
                            })
                            .catch(error => {});
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
