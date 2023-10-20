<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.themes.edit.title')
    </x-slot:title>
   
    @php
        $channels = core()->getAllChannels();

        $currentChannel = core()->getRequestedChannel();

        $currentLocale = core()->getRequestedLocale();
    @endphp

    <x-admin::form 
        :action="route('admin.settings.themes.update', $theme->id)"
        enctype="multipart/form-data"
        v-slot="{ errors }"
    >
        <div class="flex justify-between items-center">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.themes.edit.title')
            </p>
            
            <div class="flex gap-x-[10px] items-center">
                <div class="flex gap-x-[10px] items-center">
                    <a 
                        href="{{ route('admin.settings.themes.index') }}"
                        class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                    > 
                        @lang('admin::app.settings.themes.edit.back')
                    </a>
                </div>
                
                <button 
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.themes.edit.save-btn')
                </button>
            </div>
        </div>

        {{-- Channel and Locale Switcher --}}
        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                {{-- Locale Switcher --}}
                <x-admin::dropdown>
                    {{-- Dropdown Toggler --}}
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-[4px] py-[6px] hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                        >
                            <span class="icon-language text-[24px]"></span>

                            {{ $currentLocale->name }}
                            
                            <input type="hidden" name="locale" value="{{ $currentLocale->code }}"/>

                            <span class="icon-sort-down text-[24px]"></span>
                        </button>
                    </x-slot:toggle>

                    {{-- Dropdown Content --}}
                    <x-slot:content class="!p-[0px]">
                        @foreach ($currentChannel->locales as $locale)
                            <a
                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot:content>
                </x-admin::dropdown>
            </div>
        </div>

        <v-theme-customizer :errors="errors"></v-theme-customizer>
    </x-admin::form>

    @pushOnce('scripts')
        {{-- Customizer Parent Template--}}
        <script type="text/x-template" id="v-theme-customizer-template">
            <div>
                <component
                    :errors="errors"
                    :is="componentName"
                    ref="dynamicComponentThemeRef"
                >
                </component>
            </div>
        </script>

        {{-- Slider Template --}}
        <script type="text/x-template" id="v-slider-theme-template">
            <div>
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white dark:bg-gray-900 rounded box-shadow">
                            <div class="flex gap-x-[10px] justify-between items-center">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">@lang('admin::app.settings.themes.edit.slider')</p>
                                    
                                    <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                                        @lang('admin::app.settings.themes.edit.slider-description')
                                    </p>
                                </div>
                
                                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="secondary-button"
                                        @click="$refs.addSliderModal.toggle()"
                                    >
                                        @lang('admin::app.settings.themes.edit.slider-add-btn')
                                    </div>
                                </div>
                            </div>

                            <template v-for="(deletedSlider, index) in deletedSliders">
                                <input type="hidden" :name="'deleted_sliders['+ index +'][image]'" :value="deletedSlider.image" />
                            </template>

                            <div
                                class="grid pt-[16px]"
                                v-if="sliders.images.length"
                                v-for="(image, index) in sliders.images"
                            >
                                <!-- Hidden Input -->
                                <input type="file" class="hidden" :name="'options['+ index +'][image]'" :ref="'imageInput_' + index" />
                                <input type="hidden" :name="'options['+ index +'][link]'" :value="image.link" />
                                <input type="hidden" :name="'options['+ index +'][image]'" :value="image.image" />
                            
                                <!-- Details -->
                                <div 
                                    class="flex gap-[10px] justify-between py-5 cursor-pointer"
                                    :class="{
                                        'border-b-[1px] border-slate-300 dark:border-gray-800': index < sliders.images.length - 1
                                    }"
                                >
                                    <div class="flex gap-[10px]">
                                        <div class="grid gap-[6px] place-content-start">
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
                                                            :href="'{{ config('app.url') }}' + image.image"
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

                            <div    
                                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]"
                                v-else
                            >
                                <img    
                                    class="w-[120px] h-[120px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
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
                                <input type="hidden" name="type" value="image_carousel">
    
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
                                    @lang('admin::app.settings.themes.edit.update-slider')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.settings.themes.edit.link')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="{{ $currentLocale->code }}[link]"
                                            :placeholder="trans('admin::app.settings.themes.edit.link')"
                                        >
                                        </x-admin::form.control-group.control>
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
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="slider_image"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.themes.edit.image-size')
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

        {{-- Product Template --}}
        <script type="text/x-template" id="v-product-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white dark:bg-gray-900 rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center mb-[10px]">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.product-carousel')
                                </p>

                                <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                                    @lang('admin::app.settings.themes.edit.product-carousel-description')
                                </p>
                            </div>
                        </div>

                        <x-admin::form.control-group class="mb-[10px] pt-[16px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.filter-title')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[title]"
                                value="{{ $theme->options['title'] ?? '' }}"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
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
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.sort')
                            </x-admin::form.control-group.label>

                            <v-field
                                name="options[filters][sort]"
                                v-slot="{ field }"
                                rules="required"
                                value="{{ $theme->options['filters']['sort'] ?? '' }}"
                                label="@lang('admin::app.settings.themes.edit.sort')"
                            >
                                <select
                                    name="options[filters][sort]"
                                    v-bind="field"
                                    class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400"
                                    :class="[errors['options[filters][sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                >
                                    <option value="" selected disabled>@lang('admin::app.settings.themes.edit.select')</option>
                                    
                                    @foreach (
                                        product_toolbar()->getAvailableOrders()->pluck('title', 'value') 
                                        as $key => $availableOrder
                                    )
                                        <option value="{{ $key }}">{{ $availableOrder }}</option>
                                    @endforeach
                                </select>
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[filters][sort]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.limit')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="select"
                                name="options[filters][limit]"
                                v-slot="{ field }"
                                rules="required"
                                value="{{ $theme->options['filters']['limit'] ?? '' }}"
                                label="@lang('admin::app.settings.themes.edit.limit')"
                            >
                                <select
                                    name="options[filters][limit]"
                                    v-bind="field"
                                    class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400"
                                    :class="[errors['options[filters][limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                >
                                    <option value="" selected disabled>@lang('admin::app.settings.themes.edit.select')</option>

                                    @foreach (product_toolbar()->getAvailableLimits() as $availableLimit)
                                        <option value="{{ $availableLimit }}">{{ $availableLimit }}</option>
                                    @endforeach
                                </select>
                            </v-field>

                            
                            <x-admin::form.control-group.error
                                control-name="options[filters][limit]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] dark:border-gray-800"></span>

                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.filters')
                                </p>
                            </div>
            
                            <div class="flex gap-[10px]">
                                <div
                                    class="secondary-button"
                                    @click="$refs.productFilterModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.edit.add-filter-btn')
                                </div>
                            </div>
                        </div>

                        <!-- Filters Lists -->
                        <div
                            class="grid"
                            v-if="options.filters.length"
                            v-for="(filter, index) in options.filters"
                        >
                            <!-- Hidden Input -->
                            <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 
                        
                            <!-- Details -->
                            <div 
                                class="flex gap-[10px] justify-between py-5 cursor-pointer"
                                :class="{
                                    'border-b-[1px] border-slate-300 dark:border-gray-800': index < options.filters.length - 1
                                }"
                            >
                                <div class="flex gap-[10px]">
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div> 
                                                @{{ "@lang('admin::app.settings.themes.edit.key')".replace(':key', filter.key) }}
                                            </div>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('admin::app.settings.themes.edit.value')".replace(':value', filter.value) }}
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
                                            @lang('admin::app.settings.themes.edit.delete')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Filters Illustration -->
                        <div    
                            class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px] "
                            v-else
                        >
                            <img
                                class="w-[120px] h-[120px] p-2 dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/empty-placeholders/default-empty.svg') }}"
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
                            <input type="hidden" name="type" value="product_carousel">

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
                                >
                                </v-field>

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
                                    value="{{ $theme->sort_order }}"
                                    rules="required|min_value:1"
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
                                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                    @lang('admin::app.settings.themes.edit.create-filter')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
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
                                        <x-admin::form.control-group.label class="required">
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

        {{-- Category Template --}}
        <script type="text/x-template" id="v-category-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                    <div class="p-[16px] bg-white dark:bg-gray-900 rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center mb-[10px]">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.category-carousel')
                                </p>

                                <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                                    @lang('admin::app.settings.themes.edit.category-carousel-description')
                                </p>
                            </div>
                        </div>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.sort')
                            </x-admin::form.control-group.label>

                            <v-field
                                name="options[filters][sort]"
                                value="{{ $theme->options['filters']['sort'] ?? ''}}"
                                v-slot="{ field }"
                                rules="required"
                                label="@lang('admin::app.settings.themes.edit.sort')"
                            >
                                <select
                                    name="options[filters][sort]"
                                    v-bind="field"
                                    class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                    :class="[errors['options[filters][sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                >
                                    <option value="" selected disabled>@lang('admin::app.settings.themes.edit.select')</option>
                                    <option value="desc">@lang('admin::app.settings.themes.edit.desc')</option>
                                    <option value="asc">@lang('admin::app.settings.themes.edit.asc')</option>
                                </select>
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[filters][sort]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.limit')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="options[filters][limit]"
                                value="{{ $theme->options['filters']['limit'] ?? '' }}"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                :class="[errors['options[filters][limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                rules="required|min_value:1"
                                label="@lang('admin::app.settings.themes.edit.limit')"
                                placeholder="@lang('admin::app.settings.themes.edit.limit')"
                            >
                            </v-field>

                            <x-admin::form.control-group.error
                                control-name="options[filters][limit]"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>


                        <span class="block w-full mb-[16px] mt-[16px] border-b-[1px] dark:border-gray-800"></span>

                        <div class="flex gap-x-[10px] justify-between items-center">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.filters')
                                </p>
                            </div>
            
                            <div class="flex gap-[10px]">
                                <div
                                    class="secondary-button"
                                    @click="$refs.categoryFilterModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.edit.add-filter-btn')
                                </div>
                            </div>
                        </div>

                        <!-- Filters Lists -->
                        <div
                            class="grid"
                            v-if="options.filters.length"
                            v-for="(filter, index) in options.filters"
                        >
                            <!-- Hidden Input -->
                            <input type="hidden" :name="'options[filters][' + filter.key +']'" :value="filter.value"> 
                        
                            <!-- Details -->
                            <div 
                                class="flex gap-[10px] justify-between py-5 cursor-pointer"
                                :class="{
                                    'border-b-[1px] border-slate-300 dark:border-gray-800': index < options.filters.length - 1
                                }"
                            >
                                <div class="flex gap-[10px]">
                                    <div class="grid gap-[6px] place-content-start">
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <div> 
                                                @{{ "@lang('admin::app.settings.themes.edit.key')".replace(':key', filter.key) }}
                                            </div>
                                        </p>

                                        <p class="text-gray-600 dark:text-gray-300">
                                            @{{ "@lang('admin::app.settings.themes.edit.value')".replace(':value', filter.value) }}
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
                                            @lang('admin::app.settings.themes.edit.delete')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters Illustration -->
                        <div    
                            class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px] "
                            v-else
                        >
                            <img
                                class="w-[120px] h-[120px] p-2 dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/empty-placeholders/default-empty.svg') }}"
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
                                class="secondary-button"
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
                            <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.edit.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="category_carousel">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
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
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    value="{{ $theme->sort_order }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required|min_value:1"
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
                        <x-admin::modal ref="categoryFilterModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                    @lang('admin::app.settings.themes.edit.create-filter')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <!-- Key -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
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
                                        <x-admin::form.control-group.label class="required">
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

        {{-- Static Template --}}
        <script type="text/x-template" id="v-static-theme-template">
            <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                <div class="flex flex-col gap-[8px] flex-1 min-w-[931px] max-xl:flex-auto">
                    <div class="p-[16px] bg-white dark:bg-gray-900 rounded box-shadow">
                        <div class="flex gap-x-[10px] justify-between items-center mb-[10px]">
                            <div class="flex flex-col gap-[4px]">
                                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.static-content')
                                </p>

                                <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                                    @lang('admin::app.settings.themes.edit.static-content-description')
                                </p>
                            </div>

                            <div
                                class="flex gap-[10px]"
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
                                    @change="storeImage($event)"
                                >
                            </div>
                        </div>
                        
                        <div class="text-sm font-medium text-center pt-[16px] text-gray-500">
                            <div class="tabs">
                                <div class="flex gap-[15px] mb-[15px] pt-[8px] border-b-[2px] max-sm:hidden">
                                    <p @click="switchEditor('v-html-editor-theme', 1)">
                                        <div
                                            class="transition pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 dark:text-gray-300 cursor-pointer"
                                            :class="{'mb-[-1px] border-b-[2px] border-blue-600': inittialEditor == 'v-html-editor-theme'}"
                                        >
                                            @lang('admin::app.settings.themes.edit.html')
                                        </div>
                                    </p>

                                    <p @click="switchEditor('v-css-editor-theme', 0);">
                                        <div
                                            class="transition pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 dark:text-gray-300 cursor-pointer"
                                            :class="{'mb-[-1px] border-b-[2px] border-blue-600': inittialEditor == 'v-css-editor-theme'}"
                                        >
                                            @lang('admin::app.settings.themes.edit.css')
                                        </div>
                                    </p>

                                    <p @click="switchEditor('v-static-content-previewer', 0);">
                                        <div
                                            class="transition pb-[14px] px-[10px] text-[16px] font-medium text-gray-600 dark:text-gray-300 cursor-pointer"
                                            :class="{'mb-[-1px] border-b-[2px] border-blue-600': inittialEditor == 'v-static-content-previewer'}"
                                        >
                                            @lang('admin::app.settings.themes.edit.preview')
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="{{ $currentLocale->code }}[options][html]" v-model="options.html">
                        <input type="hidden" name="{{ $currentLocale->code }}[options][css]" v-model="options.css">

                        <KeepAlive>
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
                <div class="flex flex-col gap-[8px] w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-[10px] text-gray-600 dark:text-gray-300 text-[16px] font-semibold">
                                @lang('admin::app.settings.themes.edit.general')
                            </p>
                        </x-slot:header>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="static_content">

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
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
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    value="{{ $theme->sort_order }}"
                                    rules="required|min_value:1"
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

        {{-- Html Editor Template --}}
        <script type="text/x-template" id="v-html-editor-theme-template">
            <div>
                <div ref="html"></div>
            </div>
        </script>

        {{-- Css Editor Template --}}
        <script type="text/x-template" id="v-css-editor-theme-template">
            <div>
                <div ref="css"></div>
            </div>
        </script>

        {{-- Static Content Previewer --}}
        <script type="text/x-template" id="v-static-content-previewer-template">
            <div>   
                <div v-html="getPreviewContent()"></div>
            </div>
        </script>

        {{-- Footer Template --}}
        <script type="text/x-template" id="v-footer-link-theme-template">
            <div>
                <div class="flex gap-[10px] mt-[14px] max-xl:flex-wrap">
                    <div class=" flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                        <div class="p-[16px] bg-white dark:bg-gray-900 rounded box-shadow">
                            <!-- Add Links-->
                            <div class="flex gap-x-[10px] justify-between items-center mb-[10px]">
                                <div class="flex flex-col gap-[4px]">
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                        @lang('admin::app.settings.themes.edit.footer-link')
                                    </p>
    
                                    <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                                        @lang('admin::app.settings.themes.edit.footer-link-description')
                                    </p>
                                </div>
                
                                <div class="flex gap-[10px]">
                                    <div
                                        class="secondary-button"
                                        @click="isUpdating=false;$refs.addLinksModal.toggle()"
                                    >
                                        @lang('admin::app.settings.themes.edit.add-link')
                                    </div>
                                </div>
                            </div>
    
                            <!-- Footer Links -->
                            <div
                                v-if="Object.keys(footerLinks).length"
                                v-for="(footerLink, index) in footerLinks"
                            >
                                <!-- Information -->
                                <div 
                                    class="grid border-b-[1px] last:border-b-0 border-slate-300 dark:border-gray-800"
                                    v-for="(link, key) in footerLink"
                                >
                                    <!-- Hidden Input -->
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +']'" :value="link.column"> 
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +'][url]'" :value="link.url"> 
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +'][title]'" :value="link.title"> 
                                    <input type="hidden" :name="'options['+ link.column +'][' + key +'][sort_order]'" :value="link.sort_order"> 
                                    
                                    <div class="flex gap-[10px] justify-between py-5 cursor-pointer">
                                        <div class="flex gap-[10px] ">
                                            <div class="grid gap-[6px] place-content-start">
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.column'): 

                                                        <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                            @{{ link.column }}
                                                        </span>
                                                    </div>
                                                </p>
    
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.path'):

                                                        <a
                                                            :href="link.url"
                                                            target="_blank"
                                                            class="text-blue-600 transition-all hover:underline"
                                                            v-text="link.url"
                                                        >
                                                        </a>
                                                    </div>
                                                </p>

                                                <p class="text-gray-600 dark:text-gray-300">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.filter-title'):

                                                        <span
                                                            class="text-gray-600 dark:text-gray-300 transition-all"
                                                            v-text="link.title"
                                                        >
                                                        </span>
                                                    </div>
                                                </p>

                                                <p class="text-gray-600 dark:text-gray-300">
                                                    <div> 
                                                        @lang('admin::app.settings.themes.edit.sort-order'):

                                                        <span
                                                            class="text-gray-600 dark:text-gray-300 transition-all"
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
                                class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px] "
                            >
                                <img
                                    class="w-[120px] h-[120px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px] dark:invert dark:mix-blend-exclusion"
                                    src="{{ bagisto_asset('images/empty-placeholders/default-empty.svg') }}"
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
                
                                <div class="secondary-button">
                                    @lang('admin::app.settings.themes.edit.add-footer-link-btn')
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
                                <input type="hidden" name="type" value="footer_links">

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.edit.name')
                                    </x-admin::form.control-group.label>
    
                                    <v-field
                                        type="text"
                                        name="name"
                                        value="{{ $theme->name }}"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
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
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.edit.sort-order')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="sort_order"
                                        value="{{ $theme->sort_order }}"
                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
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

                <!-- For Fitler Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="footerLinkUpdateOrCreateModal"
                >
                    <form @submit="handleSubmit($event, updateOrCreate)">
                        <x-admin::modal ref="addLinksModal">
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                    @lang('admin::app.settings.themes.edit.footer-link-form-title')
                                </p>
                            </x-slot:header>
        
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
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
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="column"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
        
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.edit.footer-title')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="title"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.footer-title')"
                                            :placeholder="trans('admin::app.settings.themes.edit.footer-title')"
                                        >
                                        </x-admin::form.control-group.control>
        
                                        <x-admin::form.control-group.error
                                            control-name="title"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
        
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.settings.themes.edit.path')
                                        </x-admin::form.control-group.label>
        
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="url"
                                            rules="required"
                                            :label="trans('admin::app.settings.themes.edit.path')"
                                            :placeholder="trans('admin::app.settings.themes.edit.path')"
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

        {{-- Parent Theme Customizer Component --}}
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
                    };
                },

                created(){
                    this.componentName = this.themeType["{{ $theme->type }}"];
                },
            });
        </script>

        {{-- Slider Theme Component --}}
        <script type="module">
            app.component('v-slider-theme', {
                template: '#v-slider-theme-template',

                props: ['errors'],

                data() {
                    return {
                        sliders: @json($theme->options),

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
                                slider_image: sliderImage,
                                link: formData.get("{{ $currentLocale->code }}[link]"),
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
                        this.deletedSliders.push(image);
                        
                        this.sliders.images = this.sliders.images.filter(item => {
                            return (
                                item.link !== image.link || 
                                item.image !== image.image
                            );
                        });
                    },
                },
            });
        </script>

        {{-- Product Theme Component --}}
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
                    if (this.options === null) {
                        this.options = { filters: {} };
                    }   
                    
                    if (! this.options.filters) {
                        this.options.filters = {};
                    }

                    this.options.filters = Object.keys(this.options.filters)
                        .filter(key => ! ['sort', 'limit', 'title'].includes(key))
                        .map(key => ({
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
                    },
                },
            });
        </script>

        {{-- Category Theme Component --}}
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
                    if (this.options === null) {
                        this.options = { filters: {} };
                    }   

                    if (! this.options.filters) {
                        this.options.filters = {};
                    }

                    this.options.filters = Object.keys(this.options.filters)
                        .filter(key => ! ['sort', 'limit', 'title'].includes(key))
                        .map(key => ({
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

        {{-- Html Editor Component --}}
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
                },

                methods: {
                    initHtmlEditor() {
                        setTimeout(() => {
                            this.options.html = SimplyBeautiful().html(this.options.html);

                            this._html = new CodeMirror(this.$refs.html, {
                                lineNumbers: true,
                                tabSize: 4,
                                lineWiseCopyCut: true,
                                value: this.options.html,
                                mode: 'htmlmixed',
                            });

                            this._html.on('changes', (e) => {
                                this.options.html = this._html.getValue();

                                this.cursorPointer = e.getCursor();

                                this.$emit('editorData', this.options);
                            });
                        }, 0);
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

                        formData.append('options[image][image]', selectedImage);
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
                            .catch((error) => {});
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
                        options:{
                            css: `{!! $theme->translate($currentLocale->code)['options']['css'] ?? '' !!}`,
                        }
                    };
                },

                created() {
                    this.initCssEditor();
                },

                methods: {
                    initCssEditor() {
                        setTimeout(() => {
                            this.options.css = SimplyBeautiful().css(this.options.css);

                            this._css = new CodeMirror(this.$refs.css, {
                                lineNumbers: true,
                                tabSize: 4,
                                lineWiseCopyCut: true,
                                value: this.options.css,
                                mode: 'css',
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

        {{-- Static Content Previewer --}}
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

        {{-- Footer component --}}
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

                computed: {
                    isFooterLinksEmpty() {
                        return Object.values(this.footerLinks).every(column => column.length === 0);
                    },
                },

                created() {
                    if (this.footerLinks === null) {
                        this.footerLinks = {};
                    }

                    for (let i = 1; i <= 3; i++) {
                        if (!this.footerLinks.hasOwnProperty(`column_${i}`)) {
                            this.footerLinks[`column_${i}`] = [];
                        }
                    }

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

        {{-- Code mirror script CDN --}}
        <script
            type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"
        >
        </script>

        {{-- 
            Html mixed and xml cnd both are dependent 
            Used for html and css theme
        --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/css/css.js"></script>

        {{-- Beatutify html and css --}}
        <script src="https://cdn.jsdelivr.net/npm/simply-beautiful@latest/dist/index.min.js"></script>
    @endPushOnce

    @pushOnce('styles')
        {{-- Code mirror style cdn --}}
        <link 
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css"
        >
        </link>
    @endPushOnce
</x-admin::layouts>
                            