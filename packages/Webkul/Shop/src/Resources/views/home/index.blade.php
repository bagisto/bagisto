<x-shop::layouts>
    {{-- Hero Section --}}
	<div class="bs-hero-section">
		<picture>
			<img alt="" src="{{ bagisto_asset('images/hero-image.webp') }}" />
		</picture>

		<div class="">
			<a
                href="javascript:void(0);"
                class="block text-[22px] py-[20px] font-medium text-center bg-[#E8EDFE] font-dmserif"
            >
                @lang('shop::app.home.index.offer')
            </a>
		</div>
	</div>

    {{-- Categories carousel --}}
    <x-shop::categories.carousel
        title="Categories Collections"
        :src="route('shop.api.categories.index', ['only_children' => true])"
        :navigation-link="route('shop.home.index')"
    >
    </x-shop::categories.carousel>

    {{-- Carousel --}}
	<x-shop::products.carousel
        title="Men's Collections"
        :src="route('shop.api.products.index')"
        :navigation-link="route('shop.home.index')"
    >
    </x-shop::products.carousel>
</x-shop::layouts>
