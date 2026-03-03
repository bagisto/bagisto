<section class="max-w-7xl mx-auto px-4 py-24">

    <!-- Top Section -->
    <div class="grid grid-cols-12 gap-10">

        <!-- Thumbnails -->
        <div class="col-span-1 flex flex-col gap-4">
            <img src="/images/thumb1.jpg" class="w-16 h-16 object-cover rounded-lg cursor-pointer border">
            <img src="/images/thumb2.jpg" class="w-16 h-16 object-cover rounded-lg cursor-pointer border">
            <img src="/images/thumb3.jpg" class="w-16 h-16 object-cover rounded-lg cursor-pointer border">
            <img src="/images/thumb4.jpg" class="w-16 h-16 object-cover rounded-lg cursor-pointer border">
        </div>

        <!-- Main Image -->
        <div class="col-span-5">
            <div class="relative">
                <img src="/images/main-service.jpg"
                     class="w-full h-[420px] object-cover rounded-xl">

                <!-- Wishlist Icon -->
                <div class="absolute top-4 right-4 bg-white p-2 rounded-full shadow">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 10-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Service Info -->
        <div class="col-span-6">

            <h1 class="font-oswald text-3xl uppercase text-[#2a1f14] mb-4">
                {{ $service->name }}
            </h1>

            <!-- Price -->
            <div class="flex items-center gap-4 mb-2">
                <span class="text-[#c07a3a] text-xl font-semibold">
                    {{ core()->currency($service->price) }}
                </span>

                <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded-full">
                    Sold out
                </span>
            </div>

            <p class="text-xs text-gray-400 mb-6">
                All prices include tax
            </p>

            <!-- Info Boxes -->
            <div class="grid grid-cols-2 gap-4 mb-6">

                <div class="flex items-center gap-3 bg-gray-100 rounded-lg p-4">
                    <svg class="w-5 h-5 text-[#c07a3a]" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 00-7 17l-1 3 3-1a10 10 0 1010-19z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium">No Address Hassle</p>
                        <p class="text-xs text-gray-500">We will collect the address for you</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-gray-100 rounded-lg p-4">
                    <svg class="w-5 h-5 text-[#c07a3a]" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M3 3h18v13H3z"/>
                        <path d="M16 16v2a2 2 0 01-2 2H10a2 2 0 01-2-2v-2"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium">Free delivery</p>
                        <p class="text-xs text-gray-500">On orders over AED 300</p>
                    </div>
                </div>

            </div>

            <!-- Button -->
            <button class="bg-[#c07a3a] text-white px-8 py-3 rounded-full text-sm tracking-wide hover:bg-[#a8652f] transition">
                BOOK NOW
            </button>

        </div>

    </div>


    <!-- Description Section -->
    <div class="mt-16">

        <h2 class="font-oswald uppercase tracking-wide text-[#2a1f14] mb-4">
            Description
        </h2>

        <p class="text-gray-500 text-sm leading-relaxed mb-8 max-w-3xl">
            {{ Str::limit(strip_tags($service->short_description), 200) }}
        </p>

        <h3 class="font-oswald uppercase tracking-wide text-[#2a1f14] mb-4">
            Mushaf Holder Details:
        </h3>

        <ul class="text-gray-500 text-sm space-y-2">
            <li>Brand: Floward</li>
            <li>Type: Holy Quran</li>
            <li>Color: Brown</li>
            <li>Material: Leather</li>
        </ul>

    </div>

</section>