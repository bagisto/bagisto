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
@endphp

<div
    class="w-full flex justify-between px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0 pb-[5px] pt-[17px]"
>
    {{--
        This section will provide categories for the first, second, and third levels. If
        additional levels are required, users can customize them according to their needs.
    --}}
    <div class="flex items-center gap-x-[54px] max-[1180px]:gap-x-[35px]">
        <a
            herf=""
            class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block mb-[16px]"
        >
        </a>

        <div class="flex items-center gap-x-[45px]  max-[1180px]:gap-x-[30px]">
            {{--
                For active category: `text-sm border-t-0 border-b-[2px] border-l-0 border-r-0 border-navyBlue`.
            --}}
            @foreach ($categories as $firstLevelCategory)
                <div class="pt-[8px] pb-[20px] relative group">
                    <span class="text-sm">
                        <a href="{{ $firstLevelCategory->url }}">
                            {{ $firstLevelCategory->name }}
                        </a>
                    </span>

                    <div
                        class="hidden group-hover:block max-h-[580px] max-w-[1260px] overflow-auto overflow-x-auto -left-[35px] w-max absolute top-[54px] bg-white p-[35px] border border-b-0 border-l-0 border-r-0 border-t-[1px] border-[#F3F3F3]"
                    >
                        <div class="flex aigns gap-x-[70px] justify-between">
                            <div class="grid grid-cols-[1fr] gap-[20px] content-start w-full flex-auto min-w-max max-w-[150px]">
                                @foreach ($firstLevelCategory->children as $secondLevelCategory)
                                    <p class="text-navyBlue font-medium">
                                        <a href="{{ $secondLevelCategory->url }}">
                                            {{ $secondLevelCategory->name }}
                                        </a>
                                    </p>

                                    <ul class="grid grid-cols-[1fr] gap-[12px]">
                                        @foreach ($secondLevelCategory->children as $thirdLevelCategory)
                                            <li class="text-[14px] font-medium text-[#7D7D7D]">
                                                <a href="{{ $thirdLevelCategory->url }}">
                                                    {{ $thirdLevelCategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-x-[35px] max-lg:gap-x-[30px] max-[1100px]:gap-x-[25px] pb-[11px]">
        <form class="flex items-center max-w-[445px]">
            <label
                for="organic-search"
                class="sr-only"
            >
                {{-- @translations --}}
                @lang('Search')
            </label>

            <div class="relative w-full">
                <div
                    class="icon-search text-[22px] absolute left-[12px] top-[12px] flex items-center pointer-events-none">
                </div>

                <input
                    type="text"
                    class="bg-[#F5F5F5] rounded-lg block w-full px-11 py-3.5 text-gray-900 text-xs font-medium"
                    placeholder="Search for products"
                    required
                >

                <button
                    type="button"
                    class="icon-camera text-[22px] absolute top-[12px] right-[12px] flex items-center pr-3"
                >
                </button>
            </div>
        </form>

        {{-- =============== Will remove it. =============== --}}
            @auth('customer')
                <form
                    method="POST"
                    action="{{ route('shop.customer.session.destroy') }}"
                    id="customerLogout"
                >
                    @csrf

                    @method('DELETE')
                </form>

                <a
                    class="border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
                    href="{{ route('shop.customer.session.destroy') }}"
                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                >
                    {{ __('shop::app.header.logout') }}
                </a>
            @endauth
        {{-- =============== Will remove it. =============== --}}

        <div>
            <span class="icon-heart text-[24px] inline-block cursor-pointer"></span>
        </div>

        <div>
            <span class="icon-cart text-[24px] cursor-pointer"></span>
        </div>

        <x-shop::dropdown position="bottom-right">
            <x-slot:toggle>
                <span class="icon-users text-[24px] inline-block cursor-pointer"></span>
            </x-slot:toggle>

            <x-slot:content>
                <div class="grid gap-[10px]">
                    <p class="text-[20px] font-dmserif">
                        {{-- @translations --}}
                        @lang('Welcome Guest')
                    </p>

                    <p class="text-[14px]">
                        {{-- @translations --}}
                        @lang('Manage Cart, Orders & Wishlist')
                    </p>
                </div>

                <p class="py-2px border border-[#E9E9E9] mt-[12px] w-full"></p>

                <div class="flex gap-[16px] mt-[25px]">
                    <a
                        href="{{ route('shop.customer.session.create') }}"
                        class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[15px] px-[29px] rounded-[18px] text-center cursor-pointer"
                    >
                        {{-- @translations --}}
                        @lang('Sign In')
                    </a>

                    <a
                        href="{{ route('shop.customers.register.index') }}"
                        class="m-0 ml-[0px] block mx-auto bg-white border-2 border-navyBlue text-navyBlue text-base w-max font-medium py-[14px] px-[29px] rounded-[18px] text-center cursor-pointer"
                    >
                        {{-- @translations --}}
                        @lang('Sign Up')
                    </a>
                </div>
            </x-slot:content>
        </x-shop::dropdown>
    </div>
</div>
