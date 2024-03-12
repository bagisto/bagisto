<!--
    - This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.

    - Need to check the view composer capability for the component.
-->
@php
    $menu = \Webkul\Core\Tree::create();

    foreach (config('menu.customer') as $item) {
        $menu->add($item, 'menu');
    }

    $menu->items = core()->sortItems($menu->items);

    $customer = auth()->guard('customer')->user();
@endphp

<div class="panel-side grid grid-cols-[1fr] gap-8 max-w-[380px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll min-w-[342px] max-xl:min-w-[270px] max-md:max-w-full">
    <!-- Account Profile Hero Section -->
    <div class="grid grid-cols-[auto_1fr] gap-4 items-center px-5 py-[25px] border border-[#E9E9E9] rounded-xl">
        <div class="">
            <img
                src="{{ $customer->image_url ??  bagisto_asset('images/user-placeholder.png') }}"
                class="w-[60px] h-[60px] rounded-full"
                alt="Profile Image"
            >
        </div>

        <div class="flex flex-col justify-between">
            <p class="text-2xl font-mediums">Hello! {{ $customer->first_name }}</p>

            <p class="text-[#6E6E6E] ">{{ $customer->email }}</p>
        </div>
    </div>

    <!-- Account Navigation Menus -->
    @foreach ($menu->items as $menuItem)
        <div class="max-md:border max-md:border-t-0 max-md:border-r max-md:border-l-[1px] max-md:border-b max-md:border-[#E9E9E9]   max-md:rounded-md">
            <v-account-navigation>
                <!-- Account Navigation Toggler -->
                <div class="max-md:flex max-md:gap-x-4 max-md:justify-between max-md:items-center pb-5 max-md:bg-gray-100 max-md:px-6 max-md:py-5 max-md:rounded-tl-[6px] max-md:rounded-tr-[6px] accordian-toggle md:pointer-events-none select-none">
                    <p class="text-xl md:font-medium">@lang($menuItem['name'])</p>

                    <span class="icon-arrow-right text-2xl md:hidden"></span>
                </div>

                <!-- Account Navigation Content -->
                <div class="grid border border-t-0 border-r border-l-[1px] border-b border-[#E9E9E9] rounded-md max-md:hidden max-md:border-none accordian-content">
                    @if (! (bool) core()->getConfigData('general.content.shop.wishlist_option'))
                        @php
                            unset($menuItem['children']['wishlist']);
                        @endphp
                    @endif

                    @foreach ($menuItem['children'] as $subMenuItem)
                        <a href="{{ $subMenuItem['url'] }}">
                            <div class="flex justify-between px-6 py-5 border-t border-[#E9E9E9] hover:bg-[#f3f4f682] cursor-pointer {{ request()->routeIs($subMenuItem['route']) ? 'bg-gray-100' : '' }}">
                                <p class="flex gap-x-4 items-center text-lg font-medium">
                                    <span class="{{ $subMenuItem['icon'] }}  text-2xl"></span>

                                    @lang($subMenuItem['name'])
                                </p>

                                <span class="icon-arrow-right rtl:icon-arrow-left text-2xl max-md:hidden"></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </v-account-navigation>
        </div>
    @endforeach
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-account-navigation-template">
        <div>
            <slot></slot>
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

            mounted() {
                this.$el.querySelector('.accordian-toggle').addEventListener('click', () => {
                    this.toggleAccordion();
                });
            },

            methods: {
                toggleAccordion() {
                    this.isOpen = ! this.isOpen;

                    if (this.isOpen) {
                        this.$el.querySelector('.icon-arrow-right').classList.add('icon-arrow-down');
                        this.$el.querySelector('.icon-arrow-down').classList.remove('icon-arrow-right');

                        this.$el.querySelector('.accordian-content').style.display = "grid";
                    } else {
                        this.$el.querySelector('.icon-arrow-down').classList.add('icon-arrow-right');
                        this.$el.querySelector('.icon-arrow-right').classList.remove('icon-arrow-down');

                        this.$el.querySelector('.accordian-content').style.display = "none";
                    }
                },
            },
        });
      </script>
@endpushOnce