@php
    $channel = core()->getCurrentChannel();

    $homeSEO = $channel->home_seo;

    if (isset($homeSEO)) {
        $homeSEO = json_decode($channel->home_seo);

        $metaTitle = $homeSEO->meta_title;

        $metaDescription = $homeSEO->meta_description;

        $metaKeywords = $homeSEO->meta_keywords;
    }
@endphp


{{-- SEO Meta Content --}}
@push ('meta')
    <meta name="title" content="{{ $metaTitle }}" />

    <meta name="description" content="{{ $metaDescription }}" />

    <meta name="keywords" content="{{ $metaKeywords }}" />
@endPush

<x-shop::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        {{ $metaTitle ?? "" }}
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
