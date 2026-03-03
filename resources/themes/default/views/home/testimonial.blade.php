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

<section class="w-full max-w-[1400px] mx-auto px-8 py-16">

  <!-- Section Title -->
  <h2 class="font-oswald font-semibold text-[36px] uppercase tracking-widest text-[#2a1f14] text-center mb-10">
    Testimonial
  </h2>

  <div class="relative max-w-[860px] mx-auto">

    <!-- ================= TESTIMONIAL 1 ================= -->
    <div class="testimonial-item">

      <div class="flex items-stretch gap-0 rounded-2xl">

        <!-- Left Image -->
        <div class="flex-shrink-0 w-[240px]">
          <img
            src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&h=500&fit=crop&crop=face"
            class="w-full h-full object-cover rounded-2xl"
            style="min-height:280px;"
          />
        </div>

        <!-- Right Content -->
        <div class="flex-1 bg-white px-10 py-8 flex flex-col justify-between"
             style="border-radius: 0 1rem 1rem 0;">

          <div>
            <div class="text-[#c07a3a] text-5xl mb-4">&ldquo;</div>
            <p class="text-gray-500 text-[15px] leading-relaxed mb-6">
              Best service ever .. and they came at the time
            </p>
            <p class="font-oswald font-semibold text-[13px] uppercase tracking-widest text-[#2a1f14] mb-2">
              Mariam
            </p>
            <div class="flex gap-1 text-yellow-400 text-lg">
              ★ ★ ★ ★ <span class="text-gray-300">★</span>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- ================= TESTIMONIAL 2 ================= -->
    <div class="testimonial-item hidden">

      <div class="flex items-stretch gap-0 rounded-2xl">

        <div class="flex-shrink-0 w-[240px]">
          <img
            src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=400&h=500&fit=crop&crop=face"
            class="w-full h-full object-cover rounded-2xl"
            style="min-height:280px;"
          />
        </div>

        <div class="flex-1 bg-white px-10 py-8 flex flex-col justify-between"
             style="border-radius: 0 1rem 1rem 0;">

          <div>
            <div class="text-[#c07a3a] text-5xl mb-4">&ldquo;</div>
            <p class="text-gray-500 text-[15px] leading-relaxed mb-6">
              Very professional and clean work. Highly recommended!
            </p>
            <p class="font-oswald font-semibold text-[13px] uppercase tracking-widest text-[#2a1f14] mb-2">
              Aisha
            </p>
            <div class="flex gap-1 text-yellow-400 text-lg">
              ★ ★ ★ ★ ★
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- ================= NAV ARROWS ================= -->
    <div class="flex justify-between items-center mt-8">
      <button onclick="changeTestimonial(-1)"
        class="w-9 h-9 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
        ‹
      </button>

      <button onclick="changeTestimonial(1)"
        class="w-9 h-9 rounded-full border border-[#c9b9a8] flex items-center justify-center hover:border-[#c07a3a] transition-colors">
        ›
      </button>
    </div>

  </div>

</section>

<script>
  let currentIndex = 0;
  const items = document.querySelectorAll('.testimonial-item');

  function changeTestimonial(direction) {
    items[currentIndex].classList.add('hidden');

    currentIndex += direction;

    if (currentIndex < 0) currentIndex = items.length - 1;
    if (currentIndex >= items.length) currentIndex = 0;

    items[currentIndex].classList.remove('hidden');
  }
</script>