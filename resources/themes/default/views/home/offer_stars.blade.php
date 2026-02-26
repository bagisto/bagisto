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
  <!-- Offer Cards Row -->
  <div class="grid grid-cols-2 gap-5 mb-14">

    <!-- Card 1: Offer 330 (teal/pool bg) -->
    <div class="relative rounded-2xl overflow-hidden h-[200px]">
      <!-- Background Image -->
      <img
        src="https://images.unsplash.com/photo-1519751138087-5bf79df62d5b?w=700&h=300&fit=crop"
        alt="Offer 330"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <!-- Dark overlay -->
      <div class="absolute inset-0 bg-black/30"></div>
      <!-- Content -->
      <div class="relative z-10 p-6 flex flex-col justify-between h-full">
        <div>
          <h3 class="font-oswald font-bold text-[28px] text-white uppercase tracking-wide leading-tight mb-2">Offer 330</h3>
          <p class="text-white/85 text-[13px] leading-relaxed max-w-[220px]">
            Mani pedi with polish +<br>blow dry straight or volume
          </p>
        </div>
        <div class="flex flex-col items-end">
          <span class="text-green-400 text-[11px] font-medium tracking-wide mb-0.5">Save 15%</span>
          <span class="font-oswald font-bold text-white text-[22px] tracking-wide">AED 330</span>
        </div>
      </div>
    </div>

    <!-- Card 2: Offer 260 (dark red bg) -->
    <div class="relative rounded-2xl overflow-hidden h-[200px]">
      <!-- Background Image -->
      <img
        src="https://images.unsplash.com/photo-1604654894610-df63bc536371?w=700&h=300&fit=crop"
        alt="Offer 260"
        class="absolute inset-0 w-full h-full object-cover object-top"
      />
      <!-- Dark red overlay -->
      <div class="absolute inset-0 bg-[#7a1a1a]/70"></div>
      <!-- Content -->
      <div class="relative z-10 p-6 flex flex-col justify-between h-full">
        <div>
          <h3 class="font-oswald font-bold text-[28px] text-white uppercase tracking-wide leading-tight mb-2">Offer 260</h3>
          <p class="text-white/85 text-[13px] leading-relaxed max-w-[240px]">
            Mani Pedi with polish + foot paraffin<br>+ 10 minutes parts massage
          </p>
        </div>
        <div class="flex flex-col items-end">
          <span class="text-green-400 text-[11px] font-medium tracking-wide mb-0.5">Save 18%</span>
          <span class="font-oswald font-bold text-white text-[22px] tracking-wide">AED 260</span>
        </div>
      </div>
    </div>

  </div>

  <!-- Stats Row -->
  <div class="grid grid-cols-3 gap-8 px-4">

    <!-- Stat 1 -->
    <div class="flex items-center gap-4">
      <div class="flex flex-col items-end">
        <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">years</span>
        <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">of experience</span>
      </div>
      <span class="font-oswald font-bold text-[52px] text-[#2a1f14] leading-none tracking-tight">10+</span>
    </div>

    <!-- Stat 2 -->
    <div class="flex items-center gap-4">
      <div class="flex flex-col items-end">
        <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">happy</span>
        <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">clients</span>
      </div>
      <span class="font-oswald font-bold text-[52px] text-[#2a1f14] leading-none tracking-tight">2K</span>
    </div>

    <!-- Stat 3 -->
    <div class="flex items-center gap-4">
      <div class="flex flex-col items-end">
        <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">we made beauty</span>
        <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">procedures</span>
      </div>
      <span class="font-oswald font-bold text-[52px] text-[#2a1f14] leading-none tracking-tight">5B</span>
    </div>

  </div>

</section>

