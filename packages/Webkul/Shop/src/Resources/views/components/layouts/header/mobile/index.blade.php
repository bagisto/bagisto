{{--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
--}}
@inject('categoryRepository', 'Webkul\Category\Repositories\CategoryRepository')

{{--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
--}}
@php
    $categories = $categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

    $showCompare = (bool) core()->getConfigData('general.content.shop.compare_option');

    $showWishlist = (bool) core()->getConfigData('general.content.shop.wishlist_option');
@endphp

<div class="bs-mobile-menu flex-wrap hidden max-lg:flex px-[15px] pt-[25px] gap-[15px] max-lg:mb-[15px]">
    <div class="w-full flex justify-between items-center px-[6px]">
        <div class="flex items-center gap-x-[5px]">
            <x-shop::drawer
                position="left"
                width="300"
            >
                <x-slot:toggle>
                    <span class="icon-hamburger text-[24px]"></span>
        
                    <a 
                        herf="" 
                        class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block"
                    >
                    </a>
                </x-slot:toggle>

                <x-slot:header>
                    <div class="flex justify-between p-[20px] items-center">
                        <span class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block mb-[16px]"></span>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    <a href="{{ route('shop.customer.session.create') }}">
                        <div class="rounded-[12px] border border-[#f3f3f5] p-[10px] relative mb-[40px]">
                            <div class="flex items-center gap-[15px]  max-w-[calc(100%-20px)]">
                                <img 
                                    class="rounded-[12px] w-[64px] h-[65px]" 
                                    src="{{ bagisto_asset('images/thank-you.png') }}"
                                    title="Sign up or login"
                                    alt="Bag image"
                                >

                                <div>
                                    <p class="text-[16px] font-medium">Sign up or Login</p>

                                    <p class="text-[12px] mt-[10px]">Get UPTO 40% OFF</p>
                                </div>
                            </div>

                            <span class="absolute right-[10px] top-[50%] -translate-y-[50%] bg-[position:-146px_-65px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
                        </div>
                    </a>

                    @foreach ($categories as $firstLevelCategory)
                        <div class="flex justify-between items-center">
                            <a
                                href="{{ $firstLevelCategory->url }}"
                                class="flex items-center justify-between pb-[20px] mt-[20px] border border-b-[1px] border-l-0 border-r-0 border-t-0 border-[#f3f3f5]"
                            >
                                {{ $firstLevelCategory->name }}
                            </a>

                            @if ($firstLevelCategory->children->isNotEmpty())
                                <span class="text-[24px] icon-arrow-down"></span>
                            @endif
                        </div>
                    @endforeach
                </x-slot:content>

                <x-slot:footer></x-slot:footer>
            </x-shop::drawer>
        </div>

        <div class="">
            <div class="flex  items-center gap-x-[20px]">
                <span class="icon-heart text-[24px] inline-block cursor-pointer"></span>
                <span class="icon-cart text-[24px] cursor-pointer"></span>
                <span class="icon-users text-[24px] inline-block cursor-pointer"></span>
            </div>
        </div>
    </div>

    <form class="flex items-center w-full">
        <label for="organic-search" class="sr-only">Search</label>

        <div class="relative w-full">
            <div
                class="icon-search text-[25px] absolute left-[12px] top-[12px] flex items-center pointer-events-none">
            </div>

            <input type="text"
                class=" border border-['#E3E3E3'] rounded-xl block w-full px-11 py-3.5 text-gray-900 text-xs font-medium"
                placeholder="Search for products" required>

            <button type="button"
                class="icon-camera text-[22px] absolute top-[12px] right-[12px] flex items-center pr-3">
            </button>
        </div>
    </form>
</div>
