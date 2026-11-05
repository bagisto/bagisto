<v-media-upload {{ $attributes }}></v-media-upload>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-media-upload-template"
    >
        <v-field
            v-slot="{ field: mediaField, errors, handleChange, handleBlur }"
            :name="name"
            :rules="fieldRules"
            :label="label"
        >
            <div class="flex">
                <!-- Uploaded Media -->
                <div
                    v-if="hasMedia"
                    class="group relative flex h-30 w-30 items-center justify-center overflow-hidden rounded-sm border border-gray-300 dark:border-gray-800"
                >
                    <img
                        v-if="mediaPreview"
                        class="max-h-full max-w-full"
                        :src="mediaPreview"
                        :alt="mediaFileName"
                    />

                    <div
                        v-else
                        class="flex flex-col items-center gap-1 px-2"
                    >
                        <span class="icon-folder text-2xl text-gray-600 dark:text-gray-300"></span>

                        <p
                            class="line-clamp-2 break-all text-center text-xs text-gray-600 dark:text-gray-300"
                            v-text="mediaFileName"
                        >
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="invisible absolute bottom-0 flex w-full justify-center gap-1 bg-white/90 p-1 transition-all group-hover:visible dark:bg-gray-900/90">
                        <label
                            class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                            :for="name"
                            title="@lang('admin::app.components.media.upload.replace')"
                        ></label>

                        <a
                            v-if="mediaDownloadUrl"
                            class="icon-down-stat rounded-md p-1.5 text-2xl text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                            :href="mediaDownloadUrl"
                            title="@lang('admin::app.components.media.upload.download')"
                        ></a>

                        <span
                            v-if="isRemovable"
                            class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                            title="@lang('admin::app.components.media.upload.delete')"
                            @click="removeMedia"
                        ></span>
                    </div>
                </div>

                <!-- Upload Button -->
                <label
                    v-else
                    class="flex h-30 w-30 cursor-pointer flex-col items-center justify-center gap-1 overflow-hidden rounded-sm border border-dashed px-2 transition-all hover:border-gray-400 dark:hover:border-gray-400"
                    :class="(errors.length || fieldError) ? 'border-red-600' : 'border-gray-300 dark:border-gray-800'"
                    :for="name"
                >
                    <span
                        v-if="type == 'image'"
                        class="icon-image text-2xl text-gray-600 dark:text-gray-300"
                    ></span>

                    <span
                        v-else
                        class="icon-folder text-2xl text-gray-600 dark:text-gray-300"
                    ></span>

                    <p
                        v-if="type == 'image'"
                        class="text-center text-sm font-semibold text-gray-600 dark:text-gray-300"
                    >
                        @lang('admin::app.components.media.upload.add-image')
                    </p>

                    <p
                        v-else
                        class="text-center text-sm font-semibold text-gray-600 dark:text-gray-300"
                    >
                        @lang('admin::app.components.media.upload.add-file')
                    </p>

                    <p
                        v-if="mediaHint"
                        class="text-center text-xs leading-tight text-gray-500 dark:text-gray-300"
                        v-text="mediaHint"
                    >
                    </p>
                </label>

                <input
                    type="file"
                    class="hidden"
                    ref="mediaInput"
                    :id="name"
                    :name="mediaField.name"
                    :accept="mediaAccept"
                    @change="onPick($event, handleChange)"
                    @blur="handleBlur"
                />

                <input
                    v-if="media.isDeleted"
                    type="hidden"
                    :name="`${name}[delete]`"
                    value="1"
                />
            </div>

            <p
                class="mt-1 text-xs italic text-red-600"
                ref="fieldError"
                data-field-error
                v-if="fieldError"
                v-text="fieldError"
            >
            </p>
        </v-field>
    </script>

    <script type="module">
        app.component('v-media-upload', {
            template: '#v-media-upload-template',

            props: [
                'name',
                'type',
                'value',
                'src',
                'downloadUrl',
                'rules',
                'extensions',
                'label',
                'removable',
            ],

            data() {
                return {
                    media: {
                        file: null,

                        preview: '',

                        isDeleted: false,
                    },

                    clientError: '',
                };
            },

            computed: {
                /**
                 * Whether the stored media may be removed, which a required field does not allow.
                 */
                isRemovable() {
                    return this.removable != '0';
                },

                /**
                 * The extensions the control accepts, taken from the caller or from a mimes rule.
                 */
                mediaTypes() {
                    if (this.extensions) {
                        return this.extensions.split(',').map((type) => type.trim()).filter((type) => type);
                    }

                    if (typeof this.rules !== 'string') {
                        return [];
                    }

                    const rule = this.rules
                        .split('|')
                        .find((rule) => rule.startsWith('mimes:'));

                    return rule
                        ? rule.split(':')[1].split(',').filter((type) => type)
                        : [];
                },

                /**
                 * The accept attribute of the file input, narrowed to the extensions allowed.
                 */
                mediaAccept() {
                    if (this.mediaTypes.length) {
                        return this.mediaTypes.map((type) => `.${type}`).join(',');
                    }

                    return this.type == 'image' ? 'image/*' : '';
                },

                /**
                 * The rules handed to the validator, without the type and size ones this control checks itself.
                 */
                fieldRules() {
                    if (typeof this.rules !== 'string') {
                        return this.rules;
                    }

                    return this.rules
                        .split('|')
                        .filter((rule) => ! ['mimes', 'mimetypes', 'image', 'size', 'dimensions'].includes(rule.split(':')[0]))
                        .join('|');
                },

                /**
                 * The extensions the control accepts, written for display from its accepted types.
                 */
                acceptedExtensions() {
                    if (this.mediaTypes.length) {
                        return this.mediaTypes.map((type) => `.${type}`).join(', ');
                    }

                    return this.type == 'image' ? 'image' : '';
                },

                /**
                 * The accepted extensions, shown as the hint under the upload button.
                 */
                mediaHint() {
                    return this.mediaTypes.join(', ');
                },

                /**
                 * The error shown under the control when a picked file is of a type or size it refuses.
                 */
                fieldError() {
                    return this.clientError;
                },

                /**
                 * Whether there is a file to show: one just picked, or a stored one still kept.
                 */
                hasMedia() {
                    return !! this.media.file
                        || (!! this.value && ! this.media.isDeleted);
                },

                /**
                 * The image to preview, empty for a control holding something that is not one.
                 */
                mediaPreview() {
                    if (this.type != 'image') {
                        return '';
                    }

                    if (this.media.file) {
                        return this.media.preview;
                    }

                    return this.value && ! this.media.isDeleted ? this.src : '';
                },

                /**
                 * The name of the picked file, or of the stored one.
                 */
                mediaFileName() {
                    if (this.media.file) {
                        return this.media.file.name;
                    }

                    return this.value ? this.value.split('/').pop() : '';
                },

                /**
                 * The link a stored file is downloaded from, empty while there is nothing stored.
                 */
                mediaDownloadUrl() {
                    if (
                        ! this.downloadUrl
                        || this.media.file
                        || ! this.value
                        || this.media.isDeleted
                    ) {
                        return '';
                    }

                    return this.downloadUrl;
                },
            },

            watch: {
                fieldError: {
                    immediate: true,

                    handler(message) {
                        if (message) {
                            this.focusError();
                        }
                    },
                },
            },

            methods: {
                /**
                 * Stage the picked file when the control accepts it, showing why it does not otherwise.
                 */
                onPick(event, handleChange) {
                    this.clientError = '';

                    const file = event.target.files[0];

                    if (! file) {
                        handleChange(event);

                        return;
                    }

                    const refusal = this.refusalFor(file);

                    if (refusal) {
                        event.target.value = '';

                        this.clientError = refusal;

                        return;
                    }

                    handleChange(event);

                    this.stageMedia(event);
                },

                /**
                 * Why the file cannot be added to the control, or an empty string when it can.
                 */
                refusalFor(file) {
                    if (! this.isAcceptedType(file)) {
                        return "@lang('admin::app.components.media.upload.not-allowed-error')".replace(':types', this.acceptedExtensions);
                    }

                    if (this.exceedsMaxSize(file)) {
                        return "@lang('admin::app.components.media.upload.size-limit-error')".replace(':size', this.formattedSizeLimit());
                    }

                    return '';
                },

                /**
                 * Whether the control accepts the file, by its extension or its MIME type.
                 */
                isAcceptedType(file) {
                    const types = (this.mediaAccept || '')
                        .split(',')
                        .map((type) => type.trim().toLowerCase())
                        .filter(Boolean);

                    if (
                        ! types.length
                        || types.includes('*')
                        || types.includes('*/*')
                    ) {
                        return true;
                    }

                    const mime = (file.type || '').toLowerCase();

                    const extension = (file.name || '').split('.').pop().toLowerCase();

                    return types.some((type) => {
                        if (type.endsWith('/*')) {
                            return mime.startsWith(type.slice(0, -1));
                        }

                        if (type.includes('/')) {
                            return mime === type;
                        }

                        return (type.startsWith('.') ? type.slice(1) : type) === extension;
                    });
                },

                /**
                 * Whether the file is larger than PHP accepts.
                 */
                exceedsMaxSize(file) {
                    const limit = this.sizeLimitKilobytes();

                    return limit > 0
                        && file.size > limit * 1024;
                },

                /**
                 * The largest file the control takes in kilobytes, as PHP is configured.
                 */
                sizeLimitKilobytes() {
                    return {{ core()->getMaxUploadSize() }};
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
                 * Bring this control's error into view, but only when no field before it holds one.
                 */
                focusError() {
                    this.$nextTick(() => {
                        const errorElement = this.$refs.fieldError;

                        if (! errorElement) {
                            return;
                        }

                        if (document.querySelector('[data-field-error]') !== errorElement) {
                            return;
                        }

                        errorElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center',
                        });
                    });
                },

                /**
                 * Stage the picked file for preview, over whatever the control already held.
                 */
                stageMedia(event) {
                    const file = event.target.files[0];

                    if (! file) {
                        return;
                    }

                    this.media.file = file;

                    this.media.preview = '';

                    this.media.isDeleted = false;

                    if (! file.type.startsWith('image/')) {
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = (event) => this.media.preview = event.target.result;

                    reader.readAsDataURL(file);
                },

                /**
                 * Drop the picked file, or mark the stored one to be removed on save. The cleared
                 * input is dispatched so the validator lets go of the file it was holding too.
                 */
                removeMedia() {
                    this.clientError = '';

                    this.$refs.mediaInput.value = '';

                    this.$refs.mediaInput.dispatchEvent(new Event('change'));

                    if (this.media.file) {
                        this.media.file = null;

                        this.media.preview = '';

                        return;
                    }

                    this.media.isDeleted = true;
                },
            },
        });
    </script>
@endPushOnce
