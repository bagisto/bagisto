<!-- Mobile Gallery Loading -->
<div
    class="overflow-hidden 1180:hidden"
    v-if="isMediaLoading"
>
    <div class="shimmer aspect-square max-h-screen w-screen bg-zinc-200"></div>
</div>

<!-- Mobile Gallery -->
<div
    class="relative scrollbar-hide flex w-screen gap-8 overflow-auto max-sm:gap-5 1180:hidden"
    v-else
>

    <v-product-carousel
        :options="[
            ...media.images,
            ...media.videos
        ]"
        @click="isImageZooming = ! isImageZooming"
    >
        <x-shop::shimmer.products.gallery />
    </v-product-carousel>

    <!-- Wishlist Icon -->
    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
    <div
        class="absolute top-4 right-4 z-20 flex h-[42px] w-[42px] cursor-pointer items-center justify-center rounded-full bg-white text-xl shadow-md transition-all hover:opacity-[0.8]"
        role="button"
        aria-label="@lang('shop::app.products.view.add-to-wishlist')"
        tabindex="0"
        :class="$parent.isWishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
        @click="$parent.addToWishlist()"
    ></div>
    @endif

</div>