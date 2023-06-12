<v-gallery ref="gallery"></v-gallery>

@pushOnce('scripts')
    <script type="text/x-template" id="v-gallery-template">
        <div class="flex gap-[30px] max-1180:hidden h-max sticky top-[30px]">
            <div class="flex-24 h-509 overflow-x-hidden overflow-y-auto flex gap-[30px] max-w-[100px] flex-wrap">
                <img 
                    :class="`rounded-[12px] min-w-[100px] min-h-[100px] ${ hover ? 'cursor-pointer' : '' }`" 
                    v-for='image in mediaContents.images'
                    :src="image.small_image_url" 
                    alt="" 
                    title="" 
                    @mouseover='change(image)'
                >

                <!-- Need to Set Play Button  -->
                <video 
                    class='rounded-[12px] min-w-[100px]'
                    v-for='video in mediaContents.videos'
                    @mouseover='change(video)'
                >
                    <source 
                        :src="video.video_url" 
                        type="video/mp4"
                    >
                </video>
            </div>
            <div class="max-h-[609px] max-w-[560px]">
                <img 
                    class="rounded-[12px] min-w-[450px]" 
                    :src="baseFile.path" 
                    alt="" 
                    title=""
                    v-if='baseFile.type == "image"'
                >

                <div class="rounded-[12px] min-w-[450px]" v-if='baseFile.type == "video"'>
                    <video  
                        controls                             
                        width='475'
                    >
                        <source 
                            :src="baseFile.path" 
                            type="video/mp4"
                        >
                    </video>    
                </div>
                
            </div>
        </div>

        <div class="flex gap-[30px] 1180:hidden overflow-auto scrollbar-hide">
            <img 
                v-for='image in mediaContents.images'
                class="min-w-[450px] max-sm:min-w-full" 
                :src="image.large_image_url" 
            >
        </div>
    </script>

    <script type="module">
        app.component('v-gallery', {
            template: '#v-gallery-template',

            data() {
                return {
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