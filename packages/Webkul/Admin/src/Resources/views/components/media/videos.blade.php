@props([
    'name'           => 'images',
    'allowMultiple'  => false,
    'uploadedVideos' => [],
    'width'          => '210px',
    'height'         => '120px'
])

<v-media-videos
    name="{{ $name }}"
    v-bind:allow-multiple="{{ $allowMultiple ? 'true' : 'false' }}"
    :uploaded-videos='{{ json_encode($uploadedVideos) }}'
    width="{{ $width }}"
    height="{{ $height }}"
    :errors="errors"
    {{ $attributes->get('class') }}
>
</v-media-videos>

@pushOnce('scripts')
    <script type="text/x-template" id="v-media-videos-template">
        <!-- Panel Content -->
        <div class="grid">
            <div class="flex gap-[4px]">
                <!-- Upload Video Button -->
                <label
                    class="grid justify-items-center items-center w-full h-[120px] max-w-[210px] max-h-[120px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] cursor-pointer transition-all hover:border-gray-400 dark:invert dark:mix-blend-exclusion"
                    :for="$.uid + '_videoInput'"
                    v-if="allowMultiple || videos.length == 0"
                >
                    <div class="flex flex-col items-center">
                        <span class="icon-image text-[24px]"></span>

                        <p class="grid text-[14px] text-gray-600 dark:text-gray-300 font-semibold text-center">
                            @lang('admin::app.components.media.videos.add-video-btn')
                            
                            <span class="text-[12px]">
                                @lang('admin::app.components.media.videos.allowed-types')
                            </span>
                        </p>

                        <input
                            type="file"
                            class="hidden"
                            :id="$.uid + '_videoInput'"
                            accept="video/*"
                            :multiple="allowMultiple"
                            :ref="$.uid + '_videoInput'"
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
                        <v-media-video-item
                            :name="name"
                            :index="index"
                            :video="element"
                            :width="width"
                            :height="height"
                            @onRemove="remove($event)"
                        ></v-media-video-item>
                    </template>
                </draggable>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-media-video-item-template">
        <div class="grid justify-items-center h-[120px] max-w-[210px] min-w-[210px] max-h-[120px] relative border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] overflow-hidden transition-all hover:border-gray-400 group">
            <!-- Video Preview -->
            <video
                class="w-[210px] h-[120px] object-cover"
                ref="videoPreview"
                v-if="video.url.length > 0"
            >
                <source :src="video.url" type="video/mp4">
            </video>

            <div class="flex flex-col justify-between invisible w-full p-[11px] bg-white dark:bg-gray-900 absolute top-0 bottom-0 opacity-80 transition-all group-hover:visible">
                <!-- Video Name -->
                <p class="text-[12px] text-gray-600 dark:text-gray-300 font-semibold break-all"></p>

                <!-- Actions -->
                <div class="flex justify-between">
                    <!-- Remove Button -->
                    <span
                        class="icon-delete text-[24px] p-[6px] rounded-[6px] cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800"
                        @click="remove"
                    ></span>

                    <!-- Play Pause Button -->
                    <span
                        class="text-[24px] p-[6px] rounded-[6px] cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800"
                        :class="[isPlaying ? 'icon-pause': 'icon-play']"
                        @click="playPause"
                    ></span>

                    <!-- Edit Button -->
                    <label
                        class="icon-edit text-[24px] p-[6px] rounded-[6px] cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800"
                        :for="$.uid + '_videoInput_' + index"
                    ></label>

                    <input type="hidden" :name="name + '[' + video.id + ']'" v-if="! video.is_new"/>

                    <input
                        type="file"
                        :name="name + '[]'"
                        class="hidden"
                        accept="video/*"
                        :id="$.uid + '_videoInput_' + index"
                        :ref="$.uid + '_videoInput_' + index"
                        @change="edit"
                    />
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-media-videos', {
            template: '#v-media-videos-template',

            props: {
                name: {
                    type: String, 
                    default: 'videos',
                },

                allowMultiple: {
                    type: Boolean,
                    default: false,
                },

                uploadedVideos: {
                    type: Array,
                    default: () => []
                },

                width: {
                    type: String,
                    default: '210px'
                },

                height: {
                    type: String,
                    default: '120px'
                },

                errors: {
                    type: Object,
                    default: () => {}
                }
            },

            data() {
                return {
                    videos: [],
                }
            },

            mounted() {
                this.videos = this.uploadedVideos;
            },

            methods: {
                add() {
                    let videoInput = this.$refs[this.$.uid + '_videoInput'];

                    if (videoInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(videoInput.files).every(file => file.type.includes('video/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('admin::app.components.media.videos.not-allowed-error')"
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

        app.component('v-media-video-item', {
            template: '#v-media-video-item-template',

            props: ['index', 'video', 'name', 'width', 'height'],

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
                    let videoInput = this.$refs[this.$.uid + '_videoInput_' + this.index];

                    if (videoInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(videoInput.files).every(file => file.type.includes('video/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('admin::app.components.media.videos.not-allowed-error')"
                        });

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

                    this.$refs[this.$.uid + '_videoInput_' + this.index].files = dataTransfer.files;
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