@php
    use Illuminate\Database\Eloquent\Model;
    use Webkul\MagicAI\AiProvider;

    $enabledProviders = array_filter(explode(',', core()->getConfigData('magic_ai.admin_features.image_generation.providers') ?? ''));
    
    $models = AiProvider::modelsForProviders($enabledProviders, 'image');
    
    $defaultModel = $models[0]['value'] ?? '';
@endphp

@props([
    'name'             => 'images',
    'allowMultiple'    => false,
    'showPlaceholders' => false,
    'uploadedImages'   => [],
    'width'            => '120px',
    'height'           => '120px',
    'enableSeo'        => false,
    'metaName'         => '',
])

@php
    $uploadedImages = collect($uploadedImages)->map(function ($image) {
        if (! $image instanceof Model) {
            return $image;
        }

        $data = $image->toArray();

        if (in_array('alt_text', $image->translatedAttributes ?? [])) {
            $data['alt_text'] = $image->translate(core()->getRequestedLocaleCode())?->alt_text;
        }

        return $data;
    })->values()->all();
@endphp

<v-media-images
    name="{{ $name }}"
    v-bind:allow-multiple="{{ $allowMultiple ? 'true' : 'false' }}"
    v-bind:show-placeholders="{{ $showPlaceholders ? 'true' : 'false' }}"
    v-bind:enable-seo="{{ $enableSeo ? 'true' : 'false' }}"
    meta-name="{{ $metaName }}"
    :uploaded-images="@js($uploadedImages)"
    width="{{ $width }}"
    height="{{ $height }}"
    :errors="errors"
>
    <x-admin::shimmer.image class="h-[110px] w-[110px] rounded" />
</v-media-images>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-media-images-template"
    >
        <!-- Panel Content -->
        <div class="grid">
            <div class="flex flex-wrap gap-1">
                <!-- Upload Image Button -->
                <template v-if="allowMultiple || images.length == 0">
                    <!-- AI Image Generation Button -->
                    <label
                        class="grid h-[120px] max-h-[120px] min-h-[110px] w-full min-w-[110px] max-w-[120px] cursor-pointer items-center justify-items-center rounded border border-dashed border-blue-300 transition-all hover:border-blue-600 dark:mix-blend-exclusion dark:invert"
                        :style="{'max-width': this.width, 'max-height': this.height}"
                        v-if="ai.enabled"
                        @click="resetAIModal(); $refs.magicAIImageModal.open()"
                    >
                        <div class="flex flex-col items-center">
                            <span class="icon-magic text-2xl text-blue-600"></span>

                            <p class="grid text-center text-sm font-semibold text-blue-600">
                                @lang('admin::app.components.media.images.ai-add-image-btn')
                                
                                <span class="text-xs">
                                    @lang('admin::app.components.media.images.ai-btn-info')
                                </span>
                            </p>
                        </div>
                    </label>

                    <!-- Upload Image Button -->
                    <label
                        class="grid h-[120px] max-h-[120px] min-h-[110px] w-full min-w-[110px] max-w-[120px] cursor-pointer items-center justify-items-center rounded border border-dashed border-gray-300 transition-all hover:border-gray-400 dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                        :class="[(errors?.['images.files[0]'] ?? false) ? 'border border-red-500' : 'border-gray-300']"
                        :style="{'max-width': this.width, 'max-height': this.height}"
                        :for="$.uid + '_imageInput'"
                    >
                        <div class="flex flex-col items-center">
                            <span class="icon-image text-2xl"></span>

                            <p class="grid text-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                                @lang('admin::app.components.media.images.add-image-btn')
                                
                                <span class="text-xs">
                                    @lang('admin::app.components.media.images.allowed-types')
                                </span>
                            </p>

                            <input
                                type="file"
                                class="hidden"
                                :id="$.uid + '_imageInput'"
                                accept="image/*"
                                :multiple="allowMultiple"
                                :ref="$.uid + '_imageInput'"
                                @change="add"
                            />
                        </div>
                    </label>
                </template>

                <!-- Uploaded Images -->
                <draggable
                    class="flex flex-wrap gap-1"
                    ghost-class="draggable-ghost"
                    v-bind="{animation: 200}"
                    :list="images"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <v-media-image-item
                            :name="name"
                            :index="index"
                            :image="element"
                            :width="width"
                            :height="height"
                            :enable-seo="enableSeo"
                            :meta-name="metaName"
                            @onRemove="remove($event)"
                        >
                        </v-media-image-item>
                    </template>
                </draggable>

                <!-- Placeholders -->
                <template v-if="showPlaceholders && ! images.length">
                    <!-- Front Placeholder -->
                    <div
                        class="relative h-[120px] max-h-[120px] w-full min-w-[120px] max-w-[120px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert"
                        v-for="placeholder in placeholders"
                    >
                        <img :src="placeholder.image">

                        <p class="absolute bottom-4 w-full text-center text-xs font-semibold text-gray-400">
                            @{{ placeholder.label }}
                        </p>
                    </div>
                </template>

                <!-- Use Teleport to move the modal to the body. -->
                <Teleport to="body">
                    <x-admin::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form @submit="handleSubmit($event, generate)">
                            <!-- AI Content Generation Modal -->
                            <x-admin::modal
                                ref="magicAIImageModal"
                                class="[&>*]:z-[10007]"
                            >
                                <!-- Modal Header -->
                                <x-slot:header>
                                    <template v-if="! ai.images.length">
                                        <p class="flex items-center gap-2.5 text-lg font-bold text-gray-800 dark:text-white">
                                            <span class="icon-magic text-2xl text-gray-800"></span>

                                            @lang('admin::app.components.media.images.ai-generation.title')
                                        </p>
                                    </template>

                                    <template v-else>
                                        <p class="truncate text-lg font-bold text-gray-800 dark:text-white">
                                            <span
                                                class="icon-arrow-right mr-1 cursor-pointer align-middle text-2xl hover:rounded-md hover:bg-gray-100 dark:hover:bg-gray-950"
                                                @click="ai.images = []"
                                            ></span>

                                            <span class="align-middle">
                                                @{{ ai.prompt }}
                                            </span>
                                        </p>
                                    </template>
                                </x-slot>

                                <!-- Modal Content -->
                                <x-slot:content>
                                    <div v-show="! ai.images.length">
                                        <!-- Prompt -->
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.components.media.images.ai-generation.prompt')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="textarea"
                                                name="prompt"
                                                rules="required"
                                                v-model="ai.prompt"
                                                :label="trans('admin::app.components.media.images.ai-generation.prompt')"
                                            />

                                            <x-admin::form.control-group.error control-name="prompt" />
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.components.media.images.ai-generation.number-of-images')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="n"
                                                rules="required"
                                                v-model="ai.n"
                                                :label="trans('admin::app.components.media.images.ai-generation.number-of-images')"
                                            />

                                            <x-admin::form.control-group.error control-name="n" />
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.components.media.images.ai-generation.size')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="size"
                                                rules="required"
                                                v-model="ai.size"
                                                :label="trans('admin::app.components.media.images.ai-generation.size')"
                                            >
                                                <option value="1:1">
                                                    @lang('admin::app.components.media.images.ai-generation.square')
                                                </option>

                                                <option value="2:3">
                                                    @lang('admin::app.components.media.images.ai-generation.portrait')
                                                </option>

                                                <option value="3:2">
                                                    @lang('admin::app.components.media.images.ai-generation.landscape')
                                                </option>
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="size" />
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label class="required">
                                                @lang('admin::app.components.media.images.ai-generation.quality')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="quality"
                                                rules="required"
                                                v-model="ai.quality"
                                                :label="trans('admin::app.components.media.images.ai-generation.quality')"
                                            >
                                                <option value="high">
                                                    @lang('admin::app.components.media.images.ai-generation.high')
                                                </option>

                                                <option value="medium">
                                                    @lang('admin::app.components.media.images.ai-generation.medium')
                                                </option>

                                                <option value="low">
                                                    @lang('admin::app.components.media.images.ai-generation.low')
                                                </option>
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="quality" />
                                        </x-admin::form.control-group>

                                        <!-- Model Select -->
                                        <x-admin::form.control-group v-if="ai.models && ai.models.length">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.components.media.images.ai-generation.model')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="model"
                                                v-model="ai.model"
                                                :label="trans('admin::app.components.media.images.ai-generation.model')"
                                            >
                                                <option
                                                    v-for="option in ai.models"
                                                    :key="option.value"
                                                    :value="option.value"
                                                    v-text="option.title"
                                                ></option>
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error control-name="model" />
                                        </x-admin::form.control-group>
                                    </div>

                                    <div v-show="ai.images.length">
                                        <div class="grid grid-cols-4 gap-5">
                                            <div
                                                class="relative grid max-h-[120px] min-w-[120px] cursor-pointer justify-items-center overflow-hidden rounded border-[3px] border-transparent transition-all hover:opacity-80"
                                                :class="{'!border-blue-600': image.selected}"
                                                v-for="image in ai.images"
                                                @click="image.selected = ! image.selected"
                                            >
                                                <!-- Image Preview -->
                                                <img
                                                    class="h-[120px] w-[120px]"
                                                    :src="image.url"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </x-slot>

                                <!-- Modal Footer -->
                                <x-slot:footer>
                                    <div class="flex items-center gap-x-2.5">
                                        <template v-if="! ai.images.length">
                                            <button class="secondary-button">
                                                <!-- Spinner -->
                                                <template v-if="isLoading">
                                                    <img
                                                        class="h-5 w-5 animate-spin"
                                                        src="{{ bagisto_asset('images/spinner.svg') }}"
                                                    />

                                                    @lang('admin::app.components.media.images.ai-generation.generating')
                                                </template>

                                                <template v-else>
                                                    <span class="icon-magic text-blue-600"></span>
                                                    
                                                    @lang('admin::app.components.media.images.ai-generation.generate')
                                                </template>
                                            </button>
                                        </template>

                                        <template v-else>
                                            <button class="secondary-button">
                                                <!-- Spinner -->
                                                <template v-if="isLoading">
                                                    <img
                                                        class="h-5 w-5 animate-spin"
                                                        src="{{ bagisto_asset('images/spinner.svg') }}"
                                                    />

                                                    @lang('admin::app.components.media.images.ai-generation.regenerating')
                                                </template>

                                                <template v-else>
                                                    <span class="icon-magic text-2xl text-blue-600"></span>
                                                    
                                                    @lang('admin::app.components.media.images.ai-generation.regenerate')
                                                </template>
                                            </button>

                                            <x-admin::button
                                                button-type="button"
                                                class="primary-button"
                                                :title="trans('admin::app.components.media.images.ai-generation.apply')"
                                                ::disabled="! selectedAIImages.length"
                                                @click="apply"
                                            />
                                        </template>
                                    </div>
                                </x-slot>
                            </x-admin::modal>
                        </form>
                    </x-admin::form>
                </Teleport>
            </div>
        </div>  
    </script>

    <script type="text/x-template" id="v-media-image-item-template">
        <div class="group relative grid max-h-[120px] min-w-[120px] justify-items-center overflow-hidden rounded transition-all hover:border-gray-400">
            <!-- Image Preview -->
            <img
                :src="image.url"
                :style="{'width': this.width, 'height': this.height}"
            />

            <div class="invisible absolute bottom-0 top-0 flex w-full flex-col justify-between bg-white p-3 opacity-80 transition-all group-hover:visible dark:bg-gray-900">
                <!-- Image Name -->
                <p class="break-all text-xs font-semibold text-gray-600 dark:text-gray-300">
                    @{{ image.file_name }}
                </p>

                <!-- Actions -->
                <div class="flex justify-between">
                    <span
                        class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200 dark:hover:bg-gray-800"
                        @click="remove"
                    ></span>

                    <!-- Opens the seo drawer, where replacing the file is one of the options -->
                    <span
                        class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200 dark:hover:bg-gray-800"
                        v-if="enableSeo"
                        @click="openSeoDrawer"
                    ></span>

                    <label
                        class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-200 dark:hover:bg-gray-800"
                        v-else
                        :for="$.uid + '_imageInput_' + index"
                    ></label>

                    <input
                        type="hidden"
                        :name="name + '[' + image.id + ']'"
                        v-if="! image.is_new"
                    />

                    <input
                        type="file"
                        :name="enableSeo ? name + '[' + image.id + ']' : name + '[]'"
                        class="hidden"
                        accept="image/*"
                        :id="$.uid + '_imageInput_' + index"
                        :ref="$.uid + '_imageInput_' + index"
                        @change="edit"
                    />
                </div>
            </div>

            <!--
                Kept outside of the drawer, which is only rendered while open, so that the
                metadata is submitted with the form whether the drawer was opened or not.
            -->
            <template v-if="enableSeo && metaName">
                <input
                    type="hidden"
                    :name="metaName + '[' + image.id + '][alt_text]'"
                    :value="image.alt_text ?? ''"
                />

                <input
                    type="hidden"
                    :name="metaName + '[' + image.id + '][file_name]'"
                    :value="image.file_name ?? ''"
                />
            </template>

            <!-- Image SEO Drawer -->
            <x-admin::drawer
                ref="seoDrawer"
                v-if="enableSeo"
            >
                <x-slot:header>
                    <p class="text-lg font-bold text-gray-800 dark:text-white">
                        @lang('admin::app.components.media.images.seo.title')
                    </p>

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                        @lang('admin::app.components.media.images.seo.info')
                    </p>
                </x-slot>

                <x-slot:content>
                    <!-- Preview -->
                    <div class="mb-4 flex justify-center rounded border border-gray-200 p-4 dark:border-gray-800">
                        <img
                            class="max-h-[180px] max-w-full rounded"
                            :src="image.url"
                        />
                    </div>

                    <!-- Alt Text -->
                    <div class="mb-4">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.components.media.images.seo.alt-text')
                        </x-admin::form.control-group.label>

                        <input
                            type="text"
                            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                            :placeholder="@js(trans('admin::app.components.media.images.seo.alt-text-placeholder'))"
                            v-model="image.alt_text"
                        />

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">
                            @lang('admin::app.components.media.images.seo.alt-text-info')
                        </p>
                    </div>

                    <!-- File Name -->
                    <div class="mb-4">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.components.media.images.seo.file-name')
                        </x-admin::form.control-group.label>

                        <input
                            type="text"
                            class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                            :placeholder="@js(trans('admin::app.components.media.images.seo.file-name-placeholder'))"
                            v-model="image.file_name"
                        />

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">
                            @lang('admin::app.components.media.images.seo.file-name-info')
                        </p>
                    </div>

                    <!-- Replace Image -->
                    <div>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.components.media.images.seo.replace')
                        </x-admin::form.control-group.label>

                        <label
                            class="secondary-button inline-flex cursor-pointer"
                            :for="$.uid + '_imageInput_' + index"
                        >
                            @lang('admin::app.components.media.images.seo.replace-btn')
                        </label>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">
                            @lang('admin::app.components.media.images.seo.replace-info')
                        </p>
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
        app.component('v-media-images', {
            template: '#v-media-images-template',

            props: {
                name: {
                    type: String, 
                    default: 'images',
                },

                allowMultiple: {
                    type: Boolean,
                    default: false,
                },

                showPlaceholders: {
                    type: Boolean,
                    default: false,
                },

                uploadedImages: {
                    type: Array,
                    default: () => []
                },

                width: {
                    type: String,
                    default: '120px'
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

                errors: {
                    type: Object,
                    default: () => {}
                }
            },

            data() {
                return {
                    images: [],

                    newImageIndex: 0,

                    placeholders: [
                        {
                            label: "@lang('admin::app.components.media.images.placeholders.front')",
                            image: "{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                        }, {
                            label: "@lang('admin::app.components.media.images.placeholders.next')",
                            image: "{{ bagisto_asset('images/product-placeholders/next-1.svg') }}"
                        }, {
                            label: "@lang('admin::app.components.media.images.placeholders.next')",
                            image: "{{ bagisto_asset('images/product-placeholders/next-2.svg') }}"
                        }, {
                            label: "@lang('admin::app.components.media.images.placeholders.zoom')",
                            image: "{{ bagisto_asset('images/product-placeholders/zoom.svg') }}"
                        }, {
                            label: "@lang('admin::app.components.media.images.placeholders.use-cases')",
                            image: "{{ bagisto_asset('images/product-placeholders/use-cases.svg') }}"
                        }, {
                            label: "@lang('admin::app.components.media.images.placeholders.size')",
                            image: "{{ bagisto_asset('images/product-placeholders/size.svg') }}"
                        }
                    ],

                    isLoading: false,

                    ai: {
                        enabled: Boolean("{{ core()->getConfigData('magic_ai.general.settings.enabled') && core()->getConfigData('magic_ai.admin_features.image_generation.enabled') }}"),

                        models: {!! json_encode($models) !!},

                        model: "{{ $defaultModel }}",

                        prompt: null,

                        n: 1,

                        size: '1:1',

                        quality: 'medium',

                        images: [],
                    },
                }
            },

            computed: {
                selectedAIImages() {
                    return this.ai.images.filter(image => image.selected);
                }
            },

            mounted() {
                this.images = this.uploadedImages;
            },

            methods: {
                add() {
                    let imageInput = this.$refs[this.$.uid + '_imageInput'];

                    if (imageInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(imageInput.files).every(file => file.type.includes('image/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('admin::app.components.media.images.not-allowed-error')"
                        });

                        return;
                    }

                    imageInput.files.forEach((file, index) => {
                        this.images.push(this.newImage(file, file.name.replace(/\.[^/.]+$/, '')));
                    });
                },

                newImage(file, fileName) {
                    return {
                        id: 'image_' + this.newImageIndex++,
                        url: '',
                        alt_text: '',
                        file_name: fileName,
                        file: file,
                    };
                },

                remove(image) {
                    let index = this.images.indexOf(image);

                    this.images.splice(index, 1);
                },

                generate(params, { setErrors }) {
                    this.isLoading = true;

                    let self = this;

                    this.$axios.post("{{ route('admin.magic_ai.image') }}", params)
                        .then(response => {
                            this.isLoading = false;

                            self.ai.images = response.data.images;
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
                    this.selectedAIImages.forEach((image, index) => {
                        this.images.push(this.newImage(this.getBase64ToFile(image.url, 'temp.png'), ''));
                    });

                    this.$refs.magicAIImageModal.close();
                },

                getBase64ToFile(base64, filename) {
                    var arr = base64.split(','),
                        mime = arr[0].match(/:(.*?);/)[1],
                        bstr = atob(arr[arr.length - 1]), 
                        n = bstr.length, 
                        u8arr = new Uint8Array(n);

                    while (n--) {
                        u8arr[n] = bstr.charCodeAt(n);
                    }

                    return new File([u8arr], filename, {type:mime});
                },

                resetAIModal() {
                    this.ai = {
                        enabled: Boolean("{{ core()->getConfigData('magic_ai.general.settings.enabled') && core()->getConfigData('magic_ai.admin_features.image_generation.enabled') }}"),

                        models: {!! json_encode($models) !!},

                        model: "{{ $defaultModel }}",

                        prompt: null,

                        n: 1,

                        size: '1:1',

                        quality: 'medium',

                        images: [],
                    };
                }
            }
        });

        app.component('v-media-image-item', {
            template: '#v-media-image-item-template',

            props: ['index', 'image', 'name', 'width', 'height', 'enableSeo', 'metaName'],

            mounted() {
                if (this.image.file instanceof File) {
                    this.setFile(this.image.file);

                    this.readFile(this.image.file);
                }
            },

            methods: {
                openSeoDrawer() {
                    this.$refs.seoDrawer.open();
                },

                edit() {
                    let imageInput = this.$refs[this.$.uid + '_imageInput_' + this.index];

                    if (imageInput.files == undefined) {
                        return;
                    }

                    const validFiles = Array.from(imageInput.files).every(file => file.type.includes('image/'));

                    if (! validFiles) {
                        this.$emitter.emit('add-flash', {
                            type: 'warning',
                            message: "@lang('admin::app.components.media.images.not-allowed-error')"
                        });

                        return;
                    }

                    if (! this.image.file_name) {
                        this.image.file_name = imageInput.files[0].name.replace(/\.[^/.]+$/, '');
                    }

                    this.setFile(imageInput.files[0]);

                    this.readFile(imageInput.files[0]);
                },

                remove() {
                    this.$emit('onRemove', this.image)
                },

                setFile(file) {
                    this.image.is_new = 1;

                    const dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);

                    this.$refs[this.$.uid + '_imageInput_' + this.index].files = dataTransfer.files;
                },

                readFile(file) {
                    let reader = new FileReader();

                    reader.onload = (e) => {
                        this.image.url = e.target.result;
                    }

                    reader.readAsDataURL(file);
                },
            }
        });
    </script>
@endPushOnce
