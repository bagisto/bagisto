<!-- ================= GALLERY SECTION ================= -->
<section class="max-w-7xl mx-auto px-4 py-32 mt-6">

    <!-- Category Tabs -->
    <div class="flex items-center justify-center gap-8 mb-10 border-b border-gray-200 pb-3">

        <button class="gallery-tab text-[#c07a3a] border-b-2 border-[#c07a3a] pb-1 text-sm"
                data-category="all">All</button>

        <button class="gallery-tab text-gray-400 hover:text-[#2a1f14] text-sm"
                data-category="nail">Nail</button>

        <button class="gallery-tab text-gray-400 hover:text-[#2a1f14] text-sm"
                data-category="hair">Hair</button>

        <button class="gallery-tab text-gray-400 hover:text-[#2a1f14] text-sm"
                data-category="face">Face</button>

        <button class="gallery-tab text-gray-400 hover:text-[#2a1f14] text-sm"
                data-category="perfume">Perfumes</button>

    </div>

    <!-- Gallery Grid -->
    <div id="galleryContainer" class="grid grid-cols-3 gap-5">

        <div class="gallery-item nail transition-all duration-300">
            <img src="/images/nail1.jpg" class="w-full h-[250px] object-cover rounded-xl">
        </div>

        <div class="gallery-item nail transition-all duration-300">
            <img src="/images/nail2.jpg" class="w-full h-[320px] object-cover rounded-xl">
        </div>

        <div class="gallery-item hair transition-all duration-300">
            <img src="/images/hair1.jpg" class="w-full h-[320px] object-cover rounded-xl">
        </div>

        <div class="gallery-item hair transition-all duration-300">
            <img src="/images/hair2.jpg" class="w-full h-[250px] object-cover rounded-xl">
        </div>

        <div class="gallery-item face transition-all duration-300">
            <img src="/images/face1.jpg" class="w-full h-[180px] object-cover rounded-xl">
        </div>

        <div class="gallery-item perfume transition-all duration-300">
            <img src="/images/perfume1.jpg" class="w-full h-[180px] object-cover rounded-xl">
        </div>

    </div>

</section>


<!-- ================= ICON SERVICES SECTION ================= -->
<section class="max-w-7xl mx-auto px-4 py-24">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">

        <!-- Manicure -->
        <div class="flex flex-col items-center">
            <div class="mb-6">
                <img src="/images/icons/manicure.svg" class="w-14 h-14 object-contain">
            </div>
            <h3 class="font-oswald text-[14px] tracking-[2px] uppercase text-[#2a1f14] mb-3">
                Manicure
            </h3>
            <p class="text-gray-400 text-[12px] leading-relaxed max-w-[200px]">
                In cursus, neque a varius tempus, ante lorem dapibus urna, eu.
            </p>
        </div>

        <!-- Pedicure -->
        <div class="flex flex-col items-center">
            <div class="mb-6">
                <img src="/images/icons/pedicure.svg" class="w-14 h-14 object-contain">
            </div>
            <h3 class="font-oswald text-[14px] tracking-[2px] uppercase text-[#2a1f14] mb-3">
                Pedicure
            </h3>
            <p class="text-gray-400 text-[12px] leading-relaxed max-w-[200px]">
                In cursus, neque a varius tempus, ante lorem dapibus urna, eu.
            </p>
        </div>

        <!-- Nail Care -->
        <div class="flex flex-col items-center">
            <div class="mb-6">
                <img src="/images/icons/nail-care.svg" class="w-14 h-14 object-contain">
            </div>
            <h3 class="font-oswald text-[14px] tracking-[2px] uppercase text-[#2a1f14] mb-3">
                Nail Care
            </h3>
            <p class="text-gray-400 text-[12px] leading-relaxed max-w-[200px]">
                In cursus, neque a varius tempus, ante lorem dapibus urna, eu.
            </p>
        </div>

        <!-- Cosmetics -->
        <div class="flex flex-col items-center">
            <div class="mb-6">
                <img src="/images/icons/cosmetics.svg" class="w-14 h-14 object-contain">
            </div>
            <h3 class="font-oswald text-[14px] tracking-[2px] uppercase text-[#2a1f14] mb-3">
                Cosmetics
            </h3>
            <p class="text-gray-400 text-[12px] leading-relaxed max-w-[200px]">
                In cursus, neque a varius tempus, ante lorem dapibus urna, eu.
            </p>
        </div>

    </div>

</section>


<!-- ================= GALLERY FILTER SCRIPT ================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll(".gallery-tab");
    const items = document.querySelectorAll(".gallery-item");

    tabs.forEach(tab => {
        tab.addEventListener("click", function () {

            const category = this.dataset.category;

            tabs.forEach(t => {
                t.classList.remove("text-[#c07a3a]", "border-b-2", "border-[#c07a3a]");
                t.classList.add("text-gray-400");
            });

            this.classList.add("text-[#c07a3a]", "border-b-2", "border-[#c07a3a]");
            this.classList.remove("text-gray-400");

            items.forEach(item => {
                if (category === "all" || item.classList.contains(category)) {
                    item.classList.remove("hidden");
                } else {
                    item.classList.add("hidden");
                }
            });

        });
    });

});
</script>