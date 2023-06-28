<x-shop::layouts.account>
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
            class="flex items-center gap-x-[10px] border border-[#E9E9E9] rounded-[12px] py-[12px] px-[20px] cursor-pointer"
        >
            @lang('shop::app.customers.account.addresses.add-address') 
        </a>
    </div>

    @if (! $addresses->isEmpty())
        <div class="grid mt-[60px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr]">
            @foreach ($addresses as $address)
                <div class="border border-[#e5e5e5] rounded-[12px] p-[20px] max-sm:flex-wrap">
                    <div class="flex justify-between items-center">
                        <p class="text-[16px] font-medium">
                            {{ $address->company_name }}
                        </p>

                        <div class="flex gap-[25px] items-center">

                            @if ($address->default_address)
                                <div 
                                    class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white w-max font-medium p-[5px] rounded-[10px] text-center text-[12px]"
                                >
                                    @lang('shop::app.customers.account.addresses.default-address') 
                                </div>
                            @endif

                            <x-shop::dropdown>
                                <x-slot:toggle>
                                    <span class="icon-more text-[24px] text-[#7D7D7D] cursor-pointer"></span>
                                </x-slot:toggle>

                                <x-slot:menu>
                                    <x-shop::dropdown.menu.item>
                                        <a href="{{ route('shop.customers.account.addresses.edit', $address->id) }}">
                                            @lang('shop::app.customers.account.addresses.edit')
                                        </a>    
                                    </x-shop::dropdown.menu.item>

                                    <x-shop::dropdown.menu.item>
                                        <x-shop::form
                                            :action="route('shop.customers.account.addresses.delete', $address->id)"
                                            method="DELETE"
                                        >
                                            <button>
                                                @lang('shop::app.customers.account.addresses.delete')
                                            </button>
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

                    <p class="text-[#7D7D7D] mt-[25px]">
                        {{ $address->address1 }} {{ $address->address2 }},
                        {{ $address->city }}, 
                        {{ $address->state }}, {{ $address->country }}, 
                        {{ $address->postcode }}
                    </p>
                </div>    
            @endforeach
        </div>
    @else
        <div class="grid items-center justify-items-center w-max m-auto h-[476px] place-content-center">
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
