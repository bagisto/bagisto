<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.themes.create.title')
    </x-slot:title>

    <x-admin::form 
        :action="route('admin.theme.store')"
        enctype="multipart/form-data"
        v-slot="{ errors }"
    >
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.themes.create.title')
            </p>
            
            <div class="flex gap-x-[10px] items-center">
                <div class="flex gap-x-[10px] items-center">
                    <a 
                        href="{{ route('admin.theme.index') }}"
                        class="transparent-button hover:bg-gray-200"
                    > 
                        @lang('admin::app.settings.themes.create.back')
                    </a>
                </div>

                <button 
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.themes.create.save-btn')
                </button>
            </div>
        </div>

        <v-theme-customizer :errors="errors"></v-theme-customizer>
    </x-admin::form>

    @pushOnce('scripts')
        {{-- Customizer parent Template --}}
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
                                @lang('admin::app.settings.themes.create.sliders')
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
                                @lang('admin::app.settings.themes.create.product-carousel')    
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
                                @lang('admin::app.settings.themes.create.category-carousel')    
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
                                @lang('admin::app.settings.themes.create.static-content')    
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
                                @lang('admin::app.settings.themes.create.footer-link')    
                            </p>
                        </div>
                    </div>
                </div>

                <component 
                    :is="componentName"
                    :errors="errors"
                >
                </component>
            </div>
        </script>

        {{-- Slider theme Template --}}
        <script type="text/x-template" id="v-slider-theme-template">
            <div>
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white rounded box-shadow">
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">@lang('admin::app.settings.themes.create.sliders')</p>
                                    <p class="text-[12px] text-gray-500 font-medium">
                                        @lang('admin::app.settings.themes.create.slider-description')
                                    </p>
                                </div>
                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        @click="$refs.addSliderModal.toggle()"
                                    >
                                        @lang('admin::app.settings.themes.create.slider-add-btn')
                                    </div>
                                </div>
                            </div>
                
                            <div
                                class="grid"
                                v-if="sliders.length"
                                v-for="(image, index) in sliders"
                            >
                                <!-- Hidden Input -->
                                <input type="file" class="hidden" :name="'options['+ index +'][image]'" :ref="'imageInput_' + index" />    

                                <input type="hidden" :name="'options['+ index +'][link]'" :value="image.link" />    
                            
                                <!-- Details -->
                                <div 
                                    class="flex gap-[10px] justify-between py-5 cursor-pointer"
                                    :class="{
                                        'border-b-[1px] border-slate-300': index < sliders.length - 1
                                    }"
                                >
                                    <div class="flex gap-[10px]">
                                        <div class="grid gap-[6px] place-content-start">
                                            <p class="text-gray-600">
                                                <div> 
                                                    @lang('admin::app.settings.themes.create.link'): 

                                                    <span class="text-gray-600 transition-all">
                                                        @{{ image.link }}
                                                    </span>
                                                </div>
                                            </p>

                                            <p class="text-gray-600">
                                                <div class="flex justify-between"> 
                                                    @lang('admin::app.settings.themes.create.image'): 

                                                    <span class="text-gray-600 transition-all">
                                                        <a 
                                                            :ref="'image_' + index"
                                                            target="_blank"
                                                            class="ltr:ml-2 rtl:mr-2 text-blue-600 transition-all hover:underline"
                                                        >
                                                            <span :ref="'imageName_' + index"></span>
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
                                                @lang('admin::app.settings.themes.create.delete')
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
                                    src="{{ bagisto_asset('images/empty_theme.png') }}"
                                    alt="add-product-to-store"
                                >
                
                                <div class="flex flex-col items-center">
                                    <p class="text-[16px] text-gray-400 font-semibold">
                                        @lang('admin::app.settings.themes.create.slider-add-btn')
                                    </p>
                                    
                                    <p class="text-gray-400">
                                        @lang('admin::app.settings.themes.create.slider-description')
                                    </p>
                                </div>
                
                                <div
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.addSliderModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.create.slider-add-btn')
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- General -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.settings.themes.create.general')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <input type="hidden" name="type" value="image_carousel">

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.name')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="name"
                                        rules="required"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                        :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        label="@lang('admin::app.settings.themes.create.name')"
                                        placeholder="@lang('admin::app.settings.themes.create.name')"
                                    ></v-field>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.sort-order')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="sort_order"
                                        rules="required"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                        :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        label="@lang('admin::app.settings.themes.create.sort-order')"
                                        placeholder="@lang('admin::app.settings.themes.create.sort-order')"
                                    >
                                    </v-field>

                                    <x-admin::form.control-group.error
                                        control-name="sort_order"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.status')
                                    </x-admin::form.control-group.label>

                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <v-field
                                            type="checkbox"
                                            name="status"
                                            class="hidden"
                                            v-slot="{ field }"
                                            :value="1"
                                        >
                                            <input
                                                type="checkbox"
                                                name="status"
                                                id="status"
                                                value="1"
                                                class="sr-only peer"
                                                v-bind="field"
                                                :checked="1"
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

                <!-- For Fitler Form -->
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
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.settings.themes.create.create-slider')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.link')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="link"
                                            rules="required|url"
                                            :label="trans('admin::app.settings.themes.create.link')"
                                            :placeholder="trans('admin::app.settings.themes.create.link')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="link"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.slider-image')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="image"
                                            name="slider_image"
                                            :label="trans('admin::app.settings.themes.create.add-logo')"
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
                                        @lang('admin::app.settings.themes.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        {{-- Product Theme Template --}}
        <script type="text/x-template" id="v-product-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.create.product-carousel')
                                </p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    @lang('admin::app.settings.themes.create.product-carousel-description')
                                </p>
                            </div>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.create.filter-title')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[title]"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[title]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.create.filter-title')"
                                placeholder="@lang('admin::app.settings.themes.create.filter-title')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[title]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.create.sort')
                            </x-admin::form.control-group.label>

                            <v-field
                                name="options[filters][sort]"
                                v-slot="{ field }"
                                rules="required"
                                label="@lang('admin::app.settings.themes.create.sort')"
                            >
                                <select
                                   
                                    v-bind="field"
                                    class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400"
                                    :class="[errors['options[filters][sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                >
                                    <option value="" selected disabled>@lang('admin::app.settings.themes.create.select')</option>
                                    <option value="desc">@lang('admin::app.settings.themes.create.desc')</option>
                                    <option value="asc">@lang('admin::app.settings.themes.create.asc')</option>
                                </select>
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[filters][sort]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.create.limit')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[filters][limit]"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[filters][limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.create.limit')"
                                placeholder="@lang('admin::app.settings.themes.create.limit')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[filters][limit]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] border-gray-300"></span>

                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.create.filters')
                                </p>
                            </div>
            
                            <div class="flex gap-[10px]">
                                <div
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.productFilterModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.create.add-filter-btn')
                                </div>
                            </div>
                        </div>

                        <!-- Filters List -->
                        <div v-if="options.filters.length">
                            <div
                                class="grid"
                                v-for="(filter, index) in options.filters"
                            >
                                <!-- Hidden Input -->
                                <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 
                            
                                <!-- Details -->
                                <div 
                                    class="flex gap-[10px] justify-between py-5 cursor-pointer"
                                    :class="{
                                        'border-b-[1px] border-slate-300': index < options.filters.length - 1
                                    }"
                                >
                                    <div class="flex gap-[10px]">
                                        <div class="grid gap-[6px] place-content-start">
                                            <p class="text-gray-600">
                                                <div> 
                                                    @{{ "@lang('admin::app.settings.themes.create.key')".replace(':key', filter.key) }}
                                                </div>
                                            </p>

                                            <p class="text-gray-600">
                                                @{{ "@lang('admin::app.settings.themes.create.value')".replace(':value', filter.value) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="grid gap-[4px] place-content-start text-right">
                                        <div class="flex gap-x-[20px] items-center">
                                            <p 
                                                class="text-red-600 cursor-pointer transition-all hover:underline"
                                                @click="remove(filter)"
                                            > 
                                                @lang('admin::app.settings.themes.create.delete')
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Illustration -->
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
                                    @lang('admin::app.settings.themes.create.product-carousel')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.settings.themes.create.product-carousel-description')
                                </p>
                            </div>
            
                            <div 
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.productFilterModal.toggle()"
                            >
                                @lang('admin::app.settings.themes.create.add-filter-btn')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.create.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="product_carousel">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    label="@lang('admin::app.settings.themes.create.name')"
                                    placeholder="@lang('admin::app.settings.themes.create.name')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    label="@lang('admin::app.settings.themes.create.sort-order')"
                                    placeholder="@lang('admin::app.settings.themes.create.sort-order')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="sort_order"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.status')
                                </x-admin::form.control-group.label>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        :value="1"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            value="1"
                                            id="status"
                                            class="sr-only peer"
                                            v-bind="field"
                                            :checked="1"
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

                <!-- For Fitler Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addFilter)">
                        <x-admin::modal ref="productFilterModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.settings.themes.create.create-filter')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.key-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="key"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.create.key-input')"
                                            :placeholder="trans('admin::app.settings.themes.create.key-input')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="key"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Value -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.value-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="value"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.create.value-input')"
                                            :placeholder="trans('admin::app.settings.themes.create.value-input')"
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
                                        @lang('admin::app.settings.themes.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        {{-- Category Theme Template --}}
        <script type="text/x-template" id="v-category-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.create.category-carousel')
                                </p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    @lang('admin::app.settings.themes.create.category-carousel-description')
                                </p>
                            </div>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.create.filter-title')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[title]"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[title]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.create.filter-title')"
                                placeholder="@lang('admin::app.settings.themes.create.filter-title')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[title]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.create.sort')
                            </x-admin::form.control-group.label>

                            <v-field
                                name="options[filters][sort]"
                                v-slot="{ field }"
                                rules="required"
                                label="@lang('admin::app.settings.themes.create.sort')"
                            >
                                <select
                                    name="options[filters][sort]"
                                    v-bind="field"
                                    class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400"
                                    :class="[errors['options[filters][sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                >
                                    <option value="" selected disabled>Select</option>
                                    <option value="desc">@lang('admin::app.settings.themes.create.desc')</option>
                                    <option value="asc">@lang('admin::app.settings.themes.create.asc')</option>
                                </select>
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[filters][sort]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.create.limit')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[filters][limit]"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                :class="[errors['options[filters][limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required"
                                label="@lang('admin::app.settings.themes.create.limit')"
                                placeholder="@lang('admin::app.settings.themes.create.limit')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[filters][limit]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] border-gray-300"></span>

                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.create.filters')
                                </p>
                            </div>
            
                            <div class="flex gap-[10px]">
                                <div
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="$refs.categoryFilterModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.create.add-filter-btn')
                                </div>
                            </div>
                        </div>

                        <!-- Filters List -->
                        <div v-if="options.filters.length">
                            <div
                                class="grid"
                                v-for="(filter, index) in options.filters"
                            >
                                <!-- Hidden Input -->
                                <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 
                            
                                <!-- Details -->
                                <div 
                                    class="flex gap-[10px] justify-between py-5 cursor-pointer"
                                    :class="{
                                        'border-b-[1px] border-slate-300': index < options.filters.length - 1
                                    }"
                                >
                                    <div class="flex gap-[10px]">
                                        <div class="grid gap-[6px] place-content-start">
                                            <p class="text-gray-600">
                                                <div> 
                                                    @{{ "@lang('admin::app.settings.themes.create.key')".replace(':key', filter.key) }}
                                                </div>
                                            </p>

                                            <p class="text-gray-600">
                                                @{{ "@lang('admin::app.settings.themes.create.value')".replace(':value', filter.value) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="grid gap-[4px] place-content-start text-right">
                                        <div class="flex gap-x-[20px] items-center">
                                            <p 
                                                class="text-red-600 cursor-pointer transition-all hover:underline"
                                                @click="remove(filter)"
                                            > 
                                                @lang('admin::app.settings.themes.create.delete')
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Illustration -->
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
                                    @lang('admin::app.settings.themes.create.category-carousel')
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.settings.themes.create.category-carousel-description')

                                </p>
                            </div>
            
                            <div 
                                class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                @click="$refs.categoryFilterModal.toggle()"
                            >
                                @lang('admin::app.settings.themes.create.add-filter-btn')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.create.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="category_carousel">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                   :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('admin::app.settings.themes.create.name')"
                                    placeholder="@lang('admin::app.settings.themes.create.name')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                   :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('admin::app.settings.themes.create.sort-order')"
                                    placeholder="@lang('admin::app.settings.themes.create.sort-order')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="sort_order"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.status')
                                </x-admin::form.control-group.label>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        :value="1"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            id="status"
                                            value="1"
                                            class="sr-only peer"
                                            v-bind="field"
                                            :checked="1"
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
    
                <!-- For Filter Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addFilter)">
                        <x-admin::modal ref="categoryFilterModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.settings.themes.create.create-filter')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.key-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="key"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.create.key-input')"
                                            :placeholder="trans('admin::app.settings.themes.create.key-input')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="key"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
    
                                    <!-- Value -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.value-input')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="value"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.create.value-input')"
                                            :placeholder="trans('admin::app.settings.themes.create.value-input')"
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
                                        @lang('admin::app.settings.themes.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        {{-- Footer Link Template --}}
        <script type="text/x-template" id="v-footer-link-theme-template">
            <div>
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white rounded box-shadow">
                            <!-- Add Links-->
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 font-semibold">
                                        @lang('admin::app.settings.themes.create.footer-link')
                                    </p>
    
                                    <p class="text-[12px] text-gray-500 font-medium">
                                        @lang('admin::app.settings.themes.create.footer-link-description')
                                    </p>
                                </div>
                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                        @click="isUpdating=false;$refs.addLinksModal.toggle()"
                                    >
                                        @lang('admin::app.settings.themes.create.add-link')
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
                                
                                    <!-- Details -->
                                    <div class="flex gap-[10px] justify-between  border-b-[1px] py-5 border-slate-300 cursor-pointer">
                                        <div class="flex gap-[10px]">
                                            <div class="grid gap-[6px] place-content-start">
                                                <p class="text-gray-600">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.create.column'): 

                                                        <span class="text-gray-600 transition-all">
                                                            @{{ link.column }}
                                                        </span>
                                                    </div>
                                                </p>
    
                                                <p class="text-gray-600">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.create.url'):

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
                                                        @lang('admin::app.settings.themes.create.filter-title'):

                                                        <span
                                                            class="text-gray-600 transition-all"
                                                            v-text="link.title"
                                                        >
                                                        </span>
                                                    </div>
                                                </p>

                                                <p class="text-gray-600">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.create.sort-order'):

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
                                                    @lang('admin::app.settings.themes.create.edit')
                                                </p>

                                                <p 
                                                    class="text-red-600 cursor-pointer transition-all hover:underline"
                                                    @click="remove(link)"
                                                > 
                                                    @lang('admin::app.settings.themes.create.delete')
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div   
                                v-if="isShowIllustrator"
                                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                            >
                                <img
                                    class="w-[120px] h-[120px] border border-dashed border-gray-300 rounded-[4px]"
                                    src="{{ bagisto_asset('images/empty_theme.png') }}"
                                    alt="add-product-to-store"
                                >
                
                                <div class="flex flex-col items-center">
                                    <p class="text-[16px] text-gray-400 font-semibold">
                                        @lang('admin::app.settings.themes.create.footer-link')
                                    </p>
    
                                    <p class="text-gray-400">
                                        @lang('admin::app.settings.themes.create.footer-link-description')
                                    </p>
                                </div>
                
                                <div
                                    class="max-w-max px-[12px] py-[5px] bg-white border-[2px] border-blue-600 rounded-[6px] text-blue-600 font-semibold whitespace-nowrap cursor-pointer"
                                    @click="isUpdating=false;$refs.addLinksModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.create.add-link')
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- General -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                        <x-admin::accordion>
                            <x-slot:header>
                                <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                    @lang('admin::app.settings.themes.create.general')
                                </p>
                            </x-slot:header>
                        
                            <x-slot:content>
                                <input type="hidden" name="type" value="footer_links">

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.name')
                                    </x-admin::form.control-group.label>
    
                                    <v-field
                                        type="text"
                                        name="name"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                        :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required"
                                        label="@lang('admin::app.settings.themes.create.name')"
                                        placeholder="@lang('admin::app.settings.themes.create.name')"
                                    >
                                    </v-field>
    
                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.sort-order')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="sort_order"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                        :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required"
                                        label="@lang('admin::app.settings.themes.create.sort-order')"
                                        placeholder="@lang('admin::app.settings.themes.create.sort-order')"
                                    >
                                    </v-field>
    
                                    <x-admin::form.control-group.error
                                        control-name="sort_order"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
    
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.status')
                                    </x-admin::form.control-group.label>
    
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <v-field
                                            type="checkbox"
                                            name="status"
                                            class="hidden"
                                            v-slot="{ field }"
                                            :value="1"
                                        >
                                            <input
                                                type="checkbox"
                                                name="status"
                                                id="status"
                                                value="1"
                                                class="sr-only peer"
                                                v-bind="field"
                                                :checked="1"
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
                                    @lang('admin::app.settings.themes.create.footer-link-form-title')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.column')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="column"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.create.column')"
                                            :placeholder="trans('admin::app.settings.themes.create.column')"
                                            ::disabled="isUpdating"
                                        >
                                            <option value="column_1">1</option>
                                            <option value="column_2">2</option>
                                            <option value="column_3">3</option>
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="column"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
        
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.footer-title')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="title"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.create.footer-title')"
                                            :placeholder="trans('admin::app.settings.themes.create.footer-title')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="title"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
        
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.url')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="url"
                                            rules="required|url"
                                            :label="trans('admin::app.settings.themes.create.url')"
                                            :placeholder="trans('admin::app.settings.themes.create.url')"
                                            ::disabled="isUpdating"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="url"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.create.sort-order')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="sort_order"
                                            rules="required|numeric"
                                            :label="trans('admin::app.settings.themes.create.sort-order')"
                                            :placeholder="trans('admin::app.settings.themes.create.sort-order')"
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
                                        @lang('admin::app.settings.themes.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        {{-- Static Theme Template --}}
        <script type="text/x-template" id="v-static-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 font-semibold">
                                    @lang('admin::app.settings.themes.create.static-content')
                                </p>

                                <p class="text-[12px] text-gray-500 font-medium">
                                    @lang('admin::app.settings.themes.create.static-content-description')
                                </p>
                            </div>
                        </div>

                        <!-- Editor Switcher -->
                        <div class="text-sm font-medium text-center text-gray-500">
                            <div class="tabs">
                                <div class="flex gap-[15px] mb-[15px] pt-[8px] border-b-[2px] max-sm:hidden">
                                    <p @click="switchEditor('v-html-editor-theme')">
                                        <div
                                            class="mb-[-1px] border-b-[2px] transition pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 cursor-pointer"
                                            :class="{'border-blue-600': inittialEditor == 'v-html-editor-theme'}"
                                        >
                                            @lang('admin::app.settings.themes.create.html')
                                        </div>
                                    </p>

                                    <p @click="switchEditor('v-css-editor-theme')">
                                        <div
                                            class="mb-[-1px] border-b-[2px] transition pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 cursor-pointer"
                                            :class="{'border-blue-600': inittialEditor == 'v-css-editor-theme'}"
                                        >
                                            @lang('admin::app.settings.themes.create.css')
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="options[html]" v-model="options.html">
                        <input type="hidden" name="options[css]" v-model="options.css">

                        <!-- Dynamic Component -->
                        <KeepAlive>
                            <component 
                                :is="inittialEditor"
                                @editor-data="editorData"
                            >
                            </component>
                        </KeepAlive>
                    </div>
                </div>

                <!-- General -->
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.create.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="static_content">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('admin::app.settings.themes.create.name')"
                                    placeholder="@lang('admin::app.settings.themes.create.name')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    rules="required"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    label="@lang('admin::app.settings.themes.create.sort-order')"
                                    placeholder="@lang('admin::app.settings.themes.create.sort-order')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error
                                    control-name="sort_order"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.create.status')
                                </x-admin::form.control-group.label>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        :value="1"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            id="status"
                                            value="1"
                                            class="sr-only peer"
                                            v-bind="field"
                                            :checked="1"
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
        </script>

        <script type="text/x-template" id="v-html-editor-theme-template">
            <div>
                <div ref="html"></div>
            </div>
        </script>

        <script type="text/x-template" id="v-css-editor-theme-template">
            <div>
                <div ref="css"></div>
            </div>
        </script>

        {{-- Html Editor Component --}}
        <script type="module">
            app.component('v-html-editor-theme', {
                template: '#v-html-editor-theme-template',

                data() {
                    return {
                        options:{
                            html: '',
                        }
                    };
                },

                created() {
                    this.initHtmlEditor();
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

                                this.$emit('editorData', this.options);
                            });
                        }, 0);
                    },
                },
            });
        </script>

        {{-- Slider Theme Component--}}
        <script type="module">
            app.component('v-slider-theme', {
                template: '#v-slider-theme-template',
                
                props:['errors'],
                
                data() {
                    return {
                        sliders: [],
                    };
                },

                methods: {
                    saveSliderImage(params) {
                        let formData = new FormData(this.$refs.createSliderForm);

                        this.sliders.push({
                            slider_image: formData.get("slider_image[]"),
                            link: formData.get("link"),
                        });

                        if (formData.get("slider_image[]") instanceof File) {
                            this.setFile(formData.get("slider_image[]"), this.sliders.length - 1);
                        }

                        this.$refs.addSliderModal.toggle();
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
                        let index = this.sliders.indexOf(image);

                        this.sliders.splice(image, 1);
                    },
                },
            });
        </script>

        {{-- Customizer component --}}
        <script type="module">
            app.component('v-theme-customizer', {
                template: '#v-theme-customizer-template',

                props: ['errors'],

                data() {
                    return {
                        componentName: 'v-slider-theme',
                    };
                },

                methods: {
                    switchComponent(component) {
                        this.componentName = component;
                    },
                },
            });
        </script>

        {{-- Product Theme Component--}}
        <script type="module">
            app.component('v-product-theme', {
                template: '#v-product-theme-template',

                props: ['errors'],
                
                data() {
                    return {
                        options: {
                            filters: [],
                        },
                    };
                },

                methods: {
                    addFilter(params) {
                        this.options.filters.push(params);

                        this.$refs.productFilterModal.toggle();
                    },

                    remove(filter) {
                        let index = this.options.filters.indexOf(filter);

                        this.options.filters.splice(index, 1);
                    },
                },
            });
        </script>
            
        {{-- Category Theme Component--}}
        <script type="module">
            app.component('v-category-theme', {
                template: '#v-category-theme-template',
    
                props: ['errors'],

                data() {
                    return {
                        options: {
                            filters: [],
                        },
                    };
                },
    
                methods: {
                    addFilter(params) {
                        this.options.filters.push(params);
    
                        this.$refs.categoryFilterModal.toggle();
                    },
    
                    remove(filter) {
                        let index = this.options.filters.indexOf(filter);
    
                        this.options.filters.splice(index, 1);
                    },
                },
            });
        </script>

        {{-- Footer Link Component--}}
        <script type="module">
            app.component('v-footer-link-theme', {
                template: '#v-footer-link-theme-template',

                props: ['errors'],

                data() {
                    return {
                        footerLinks: {
                            column_1: [],
                            column_2: [],
                            column_3: [],
                        },

                        isShowIllustrator: true,

                        isUpdating: false,
                    };
                },

                computed: {
                    isFooterLinksEmpty() {
                        return Object.values(this.footerLinks).every(column => column.length === 0);
                    },
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
                            this.isShowIllustrator = false;
                            
                            if (! this.footerLinks.hasOwnProperty(params.column)) {
                                this.footerLinks[params.column] = []; 
                            }
                            
                            this.footerLinks[params.column].push(params);
                        }

                        this.$refs.addLinksModal.toggle();
                    },

                    remove(footerLink) {
                        if (
                            this.footerLinks.hasOwnProperty(footerLink.column) 
                            && Array.isArray(this.footerLinks[footerLink.column])
                            && this.footerLinks[footerLink.column].length > 0
                        ) {
                            this.footerLinks[footerLink.column].splice(0, 1);
                        }

                        if (this.isFooterLinksEmpty) {
                            this.isShowIllustrator = true;
                        }
                    },
                   
                    edit(footerLink) {
                        this.isUpdating = true;

                        this.$refs.footerLinkUpdateOrCreateModal.setValues(footerLink);

                        this.$refs.addLinksModal.toggle();
                    },
                },
            });
        </script>

        {{-- Css Editor Component --}}
        <script type="module">
            app.component('v-css-editor-theme', {
                template: '#v-css-editor-theme-template',

                data() {
                    return {
                        options: {
                            css: '',
                        }
                    };
                },

                created() {
                    this.initCssEditor();
                },

                methods: {
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

                                this.$emit('editorData', this.options);
                            });
                        }, 0);
                    },
                },
            });
        </script>

        {{-- Static Theme component --}}
        <script type="module">
            app.component('v-static-theme', {
                template: '#v-static-theme-template',

                props: ['errors'],

                data() {
                    return {
                        inittialEditor: 'v-html-editor-theme',

                        options: {
                            html: '',
                            css: '',
                        }
                    };
                },

                methods: {
                    switchEditor(editor) {
                        this.inittialEditor = editor;
                    },

                    editorData(value) {
                        if (value.html) {
                            this.options.html = value.html;
                        } else {
                            this.options.css = value.css;
                        } 
                    },
                },
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