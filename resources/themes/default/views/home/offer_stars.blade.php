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

<section class="home-offers-sec py-16 md:py-24">
  <div class="container">
    <!-- Offer Cards Row -->
    <div class="offer-card-grid-wrap grid grid-cols-2 gap-5">

      <!-- Card 1: Offer 330 (teal/pool bg) -->
      <div class="offer-card-wrap offer-330 relative overflow-hidden">
        <div class="offer-card-content-wrap relative z-10 flex flex-col justify-between h-full">
          <div class="offer-card-header">
            <h2 class="offer-card-heading font-oswald text-white uppercase tracking-wide leading-tight mb-2">
              Offer 330
            </h2>
            <p class="offer-card-des heading-color leading-relaxed">
              Mani pedi with polish +<br>blow dry straight or volume
            </p>
          </div>
          <div class="flex flex-col items-end price-with-save-txt-wrap">
            <span class="offer-save-txt text-green-400 font-medium tracking-wide mb-0.5">
              Save 15%
            </span>
            <span class="offer-price-txt font-oswald heading-color tracking-wide">AED 330</span>
          </div>
        </div>
      </div>

      <!-- Card 2: Offer 260 (dark red bg) -->
      <div class="offer-card-wrap offer-260 relative overflow-hidden">
        <div class="offer-card-content-wrap relative z-10 flex flex-col justify-between h-full">
          <div class="offer-card-header">
            <h2 class="offer-card-heading font-oswald text-white uppercase tracking-wide leading-tight mb-2">
              Offer 260
            </h2>
            <p class="offer-card-des leading-relaxed text-white">
              Mani Pedi with polish + foot paraffin<br>+ 10 minutes parts massage
            </p>
          </div>
          <div class="flex flex-col items-end price-with-save-txt-wrap">
            <span class="offer-save-txt text-green-400 font-medium tracking-wide mb-0.5">
              Save 18%
            </span>
            <span class="offer-price-txt font-oswald text-white tracking-wide">
              AED 260
            </span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="home-assets-sec">
  <div class="container">
    <!-- Stats Row -->
    <div class="grid grid-cols-3 gap-8 px-4">

      <!-- Stat 1 -->
      <div class="flex items-center gap-4">
        <div class="flex flex-col items-end">
          <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">
            years
          </span>
          <span class="text-[11px] text-gray-500 tracking-wide leading-tight text-right">
            of experience
          </span>
        </div>
        <span class="font-oswald heading-color text-[52px] text-[#2a1f14] leading-none tracking-tight">
          10+
        </span>
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
  </div>
</section>