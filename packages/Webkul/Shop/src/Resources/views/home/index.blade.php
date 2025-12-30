@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

@push('scripts')
    <script>
        localStorage.setItem('categories', JSON.stringify(@json($categories)));
    </script>
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- RAM Plaza Hero - Muro Loco Style Carousel -->
    <div class="muro-loco-carousel" id="muro-loco-carousel">
        <!-- Global Rain Effect (constant across all slides) -->
        <div class="muro-loco-rain-container">
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
            <span class="muro-loco-rain-drop">%</span>
            <span class="muro-loco-rain-drop">🔥</span>
            <span class="muro-loco-rain-drop">💎</span>
            <span class="muro-loco-rain-drop">🏷️</span>
            <span class="muro-loco-rain-drop">⚡</span>
            <span class="muro-loco-rain-drop">⭐</span>
            <span class="muro-loco-rain-drop">🎁</span>
        </div>

        <!-- Slide 1: RAM PLAZA Hero -->
        <div class="muro-loco-header muro-loco-slide-hero active" data-slide="0">
            <!-- Spotlight Beams (7) -->
            <div class="muro-loco-spotlight-container">
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-source"></div>
            </div>

            <!-- Bubbles -->
            <div class="muro-loco-bubbles">
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
            </div>

            <!-- Hero Content - Centered vertical layout -->
            <div class="muro-loco-hero-content">
                <div class="muro-loco-hero-center">
                    <h1 class="muro-loco-title-hero">
                        <i class="icon-star-fill"></i>
                        <span class="muro-loco-letter">R</span><span class="muro-loco-letter">A</span><span class="muro-loco-letter">M</span><span class="muro-loco-letter"> </span><span class="muro-loco-letter">P</span><span class="muro-loco-letter">L</span><span class="muro-loco-letter">A</span><span class="muro-loco-letter">Z</span><span class="muro-loco-letter">A</span>
                    </h1>
                    <p class="muro-loco-hero-subtitle">@lang('shop::app.home.index.hero-subtitle')</p>
                    <div class="muro-loco-badges">
                        <span class="muro-loco-badge descuento"><i class="icon-star-fill"></i> @lang('shop::app.home.index.badge-offers')</span>
                        <span class="muro-loco-badge nuevo"><i class="icon-star"></i> @lang('shop::app.home.index.badge-new')</span>
                        <span class="muro-loco-badge hot"><i class="icon-truck"></i> @lang('shop::app.home.index.badge-free-shipping')</span>
                    </div>
                </div>
            </div>

            <!-- Promo Ticker -->
            <div class="muro-loco-promo-ticker">
                <div class="muro-loco-promo-track">
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                </div>
            </div>
        </div>

        <!-- Slide 2: Ofertas -->
        <div class="muro-loco-header muro-loco-slide-content" data-slide="1">
            <div class="muro-loco-spotlight-container">
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-source"></div>
            </div>
            <div class="muro-loco-bubbles">
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
            </div>
            <!-- Content -->
            <div class="muro-loco-slide-inner">
                <div class="muro-loco-title">
                    <h2><i class="icon-star-fill"></i> @lang('shop::app.home.index.slide-offers-title')</h2>
                </div>
                <p class="muro-loco-subtitle">@lang('shop::app.home.index.slide-offers-subtitle')</p>
                <div class="muro-loco-cta">
                    <a href="{{ route('shop.search.index') }}" class="muro-loco-btn-contact">
                        <i class="icon-cart"></i> @lang('shop::app.home.index.slide-offers-cta')
                    </a>
                </div>
            </div>
            <div class="muro-loco-promo-ticker">
                <div class="muro-loco-promo-track">
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                </div>
            </div>
        </div>

        <!-- Slide 3: Categorías -->
        <div class="muro-loco-header muro-loco-slide-content" data-slide="2">
            <div class="muro-loco-spotlight-container">
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-beam"></div>
                <div class="muro-loco-spotlight-source"></div>
            </div>
            <div class="muro-loco-bubbles">
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
                <div class="muro-loco-bubble"></div>
            </div>
            <!-- Content -->
            <div class="muro-loco-slide-inner">
                <div class="muro-loco-title">
                    <h2><i class="icon-product"></i> @lang('shop::app.home.index.slide-categories-title')</h2>
                </div>
                <p class="muro-loco-subtitle">@lang('shop::app.home.index.slide-categories-subtitle')</p>
                <div class="muro-loco-cta">
                    <a href="{{ route('shop.home.index') }}" class="muro-loco-btn-contact">
                        <i class="icon-search"></i> @lang('shop::app.home.index.slide-categories-cta')
                    </a>
                </div>
            </div>
            <div class="muro-loco-promo-ticker">
                <div class="muro-loco-promo-track">
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-offers')</span>
                    <span class="muro-loco-promo-item"><i class="icon-truck"></i> @lang('shop::app.home.index.promo-shipping')</span>
                    <span class="muro-loco-promo-item"><i class="icon-dollar-sign"></i> @lang('shop::app.home.index.promo-payments')</span>
                    <span class="muro-loco-promo-item"><i class="icon-star"></i> @lang('shop::app.home.index.promo-quality')</span>
                    <span class="muro-loco-promo-item"><i class="icon-box-fill"></i> @lang('shop::app.home.index.promo-gifts')</span>
                </div>
            </div>
        </div>

        <!-- Carousel Indicators -->
        <div class="muro-loco-indicators">
            <div class="muro-loco-indicator active" data-slide="0"></div>
            <div class="muro-loco-indicator" data-slide="1"></div>
            <div class="muro-loco-indicator" data-slide="2"></div>
        </div>
    </div>

    <!-- Carousel JavaScript - Deferred execution to avoid Vue.js conflicts -->
    <script>
        window.addEventListener('load', function() {
            // Wait for Vue.js to finish rendering
            setTimeout(function() {
                var carousel = document.getElementById('muro-loco-carousel');
                if (!carousel) return;

                var slides = carousel.querySelectorAll('.muro-loco-header');
                var indicators = carousel.querySelectorAll('.muro-loco-indicator');
                var currentSlide = 0;
                var autoplayInterval = null;

                function goToSlide(index) {
                    for (var i = 0; i < slides.length; i++) {
                        slides[i].classList.remove('active', 'exit');
                        if (i === currentSlide && i !== index) {
                            slides[i].classList.add('exit');
                        }
                    }

                    for (var j = 0; j < indicators.length; j++) {
                        if (j === index) {
                            indicators[j].classList.add('active');
                        } else {
                            indicators[j].classList.remove('active');
                        }
                    }

                    setTimeout(function() {
                        slides[index].classList.add('active');
                        currentSlide = index;
                    }, 80);
                }

                function nextSlide() {
                    var next = (currentSlide + 1) % slides.length;
                    goToSlide(next);
                }

                for (var k = 0; k < indicators.length; k++) {
                    (function(idx) {
                        indicators[idx].addEventListener('click', function() {
                            goToSlide(idx);
                            clearInterval(autoplayInterval);
                            autoplayInterval = setInterval(nextSlide, 4000);
                        });
                    })(k);
                }

                // Start autoplay
                autoplayInterval = setInterval(nextSlide, 4000);
            }, 500);
        });
    </script>

    <!-- Loop over the theme customization (except IMAGE_CAROUSEL) -->
    @php $sectionCount = 0; @endphp
    @foreach ($customizations as $customization)
        @php ($data = $customization->options) @endphp

        @switch ($customization->type)
            @case ($customization::STATIC_CONTENT)
                <!-- push style -->
                @if (! empty($data['css']))
                    @push ('styles')
                        <style>
                            {{ $data['css'] }}
                        </style>
                    @endpush
                @endif

                <!-- render html -->
                @if (! empty($data['html']))
                    {!! $data['html'] !!}
                @endif
                @php $sectionCount++; @endphp

                @break
            @case ($customization::CATEGORY_CAROUSEL)
                <!-- Categories carousel -->
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                />
                @php $sectionCount++; @endphp

                @break
            @case ($customization::PRODUCT_CAROUSEL)
                <!-- Product Carousel -->
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                />
                @php $sectionCount++; @endphp

                @break
        @endswitch

        {{-- RAM Promo Banner - After 5 sections --}}
        @if ($sectionCount === 5)
            <div class="ram-promo-banner">
                <h3><i class="icon-star-fill"></i> @lang('shop::app.home.index.promo-banner-title') <i class="icon-star-fill"></i></h3>
                <p>@lang('shop::app.home.index.promo-banner-subtitle')</p>
            </div>
        @endif
    @endforeach

    <!-- Image Carousel - Moved to end, before footer -->
    @foreach ($customizations as $customization)
        @if ($customization->type === $customization::IMAGE_CAROUSEL)
            @php ($data = $customization->options) @endphp
            <x-shop::carousel
                :options="$data"
                aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
            />
        @endif
    @endforeach
</x-shop::layouts>
