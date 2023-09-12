@php
    $channel = core()->getCurrentChannel();
@endphp


{{-- SEO Meta Content --}}
@push ('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />

    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />

    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

<x-shop::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>
    
    {{-- Loop over the theme customization --}}
    @foreach ($customizations as $customization)
        @php ($data = $customization->options)

        {{-- Static content --}}
        @switch ($customization->type)
            {{-- Image Carousel --}}
            @case ($customization::IMAGE_CAROUSEL)
                <x-shop::carousel :options="$data"></x-shop::carousel>

                @break

            @case ($customization::STATIC_CONTENT)
                {{-- push style --}}
                @push ('styles')
                    <style>
                        {{ $data['css'] }}
                    </style>
                @endpush

                {{-- render html --}}
                {!! $data['html'] !!}

                @break

            @case ($customization::CATEGORY_CAROUSEL)
                {{-- Categories carousel --}}
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::categories.carousel>

                @break

            @case ($customization::PRODUCT_CAROUSEL)
                {{-- Product Carousel --}}
                <x-shop::products.carousel
                    {{-- title="Men's Collections" --}}
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::products.carousel>

                @break
        @endswitch
    @endforeach
</x-shop::layouts>
