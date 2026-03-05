<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

{{-- ================= TOP BAR ================= --}}
<div class="bg-[#F3EFEE] w-full text-[#371E0F] font-['Roboto'] text-sm">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="flex justify-between items-center py-2 md:py-3 flex-wrap md:flex-nowrap">

            <!-- LEFT SIDE -->
            <div class="flex items-center gap-4 md:gap-8 flex-wrap">
                <!-- Phone -->
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/support.png') }}" alt="Support" class="h-4 w-4">
                    <span class="topbar-text">+971 123 456 7890</span>
                </div>
                <!-- Location -->
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/location.png') }}" alt="Location" class="h-4 w-4">
                    <span class="topbar-text">Home Service, Abu Dhabi</span>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center gap-4 md:gap-8 mt-2 md:mt-0 flex-wrap">

                <!-- Language Dropdown -->
                <div class="relative">
                    <div class="flex items-center gap-2 cursor-pointer topbar-text dropdown-toggle">
                        <img src="https://flagcdn.com/w20/ae.png" class="w-5 h-5 rounded-full" alt="UAE Flag">
                        <span>Arabic</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#371E0F" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                    <div class="dropdown-menu absolute right-0 mt-2 w-36 bg-white border rounded shadow-lg hidden z-50">
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2">
                            <img src="https://flagcdn.com/w20/ae.png" class="w-5 h-5 rounded-full"> Arabic
                        </div>
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2">
                            <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-5 rounded-full"> English
                        </div>
                    </div>
                </div>

                <!-- Currency Dropdown -->
                <div class="relative">
                    <div class="flex items-center gap-2 cursor-pointer topbar-text dropdown-toggle">
                        <span>Currency: UAE</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#371E0F" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                    <div class="dropdown-menu absolute right-0 mt-2 w-36 bg-white border rounded shadow-lg hidden z-50">
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">UAE Dirham (AED)</div>
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">US Dollar (USD)</div>
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Euro (EUR)</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- ================= END TOP BAR ================= --}}

{{-- ================= MAIN HEADER ================= --}}
<header class="w-full bg-white shadow-sm">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="flex items-center justify-between py-4 md:py-5 flex-wrap md:flex-nowrap">

            {{-- LEFT SECTION --}}
            <div class="flex items-center gap-4 md:gap-8 w-full md:w-auto justify-between">

                {{-- LOGO --}}
                <div class="flex-shrink-0">
                    <a href="{{ route('shop.home.index') }}">
                        <img src="{{ asset('themes/shop/default/images/logo.png') }}" alt="Logo" class="h-10 md:h-12">
                    </a>
                </div>

                {{-- SEARCH BOX --}}
                <div class="relative flex-1 md:flex-none w-full md:w-[380px] mt-2 md:mt-0">
                    <form action="{{ route('shop.search.index') }}" method="get">
                        <input 
                            type="text" 
                            name="q"
                            placeholder="Search services/products here..." 
                            class="w-full h-[45px] pl-4 pr-12 text-[14px] md:text-[16px] leading-[20px] md:leading-[24px] tracking-[0.02em] font-['Roboto'] font-normal text-[#371E0F] placeholder-[#371E0F] border border-gray-200 focus:border-black focus:outline-none rounded-md"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#371E0F]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.6-5.65a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>

            {{-- RIGHT SECTION --}}
            <div class="flex items-center gap-4 lg:gap-10 mt-3 lg:mt-0">

                {{-- NAVIGATION --}}
                <nav class="hidden lg:flex items-center gap-10 text-[#371E0F]">
                    <a href="{{ route('shop.home.index') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.index' ? 'active' : '' }}">Home</a>
                    <a href="{{ route('shop.home.aboutus') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.aboutus' ? 'active' : '' }}">About Us</a>
                    <a href="{{ route('shop.home.all.services') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.all.services' ? 'active' : '' }}">Services</a>
                    <a href="{{ route('shop.gallery.index') }}" class="nav-link {{ Route::currentRouteName()=='shop.gallery.index' ? 'active' : '' }}">Gallery</a>
                    <a href="{{ route('shop.home.contactus') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.contactus' ? 'active' : '' }}">Contact Us</a>
                </nav>

                {{-- HAMBURGER MENU FOR MOBILE & TABLET --}}
                <div class="flex lg:hidden items-center cursor-pointer" id="mobile-menu-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#371E0F" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>

                {{-- CART --}}
                <div class="relative cursor-pointer">
                    <img src="{{ asset('images/cart.png') }}" alt="Cart" class="w-6 h-6">
                </div>

                {{-- WISHLIST --}}
                <div class="relative cursor-pointer">
                    <img src="{{ asset('images/wishlist.png') }}" alt="Wishlist" class="w-6 h-6">
                </div>
            </div>

        </div>

        {{-- MOBILE/TABLET NAVIGATION --}}
        <div class="lg:hidden" id="mobile-menu" style="display:none;">
            <nav class="flex flex-col gap-3 mt-3">
                <a href="{{ route('shop.home.index') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.index' ? 'active' : '' }}">Home</a>
                <a href="{{ route('shop.home.aboutus') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.aboutus' ? 'active' : '' }}">About Us</a>
                <a href="{{ route('shop.home.all.services') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.all.services' ? 'active' : '' }}">Services</a>
                <a href="{{ route('shop.gallery.index') }}" class="nav-link {{ Route::currentRouteName()=='shop.gallery.index' ? 'active' : '' }}">Gallery</a>
                <a href="{{ route('shop.home.contactus') }}" class="nav-link {{ Route::currentRouteName()=='shop.home.contactus' ? 'active' : '' }}">Contact Us</a>
            </nav>
        </div>
    </div>
</header>

<style>
.nav-link {
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    letter-spacing: 0.02em;
    color: #371E0F;
    position: relative;
    padding-bottom: 4px;
    transition: 0.3s ease;
}

/* Smaller font for mobile */
@media (max-width: 639px) {
    .nav-link { font-size: 14px; line-height: 20px; }
    .topbar-text { font-size: 12px; line-height: 16px; }
}

.nav-link:hover {
    opacity: 0.7;
}
.nav-link.active::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 24px;
    height: 2px;
    background-color: #F3EFEE;
}
.topbar-text {
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
    font-style: normal;
    font-size: 14px;
    line-height: 100%;
    letter-spacing: 0.02em;
}

/* Dropdown hidden by default */
.dropdown-menu { display: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile/tablet menu toggle
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileToggle.addEventListener('click', () => {
        mobileMenu.style.display = mobileMenu.style.display === 'none' ? 'block' : 'none';
    });

    // Dropdowns
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', () => {
            const menu = toggle.nextElementSibling;
            menu.classList.toggle('hidden');
        });
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (!menu.previousElementSibling.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    });
});
</script>