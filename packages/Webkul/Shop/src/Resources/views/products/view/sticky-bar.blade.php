@if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
    <!-- Mobile Sticky Add To Cart Bar -->
    <transition
        enter-active-class="transition duration-300 ease-out transform"
        enter-from-class="translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in transform"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-full opacity-0"
    >
        <div
            v-show="isStickyBarVisible"
            class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-zinc-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] p-3 1180:hidden dark:bg-gray-900/95 dark:border-gray-800"
            role="region"
            aria-label="@lang('shop::app.products.view.add-to-cart')"
        >
            <div class="flex items-center justify-between gap-3 max-w-lg mx-auto">
                <!-- Product Thumbnail & Details -->
                <div class="flex items-center gap-3 overflow-hidden min-w-0">
                    <img
                        src="{{ $productBaseImage['small_image_url'] ?? $productBaseImage['medium_image_url'] }}"
                        alt="{{ $product->name }}"
                        class="h-11 w-11 flex-shrink-0 rounded-lg object-cover border border-zinc-200 dark:border-gray-800"
                        loading="lazy"
                    />

                    <div class="truncate">
                        <p class="truncate text-xs font-medium text-zinc-900 dark:text-zinc-100" v-pre>
                            {{ $product->name }}
                        </p>

                        <div class="text-sm font-semibold text-navyBlue dark:text-white">
                            {!! $product->getTypeInstance()->getPriceHtml() !!}
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="flex-shrink-0">
                    <button
                        type="button"
                        class="secondary-button text-sm px-5 py-2.5 rounded-xl font-medium shadow-sm transition-transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="isStoring.addToCart || ! {{ $product->isSaleable(1) ? 'true' : 'false' }}"
                        @click="handleStickyAddToCart"
                    >
                        <span v-if="isStoring.addToCart" class="flex items-center gap-2">
                            <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
                            @lang('shop::app.products.view.add-to-cart')
                        </span>
                        <span v-else>
                            @lang('shop::app.products.view.add-to-cart')
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </transition>
@endif
