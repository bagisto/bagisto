{!! view_render_event('bagisto.admin.catalog.product.edit.form.videos.before', ['product' => $product]) !!}

<v-product-videos :errors="errors"></v-product-videos>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.videos.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-videos-template">
        <div class="relative p-[16px] bg-white rounded-[4px] box-shadow">
            <!-- Panel Header -->
            <div class="flex gap-[20px] justify-between mb-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p class="text-[16px] text-gray-800 font-semibold">
                        @lang('admin::app.catalog.products.edit.videos.title')
                    </p>

                    <p class="text-[12px] text-gray-500 font-medium">
                        @lang('admin::app.catalog.products.edit.videos.info', ['size' => core()->getMaxUploadSize()])
                    </p>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="grid">
                <div class="flex gap-[4px]">
                    <!-- Upload Image Button -->
                    <label
                        class="grid justify-items-center items-center w-full h-[120px] max-w-[210px] max-h-[120px] border border-dashed border-gray-300 rounded-[4px] cursor-pointer transition-all hover:border-gray-400"
                        for="videoInput"
                    >
                        <div class="flex flex-col items-center">
                            <span class="icon-image text-[24px]"></span>

                            <p class="grid text-[14px] text-gray-600 font-semibold text-center">
                                @lang('admin::app.catalog.products.edit.videos.add-video-btn')
                                
                                <span class="text-[12px]">
                                    @lang('admin::app.catalog.products.edit.videos.allowed-types')
                                </span>
                            </p>

                            <input
                                type="file"
                                class="hidden"
                                id="videoInput"
                                accept="video/*"
                                multiple="multiple"
                                ref="videoInput"
                                @change="add"
                            />
                        </div>
                    </label>

                    <!-- Uploaded Videos -->
                    <draggable
                        class="flex gap-[4px]"
                        ghost-class="draggable-ghost"
                        v-bind="{animation: 200}"
                        :list="videos"
                        item-key="id"
                    >
                        <template #item="{ element, index }">
                            <v-product-video-item
                                :index="index"
                                :video="element"
                                @onRemove="remove($event)"
                            ></v-product-video-item>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-product-video-item-template">
        <div class="grid justify-items-center h-[120px] max-w-[210px] min-w-[210px] max-h-[120px] relative border border-dashed border-gray-300 rounded-[4px] overflow-hidden transition-all hover:border-gray-400 group">
            <!-- Video Preview -->
            <video
                class="w-[210px] h-[120px] object-cover"
                ref="videoPreview"
                v-if="video.url.length > 0"
            >
                <source :src="video.url" type="video/mp4">
            </video>

            <div class="flex flex-col justify-between invisible w-full p-[11px] bg-white absolute top-0 bottom-0 opacity-80  transition-all group-hover:visible">
                <!-- Video Name -->
                <p class="text-[12px] text-gray-600 font-semibold break-all"></p>

                <!-- Actions -->
                <div class="flex justify-between">
                    <!-- Remove Button -->
                    <span
                        class="icon-delete text-[24px] p-[6px] rounded-[6px] cursor-pointer hover:bg-gray-200"
                        @click="remove"
                    ></span>

                    <!-- Play Pause Button -->
                    <span
                        class="text-[24px] p-[6px] rounded-[6px] cursor-pointer hover:bg-gray-200"
                        :class="[isPlaying ? 'icon-pause': 'icon-play']"
                        @click="playPause"
                    ></span>

                    <!-- Edit Button -->
                    <label
                        class="icon-edit text-[24px] p-[6px] rounded-[6px] cursor-pointer hover:bg-gray-200"
                        :for="'videoInput_' + index"
                    ></label>

                    <input type="hidden" :name="'videos[files][' + video.id + ']'" v-if="! video.is_new"/>

                    <input type="hidden" :name="'videos[positions][' + video.id + ']'"/>

                    <input
                        type="file"
                        name="videos[files][]"
                        class="hidden"
                        accept="video/*"
                        :id="'videoInput_' + index"
                        :ref="'videoInput_' + index"
                        @change="edit"
                    />
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-videos', {
            template: '#v-product-videos-template',

            props: ['errors'],

            data() {
                return {
                    videos: @json($product->videos),
                }
            },

            methods: {
                add() {
                    let videoInput = this.$refs.videoInput;

                    if (videoInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(videoInput.files).every(file => file.type.includes('video/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "{{ trans('admin::app.catalog.products.edit.videos.not-allowed-error') }}"
                        });

                        return;
                    }

                    videoInput.files.forEach((file, index) => {
                        this.videos.push({
                            id: 'video_' + this.videos.length,
                            url: '',
                            file: file
                        });
                    });
                },

                remove(video) {
                    let index = this.videos.indexOf(video);

                    this.videos.splice(index, 1);
                },
            }
        });

        app.component('v-product-video-item', {
            template: '#v-product-video-item-template',

            props: ['index', 'video'],

            data() {
                return {
                    isPlaying: false
                }
            },

            mounted() {
                if (this.video.file instanceof File) {
                    this.setFile(this.video.file);

                    this.readFile(this.video.file);
                }
            },

            methods: {
                edit() {
                    let videoInput = this.$refs['videoInput_' + this.index];

                    if (videoInput.files == undefined) {
                        return;
                    }

                    this.setFile(videoInput.files[0]);

                    this.readFile(videoInput.files[0]);
                },

                remove() {
                    this.$emit('onRemove', this.video)
                },

                setFile(file) {
                    this.video.is_new = 1;

                    const dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);

                    this.$refs['videoInput_' + this.index].files = dataTransfer.files;
                },

                readFile(file) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.video.url = e.target.result;
                    }

                    reader.readAsDataURL(file);
                },

                playPause() {
                    let videoPreview = this.$refs.videoPreview;

                    if (videoPreview.paused == true) {
                        this.isPlaying = true;

                        videoPreview.play();
                    } else {
                        this.isPlaying = false;

                        videoPreview.pause();
                    }
                }
            }
        });
    </script>
@endPushOnce