<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.add-address')
    </x-slot>
    
    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="addresses" />
    @endSection
    <div class="flex items-center justify-between">
        <div class="">
            <h2 class="text-2xl font-medium">
                @lang('shop::app.customers.account.addresses.title')
            </h2>
        </div>

        <a
            href="{{ route('shop.customers.account.addresses.create') }}"
            class="secondary-button flex items-center gap-x-2.5 border-[#E9E9E9] px-5 py-3 font-normal"
        >
            <span class="icon-location text-2xl"></span>

            @lang('shop::app.customers.account.addresses.add-address') 
        </a>
    </div>

    @if (! $addresses->isEmpty())
        <!-- Address Information -->

        {!! view_render_event('bagisto.shop.customers.account.addresses.list.before', ['addresses' => $addresses]) !!}

        <div class="mt-[60px] grid grid-cols-2 gap-5 max-1060:grid-cols-[1fr]">
            @foreach ($addresses as $address)
                <div class="rounded-xl border border-[#e5e5e5] p-5 max-sm:flex-wrap">
                    <div class="flex items-center justify-between">
                        <p class="text-base font-medium">
                            {{ $address->first_name }} {{ $address->last_name }}

                            @if ($address->company_name)
                                ({{ $address->company_name }})
                            @endif
                        </p>

                        <div class="flex items-center gap-4">

                            @if ($address->default_address)
                                <div class="label-pending block w-max px-1.5 py-1">
                                    @lang('shop::app.customers.account.addresses.default-address') 
                                </div>
                            @endif

                            <!-- Dropdown Actions -->
                            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                                <x-slot:toggle>
                                    <button 
                                        class="icon-more cursor-pointer rounded-md px-1.5 py-1 text-2xl text-[#6E6E6E] transition-all hover:bg-gray-100 hover:text-black focus:bg-gray-100 focus:text-black" 
                                        aria-label="More Options"
                                    >
                                    </button>
                                </x-slot>

                                <x-slot:menu>
                                    <x-shop::dropdown.menu.item>
                                        <a href="{{ route('shop.customers.account.addresses.edit', $address->id) }}">
                                            <p class="w-full">
                                                @lang('shop::app.customers.account.addresses.edit')
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
                                                @lang('shop::app.customers.account.addresses.delete')
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
                                                    @lang('shop::app.customers.account.addresses.set-as-default')
                                                </button>
                                            </a>
                                        </x-shop::dropdown.menu.item>
                                    @endif
                                </x-slot>
                            </x-shop::dropdown>
                        </div>
                    </div>

                    <p class="mt-6 text-[#6E6E6E]">
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
        <div class="m-auto grid h-[476px] w-full place-content-center items-center justify-items-center text-center">
            <img 
                class="" src="{{ bagisto_asset('images/no-address.png') }}" 
                alt="" 
                title=""
            >
            
            <p class="text-xl">
                @lang('shop::app.customers.account.addresses.empty-address')
            </p>
        </div>    
    @endif
</x-shop::layouts.account>
