@php
    $admin = auth()->guard('admin')->user();

    $orderStatusMessages = [
        'pending'         => trans('admin::app.notification.order-status-messages.pending'),
        'canceled'        => trans('admin::app.notification.order-status-messages.canceled'),
        'closed'          => trans('admin::app.notification.order-status-messages.closed'),
        'completed'       => trans('admin::app.notification.order-status-messages.completed'),
        'processing'      => trans('admin::app.notification.order-status-messages.processing'),
        'pending_payment' => trans('admin::app.notification.order-status-messages.pending_payment')
    ];

    $allLocales = core()->getAllLocales()->pluck('name', 'code');
@endphp

<header class="flex justify-between items-center px-[16px] py-[10px] bg-white border-b-[1px] border-gray-300 sticky top-0 z-10">
    <div class="flex gap-[6px] items-center">
        {{-- Hamburger Menu --}}
        <i
            class="hidden icon-menu text-[24px] p-[6px] max-lg:block cursor-pointer"
            @click="$refs.sidebarMenuDrawer.open()"
        ></i>

        {{-- Logo --}}
        <a
            href="{{ route('admin.dashboard.index') }}" 
            class="place-self-start -mt-[4px]"            
        >
            @if (core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode()))
                <img src="{{ Storage::url(core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode())) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
            @else
                <img src="{{ bagisto_asset('images/logo.png') }}">
            @endif
        </a>

        {{-- Search Bar --}}
        <form class="flex items-center max-w-[445px] ml-[10px]">
            <label 
                for="organic-search" 
                class="sr-only"
            >
                @lang('admin::app.components.layouts.header.search')
            </label>

            <div class="relative w-full">
                <div class="icon-search text-[22px] absolute left-[12px] top-[6px] flex items-center pointer-events-none"></div>

                <input 
                    type="text" 
                    class="bg-white border border-gray-300 rounded-lg block w-full px-[40px] py-[5px] leading-6 text-gray-400 transition-all hover:border-gray-400"
                    placeholder="@lang('admin::app.components.layouts.header.mega-search')" 
                    required=""
                >

                <button 
                    type="button" 
                    class="absolute icon-camera top-[12px] right-[12px] flex items-center pr-[12px] text-[22px]"
                >
                </button>
            </div>
        </form>
    </div>

    <div class="flex gap-[10px] items-center">
        <a 
            href="{{ route('shop.home.index') }}" 
            target="_blank"
            class="mt-[6px]"
        >
            <span 
                class="icon-store p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100"
                title="@lang('admin::app.components.layouts.header.visit-shop')"
            >
            </span>
        </a>

        <x-admin::notification
            notif-title="{{ __('admin::app.notification.notification-title', ['read' => 0]) }}"
            :get-notification-url="route('admin.notification.get_notification')"
            :view-all="route('admin.notification.index')"
            order-view-url="{{ \URL::to('/') }}/{{ config('app.admin_url')}}/viewed-notifications/"
            :pusher-key="env('PUSHER_APP_KEY')"
            :pusher-cluster="env('PUSHER_APP_CLUSTER')"
            title="{{ __('admin::app.notification.title-plural') }}"
            view-all-title="{{ __('admin::app.notification.view-all') }}"
            :get-read-all-url="route('admin.notification.read_all')"
            :order-status-messages="json_encode($orderStatusMessages)"
            read-all-title="{{ __('admin::app.notification.read-all') }}"
            :locale-code="core()->getCurrentLocale()->code"
        >
        </x-admin::notification>

        {{-- Admin profile --}}
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <x-slot:toggle>
                @if ($admin->image)
                    <div class="profile-info-icon">
                        <img
                            src="{{ $admin->image_url }}"
                            class="max-w-[36px] max-h-[36px] rounded-[6px]"
                        />
                    </div>
                @else
                    <div class="profile-info-icon">
                        <span class="px-[8px] py-[6px] bg-blue-400 rounded-full text-white font-semibold cursor-pointer leading-6">
                            {{ substr($admin->name, 0, 1) }}
                        </span>
                    </div>
                @endif
            </x-slot:toggle>

            {{-- Admin Dropdown --}}
            <x-slot:content class="!p-[0px]">
                <div class="grid gap-[10px] px-[20px] py-[10px] border border-b-gray-300">
                    {{-- Version --}}
                    <p class="text-gray-400">
                        @lang('admin::app.components.layouts.header.app-version', ['version' => 'v' . core()->version()])
                    </p>
                </div>

                <div class="grid gap-[4px] pb-[10px]">
                    <a
                        class="px-5 py-2 text-[16px] text-gray-800 hover:bg-gray-100 cursor-pointer"
                        href="{{ route('admin.account.edit') }}"
                    >
                        @lang('admin::app.components.layouts.header.my-account')
                    </a>

                    {{--Admin logout--}}
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <a
                        class="px-5 py-2 text-[16px] text-gray-800 hover:bg-gray-100 cursor-pointer"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        @lang('admin::app.components.layouts.header.logout')
                    </a>
                </div>
            </x-slot:content>
        </x-admin::dropdown>
    </div>
</header>

<!-- Menu Sidebar Drawer -->
<x-admin::drawer
    position="left"
    width="270px"
    ref="sidebarMenuDrawer"
>
    <!-- Drawer Header -->
    <x-slot:header>
        <div class="flex justify-between items-center">
            @if (core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode()))
                <img src="{{ Storage::url(core()->getConfigData('general.design.admin_logo.logo_image', core()->getCurrentChannelCode())) }}" alt="{{ config('app.name') }}" style="height: 40px; width: 110px;"/>
            @else
                <img src="{{ bagisto_asset('images/logo.png') }}">
            @endif
        </div>
    </x-slot:header>

    <!-- Drawer Content -->
    <x-slot:content class="p-[16px]">
        <div class="h-[calc(100vh-100px)] overflow-auto journal-scroll">
            <nav class="grid gap-[7px] w-full">
                {{-- Navigation Menu --}}
                @foreach ($menu->items as $menuItem)
                    <div class="relative group/item">
                        <a
                            href="{{ $menuItem['url'] }}"
                            class="flex gap-[10px] p-[6px] items-center cursor-pointer {{ $menu->getActive($menuItem) == 'active' ? 'bg-blue-600 rounded-[8px]' : ' hover:bg-gray-100' }} peer"
                        >
                            <span class="{{ $menuItem['icon'] }} text-[24px] {{ $menu->getActive($menuItem) ? 'text-white' : ''}}"></span>
                            
                            <p class="text-gray-600 font-semibold whitespace-nowrap {{ $menu->getActive($menuItem) ? 'text-white' : ''}}">
                                @lang($menuItem['name'])
                            </p>
                        </a>

                        @if (count($menuItem['children']))
                            <div class="{{ $menu->getActive($menuItem) ? ' !grid bg-gray-100' : '' }} hidden min-w-[180px] pl-[40px] pb-[7px] rounded-b-[8px] z-[100]">
                                @foreach ($menuItem['children'] as $subMenuItem)
                                    <a
                                        href="{{ $subMenuItem['url'] }}"
                                        class="text-[14px] text-{{ $menu->getActive($subMenuItem) ? 'blue':'gray' }}-600 whitespace-nowrap py-[4px]"
                                    >
                                        @lang($subMenuItem['name'])
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
        </div>
    </x-slot:content>
</x-admin::drawer>