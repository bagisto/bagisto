<!-- Start Header -->
{!! view_render_event('bagisto.shop.layout.header.before') !!}

<header class="bs-main-header">
    <div class="bs-dekstop-menu flex flex-wrap max-lg:hidden">
        <div
            class="flex justify-between items-center w-full border border-t-0 border-b-[1px] border-l-0 border-r-0 py-[11px] px-16">
            <select
                class="font-medium px-0 text-sm text-black bg-transparent border-0 border-gray-20 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                <option value="usd">USD</option>
                <option value="inr">INR</option>
            </select>

            <p class="text-xs font-medium">Get UPTO 40% OFF on your 1st order <a href="" class="underline">SHOP NOW</a>
            </p>

            <select
                class="font-medium px-0 text-sm text-black bg-transparent border-0 border-gray-20 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                <option class="bg-[position:-169px_-26px] bs-main-sprite" value="US">US/EN</option>
            </select>
        </div>

        <div
            class="w-full flex justify-between px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0 pb-[5px] pt-[17px]">
            <div class="flex  items-center gap-x-[54px] max-[1180px]:gap-x-[35px]">
                <a herf=""
                    class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block mb-[16px]"></a>
                <div class="flex  items-center gap-x-[45px]  max-[1180px]:gap-x-[30px]">
                    <span
                        class="text-sm pb-[20px] border-t-0 border-b-[2px] border-l-0 border-r-0 border-navyBlue">Men</span>
                    <span class="pb-[20px]">Women</span>
                    <span class="pb-[20px]">Kids</span>
                    <span class="pb-[20px]">Luggage</span>
                </div>
            </div>

            <div class="flex  items-center gap-x-[35px] max-lg:gap-x-[30px] max-[1100px]:gap-x-[25px] pb-[11px]">
                <form class="flex items-center max-w-[445px]">
                    <label for="organic-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div
                            class="bg-[position:-2px_-115px] bs-main-sprite w-[21px] h-[20px] absolute left-[12px] top-[12px] flex items-center pl-3 pointer-events-none">
                        </div>
                        <input type="text"
                            class="bg-[#F5F5F5]  rounded-lg block w-full px-11 py-3.5 text-gray-900 text-xs font-medium"
                            placeholder="Search for products" required>
                        <button type="button"
                            class="bg-[position:0px_-88px] bs-main-sprite w-[24px] h-[22px] absolute top-[12px] right-[12px] flex items-center pr-3">
                        </button>
                    </div>
                </form>
                <span
                    class="bg-[position:-169px_-65px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
                <span
                    class="bg-[position:-100px_-138px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
                <span
                    class="bg-[position:-146px_-65px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
            </div>
        </div>
    </div>

    <div class="bs-mobile-menu flex-wrap hidden max-lg:flex px-[15px] pt-[25px] gap-[15px] max-lg:mb-[15px]">
        <div class="w-full flex justify-between items-center px-[6px]">
            <div class="flex  items-center gap-x-[5px]">
                <span class="bg-[position:-168px_-112px] bs-main-sprite w-[24px] h-[24px]"></span>
                <a herf="" class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block"></a>
            </div>

            <div class="">
                <div class="flex  items-center gap-x-[25px]">
                    <span
                        class="bg-[position:-169px_-65px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
                    <span
                        class="bg-[position:-100px_-138px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
                    <span
                        class="bg-[position:-146px_-65px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
                </div>
            </div>
        </div>

        <form class="flex items-center w-full">
            <label for="organic-search" class="sr-only">Search</label>

            <div class="relative w-full">
                <div
                    class="bg-[position:-2px_-114px] bs-main-sprite w-[21px] h-[20px] absolute left-[12px] top-[12px] flex items-center pl-3 pointer-events-none">
                </div>

                <input type="text"
                    class=" border border-['#E3E3E3'] rounded-xl block w-full px-11 py-3.5 text-gray-900 text-xs font-medium"
                    placeholder="Search for products" required>

                <button type="button"
                    class="bg-[position:0px_-88px] bs-main-sprite w-[24px] h-[22px] absolute top-[12px] right-[12px] flex items-center pr-3">
                </button>
            </div>
        </form>
    </div>
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}
