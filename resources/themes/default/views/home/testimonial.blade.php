<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
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
</head>

<section class="w-full max-w-[1400px] mx-auto px-8 py-32">
  <!-- Section Title -->
  <h2 class="font-oswald font-semibold text-[36px] uppercase tracking-widest text-[#2a1f14] text-center mb-10">
    Testimonial
  </h2>

  <!-- Card -->
  <div class="flex items-stretch gap-0 rounded-2xl overflow-visible max-w-[860px] mx-auto">

    <!-- Left: Person Image -->
    <div class="flex-shrink-0 w-[240px]">
      <img
        src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&h=500&fit=crop&crop=face"
        alt="Mariam"
        class="w-full h-full object-cover rounded-2xl"
        style="min-height:280px;"
      />
    </div>

    <!-- Right: Testimonial Content -->
    <div class="flex-1 bg-white rounded-2xl ml-0 px-10 py-8 flex flex-col justify-between relative" style="border-radius: 0 1rem 1rem 0;">

      <!-- Quote Icon -->
      <div>
        <div class="text-[#c07a3a] text-5xl leading-none mb-4" style="font-family:Georgia,serif;">&ldquo;</div>
        <p class="text-gray-500 text-[15px] leading-relaxed mb-6">
          Best service ever .. and they came at the time
        </p>
        <p class="font-oswald font-semibold text-[13px] uppercase tracking-widest text-[#2a1f14] mb-2">Mariam</p>
        <!-- Stars -->
        <div class="flex gap-1">
          <span class="text-yellow-400 text-lg">★</span>
          <span class="text-yellow-400 text-lg">★</span>
          <span class="text-yellow-400 text-lg">★</span>
          <span class="text-yellow-400 text-lg">★</span>
          <span class="text-gray-300 text-lg">★</span>
        </div>
      </div>

      <!-- Nav Arrows at bottom -->
      <div class="flex justify-between items-center mt-8">
        <button class="w-9 h-9 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
          <svg class="w-3.5 h-3.5 fill-none stroke-[#c07a3a]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6"/>
          </svg>
        </button>
        <button class="w-9 h-9 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
          <svg class="w-3.5 h-3.5 fill-none stroke-[#c07a3a]" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </button>
      </div>

    </div>
  </div>

</section>
