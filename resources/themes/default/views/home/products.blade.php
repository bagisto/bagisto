<section class="max-w-screen-xl mx-auto px-4 py-16 md:py-32">

  <!-- Header Section -->
  <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6 mb-10 md:mb-12">

    <h2 class="font-oswald text-[#371E0F] uppercase"
        style="
          font-weight:400;
          font-size:60px;
          line-height:100%;
          letter-spacing:0%;
          text-transform:uppercase;
        ">
      Our Products
    </h2>

    <div class="flex items-center gap-3 md:mt-3">

      <!-- Previous Button -->
      <button id="prevBtn"
        class="w-11 h-11 rounded-full border-2 border-[#78718B] flex items-center justify-center hover:bg-[#78718B] hover:bg-opacity-10 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
             stroke="#78718B" stroke-width="2">
          <polyline points="15 18 9 12 15 6" />
        </svg>
      </button>

      <!-- Next Button -->
      <button id="nextBtn"
        class="w-11 h-11 rounded-full border-2 border-[#DFAA8B] flex items-center justify-center hover:bg-[#DFAA8B] hover:bg-opacity-10 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
             stroke="#DFAA8B" stroke-width="2">
          <polyline points="9 18 15 12 9 6" />
        </svg>
      </button>

    </div>
  </div>


<!-- Carousel -->
<div id="productCarousel"
     class="flex gap-5 overflow-x-auto scroll-smooth no-scrollbar">

@forelse($products as $product)
    
<a href="{{ route('shop.home.product.details', $product->url_key) }}"
   class="flex-shrink-0 w-full sm:w-[48%] lg:w-[23%] flex flex-col items-center cursor-pointer">

    <div
      class="relative w-full h-[240px] md:h-[300px] rounded-xl overflow-hidden bg-[#e8e3dc] mb-5 transition-transform transform hover:translate-y-1 hover:shadow-2xl">

      <img class="w-full h-full object-cover"
           src="{{ $product->product->base_image_url ?? 'https://via.placeholder.com/400' }}"
           alt="{{ $product->name }}">
    </div>  

    <!-- Product Title -->
    <span class="font-oswald text-center uppercase text-[#371E0F]"
          style="
            font-weight:400;
            font-size:24px;
            line-height:100%;
            letter-spacing:10%;
          ">
      {{ $product->name }}
    </span>

    <!-- Product Price -->
    <span class="font-oswald text-[24px] tracking-[0.1em] uppercase text-[#DFAA8B] text-center">
        {{ core()->currency($product->price) }}
    </span>

</a>

@empty
<p>No products found</p>
@endforelse

</div>

</section>


<style>
.no-scrollbar::-webkit-scrollbar{
  display:none;
}
.no-scrollbar{
  -ms-overflow-style:none;
  scrollbar-width:none;
}
</style>


<script>

const carousel = document.getElementById('productCarousel');
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');

const scrollAmount = 320;

nextBtn.addEventListener('click', () => {
  carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
});

prevBtn.addEventListener('click', () => {
  carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
});

</script>