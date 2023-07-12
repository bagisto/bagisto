<x-shop::layouts>
    {{-- Loop over the theme customization --}}
    @foreach ($customizations as $customization)
        @php($data = $customization->options)

        {{-- Static content --}}
        @switch($customization->type)
            {{-- Image Carousel --}}
            @case($customization::IMAGE_CAROUSEL)
                <x-shop::slider
                    :options="$data"
                >
                </x-shop::slider>

            @break

            @case($customization::STATIC_CONTENT)
                {{-- push style --}}
                @push('styles')
                    <style>
                        {{ $data['css'] }}
                    </style>
                @endpush

                {{-- render html --}}
                {!! $data['html'] !!}

                @break

            @case($customization::CATEGORY_CAROUSEL)
                {{-- Categories carousel --}}
                <x-shop::categories.carousel
                    :title="$customization->name"
                    :src="route('shop.api.categories.index', $data)"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::categories.carousel>

                @break

            @case($customization::PRODUCT_CAROUSEL)
                {{-- Product Carousel --}}
                <x-shop::products.carousel
                    {{-- title="Men's Collections" --}}
                    :title="$customization->name"
                    :src="route('shop.api.products.index', $data)"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::products.carousel>

                @break
        @endswitch
    @endforeach
</x-shop::layouts>
