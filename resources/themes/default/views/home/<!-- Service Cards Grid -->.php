<!-- Service Cards Grid -->
<div class="grid grid-cols-2 gap-5 mb-10">

    @forelse($services as $service)
        <div class="flex gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">
            
            <!-- Image -->
            <div class="relative flex-shrink-0 w-[150px] h-[150px] rounded-xl overflow-hidden bg-[#d4c4b0]">
                
                <img 
                    src="{{ $service->base_image_url ?? asset('images/placeholder.png') }}" 
                    alt="{{ $service->name }}" 
                    class="w-full h-full object-cover"
                />

                @if($service->duration)
                    <span class="absolute top-2 left-2 bg-white/90 text-[#2a1f14] text-[10px] font-medium px-2 py-0.5 rounded-full">
                        {{ $service->duration }}
                    </span>
                @endif
            </div>

            <!-- Content -->
            <div class="flex flex-col flex-1 py-1">

                <div class="flex items-start justify-between mb-1">
                    <span class="font-oswald font-semibold text-[16px] uppercase tracking-wide text-[#2a1f14]">
                        {{ $service->name }}
                    </span>

                    <span class="font-oswald font-semibold text-[14px] text-[#c07a3a] ml-2 whitespace-nowrap">
                        {{ core()->currency($service->price) }}
                    </span>
                </div>

                <p class="text-[12px] text-gray-400 mb-1">
                    {{ $service->short_description }}
                </p>

                <p class="text-[12px] text-gray-500 leading-relaxed mb-3 line-clamp-3">
                    {{ Str::limit(strip_tags($service->description), 120) }}
                </p>

                <button class="self-start bg-[#2a1f14] text-white text-[11px] font-medium tracking-wide px-5 py-2 rounded-full hover:bg-[#3d2e1e] transition-colors mt-auto">
                    Book Now
                </button>

            </div>
        </div>

    @empty
        <div class="col-span-2 text-center py-10 text-gray-400">
            No services found.
        </div>
    @endforelse

</div>


{{ route('shop.home.services',$category['slug']) }}