<section class="home-our-products py-16 md:py-32">
  <div class="container">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6 mb-10 md:mb-12">

      <h2 class="font-oswald text-[#371E0F] uppercase text-[40px] md:text-[60px]">
        Our Products
      </h2>

      <div class="flex items-center gap-3 md:mt-3">

        <button
          class="swiper-button-prev-custom w-11 h-11 rounded-full border-2 border-[#78718B] flex items-center justify-center hover:bg-[#78718B]/10 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#78718B"
            stroke-width="2">
            <polyline points="15 18 9 12 15 6" />
          </svg>
        </button>

        <button
          class="swiper-button-next-custom w-11 h-11 rounded-full border-2 border-[#DFAA8B] flex items-center justify-center hover:bg-[#DFAA8B]/10 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#DFAA8B"
            stroke-width="2">
            <polyline points="9 18 15 12 9 6" />
          </svg>
        </button>

      </div>

    </div>

    <!-- Swiper -->
    <div class="swiper productSwiper">

      <div class="swiper-wrapper">

        @forelse($products as $product)

        <div class="swiper-slide">

          <a href="{{ route('shop.home.product.details', $product->url_key) }}"
            class="product-slider-card-wrap flex flex-col items-center">

            <div
              class="product-slider-img-wrap relative w-full h-[240px] md:h-[300px] rounded-xl overflow-hidden bg-[#e8e3dc] mb-5 hover:shadow-xl transition">

              <img class="w-full h-full object-cover"
                src="{{ $product->product->base_image_url ?? 'https://via.placeholder.com/400' }}"
                alt="{{ $product->name }}">

            </div>

            <span
              class="product-slider-product-name-wrap font-oswald text-center uppercase text-[#371E0F] text-[20px] md:text-[24px] tracking-[0.1em]">
              {{ $product->name }}
            </span>

            <span
              class="product-slider-price-wrap font-oswald text-[20px] md:text-[24px] tracking-[0.1em] uppercase text-[#DFAA8B] text-center">
              {{ core()->currency($product->price) }}
            </span>

          </a>

        </div>

        @empty
        <p>No products found</p>
        @endforelse

      </div>

    </div>

  </div>
</section>
<script>

  new Swiper(".productSwiper", {

    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 3000, // 3 seconds
      disableOnInteraction: false,
    },

    navigation: {
      nextEl: ".swiper-button-next-custom",
      prevEl: ".swiper-button-prev-custom",
    },

    breakpoints: {

      640: {
        slidesPerView: 2
      },

      1024: {
        slidesPerView: 4
      }

    }

  });

</script>