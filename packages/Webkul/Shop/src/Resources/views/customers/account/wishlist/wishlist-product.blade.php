<div class="flex gap-[65px] p-[25px] items-center border-b-[1px] border-[#E9E9E9]">
    <div class="flex gap-x-[20px]">

        @php
            $image = $item->product->getTypeInstance()->getBaseImage($item);
        @endphp

        <div>
            <a href="{{ route('shop.productOrCategory.index', $item->product->url_key) }}">
                <img 
                    class="max-w-[110px] max-h-[110px] rounded-[12px]" 
                    src="{{ $image['small_image_url'] }}" 
                    alt=""
                />
            </a>
        </div>

        <div class="grid gap-y-[10px]">
            <p class="text-[16px]">
                {{ $item->product->name }}
            </p>

            <p class="text-[16px]">
                @lang('shop::app.customers.account.wishlist.color') 
                {{ $item->product->color }}
            </p>

            <x-shop::form
                method="DELETE"
                id="wishlist-{{ $item->id }}" 
                action="{{ route('shop.customers.account.wishlist.destroy', $item->id) }}" 
            >
            </x-shop::form>

            @auth('customer')
                <a 
                    class="text-[16px] text-[#4D7EA8]" 
                    href="javascript:void(0);"
                    onclick="document.getElementById('wishlist-{{ $item->id }}').submit();"
                >
                    @lang('shop::app.customers.account.wishlist.remove')
                </a>
            @endauth
        </div>
    </div>

    <p class="text-[18px]">
        {{ $item->product->price }}
    </p>

    <div class="flex gap-x-[25px] border rounded-[54px] border-navyBlue py-[10px] px-[20px] items-center">

        <span class="bg-[position:-5px_-69px] bs-main-sprite w-[14px] h-[14px]"></span>

        <p>1</p>

        <span class="bg-[position:-172px_-44px] bs-main-sprite w-[14px] h-[14px]"></span>
    </div>

    <x-shop::form
        :action="route('shop.customers.account.wishlist.move_to_cart', $item->id)"
    >
        <button class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[54px] text-center">
            @lang('shop::app.customers.account.wishlist.move-to-cart')
        </button>
    </x-shop::form>
</div>
           
