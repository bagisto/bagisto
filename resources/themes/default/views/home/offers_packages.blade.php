<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          oswald: ['Oswald', 'sans-serif'],
          jost: ['Jost', 'sans-serif'],
        }
      }
    }
  }
</script>
<style>
  body { font-family: 'Jost', sans-serif; }
  .offer-card:hover .offer-img-wrap {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.14);
  }
  .offer-img-wrap { transition: transform 0.3s ease, box-shadow 0.3s ease; }
</style>
</head>
<body class="bg-[#f5f4f1] min-h-screen flex items-center justify-center py-32">

<section class="w-full max-w-[1400px] mx-auto px-8">

  <!-- Header Row -->
  <div class="flex items-start justify-between mb-12">
    <h2 class="font-oswald font-semibold text-[56px] uppercase tracking-wide text-[#2a1f14] leading-none">
      Offers and Packages
    </h2>
    <div class="flex gap-3 mt-3">
      <button class="w-11 h-11 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
        <svg class="w-4 h-4 fill-none stroke-[#c07a3a]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </button>
      <button class="w-11 h-11 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
        <svg class="w-4 h-4 fill-none stroke-[#c07a3a]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Cards Grid -->
  <div class="grid grid-cols-4 gap-7">

    <!-- Card 1: Offer 299 -->
    <div class="offer-card flex flex-col cursor-pointer">
      <div class="offer-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#8a9e6e] mb-5 relative">
        <img src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=400&h=400&fit=crop" alt="Offer 299" class="w-full h-full object-cover"/>
        <!-- Duration Badge -->
        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-[#2a1f14] text-[11px] font-medium tracking-wide px-3 py-1.5 rounded-full">
          2hrs, 30mins
        </span>
      </div>
      <div class="flex items-baseline justify-between mb-2">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wide text-[#2a1f14]">Offer 299</span>
        <span class="font-oswald font-semibold text-[15px] text-[#c07a3a]">AED 299</span>
      </div>
      <p class="text-[13px] text-gray-500 leading-relaxed">Mani Pedi with polish + 30 minutes massage</p>
    </div>

    <!-- Card 2: Hair Botox -->
    <div class="offer-card flex flex-col cursor-pointer">
      <div class="offer-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#c8b8d8] mb-5 relative">
        <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=400&h=400&fit=crop" alt="Hair Botox" class="w-full h-full object-cover"/>
        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-[#2a1f14] text-[11px] font-medium tracking-wide px-3 py-1.5 rounded-full">
          4hrs, 30mins
        </span>
      </div>
      <div class="flex items-baseline justify-between mb-2">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wide text-[#2a1f14]">Hair Botox</span>
        <span class="font-oswald font-semibold text-[15px] text-[#c07a3a]">AED 710</span>
      </div>
      <p class="text-[13px] text-gray-500 leading-relaxed">Deep conditioning treatments for hair that coats damaged hai</p>
    </div>

    <!-- Card 3: Pampering Offer -->
    <div class="offer-card flex flex-col cursor-pointer">
      <div class="offer-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#c8b49a] mb-5 relative">
        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=400&h=400&fit=crop" alt="Pampering Offer" class="w-full h-full object-cover"/>
        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-[#2a1f14] text-[11px] font-medium tracking-wide px-3 py-1.5 rounded-full">
          3hrs
        </span>
      </div>
      <div class="flex items-baseline justify-between mb-2">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wide text-[#2a1f14]">Pampering Offer</span>
        <span class="font-oswald font-semibold text-[15px] text-[#c07a3a]">AED 450</span>
      </div>
      <p class="text-[13px] text-gray-500 leading-relaxed">Mani + padi + polish<br>Massage Swedish</p>
    </div>

    <!-- Card 4: Offer Massage -->
    <div class="offer-card flex flex-col cursor-pointer">
      <div class="offer-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#d4b896] mb-5 relative">
        <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?w=400&h=400&fit=crop" alt="Offer Massage" class="w-full h-full object-cover"/>
        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-[#2a1f14] text-[11px] font-medium tracking-wide px-3 py-1.5 rounded-full">
          1hr
        </span>
      </div>
      <div class="flex items-baseline justify-between mb-2">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wide text-[#2a1f14]">Offer Massage</span>
        <span class="font-oswald font-semibold text-[15px] text-[#c07a3a]">AED 258</span>
      </div>
      <p class="text-[13px] text-gray-500 leading-relaxed">Mani Pedi with polish + 30 minutes massage</p>
    </div>

  </div>
</section>
