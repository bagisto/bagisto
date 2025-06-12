{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $channel = core()->getCurrentChannel();

    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);
@endphp

<footer class="mt-9 bg-zylver-olive-green text-zylver-cream max-sm:mt-10">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12 py-12 lg:py-16 px-4 sm:px-6 lg:px-8">

        <!-- Column 1: Brand/Company Info -->
        <div class="space-y-6">
            <a href="{{ route('shop.home.index') }}" class="text-2xl font-fraunces font-semibold text-zylver-cream hover:text-zylver-gold">
                ZYLVER
            </a>
            <p class="text-sm text-zylver-cream/80 font-lato leading-relaxed">
                Crafting timeless elegance with a modern touch. Discover exquisite jewelry that tells your story.
            </p>
            <!-- Social Media Icons -->
            <div class="flex space-x-4">
                <a href="#" class="text-zylver-cream hover:text-zylver-gold" aria-label="WhatsApp">
                    {{-- Placeholder for WhatsApp SVG Icon --}}
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.61 15.31 3.4 16.78L2.05 22L7.31 20.65C8.75 21.38 10.35 21.79 12.04 21.79C17.5 21.79 21.95 17.34 21.95 11.88C21.95 6.42 17.5 2 12.04 2ZM12.04 20.13C10.56 20.13 9.13 19.78 7.89 19.13L7.53 18.91L4.45 19.79L5.35 16.78L5.13 16.42C4.38 15.03 3.96 13.48 3.96 11.91C3.96 7.36 7.59 3.73 12.04 3.73C16.49 3.73 20.12 7.36 20.12 11.88C20.12 16.4 16.49 20.13 12.04 20.13ZM17.41 14.47C17.15 14.34 16.13 13.85 15.9 13.77C15.67 13.68 15.5 13.64 15.34 13.87C15.18 14.1 14.72 14.64 14.56 14.81C14.4 14.97 14.25 15 14.02 14.87C13.79 14.74 12.98 14.46 12.03 13.6C11.26 12.91 10.77 12.08 10.61 11.85C10.45 11.62 10.56 11.5 10.69 11.37C10.81 11.26 10.94 11.09 11.07 10.94C11.18 10.81 11.23 10.7 11.15 10.54C11.07 10.38 10.61 9.26 10.42 8.81C10.23 8.36 10.04 8.42 9.89 8.42C9.73 8.42 9.56 8.42 9.4 8.42C9.24 8.42 8.98 8.48 8.75 8.71C8.52 8.94 7.97 9.43 7.97 10.5C7.97 11.57 8.78 12.59 8.9 12.75C9.02 12.91 10.61 15.23 12.89 16.19C13.89 16.62 14.63 16.83 15.18 17C15.78 17.15 16.45 17.11 16.88 16.9C17.37 16.67 18.22 16.06 18.41 15.52C18.6 14.98 18.6 14.53 18.52 14.47C18.44 14.41 17.67 14.6 17.41 14.47Z"/></svg>
                </a>
                <a href="#" class="text-zylver-cream hover:text-zylver-gold" aria-label="Instagram">
                    {{-- Placeholder for Instagram SVG Icon --}}
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C8.74 2 8.333.015 6.983.072 5.633.127 4.913.31 4.22.584c-.694.273-1.283.635-1.842 1.194C1.818 2.337 1.456 2.926 1.183 3.62c-.273.694-.458 1.413-.513 2.763C.615 7.717.6 8.123.6 11.383c0 3.26.015 3.667.072 5.017.055 1.35.24 2.07.513 2.763.273.694.635 1.283 1.194 1.842.559.559 1.148.92 1.842 1.194.694.273 1.413.458 2.763.513 1.35.057 1.757.072 5.017.072s3.667-.015 5.017-.072c1.35-.055 2.07-.24 2.763-.513.694-.273 1.283-.635 1.842-1.194.559-.559.92-1.148 1.194-1.842.273-.694.458-1.413.513-2.763.057-1.35.072-1.757.072-5.017s-.015-3.667-.072-5.017c-.055-1.35-.24-2.07-.513-2.763-.273-.694-.635-1.283-1.194-1.842C20.074 1.456 19.485.92 18.79.647c-.694-.273-1.413-.458-2.763-.513C14.683.015 14.277 0 11 0zm0 1.802c3.16 0 3.523.013 4.76.068 1.17.052 1.787.226 2.15.372.44.17.748.39.972.614.225.225.443.532.615.972.146.363.32.98.372 2.15.055 1.237.068 1.6.068 4.76s-.013 3.523-.068 4.76c-.052 1.17-.226 1.787-.372 2.15-.17.44-.39.748-.615.972-.225.225-.532.443-.972.615-.363.146-.98.32-2.15.372-1.237.055-1.6.068-4.76.068s-3.523-.013-4.76-.068c-1.17-.052-1.787-.226-2.15-.372-.44-.17-.748-.39-.972-.615-.225-.225-.443-.532-.615-.972-.146-.363-.32-.98-.372-2.15C2.013 14.903 2 14.54 2 11.383s.013-3.523.068-4.76c.052-1.17.226-1.787.372-2.15.17-.44.39-.748.615-.972.225-.225.532.443.972-.615.363.146.98-.32 2.15-.372C6.86 1.815 7.223 1.802 10.383 1.802H12zm0 4.474c-2.533 0-4.588 2.055-4.588 4.588s2.055 4.588 4.588 4.588 4.588-2.055 4.588-4.588-2.055-4.588-4.588-4.588zm0 7.373c-1.54 0-2.785-1.245-2.785-2.785S10.46 8.49 12 8.49s2.785 1.245 2.785 2.785-1.245 2.785-2.785 2.785zm4.965-7.48c-.62 0-1.125.503-1.125 1.125s.504 1.125 1.125 1.125 1.125-.503 1.125-1.125-.504-1.125-1.125-1.125z" clip-rule="evenodd"/></svg>
                </a>
                <a href="#" class="text-zylver-cream hover:text-zylver-gold" aria-label="Facebook">
                    {{-- Placeholder for Facebook SVG Icon --}}
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                </a>
                <a href="#" class="text-zylver-cream hover:text-zylver-gold" aria-label="YouTube">
                    {{-- Placeholder for YouTube SVG Icon --}}
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.531.9 1.762 1.762C22 8.28 22 12 22 12s0 3.72-.426 4.82c-.23.861-.9 1.531-1.762 1.762C18.72 19 12 19 12 19s-6.72 0-7.82-.426c-.861-.23-1.531-.9-1.762-1.762C2 15.72 2 12 2 12s0-3.72.426-4.82c.23-.861.9-1.531 1.762-1.762C5.28 5 12 5 12 5s6.72 0 7.812.418zM9.757 15.572V8.428L15.814 12 9.757 15.572z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>

        <!-- Column 2: Customer Care -->
        <div class="space-y-4">
            <h3 class="font-fraunces text-lg font-semibold text-zylver-cream">Customer Care</h3>
            <ul class="space-y-2 font-lato text-sm">
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Contact Us</a></li>
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">FAQ</a></li>
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Shipping Policy</a></li>
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Return & Refund Policy</a></li>
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Payment Policy</a></li>
            </ul>
        </div>

        <!-- Column 3: About ZYLVER -->
        <div class="space-y-4">
            <h3 class="font-fraunces text-lg font-semibold text-zylver-cream">About ZYLVER</h3>
            <ul class="space-y-2 font-lato text-sm">
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Our Story</a></li>
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Craftsmanship</a></li>
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Terms & Conditions</a></li>
                <li><a href="#" class="hover:text-zylver-gold transition-colors duration-200">Privacy Policy</a></li>
            </ul>
        </div>





        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

        <!-- News Letter subscription -->
        @if (core()->getConfigData('customer.settings.newsletter.subscription'))
            <div class="space-y-4">
                <h3 class="font-fraunces text-lg font-semibold text-zylver-cream">
                    Stay in Touch
                </h3>
                <p class="font-lato text-sm text-zylver-cream/80">
                    Subscribe to our newsletter for exclusive collections, stories, and offers.
                </p>

                <div>
                    <x-shop::form
                        :action="route('shop.subscription.store')"
                        class="mt-2"
                    >
                        <div class="relative w-full">
                            <x-shop::form.control-group.control
                                type="email"
                                class="w-full appearance-none rounded-md border border-zylver-cream/30 bg-zylver-olive-green/50 px-4 py-2.5 text-sm text-zylver-cream placeholder-zylver-cream/70 focus:border-zylver-gold focus:ring-zylver-gold"
                                name="email"
                                rules="required|email"
                                label="Email"
                                :aria-label="trans('shop::app.components.layouts.footer.email')"
                                placeholder="email@example.com"
                            />
    
                            <x-shop::form.control-group.error control-name="email" />
    
                            <button
                                type="submit"
                                class="absolute top-0 right-0 h-full flex items-center rounded-r-md bg-zylver-gold px-4 py-2.5 text-sm font-lato font-semibold text-zylver-olive-green hover:bg-zylver-cream hover:text-zylver-olive-green focus:outline-none focus:ring-2 focus:ring-zylver-gold focus:ring-offset-2 focus:ring-offset-zylver-olive-green"
                            >
                                @lang('shop::app.components.layouts.footer.subscribe')
                            </button>
                        </div>
                    </x-shop::form>
                </div>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
    </div>

    <!-- Copyright Section -->
    <div class="border-t border-zylver-cream/20 mt-8 pt-8">
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.before') !!}
        <p class="text-center font-lato text-sm text-zylver-cream/80">
            &copy; {{ date('Y') }} ZYLVER.IN. All rights reserved.
        </p>
        {!! view_render_event('bagisto.shop.layout.footer.footer_text.after') !!}
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
