<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

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

<section class="w-full max-w-[1400px] mx-auto px-8 py-32">

  <!-- Header Row -->
  <div class="flex items-start justify-between mb-12">
    <h2 class="font-oswald font-normal text-[60px] uppercase leading-[100%] text-[#371E0F]">
      Offers and Packages
    </h2>

    <div class="flex gap-3 mt-3">
      <button id="scrollLeft" class="w-11 h-11 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
        <svg class="w-4 h-4 fill-none stroke-[#c07a3a]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </button>

      <button id="scrollRight" class="w-11 h-11 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
        <svg class="w-4 h-4 fill-none stroke-[#c07a3a]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Cards -->
  <div id="offersContainer" class="flex gap-7 overflow-x-auto scroll-smooth">

    <!-- CARD -->
    <div class="offer-card flex flex-col min-w-[300px] cursor-pointer">

      <div class="w-full aspect-square rounded-2xl overflow-hidden mb-5 relative">
        <img src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=400&h=400&fit=crop" class="w-full h-full object-cover"/>

        <span class="absolute top-3 left-3 bg-white/90 text-[#371E0F] text-[15px] px-3 py-1.5 rounded-full font-roboto tracking-[0.02em] leading-[24px]">
          2hrs, 30mins
        </span>
      </div>

      <div class="flex justify-between mb-2">

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#371E0F] leading-[100%]">
          Offer 299
        </span>

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#DFAA8B] leading-[100%] text-right">
          AED 299
        </span>

      </div>

      <p class="font-roboto text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
        Mani Pedi with polish + 30 minutes massage
      </p>

    </div>


    <!-- CARD -->
    <div class="offer-card flex flex-col min-w-[300px] cursor-pointer">

      <div class="w-full aspect-square rounded-2xl overflow-hidden mb-5 relative">
        <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=400&h=400&fit=crop" class="w-full h-full object-cover"/>

        <span class="absolute top-3 left-3 bg-white/90 text-[#371E0F] text-[15px] px-3 py-1.5 rounded-full font-roboto tracking-[0.02em] leading-[24px]">
          4hrs, 30mins
        </span>
      </div>

      <div class="flex justify-between mb-2">

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#371E0F] leading-[100%]">
          Hair Botox
        </span>

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#DFAA8B] leading-[100%] text-right">
          AED 710
        </span>

      </div>

      <p class="font-roboto text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
        Deep conditioning treatments for damaged hair
      </p>

    </div>


    <!-- CARD -->
    <div class="offer-card flex flex-col min-w-[300px] cursor-pointer">

      <div class="w-full aspect-square rounded-2xl overflow-hidden mb-5 relative">
        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=400&h=400&fit=crop" class="w-full h-full object-cover"/>

        <span class="absolute top-3 left-3 bg-white/90 text-[#371E0F] text-[15px] px-3 py-1.5 rounded-full font-roboto tracking-[0.02em] leading-[24px]">
          3hrs
        </span>
      </div>

      <div class="flex justify-between mb-2">

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#371E0F] leading-[100%]">
          Pampering Offer
        </span>

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#DFAA8B] leading-[100%] text-right">
          AED 450
        </span>

      </div>

      <p class="font-roboto text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
        Mani + Pedi + Polish + Swedish Massage
      </p>

    </div>


    <!-- CARD -->
    <div class="offer-card flex flex-col min-w-[300px] cursor-pointer">

      <div class="w-full aspect-square rounded-2xl overflow-hidden mb-5 relative">
        <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?w=400&h=400&fit=crop" class="w-full h-full object-cover"/>

        <span class="absolute top-3 left-3 bg-white/90 text-[#371E0F] text-[15px] px-3 py-1.5 rounded-full font-roboto tracking-[0.02em] leading-[24px]">
          1hr
        </span>
      </div>

      <div class="flex justify-between mb-2">

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#371E0F] leading-[100%]">
          Offer Massage
        </span>

        <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#DFAA8B] leading-[100%] text-right">
          AED 258
        </span>

      </div>

      <p class="font-roboto text-[16px] leading-[24px] tracking-[0.02em] text-[#78718B]">
        Mani Pedi with polish + 30 minutes massage
      </p>

    </div>

  </div>

</section>


<script>
const container = document.getElementById('offersContainer');
const leftBtn = document.getElementById('scrollLeft');
const rightBtn = document.getElementById('scrollRight');

leftBtn.addEventListener('click', () => {
  const cardWidth = container.querySelector('.offer-card').offsetWidth + 28;
  container.scrollBy({ left: -cardWidth, behavior: 'smooth' });
});

rightBtn.addEventListener('click', () => {
  const cardWidth = container.querySelector('.offer-card').offsetWidth + 28;
  container.scrollBy({ left: cardWidth, behavior: 'smooth' });
});
</script>