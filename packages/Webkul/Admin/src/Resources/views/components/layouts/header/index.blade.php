@php $admin = auth()->guard('admin')->user(); @endphp

<header class="flex justify-between items-center px-[16px] py-[10px] bg-white border-b-[1px] border-gray-300 sticky top-0 z-10">
    <div class="flex gap-[16px]">
        <a
            href="{{ route('admin.dashboard.index') }}" 
            class="place-self-start -mt-[4px]"            
        >
            <img src="{{ bagisto_asset('images/logo.png') }}">
        </a>

        <form class="flex items-center max-w-[445px]">
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

    <div class="flex gap-[4px] items-center">
        <a 
            href="{{ route('shop.home.index') }}" 
            target="_blank"
        >
            <span 
                class="icon-store p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100"
                title="@lang('admin::app.components.layouts.header.visit-shop')"
            >
            </span>
        </a>

        <span 
            class="icon-notification p-[6px] bg-gray-100 rounded-[6px] text-[24px] text-red cursor-pointer transition-all" 
            title="@lang('admin::app.components.layouts.header.notifications')"
        >
        </span>

        @if ($admin->image)
            <div class="profile-info-icon">
                <img src="{{ $admin->image_url }}"/>
            </div>
        @else
            <div class="profile-info-icon">
                <span class="px-[8px] py-[6px] bg-blue-400 rounded-full text-white font-semibold cursor-pointer leading-6">
                    {{ substr($admin->name, 0, 1) }}
                </span>
            </div>
        @endif
    </div>
</header>