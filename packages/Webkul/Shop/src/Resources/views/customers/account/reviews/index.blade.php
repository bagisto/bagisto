<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.reviews.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="reviews" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <!-- Back Button -->
            <a
                class="grid md:hidden"
                href="{{ route('shop.customers.account.index') }}"
            >
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.reviews.title')
            </h2>
        </div>

        <!-- Reviews Vue Component -->
        <v-product-reviews>
            <!-- Reviews Shimmer Effect -->
            <x-shop::shimmer.customers.account.reviews :count="4" />
        </v-product-reviews>

    </div>

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
                    @if (! $reviews->isEmpty())
                        <!-- Review Information -->
                        <div class="mt-14 grid gap-5 max-1060:grid-cols-[1fr] max-md:mt-5">
                            @foreach($reviews as $review)
                                <a
                                    href="{{ route('shop.product_or_category.index', $review->product->url_key) }}"
                                    id="{{ $review->product_id }}"
                                    aria-label="{{ $review->title }}"
                                >
                                    <!-- For Desktop View -->
                                    <div class="flex gap-5 rounded-xl border border-zinc-200 p-6 max-md:hidden max-md:gap-1.5">
                                        {!! view_render_event('bagisto.shop.customers.account.reviews.image.before', ['reviews' => $reviews]) !!}

                                        <x-shop::media.images.lazy
                                            class="h-[146px] max-h-[146px] w-32 min-w-32 max-w-32 rounded-xl"
                                            src="{{ $review->product->base_image_url ?? bagisto_asset('images/small-product-placeholder.webp') }}"
                                            alt="Review Image"                   
                                        />

                                        {!! view_render_event('bagisto.shop.customers.account.reviews.image.after', ['reviews' => $reviews]) !!}

                                        <div class="w-full">
                                            <div class="flex justify-between">
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.title.before', ['reviews' => $reviews]) !!}

                                                <p class="text-xl font-medium">
                                                    {{ $review->title}}
                                                </p>

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.title.after', ['reviews' => $reviews]) !!}
        
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.rating.before', ['reviews' => $reviews]) !!}

                                                <div class="flex items-center gap-0.5">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <span class="icon-star-fill text-3xl {{ $review->rating >= $i ? 'text-amber-500' : 'text-zinc-500' }}"></span>
                                                    @endfor
                                                </div>

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.rating.after', ['reviews' => $reviews]) !!}
                                            </div>
        
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.before', ['reviews' => $reviews]) !!}

                                            <p class="mt-2.5 text-sm font-medium">
                                                {{ $review->created_at }}
                                            </p>
        
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.after', ['reviews' => $reviews]) !!}

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.comment.before', ['reviews' => $reviews]) !!}

                                            <p class="mt-5 text-base text-zinc-500 max-md:mt-2">
                                                {{ $review->comment }}
                                            </p>

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.comment.after', ['reviews' => $reviews]) !!}
                                        </div>
                                       
                                    </div>

                                    <!-- For Mobile View -->
                                    <div class="flex gap-5 rounded-xl border border-zinc-200 p-6 max-md:grid max-md:gap-2.5 max-md:p-4 md:hidden">
                                        <div class="flex gap-2.5">
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.image.before', ['reviews' => $reviews]) !!}
    
                                            <x-shop::media.images.lazy
                                                class="h-[146px] max-h-[146px] w-32 min-w-32 max-w-32 rounded-xl max-md:h-20 max-md:w-20 max-md:min-w-20 max-md:rounded-lg"
                                                src="{{ $review->product->base_image_url ?? bagisto_asset('images/small-product-placeholder.webp') }}"
                                                alt="Review Image"                   
                                            />
    
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.image.after', ['reviews' => $reviews]) !!}

                                            <div class="justify-between">
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.title.before', ['reviews' => $reviews]) !!}

                                                <p class="text-xl font-medium max-md:text-base">
                                                    {{ $review->title}}
                                                </p>

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.title.after', ['reviews' => $reviews]) !!}

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.before', ['reviews' => $reviews]) !!}

                                                <p class="mt-1.5 font-normal text-zinc-500 max-md:mt-0 max-md:text-xs">
                                                    {{ $review->created_at }}
                                                </p>
            
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.after', ['reviews' => $reviews]) !!}
        
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.rating.before', ['reviews' => $reviews]) !!}

                                                <div class="mt-1 flex items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <span class="icon-star-fill text-3xl {{ $review->rating >= $i ? 'text-amber-500' : 'text-zinc-500' }}"></span>
                                                    @endfor
                                                </div>

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.rating.after', ['reviews' => $reviews]) !!}
                                            </div>

                                        </div>

                                        <div>
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.comment.before', ['reviews' => $reviews]) !!}

                                            <p class="text-xs text-zinc-500">
                                                {{ $review->comment }}
                                            </p>

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.comment.after', ['reviews' => $reviews]) !!}
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            <!-- Pagination -->
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <!-- Review Empty Page -->
                        <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                            <img
                                class="max-md:h-[100px] max-md:w-[100px]"
                                src="{{ bagisto_asset('images/review.png') }}"
                                alt="Empty Review"
                                title=""
                            >

                            <p
                                class="text-xl max-md:text-sm"
                                role="heading"
                            >
                                @lang('shop::app.customers.account.reviews.empty-review')
                            </p>
                        </div>
                    @endif
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
