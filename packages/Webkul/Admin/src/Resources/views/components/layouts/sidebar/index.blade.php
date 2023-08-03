@php
    $tree = \Webkul\Core\Tree::create();

    foreach (config('core') as $item) {
        $tree->add($item);
    }
@endphp

<div class="fixed top-[57px] h-full bg-white px-[16px] pt-[8px]  shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)] max-lg:hidden">
    {{-- relative overflow-hidden --}}
    <div class="h-[calc(100vh-100px)] overflow-auto journal-scroll">
        <nav 
            class="w-[222px]"
            :class="isOpen ? 'w-[35px] transition-width duration-200 ease-in-out' : 'transition-width duration-200 ease-in-in'"
        >
            <div>
                {{-- Navigation Menu --}}
                @foreach ($menu->items as $menuItem)
                  <div  class="group relative">
                    <a href="{{ $menuItem['url'] }}" >
                        <div class="flex justify-between p-[6px] my-[6px] hover:rounded-[8px] {{ $menu->getActive($menuItem) == 'active' ? 'bg-blue-600 rounded-[8px]' : ' hover:bg-gray-100' }}">
                            <div class="flex gap-[10px] items-center justify-center">
                                <span class="{{ $menuItem['icon'] }} text-[24px] {{ $menu->getActive($menuItem) ? 'text-white' : ''}}"></span>
                            
                                <p class="text-gray-600 font-semibold {{ $menu->getActive($menuItem) ? 'text-white' : ''}}">
                                    @lang($menuItem['name']) 
                                </p>
                            </div>

                            @if (count($menuItem['children']) || $menuItem['key'] == 'configuration' )
                                <span
                                    class="float-right {{ $menu->getActive($menuItem) == 'active' ? 'icon-sort-up text-white' : 'icon-sort-down text-gray' }}  text-[25px]"
                                >
                                </span>
                            @endif
                        </div>
                    </a>
                  </div>

                    @if ($menuItem['key'] != 'configuration')
                        <div 
                            class="grid {{ $menu->getActive($menuItem) ? 'bg-gray-100' : 'hidden' }}"
                            :class="{'hidden' : isOpen}"
                        >
                            @foreach ($menuItem['children'] as $subMenuItem)
                                <a href="{{ $subMenuItem['url'] }}">
                                    <p class="text-{{ $menu->getActive($subMenuItem) ? 'blue':'gray' }}-600 px-[40px] py-[4px]">
                                        @lang($subMenuItem['name'])
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @else 
                        <div 
                            class="grid {{ $menu->getActive($menuItem) ? 'bg-gray-100' : 'hidden' }}"
                            :class="{'hidden' : isOpen}"
                        >
                            @foreach (core()->sortItems($tree->items) as $key => $item)
                                <a href="{{ route('admin.configuration.index', $item['key']) }}">
                                    <p class="text-{{ $item['key'] == request()->route('slug') ? 'blue':'gray' }}-600 px-[40px] py-[4px]">
                                        {{ trans($item['name']) ?? '' }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </nav>
    </div>

    {{-- Collapse menu --}}
    <v-sidebar-slider :is-toggled="isOpen"/>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-sidebar-slider-template">
        <div class="bg-white mt-[60px] fixed w-full max-w-[221px] bottom-0">
            <div 
                class="flex gap-[10px] p-[6px] items-center cursor-pointer"
                @click="toggle"
            >
                <span
                    class="text-[24px]"
                    :class="isToggled ? 'icon-arrow-left' : 'icon-arrow-right'"
                ></span>
    
                <p 
                    class="text-gray-600 font-semibold"
                    :class="{'hidden' : isToggled}"
                >
                    @lang('admin::app.components.layouts.sidebar.collapse')
                </p>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-slider', {
            template: '#v-sidebar-slider-template',

            props: ['isToggled'],

            methods: {
                toggle() {
                    this.$root.toggleMenu();
                }
            }
        })
    </script>
@endPushOnce

@pushOnce('styles')
    <style>
/* Style the flyout menu */
.absolute {
  position: absolute;
}

.hidden {
  display: none;
}

/* Show flyout menu on parent hover */
.group:hover .group-hover\:block {
  display: block;
}

    </style>
@endPushOnce