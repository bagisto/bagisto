@php
    use Webkul\Core\Helpers\MediaUpload;

    $mediaUpload = app(MediaUpload::class);

    $defaultExtensions = implode(',', $mediaUpload->extensions(MediaUpload::VIDEO));

    $defaultMaxSize = $mediaUpload->maxSize(MediaUpload::VIDEO);
@endphp

@props([
    'name'           => 'videos',
    'allowMultiple'  => false,
    'uploadedVideos' => [],
    'width'          => '210px',
    'height'         => '120px',
    'enableSeo'      => false,
    'metaName'       => '',
    'allowedTypes'   => null,
    'extraTypes'     => '',
    'maxSize'        => null,
])

<v-media-videos
    name="{{ $name }}"
    v-bind:allow-multiple="{{ $allowMultiple ? 'true' : 'false' }}"
    v-bind:enable-seo="{{ $enableSeo ? 'true' : 'false' }}"
    meta-name="{{ $metaName }}"
    allowed-types="{{ $allowedTypes ?: $defaultExtensions }}"
    extra-types="{{ $extraTypes }}"
    v-bind:max-size="{{ (int) ($maxSize ?: $defaultMaxSize) }}"
    :uploaded-videos="@js($uploadedVideos)"
    width="{{ $width }}"
    height="{{ $height }}"
    :errors="errors"
    {{ $attributes->except([':uploaded-videos', ':errors']) }}
>
</v-media-videos>

@include('admin::components.media.validation')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-media-videos-template"
    >
        <!-- Panel Content -->
        <div class="grid">
            <div class="flex gap-1">
                <!-- Upload Video Button -->

                <label
                    class="grid h-30 max-h-30 w-full max-w-52.5 cursor-pointer items-center justify-items-center rounded-sm border border-dashed transition-all hover:border-gray-400 dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                    :class="[errorMessage ? 'border border-red-500' : 'border-gray-300']"
                    :for="$.uid + '_videoInput'"
                    v-if="allowMultiple || videos.length == 0"
                >
                    <div class="flex flex-col items-center">
                        <span class="icon-image text-2xl"></span>

                        <p class="grid text-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                            @lang('admin::app.components.media.videos.add-video-btn')
                            
                            <span
                                class="text-xs"
                                v-text="hint"
                            >
                            </span>
                        </p>

                        <input
                            type="file"
                            class="hidden"
                            :id="$.uid + '_videoInput'"
                            :accept="rules.accept"
                            :multiple="allowMultiple"
                            @change="add"
                        />
                    </div>
                </label>

                <!-- Uploaded Videos -->
                <draggable
                    class="flex gap-1"
                    ghost-class="draggable-ghost"
                    v-bind="{animation: 200}"
                    :list="videos"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <v-media-video-item
                            :enable-seo="enableSeo"
                            :meta-name="metaName"
                            :name="name"
                            :index="index"
                            :video="element"
                            :width="width"
                            :height="height"
                            :rules="rules"
                            @onRemove="remove($event)"
                            @onChange="clearErrors"
                            @onInvalid="clientError = $event"
                        >
                        </v-media-video-item>
                    </template>
                </draggable>
            </div>

            <p
                class="mt-1 text-xs italic text-red-600"
                v-if="errorMessage"
                v-text="errorMessage"
            >
            </p>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-media-video-item-template"
    >
        <div class="group relative grid h-30 max-h-30 min-w-52.5 max-w-52.5 justify-items-center overflow-hidden rounded-sm border border-dashed border-gray-300 transition-all hover:border-gray-400 dark:border-gray-800">
            <!-- Video Preview -->
            <video
                class="h-30 w-52.5 object-cover"
                ref="videoPreview"
                :src="video.url"
                v-if="video.url"
                @ended="isPlaying = false"
            >
            </video>

            <div class="invisible absolute bottom-0 top-0 flex w-full flex-col justify-between bg-white p-3 opacity-80 transition-all group-hover:visible dark:bg-gray-900">
                <!-- Video Name -->
                <p class="break-all text-xs font-semibold text-gray-600 dark:text-gray-300">
                    @{{ video.file_name }}
                </p>

                <!-- Actions -->
                <div class="flex justify-between">
                    <!-- Remove Button -->
                    <span
                        class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200 dark:hover:bg-gray-800"
                        @click="remove"
                    ></span>

                    <!-- Play Pause Button -->
                    <span
                        class="cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200 dark:hover:bg-gray-800"
                        :class="[isPlaying ? 'icon-pause': 'icon-play']"
                        @click="playPause"
                    ></span>

                    <!-- Edit Button -->
                    <span
                        class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200 dark:hover:bg-gray-800"
                        v-if="enableSeo"
                        @click="$refs.seoDrawer.open()"
                    ></span>

                    <label
                        class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200 dark:hover:bg-gray-800"
                        v-else
                        :for="$.uid + '_videoInput_' + index"
                    ></label>

                    <input
                        type="hidden"
                        :name="name + '[' + video.id + ']'"
                        v-if="! video.is_new"
                    />

                    <input
                        type="file"
                        class="hidden"
                        :name="enableSeo ? name + '[' + video.id + ']' : name + '[]'"
                        ref="fileInput"
                    />

                    <input
                        type="file"
                        class="hidden"
                        :accept="rules.accept"
                        :id="$.uid + '_videoInput_' + index"
                        @change="replace"
                    />
                </div>
            </div>

            <!--
                Kept outside of the drawer, which is only rendered while open, so that the
                metadata is submitted with the form whether the drawer was opened or not.
            -->
            <input
                type="hidden"
                :name="metaName + '[' + video.id + '][file_name]'"
                :value="video.file_name ?? ''"
                v-if="enableSeo && metaName"
            />

            <!-- Video SEO Drawer -->
            <x-admin::drawer
                ref="seoDrawer"
                v-if="enableSeo"
            >
                <x-slot:header>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.components.media.videos.seo.title')
                    </p>

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                        @lang('admin::app.components.media.videos.seo.info')
                    </p>
                </x-slot>

                <x-slot:content>
                    <!-- File Name -->
                    <div class="mb-4">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.components.media.images.seo.file-name')
                        </x-admin::form.control-group.label>

                        <input
                            type="text"
                            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                            :placeholder="@js(trans('admin::app.components.media.images.seo.file-name-placeholder'))"
                            v-model="video.file_name"
                        />

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">
                            @lang('admin::app.components.media.images.seo.file-name-info')
                        </p>
                    </div>

                    <!-- Replace Video -->
                    <div>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.components.media.images.seo.replace')
                        </x-admin::form.control-group.label>

                        <label
                            class="secondary-button inline-flex cursor-pointer"
                            :for="$.uid + '_videoInput_' + index"
                        >
                            @lang('admin::app.components.media.images.seo.replace-btn')
                        </label>
                    </div>
                </x-slot>

                <x-slot:footer>
                    <div class="flex justify-end px-3">
                        <button
                            type="button"
                            class="primary-button"
                            @click="$refs.seoDrawer.close()"
                        >
                            @lang('admin::app.components.media.images.seo.done-btn')
                        </button>
                    </div>
                </x-slot>
            </x-admin::drawer>
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
                    default: () => [],
                },

                width: {
                    type: String,
                    default: '210px',
                },

                height: {
                    type: String,
                    default: '120px',
                },

                enableSeo: {
                    type: Boolean,
                    default: false,
                },

                metaName: {
                    type: String,
                    default: '',
                },

                allowedTypes: {
                    type: [String, Array],
                    default: @js($defaultExtensions),
                },

                extraTypes: {
                    type: [String, Array],
                    default: '',
                },

                maxSize: {
                    type: Number,
                    default: {{ $defaultMaxSize }},
                },

                errors: {
                    type: Object,
                    default: () => ({}),
                },
            },

            data() {
                return {
                    videos: [],

                    newVideoIndex: 0,

                    clientError: null,

                    isServerErrorDismissed: false,
                };
            },

            computed: {
                /**
                 * What a picked file is checked against: its extensions, size and type, and the picker filter.
                 */
                rules() {
                    const extensions = [...new Set([
                        ...this.$mediaUpload.parseExtensions(this.allowedTypes),
                        ...this.$mediaUpload.parseExtensions(this.extraTypes),
                    ])];

                    return {
                        extensions,
                        maxSize: this.maxSize,
                        mimePrefix: 'video/',
                        accept: this.$mediaUpload.accept(extensions),
                    };
                },

                /**
                 * The allowed extensions and size, shown under the upload button.
                 */
                hint() {
                    return this.$mediaUpload.hint(this.rules.extensions, this.rules.maxSize);
                },

                /**
                 * The first error the server reported for this field.
                 */
                serverError() {
                    return this.$mediaUpload.fieldError(this.errors, this.name);
                },

                /**
                 * The message shown under the field, a rejected selection taking precedence over the server's.
                 */
                errorMessage() {
                    if (this.clientError) {
                        return this.clientError;
                    }

                    return this.isServerErrorDismissed ? null : this.serverError;
                },
            },

            watch: {
                /**
                 * A fresh server error describes the latest submission, so it is shown again.
                 */
                serverError() {
                    this.isServerErrorDismissed = false;
                },
            },

            mounted() {
                this.videos = this.uploadedVideos;

                document.addEventListener('submit', this.onSubmit, true);
            },

            beforeUnmount() {
                document.removeEventListener('submit', this.onSubmit, true);
            },

            methods: {
                /**
                 * Add the picked files, or refuse all of them when one is not allowed.
                 */
                add(event) {
                    const files = this.$mediaUpload.takeFiles(event.target);

                    if (! files.length) {
                        return;
                    }

                    const error = files.map(file => this.$mediaUpload.validate(file, this.rules)).find(Boolean);

                    if (error) {
                        this.clientError = error;

                        return;
                    }

                    this.clearErrors();

                    files.forEach(file => {
                        this.videos.push({
                            id: 'video_' + this.newVideoIndex++,
                            url: '',
                            file_name: file.name.replace(/\.[^/.]+$/, ''),
                            file,
                        });
                    });
                },

                /**
                 * Remove a video from the list.
                 */
                remove(video) {
                    this.videos.splice(this.videos.indexOf(video), 1);

                    this.clearErrors();
                },

                /**
                 * Drop the messages describing the previous selection once the videos change.
                 */
                clearErrors() {
                    this.clientError = null;

                    this.isServerErrorDismissed = true;
                },

                /**
                 * Let the server's answer to a new submission be shown again.
                 */
                onSubmit() {
                    this.isServerErrorDismissed = false;
                },
            },
        });

        app.component('v-media-video-item', {
            template: '#v-media-video-item-template',

            props: ['index', 'video', 'name', 'width', 'height', 'enableSeo', 'metaName', 'rules'],

            emits: ['onRemove', 'onChange', 'onInvalid'],

            data() {
                return {
                    isPlaying: false,

                    objectUrl: null,
                };
            },

            mounted() {
                if (this.video.file instanceof File) {
                    this.setFile(this.video.file);

                    this.preview(this.video.file);
                }
            },

            beforeUnmount() {
                this.revokePreview();
            },

            methods: {
                /**
                 * Replace the video with the picked file, which reaches the submitted input only once allowed.
                 */
                replace(event) {
                    const [file] = this.$mediaUpload.takeFiles(event.target);

                    if (! file) {
                        return;
                    }

                    const error = this.$mediaUpload.validate(file, this.rules);

                    if (error) {
                        this.$emit('onInvalid', error);

                        return;
                    }

                    if (! this.video.file_name) {
                        this.video.file_name = file.name.replace(/\.[^/.]+$/, '');
                    }

                    this.isPlaying = false;

                    this.setFile(file);

                    this.preview(file);

                    this.$emit('onChange', this.video);
                },

                /**
                 * Ask the list to remove this video.
                 */
                remove() {
                    this.$emit('onRemove', this.video);
                },

                /**
                 * Put the file into the input submitted with the form.
                 */
                setFile(file) {
                    this.video.is_new = 1;

                    this.video.file = file;

                    const dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);

                    this.$refs.fileInput.files = dataTransfer.files;
                },

                /**
                 * Preview the file through an object URL, which unlike a data URL is not held in memory as text.
                 */
                preview(file) {
                    this.revokePreview();

                    this.objectUrl = URL.createObjectURL(file);

                    this.video.url = this.objectUrl;
                },

                /**
                 * Release the object URL of the previous preview.
                 */
                revokePreview() {
                    if (this.objectUrl) {
                        URL.revokeObjectURL(this.objectUrl);

                        this.objectUrl = null;
                    }
                },

                /**
                 * Play the preview, or pause it when it is playing.
                 */
                playPause() {
                    const videoPreview = this.$refs.videoPreview;

                    if (! videoPreview) {
                        return;
                    }

                    if (videoPreview.paused) {
                        this.isPlaying = true;

                        videoPreview.play();
                    } else {
                        this.isPlaying = false;

                        videoPreview.pause();
                    }
                },
            },
        });
    </script>
@endPushOnce