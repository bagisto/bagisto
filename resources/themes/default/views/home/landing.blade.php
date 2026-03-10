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
<div class="flex justify-center mb-12">
<a href="{{ route('spa.home') }}">
<img src="{{ asset('themes/shop/default/images/logo.png') }}" class="h-10 md:h-16">
</a>
</div>



<!-- GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

<!-- SPA SERVICE -->
<div class="relative bg-[#D4AF37] rounded-[28px] overflow-hidden text-white pt-8 pl-11 pr-6 pb-8 h-[420px] md:h-[480px] lg:h-[520px]">

<h2 class="font-oswald text-[32px] md:text-[40px] lg:text-[48px] uppercase text-white mb-5">
SPA SERVICE
</h2>

<ul class="font-roboto text-[14px] md:text-[16px] leading-[28px] md:leading-[32px] mb-5">

<li>Facial</li>
<li>Eye Lashes & Eyebrow Bar</li>
<li>Nail Services Bar</li>
<li>Massage</li>
<li>Hair Treatment</li>
<li>Threading Bar</li>
<li>Waxing Bar</li>

</ul>

<a href="{{ route('shop.home.index') }}"
class="inline-flex items-center justify-center bg-white text-[#371E0F] rounded-full px-6 py-2 md:px-8 md:py-3 font-oswald text-[14px] md:text-[16px] lg:text-[18px] uppercase relative z-10">
BOOK SERVICES
</a>

<img
src="{{ asset('themes/shop/default/images/landing_page_one.png') }}"
class="absolute bottom-0 -right-10 md:-right-14 lg:-right-12 h-[240px] md:h-[300px] lg:h-[380px] object-contain pointer-events-none">

</div>



<!-- PERFUME -->
<div class="relative rounded-[28px] pt-8 pl-10 pr-6 pb-6 h-[420px] md:h-[480px] lg:h-[520px] overflow-hidden bg-[#D0CBC1]">

<h2 class="font-oswald text-[#371E0F] text-[32px] md:text-[40px] lg:text-[48px] uppercase mb-6">
PERFUME
</h2>

<a href="{{ route('sbt.perfume.index') }}"
class="inline-flex items-center justify-center bg-[#371E0F] text-white rounded-full px-6 py-2 md:px-8 md:py-3 font-oswald text-[14px] md:text-[16px] lg:text-[18px] uppercase relative z-10">
BUY PERFUMES
</a>

<img
src="{{ asset('themes/shop/default/images/landing_page_two.png') }}"
class="absolute bottom-0 left-0 w-full object-contain h-[200px] md:h-[240px] lg:h-auto">

</div>



<!-- RIGHT COLUMN (DESKTOP ONLY) -->
<div class="hidden lg:grid grid-rows-2 gap-6 h-[520px]">

<!-- SPA PRODUCTS -->
<div class="relative rounded-2xl overflow-hidden">

<img src="{{ asset('themes/shop/default/images/third_a.png') }}" class="w-full h-full object-cover">

<div class="absolute inset-0 flex flex-col items-center justify-center gap-6">

<h2 class="font-oswald text-[40px] uppercase text-[#371E0F] text-center">
SPA PRODUCTS
</h2>

<a href="{{ route('spa.product.index') }}"
class="inline-flex items-center justify-center bg-[#371E0F] text-white rounded-full px-8 py-3 font-oswald text-[18px] uppercase">
BUY PRODUCTS
</a>

</div>

</div>



<!-- FLOWERS -->
<div class="relative rounded-2xl overflow-hidden">

<img src="{{ asset('themes/shop/default/images/third_b.png') }}"
class="absolute inset-0 w-full h-full object-cover">

<div class="relative flex items-center h-full px-10">

<div class="flex flex-col gap-6">

<h2 class="font-oswald text-[40px] uppercase text-[#371E0F] leading-[120%]">
FLOWERS <br> PRODUCT
</h2>

<a href="{{ route('flower.product.index') }}"
class="inline-flex items-center justify-center bg-[#DBA585] text-[#371E0F] rounded-full px-8 py-3 font-oswald text-[18px] uppercase w-max">
BUY FLOWERS
</a>

</div>

</div>

</div>

</div>



<!-- SPA PRODUCTS (Tablet + Mobile) -->
<div class="relative rounded-2xl overflow-hidden lg:hidden h-[240px] md:h-[280px]">

<img src="{{ asset('themes/shop/default/images/third_a.png') }}"
class="absolute inset-0 w-full h-full object-cover">

<div class="absolute inset-0 flex flex-col items-center justify-center gap-4">

<h2 class="font-oswald text-[26px] md:text-[34px] uppercase text-[#371E0F] text-center">
SPA PRODUCTS
</h2>

<a href="{{ route('spa.product.index') }}"
class="bg-[#371E0F] text-white rounded-full px-6 py-2 md:px-8 md:py-3 font-oswald text-[14px] md:text-[16px] uppercase">
BUY PRODUCTS
</a>

</div>

</div>



<!-- FLOWERS (Tablet + Mobile) -->
<div class="relative rounded-2xl overflow-hidden lg:hidden h-[240px] md:h-[280px]">

<img src="{{ asset('themes/shop/default/images/third_b.png') }}"
class="absolute inset-0 w-full h-full object-cover">

<div class="relative flex items-center h-full px-6 md:px-10">

<div class="flex flex-col gap-4">

<h2 class="font-oswald text-[26px] md:text-[34px] uppercase text-[#371E0F] leading-[120%]">
FLOWERS <br> PRODUCT
</h2>

<a href="{{ route('flower.product.index') }}"
class="bg-[#DBA585] text-[#371E0F] rounded-full px-6 py-2 md:px-8 md:py-3 font-oswald text-[14px] md:text-[16px] uppercase w-max">
BUY FLOWERS
</a>

</div>

</div>

</div>


</div>
</div>

</body>
</html>