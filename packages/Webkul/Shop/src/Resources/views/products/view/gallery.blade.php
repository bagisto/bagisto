<v-product-gallery ref="gallery">
    <x-shop::shimmer.products.gallery/>
</v-product-gallery>

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-gallery-template">
        <div class="flex gap-[30px] h-max sticky top-[30px] max-1180:hidden">
            <!-- Product Image Slider -->
            <div class="flex-24 justify-center place-content-start h-509 overflow-x-hidden overflow-y-auto flex gap-[10px] max-w-[100px] flex-wrap">
                <span
                    class="icon-arrow-up text-[24px] cursor-pointer"
                    role="button"
                    aria-label="@lang('shop::app.components.products.carousel.previous')"
                    tabindex="0"
                    @click="swipeDown"
                    v-if= "lengthOfMedia"
                >
                </span>

                <div
                    ref="swiperContainer"
                    class="flex flex-col max-h-[540px] gap-[10px] [&>*]:flex-[0] overflow-auto scroll-smooth scrollbar-hide"
                >

                    <img 
                        :class="`min-w-[100px] max-h-[100px] rounded-[12px] border transparent cursor-pointer ${activeIndex === index ? 'border border-navyBlue pointer-events-none' : 'border-white'}`"
                        v-for="(image, index) in media.images"
                        :src="image.small_image_url"
                        alt="{{ $product->name }}"
                        width="100"
                        height="100"
                        @click="change(image, index)"
                    />

                    <!-- Need to Set Play Button  -->
                    <video 
                        class="min-w-[100px] rounded-[12px]"
                        v-for="(video, index) in media.videos"
                        @click="change(video, index)"
                    >
                        <source 
                            :src="video.video_url"
                            type="video/mp4"
                        />
                    </video>
                </div>

                <span
                    class="icon-arrow-down text-[24px] cursor-pointer"
                    role="button"
                    aria-label="@lang('shop::app.components.products.carousel.previous')"
                    tabindex="0"
                    @click="swipeTop"
                    v-if= "lengthOfMedia"
                >
                </span>
            </div>
            
            <!-- Media shimmer Effect -->
            <div
                class="max-w-[560px] max-h-[609px]"
                v-show="isMediaLoading"
            >
                <div class="min-w-[560px] min-h-[607px] bg-[#E9E9E9] rounded-[12px] shimmer"></div>
            </div>

            <div
                class="max-w-[560px] max-h-[609px]"
                v-show="! isMediaLoading"
            >
                <img 
                    class="min-w-[450px] rounded-[12px]" 
                    :src="baseFile.path" 
                    v-if="baseFile.type == 'image'"
                    alt="{{ $product->name }}"
                    width="560"
                    height="609"
                    @load="onMediaLoad()"
                />

                <div
                    class="min-w-[450px] rounded-[12px]"
                    v-if="baseFile.type == 'video'"
                >
                    <video  
                        controls                             
                        width='475'
                        @load="onMediaLoad()"
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
        <div class="flex gap-[30px] 1180:hidden overflow-auto scrollbar-hide">
            <x-shop::media.images.lazy
                ::src="image.large_image_url"
                class="min-w-[450px] max-sm:min-w-full w-[490px]" 
                v-for="image in media.images"
            >
            </x-shop::media.images.lazy>
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
                        if (JSON.stringify(newImages) !== JSON.stringify(oldImages)) {
                            this.baseFile.path = newImages[this.activeIndex].large_image_url;
                        }
                    },
                },
            },
    
            mounted() {
                if (this.media.images.length) {
                    this.baseFile.type = 'image';
                    this.baseFile.path = this.media.images[0].large_image_url;
                } else if (this.media.videos.length) {
                    this.baseFile.type = 'video';
                    this.baseFile.path = this.media.videos[0].video_url;
                }
            },

            computed: {
                lengthOfMedia() {
                    return [...this.media.images, ...this.media.videos].length > 5;
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
            }
        })
    </script>
@endpushOnce
