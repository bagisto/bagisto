<section class="max-w-screen-xl mx-auto px-4 py-16 md:py-32">


  <!-- Products Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

  @forelse($flower_products as $flower_product)

  <a href="#"
     class="flex flex-col items-center cursor-pointer">

      <!-- Image -->
      <div class="relative w-full h-[240px] md:h-[300px] rounded-xl overflow-hidden bg-[#e8e3dc] mb-5 transition-transform transform hover:translate-y-1 hover:shadow-2xl">

        <img class="w-full h-full object-cover"
             src="{{ $flower_product->product->base_image_url ?? 'https://via.placeholder.com/400' }}"
             alt="{{ $flower_product->name }}">
      </div>

      <!-- Title -->
      <span class="font-oswald text-center uppercase text-[#371E0F] font-normal text-[20px] md:text-[24px] leading-[100%] tracking-[0.1em]">
        {{ $flower_product->name }}
      </span>

      <!-- Price -->
      <span class="font-oswald text-[20px] md:text-[24px] tracking-[0.1em] uppercase text-[#DFAA8B] text-center">
        {{ core()->currency($flower_product->price) }}
      </span>

  </a>

  @empty
    <p>No products found</p>
  @endforelse

  </div>

</section>