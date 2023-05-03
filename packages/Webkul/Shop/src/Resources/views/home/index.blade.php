<x-layouts>
    <x-slot:title>
        {{ isset($metaTitle) ? $metaTitle : "" }}
    </x-slot>

    <!-- Hero section -->
	<div class="bs-hero-section">
		<picture>
			<img alt="" src="{{ Vite::useHotFile('shop-vite.hot')->useBuildDirectory('themes/default/build')->asset('src/Resources/assets/images/hero-image.webp') }}" />
		</picture>

		<div class="">
			<a href="/" class="block text-[22px] py-[20px] font-medium text-center bg-[#E8EDFE] font-dmserif">Get UPTO 40% OFF on
				your 1st order SHOP NOW</a>
		</div>
	</div>

	<!-- The game with our new additions! section -->
	<div class="section-title mt-20 max-sm:mt-[30px]">
		<h2 class="max-w-[595px] mx-auto font-dmserif">The game with our new additions!</h2>
	</div>

	<div class="container mt-[60px] max-lg:px-[30px] max-sm:mt-[20px]">
		<div class="bs-item-carousal-wrapper relative">
			<div class="flex gap-10 overflow-auto scrollbar-hide">
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/womens.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/mens.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/kids.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/glasses.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/leather-bag.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/shoes.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/fitness.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/womens.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/shoes.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
				<div class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]">
					<picture class="bg-[#F5F5F5] rounded-full">
						<img alt="" src="{{ bagisto_asset('/images/kids.png') }}" />
					</picture>
					<p class="text-black text-[20px] font-medium">Womens</p>
				</div>
			</div>
			<span
				class="bs-carousal-next flex border border-black items-center justify-center rounded-full w-[50px] h-[50px] bg-white absolute top-[37px] -left-[41px] cursor-pointer transition hover:bg-black before:content-[' '] before:bs-main-sprite before:bg-[position:-122px_-137px] before:w-[21px] before:h-[20px] before:block before:hover:invert max-lg:-left-[29px]"></span>
			<span
				class="bs-carousal-prev flex border border-black items-center justify-center rounded-full w-[50px] h-[50px] bg-white absolute top-[37px] -right-[22px] cursor-pointer transition hover:bg-black before:content-[' '] before:bs-main-sprite before:bg-[position:-147px_-137px] before:w-[21px] before:h-[20px] before:block before:hover:invert max-lg:-right-[29px]"></span>
		</div>
	</div>
	<!-- men collection -->
	<div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
		<div class="flex justify-between">
			<h3 class="text-[30px] max-sm:text-[25px] font-dmserif">Men’s Collections</h3>
			<div class="flex justify-between items-center gap-8">
				<span
					class="bg-[position:-122px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
				<span
					class="bg-[position:-147px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
			</div>
		</div>
		<div class="flex gap-8 mt-[60px] overflow-auto scrollbar-hide max-sm:mt-[20px]">
			<div class="bs-single-card relative min-w-[291px]">
				<div class="">
					<img class="" src="{{ bagisto_asset('/images/mens-collections.jpg') }}">
				</div>
				<div class="action-items">
					<p
						class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]">
						New</p>
					<span
						class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[20px] right-[20px] before:content-[' '] before:bg-[position:-170px_-65px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"></span>
					<span
						class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[60px] right-[20px] before:content-[' '] before:bg-[position:-98px_-90px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"></span>
					<div
						class="rounded-xl bg-white text-navyBlue text-xs w-max font-medium py-[11px] px-[43px]  absolute top-[244px] left-[50%] -translate-x-[50%]">
						Add to cart</div>
				</div>
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4] active"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/mens-collections.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/mens-collections.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/mens-collections.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/mens-collections.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
		</div>
		<button
			class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center">View
			All</button>
	</div>

	<!-- our collection top -->
	<div class="section-title mt-20 max-sm:mt-[30px]">
		<h2 class="max-w-[595px] mx-auto font-dmserif">The game with our new additions!</h2>
	</div>

	<div class="container mt-[60px] max-lg:px-[30px] max-sm:mt-[20px]">
		<div class="flex justify-center gap-8 flex-wrap max-sm:gap-[15px]">
			<div class="relative">
				<img class="max-w-[396px] min-h-[396px] rounded-2xl bg-[#F5F5F5] max-sm:max-w-full max-sm:min-h-full" src="{{ bagisto_asset('/images/our-collection.jpg') }}"
					alt="" title="">
				<h3 class="w-max text-[30px] text-navyBlue absolute bottom-[30px] left-[50%] -translate-x-[50%] font-dmserif">Our
					Collections</h3>
			</div>
			<div class="relative">
				<img class="max-w-[396px] min-h-[396px] rounded-2xl bg-[#F5F5F5] max-sm:max-w-full max-sm:min-h-full" src="{{ bagisto_asset('/images/our-collection') }}-2.jpg"
					alt="" title="">
				<h3 class="w-max text-[30px] text-navyBlue absolute bottom-[30px] left-[50%] -translate-x-[50%] font-dmserif">Our
					Collections </h3>
			</div>
			<div class="relative">
				<img class="max-w-[396px] min-h-[396px] rounded-2xl bg-[#F5F5F5] max-sm:max-w-full max-sm:min-h-full" src="{{ bagisto_asset('/images/our-collection.jpg') }}"
					alt="" title="">
				<h3 class="w-max text-[30px] text-navyBlue absolute bottom-[30px] left-[50%] -translate-x-[50%] font-dmserif">Our
					Collections </h3>
			</div>
			<div class="relative">
				<img class="max-w-[396px] min-h-[396px] rounded-2xl bg-[#F5F5F5] max-sm:max-w-full max-sm:min-h-full" src="{{ bagisto_asset('/images/our-collection-2.jpg') }}"
					alt="" title="">
				<h3 class="w-max text-[30px] text-navyBlue absolute bottom-[30px] left-[50%] -translate-x-[50%] font-dmserif">Our
					Collections </h3>
			</div>
			<div class="relative">
				<img class="max-w-[396px] min-h-[396px] rounded-2xl bg-[#F5F5F5] max-sm:max-w-full max-sm:min-h-full" src="{{ bagisto_asset('/images/our-collection.jpg') }}"
					alt="" title="">
				<h3 class="w-max text-[30px] text-navyBlue absolute bottom-[30px] left-[50%] -translate-x-[50%] font-dmserif">Our
					Collections </h3>
			</div>
			<div class="relative">
				<img class="max-w-[396px] min-h-[396px] rounded-2xl bg-[#F5F5F5] max-sm:max-w-full max-sm:min-h-full" src="{{ bagisto_asset('/images/our-collection-2.jpg') }}"
					alt="" title="">
				<h3 class="w-max text-[30px] text-navyBlue absolute bottom-[30px] left-[50%] -translate-x-[50%] font-dmserif">Our
					Collections </h3>
			</div>
		</div>
	</div>

	<!-- Get ready -->
	<div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
		<div class="grid grid-cols-[auto_1fr] gap-x-[60px] items-center max-991:grid-cols-[1fr] max-991:gap-[16px]">
			<div class="max-w-[632px]">
				<img class="rounded-2xl" src="{{ bagisto_asset('/images/inline-collection.jpg') }}" alt="" title="">
			</div>
			<div class="flex flex-wrap gap-[20px] max-w-[464px] max-991:gap-[10px]">
				<h2 class="max-w-[442px] text-[60px] font-normal text-navyBlue leading-[70px] max-sm:text-[30px] max-sm:leading-normal font-dmserif">Get Ready for our new Bold Collections!
				</h2>
				<p class="text-[#7D7D7D] text-[18px]">Buy prodcuts in groups for bigger discounts. like lorem ipsume is
					simply text for digital platfromn</p>
				<button
					class="block text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] border-navyBlue bg-navyBlue text-center after:content[' '] after:inline-block after:bg-[position:-49px_-99px] after:bs-main-sprite after:w-[21px] after:h-[20px] after:align-middle after:ml-[10px] max-991:mt-[10px]">View
					All</button>
			</div>
		</div>
	</div>
	<!-- Women Collection -->
	<div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
		<div class="flex justify-between">
			<h3 class="text-[30px] max-sm:text-[25px] font-dmserif">Women’s Collections</h3>
			<div class="flex justify-between items-center gap-8">
				<span class="bg-[position:-122px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
				<span class="bg-[position:-147px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
			</div>
		</div>
		<div class="flex gap-8 mt-[60px] overflow-auto scrollbar-hide max-sm:mt-[20px]">
			<div class="bs-single-card relative min-w-[291px]">
				<div class="">
					<img class="" src="{{ bagisto_asset('/images/women-collection-1.jpg') }}">
				</div>
				<div class="action-items">
					<p
						class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]">
						New</p>
					<span
						class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[20px] right-[20px] before:content-[' '] before:bg-[position:-170px_-65px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"></span>
					<span
						class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[60px] right-[20px] before:content-[' '] before:bg-[position:-98px_-90px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"></span>
					<div
						class="rounded-xl bg-white text-navyBlue text-xs w-max font-medium py-[11px] px-[43px]  absolute top-[244px] left-[50%] -translate-x-[50%]">
						Add to cart</div>
				</div>
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4] active"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/women-collection2.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/women-collection-1.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/women-collection2.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/women-collection2.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
		</div>
		<button
			class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center">View
			All</button>
	</div>

	<!-- our collection -->
	<div class="section-title mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
		<h2 class="max-w-[595px] mx-auto font-dmserif">The game with our new additions!</h2>
	</div>

	<div class="container mt-[60px] max-lg:px-[30px] max-sm:mt-[20px]">
		<div class="flex justify-center gap-[30px] max-991:flex-wrap">
			<div class="relative">
				<img class="rounded-2xl bg-[#F5F5F5]" src="{{ bagisto_asset('/images/our-collection-bottom.jpg') }}" alt="" title="">
				<h3 class="text-[50px] max-w-[234px] italic  text-navyBlue absolute bottom-[30px] left-[30px] max-sm:text-[30px] font-dmserif">Our
					Collections</h3>
			</div>
			<div class="relative">
				<img class="rounded-2xl bg-[#F5F5F5]" src="{{ bagisto_asset('/images/collection-bottom.jpg') }}" alt="" title="">
				<h3 class="text-[50px] max-w-[234px] italic  text-navyBlue absolute bottom-[30px] left-[30px] max-sm:text-[30px] font-dmserif">Our
					Collections </h3>
			</div>
		</div>
	</div>

	<!-- Kid collection -->
	<div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
		<div class="flex justify-between">
			<h3 class="text-[30px] max-sm:text-[25px] font-dmserif">Kid’s Collections</h3>
			<div class="flex justify-between items-center gap-8">
				<span class="bg-[position:-122px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
				<span class="bg-[position:-147px_-137px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
			</div>
		</div>
		<div class="flex gap-8 mt-[60px] overflow-auto scrollbar-hide max-sm:mt-[20px]">
			<div class="bs-single-card relative min-w-[291px]">
				<div class="">
					<img class="" src="{{ bagisto_asset('/images/kids-1.jpg') }}">
				</div>
				<div class="action-items">
					<p
						class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]">
						New</p>
					<span
						class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[20px] right-[20px] before:content-[' '] before:bg-[position:-170px_-65px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"></span>
					<span
						class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[60px] right-[20px] before:content-[' '] before:bg-[position:-98px_-90px] before:bs-main-sprite before:w-[21px] before:h-[20px] before:block"></span>
					<div
						class="rounded-xl bg-white text-navyBlue text-xs w-max font-medium py-[11px] px-[43px]  absolute top-[244px] left-[50%] -translate-x-[50%]">
						Add to cart</div>
				</div>
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4] active"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/kids-2.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/kids-3.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/kids-4.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
			<div class="bs-single-card min-w-[291px]">
				<img class="" src="{{ bagisto_asset('/images/kids-4.jpg') }}">
				<p class="text-base">Slim High Ankle Jeans</p>
				<div class="price-block">
					<p class="offer-price">$20.00</p>
					<p class="original-price">$30.00</p>
				</div>
				<div class="change-card-color">
					<span class="bg-[#B5DCB4]"></span>
					<span class="bg-[#5C5C5C]"></span>
				</div>
			</div>
		</div>
		<button
			class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center">View
			All</button>
	</div>

	<!-- Get ready bottom -->
	<div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]">
		<div class="grid grid-cols-[auto_1fr] gap-x-[60px] items-center justify-items-end direction-rtl max-991:grid-cols-[1fr] max-991:gap-[16px]">
			<div class="max-w-[632px]">
				<img class="rounded-2xl" src="{{ bagisto_asset('/images/inline-collection.jpg') }}" alt="" title="">
			</div>
			<div class="flex flex-wrap gap-[20px] max-w-[464px] direction-ltr max-991:gap-[10px]">
				<h2 class="max-w-[442px] text-[60px] text-navyBlue font-normal leading-[70px] max-sm:text-[30px] max-sm:leading-normal font-dmserif">Get Ready for our new Bold Collections!
				</h2>
				<p class="text-[#7D7D7D] text-[18px] font-dmserif">Buy prodcuts in groups for bigger discounts. like lorem ipsume is
					simply text for digital platfromn</p>
				<button
					class="block text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] border-navyBlue bg-navyBlue text-center after:content[' '] after:inline-block after:bg-[position:-49px_-99px] after:bs-main-sprite after:w-[21px] after:h-[20px] after:align-middle after:ml-[10px] max-991:mt-[10px]">View
					All</button>
			</div>
		</div>
	</div>

</x-layouts>
