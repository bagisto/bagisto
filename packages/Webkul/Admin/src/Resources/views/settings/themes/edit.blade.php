<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.themes.edit.title')
    </x-slot:title>
   
    <x-admin::form 
        :action="route('admin.theme.update', $theme->id)"
        enctype="multipart/form-data"
        v-slot="{ errors }"
    >
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.themes.edit.title')
            </p>
            
            <div class="flex gap-x-[10px] items-center">
                <button 
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.themes.edit.save-btn')
                </button>
            </div>
        </div>

        <v-theme-customizer :errors="errors"></v-theme-customizer>
    </x-admin::form>

    @pushOnce('scripts')
        {{-- Customizer parent --}}
        <script type="text/x-template" id="v-theme-customizer-template">
            <div>
                <div class="flex gap-[10px]">
                    <div 
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2 box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-slider-theme'}"
                        @click="switchComponent('v-slider-theme')"
                    >
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-slider-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_slider.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_slider.png') }}"
                            >
                
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold"> 
                                @lang('admin::app.settings.themes.edit.slider')
                            </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-product-theme'}"
                        @click="switchComponent('v-product-theme')"
                    >   
                        <div class="flex flex-col items-center max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-product-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/product_carousel.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_product_carousel.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold"> 
                                @lang('admin::app.settings.themes.edit.product-carousel')    
                            </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-category-theme'}"
                        @click="switchComponent('v-category-theme')"
                    >   
                        <div class="flex flex-col items-center max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-category-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_category.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_category.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold">
                                @lang('admin::app.settings.themes.edit.category-carousel')    
                            </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-static-theme'}"
                        @click="switchComponent('v-static-theme')"
                    >   
                        <div class="flex flex-col items-center max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-static-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_static_content.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_static_content.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold">
                                @lang('admin::app.settings.themes.edit.static-content')    
                            </p>
                        </div>
                    </div>
                
                    <div    
                        class="flex justify-center w-[180px] h-[180px] p-[16px] mt-[8px] bg-white rounded-[4px] border-solid border-2  box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1 cursor-pointer"
                        :class="{'border-blue-600': componentName == 'v-footer-link-theme'}"
                        @click="switchComponent('v-footer-link-theme')"
                    >   
                        <div class="max-w-[360px] p-[8px] rounded-[4px] transition-all hover:bg-gray-50">
                            <img
                                v-if="componentName == 'v-footer-link-theme'"
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/active_footer_link.png') }}"
                            >

                            <img
                                v-else
                                class="w-[80px] h-[80px]"
                                src="{{ bagisto_asset('images/inactive_footer_link.png') }}"
                            >
                
                            <p class="mb-[5px] text-[12px] top-[134px] text-center text-gray-800 font-semibold">
                                @lang('admin::app.settings.themes.edit.footer-link')    
                            </p>
                        </div>
                    </div>
                </div>

                <component
                    :errors="errors"
                    :is="componentName"
                >
                </component>
            </div>
        </script>

        <script type="module">
            app.component('v-theme-customizer', {
                template: '#v-theme-customizer-template',

                props: ['errors'],

                data() {
                    return {
                        componentName: 'v-slider-theme',

                        themeType: {
                            product_carousel: 'v-product-theme',
                            category_carousel: 'v-category-theme',
                            static_content: 'v-static-theme',
                            image_carousel: 'v-slider-theme',
                            footer_links: 'v-footer-link-theme',
                        } 
                    }
                },

                created(){
                    this.componentName = this.themeType["{{ $theme->type }}"];
                },

                methods: {
                    switchComponent(component) {
                        return;
                    }
                },
            })
        </script>

        {{-- Slider theme --}}
        <script type="text/x-template" id="v-slider-theme-template">
            <div>
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white rounded box-shadow">
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">@lang('admin::app.settings.themes.edit.slider')</p>
                                    
                                    <p class="text-[12px] text-gray-500 font-medium">
                                        @lang('admin::app.settings.themes.edit.slider-description')
                                    </p>
                                </div>
                
                                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        @click="$refs.addSliderModal.toggle()"
                                    >
                                        @lang('admin::app.settings.themes.edit.slider-add-btn')
                                    </div>
                                </div>
                            </div>

                            <template v-for="removeImage in removedImages">
                                <input type="text" class="hidden" :name="'removeImages[]'" :value="removeImage"/>    
                            </template>

                            <div
                                class="grid"
                                v-if="sliders.images.length"
                            >
                                <!-- Single product column -->
                                <div    
                                    class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300"
                                    v-for="(image, index) in sliders.images"
                                >
                                    <input type="file" class="hidden" :name="'options[]'" :ref="'imageInput_' + index"/>    

                                    <div class="flex items-center gap-[10px]">
                                        <div class="grid gap-[4px] content-center justify-items-center min-w-[60px] h-[60px] px-[6px] border border-dashed border-gray-300 rounded-[4px]">
                                            <img 
                                                :src="'../../../../' + image"
                                                :ref="'image_' + index"
                                                class="w-[20px]"
                                            >
                                            
                                            <p class="text-[6px] text-gray-400 font-semibold">
                                                @lang('admin::app.settings.themes.edit.slider-image')
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid gap-[4px] place-content-start">
                                        <div class="flex gap-[10px]">
                                            <p 
                                                class="text-red-600 cursor-pointer"
                                                @click="remove(image)"
                                            >
                                                @lang('admin::app.settings.themes.edit.delete')
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div    
                                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                                v-else
                            >
                                <img    
                                    class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]"
                                    src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                    alt="add-product-to-store"
                                >
                
                                <div class="flex flex-col items-center">
                                    <p class="text-[16px] text-gray-400 font-semibold">
                                        @lang('admin::app.settings.themes.edit.slider-add-btn')
                                    </p>
                                    
                                    <p class="text-gray-400">
                                        @lang('admin::app.settings.themes.edit.slider-description')
                                    </p>
                                </div>
                
                                <div class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                    @lang('admin::app.settings.themes.edit.slider-add-btn')
                                </div>
                            </div>
                        </div>
                    </div>
                
                        <!-- General -->
                        <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.settings.themes.edit.general')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <input type="hidden" name="type" value="image_carousel">
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.themes.edit.name')
                                    </x-admin::form.control-group.label>
    
                                    <v-field
                                        type="text"
                                        name="name"
                                        value="{{ $theme->name }}"
                                        rules="required"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
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
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.themes.edit.sort-order')
                                    </x-admin::form.control-group.label>
    
                                    <v-field
                                        type="text"
                                        name="sort_order"
                                        rules="required"
                                        value="{{ $theme->sort_order }}"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
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
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.themes.edit.status')
                                    </x-admin::form.control-group.label>
    
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <v-field
                                            type="checkbox"
                                            name="status"
                                            class="hidden"
                                            v-slot="{ field }"
                                        >
                                            <input
                                                type="checkbox"
                                                name="status"
                                                id="status"
                                                value="{{ $theme->status }}"
                                                class="sr-only peer"
                                                v-bind="field"
                                            />
                                        </v-field>
                            
                                        <label
                                            class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
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
                    >
                        <x-admin::modal ref="addSliderModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    
                                    @lang('admin::app.settings.themes.edit.update-slider')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.slider-image')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="image"
                                            name="slider_image"
                                            :label="trans('admin::app.catalog.categories.create.add-logo')"
                                            :is-multiple="false"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="title"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
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
            app.component('v-slider-theme', {
                template: '#v-slider-theme-template',

                props: ['errors'],

                data() {
                    return {
                        sliders: @json($theme->options),

                        removedImages: [],
                    };
                },

                created(){
                    console.log(this.sliders);  
                },
                methods: {
                    saveSliderImage(params) {
                        this.sliders.images.push(params);

                        if (params.slider_image instanceof File) {
                            this.setFile(params, this.sliders.images.length - 1);
                        }

                        this.$refs.addSliderModal.toggle();
                    },

                    setFile(event, index) {
                        let dataTransfer = new DataTransfer();

                        dataTransfer.items.add(event.slider_image);

                        setTimeout(() => {
                            this.$refs['image_' + index][0].src =  URL.createObjectURL(event.slider_image);

                            this.$refs['imageInput_' + index][0].files = dataTransfer.files;
                        }, 0);
                    },

                    remove(image) {
                        this.removedImages.push(image);

                        let index = this.sliders.images.indexOf(image);

                        this.sliders.images.splice(image, 1);
                    }
                }
            });
        </script>

        {{-- Product Theme --}}
        <script type="text/x-template" id="v-product-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.edit.product-carousel')
                                </p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    @lang('admin::app.settings.themes.edit.product-carousel-description')
                                </p>
                            </div>
            
                            <div class="flex gap-[10px]">
                                <div
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.productFilterModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.edit.add-filter-btn')
                                </div>

                                <button
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    type="submit"
                                >
                                    @lang('admin::app.settings.themes.edit.save-btn')
                                </button>
                            </div>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.themes.edit.filter-title')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[title]"
                                :value="options.title"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[title]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.edit.filter-title')"
                                placeholder="@lang('admin::app.settings.themes.edit.filter-title')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[title]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.themes.edit.sort')
                            </x-admin::form.control-group.label>

                            <v-field
                                name="options[sort]"
                                v-slot="{ field }"
                                rules="required"
                                :value="options.sort"
                                label="@lang('admin::app.settings.themes.edit.sort')"
                            >
                                <select
                                    name="options[sort]"
                                    v-bind="field"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['options[sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                >
                                    <option value="" selected disabled>@lang('admin::app.settings.themes.edit.select')</option>
                                    <option value="desc">@lang('admin::app.settings.themes.edit.desc')</option>
                                    <option value="asc">@lang('admin::app.settings.themes.edit.asc')</option>
                                </select>
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[sort]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.themes.edit.limit')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[limit]"
                                :value="options.limit"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.edit.limit')"
                                placeholder="@lang('admin::app.settings.themes.edit.limit')"
                            >
                            </v-field>
                            
                            <x-admin::form.control-group.error
                                control-name="options[limit]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div v-if="options.filters.length">
                            <div
                                class="flex gap-[10px] justify-between p-[16px] !pl-0 border-b-[1px] border-slate-300"
                                v-for="filter in options.filters"
                            >
                                <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 

                                <!-- Information -->
                                <div class="flex gap-[10px]">
                                    <!-- Details -->
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-[16x] text-gray-800 font-semibold">
                                            @{{ "@lang('admin::app.settings.themes.edit.key')".replace(':key', filter.key) }}
                                        </p>

                                        <p class="text-[16x] text-gray-800 font-semibold">
                                            @{{ "@lang('admin::app.settings.themes.edit.value')".replace(':value', filter.value) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-[4px] place-content-start text-right">
                                    <p
                                        class="text-red-600 cursor-pointer hover:underline"
                                        @click="remove(filter)"
                                    >
                                        @lang('admin::app.settings.themes.edit.delete')
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div    
                            class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                            v-else
                        >
                            <img
                                class="w-[120px] h-[120px] p-2 border border-dashed border-gray-300 rounded-[4px]"
                                src="{{ bagisto_asset('images/empty_theme.png') }}"
                                alt="add-product-to-store"
                            >
            
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">
                                    @lang('admin::app.settings.themes.edit.product-carousel')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.settings.themes.edit.product-carousel-description')
                                </p>
                            </div>

                            <div 
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.productFilterModal.toggle()"
                            >
                                @lang('admin::app.settings.themes.edit.add-filter-btn')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.edit.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="product_carousel">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    label="@lang('admin::app.settings.themes.edit.name')"
                                    placeholder="@lang('admin::app.settings.themes.edit.name')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    value="{{ $theme->sort_order }}"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
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
                                <x-admin::form.control-group.label>
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
                                        />
                                    </v-field>
                        
                                    <label
                                        class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
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

                <!-- For Fitler Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addFilter)">
                        <x-admin::modal ref="productFilterModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.settings.themes.edit.create-filter')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.key-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="key"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.key-input')"
                                            :placeholder="trans('admin::app.settings.themes.edit.key-input')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="key"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Value -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.value-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="value"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.value-input')"
                                            :placeholder="trans('admin::app.settings.themes.edit.value-input')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="value"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
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
            app.component('v-product-theme', {
                template: '#v-product-theme-template',

                props: ['errors'],

                data() {
                    return {
                        options: @json($theme->options),
                    };
                },

                created() {
                    if (! this.options.filters) {
                        this.options.filters = {};
                    }

                    this.options.filters = Object.keys(this.options.filters).map(key => ({
                        key: key,
                        value: this.options.filters[key]
                    }));
                },

                methods: {
                    addFilter(params) {
                        this.options.filters.push(params);

                        this.$refs.productFilterModal.toggle();
                    },

                    remove(filter) {
                        let index = this.options.filters.indexOf(filter);

                        this.options.filters.splice(index, 1);
                    }
                }
            });
        </script>

        {{-- Category Theme --}}
        <script type="text/x-template" id="v-category-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.edit.category-carousel')
                                </p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    @lang('admin::app.settings.themes.edit.category-carousel-description')
                                </p>
                            </div>
            
                            <div class="flex gap-[10px]">
                                <div
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.categoryFilterModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.edit.add-filter-btn')
                                </div>
                            </div>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.themes.edit.filter-title')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[title]"
                                :value="options.title"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[title]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.edit.filter-title')"
                                placeholder="@lang('admin::app.settings.themes.edit.filter-title')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[title]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.themes.edit.sort')
                            </x-admin::form.control-group.label>

                            <v-field
                                name="options[sort]"
                                :value="options.sort"
                                v-slot="{ field }"
                                rules="required"
                                label="@lang('admin::app.settings.themes.edit.sort')"
                            >
                                <select
                                    name="options[sort]"
                                    v-bind="field"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['options[sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                >
                                    <option value="" selected disabled>@lang('admin::app.settings.themes.edit.select')</option>
                                    <option value="desc">@lang('admin::app.settings.themes.edit.desc')</option>
                                    <option value="asc">@lang('admin::app.settings.themes.edit.asc')</option>
                                </select>
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[sort]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.themes.edit.limit')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[limit]"
                                :value="options.limit"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.edit.limit')"
                                placeholder="@lang('admin::app.settings.themes.edit.limit')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[limit]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <div v-if="options.filters.length">
                            <div
                                class="flex gap-[10px] justify-between p-[16px] !pl-0 border-b-[1px] border-slate-300"
                                v-for="filter in options.filters"
                            >
                                <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 

                                <!-- Information -->
                                <div class="flex gap-[10px]">
                                    <!-- Details -->
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-[16x] text-gray-800 font-semibold">
                                            @{{ "@lang('admin::app.settings.themes.edit.key')".replace(':key', filter.key) }}
                                        </p>
    
                                        <p class="text-[16x] text-gray-800 font-semibold">
                                            @{{ "@lang('admin::app.settings.themes.edit.value')".replace(':value', filter.value) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-[4px] place-content-start text-right">
                                    <p
                                        class="text-red-600 cursor-pointer hover:underline"
                                        @click="remove(filter)"
                                    >
                                        @lang('admin::app.settings.themes.edit.delete')
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div    
                            class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                            v-else
                        >
                            <img
                                class="w-[120px] h-[120px] p-2 border border-dashed border-gray-300 rounded-[4px]"
                                src="{{ bagisto_asset('images/empty_theme.png') }}"
                                alt="add-category-to-store"
                            >
            
                            <div class="flex flex-col items-center">
                                <p class="text-[16px] text-gray-400 font-semibold">
                                    @lang('admin::app.settings.themes.edit.category-carousel')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.settings.themes.edit.category-carousel-description')

                                </p>
                            </div>
            
                            <div 
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.categoryFilterModal.toggle()"
                            >
                                @lang('admin::app.settings.themes.edit.add-filter-btn')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.edit.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="category_carousel">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('admin::app.settings.themes.edit.name')"
                                    placeholder="@lang('admin::app.settings.themes.edit.name')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    value="{{ $theme->sort_order }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
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
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.status')
                                </x-admin::form.control-group.label>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        value="{{ $theme->sort_order }}"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            id="status"
                                            class="sr-only peer"
                                            v-bind="field"
                                            :checked="{{ $theme->sort_order }}"
                                        />
                                    </v-field>
                        
                                    <label
                                        class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
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
    
                <!-- For Fitler Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addFilter)">
                        <x-admin::modal ref="categoryFilterModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.settings.themes.edit.create-filter')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.key-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="key"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.key-input')"
                                            :placeholder="trans('admin::app.settings.themes.edit.key-input')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="key"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
    
                                    <!-- Value -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.value-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="value"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.value-input')"
                                            :placeholder="trans('admin::app.settings.themes.edit.value-input')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="value"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
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
            app.component('v-category-theme', {
                template: '#v-category-theme-template',

                props: ['errors'],

                data() {
                    return {
                        options: @json($theme->options),
                    };
                },
    
                created() {
                    if (! this.options.filters) {
                        this.options.filters = {};
                    }

                    this.options.filters = Object.keys(this.options.filters).map(key => ({
                        key: key,
                        value: this.options.filters[key]
                    }));
                },
                
                methods: {
                    addFilter(params) {
                        this.options.filters.push(params);
    
                        this.$refs.categoryFilterModal.toggle();
                    },
    
                    remove(filter) {
                        let index = this.options.filters.indexOf(filter);
    
                        this.options.filters.splice(index, 1);
                    }
                }
            });
        </script>

        {{-- Static Theme --}}
        <script type="text/x-template" id="v-static-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.edit.static-content')
                                </p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    @lang('admin::app.settings.themes.edit.static-content-description')
                                </p>
                            </div>
            
                            <div class="flex gap-[10px]">
                                <button
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    type="submit"
                                >
                                    @lang('admin::app.settings.themes.edit.save-btn')
                                </button>
                            </div>
                        </div>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label for="status">
                                @lang('admin::app.settings.themes.edit.html')
                            </x-admin::form.control-group.label>

                            <div
                                class="max-w-[870px]"
                                ref="html"
                            >
                            </div>

                            <v-field
                                type="hidden"
                                name="options[html]"
                                v-model="options.html"
                            >
                            </v-field>

                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.settings.themes.edit.css')
                            </x-admin::form.control-group.label>

                            <div
                                class="max-w-[870px]"
                                ref="css"
                            >
                            </div>

                            <v-field
                                type="hidden"
                                name="options[css]"
                                v-model="options.css"
                            >
                            </v-field>
                        </x-admin::form.control-group>
                    </div>
                </div>

                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.edit.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="static_content">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('admin::app.settings.themes.edit.name')"
                                    placeholder="@lang('admin::app.settings.themes.edit.name')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    value="{{ $theme->sort_order }}"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
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
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.settings.themes.edit.status')
                                </x-admin::form.control-group.label>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        value="{{ $theme->sort_order }}"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            id="status"
                                            class="sr-only peer"
                                            v-bind="field"
                                            :checked="{{ $theme->sort_order }}"
                                        />
                                    </v-field>
                        
                                    <label
                                        class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
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
        </script>

        <script type="module">
            app.component('v-static-theme', {
                template: '#v-static-theme-template',

                props: ['erros'],

                data() {
                    return {
                        options: @json($theme->options),
                    };
                },

                created() {
                    this.initHtmlEditor();

                    this.initCssEditor();
                },

                methods: {
                    initHtmlEditor() {
                        setTimeout(() => {
                            this._html = new CodeMirror(this.$refs.html, {
                                lineNumbers: true,
                                tabSize: 2,
                                value: this.options.html,
                                mode: 'html',
                                theme: 'monokai'
                            });

                            this._html.on('changes', () => {
                                this.options.html = this._html.getValue();
                            });
                        }, 0);
                    },

                    initCssEditor() {
                        setTimeout(() => {
                            this._css = new CodeMirror(this.$refs.css, {
                                lineNumbers: true,
                                tabSize: 2,
                                value: this.options.css,
                                mode: 'css',
                                theme: 'monokai'
                            });

                            this._css.on('changes', () => {
                                this.options.css = this._css.getValue();
                            });
                        }, 0);
                    },
                },
            });
        </script>

        {{-- Footer Link --}}
        <script type="text/x-template" id="v-footer-link-theme-template">
            <div>
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white rounded box-shadow">
                            <!-- Add Links-->
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">
                                        @lang('admin::app.settings.themes.edit.footer-link')
                                    </p>
    
                                    <p class="text-[12px] text-gray-500 font-medium">
                                        @lang('admin::app.settings.themes.edit.footer-link-description')
                                    </p>
                                </div>
                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        @click="isUpdating=false;$refs.addLinksModal.toggle()"
                                    >
                                        @lang('admin::app.settings.themes.edit.add-links')
                                    </div>
                                </div>
                            </div>
    
                            <div
                                v-if="Object.keys(footerLinks).length"
                                v-for="(footerLink, index) in footerLinks"
                            >
                                <!-- Information -->
                                <div 
                                    class="grid"
                                    v-for="(link, key) in footerLink"
                                >
                                    <!-- Hidden Input -->
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +']'" :value="link.column"> 
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +'][url]'" :value="link.url"> 
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +'][title]'" :value="link.title"> 
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +'][sort_order]'" :value="link.sort_order"> 
                                
                                    <div class="flex gap-[10px] justify-between border-b-[1px] py-5 border-slate-300 cursor-pointer">
                                        <div class="flex gap-[10px]"><!-- Drag Icon -->
                                            <div class="grid gap-[6px] place-content-start">
                                                <p class="text-gray-600">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.column'): 

                                                        <span class="text-gray-600 transition-all">
                                                            @{{ link.column }}
                                                        </span>
                                                    </div>
                                                </p>
    
                                                <p class="text-gray-600">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.url'):

                                                        <a
                                                            :href="link.url"
                                                            target="_blank"
                                                            class="text-blue-600 transition-all hover:underline"
                                                            v-text="link.url"
                                                        >
                                                        </a>
                                                    </div>
                                                </p>

                                                <p class="text-gray-600">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.filter-title'):

                                                        <span
                                                            class="text-gray-600 transition-all"
                                                            v-text="link.title"
                                                        >
                                                        </span>
                                                    </div>
                                                </p>

                                                <p class="text-gray-600">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.sort-order'):

                                                        <span
                                                            class="text-gray-600 transition-all"
                                                            v-text="link.sort_order"
                                                        >
                                                        </span>
                                                    </div>
                                                </p>
                                            </div>
                                        </div>
    
                                        <!-- Actions -->
                                        <div class="grid gap-[4px] place-content-start text-right">
                                            <div class="flex gap-x-[20px] items-center">
                                                <p 
                                                    class="text-blue-600 cursor-pointer transition-all hover:underline"
                                                    @click="edit(link)"
                                                > 
                                                    @lang('admin::app.settings.themes.edit.edit')
                                                </p>

                                                <p 
                                                    class="text-red-600 cursor-pointer transition-all hover:underline"
                                                    @click="remove(link)"
                                                > 
                                                    @lang('admin::app.settings.themes.edit.delete')
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div   
                                v-else
                                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                            >
                                <img
                                    class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]"
                                    src="{{ bagisto_asset('images/empty_theme.png') }}"
                                    alt="add-product-to-store"
                                >
                
                                <div class="flex flex-col items-center">
                                    <p class="text-[16px] text-gray-400 font-semibold">
                                        @lang('admin::app.settings.themes.edit.footer-link')
                                        
                                    </p>
    
                                    <p class="text-gray-400">
                                        @lang('admin::app.settings.themes.edit.footer-link-description')
                                    </p>
                                </div>
                
                                <div class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer">
                                    @lang('admin::app.settings.themes.edit.add-footer-link-btn')
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- General -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.settings.themes.edit.general')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <input type="hidden" name="type" value="footer_links">

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.themes.edit.name')
                                    </x-admin::form.control-group.label>
    
                                    <v-field
                                        type="text"
                                        name="name"
                                        value="{{ $theme->name }}"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                        :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required"
                                        label="@lang('admin::app.settings.themes.edit.name')"
                                        placeholder="@lang('admin::app.settings.themes.edit.name')"
                                    >
                                    </v-field>
    
                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.themes.edit.sort-order')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="sort_order"
                                        value="{{ $theme->sort_order }}"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                        :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required"
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
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.themes.edit.status')
                                    </x-admin::form.control-group.label>
    
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <v-field
                                            type="checkbox"
                                            name="status"
                                            class="hidden"
                                            v-slot="{ field }"
                                        >
                                            <input
                                                type="checkbox"
                                                name="status"
                                                id="status"
                                                value="{{ $theme->status }}"
                                                class="sr-only peer"
                                                v-bind="field"
                                            />
                                        </v-field>
                            
                                        <label
                                            class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
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

                <!-- For Fitler Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="footerLinkUpdateOrCreateModal"
                >
                    <form @submit="handleSubmit($event, updateOrCreate)">
                        <x-admin::modal ref="addLinksModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.settings.themes.edit.create-filter')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.column')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="column"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.column')"
                                            :placeholder="trans('admin::app.settings.themes.edit.column')"
                                            ::disabled="isUpdating"
                                        >
                                            <option value="column_1">1</option>
                                            <option value="column_2">2</option>
                                            <option value="column_3">3</option>
                                            <option value="column_4">4</option>
                                            <option value="column_5">5</option>
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="column"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
        
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.title')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="title"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.title')"
                                            :placeholder="trans('admin::app.settings.themes.edit.title')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="title"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
        
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.url')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="url"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.url')"
                                            :placeholder="trans('admin::app.settings.themes.edit.url')"
                                            ::disabled="isUpdating"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="url"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.sort-order')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="sort_order"
                                            rules="required|numeric"
                                            :label="trans('admin::app.settings.themes.edit.sort-order')"
                                            :placeholder="trans('admin::app.settings.themes.edit.sort-order')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="sort_order"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
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
            app.component('v-footer-link-theme', {
                template: '#v-footer-link-theme-template',

                props: ['errors'],

                data() {
                    return {
                        footerLinks: @json($theme->options),

                        isUpdating: false,
                    };
                },

                mounted() {
                    Object.keys(this.footerLinks).forEach(key => {
                        this.footerLinks[key] = this.footerLinks[key].map(item => ({
                            ...item,
                            column: key
                        }));
                    });
                },

                methods: {
                    updateOrCreate(params) {
                        let updatedFooterLinks = this.footerLinks[params.column].map((item) => {
                            if (item.url === params.url) {
                                return params;
                            }

                            return item;
                        });

                        this.footerLinks[params.column] = updatedFooterLinks;

                        if (! updatedFooterLinks.some((item) => item.url === params.url)) {
                            if (!this.footerLinks.hasOwnProperty(params.column)) {
                                this.footerLinks[params.column] = []; 
                            }
                            
                            this.footerLinks[params.column].push(params);
                        }

                        this.$refs.addLinksModal.toggle();
                    },

                    remove(footerLink) {
                        if (
                            this.footerLinks.hasOwnProperty(footerLink.column) &&
                            Array.isArray(this.footerLinks[footerLink.column]) &&
                            this.footerLinks[footerLink.column].length > 0
                        ) {
                            this.footerLinks[footerLink.column].splice(0, 1);
                        }
                    },

                    edit(footerLink) {
                        this.isUpdating = true;

                        this.$refs.footerLinkUpdateOrCreateModal.setValues(footerLink);

                        this.$refs.addLinksModal.toggle();
                    },
                }
            });
        </script>

        {{-- Code mirror script CDN --}}
        <script
            type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"
        >
        </script>
    @endPushOnce

    @pushOnce('styles')
        {{-- Code mirror style cdn --}}
        <link 
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css"
        >
        </link>
    @endPushOnce
</x-admin::layouts>