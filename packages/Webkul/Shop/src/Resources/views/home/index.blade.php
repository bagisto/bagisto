<x-shop::layouts>
    {{-- hero section --}}
	<div class="bs-hero-section">
		<picture>
			<img alt="" src="{{ bagisto_asset('images/hero-image.webp') }}" />
		</picture>

		<div class="">
			<a href="/" class="block text-[22px] py-[20px] font-medium text-center bg-[#E8EDFE] font-dmserif">Get UPTO 40% OFF on
				your 1st order SHOP NOW</a>
		</div>
	</div>

    {{-- product collection --}}
	@php
		$products = [
			[
				'name' => 'Test 1'
			], [
				'name' => 'Test 2'
			]
		]
	@endphp

	<x-shop::products.carousel
        title="Men’s Collections"
        :products="$products"
        :navigation-link="route('shop.home.index')"
    >
    </x-shop::products.carousel>
</x-shop::layouts>
