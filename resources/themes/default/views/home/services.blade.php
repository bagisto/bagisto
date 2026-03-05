<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        oswald: ['Oswald', 'sans-serif'],
        roboto: ['Roboto', 'sans-serif'],
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
        @php $slug = $category->translations->first()->slug ?? ''; @endphp
        <a href="javascript:void(0);" 
           data-slug="{{ $slug }}"
           class="px-2 py-2 whitespace-nowrap text-center font-roboto text-[16px] leading-[24px] tracking-[0.02em] transition-colors
           {{ ($activeCategorySlug ?? request()->route('slug')) == $slug 
               ? 'text-[#DFAA8B] border-b-2 border-[#DFAA8B]' 
               : 'text-[#78718B] hover:text-[#DFAA8B]' }}">
            {{ $category->name }}
        </a>
        @endforeach
    </div>

    <button id="scrollRight" class="p-1 text-gray-400 hover:text-gray-600">
        <svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    </button>

</div>

<div id="servicesContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
    @forelse($services as $service)
        <div class="flex flex-col sm:flex-row bg-white rounded-2xl overflow-hidden border border-[#CACACA]">
            <div class="relative w-full sm:w-[180px] h-[220px] sm:h-auto flex-shrink-0">
                <img src="{{ $service->product->images->first() ? asset('storage/' . $service->product->images->first()->path) : asset('images/placeholder.png') }}"
                     alt="{{ $service->name }}" class="w-full h-full object-cover">
                @if($service->duration)
                <span class="absolute top-3 left-3 bg-white text-[#371E0F] font-roboto text-[14px] px-3 py-1 rounded-full">
                    {{ $service->duration }}
                </span>
                @endif
            </div>
            <div class="flex flex-col flex-1 p-4 md:p-6">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="font-oswald font-medium text-[24px] leading-[100%] tracking-[0.1em] uppercase text-[#371E0F] block">{{ $service->name }}</span>
                        <span class="font-roboto text-[14px] text-[#C9C0D4]">{{ $service->slug }}</span>
                    </div>
                    <span class="font-oswald text-[20px] text-[#DFAA8B] uppercase">{{ core()->currency($service->price) }}</span>
                </div>
                <div class="font-roboto text-[15px] leading-[24px] tracking-[0.02em] text-[#78718B] mb-4">{!! $service->short_description !!}</div>
                <button class="bg-[#D7CDBA] text-[#371E0F] font-roboto font-medium text-[14px] px-5 py-1 rounded-full w-fit">Book Now</button>
            </div>
        </div>
    @empty
        <div class="col-span-2 text-center py-10 text-gray-400">No services found.</div>
    @endforelse
</div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const container = document.getElementById("categoryTabs");
    const servicesContainer = document.getElementById("servicesContainer");

    // Scroll buttons
    const btnLeft = document.getElementById("scrollLeft");
    const btnRight = document.getElementById("scrollRight");
    btnRight?.addEventListener("click", () => container.scrollBy({ left: 200, behavior: "smooth" }));
    btnLeft?.addEventListener("click", () => container.scrollBy({ left: -200, behavior: "smooth" }));

    // AJAX category click
    container.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function() {
            const slug = this.dataset.slug;
            if(!slug) return;

            // show loading
            servicesContainer.innerHTML = `<div class="col-span-2 text-center py-10 text-gray-500">Loading...</div>`;

            fetch(`{{ url('/services') }}/${slug}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.services && data.services.length > 0) {
                    data.services.forEach(service => {
                        html += `
<div class="flex flex-col sm:flex-row bg-white rounded-2xl overflow-hidden border border-[#CACACA]">
    <div class="relative w-full sm:w-[180px] h-[220px] sm:h-auto flex-shrink-0">
        <img src="${service.image}" alt="${service.name}" class="w-full h-full object-cover">
        ${service.duration ? `<span class="absolute top-3 left-3 bg-white text-[#371E0F] font-roboto text-[14px] px-3 py-1 rounded-full">${service.duration}</span>` : ''}
    </div>
    <div class="flex flex-col flex-1 p-4 md:p-6">
        <div class="flex justify-between items-start mb-2">
            <div>
                <span class="font-oswald font-medium text-[24px] leading-[100%] tracking-[0.1em] uppercase text-[#371E0F] block">${service.name}</span>
                <span class="font-roboto text-[14px] text-[#C9C0D4]">${service.slug}</span>
            </div>
            <span class="font-oswald text-[20px] text-[#DFAA8B] uppercase">${service.price}</span>
        </div>
        <div class="font-roboto text-[15px] leading-[24px] tracking-[0.02em] text-[#78718B] mb-4">${service.short_description}</div>
        <button class="bg-[#D7CDBA] text-[#371E0F] font-roboto font-medium text-[14px] px-5 py-1 rounded-full w-fit">Book Now</button>
    </div>
</div>`;
                    });
                } else {
                    html = `<div class="col-span-2 text-center py-10 text-gray-400">No services found.</div>`;
                }

                servicesContainer.innerHTML = html;

                // Update active tab: text color + underline border
                container.querySelectorAll('a').forEach(a => {
                    a.classList.remove('text-[#DFAA8B]', 'border-b-2', 'border-[#DFAA8B]');
                    a.classList.add('text-[#78718B]');
                });
                this.classList.add('text-[#DFAA8B]', 'border-b-2', 'border-[#DFAA8B]');
                this.classList.remove('text-[#78718B]');
            })
            .catch(err => {
                console.error(err);
                servicesContainer.innerHTML = `<div class="col-span-2 text-center py-10 text-red-500">Error loading services.</div>`;
            });
        });
    });

});
</script>