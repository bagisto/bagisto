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
                jost: ['Jost', 'sans-serif'],
            }
        }
    }
}
</script>

<section class="max-w-7xl mx-auto px-4 py-32 mt-6 relative">

<!-- ================= CATEGORY TABS ================= -->
<div id="categoryTabsWrapper"
     class="flex items-center justify-center gap-1 mb-8 border-b border-gray-200 pb-2 relative">

    <button id="scrollLeft" class="p-1 text-gray-400 hover:text-gray-600 flex-shrink-0">
        <svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </button>

    <div id="categoryTabs"
         class="flex gap-2 overflow-x-auto scroll-smooth no-scrollbar max-w-[800px]">

        @foreach($categories as $category)
            <a href="{{ route('shop.home.services', $category->slug) }}"
               class="category-link px-4 py-1.5 text-[13px] whitespace-nowrap transition-colors
               {{ request()->route('slug') == $category->slug 
                    ? 'text-[#2a1f14] border-b-2 border-[#2a1f14] font-semibold' 
                    : 'text-gray-400 hover:text-[#2a1f14]' }}">
                {{ $category->name }}
            </a>
        @endforeach

    </div>

    <button id="scrollRight" class="p-1 text-gray-400 hover:text-gray-600 flex-shrink-0 ml-1">
        <svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    </button>
</div>

<!-- ================= SERVICES GRID ================= -->
<div id="servicesContainer" class="grid grid-cols-2 gap-5 mb-10">

@forelse($services as $service)
    <div class="flex gap-4 bg-white border-2 border-gray-300 rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">
        
        <div class="relative flex-shrink-0 w-[150px] h-[150px] rounded-xl overflow-hidden bg-[#d4c4b0]">
            <img src="{{ $service->base_image_url ?? asset('images/placeholder.png') }}" 
                 alt="{{ $service->name }}" 
                 class="w-full h-full object-cover">

            @if($service->duration)
                <span class="absolute top-2 left-2 bg-white/90 text-[#2a1f14] text-[10px] font-medium px-2 py-0.5 rounded-full">
                    {{ $service->duration }}
                </span>
            @endif
        </div>

        <div class="flex flex-col flex-1 py-1">
            <div class="flex items-start justify-between mb-1">
                <span class="font-oswald font-semibold text-[16px] uppercase tracking-wide text-[#2a1f14]">
                    {{ $service->name }}
                </span>

                <span class="font-oswald font-semibold text-[14px] text-[#c07a3a] ml-2 whitespace-nowrap">
                    {{ core()->currency($service->price) }}
                </span>
            </div>
            
            <p class="text-[12px] text-gray-500 leading-relaxed mb-3 line-clamp-3">
                {{ Str::limit(strip_tags($service->short_description), 120) }}
            </p>

            <a href="{{ route('shop.home.service.details',$service->id) }}">
            <button class="self-start bg-[#2a1f14] text-white text-[11px] font-medium tracking-wide px-5 py-2 rounded-full hover:bg-[#3d2e1e] transition-colors mt-auto">
                Book Now
            </button>
            </a>
        </div>
    </div>
@empty
    <div class="col-span-2 text-center py-10 text-gray-400">
        No services found.
    </div>
@endforelse

</div>
</section>

<!-- ================= SCRIPTS ================= -->
<script>
$(document).ready(function(){

    // Horizontal Scroll
    const container = document.getElementById("categoryTabs");
    const scrollAmount = 200;

    $("#scrollRight").click(function(){
        container.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });

    $("#scrollLeft").click(function(){
        container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    // AJAX Category Click
    $(document).on('click', '.category-link', function(e){
        e.preventDefault();

        let url = $(this).attr('href');
        let clickedTab = $(this);

        // Active tab style
        $('.category-link')
            .removeClass('text-[#2a1f14] border-b-2 border-[#2a1f14] font-semibold')
            .addClass('text-gray-400');

        clickedTab
            .addClass('text-[#2a1f14] border-b-2 border-[#2a1f14] font-semibold')
            .removeClass('text-gray-400');

        // Loader
        $('#servicesContainer').html('<div class="col-span-2 text-center py-10">Loading...</div>');

        $.ajax({
            url: url,
            type: "GET",
            success: function(response){
                let html = $(response).find('#servicesContainer').html();
                $('#servicesContainer').html(html);
            },
            error: function(){
                $('#servicesContainer').html('<div class="col-span-2 text-center py-10 text-red-500">Something went wrong.</div>');
            }
        });

    });

});
</script>