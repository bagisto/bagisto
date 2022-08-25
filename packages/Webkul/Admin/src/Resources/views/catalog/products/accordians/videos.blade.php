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
        <label class="image-item" v-bind:class="{ 'has-image': videoData.length > 0 }">
            <input
                type="hidden"
                :name="'videos[files][' + video.id + ']'"
                v-if="! new_video"/>

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
                @change="addVideoView($event)"/>

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
                let self = this;

                this.videos.forEach(function(video) {
                    self.items.push(video);

                    self.videoCount++;
                });
            },

            methods: {
                createFileType: function() {
                    let self = this;

                    this.videoCount++;

                    this.items.push({'id': 'video_' + this.videoCount});
                },

                removeVideo: function(video) {
                    let index = this.items.indexOf(video);

                    Vue.delete(this.items, index);
                },

                videoSelected: function(event) {
                    let self = this;

                    Array.from(event.files).forEach(function(video, index) {
                        if (index) {
                            self.videoCount++;

                            self.items.push({'id': 'video_' + self.videoCount, file: video});
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

                    new_video: 0,
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

                    this.new_video = 1;
                },

                removeVideo: function() {
                    this.$emit('onRemoveVideo', this.video);
                },
            }
        });
    </script>
@endpush
