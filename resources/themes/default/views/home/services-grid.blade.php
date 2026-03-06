<style>
.no-scrollbar::-webkit-scrollbar{display:none;}
.no-scrollbar{-ms-overflow-style:none;scrollbar-width:none;}
</style>

<script>
tailwind.config = {
  theme:{
    extend:{
              fontFamily:{
        oswald:['Oswald','sans-serif'],
        roboto:['Roboto','sans-serif'],
      }
    }
  }
}
</script>

<section class="max-w-7xl mx-auto px-4 py-16 md:py-28">

<h2 class="font-oswald font-normal text-[36px] md:text-[60px] leading-[100%] uppercase text-center text-[#371E0F] mb-10 md:mb-14">
Services Treatment
</h2>

<div class="flex items-center justify-center gap-2 mb-10 md:mb-14">

<button id="scrollLeft" class="p-1 text-gray-400 hover:text-gray-600">
<svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
<polyline points="15 18 9 12 15 6"/>
</svg>
</button>

<div id="categoryTabs" class="flex gap-4 md:gap-6 overflow-x-auto scroll-smooth no-scrollbar max-w-full md:max-w-[800px]">

@foreach($categories as $category)
<a href="{{ route('shop.home.services', $category->translate(app()->getLocale())->slug) }}"
   class="px-2 py-2 whitespace-nowrap text-center font-roboto text-[16px] leading-[24px] tracking-[0.02em] transition-colors
   {{ ($activeCategorySlug ?? request()->route('slug')) == $category->translate(app()->getLocale())->slug 
       ? 'text-[#DFAA8B]' 
       : 'text-[#78718B] hover:text-[#DFAA8B]' }}">
    {{ $category->translate(app()->getLocale())->name }}
</a>
@endforeach

</div>

<button id="scrollRight" class="p-1 text-gray-400 hover:text-gray-600">
<svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
<polyline points="9 18 15 12 9 6"/>
</svg>
</button>

</div>

<!-- AJAX services container -->
<div id="servicesContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
    @include('shop::home.services-grid', ['services' => $services])
</div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const container = document.getElementById("categoryTabs");
    const servicesContainer = document.getElementById("servicesContainer");

    container.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const url = this.href;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                servicesContainer.innerHTML = data.html;

                // update active tab
                container.querySelectorAll('a').forEach(a => a.classList.remove('text-[#DFAA8B]'));
                this.classList.add('text-[#DFAA8B]');
            });
        });
    });

    // Scroll buttons
    const btnLeft = document.getElementById("scrollLeft");
    const btnRight = document.getElementById("scrollRight");

    btnRight.addEventListener("click", () => {
        container.scrollBy({ left: 200, behavior: "smooth" });
    });

    btnLeft.addEventListener("click", () => {
        container.scrollBy({ left: -200, behavior: "smooth" });
    });

});
</script>