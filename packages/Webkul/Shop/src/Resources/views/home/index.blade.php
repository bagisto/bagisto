<x-shop::layouts>
    {{-- Loop over the theme customization --}}
    @foreach ($customizations as $customization)
        @php($data = json_decode($customization->options))

        {{-- Static content --}}
        @switch($customization->type)
            {{-- Image Carousel --}}
            @case($customization::IMAGE_CAROUSEL)
                <div class="bs-hero-section">
                    @foreach ($data->images as $image)
                        <picture>
                            <img src="{{ $image }}" />
                        </picture>
                    @endforeach
                </div>

            @break

            @case($customization::STATIC_CONTENT)
                {{-- push style --}}
                @push('styles')
                    <style>
                        {{ $data->css }}
                    </style>
                @endpush

                {{-- render html --}}
                {!! $data->html !!}

                @break

            @case($customization::CATEGORY_CAROUSEL)
                {{-- Categories carousel --}}
                <x-shop::categories.carousel
                    :title="$customization->name"
                    :src="route('shop.api.categories.index', json_decode($customization->options, true))"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::categories.carousel>

                @break

            @case($customization::PRODUCT_CAROUSEL)
                {{-- Carousel --}}
                <x-shop::products.carousel
                    {{-- title="Men's Collections" --}}
                    :title="$customization->name"
                    :src="route('shop.api.products.index', json_decode($customization->options, true))"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::products.carousel>

                @break
        @endswitch
    @endforeach
</x-shop::layouts>
