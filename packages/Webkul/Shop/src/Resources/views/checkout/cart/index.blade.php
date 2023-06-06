<x-shop::layouts>
    <div class="flex-auto">
        <div class="container px-[60px] max-lg:px-[30px]">
            <!-- Breadcrumb -->
            <div class="flex justify-start mt-[30px] max-lg:hidden">
                <div class="flex gap-x-[14px] items-center">
                    <p class="flex items-center gap-x-[14px] text-[16px] font-medium">
                        @lang('shop::app.checkout.cart.home') 

                        <span class="icon-arrow-right text-[24px]"></span>
                    </p>

                    <p class="text-[#7D7D7D] text-[12px] flex items-center gap-x-[16px] font-medium  after:content[' '] after:bg-[position:-7px_-41px] after:bs-main-sprite after:w-[9px] after:h-[20px] after:last:hidden">
                        @lang('shop::app.checkout.cart.cart-page')
                    </p>
                </div>
            </div>

            @if ($cart)
                <div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]">
                    <div class="grid gap-y-[25px]">
                        <div class="grid gap-x-[10px] grid-cols-[380px_auto_auto_auto] border-b-[1px] border-[#E9E9E9] pb-[18px]">
                            <div class="text-[14px] font-medium">
                                @lang('shop::app.checkout.cart.product-name')
                            </div>

                            <div class="text-[14px] font-medium">
                                @lang('shop::app.checkout.cart.price')
                            </div>

                            <div class="text-[14px] font-medium">
                                @lang('shop::app.checkout.cart.quantity')
                            </div>

                            <div class="text-[14px] font-medium">
                                @lang('shop::app.checkout.cart.total')
                            </div>
                        </div>

                        @foreach ($cart->items as $item)
                            <div class="grid gap-x-[10px] grid-cols-[380px_auto_auto_auto] border-b-[1px] border-[#E9E9E9] pb-[18px]">
                                <div class="flex gap-x-[20px]">
                                    <div class="">
                                        <img 
                                            class="max-w-[110px] max-h-[110px] rounded-[12px]" 
                                            src="{{ bagisto_asset('images/wishlist-user.png')}}"
                                            alt="" 
                                            title=""
                                        >
                                    </div>

                                    <div class="grid gap-y-[10px]">
                                        <p class="text-[16px] font-medium">
                                            {{ $item->name }}
                                        </p>

                                        <form action="{{ route('shop.checkout.cart.remove', $item->id) }}" method="DELETE">
                                            @csrf
                                            <button>
                                                @lang('shop::app.checkout.cart.remove')
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <p class="text-[18px]">
                                    ${{ $item->price }}
                                </p>

                                <div class="flex gap-[20px] flex-wrap">
                                    <span class="bg-[position:-5px_-69px] bs-main-sprite w-[14px] h-[14px]"></span>

                                    <p>
                                        {{ $item->quantity }}
                                    </p>

                                    <span class="bg-[position:-172px_-44px] bs-main-sprite w-[14px] h-[14px]"></span>
                                </div>

                                <p class="text-[18px] font-semibold">
                                    {{ $item->total }}
                                </p>
                            </div>
                        @endforeach
        
                        <div class="flex flex-wrap gap-[30px] justify-end">
                            <div class="bs-secondary-button rounded-[18px]">
                                <a href="{{ route('shop.home.index') }}">
                                    @lang('shop::app.checkout.cart.continue-shopping')
                                </a>
                            </div>

                            <div class="bs-secondary-button rounded-[18px]">
                                @lang('shop::app.checkout.cart.update-cart')
                            </div>
                        </div>
                    </div>

                    <div class="w-[418px] max-w-full">
                        <p class="text-[26px] font-medium">
                            @lang('shop::app.checkout.cart.cart-summary')
                        </p>

                        <div class="grid gap-[15px] mt-[25px]">
                            <div class="flex text-right justify-between">
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.subtotal')
                                </p>

                                <p class="text-[16px] font-medium">
                                    ${{ $cart->sub_total }}
                                </p>
                            </div>

                            <div class="flex text-right justify-between">
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.tax') 0 %
                                </p>

                                <p class="text-[16px] font-medium">
                                    ${{ $cart->tax_total }}
                                </p>
                            </div>

                            <div class="flex text-right justify-between">
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.coupon-discount')
                                </p>

                                <p class="text-[16px] font-medium">
                                    @if (! $cart->discount_amount)
                                        @lang('shop::app.checkout.cart.apply-coupon')
                                    @else
                                        ${{ $cart->discount_amount }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex text-right justify-between">
                                <p class="text-[16px]">
                                    @lang('shop::app.checkout.cart.grand-total')
                                </p>

                                <p class="text-[26px] font-medium">
                                    ${{ $cart->grand_total }}
                                </p>
                            </div>

                            <div class="block place-self-end bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer mt-[15px]">
                                @lang('shop::app.checkout.cart.proceed-to-checkout')
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-[1fr_auto] gap-[30px] mt-[30px]">
                    <h1>Don't Have product in your cart</h1>
                </div>
            @endif
        </div>            
    </div>
</x-shop::layouts>