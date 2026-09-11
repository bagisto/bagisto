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
    @if(! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- Loop over the storefront sections -->
    @foreach ($sections as $section)
        @php ($data = $section->options) @endphp

        {{-- The layout marks the types it draws on every page, so this page marks the rest. --}}
        @php ($marks = ($preview ?? false) && ! $section->getTypeInstance()?->rendersInLayout())

        @if ($marks)
            <div
                data-section-id="{{ $section->id }}"
                data-section-name="{{ $section->name }}"
            >
        @endif

        <!-- Static Content -->
        @switch ($section->type)
            @case (\Webkul\Theme\Enums\SectionTypeEnum::IMAGE_CAROUSEL->value)
                <!-- Image Carousel -->
                <x-shop::carousel
                    :options="$data"
                    aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
                />

                @break
            @case (\Webkul\Theme\Enums\SectionTypeEnum::STATIC_CONTENT->value)
                <!-- Push Style -->
                @if (! empty($data['css']))
                    @push ('styles')
                        <style>
                            {!! $data['css'] !!}
                        </style>
                    @endpush
                @endif

                <!-- Render HTML -->
                @if (! empty($data['html']))
                    {!! $data['html'] !!}
                @endif

                @break
            @case (\Webkul\Theme\Enums\SectionTypeEnum::CATEGORY_CAROUSEL->value)
                <!-- Categories carousel -->
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                />

                @break
            @case (\Webkul\Theme\Enums\SectionTypeEnum::PRODUCT_CAROUSEL->value)
                <!-- Product Carousel -->
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                />

                @break
        @endswitch

        @if ($marks)
            </div>
        @endif
    @endforeach

    @if ($preview ?? false)
        @include('shop::home.preview-bridge')
    @endif
</x-shop::layouts>
