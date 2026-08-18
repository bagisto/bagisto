{{--
    A syntax highlighted editor for the html and css a static content section holds.
    CodeMirror comes from a cdn; if it does not load, the textarea underneath stays live.
--}}
@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-code-editor-template"
    >
        <div class="min-w-0 max-w-full overflow-hidden rounded-md border dark:border-gray-800">
            <!-- Toolbar -->
            <div class="flex items-center justify-between gap-2 border-b bg-gray-50 px-3 py-1.5 dark:border-gray-800 dark:bg-gray-950">
                <span class="font-mono text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                    @{{ language }}
                </span>

                <div class="flex items-center gap-2">
                    <span
                        class="text-xs text-gray-400"
                        v-if="isUploading"
                    >
                        @lang('admin::app.appearance.sections.edit.uploading')
                    </span>

                    <!-- Media is inserted at the cursor, so it lands where the author is typing. -->
                    <label
                        class="flex cursor-pointer items-center gap-1 rounded px-2 py-1 text-xs font-semibold text-blue-600 transition-all hover:bg-blue-50 dark:hover:bg-gray-900"
                        v-if="allowsMedia"
                    >
                        <span class="icon-image text-base"></span>

                        @lang('admin::app.appearance.sections.edit.add-media')

                        <input
                            type="file"
                            class="hidden"
                            accept="image/*,video/*"
                            :disabled="isUploading"
                            @change="insertMedia"
                        />
                    </label>
                </div>
            </div>

            <textarea
                class="w-full border-0 bg-white px-3 py-2.5 font-mono text-xs text-gray-600 dark:bg-gray-900 dark:text-gray-300"
                rows="12"
                ref="area"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            ></textarea>
        </div>
    </script>

    <script type="module">
        app.component('v-code-editor', {
            template: '#v-code-editor-template',

            props: ['modelValue', 'language', 'sectionId', 'mediaUrl'],

            emits: ['update:modelValue'],

            data() {
                return {
                    isUploading: false,
                };
            },

            mounted() {
                this.upgrade();
            },

            beforeUnmount() {
                this.editor?.toTextArea();
            },

            computed: {
                /**
                 * Only markup has somewhere to put a picture or a clip.
                 */
                allowsMedia() {
                    return this.language !== 'css';
                },
            },

            watch: {
                /**
                 * Reflect a value the editor did not originate, such as switching to a
                 * different section while the drawer stays mounted.
                 */
                modelValue(value) {
                    if (
                        this.editor
                        && this.editor.getValue() !== (value ?? '')
                    ) {
                        this.editor.setValue(value ?? '');
                    }
                },
            },

            methods: {
                /**
                 * Replace the textarea with CodeMirror. Silently left alone when the cdn
                 * did not load, which keeps the textarea usable rather than breaking the
                 * whole drawer.
                 */
                upgrade() {
                    if (typeof CodeMirror === 'undefined') {
                        return;
                    }

                    this.editor = CodeMirror.fromTextArea(this.$refs.area, {
                        mode: this.language === 'css' ? 'css' : 'htmlmixed',
                        theme: document.documentElement.classList.contains('dark') ? 'ayu-dark' : 'default',
                        lineNumbers: true,
                        lineWrapping: true,
                        autoCloseTags: true,
                    });

                    this.editor.on('change', () => this.$emit('update:modelValue', this.editor.getValue()));

                    /**
                     * The drawer animates open, so the editor is measured again once it has
                     * its final width.
                     */
                    this.$nextTick(() => this.editor.refresh());
                },

                /**
                 * Upload a picture or a clip and write the markup for it at the cursor.
                 */
                insertMedia(event) {
                    const file = event.target.files?.[0];

                    if (! file) {
                        return;
                    }

                    const payload = new FormData();

                    payload.append('file', file);

                    this.isUploading = true;

                    this.$axios.post(this.mediaUrl.replace('__ID__', this.sectionId), payload)
                        .then(response => this.write(this.markupFor(response.data)))
                        .catch(error => this.$emitter.emit('add-flash', {
                            type: 'error',
                            message: error.response?.data?.message ?? error.message,
                        }))
                        .finally(() => {
                            this.isUploading = false;

                            event.target.value = '';
                        });
                },

                /**
                 * The tag that renders an uploaded file on the storefront.
                 */
                markupFor({ path, type }) {
                    const src = '/' + path;

                    return type === 'video'
                        ? `<video src="${src}" controls playsinline class="w-full"></video>`
                        : `<img src="${src}" alt="" class="w-full" />`;
                },

                /**
                 * Put markup where the cursor is, or on the end when there is no editor.
                 */
                write(markup) {
                    if (this.editor) {
                        this.editor.replaceSelection(markup);

                        this.editor.focus();

                        return;
                    }

                    this.$emit('update:modelValue', `${this.modelValue ?? ''}\n${markup}`);
                },
            },
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/javascript/javascript.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/css/css.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css"
    />

    <link
        rel="stylesheet"
        href="https://codemirror.net/5/theme/ayu-dark.css"
    />

    <style>
        /**
         * CodeMirror sizes to its content by default, which lets a long template push the
         * drawer's own scroll around. Pinning it keeps the field a fixed box that scrolls
         * internally.
         */
        .CodeMirror {
            height: 320px;
            width: 100%;
            max-width: 100%;
            font-size: 12px;
        }

        /**
         * Line wrapping breaks at spaces, which a generated file name or a minified rule
         * does not have, so those are broken anywhere rather than scrolled to.
         */
        .CodeMirror pre.CodeMirror-line,
        .CodeMirror pre.CodeMirror-line-like {
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /**
         * With wrapping on there is nothing left to scroll to sideways.
         */
        .CodeMirror-hscrollbar {
            display: none !important;
        }
    </style>
@endPushOnce
