@props([
    'name'           => 'images',
    'allowMultiple'  => false,
    'uploadedVideos' => [],
    'width'          => '210px',
    'height'         => '120px',
    'enableSeo'      => false,
    'metaName'       => '',
    'acceptedTypes'  => product_video()->getAllowedExtensions()->implode(','),
    'maxSize'        => 0,
])

<v-media-videos
    name="{{ $name }}"
    v-bind:allow-multiple="{{ $allowMultiple ? 'true' : 'false' }}"
    v-bind:enable-seo="{{ $enableSeo ? 'true' : 'false' }}"
    meta-name="{{ $metaName }}"
    accepted-types="{{ $acceptedTypes }}"
    v-bind:max-size="{{ (int) $maxSize }}"
    :uploaded-videos="@js($uploadedVideos)"
    width="{{ $width }}"
    height="{{ $height }}"
    :errors="errors"
    :set-field-error="setFieldError"
    {{ $attributes->get('class') }}
>
</v-media-videos>

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
                    :class="[fieldError ? 'border border-red-500' : 'border-gray-300']"
                    :for="$.uid + '_videoInput'"
                    v-if="allowMultiple || videos.length == 0"
                >
                    <div class="flex flex-col items-center">
                        <span class="icon-image text-2xl"></span>

                        <p class="grid text-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                            @lang('admin::app.components.media.videos.add-video-btn')
                            
                            <span
                                class="text-xs"
                                v-text="acceptedExtensions"
                            >
                            </span>
                        </p>

                        <input
                            type="file"
                            class="hidden"
                            :id="$.uid + '_videoInput'"
                            :accept="acceptedTypes"
                            :multiple="allowMultiple"
                            :ref="$.uid + '_videoInput'"
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
                            :refusal-for="refusalFor"
                            @onRemove="remove($event)"
                            @onChange="clearErrors"
                            @onRefusal="setClientError"
                        >
                        </v-media-video-item>
                    </template>
                </draggable>
            </div>

            <p
                class="mt-1 text-xs italic text-red-600"
                data-field-error
                v-if="fieldError"
                v-text="fieldError"
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
                v-if="video.url.length > 0"
            >
                <source
                    :src="video.url"
                    type="video/mp4"
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
                        :name="enableSeo ? name + '[' + video.id + ']' : name + '[]'"
                        class="hidden"
                        accept="video/*"
                        :id="$.uid + '_videoInput_' + index"
                        :ref="$.uid + '_videoInput_' + index"
                        @change="edit"
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

                enableSeo: {
                    type: Boolean,
                    default: false,
                },

                metaName: {
                    type: String,
                    default: '',
                },

                acceptedTypes: {
                    type: String,
                    required: true,
                },

                maxSize: {
                    type: [Number, String],
                    default: 0,
                },

                errors: {
                    type: Object,
                    default: () => ({})
                },

                setFieldError: {
                    type: Function,
                },
            },

            data() {
                return {
                    videos: [],

                    newVideoIndex: 0,

                    clientError: '',
                }
            },

            computed: {
                /**
                 * The keys of the form's errors that belong to one of this field's files.
                 */
                errorKeys() {
                    const toPath = key => key.replace(/\[\]$/, '').replace(/\[(.*?)\]/g, '.$1');

                    const field = toPath(this.name) + '.';

                    return Object.keys(this.errors).filter(key => toPath(key).startsWith(field));
                },

                /**
                 * The first error the form holds for one of this field's files.
                 */
                errorMessage() {
                    return this.errors[this.errorKeys[0]];
                },

                /**
                 * The error shown under the field, the server's for one of its files before the one found on picking.
                 */
                fieldError() {
                    return this.errorMessage ?? this.clientError;
                },

                /**
                 * The extensions the field accepts, written for display from its accepted types.
                 */
                acceptedExtensions() {
                    const subtypeExtensions = {
                        'svg+xml': 'svg',
                        'quicktime': 'mov',
                        'x-icon': 'ico',
                        'vnd.microsoft.icon': 'ico',
                    };

                    const types = (this.acceptedTypes || '')
                        .split(',')
                        .map(type => type.trim().toLowerCase())
                        .filter(Boolean);

                    const extensions = types
                        .filter(type => ! type.endsWith('*'))
                        .map(type => {
                            if (! type.includes('/')) {
                                return type.replace(/^\.?/, '.');
                            }

                            const subtype = type.split('/').pop();

                            return '.' + (subtypeExtensions[subtype] ?? subtype);
                        });

                    const labels = extensions.length
                        ? extensions
                        : types.map(type => type.split('/').shift()).filter(type => type !== '*');

                    return [...new Set(labels)].join(', ');
                },

                /**
                 * The message shown when a picked file is not one of the accepted types.
                 */
                notAllowedError() {
                    return "@lang('admin::app.components.media.videos.not-allowed-error')".replace(':types', this.acceptedExtensions);
                },
            },

            watch: {
                fieldError(message) {
                    if (message) {
                        this.focusError();
                    }
                },
            },

            mounted() {
                this.videos = this.uploadedVideos;

                if (this.fieldError) {
                    this.focusError('instant');
                }
            },

            beforeUnmount() {
                this.clearErrors();
            },

            methods: {
                /**
                 * Add the picked files to the field, provided each is of a type and size it accepts.
                 */
                add() {
                    let videoInput = this.$refs[this.$.uid + '_videoInput'];

                    if (videoInput.files == undefined) {
                        return;
                    }

                    this.clearErrors();

                    const refusal = this.refusalFor(videoInput.files);

                    if (refusal) {
                        this.clientError = refusal;

                        return;
                    }

                    videoInput.files.forEach((file, index) => {
                        this.videos.push({
                            id: 'video_' + this.newVideoIndex++,
                            url: '',
                            file: file
                        });
                    });
                },

                /**
                 * Why the files cannot be added to the field, or an empty string when they can.
                 */
                refusalFor(files) {
                    if (! Array.from(files).every(file => file.type.includes('video/'))) {
                        return this.notAllowedError;
                    }

                    if (Array.from(files).some(file => this.exceedsMaxSize(file))) {
                        return "@lang('admin::app.components.media.videos.size-limit-error')".replace(':size', this.formattedSizeLimit());
                    }

                    return '';
                },

                /**
                 * Whether the file is larger than the field, or PHP, accepts.
                 */
                exceedsMaxSize(file) {
                    const limit = this.sizeLimitKilobytes();

                    return limit > 0
                        && file.size > limit * 1024;
                },

                /**
                 * The largest file the field takes in kilobytes, the lower of its own limit and PHP's.
                 */
                sizeLimitKilobytes() {
                    const limits = [{{ core()->getMaxUploadSize() }}, Number(this.maxSize) || 0].filter(limit => limit > 0);

                    return limits.length ? Math.min(...limits) : 0;
                },

                /**
                 * The size limit written for the admin, in megabytes from one megabyte up.
                 */
                formattedSizeLimit() {
                    const kilobytes = this.sizeLimitKilobytes();

                    return kilobytes >= 1024
                        ? (Math.round((kilobytes / 1024) * 10) / 10) + ' MB'
                        : kilobytes + ' KB';
                },

                /**
                 * Remove a video from the field.
                 */
                remove(video) {
                    let index = this.videos.indexOf(video);

                    this.videos.splice(index, 1);

                    this.clearErrors();
                },

                /**
                 * Forget the errors of this field's files, which the form otherwise keeps until the page reloads.
                 */
                clearErrors() {
                    this.clientError = '';

                    this.errorKeys.forEach(key => this.setFieldError?.(key, undefined));
                },

                /**
                 * Show, under the field, why a file picked to replace an existing video was refused.
                 */
                setClientError(message) {
                    this.clearErrors();

                    this.clientError = message;
                },

                /**
                 * Bring this field's error into view when no field before it holds one. On load it jumps rather
                 * than glides, so scroll anchoring keeps it there while the editors above it finish growing.
                 */
                focusError(behavior = 'smooth') {
                    this.$nextTick(() => {
                        const errorElement = this.$el?.querySelector?.('[data-field-error]');

                        if (! errorElement) {
                            return;
                        }

                        if (document.querySelector('[data-field-error]') !== errorElement) {
                            return;
                        }

                        errorElement.scrollIntoView({
                            behavior,
                            block: 'center',
                        });
                    });
                },
            }
        });

        app.component('v-media-video-item', {
            template: '#v-media-video-item-template',

            props: ['index', 'video', 'name', 'width', 'height', 'enableSeo', 'metaName', 'refusalFor'],

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
                /**
                 * Replace this video with the picked file, provided it is a video.
                 */
                edit() {
                    let videoInput = this.$refs[this.$.uid + '_videoInput_' + this.index];

                    if (videoInput.files == undefined) {
                        return;
                    }

                    const refusal = this.refusalFor(videoInput.files);

                    if (refusal) {
                        this.$emit('onRefusal', refusal);

                        return;
                    }

                    this.setFile(videoInput.files[0]);

                    this.readFile(videoInput.files[0]);

                    this.$emit('onChange');
                },

                /**
                 * Ask the field to remove this video.
                 */
                remove() {
                    this.$emit('onRemove', this.video)
                },

                /**
                 * Put the file on this video's input, so that it is submitted with the form.
                 */
                setFile(file) {
                    this.video.is_new = 1;

                    const dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);

                    this.$refs[this.$.uid + '_videoInput_' + this.index].files = dataTransfer.files;
                },

                /**
                 * Read the file for this video's preview.
                 */
                readFile(file) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.video.url = e.target.result;
                    }

                    reader.readAsDataURL(file);
                },

                /**
                 * Play or pause the preview of this video.
                 */
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