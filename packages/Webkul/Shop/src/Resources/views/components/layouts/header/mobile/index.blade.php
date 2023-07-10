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
                </x-slot:toggle>
                
                <x-slot:header>
                    <div class="flex justify-between p-[20px] items-center">
                        <a 
                            href="{{ route('shop.home.index') }}"
                        >
                            <img src="{{ bagisto_asset('images/logo.png') }}">
                        </a>
                    </div>
                </x-slot:header>

                <x-slot:content>
                    {{-- Mobile category view --}}
                    <v-mobile-category></v-mobile-category>
                </x-slot:content>

                <x-slot:footer></x-slot:footer>
            </x-shop::drawer>

            <a href="{{ route('shop.home.index') }}">
                <img
                    class="h-[40px]"
                    src="{{ bagisto_asset('images/logo.png') }}"
                >
            </a>
        </div>

        <div>
            <div class="flex  items-center gap-x-[20px]">
                <a href="{{ route('shop.compare.index') }}">
                    <span class="icon-compare text-[24px] inline-block cursor-pointer"></span>
                </a>
                
                @include('shop::checkout.cart.mini-cart')

                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                    <x-slot:toggle>
                        <span class="icon-users text-[24px] inline-block cursor-pointer"></span>
                    </x-slot:toggle>
    
                    {{-- Guest Dropdown --}}
                    @guest('customer')
                        <x-slot:content>
                            <div class="grid gap-[10px]">
                                <p class="text-[20px] font-dmserif">
                                    @lang('shop::app.components.layouts.header.welcome-guest')
                                </p>
    
                                <p class="text-[14px]">
                                    @lang('shop::app.components.layouts.header.dropdown-text')
                                </p>
                            </div>
    
                            <p class="py-2px border border-[#E9E9E9] mt-[12px] w-full"></p>
    
                            <div class="flex gap-[16px] mt-[25px]">
                                <a
                                    href="{{ route('shop.customer.session.create') }}"
                                    class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[15px] px-[29px] rounded-[18px] text-center cursor-pointer"
                                >
                                    @lang('shop::app.components.layouts.header.sign-in')
                                </a>
    
                                <a
                                    href="{{ route('shop.customers.register.index') }}"
                                    class="m-0 ml-[0px] block mx-auto bg-white border-2 border-navyBlue text-navyBlue text-base w-max font-medium py-[14px] px-[29px] rounded-[18px] text-center cursor-pointer"
                                >
                                    @lang('shop::app.components.layouts.header.sign-up')
                                </a>
                            </div>
                        </x-slot:content>
                    @endguest
    
                    {{-- Customers Dropdown --}}
                    @auth('customer')
                        <x-slot:content class="!p-[0px]">
                            <div class="grid gap-[10px] p-[20px] pb-0">
                                <p class="text-[20px] font-dmserif">
                                    @lang('shop::app.components.layouts.header.welcome')’
                                    {{ auth()->guard('customer')->user()->first_name }}
                                </p>
    
                                <p class="text-[14px]">
                                    @lang('shop::app.components.layouts.header.dropdown-text')
                                </p>
                            </div>
    
                            <p class="py-2px border border-[#E9E9E9] mt-[12px] w-full"></p>
    
                            <div class="grid gap-[4px] mt-[10px] pb-[10px]">
                                <a
                                    class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                                    href="{{ route('shop.customers.account.profile.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.profile')
                                </a>
    
                                <a
                                    class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                                    href="{{ route('shop.customers.account.orders.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.orders')
                                </a>
    
                                @if ($showWishlist)
                                    <a
                                        class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                                        href="{{ route('shop.customers.account.wishlist.index') }}"
                                    >
                                        @lang('shop::app.components.layouts.header.wishlist')
                                    </a>
                                @endif
    
                                {{--Customers logout--}}
                                @auth('customer')
                                    <x-shop::form
                                        method="DELETE"
                                        action="{{ route('shop.customer.session.destroy') }}"
                                        id="customerLogout"
                                    >
                                    </x-shop::form>
    
                                    <a
                                        class="text-[16px] px-5 py-2 cursor-pointer hover:bg-gray-100"
                                        href="{{ route('shop.customer.session.destroy') }}"
                                        onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                    >
                                        @lang('shop::app.components.layouts.header.logout')
                                    </a>
                                @endauth
                            </div>
                        </x-slot:content>
                    @endauth
                </x-shop::dropdown>
            </div>
        </div>
    </div>

    <form action="{{ route('shop.search.index') }}" class="flex items-center w-full">
        <label for="organic-search" class="sr-only">Search</label>

        <div class="relative w-full">
            <div
                class="icon-search text-[25px] absolute left-[12px] top-[12px] flex items-center pointer-events-none">
            </div>

            <input 
                type="text"
                class=" border border-['#E3E3E3'] rounded-xl block w-full px-11 py-3.5 text-gray-900 text-xs font-medium"
                name="query"
                value="{{ request('query') }}"
                placeholder="Search for products" 
                required
            >

            <button type="button"
                class="icon-camera text-[22px] absolute top-[12px] right-[12px] flex items-center pr-3">
            </button>
        </div>
    </form>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mobile-category-template">
        <div>
            <template v-for="(category) in categories">
                <div class="flex justify-between items-center border border-b-[1px] border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                    <a
                        :href="category.url"
                        class="flex items-center justify-between pb-[20px] mt-[20px]"
                        v-text="category.name"
                    >
                    </a>

                    <span
                        class="text-[24px] cursor-pointer"
                        :class="{'icon-arrow-down': category.isOpen, 'icon-arrow-right': ! category.isOpen}"
                        @click="toggle(category)"
                    >
                    </span>
                </div>

                <div 
                    class="grid gap-[8px]"
                    v-if="category.isOpen"
                >
                    <ul v-if="category.children.length">
                        <li v-for="secondLevelCategory in category.children">
                            <div class="flex justify-between items-center border border-b-[1px] border-l-0 border-r-0 border-t-0 border-[#f3f3f5] ml-3">
                                <a
                                    :href="secondLevelCategory.url"
                                    class="flex items-center justify-between pb-[20px] mt-[20px]"
                                    v-text="secondLevelCategory.name"
                                >
                                </a>

                                <span
                                    class="text-[24px] cursor-pointer"
                                    :class="{'icon-arrow-down': secondLevelCategory.category_show, 'icon-arrow-right': ! secondLevelCategory.category_show}"
                                    @click="secondLevelCategory.category_show = ! secondLevelCategory.category_show"
                                >
                                </span>
                            </div>

                            <div v-if="secondLevelCategory.category_show">
                                <ul v-if="secondLevelCategory.children.length">
                                    <li v-for="thirdLevelCategory in secondLevelCategory.children">
                                        <div class="flex justify-between items-center border border-b-[1px] border-l-0 border-r-0 border-t-0 border-[#f3f3f5] ml-3">
                                            <a
                                                :href="thirdLevelCategory.url"
                                                class="flex items-center justify-between pb-[20px] mt-[20px] ml-3"
                                                v-text="thirdLevelCategory.name"
                                            >
                                            </a>
                                        </div>
                                    </li>
                                </ul>

                                <span 
                                    class="ml-2"
                                    v-else
                                >
                                    @lang('No category found.')
                                </span>
                            </div>
                        </li>
                    </ul>

                    <span 
                        class="ml-2"
                        v-else
                    >
                        @lang('No category found.')
                    </span>
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            data() {
                return  {
                    categories: @json($categories),
                }
            }, 

            methods: {
                toggle(selectedCategory) {
                    this.categories = this.categories.map((category) => ({
                        ...category,
                        isOpen: category.id === selectedCategory.id ? ! category.isOpen : false,
                    }));
                },
            },
        });
    </script>
@endPushOnce
