<v-tinymce {{ $attributes }}></v-tinymce>

@pushOnce('scripts')
    <!--
        TODO (@devansh-webkul): Only this portion is pending; it just needs to be integrated using the Vite bundler. Currently,
        there is an issue with relative paths in the plugins. I intend to address this task at the end.
    -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.2/tinymce.min.js"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    ></script>

    <script
        type="text/x-template"
        id="v-tinymce-template"
    >
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, generate)">
                <!-- AI Content Generation Modal -->
                <x-admin::modal ref="magicAIModal">
                    <!-- Modal Header -->
                    <x-slot:header>
                        <p class="flex items-center gap-2.5 text-lg font-bold text-gray-800 dark:text-white">
                            <span class="icon-magic text-2xl text-gray-800"></span>

                            @lang('admin::app.components.tinymce.ai-generation.title')
                        </p>
                    </x-slot>

                    <!-- Modal Content -->
                    <x-slot:content>
                        <!-- LLM Model -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.components.tinymce.ai-generation.model')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="model"
                                rules="required"
                                v-model="ai.model"
                                :label="trans('admin::app.components.tinymce.ai-generation.model')"
                            >
                                <option value="gpt-3.5-turbo">
                                    @lang('admin::app.components.tinymce.ai-generation.gpt-3-5-turbo')
                                </option>

                                <option value="llama2">
                                    @lang('admin::app.components.tinymce.ai-generation.llama2')
                                </option>

                                <option value="mistral">
                                    @lang('admin::app.components.tinymce.ai-generation.mistral')
                                </option>

                                <option value="dolphin-phi">
                                    @lang('admin::app.components.tinymce.ai-generation.dolphin-phi')
                                </option>

                                <option value="phi">
                                    @lang('admin::app.components.tinymce.ai-generation.phi')
                                </option>

                                <option value="starling-lm">
                                    @lang('admin::app.components.tinymce.ai-generation.starling-lm')
                                </option>

                                <option value="llama2-uncensored">
                                    @lang('admin::app.components.tinymce.ai-generation.llama2-uncensored')
                                </option>

                                <option value="llama2:13b">
                                    @lang('admin::app.components.tinymce.ai-generation.llama2:13b')
                                </option>

                                <option value="llama2:70b">
                                    @lang('admin::app.components.tinymce.ai-generation.llama2:70b')
                                </option>

                                <option value="orca-mini">
                                    @lang('admin::app.components.tinymce.ai-generation.orca-mini')
                                </option>

                                <option value="vicuna">
                                    @lang('admin::app.components.tinymce.ai-generation.vicuna')
                                </option>

                                <option value="llava">
                                    @lang('admin::app.components.tinymce.ai-generation.llava')
                                </option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="model"></x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <!-- Prompt -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.components.tinymce.ai-generation.prompt')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                class="h-[180px]"
                                name="prompt"
                                rules="required"
                                v-model="ai.prompt"
                                :label="trans('admin::app.components.tinymce.ai-generation.prompt')"
                            />

                            <x-admin::form.control-group.error control-name="prompt" />
                        </x-admin::form.control-group>

                        <!-- Modal Submission -->
                        <div class="flex items-center gap-x-2.5">
                            <button
                                type="submit"
                                class="secondary-button"
                            >
                                <!-- Spinner -->
                                <template v-if="isLoading">
                                    <img
                                        class="h-5 w-5 animate-spin"
                                        src="{{ bagisto_asset('images/spinner.svg') }}"
                                    />

                                    @lang('admin::app.components.tinymce.ai-generation.generating')
                                </template>

                                <template v-else>
                                    <span class="icon-magic text-2xl text-blue-600"></span>

                                    @lang('admin::app.components.tinymce.ai-generation.generate')
                                </template>
                            </button>
                        </div>

                        <!-- Generated Content -->
                        <x-admin::form.control-group class="mt-5">
                            <x-admin::form.control-group.label class="text-left">
                                @lang('admin::app.components.tinymce.ai-generation.generated-content')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                class="h-[180px]"
                                name="content"
                                v-model="ai.content"
                            />

                            <span class="text-xs text-gray-500">
                                @lang('admin::app.components.tinymce.ai-generation.generated-content-info')
                            </span>
                        </x-admin::form.control-group>
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <div class="flex items-center gap-x-2.5">
                            <button
                                type="button"
                                class="primary-button"
                                :disabled="!ai.content"
                                @click="apply"
                            >
                                @lang('admin::app.components.tinymce.ai-generation.apply')
                            </button>
                        </div>
                    </x-slot>
                </x-admin::modal>
            </form>
        </x-admin::form>
    </script>

    <script type="module">
        app.component('v-tinymce', {
            template: '#v-tinymce-template',
                
            props: ['selector', 'field', 'prompt'],

            data() {
                return {
                    currentSkin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',

                    currentContentCSS: document.documentElement.classList.contains('dark') ? 'dark' : 'default',

                    isLoading: false,

                    ai: {
                        enabled: Boolean("{{ core()->getConfigData('general.magic_ai.settings.enabled') && core()->getConfigData('general.magic_ai.content_generation.enabled') }}"),

                        model: null,

                        prompt: null,

                        content: null,
                    },
                };
            },

            mounted() {
                this.init();

                this.$emitter.on('change-theme', (theme) => {
                    tinymce.activeEditor.destroy();

                    this.currentSkin = (theme === 'dark') ? 'oxide-dark' : 'oxide';
                    this.currentContentCSS = (theme === 'dark') ? 'dark' : 'default';

                    this.init();
                });
            },

            methods: {
                init() {
                    let self = this;

                    let tinyMCEHelper = {
                        initTinyMCE: function(extraConfiguration) {
                            let self2 = this;

                            let config = {  
                                relative_urls: false,
                                menubar: false,
                                remove_script_host: false,
                                document_base_url: '{{ asset('/') }}',
                                uploadRoute: '{{ route('admin.tinymce.upload') }}',
                                csrfToken: '{{ csrf_token() }}',
                                ...extraConfiguration,
                                skin: self.currentSkin,
                                content_css: self.currentContentCSS,
                            };

                            const image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
                                self2.uploadImageHandler(config, blobInfo, resolve, reject, progress);
                            });

                            tinymce.init({
                                ...config,

                                file_picker_callback: function(cb, value, meta) {
                                    self2.filePickerCallback(config, cb, value, meta);
                                },

                                images_upload_handler: image_upload_handler,
                            });
                        },

                        filePickerCallback: function(config, cb, value, meta) {
                            let input = document.createElement('input');
                            input.setAttribute('type', 'file');
                            input.setAttribute('accept', 'image/*');

                            input.onchange = function() {
                                let file = this.files[0];

                                let reader = new FileReader();
                                reader.readAsDataURL(file);
                                reader.onload = function() {
                                    let id = 'blobid' + new Date().getTime();
                                    let blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                    let base64 = reader.result.split(',')[1];
                                    let blobInfo = blobCache.create(id, file, base64);

                                    blobCache.add(blobInfo);

                                    cb(blobInfo.blobUri(), {
                                        title: file.name
                                    });
                                };
                            };

                            input.click();
                        },

                        uploadImageHandler: function(config, blobInfo, resolve, reject, progress) {
                            let xhr, formData;

                            xhr = new XMLHttpRequest();

                            xhr.withCredentials = false;

                            xhr.open('POST', config.uploadRoute);

                            xhr.upload.onprogress = ((e) => progress((e.loaded / e.total) * 100));

                            xhr.onload = function() {
                                let json;

                                if (xhr.status === 403) {
                                    reject("@lang('admin::app.error.tinymce.http-error')", {
                                        remove: true
                                    });

                                    return;
                                }

                                if (xhr.status < 200 || xhr.status >= 300) {
                                    reject("@lang('admin::app.error.tinymce.http-error')");

                                    return;
                                }

                                json = JSON.parse(xhr.responseText);

                                if (! json || typeof json.location != 'string') {
                                    reject("@lang('admin::app.error.tinymce.invalid-json')" + xhr.responseText);

                                    return;
                                }

                                resolve(json.location);
                            };

                            xhr.onerror = (()=>reject("@lang('admin::app.error.tinymce.upload-failed')"));

                            formData = new FormData();
                            formData.append('_token', config.csrfToken);
                            formData.append('file', blobInfo.blob(), blobInfo.filename());

                            xhr.send(formData);
                        },
                    };

                    tinyMCEHelper.initTinyMCE({
                        selector: this.selector,
                        plugins: 'image media wordcount save fullscreen code table lists link',
                        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor image alignleft aligncenter alignright alignjustify | link hr |numlist bullist outdent indent  | removeformat | code | table | aibutton',
                        image_advtab: true,
                        directionality : "{{ core()->getCurrentLocale()->direction }}",

                        setup: editor => {
                            editor.ui.registry.addIcon('magic', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <g clip-path="url(#clip0_3148_2242)"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.1484 9.31989L9.31995 12.1483L19.9265 22.7549L22.755 19.9265L12.1484 9.31989ZM12.1484 10.7341L10.7342 12.1483L13.5626 14.9767L14.9768 13.5625L12.1484 10.7341Z" fill="#2563EB"/> <path d="M11.0877 3.30949L13.5625 4.44748L16.0374 3.30949L14.8994 5.78436L16.0374 8.25924L13.5625 7.12124L11.0877 8.25924L12.2257 5.78436L11.0877 3.30949Z" fill="#2563EB"/> <path d="M2.39219 2.39217L5.78438 3.95197L9.17656 2.39217L7.61677 5.78436L9.17656 9.17655L5.78438 7.61676L2.39219 9.17655L3.95198 5.78436L2.39219 2.39217Z" fill="#2563EB"/> <path d="M3.30947 11.0877L5.78434 12.2257L8.25922 11.0877L7.12122 13.5626L8.25922 16.0374L5.78434 14.8994L3.30947 16.0374L4.44746 13.5626L3.30947 11.0877Z" fill="#2563EB"/> </g> <defs> <clipPath id="clip0_3148_2242"> <rect width="24" height="24" fill="white"/> </clipPath> </defs> </svg>');

                            editor.ui.registry.addButton('aibutton', {
                                text: "@lang('admin::app.components.tinymce.ai-btn-tile')",
                                icon: 'magic',
                                enabled: self.ai.enabled,

                                onAction: function () {
                                    self.ai = {
                                        prompt: self.prompt,

                                        content: null,
                                    };

                                    self.$refs.magicAIModal.toggle()
                                }
                            });

                            editor.on('keyup', () => {
                                this.field.onInput(editor.getContent());
                            });
                        },
                    });
                },

                generate(params, { resetForm, resetField, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.magic_ai.content') }}", {
                        prompt: params['prompt'],
                        model: params['model']
                    })
                        .then(response => {
                            this.isLoading = false;

                            this.ai.content = response.data.content;
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            } else {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            }
                        });
                },

                apply() {
                    if (! this.ai.content) {
                        return;
                    }

                    tinymce.get(this.selector.replace('textarea#', '')).setContent(this.ai.content.replace(/\r?\n/g, '<br />'))

                    this.field.onInput(this.ai.content.replace(/\r?\n/g, '<br />'));

                    this.$refs.magicAIModal.close();
                },
            },
        })
    </script>
@endPushOnce