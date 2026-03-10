<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<title>SBT</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400&family=Roboto:wght@500&display=swap" rel="stylesheet">

</head>

<body class="bg-gray-100">

<div class="max-w-7xl mx-auto py-10 px-4">

<!-- Logo -->

<div class="flex justify-center mb-10">
<a href="{{ route('spa.home') }}">
<img src="{{ asset('themes/shop/default/images/logo.png') }}" class="h-10 md:h-14">
</a>
</div>

<!-- GRID -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

<!-- SPA SERVICE -->

<div class="relative bg-[#D4AF37] rounded-[28px] overflow-hidden text-white pt-8 pl-8 pr-6 pb-8 h-[420px] md:h-[460px] lg:h-[520px]">

<h2 class="font-oswald text-[34px] md:text-[40px] lg:text-[48px] uppercase mb-4">
SPA SERVICE
</h2>

<ul class="font-roboto text-[14px] md:text-[15px] lg:text-[16px] leading-[28px] md:leading-[30px] lg:leading-[32px] mb-6">
<li>Facial</li>
<li>Eye Lashes & Eyebrow Bar</li>
<li>Nail Services Bar</li>
<li>Massage</li>
<li>Hair Treatment</li>
<li>Threading Bar</li>
<li>Waxing Bar</li>
</ul>

<a href="{{ route('shop.home.index') }}"
class="inline-flex items-center justify-center w-[160px] h-[42px] md:w-[170px] md:h-[45px] rounded-full bg-white text-[#371E0F] font-oswald text-[15px] md:text-[16px] uppercase">
BOOK SERVICES </a>

<img src="{{ asset('themes/shop/default/images/landing_page_one.png') }}"
class="absolute bottom-0 -right-10 h-[260px] md:h-[320px] lg:h-[380px] object-contain pointer-events-none">

</div>

<!-- PERFUME -->

<div class="relative rounded-[28px] pt-10 pl-8 pr-8 pb-6 h-[300px] md:h-[460px] lg:h-[520px] overflow-hidden bg-[#D0CBC1]">

<h2 class="font-oswald text-[34px] md:text-[40px] lg:text-[48px] uppercase text-[#371E0F] mb-6">
PERFUME
</h2>

<a href="{{ route('sbt.perfume.index') }}"
class="inline-flex items-center justify-center w-[160px] h-[42px] md:w-[170px] md:h-[45px] rounded-full bg-[#371E0F] text-white font-oswald text-[15px] md:text-[16px] uppercase">
BUY PERFUMES </a>

<img src="{{ asset('themes/shop/default/images/landing_page_two.png') }}"
class="absolute bottom-0 left-0 w-full object-contain">

</div>

<!-- RIGHT COLUMN (Desktop only container) -->

<div class="grid grid-rows-2 gap-6 lg:h-[520px] md:col-span-2 lg:col-span-1">

<!-- SPA PRODUCTS -->

<div class="relative rounded-2xl overflow-hidden h-[240px] md:h-[220px] lg:h-full">

<img src="{{ asset('themes/shop/default/images/third_a.png') }}" class="w-full h-full object-cover">

<div class="absolute inset-0 flex flex-col items-center justify-center gap-4 md:gap-5">

<h2 class="font-oswald text-[28px] md:text-[32px] lg:text-[48px] uppercase text-[#371E0F] text-center">
SPA PRODUCTS
</h2>

<a href="{{ route('spa.product.index') }}"
class="inline-flex items-center justify-center px-6 md:px-8 py-2 md:py-3 rounded-full bg-[#371E0F] text-white font-oswald text-[14px] md:text-[16px] lg:text-[18px] uppercase">
BUY PRODUCTS </a>

</div>

</div>

<!-- FLOWERS -->

<div class="relative rounded-2xl overflow-hidden h-[240px] md:h-[220px] lg:h-full">

<img src="{{ asset('themes/shop/default/images/third_b.png') }}"
class="absolute inset-0 w-full h-full object-cover">

<div class="relative flex items-center h-full px-6 md:px-8">

<div class="flex flex-col gap-4 md:gap-5">

<h2 class="font-oswald text-[26px] md:text-[30px] lg:text-[40px] uppercase text-[#371E0F] leading-[120%]">
FLOWERS <br> PRODUCT
</h2>

<a href="{{ route('flower.product.index') }}"
class="inline-flex items-center justify-center px-6 md:px-8 py-2 md:py-3 rounded-full bg-[#DBA585] text-[#371E0F] font-oswald text-[14px] md:text-[16px] lg:text-[18px] uppercase">
BUY FLOWERS </a>

</div>

</div>

</div>

</div>

</div>

</body>
</html>
