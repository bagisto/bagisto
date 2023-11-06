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

        <!-- Channel and Locale Switcher -->
        <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
            <div class="flex gap-x-[4px] items-center">
                <!-- Locale Switcher -->
                <x-admin::dropdown>
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-[4px] py-[6px] hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                        >
                            <span class="icon-language text-[24px]"></span>

                            {{ $currentLocale->name }}
                            
                            <input
                                type="hidden"
                                name="locale"
                                value="{{ $currentLocale->code }}"
                            />

                            <span class="icon-sort-down text-[24px]"></span>
                        </button>
                    </x-slot:toggle>

                    <!-- Dropdown Content -->
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
        <!-- Customizer Parent Template-->
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

        <!-- Image-Carousel Template -->
        @includeWhen($theme->type === 'image_carousel', 'admin::settings.themes.edit.image-carousel')

        <!-- Product-Carousel Template -->
        @includeWhen($theme->type === 'product_carousel', 'admin::settings.themes.edit.product-carousel')

        <!-- Category Template -->
        @includeWhen($theme->type==='category_carousel', 'admin::settings.themes.edit.category-carousel')

        <!-- Static-Content Template -->
        @includeWhen($theme->type === 'static_content', 'admin::settings.themes.edit.static-content')

        <!-- Footer Template -->
        @includeWhen($theme->type === 'footer_links', 'admin::settings.themes.edit.footer-links')

        <!-- Parent Theme Customizer Component -->
        <script type="module">
            app.component('v-theme-customizer', {
                template: '#v-theme-customizer-template',

                props: ['errors'],

                data() {
                    return {
                        componentName: 'v-image-carousel',

                        themeType: {
                            product_carousel: 'v-product-carousel',
                            category_carousel: 'v-category-carousel',
                            static_content: 'v-static-content',
                            image_carousel: 'v-image-carousel',
                            footer_links: 'v-footer-links',
                        } 
                    };
                },

                created(){
                    this.componentName = this.themeType["{{ $theme->type }}"];
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
                            