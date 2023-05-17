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
    $categories = $categoryRepository
        ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
@endphp

<div
    class="w-full flex justify-between px-[60px] border border-t-0 border-b-[1px] border-l-0 border-r-0 pb-[5px] pt-[17px]">
    <div class="flex items-center gap-x-[54px] max-[1180px]:gap-x-[35px]">
        <a herf=""
            class="bs-logo bg-[position:-5px_-3px] bs-main-sprite w-[131px] h-[29px] inline-block mb-[16px]"></a>

        <div class="flex items-center gap-x-[45px] max-[1180px]:gap-x-[30px]">
            {{--
                For active category: `pb-[20px] text-sm border-t-0 border-b-[2px] border-l-0 border-r-0 border-navyBlue`.
            --}}
            @foreach ($categories as $category)
                <span class="pb-[20px]">{{ $category->name }}</span>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-x-[35px] max-lg:gap-x-[30px] max-[1100px]:gap-x-[25px] pb-[11px]">
        <form class="flex items-center max-w-[445px]">
            <label for="organic-search" class="sr-only">Search</label>
            <div class="relative w-full">
                <div
                    class="bg-[position:-2px_-115px] bs-main-sprite w-[21px] h-[20px] absolute left-[12px] top-[12px] flex items-center pl-3 pointer-events-none">
                </div>

                <input type="text"
                    class="bg-[#F5F5F5] rounded-lg block w-full px-11 py-3.5 text-gray-900 text-xs font-medium"
                    placeholder="Search for products" required>

                <button type="button"
                    class="bg-[position:0px_-88px] bs-main-sprite w-[24px] h-[22px] absolute top-[12px] right-[12px] flex items-center pr-3">
                </button>
            </div>
        </form>

        {{-- Will remove it. --}}
        @auth('customer')
            <form id="customerLogout" action="{{ route('shop.customer.session.destroy') }}" method="POST">
                @csrf

                @method('DELETE')
            </form>

            <a
                class="border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
                href="{{ route('shop.customer.session.destroy') }}"
                onclick="event.preventDefault(); document.getElementById('customerLogout').submit();">
                {{ __('shop::app.header.logout') }}
            </a>
        @endauth

        <span class="bg-[position:-169px_-65px] bs-main-sprite w-[21px] h-[20px] inline-block cursor-pointer"></span>
        <span class="bg-[position:-100px_-138px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
        <span class="bg-[position:-146px_-65px] bs-main-sprite w-[18px] h-[20px] inline-block cursor-pointer"></span>
    </div>
</div>
