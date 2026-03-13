<section class="relative w-full">
    <!-- Background Images -->
    <div class="absolute inset-0 flex">
        <div class="w-1/2 h-full">
            <img src="{{ asset('images/bn-left.png') }}" alt="Left Background" class="w-full h-full object-cover">
        </div>
        <div class="w-1/2 h-full">
            <img src="{{ asset('images/bn-right.png') }}" alt="Right Background" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Subtle Pattern Overlay -->
    <div class="absolute inset-0 bg-[url('/images/overlay-pattern.png')] bg-no-repeat bg-center bg-cover opacity-20 pointer-events-none"></div>

    <!-- Centered Content -->
    <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-32 flex flex-col items-center text-center">
        <h1 class="text-4xl md:text-6xl font-semibold text-[#3c2415] leading-tight">
            {{ $title ?? '' }}
        </h1>
        <p class="text-sm text-gray-600 mb-3">
            {{ $breadcrumb ?? '' }}
        </p>
    </div>
</section>