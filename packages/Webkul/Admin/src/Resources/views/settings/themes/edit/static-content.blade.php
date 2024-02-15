<!-- Static-Content Component -->
<v-static-content></v-static-content>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-static-content-template"
    >
        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <div class="flex flex-col gap-2 flex-1 min-w-[931px] max-xl:flex-auto">
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <div class="flex gap-x-2.5 justify-between items-center mb-2.5">
                        <div class="flex flex-col gap-1">
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @lang('admin::app.settings.themes.edit.static-content')
                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                                @lang('admin::app.settings.themes.edit.static-content-description')
                            </p>
                        </div>

                        <div
                            class="flex gap-2.5"
                            v-if="isHtmlEditorActive"
                        >
                            <!-- Hidden Input Filed for upload images -->
                            <label
                                class="secondary-button"
                                for="static_image"
                            >
                                @lang('admin::app.settings.themes.edit.add-image-btn')
                            </label>

                            <input 
                                type="file"
                                name="static_image"
                                id="static_image"
                                class="hidden"
                                accept="image/*"
                                ref="static_image"
                                label="Image"
                                @change="storeImage($event)"
                            >
                        </div>
                    </div>
                    
                    <div class="text-sm font-medium text-center pt-4 text-gray-500">
                        <div class="tabs">
                            <div class="flex gap-4 mb-4 pt-2 border-b-2 max-sm:hidden">
                                <!-- HTML Tab Header -->
                                <p @click="switchEditor('v-html-editor-theme', 1)">
                                    <div
                                        class="transition pb-3.5 px-2.5 text-base  font-medium text-gray-600 dark:text-gray-300 cursor-pointer"
                                        :class="{'-mb-px border-b-2 border-blue-600': inittialEditor == 'v-html-editor-theme'}"
                                    >
                                        @lang('admin::app.settings.themes.edit.html')
                                    </div>
                                </p>

                                <!-- CSS Tab Editor -->
                                <p @click="switchEditor('v-css-editor-theme', 0);">
                                    <div
                                        class="transition pb-3.5 px-2.5 text-base  font-medium text-gray-600 dark:text-gray-300 cursor-pointer"
                                        :class="{'-mb-px border-b-2 border-blue-600': inittialEditor == 'v-css-editor-theme'}"
                                    >
                                        @lang('admin::app.settings.themes.edit.css')
                                    </div>
                                </p>

                                <!-- Preview Tab Editor -->
                                <p @click="switchEditor('v-static-content-previewer', 0);">
                                    <div
                                        class="transition pb-3.5 px-2.5 text-base  font-medium text-gray-600 dark:text-gray-300 cursor-pointer"
                                        :class="{'-mb-px border-b-2 border-blue-600': inittialEditor == 'v-static-content-previewer'}"
                                    >
                                        @lang('admin::app.settings.themes.edit.preview')
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>

                    <input
                        type="hidden"
                        name="{{ $currentLocale->code }}[options][html]"
                        v-model="options.html"
                    />

                    <input
                        type="hidden"
                        name="{{ $currentLocale->code }}[options][css]"
                        v-model="options.css"
                    />

                    <KeepAlive class="dark:bg-white">
                        <component 
                            :is="inittialEditor"
                            ref="editor"
                            @editor-data="editorData"
                            :options="options"
                        >
                        </component>
                    </KeepAlive>
                </div>
            </div>

            <!-- General -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                            @lang('admin::app.settings.themes.edit.general')
                        </p>
                    </x-slot>
                
                    <x-slot:content>
                        <input type="hidden" name="type" value="static_content">

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.name')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="name"
                                value="{{ $theme->name }}"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.edit.name')"
                                placeholder="@lang('admin::app.settings.themes.edit.name')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.sort-order')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="sort_order"
                                value="{{ $theme->sort_order }}"
                                rules="required|min_value:1"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                label="@lang('admin::app.settings.themes.edit.sort-order')"
                                placeholder="@lang('admin::app.settings.themes.edit.sort-order')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error control-name="sort_order" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.channels')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="channel_id"
                                rules="required"
                                :value="$theme->channel_id"
                            >
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach 
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="channel_id" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="!mb-0">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.status')
                            </x-admin::form.control-group.label>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <v-field
                                    type="checkbox"
                                    name="status"
                                    class="hidden"
                                    v-slot="{ field }"
                                    value="{{ $theme->status }}"
                                >
                                    <input
                                        type="checkbox"
                                        name="status"
                                        id="status"
                                        class="sr-only peer"
                                        v-bind="field"
                                        :checked="{{ $theme->status }}"
                                    />
                                </v-field>
                    
                                <label
                                    class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-9 h-5 bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-0.5 after:ltr:left-0.5 after:rtl:right-0.5 peer-focus:outline-none after:border after:rounded-full after:h-4 after:w-4 after:transition-all"
                                    for="status"
                                ></label>
                            </label>
                            
                            <x-admin::form.control-group.error control-name="status" />
                        </x-admin::form.control-group>
                    </x-slot>
                </x-admin::accordion>
            </div>
        </div>
    </script>

    <!-- Html Editor Template -->
    <script type="text/x-template" id="v-html-editor-theme-template">
        <div>
            <div ref="html"></div>
        </div>
    </script>

    <!-- Css Editor Template -->
    <script type="text/x-template" id="v-css-editor-theme-template">
        <div>
            <div ref="css"></div>
        </div>
    </script>

    <!-- Static Content Previewer -->
    <script type="text/x-template" id="v-static-content-previewer-template">
        <div>   
            <div v-html="getPreviewContent()"></div>
        </div>
    </script>

    <script type="module">
        app.component('v-static-content', {
            template: '#v-static-content-template',

            props: ['errors'],

            data() {
                return {
                    inittialEditor: 'v-html-editor-theme',

                    options: @json($theme->translate($currentLocale->code)['options'] ?? null),

                    isHtmlEditorActive: true,
                };
            },

            created() {
                if (this.options === null) {
                    this.options = { html: {} };
                }   
            },

            methods: {
                switchEditor(editor, isActive) {
                    this.inittialEditor = editor;

                    this.isHtmlEditorActive = isActive;

                    if (editor == 'v-static-content-previewer') {
                        this.$refs.editor.review = this.options;
                    }
                },

                editorData(value) {
                    if (value.html) {
                        this.options.html = value.html;
                    } else {
                        this.options.css = value.css;
                    } 
                },

                storeImage($event) {
                    let imageInput = this.$refs.static_image;

                    if (imageInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(imageInput.files).every(file => file.type.includes('image/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('admin::app.settings.themes.edit.image-upload-message')"
                        });

                        imageInput.value = '';

                        return;
                    }

                    imageInput.files.forEach((file, index) => {
                        this.$refs.editor.storeImage($event);
                    });
                },
            },
        });
    </script>

    <!-- Html Editor Component -->
    <script type="module">
        app.component('v-html-editor-theme', {
            template: '#v-html-editor-theme-template',
            
            data() {
                return {
                    options:{
                        html: `{!! $theme->translate($currentLocale->code)['options']['html'] ?? '' !!}`,
                    },

                    cursorPointer: {},
                };
            },

            created() {
                this.initHtmlEditor();

                this.$emitter.on('change-theme', (theme) => this._html.setOption('theme', (theme === 'dark') ? 'ayu-dark' : 'default'));
            },

            methods: {
                initHtmlEditor() {
                    this.$nextTick(() => {
                        this.options.html = SimplyBeautiful().html(this.options.html);

                        this._html = new CodeMirror(this.$refs.html, {
                            lineNumbers: true,
                            tabSize: 4,
                            lineWiseCopyCut: true,
                            value: this.options.html,
                            mode: 'htmlmixed',
                            theme: document.documentElement.classList.contains('dark') ? 'ayu-dark' : 'default',
                        });

                        this._html.on('changes', (e) => {
                            this.options.html = this._html.getValue();

                            this.cursorPointer = e.getCursor();

                            this.$emit('editorData', this.options);
                        });
                    });
                },

                storeImage($event) {
                    let selectedImage = $event.target.files[0];

                    if (! selectedImage) {
                        return;
                    }

                    const allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

                    if (! allowedImageTypes.includes(selectedImage.type)) {
                        return;
                    }

                    let formData = new FormData();

                    formData.append('{{ $currentLocale->code }}[options][][image]', selectedImage);
                    formData.append('id', "{{ $theme->id }}");
                    formData.append('type', "static_content");

                    this.$axios.post('{{ route('admin.settings.themes.store') }}', formData)
                        .then((response) => {
                            let editor = this._html.getDoc();

                            let cursorPointer = editor.getCursor();

                            editor.replaceRange(`<img class="lazy" data-src="${response.data}">`, {
                                line: cursorPointer.line, ch: cursorPointer.ch
                            });

                            editor.setCursor({
                                line: cursorPointer.line, ch: cursorPointer.ch + response.data.length
                            });
                        })
                        .catch((error) => {
                            if (error.response.status == 422) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            }
                        });
                },
            },
        });
    </script>
    
    <!-- Css Editor Component -->
    <script type="module">
        app.component('v-css-editor-theme', {
            template: '#v-css-editor-theme-template',

            data() {
                return {
                    options:{
                        css: `{!! $theme->translate($currentLocale->code)['options']['css'] ?? '' !!}`,
                    }
                };
            },

            created() {
                this.initCssEditor();

                this.$emitter.on('change-theme', (theme) => this._css.setOption('theme', (theme === 'dark') ? 'ayu-dark' : 'default'));
            },

            methods: {
                initCssEditor() {
                    this.$nextTick(() => {
                        this.options.css = SimplyBeautiful().css(this.options.css);

                        this._css = new CodeMirror(this.$refs.css, {
                            lineNumbers: true,
                            tabSize: 4,
                            lineWiseCopyCut: true,
                            value: this.options.css,
                            mode: 'css',
                            theme: document.documentElement.classList.contains('dark') ? 'ayu-dark' : 'default',
                        });

                        this._css.on('changes', () => {
                            this.options.css = this._css.getValue();

                            this.$emit('editorData', this.options);
                        });
                    });
                },
            },
        });
    </script>
    
    <!-- Static Content Previewer -->
    <script type="module">
        app.component('v-static-content-previewer', {
            template: '#v-static-content-previewer-template',

            props: ['options'],

            methods: {
                getPreviewContent() {
                    let html = this.options.html.slice();

                    html = html.replaceAll('src=""', '').replaceAll('data-src', 'src').replaceAll('src="storage/theme/', "src=\"{{ config('app.url') }}/storage/theme/");

                    return html + '<style type=\"text/css\">' +   this.options.css + '</style>';
                },
            },
        });
    </script>

         <!-- Code mirror script CDN -->
         <script
         type="text/javascript"
         src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"
     >
     </script>

     <!-- 
         Html mixed and xml cnd both are dependent 
         Used for html and css theme
     -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>

     <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/css/css.js"></script>

     <!-- Beatutify html and css -->
     <script src="https://cdn.jsdelivr.net/npm/simply-beautiful@latest/dist/index.min.js"></script>
@endPushOnce

@pushOnce('styles')
    <!-- Code mirror style cdn -->
    <link 
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css"
    >
    </link>

    <!-- Dark theme css -->
    <link rel="stylesheet" href="https://codemirror.net/5/theme/ayu-dark.css">
@endPushOnce