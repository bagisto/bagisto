<!-- Todays Details Vue Component -->
<v-services-content></v-services-content>

@pushOnce('scripts')
<script type="text/x-template" id="v-services-content-template">
    <div>
        <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
            <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                <div class="p-[16px] bg-white dark:bg-gray-900 rounded box-shadow">
                    <div class="flex gap-x-[10px] justify-between items-center">
                        <div class="flex flex-col gap-[4px]">
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                @lang('Services')
                            </p>
                            
                            <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                                @lang('Service related theme customization.')
                            </p>
                        </div>
        
                        <!-- Add Slider Button -->
                        <div class="flex gap-[10px]">
                            <div
                                class="secondary-button"
                                @click="$refs.addSliderModal.toggle()"
                            >
                                @lang('Add Services')
                            </div>
                        </div>
                    </div>

                    <template v-for="(deletedSlider, index) in deletedServices">
                        <input
                            type="hidden"
                            :name="'deleted_sliders['+ index +'][image]'"
                            :value="deletedSlider.image"
                        />
                    </template>

                    <div
                        class="grid pt-[16px]"
                        v-if="servicesContent.services.length"
                        v-for="(image, index) in servicesContent.services"
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
                            :name="'{{ $currentLocale->code }}[options]['+ index +'][description]'"
                            :value="image.description"
                        />

                        <input
                            type="hidden"
                            :name="'{{ $currentLocale->code }}[options]['+ index +'][image]'"
                            :value="image.image"
                        />
                    
                        <!-- Details -->
                        <div 
                            class="flex gap-[10px] justify-between py-5 cursor-pointer"
                            :class="{
                                'border-b-[1px] border-slate-300 dark:border-gray-800': index < servicesContent.services.length - 1
                            }"
                        >
                            <div class="flex gap-[10px]">
                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        <div> 
                                            @lang('Title'): 

                                            <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                @{{ image.title }}
                                            </span>
                                        </div>
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        <div> 
                                            @lang('Description'): 

                                            <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                @{{ image.description }}
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
                            <div class="grid gap-[4px] place-content-start text-right">
                                <div class="flex gap-x-[20px] items-center">
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
                        class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                        v-else
                    >
                        <img    
                            class="w-[120px] h-[120px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                            alt="add-product-to-store"
                        >
        
                        <div class="flex flex-col gap-[5px] items-center">
                            <p class="text-[16px] text-gray-400 font-semibold">
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
            <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                            @lang('admin::app.settings.themes.edit.general')
                        </p>
                    </x-slot:header>
                
                    <x-slot:content>
                        <input
                            type="hidden"
                            name="type"
                            value="services_content"
                        />

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.name')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="name"
                                value="{{ $theme->name }}"
                                rules="required"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                label="@lang('admin::app.settings.themes.edit.name')"
                                placeholder="@lang('admin::app.settings.themes.edit.name')"
                            ></v-field>

                            <x-admin::form.control-group.error
                                control-name="name"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.sort-order')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="sort_order"
                                rules="required|min_value:1"
                                value="{{ $theme->sort_order }}"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                label="@lang('admin::app.settings.themes.edit.sort-order')"
                                placeholder="@lang('admin::app.settings.themes.edit.sort-order')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="sort_order"
                            >
                            </x-admin::form.control-group.error>
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

                            <x-admin::form.control-group.error control-name="channel_id"></x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
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
                                    class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:ltr:left-[2px] after:rtl:right-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
                                    for="status"
                                ></label>
                            </label>

                            <x-admin::form.control-group.error
                                control-name="status"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                    </x-slot:content>
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
                    <x-slot:header>
                        <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                            @lang('Update Services')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('Title')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="{{ $currentLocale->code }}[title]"
                                    rules="required"
                                    :placeholder="trans('Title')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="{{ $currentLocale->code }}[title]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('Description')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="{{ $currentLocale->code }}[description]"
                                    :placeholder="trans('Description')"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('Services Image')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="image"
                                    name="slider_image"
                                    rules="required"
                                    :is-multiple="false"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="slider_image"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                @lang('Image resolution should be (40px X 40px)')
                            </p>
                        </div>
                    </x-slot:content>

                    <x-slot:footer>
                        <div class="flex gap-x-[10px] items-center">
                            <!-- Save Button -->
                            <button 
                                type="submit"
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.settings.themes.edit.save-btn')
                            </button>
                        </div>
                    </x-slot:footer>
                </x-admin::modal>
            </form>
        </x-admin::form>
    </div>
</script>

<script type="module">
    app.component('v-services-content', {
        template: '#v-services-content-template',

        props: ['errors'],

        data() {
            return {
                
                servicesContent: @json($theme->translate($currentLocale->code)['options'] ?? null),

                deletedServices: [],
            };
        },
        
        created() {
            if (
                this.servicesContent == null 
                || this.servicesContent.length == 0
            ) {
                this.servicesContent = { services: [] };
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

                    this.servicesContent.services.push({
                        title: formData.get("{{ $currentLocale->code }}[title]"),
                        description: formData.get("{{ $currentLocale->code }}[description]"),
                        slider_image: sliderImage,
                    });

                    if (sliderImage instanceof File) {
                        this.setFile(sliderImage, this.servicesContent.services.length - 1);
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
                this.deletedServices.push(image);
                
                this.servicesContent.services = this.servicesContent.services.filter(item => {
                    return (
                        item.title !== image.title || 
                        item.description !== image.description || 
                        item.image !== image.image
                    );
                });
            },
        },
    });
</script>
@endPushOnce    