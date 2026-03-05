<script>
tailwind.config = {
  theme:{
    extend:{
      fontFamily:{
        oswald:['Oswald','sans-serif'],
        roboto:['Roboto','sans-serif'],
      }
    }
  }
}
</script>

<style>
.team-card:hover .team-img-wrap{
  transform:translateY(-4px);
  box-shadow:0 16px 40px rgba(0,0,0,0.12);
}
.team-img-wrap{
  transition:transform .3s ease, box-shadow .3s ease;
}
</style>

<section class="w-full max-w-[1400px] mx-auto px-4 md:px-8 py-16 md:py-24">

<!-- Heading -->
<h2 class="font-oswald font-normal text-[34px] sm:text-[44px] md:text-[60px] leading-[100%] uppercase text-center text-[#371E0F] mb-10 md:mb-14">
SBT Team
</h2>

<!-- Team Grid -->
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">

<!-- Member 1 -->
<div class="team-card flex flex-col cursor-pointer">

<div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#e0d8d0] mb-4">
<img
src="https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=400&h=400&fit=crop&crop=face"
alt="Dipti"
class="w-full h-full object-cover object-top"
/>
</div>

<div class="flex items-center justify-between mb-1">

<span class="font-oswald font-normal text-[18px] sm:text-[20px] md:text-[24px] leading-[100%] tracking-[0.1em] uppercase text-[#371E0F]">
Dipti
</span>

<div class="flex items-center gap-1">
<span class="font-roboto font-semibold text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#371E0F]">
4.5
</span>
<span class="text-yellow-400 text-sm">★</span>
</div>

</div>

<p class="font-roboto font-normal text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
Nail Technician
</p>

</div>

<!-- Member 2 -->
<div class="team-card flex flex-col cursor-pointer">

<div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#ddd5cc] mb-4">
<img
src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&h=400&fit=crop&crop=face"
alt="Sami"
class="w-full h-full object-cover object-top"
/>
</div>

<div class="flex items-center justify-between mb-1">

<span class="font-oswald font-normal text-[18px] sm:text-[20px] md:text-[24px] leading-[100%] tracking-[0.1em] uppercase text-[#371E0F]">
Sami
</span>

<div class="flex items-center gap-1">
<span class="font-roboto font-semibold text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#371E0F]">
4.6
</span>
<span class="text-yellow-400 text-sm">★</span>
</div>

</div>

<p class="font-roboto font-normal text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
Nail Technician
</p>

</div>

<!-- Member 3 -->
<div class="team-card flex flex-col cursor-pointer">

<div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#e2d9cf] mb-4">
<img
src="https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?w=400&h=400&fit=crop&crop=face"
alt="Khim"
class="w-full h-full object-cover object-top"
/>
</div>

<div class="flex items-center justify-between mb-1">

<span class="font-oswald font-normal text-[18px] sm:text-[20px] md:text-[24px] leading-[100%] tracking-[0.1em] uppercase text-[#371E0F]">
Khim
</span>

<div class="flex items-center gap-1">
<span class="font-roboto font-semibold text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#371E0F]">
5.0
</span>
<span class="text-yellow-400 text-sm">★</span>
</div>

</div>

<p class="font-roboto font-normal text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
Nail Technician
</p>

</div>

<!-- Member 4 -->
<div class="team-card flex flex-col cursor-pointer">

<div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#dbd3c9] mb-4">
<img
src="https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=400&h=400&fit=crop&crop=face"
alt="Rupa"
class="w-full h-full object-cover object-top"
/>
</div>

<div class="flex items-center justify-between mb-1">

<span class="font-oswald font-normal text-[18px] sm:text-[20px] md:text-[24px] leading-[100%] tracking-[0.1em] uppercase text-[#371E0F]">
Rupa
</span>

<div class="flex items-center gap-1">
<span class="font-roboto font-semibold text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#371E0F]">
5.0
</span>
<span class="text-yellow-400 text-sm">★</span>
</div>

</div>

<p class="font-roboto font-normal text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
Technicians
</p>

</div>

</div>

</section>