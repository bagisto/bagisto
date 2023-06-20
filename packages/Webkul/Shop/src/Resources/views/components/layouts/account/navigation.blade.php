{{--
    - This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.

    - Need to check the view composer capability for the component.
--}}
@php
    $menu = \Webkul\Core\Tree::create();

    foreach (config('menu.customer') as $item) {
        $menu->add($item, 'menu');
    }

    $menu->items = core()->sortItems($menu->items);
@endphp

<div
    class="grid grid-cols-[1fr] panel-side max-w-[380px] gap-[30px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll min-w-[342px] max-xl:min-w-[270px] max-md:hidden"
>
    <div
        class="grid grid-cols-[auto_1fr] gap-[15px] items-center px-[20px] py-[25px] border border-[#E9E9E9] rounded-[12px]"
    >
        <div class="">
            <img
                class="bg-navyBlue w-[60px] h-[60px] rounded-full"
                src="{{ bagisto_asset('images/user-placeholder.png') }}"
                alt=""
                title=""
            >
        </div>

        <div class="flex flex-col justify-between gap-[10px]">
            <p class=" text-[25px] font-mediums">Hello! {{ auth()->guard('customer')->user()->first_name }}</p>

            <p class=" text-[#7D7D7D] ">{{ auth()->guard('customer')->user()->email }}</p>
        </div>
    </div>

    <div
        class="grid border border-t-0 border-r-[1px] border-l-[1px] border-b-[1px] border-[#E9E9E9] rounded-[6px]"
    >
        @foreach ($menu->items as $menuItem)
            @foreach ($menuItem['children'] as $subMenuItem)
                <a href="{{ $subMenuItem['url'] }}">
                    <div
                        class="flex px-[25px] py-[20px] justify-between border-t-[1px] border-[#E9E9E9] hover:bg-[#f3f4f682] cursor-pointer {{ request()->routeIs($subMenuItem['route']) ? 'bg-[#E9E9E9] rounded-t-[6px]' : '' }}"
                    >
                        <p class="flex gap-x-[15px] text-[18px] font-medium items-center">
                            <span class="{{ $subMenuItem['icon'] }} bg-[position:-7px_-41px] text-[22px]"></span>

                            @lang($subMenuItem['name'])
                        </p>
                        <span class="inline-block bg-[position:-7px_-41px] bs-main-sprite w-[9px] h-[20px]"></span>
                    </div>
                </a>
            @endforeach
        @endforeach
    </div>
</div>
