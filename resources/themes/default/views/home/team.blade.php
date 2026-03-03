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
  .team-card:hover .team-img-wrap {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.12);
  }
  .team-img-wrap { transition: transform 0.3s ease, box-shadow 0.3s ease; }
</style>
</head>
<body class="bg-[#f5f4f1] min-h-screen flex items-center justify-center py-16">

<section class="w-full max-w-[1400px] mx-auto px-8">

  <!-- Title -->
  <h2 class="font-oswald font-semibold text-[40px] uppercase tracking-widest text-[#2a1f14] text-center mb-12">
    SBT Team
  </h2>

  <!-- Team Grid -->
  <div class="grid grid-cols-4 gap-8">

    <!-- Member 1: Dipti -->
    <div class="team-card flex flex-col cursor-pointer">
      <div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#e0d8d0] mb-4">
        <img
          src="https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=400&h=400&fit=crop&crop=face"
          alt="Dipti"
          class="w-full h-full object-cover object-top"
        />
      </div>
      <div class="flex items-center justify-between mb-1">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wider text-[#2a1f14]">Dipti</span>
        <div class="flex items-center gap-1">
          <span class="text-[13px] text-gray-600 font-medium">4.5</span>
          <span class="text-yellow-400 text-sm">★</span>
        </div>
      </div>
      <p class="text-[12px] text-gray-400 tracking-wide">Nail Technician</p>
    </div>

    <!-- Member 2: Sami -->
    <div class="team-card flex flex-col cursor-pointer">
      <div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#ddd5cc] mb-4">
        <img
          src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&h=400&fit=crop&crop=face"
          alt="Sami"
          class="w-full h-full object-cover object-top"
        />
      </div>
      <div class="flex items-center justify-between mb-1">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wider text-[#2a1f14]">Sami</span>
        <div class="flex items-center gap-1">
          <span class="text-[13px] text-gray-600 font-medium">4.6</span>
          <span class="text-yellow-400 text-sm">★</span>
        </div>
      </div>
      <p class="text-[12px] text-gray-400 tracking-wide">Nail Technician</p>
    </div>

    <!-- Member 3: Khim -->
    <div class="team-card flex flex-col cursor-pointer">
      <div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#e2d9cf] mb-4">
        <img
          src="https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?w=400&h=400&fit=crop&crop=face"
          alt="Khim"
          class="w-full h-full object-cover object-top"
        />
      </div>
      <div class="flex items-center justify-between mb-1">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wider text-[#2a1f14]">Khim</span>
        <div class="flex items-center gap-1">
          <span class="text-[13px] text-gray-600 font-medium">5.0</span>
          <span class="text-yellow-400 text-sm">★</span>
        </div>
      </div>
      <p class="text-[12px] text-gray-400 tracking-wide">Nail Technician</p>
    </div>

    <!-- Member 4: Rupa -->
    <div class="team-card flex flex-col cursor-pointer">
      <div class="team-img-wrap w-full aspect-square rounded-2xl overflow-hidden bg-[#dbd3c9] mb-4">
        <img
          src="https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=400&h=400&fit=crop&crop=face"
          alt="Rupa"
          class="w-full h-full object-cover object-top"
        />
      </div>
      <div class="flex items-center justify-between mb-1">
        <span class="font-oswald font-semibold text-[15px] uppercase tracking-wider text-[#2a1f14]">Rupa</span>
        <div class="flex items-center gap-1">
          <span class="text-[13px] text-gray-600 font-medium">5.0</span>
          <span class="text-yellow-400 text-sm">★</span>
        </div>
      </div>
      <p class="text-[12px] text-gray-400 tracking-wide">Technicians</p>
    </div>

  </div>
</section>
