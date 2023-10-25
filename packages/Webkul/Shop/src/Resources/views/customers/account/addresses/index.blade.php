<x-shop::layouts.account>
    {{-- Page Title --}}
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.add-address')
    </x-slot>
    
    {{-- Breadcrumbs --}}
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="addresses"></x-shop::breadcrumbs>
    @endSection
    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-[26px] font-medium">
                @lang('shop::app.customers.account.addresses.title')
            </h2>
        </div>

        <a
            href="{{ route('shop.customers.account.addresses.create') }}"
            class="secondary-button flex gap-x-[10px] items-center py-[12px] px-[20px] border-[#E9E9E9] font-normal"
        >
            <span class="icon-location text-[24px]"></span>

            @lang('shop::app.customers.account.addresses.add-address') 
        </a>
    </div>

    @if (! $addresses->isEmpty())
        {{-- Address Information --}}

        {!! view_render_event('bagisto.shop.customers.account.addresses.list.before', ['addresses' => $addresses]) !!}

        <div class="grid grid-cols-2 gap-[20px] mt-[60px] max-1060:grid-cols-[1fr]">
            @foreach ($addresses as $address)
                <div class="p-[20px] border border-[#e5e5e5] rounded-[12px] max-sm:flex-wrap">
                    <div class="flex justify-between items-center">
                        <p class="text-[16px] font-medium">
                            {{ $address->company_name }}
                        </p>

                        <div class="flex gap-[25px] items-center">

                            @if ($address->default_address)
                                <div 
                                    class="block w-max m-0 ml-[0px] mx-auto p-[5px] rounded-[10px] bg-navyBlue text-[12px] text-white font-medium text-center"
                                >
                                    @lang('shop::app.customers.account.addresses.default-address') 
                                </div>
                            @endif

                            {{-- Dropdown Actions --}}
                            <x-shop::dropdown position="bottom-right">
                                <x-slot:toggle>
                                    <button class="icon-more px-[6px] py-[4px] rounded-[6px] text-[24px] text-[#6E6E6E] cursor-pointer transition-all hover:bg-gray-100 hover:text-black focus:bg-gray-100 focus:text-black"></button>
                                </x-slot:toggle>

                                <x-slot:menu>
                                    <x-shop::dropdown.menu.item>
                                        <a href="{{ route('shop.customers.account.addresses.edit', $address->id) }}">
                                            <p class="w-full">
                                                @lang('shop::app.customers.account.addresses.edit')
                                            </p>
                                        </a>    
                                    </x-shop::dropdown.menu.item>

                                    <x-shop::dropdown.menu.item>
                                        <x-shop::form
                                            :action="route('shop.customers.account.addresses.delete', $address->id)"
                                            method="DELETE"
                                            id="addressDelete"
                                        >
                                            <a 
                                                onclick="event.preventDefault(); document.getElementById('addressDelete').submit();"
                                                href="{{ route('shop.customers.account.addresses.delete', $address->id) }}"
                                            >
                                                <p class="w-full">
                                                    @lang('shop::app.customers.account.addresses.delete')
                                                </p>
                                            </a>
                                        </x-shop::form>
                                    </x-shop::dropdown.menu.item>

                                    <x-shop::dropdown.menu.item>
                                        <x-shop::form
                                            :action="route('shop.customers.account.addresses.update.default', $address->id)"
                                            method="PATCH"
                                        >
                                            <button>
                                                @lang('shop::app.customers.account.addresses.set-as-default')
                                            </button>
                                        </x-shop::form>
                                    </x-shop::dropdown.menu.item>
                                </x-slot:menu>
                            </x-shop::dropdown>
                        </div>
                    </div>

                    <p class="text-[#6E6E6E] mt-[25px]">
                        {{ $address->address1 }},

                        @if ($address->address2)
                            {{ $address->address2 }},
                        @endif

                        {{ $address->city }}, 
                        {{ $address->state }}, {{ $address->country }}, 
                        {{ $address->postcode }}
                    </p>
                </div>    
            @endforeach
        </div>

        {!! view_render_event('bagisto.shop.customers.account.addresses.list.after', ['addresses' => $addresses]) !!}

    @else
        {{-- Address Empty Page --}}
        <div class="grid items-center justify-items-center place-content-center w-[100%] m-auto h-[476px] text-center">
            <img 
                class="" 
                src="{{ bagisto_asset('images/no-address.png') }}" 
                alt="" 
                title=""
            >
            
            <p class="text-[20px]">
                @lang('shop::app.customers.account.addresses.empty-address')
            </p>
        </div>    
    @endif
</x-shop::layouts.account>
