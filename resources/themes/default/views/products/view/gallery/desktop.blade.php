<!-- Simple Product Gallery -->
<div class="sticky top-20 flex gap-6 max-1180:hidden">

    <!-- Side Images -->
    <div class="flex flex-col gap-3">

        <img
            v-for="(image, index) in media.images"
            :key="index"
            :src="image.small_image_url"
            :class="[
                'w-[90px] h-[90px] object-cover rounded-lg cursor-pointer border',
                baseFile.path === image.large_image_url ? 'border-black' : 'border-gray-200'
            ]"
            @click="baseFile = { type: 'image', path: image.large_image_url }"
        >

    </div>


    <!-- Main Image + Wishlist -->
    <div class="relative max-w-[560px]">

        <!-- Main Image -->
        <img
            :src="baseFile.path || media.images[0]?.large_image_url"
            class="w-[560px] rounded-xl"
            alt="{{ $product->name }}"
        >

        <!-- Wishlist Icon -->
        @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
        <div
            class="absolute top-4 right-4 flex h-[42px] w-[42px] cursor-pointer items-center justify-center rounded-full bg-white text-xl shadow-md transition-all hover:opacity-[0.8]"
            role="button"
            aria-label="@lang('shop::app.products.view.add-to-wishlist')"
            tabindex="0"
            :class="$parent.isWishlist ? 'icon-heart-fill text-red-600' : 'icon-heart'"
            @click="$parent.addToWishlist()"
        ></div>
        @endif

    </div>

</div>