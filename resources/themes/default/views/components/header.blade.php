<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script> 

{{-- ================= TOP BAR ================= --}}
<div style="background-color: #F3EFEE; width: 100%;">
    <div class="max-w-[1400px] mx-auto px-[60px] max-1180:px-8">
        <div 
            class="flex justify-between items-center py-3"
            style="
                color: #371E0F;
                font-family: 'Roboto', sans-serif;
                font-size: 14px;
                font-weight: 400;
                letter-spacing: 0.02em;
            "
        >

            <!-- LEFT SIDE -->
            <div class="flex items-center gap-8">

                <!-- Phone -->
                <div class="flex items-center gap-2">
                    <!-- Headphone Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="#371E0F" 
                         class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M3 12a9 9 0 0118 0v3a3 3 0 01-3 3h-1v-6h4M3 15h4v6H6a3 3 0 01-3-3v-3z"/>
                    </svg>

                    <span>+971 123 456 7890</span>
                </div>

                <!-- Location -->
                <div class="flex items-center gap-2">
                    <!-- Location Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="#371E0F" 
                         class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M12 21c4.97-4.97 7.5-8.1 7.5-11.25A7.5 7.5 0 004.5 9.75C4.5 12.9 7.03 16.03 12 21z"/>
                        <circle cx="12" cy="9.75" r="2.25" stroke="#371E0F"/>
                    </svg>

                    <span>Home Service, Abu Dhabi</span>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center gap-8">

                <!-- Language -->
                <div class="flex items-center gap-2 cursor-pointer">
                    <img src="https://flagcdn.com/w20/ae.png" 
                         class="w-5 h-5 rounded-full" 
                         alt="UAE Flag">

                    <span>Arabic</span>

                    <!-- Dropdown Arrow -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="#371E0F" 
                         class="w-4 h-4">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </div>

                <!-- Currency -->
                <div class="flex items-center gap-2 cursor-pointer">
                    <span>Currency: UAE</span>

                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="#371E0F" 
                         class="w-4 h-4">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </div>

            </div>

        </div>
    </div>
</div>
{{-- ================= END TOP BAR ================= --}}

{{-- ================= MAIN HEADER ================= --}}
<header class="w-full bg-white border-b">

    <div class="max-w-[1400px] mx-auto px-[60px]">
        <div class="flex items-center justify-between py-5">

            {{-- LEFT SECTION --}}
            <div class="flex items-center gap-8">

                {{-- LOGO --}}
                <div>
                    <img 
                        src="{{ asset('themes/shop/default/images/logo.png') }}" 
                        alt="Logo" 
                        class="h-10"
                    >
                </div>

{{-- SEARCH BOX --}}
<div class="relative">

    <input 
        type="text" 
        placeholder="Search services/products here..."
        class="w-[380px] h-[45px] pl-5 pr-14
               focus:outline-none focus:ring-1 focus:ring-black
               font-['Roboto'] font-normal text-[16px] 
               leading-[24px] tracking-[0.02em]" style="color:#371E0F;"
    >

    {{-- Search Icon --}}
    <button type="submit"
        class="absolute right-4 top-1/2 -translate-y-1/2" style="color:#371E0F;">
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke-width="1.8" 
             stroke="currentColor" 
             class="w-5 h-5">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  d="m21 21-4.35-4.35m1.6-5.65a7.25 7.25 0 11-14.5 0 7.25 7.25 0 0114.5 0z"/>
        </svg>

    </button>

</div>

            </div>

            {{-- RIGHT SECTION --}}
            <div class="flex items-center gap-10">

                {{-- NAVIGATION --}}
                <nav class="flex items-center" style="gap:52px;">

                    <a href="#"
                       class="nav-link active">
                        Home
                    </a>

                    <a href="#"
                       class="nav-link">
                        Shop
                    </a>

                    <a href="#"
                       class="nav-link">
                        About
                    </a>

                    <a href=""
                       class="nav-link">
                        Contact
                    </a>

                </nav>

                {{-- CART --}}
                <div class="relative cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="black" 
                         class="w-6 h-6">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835L5.76 8.25m0 0h12.99c.966 0 1.725.835 1.593 1.791l-1.2 8.25A1.875 1.875 0 0117.29 20.25H8.21a1.875 1.875 0 01-1.853-1.459L4.5 6.75H2.25"/>
                    </svg>
                </div>

                {{-- WISHLIST --}}
                <div class="cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="black" 
                         class="w-6 h-6">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M21 8.25c0-2.485-2.015-4.5-4.5-4.5-1.74 0-3.246 1.004-4 2.462C11.746 4.754 10.24 3.75 8.5 3.75 6.015 3.75 4 5.765 4 8.25c0 6.75 8.5 11.25 8.5 11.25S21 15 21 8.25z"/>
                    </svg>
                </div>

            </div>
        </div>
    </div>

</header>

{{-- NAVIGATION STYLES --}}
<style>
    .nav-link {
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0.02em;
        color: #371E0F;
        position: relative;
        padding-bottom: 8px;
        transition: 0.3s ease;
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
</style>

{{-- ================= END MAIN HEADER ================= --}}


