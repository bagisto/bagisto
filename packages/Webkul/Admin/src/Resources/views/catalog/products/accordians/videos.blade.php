{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.videos.before', ['product' => $product]) !!}

<accordian title="{{ __('admin::app.catalog.products.videos') }}" :active="false">
    <div slot="body">
        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.videos.controls.before', ['product' => $product]) !!}

        <div class="control-group {{ $errors->has('videos.files.*') ? 'has-error' : '' }}">
            <label>{{ __('admin::app.catalog.products.video') }}</label>

            <product-video></product-video>

            <span
                class="control-error"
                v-text="'{{ $errors->first('videos.files.*') }}'">
            </span>
            
            <span class="control-info mt-10">{{ __('admin::app.catalog.products.video-drop') }}</span>

            <span class="control-info mt-10">{{ __('admin::app.catalog.products.video-size', ['size' => core()->getMaxUploadSize()]) }}</span>
        </div>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.videos.controls.after', ['product' => $product]) !!}
    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.videos.after', ['product' => $product]) !!}

@push('scripts')
    <script type="text/x-template" id="product-video-template">
        <div>
            <div class="image-wrapper">
                <draggable v-model="items" group="people" @end="onDragEnd">
                    <product-video-item
                        v-for='(video, index) in items'
                        :key='video.id'
                        :video="video"
                        @onRemoveVideo="removeVideo($event)"
                        @onVideoSelected="videoSelected($event)">
                    </product-video-item>
                </draggable>
            </div>

            <label class="btn btn-lg btn-primary" style="display: table; width: auto" @click="createFileType">
                {{ __('admin::app.catalog.products.add-video-btn-title') }}
            </label>
        </div>
    </script>

    <script type="text/x-template" id="product-video-item-template">

        <label class="image-item" v-bind:class="{ 'has-image': videoData.length > 0, 'dropzone': isDragging }">
            <input
                type="hidden"
                :name="'videos[files][' + video.id + ']'"
                v-if="! newVideo"/>

            <input
                type="hidden"
                :name="'videos[positions][' + video.id + ']'"/>

            <input
                :id="_uid"
                ref="videoInput"
                type="file"
                name="videos[files][]"
                accept="video/*"
                multiple="multiple"
                v-validate="'mimes:video/*'"
                class="drag-image"
                @change="addVideoView($event)"
                @drop="isDragging = false"
                @dragleave="isDragging = false" 
                @dragenter="isDragging = true" />

            <video class="preview" v-if="videoData.length > 0" width="200" height="160" controls>
                <source :src="videoData"  type="video/mp4">

                {{ __('admin::app.catalog.products.not-support-video') }}
            </video>

            <label class="remove-image" @click="removeVideo()">
                {{ __('admin::app.catalog.products.remove-video-btn-title') }}
            </label>
        </label>
    </script>

    <script>
        Vue.component('product-video', {
            template: '#product-video-template',

            data: function() {
                return {
                    videos: @json($product->videos),
                    videoCount: 0,
                    items: [],
                }
            },

            computed: {
                finalInputName: function() {
                    return 'videos[' + this.video.id + ']';
                }
            },

            created: function() {
                this.videos.forEach((video) => {
                    this.items.push(video);

                    this.videoCount++;
                });
            },

            methods: {
                createFileType: function() {

                    this.videoCount++;

                    this.items.push({'id': 'video_' + this.videoCount});
                },

                removeVideo: function(video) {
                    let index = this.items.indexOf(video);

                    Vue.delete(this.items, index);
                },

                videoSelected: function(event) {

                    Array.from(event.files).forEach((video, index) => {
                        if (index) {
                            this.videoCount++;

                            this.items.push({'id': 'video_' + this.videoCount, file: video});
                        }
                    });
                },

                onDragEnd: function() {
                    this.items = this.items.map((item, index) => {
                        item.position = index;

                        return item;
                    });
                },
            }
        });

        Vue.component('product-video-item', {
            template: '#product-video-item-template',

            props: {
                video: {
                    type: Object,
                    required: false,
                    default: null,
                },
            },

            data: function() {
                return {
                    videoData: '',
                    newVideo: 0,
                    isDragging: false,
                };
            },

            mounted () {
                if (this.video.id && this.video.url) {
                    this.videoData = this.video.url;
                } else if (this.video.id && this.video.file) {
                    this.readFile(this.video.file);
                }
            },

            computed: {
                finalInputName: function() {
                    return this.inputName + '[' + this.video.id + ']';
                }
            },

            methods: {
                addVideoView: function() {
                    let videoInput = this.$refs.videoInput;

                    if (videoInput.files && videoInput.files[0]) {
                        if (videoInput.files[0].type.includes('video/')) {
                            this.readFile(videoInput.files[0]);

                            if (videoInput.files.length > 1) {
                                this.$emit('onVideoSelected', videoInput);
                            }
                        } else {
                            videoInput.value = "";

                            alert('Only videos (.mp4, .mov, .ogg ..) are allowed.');
                        }
                    }
                },

                readFile: function(video) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.videoData = e.target.result;
                    }

                    reader.readAsDataURL(video);

                    this.newVideo = 1;
                },

                removeVideo: function() {
                    this.$emit('onRemoveVideo', this.video);
                },
            }
        });
    </script>
@endpush
