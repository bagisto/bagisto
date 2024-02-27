<!-- Image-Carousel Component -->
<v-image-carousel></v-image-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-image-carousel-template"
    >
        <div>
            <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
                <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                    <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                        <div class="flex gap-x-2.5 justify-between items-center">
                            <div class="flex flex-col gap-1">
                                <p class="text-base text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.slider')
                                </p>
                                
                                <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                                    @lang('admin::app.settings.themes.edit.slider-description')
                                </p>
                            </div>
            
                            <!-- Add Slider Button -->
                            <div class="flex gap-2.5">
                                <div
                                    class="secondary-button"
                                    @click="$refs.addSliderModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.edit.slider-add-btn')
                                </div>
                            </div>
                        </div>

                        <template v-for="(deletedSlider, index) in deletedSliders">
                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[deleted_sliders]['+ index +'][image]'"
                                :value="deletedSlider.image"
                            />
                        </template>

                        <div
                            class="grid pt-4"
                            v-if="sliders.images.length"
                            v-for="(image, index) in sliders.images"
                        >
                            <!-- Hidden Input -->
                            <input
                                type="file"
                                class="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][image]'"
                                :ref="'imageInput_' + index"
                            />

                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][title]'"
                                :value="image.title"
                            />

                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][link]'"
                                :value="image.link"
                            />

                            <input
                                type="hidden"
                                :name="'{{ $currentLocale->code }}[options]['+ index +'][image]'"
                                :value="image.image"
                            />
                        
                            <!-- Details -->
                            <div 
                                class="flex gap-2.5 justify-between py-5 cursor-pointer"
                                :class="{
                                    'border-b border-slate-300 dark:border-gray-800': index < sliders.images.length - 1
                                }"
                            >
                                <div class="flex gap-2.5">
                                    <div class="grid gap-1.5 place-content-start">
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div> 
                                                @lang('admin::app.settings.themes.edit.image-title'): 

                                                <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                    @{{ image.title }}
                                                </span>
                                            </div>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div> 
                                                @lang('admin::app.settings.themes.edit.link'): 

                                                <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                    @{{ image.link }}
                                                </span>
                                            </div>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div class="flex justify-between"> 
                                                @lang('admin::app.settings.themes.edit.image'): 

                                                <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                    <a
                                                        :href="'{{ config('app.url') }}/' + image.image"
                                                        :ref="'image_' + index"
                                                        target="_blank"
                                                        class="ltr:ml-2 rtl:mr-2 text-blue-600 transition-all hover:underline"
                                                    >
                                                        <span 
                                                            :ref="'imageName_' + index"
                                                            v-text="image.image"
                                                        ></span>
                                                    </a>
                                                </span>
                                            </div>
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="grid gap-1 place-content-start text-right">
                                    <div class="flex gap-x-5 items-center">
                                        <p 
                                            class="text-red-600 cursor-pointer transition-all hover:underline"
                                            @click="remove(image)"
                                        > 
                                            @lang('admin::app.settings.themes.edit.delete')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty Page -->
                        <div    
                            class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                            v-else
                        >
                            <img
                                class="w-[120px] h-[120px] p-2 dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/empty-placeholders/default.svg') }}"
                                alt="@lang('admin::app.settings.themes.edit.slider')"
                            >
            
                            <div class="flex flex-col gap-1.5 items-center">
                                <p class="text-base text-gray-400 font-semibold">
                                    @lang('admin::app.settings.themes.edit.slider-add-btn')
                                </p>
                                
                                <p class="text-gray-400">
                                    @lang('admin::app.settings.themes.edit.slider-description')
                                </p>
                            </div>
                        </div>
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
                            <input
                                type="hidden"
                                name="type"
                                value="image_carousel"
                            />

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    label="@lang('admin::app.settings.themes.edit.name')"
                                    placeholder="@lang('admin::app.settings.themes.edit.name')"
                                ></v-field>

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    rules="required|min_value:1"
                                    value="{{ $theme->sort_order }}"
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
                                        :value="{{ $theme->status }}"
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

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form 
                    @submit="handleSubmit($event, saveSliderImage)"
                    enctype="multipart/form-data"
                    ref="createSliderForm"
                >
                    <x-admin::modal ref="addSliderModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.settings.themes.edit.update-slider')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.image-title')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="{{ $currentLocale->code }}[title]"
                                    rules="required"
                                    :placeholder="trans('admin::app.settings.themes.edit.image-title')"
                                    :label="trans('admin::app.settings.themes.edit.image-title')"
                                />

                                <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[title]" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.link')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="{{ $currentLocale->code }}[link]"
                                    :placeholder="trans('admin::app.settings.themes.edit.link')"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.slider-image')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="image"
                                    name="slider_image"
                                    rules="required"
                                    :is-multiple="false"
                                />

                                <x-admin::form.control-group.error control-name="slider_image" />
                            </x-admin::form.control-group>

                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                @lang('admin::app.settings.themes.edit.image-size')
                            </p>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <div class="flex gap-x-2.5 items-center">
                                <button 
                                    type="submit"
                                    class="px-3 py-1.5 bg-blue-600 border border-blue-700 rounded-md text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.settings.themes.edit.save-btn')
                                </button>
                            </div>
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-image-carousel', {
            template: '#v-image-carousel-template',

            props: ['errors'],

            data() {
                return {
                    sliders: @json($theme->translate($currentLocale->code)['options'] ?? null),

                    deletedSliders: [],
                };
            },
            
            created() {
                if (
                    this.sliders == null 
                    || this.sliders.length == 0
                ) {
                    this.sliders = { images: [] };
                }   
            },

            methods: {
                saveSliderImage(params, { resetForm ,setErrors }) {
                    let formData = new FormData(this.$refs.createSliderForm);

                    try {
                        const sliderImage = formData.get("slider_image[]");

                        if (! sliderImage) {
                            throw new Error("{{ trans('admin::app.settings.themes.edit.slider-required') }}");
                        }

                        this.sliders.images.push({
                            title: formData.get("{{ $currentLocale->code }}[title]"),
                            link: formData.get("{{ $currentLocale->code }}[link]"),
                            slider_image: sliderImage,
                        });

                        if (sliderImage instanceof File) {
                            this.setFile(sliderImage, this.sliders.images.length - 1);
                        }

                        resetForm();

                        this.$refs.addSliderModal.toggle();
                    } catch (error) {
                        setErrors({'slider_image': [error.message]});
                    }
                },

                setFile(file, index) {
                    let dataTransfer = new DataTransfer();

                    dataTransfer.items.add(file);

                    setTimeout(() => {
                        this.$refs['image_' + index][0].href =  URL.createObjectURL(file);

                        this.$refs['imageName_' + index][0].innerHTML = file.name;

                        this.$refs['imageInput_' + index][0].files = dataTransfer.files;
                    }, 0);
                },

                remove(image) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.deletedSliders.push(image);
                    
                            this.sliders.images = this.sliders.images.filter(item => {
                                return (
                                    item.title !== image.title || 
                                    item.link !== image.link || 
                                    item.image !== image.image
                                );
                            });
                        }
                    });
                },
            },
        });
    </script>
@endPushOnce    