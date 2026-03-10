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
<img src="{{ asset('themes/shop/default/images/logo.png') }}" alt="Logo" class="h-10 md:h-16">
</a>
</div>

<!-- MAIN GRID -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

<!-- SPA SERVICE -->

<div class="relative bg-[#D4AF37] rounded-[28px] overflow-hidden text-white pt-8 pl-11 pr-6 pb-8 w-full h-[420px] md:h-[480px] lg:h-[520px]">

<h2
style="
font-family:'Oswald', sans-serif;
font-weight:400;
font-size:48px;
line-height:100%;
letter-spacing:0%;
text-transform:uppercase;
color:#FFFFFF;
margin-bottom:20px;
"
class="text-[34px] md:text-[40px] lg:text-[48px]"
>
SPA SERVICE
</h2>

<ul
style="
font-family:'Roboto', sans-serif;
font-weight:500;
font-size:16px;
line-height:32px;
letter-spacing:2%;
color:#FFFFFF;
margin-bottom:18px;
"
class="text-[14px] md:text-[15px] lg:text-[16px]"
>
<li>Facial</li>
<li>Eye Lashes & Eyebrow Bar</li>
<li>Nail Services Bar</li>
<li>Massage</li>
<li>Hair Treatment</li>
<li>Threading Bar</li>
<li>Waxing Bar</li>
</ul>

<a href="{{ route('shop.home.index') }}"
class="inline-flex items-center justify-center relative z-10"
style="
width:170px;
height:47px;
border-radius:50px;
background:#FFFFFF;
color:#371E0F;
font-family:'Oswald', sans-serif;
font-weight:400;
font-size:18px;
text-transform:uppercase;
padding:10px 32px;
">
BOOK SERVICES </a>

<img
src="{{ asset('themes/shop/default/images/landing_page_one.png') }}"
alt="Model"
class="absolute bottom-0 -right-12 h-[260px] md:h-[320px] lg:h-[380px] object-contain pointer-events-none z-0">

</div>

<!-- PERFUME -->

<div class="relative rounded-[28px] pt-10 pl-12 pr-10 pb-6 h-[300px] md:h-[480px] lg:h-[520px] overflow-hidden"
style="background: #D0CBC1;">

<h2
style="
font-family:'Oswald', sans-serif;
font-weight:400;
font-size:48px;
line-height:100%;
letter-spacing:0%;
text-transform:uppercase;
color:#371E0F;
margin-bottom:28px;
"
class="text-[34px] md:text-[40px] lg:text-[48px]"
>
PERFUME
</h2>

<a href="{{ route('sbt.perfume.index') }}"
class="inline-flex items-center justify-center relative z-10"
style="
width:166px;
height:47px;
border-radius:50px;
background:#371E0F;
color:#FFFFFF;
font-family:'Oswald', sans-serif;
font-weight:400;
font-size:18px;
text-transform:uppercase;
padding:10px 32px;
">
BUY PERFUMES </a>

<img
src="{{ asset('themes/shop/default/images/landing_page_two.png') }}"
class="absolute bottom-0 left-0 w-full object-contain z-0">

</div>

<!-- RIGHT COLUMN -->

<div class="grid grid-rows-2 gap-6 h-[480px] lg:h-[520px] md:col-span-2 lg:col-span-1">

<!-- SPA PRODUCTS -->

<div class="relative rounded-2xl overflow-hidden">

<img src="{{ asset('themes/shop/default/images/third_a.png') }}" class="w-full h-full object-cover">

<div class="absolute inset-0 flex flex-col items-center justify-center gap-6">

<h2
style="
font-family: Oswald;
font-weight: 400;
font-size: 48px;
line-height: 100%;
letter-spacing: 0%;
text-transform: uppercase;
color:#371E0F;
text-align:center;
"
class="text-[30px] md:text-[36px] lg:text-[48px]"
>
SPA PRODUCTS
</h2>

<a href="{{ route('spa.product.index') }}"
class="inline-flex items-center justify-center"
style="
width:170px;
height:47px;
border-radius:50px;
background:#371E0F;
color:#FFFFFF;
font-family:'Oswald', sans-serif;
font-weight:400;
font-size:18px;
text-transform:uppercase;
padding:10px 32px;
">
BUY PRODUCTS </a>

</div>

</div>

<!-- FLOWERS -->

<div class="relative rounded-2xl overflow-hidden">

<img src="{{ asset('themes/shop/default/images/third_b.png') }}"
class="absolute inset-0 w-full h-full object-cover">

<div class="relative flex items-center justify-between h-full px-10">

<div class="flex flex-col gap-6">

<h2
style="
font-family: Oswald;
font-weight: 400;
font-size: 40px;
line-height: 120%;
letter-spacing: 0%;
text-transform: uppercase;
color:#371E0F;
"
class="text-[26px] md:text-[32px] lg:text-[40px]"
>
FLOWERS <br> PRODUCT
</h2>

<a href="{{ route('flower.product.index') }}"
class="inline-flex items-center justify-center"
style="
width:166px;
height:47px;
border-radius:50px;
background:#DBA585;
color:#371E0F;
font-family:'Oswald', sans-serif;
font-weight:400;
font-size:18px;
text-transform:uppercase;
padding:10px 32px;
">
BUY FLOWERS </a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>
</html>
