<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.index.add-address')
    </x-slot>
    
    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="addresses" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Back Button -->
                <a
                    class="grid md:hidden"
                    href="{{ route('shop.customers.account.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
                </a>
    
                <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                    @lang('shop::app.customers.account.addresses.index.title')
                </h2>
            </div>

            <a
                href="{{ route('shop.customers.account.addresses.create') }}"
                class="secondary-button border-zinc-200 px-5 py-3 font-normal max-md:rounded-lg max-md:py-2 max-sm:py-1.5 max-sm:text-sm"
            >
                @lang('shop::app.customers.account.addresses.index.add-address') 
            </a>
        </div>

        @if (! $addresses->isEmpty())
            <!-- Address Information -->

            {!! view_render_event('bagisto.shop.customers.account.addresses.list.before', ['addresses' => $addresses]) !!}

            <div class="mt-[60px] grid grid-cols-2 gap-5 max-1060:grid-cols-[1fr] max-md:mt-5">
                @foreach ($addresses as $address)
                    <div class="rounded-xl border border-zinc-200 p-5 max-md:flex-wrap">
                        <div class="flex justify-between">
                            <p class="text-base font-medium">
                                {{ $address->first_name }} {{ $address->last_name }}

                                @if ($address->company_name)
                                    ({{ $address->company_name }})
                                @endif
                            </p>

                            <div class="flex gap-4 max-sm:gap-2.5">
                                @if ($address->default_address)
                                    <div class="label-pending block h-fit w-max px-2.5 py-1 max-md:px-1.5">
                                        @lang('shop::app.customers.account.addresses.index.default-address') 
                                    </div>
                                @endif

                                <!-- Dropdown Actions -->
                                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                                    <x-slot:toggle>
                                        <button 
                                            class="icon-more cursor-pointer rounded-md px-1.5 py-1 text-2xl text-zinc-500 transition-all hover:bg-gray-100 hover:text-black focus:bg-gray-100 focus:text-black max-md:p-0" 
                                            aria-label="More Options"
                                        >
                                        </button>
                                    </x-slot>

                                    <x-slot:menu class="!py-1 max-sm:!py-0">
                                        <x-shop::dropdown.menu.item>
                                            <a href="{{ route('shop.customers.account.addresses.edit', $address->id) }}">
                                                <p class="w-full">
                                                    @lang('shop::app.customers.account.addresses.index.edit')
                                                </p>
                                            </a>    
                                        </x-shop::dropdown.menu.item>

                                        <x-shop::dropdown.menu.item>
                                            <form
                                                method="POST"
                                                ref="addressDelete"
                                                action="{{ route('shop.customers.account.addresses.delete', $address->id) }}"
                                            >
                                                @method('DELETE')
                                                @csrf
                                            </form>

                                            <a 
                                                href="javascript:void(0);"                                                
                                                @click="$emitter.emit('open-confirm-modal', {
                                                    agree: () => {
                                                        $refs['addressDelete'].submit()
                                                    }
                                                })"
                                            >
                                                <p class="w-full">
                                                    @lang('shop::app.customers.account.addresses.index.delete')
                                                </p>
                                            </a>
                                        </x-shop::dropdown.menu.item>

                                        @if (! $address->default_address)
                                            <x-shop::dropdown.menu.item>
                                                <form
                                                    method="POST"
                                                    ref="setAsDefault"
                                                    action="{{ route('shop.customers.account.addresses.update.default', $address->id) }}"
                                                >
                                                    @method('PATCH')
                                                    @csrf

                                                </form>

                                                <a 
                                                    href="javascript:void(0);"                                                
                                                    @click="$emitter.emit('open-confirm-modal', {
                                                        agree: () => {
                                                            $refs['setAsDefault'].submit()
                                                        }
                                                    })"
                                                >
                                                    <button>
                                                        @lang('shop::app.customers.account.addresses.index.set-as-default')
                                                    </button>
                                                </a>
                                            </x-shop::dropdown.menu.item>
                                        @endif
                                    </x-slot>
                                </x-shop::dropdown>
                            </div>
                        </div>

                        <p class="mt-6 text-zinc-500 max-md:mt-5 max-md:text-sm">
                            {{ $address->address }},

                            {{ $address->city }}, 
                            {{ $address->state }}, {{ $address->country }}, 
                            {{ $address->postcode }}
                        </p>
                    </div>    
                @endforeach
            </div>

            {!! view_render_event('bagisto.shop.customers.account.addresses.list.after', ['addresses' => $addresses]) !!}

        @else
            <!-- Address Empty Page -->
            <div class="m-auto grid w-full place-content-center items-center justify-items-center py-32 text-center">
                <img 
                    class="max-md:h-[100px] max-md:w-[100px]"
                    src="{{ bagisto_asset('images/no-address.png') }}" 
                    alt="Empty Address" 
                    title=""
                >
                
                <p
                    class="text-xl max-md:text-sm"
                    role="heading"
                >
                    @lang('shop::app.customers.account.addresses.index.empty-address')
                </p>
            </div>    
        @endif
    </div>
</x-shop::layouts.account>
