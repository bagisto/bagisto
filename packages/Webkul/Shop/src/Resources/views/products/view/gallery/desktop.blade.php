<!-- For large screens greater than 1180px. -->
<div class="sticky top-20 flex h-max gap-8 max-1180:hidden">
    <!-- Product Image and Videos Slider -->
    <div class="flex-24 h-509 flex min-w-[100px] max-w-[100px] flex-wrap place-content-start justify-center gap-2.5 overflow-y-auto overflow-x-hidden">
        <!-- Arrow Up -->
        <span
            class="icon-arrow-up cursor-pointer text-2xl"
            role="button"
            aria-label="@lang('shop::app.components.products.carousel.previous')"
            tabindex="0"
            @click="swipeDown"
            v-if="lengthOfMedia"
        >
        </span>

        <!-- Swiper Container -->
        <div
            ref="swiperContainer"
            class="flex flex-col max-h-[540px] gap-2.5 [&>*]:flex-[0] overflow-auto scroll-smooth scrollbar-hide"
        >
            <template v-for="(media, index) in [...media.images, ...media.videos]">
                <video
                    v-if="media.type == 'videos'"
                    :class="`transparent max-h-[100px] min-w-[100px] cursor-pointer rounded-xl border ${isActiveMedia(index) ? 'pointer-events-none border-navyBlue' : 'border-white'}`"
                    @click="change(media, index)"
                    alt="{{ $product->name }}"
                    tabindex="0"
                >
                    <source
                        :src="media.video_url"
                        type="video/mp4"
                    />
                </video>

                <img
                    v-else
                    :class="`transparent max-h-[100px] min-w-[100px] cursor-pointer rounded-xl border ${isActiveMedia(index) ? 'pointer-events-none border border-navyBlue' : 'border-white'}`"
                    :src="media.small_image_url"
                    alt="{{ $product->name }}"
                    width="100"
                    height="100"
                    tabindex="0"
                    @click="change(media, index)"
                />
            </template>
        </div>

        <!-- Arrow Down -->
        <span
            class="icon-arrow-down cursor-pointer text-2xl"
            v-if= "lengthOfMedia"
            role="button"
            aria-label="@lang('shop::app.components.products.carousel.previous')"
            tabindex="0"
            @click="swipeTop"
        >
        </span>
    </div>

    <!-- Product Base Image and Video with Shimmer-->
    <div
        class="max-h-[610px] max-w-[560px]"
        v-show="isMediaLoading"
    >
        <div class="shimmer min-h-[607px] min-w-[560px] rounded-xl bg-zinc-200"></div>
    </div>

    <div
        class="max-h-[610px] max-w-[560px]"
        v-show="! isMediaLoading"
    >
        <img
            class="min-w-[450px] cursor-pointer rounded-xl"
            :src="baseFile.path"
            v-if="baseFile.type == 'image'"
            alt="{{ $product->name }}"
            width="560"
            height="610"
            tabindex="0"
            @click="isImageZooming = !isImageZooming"
            @load="onMediaLoad()"
            fetchpriority="high"
        />

        <div
            class="min-w-[450px] cursor-pointer rounded-xl"
            tabindex="0"
            v-if="baseFile.type == 'video'"
        >
            <video
                controls
                width="475"
                alt="{{ $product->name }}"
                @click="isImageZooming = !isImageZooming"
                @loadeddata="onMediaLoad()"
                :key="baseFile.path"
            >
                <source
                    :src="baseFile.path"
                    type="video/mp4"
                />
            </video>
        </div>
    </div>
</div>
