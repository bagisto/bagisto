<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        oswald: ['Oswald', 'sans-serif'],
        roboto: ['Roboto', 'sans-serif']
      }
    }
  }
}
</script>

<section class="relative w-full overflow-visible">

    <!-- Background Images -->
    <div class="absolute inset-0 flex">
        <div class="w-1/2 h-full">
            <img src="{{ asset('images/bn-left.png') }}" class="w-full h-full object-cover">
        </div>
        <div class="w-1/2 h-full">
            <img src="{{ asset('images/bn-right.png') }}" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-[#EFE9DD] opacity-90"></div>

    <!-- Banner Content -->
    <div class="relative max-w-7xl mx-auto px-4 py-20 md:py-28 lg:py-32 flex flex-col items-center text-center gap-4">

        <!-- Heading -->
        <h1 class="font-oswald font-normal text-[34px] md:text-[48px] lg:text-[60px] leading-[100%] uppercase text-[#371E0F]">
            Flower Product
        </h1>

        <!-- Breadcrumb -->
        <p class="font-roboto font-normal text-[12px] md:text-[14px] leading-[24px] tracking-[0.02em] text-[#371E0F]">
            Home / Flower Product
        </p>

    </div>

<!-- Search Bar -->
<div id="searchContainer" class="absolute left-1/2 bottom-0 -translate-x-1/2 translate-y-1/2 w-full max-w-[92%] sm:max-w-xl md:max-w-2xl px-4">

    <form id="searchForm" action="{{ route('flower.product.search') }}" method="POST"
          class="flex items-center bg-white rounded-full px-5 md:px-6 py-3 md:py-4 shadow-lg border border-gray-200">
        @csrf

        <input 
            type="text"
            placeholder="Search flower products here..."
            name="search_input"
            id="search_input"
            value="{{ old('search_input', $search_input ?? '') }}"
            class="flex-1 bg-transparent outline-none font-roboto text-[14px] md:text-[16px] leading-[24px] tracking-[0.02em] 
                   text-[#371E0F] placeholder:text-[#371E0F]"
        >

        <button type="submit" class="ml-3 md:ml-4 text-[#371E0F]">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5 md:w-6 md:h-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2.5">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </form>

    <p id="searchError" class="text-red-500 text-sm mt-1 ml-2 hidden">Please enter a search term.</p>

</div>


</section>