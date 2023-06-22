<v-gallery ref="gallery">
    <x-shop::shimmer.products.gallery></x-shop::shimmer.products.gallery>
</v-gallery>

@pushOnce('scripts')
    <script type="text/x-template" id="v-gallery-template">
        <div class="flex gap-[30px] max-1180:hidden h-max sticky top-[30px]">
            <div class="flex-24 place-content-start h-509 overflow-x-hidden overflow-y-auto flex gap-[30px] max-w-[100px] flex-wrap">
                <img 
                    :class="`rounded-[12px] min-w-[100px] max-h-[100px] ${ hover ? 'cursor-pointer' : '' }`" 
                    v-for='image in mediaContents.images'
                    :src="image.small_image_url" 
                    @mouseover='change(image)'
                />

                <!-- Need to Set Play Button  -->
                <video 
                    class='rounded-[12px] min-w-[100px]'
                    v-for='video in mediaContents.videos'
                    @mouseover='change(video)'
                >
                    <source 
                        :src="video.video_url" 
                        type="video/mp4"
                    />
                </video>
            </div>
            

            <div
                class="max-h-[609px] max-w-[560px]"
                v-show="isMediaLoading"
            >
                <div class="rounded-[12px] min-w-[560px] bg-[#E9E9E9] shimmer min-h-[607px]"></div>
            </div>

            <div
                class="max-h-[609px] max-w-[560px]"
                v-show="! isMediaLoading"
            >
                <img 
                    class="rounded-[12px] min-w-[450px]" 
                    :src="baseFile.path" 
                    v-if='baseFile.type == "image"'
                    @load="onMediaLoad()"
                />

                <div class="rounded-[12px] min-w-[450px]" v-if='baseFile.type == "video"'>
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

        <div class="flex gap-[30px] 1180:hidden overflow-auto scrollbar-hide">
            <img 
                :src="image.large_image_url"
                class="min-w-[450px] max-sm:min-w-full"
                v-for="image in mediaContents.images"
            >
        </div>
    </script>

    <script type="module">
        app.component('v-gallery', {
            template: '#v-gallery-template',

            data() {
                return {
                    isMediaLoading: true,

                    mediaContents: {
                        images: @json(product_image()->getGalleryImages($product)),

                        videos: @json(product_video()->getVideos($product)),
                    },

                    baseFile: {
                        type: '',

                        path: ''
                    },

                    hover: false,
                }
            },

            mounted() {
                if (this.mediaContents.images.length) {
                    this.baseFile.type = 'image';
                    this.baseFile.path = this.mediaContents.images[0].large_image_url;
                } else {
                    this.baseFile.type = this.mediaContents.videos[0].type;
                    this.baseFile.path = this.mediaContents.videos[0].video_url;
                }
            },

            methods: {
                onMediaLoad() {
                    this.isMediaLoading = false;
                },

                change(file) {
                    if (file.type == 'video') {
                        this.baseFile.type = file.type;
                        this.baseFile.path = file.video_url;
                    } else {
                        this.baseFile.type = 'image';
                        this.baseFile.path = file.large_image_url;
                    }
                    
                    this.hover = true;
                }
            }
        })
    </script>
@endpushOnce
