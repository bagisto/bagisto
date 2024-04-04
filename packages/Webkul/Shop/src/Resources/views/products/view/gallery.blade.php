<v-product-gallery ref="gallery">
    <x-shop::shimmer.products.gallery />
</v-product-gallery>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-gallery-template"
    >
        <div>
            <div class="flex gap-8 h-max sticky top-20 max-1180:hidden">
                <!-- Product Image Slider -->
                <div class="flex-24 justify-center place-content-start h-509 overflow-x-hidden overflow-y-auto flex gap-2.5 max-w-[100px] min-w-[100px] flex-wrap">
                    <span
                        class="icon-arrow-up text-2xl cursor-pointer"
                        role="button"
                        aria-label="@lang('shop::app.components.products.carousel.previous')"
                        tabindex="0"
                        @click="swipeDown"
                        v-if="lengthOfMedia"
                    >
                    </span>

                    <div
                        ref="swiperContainer"
                        class="flex flex-col max-h-[540px] gap-2.5 [&>*]:flex-[0] overflow-auto scroll-smooth scrollbar-hide"
                    >
                        <img
                            :class="`min-w-[100px] max-h-[100px] rounded-xl border transparent cursor-pointer ${activeIndex === `image_${index}` ? 'border border-navyBlue pointer-events-none' : 'border-white'}`"
                            v-for="(image, index) in media.images"
                            :src="image.small_image_url"
                            alt="{{ $product->name }}"
                            width="100"
                            height="100"
                            @click="change(image, `image_${index}`)"
                        />

                        <!-- Need to Set Play Button  -->
                        <video
                            :class="`min-w-[100px] max-h-[100px] rounded-xl border transparent cursor-pointer ${activeIndex === `video_${index}` ? 'border border-navyBlue pointer-events-none' : 'border-white'}`"
                            v-for="(video, index) in media.videos"
                            @click="change(video, `video_${index}`)"
                        >
                            <source
                                :src="video.video_url"
                                type="video/mp4"
                            />
                        </video>
                    </div>

                    <span
                        class="icon-arrow-down text-2xl cursor-pointer"
                        v-if= "lengthOfMedia"
                        role="button"
                        aria-label="@lang('shop::app.components.products.carousel.previous')"
                        tabindex="0"
                        @click="swipeTop"
                    >
                    </span>
                </div>

                <!-- Media shimmer Effect -->
                <div
                    class="max-w-[560px] max-h-[610px]"
                    v-show="isMediaLoading"
                >
                    <div class="min-w-[560px] min-h-[607px] bg-[#E9E9E9] rounded-xl shimmer"></div>
                </div>

                <div
                    class="max-w-[560px] max-h-[610px]"
                    v-show="! isMediaLoading"
                >
                    <img
                        class="min-w-[450px] rounded-xl cursor-pointer"
                        :src="baseFile.path"
                        v-if="baseFile.type == 'image'"
                        alt="{{ $product->name }}"
                        width="560"
                        height="610"
                        @click="$emitter.emit('v-show-images-zoomer', activeIndex)"
                        @load="onMediaLoad()"
                    />

                    <div
                        class="min-w-[450px] rounded-xl"
                        v-if="baseFile.type == 'video'"
                    >
                        <video
                            controls
                            width="475"
                            @loadeddata="onMediaLoad()"
                        >
                            <source
                                :src="baseFile.path"
                                type="video/mp4"
                            />
                        </video>
                    </div>
                </div>
            </div>

            <!-- Product slider Image with shimmer -->
            <div class="flex gap-8 1180:hidden overflow-auto scrollbar-hide">
                <x-shop::media.images.lazy
                    ::src="image.large_image_url"
                    class="min-w-[450px] max-sm:min-w-full w-[490px]"
                    v-for="(image, index) in media.images"
                    @click="$emitter.emit('v-show-images-zoomer', `image_${index}`)"
                />
            </div>

            <!-- Gallery Images Zoomer -->
            <x-shop::products.gallery-zoomer ::images="media.images"></x-shop::products.gallery-zoomer>
        </div>
    </script>

    <script type="module">
        app.component('v-product-gallery', {
            template: '#v-product-gallery-template',

            data() {
                return {
                    isMediaLoading: true,

                    media: {
                        images: @json(product_image()->getGalleryImages($product)),

                        videos: @json(product_video()->getVideos($product)),
                    },

                    baseFile: {
                        type: '',

                        path: ''
                    },

                    activeIndex: 0,

                    containerOffset: 110,
                }
            },

            watch: {
                'media.images': {
                    deep: true,

                    handler(newImages, oldImages) {
                        let selectedImage = newImages?.[this.activeIndex.split('_').pop()];

                        if (JSON.stringify(newImages) !== JSON.stringify(oldImages) && selectedImage?.large_image_url) {
                            this.baseFile.path = selectedImage.large_image_url;
                        }
                    },
                },
            },

            mounted() {
                if (this.media.images.length) {
                    this.activeIndex = 'image_0';

                    this.baseFile.type = 'image';

                    this.baseFile.path = this.media.images[0].large_image_url;
                } else if (this.media.videos.length) {
                    this.activeIndex = 'video_0';

                    this.baseFile.type = 'video';

                    this.baseFile.path = this.media.videos[0].video_url;
                }
            },

            computed: {
                lengthOfMedia() {
                    if (this.media.images.length) {
                        return [...this.media.images, ...this.media.videos].length > 5;
                    }
                }
            },

            methods: {
                onMediaLoad() {
                    this.isMediaLoading = false;
                },

                change(file, index) {
                    this.isMediaLoading = true;

                    if (file.type == 'videos') {
                        this.baseFile.type = 'video';

                        this.baseFile.path = file.video_url;

                        this.onMediaLoad();
                    } else {
                        this.baseFile.type = 'image';

                        this.baseFile.path = file.large_image_url;
                    }

                    if (index > this.activeIndex) {
                        this.swipeDown();
                    } else if (index < this.activeIndex) {
                        this.swipeTop();
                    }

                    this.activeIndex = index;
                },

                swipeTop() {
                    const container = this.$refs.swiperContainer;

                    container.scrollTop -= this.containerOffset;
                },

                swipeDown() {
                    const container = this.$refs.swiperContainer;

                    container.scrollTop += this.containerOffset;
                },
            },
        });
    </script>
@endpushOnce