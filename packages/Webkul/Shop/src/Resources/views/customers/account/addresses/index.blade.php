<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.index.add-address')
    </x-slot>
    
    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="addresses" />
    @endSection
    <div class="flex items-center justify-between">
        <div class="">
            <h2 class="text-2xl font-medium max-sm:text-xl">
                @lang('shop::app.customers.account.addresses.index.title')
            </h2>
        </div>

        <a
            href="{{ route('shop.customers.account.addresses.create') }}"
            class="secondary-button border-[#E9E9E9] px-5 py-3 font-normal max-sm:py-1.5"
        >
            @lang('shop::app.customers.account.addresses.index.add-address') 
        </a>
    </div>

    @if (! $addresses->isEmpty())
        <!-- Address Information -->

        {!! view_render_event('bagisto.shop.customers.account.addresses.list.before', ['addresses' => $addresses]) !!}

        <div class="mt-[60px] grid grid-cols-2 gap-5 max-1060:grid-cols-[1fr] max-sm:mt-[20px]">
            @foreach ($addresses as $address)
                <div class="rounded-xl border border-[#e5e5e5] p-5 max-sm:flex-wrap">
                    <div class="flex justify-between">
                        <p class="text-base font-medium">
                            {{ $address->first_name }} {{ $address->last_name }}

                            @if ($address->company_name)
                                ({{ $address->company_name }})
                            @endif
                        </p>

                        <div class="flex gap-4">

                            @if ($address->default_address)
                                <div class="label-pending block h-fit w-max px-2.5 py-1 max-sm:px-1.5">
                                    @lang('shop::app.customers.account.addresses.index.default-address') 
                                </div>
                            @endif

                            <!-- Dropdown Actions -->
                            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                                <x-slot:toggle>
                                    <button 
                                        class="icon-more cursor-pointer rounded-md px-1.5 py-1 text-2xl text-[#6E6E6E] transition-all hover:bg-gray-100 hover:text-black focus:bg-gray-100 focus:text-black max-sm:p-0" 
                                        aria-label="More Options"
                                    >
                                    </button>
                                </x-slot>

                                <x-slot:menu>
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

                    <p class="mt-6 text-[#6E6E6E] max-sm:mt-5 max-sm:text-sm">
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
                class="max-sm:h-[100px] max-sm:w-[100px]"
                src="{{ bagisto_asset('images/no-address.png') }}" 
                alt="Empty Address" 
                title=""
            >
            
            <p
                class="text-xl max-sm:text-sm"
                role="heading"
            >
                @lang('shop::app.customers.account.addresses.index.empty-address')
            </p>
        </div>    
    @endif
</x-shop::layouts.account>
