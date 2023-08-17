@php
    $tree = \Webkul\Core\Tree::create();

    foreach (config('core') as $item) {
        $tree->add($item);
    }
@endphp

<div class="fixed top-[57px] h-full bg-white pt-[8px] w-[270px] shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)] max-lg:hidden transition-all duration-300 group-[.is-collapsed]:w-[70px]">
    <div class="h-[calc(100vh-100px)] px-[16px] overflow-auto journal-scroll">
        <nav class="grid gap-[7px] w-full">
            {{-- Navigation Menu --}}
            @foreach ($menu->items as $menuItem)
                <div>
                    <a href="{{ $menuItem['url'] }}">
                        <div class="accordian-toggle flex gap-[10px] p-[6px] items-center cursor-pointer hover:rounded-[8px] {{ $menu->getActive($menuItem) == 'active' ? 'bg-blue-600 rounded-[8px]' : ' hover:bg-gray-100' }}">
                            <span class="{{ $menuItem['icon'] }} text-[24px] {{ $menu->getActive($menuItem) ? 'text-white' : ''}}"></span>
                            
                            <p class="text-gray-600 font-semibold whitespace-nowrap group-[.is-collapsed]:hidden {{ $menu->getActive($menuItem) ? 'text-white' : ''}}">
                                @lang($menuItem['name']) 
                            </p>
                        </div>
                    </a>

                    @if (count($menuItem['children']))
                        <div class="hidden group-[.is-collapsed]:!hidden pb-[7px] rounded-b-[8px] {{ $menu->getActive($menuItem) ? ' !grid bg-gray-100' : '' }}">
                            @foreach ($menuItem['children'] as $subMenuItem)
                                <a href="{{ $subMenuItem['url'] }}">
                                    <p class=" whitespace-nowrap px-[40px] py-[4px] text-{{ $menu->getActive($subMenuItem) ? 'blue':'gray' }}-600">
                                        @lang($subMenuItem['name'])
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>
    </div>

    {{-- Collapse menu --}}
    <v-sidebar-collapse></v-sidebar-collapse>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-sidebar-collapse-template">
        <div
            class="bg-white fixed w-full max-w-[270px] bottom-0 px-[16px] hover:bg-gray-100 border-t-[1px] border-gray-200 transition-all duration-300 cursor-pointer"
            :class="{'max-w-[70px]': ! isOpen}"
            @click="collapse"
        >
            <div class="flex gap-[10px] p-[6px] items-center">
                <span
                    class="icon-arrow-right text-[24px]"
                    :class="{'!icon-arrow-left': ! isOpen}"
                ></span>

                <p
                    class="text-gray-600 font-semibold transition-all duration-300"
                    :class="{'group-[.is-collapsed]:invisible': ! isOpen}"
                    v-show="isOpen"
                >
                    @lang('admin::app.components.layouts.sidebar.collapse')
                </p>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-collapse', {
            template: '#v-sidebar-collapse-template',

            data() {
                return {
                    isOpen: true,
                }
            },

            methods: {
                collapse() {
                    if (this.isOpen) {
                        this.isOpen = false;

                        this.$root.$refs.appLayout.classList.add('is-collapsed');
                    } else {
                        this.isOpen = true;

                        this.$root.$refs.appLayout.classList.remove('is-collapsed');
                    }
                }
            }
        });
    </script>
@endpushOnce