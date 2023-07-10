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

    $customer = auth()->guard('customer')->user();
@endphp

<div
    class="grid grid-cols-[1fr] panel-side max-w-[380px] gap-[30px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll min-w-[342px] max-xl:min-w-[270px] max-md:max-w-full"
>
    <div
        class="grid grid-cols-[auto_1fr] gap-[15px] items-center px-[20px] py-[25px] border border-[#E9E9E9] rounded-[12px]"
    >
        <div class="">
            <img
                class="bg-navyBlue w-[60px] h-[60px] rounded-full"
                src="{{ $customer->image_url ??  bagisto_asset('images/user-placeholder.png') }}"
                alt=""
                title=""
            >
        </div>

        <div class="flex flex-col justify-between gap-[10px]">
            <p class=" text-[25px] font-mediums">Hello! {{ $customer->first_name }}</p>

            <p class=" text-[#7D7D7D] ">{{ $customer->email }}</p>
        </div>
    </div>

    <div class="max-md:border max-md:border-t-0 max-md:border-r-[1px] max-md:border-l-[1px] max-md:border-b-[1px] max-md:border-[#E9E9E9] max-md:rounded-[6px]">
        <div class="max-md:hidden">
            @foreach ($menu->items as $menuItem)
                <div class="flex px-[25px] py-[20px] justify-between cursor-pointer">
                    <p class="text-[20px] md:font-medium">
                        @lang($menu->items['account']['name'])
                    </p>
                </div>

                <div class="grid border border-t-0 border-r-[1px] border-l-[1px] border-b-[1px] border-[#E9E9E9] rounded-[6px] max-md:border-none">
                    @foreach ($menuItem['children'] as $subMenuItem)
                        <a href="{{ $subMenuItem['url'] }}">
                            <div
                                class="flex px-[25px] py-[20px] justify-between border-t-[1px] border-[#E9E9E9] hover:bg-[#f3f4f682] cursor-pointer {{ request()->routeIs($subMenuItem['route']) ? 'bg-[#E9E9E9]' : '' }}"
                            >
                                <p class="flex gap-x-[15px] text-[18px] font-medium items-center">
                                    <span class="{{ $subMenuItem['icon'] }} bg-[position:-7px_-41px] text-[22px]"></span>

                                    @lang($subMenuItem['name'])
                                </p>

                                <span class="inline-block bg-[position:-7px_-41px] bs-main-sprite w-[9px] h-[20px]"></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="hidden max-md:block">
            <v-account-navigation>
                <div class="max-md:flex max-md:gap-x-[15px] max-md:justify-between max-md:items-center pb-[20px] max-md:bg-[#F5F5F5] max-md:px-[25px] max-md:py-[20px] max-md:rounded-tl-[6px] max-md:rounded-tr-[6px] cursor-pointer">
                    <p class="text-[20px] md:font-medium">
                        @lang($menu->items['account']['name'])
                    </p>

                    <span class="text-[24px] icon-arrow-down"></span>
                </div>
            </v-account-navigation>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-account-navigation-template">
        <div>
            @foreach ($menu->items as $menuItem)
                <div
                    class="max-md:flex max-md:gap-x-[15px] max-md:justify-between max-md:items-center pb-[20px] max-md:bg-[#F5F5F5] max-md:px-[25px] max-md:py-[20px] max-md:rounded-tl-[6px] max-md:rounded-tr-[6px] cursor-pointer"
                    @click="toggleAccordion"
                >
                    <p class="text-[20px] md:font-medium">
                        @lang($menu->items['account']['name'])
                    </p>

                    <span
                        class="text-[24px]"
                        :class="{'icon-arrow-up': isOpen, 'icon-arrow-down': ! isOpen}"
                    >
                    </span>
                </div>
        
                <div 
                    v-show="isOpen" 
                    class="grid border border-t-0 border-r-[1px] border-l-[1px] border-b-[1px] border-[#E9E9E9] rounded-[6px] max-md:border-none"
                >
                    @foreach ($menuItem['children'] as $subMenuItem)
                        <a href="{{ $subMenuItem['url'] }}">
                            <div
                                class="flex px-[25px] py-[20px] justify-between border-t-[1px] border-[#E9E9E9] hover:bg-[#f3f4f682] cursor-pointer {{ request()->routeIs($subMenuItem['route']) ? 'bg-[#E9E9E9]' : '' }}"
                            >
                                <p class="flex gap-x-[15px] text-[18px] font-medium items-center">
                                    <span class="{{ $subMenuItem['icon'] }} bg-[position:-7px_-41px] text-[22px]"></span>

                                    @lang($subMenuItem['name'])
                                </p>

                                <span class="inline-block bg-[position:-7px_-41px] bs-main-sprite w-[9px] h-[20px]"></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </script>

    <script type="module">
        app.component("v-account-navigation", {
            template: '#v-account-navigation-template',

            data() {
                return {
                    isOpen: false,
                };
            },

            methods: {
                toggleAccordion() {
                    this.isOpen = ! this.isOpen;
                },
            },
        });
      </script>
@endpushOnce