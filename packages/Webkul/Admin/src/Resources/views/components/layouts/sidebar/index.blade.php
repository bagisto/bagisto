<div class="fixed top-[57px] h-full bg-white dark:bg-gray-900  pt-[8px] w-[270px] shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)] z-[1000] max-lg:hidden transition-all duration-300 group-[.sidebar-collapsed]/container:w-[70px]">
    <div class="h-[calc(100vh-100px)] overflow-auto journal-scroll group-[.sidebar-collapsed]/container:overflow-visible">
        <nav class="grid gap-[7px] w-full">
            {{-- Navigation Menu --}}
            @foreach ($menu->items as $menuItem)
                <div class="relative px-[16px] group/item">
                    <a
                        href="{{ $menuItem['url'] }}"
                        class="flex gap-[10px] p-[6px] items-center cursor-pointer hover:rounded-[8px] {{ $menu->getActive($menuItem) == 'active' ? 'bg-blue-600 rounded-[8px]' : ' hover:bg-gray-100 dark:hover:bg-gray-950 ' }} peer"
                    >
                        <span class="{{ $menuItem['icon'] }} text-[24px] {{ $menu->getActive($menuItem) ? 'text-white' : ''}}"></span>
                        
                        <p class="text-gray-600 dark:text-gray-300  font-semibold whitespace-nowrap group-[.sidebar-collapsed]/container:hidden {{ $menu->getActive($menuItem) ? 'text-white' : ''}}">
                            @lang($menuItem['name'])
                        </p>
                    </a>

                    @if (count($menuItem['children']))
                        <div class="{{ $menu->getActive($menuItem) ? ' !grid bg-gray-100 dark:!bg-gray-900' : '' }} hidden min-w-[180px] ltr:pl-[40px] rtl:pr-[40px] pb-[7px] rounded-b-[8px] z-[100] overflow-hidden group-[.sidebar-collapsed]/container:!hidden group-[.sidebar-collapsed]/container:absolute group-[.sidebar-collapsed]/container:top-0 group-[.sidebar-collapsed]/container:ltr:left-[70px] group-[.sidebar-collapsed]/container:rtl:right-[70px] group-[.sidebar-collapsed]/container:p-[0] group-[.sidebar-collapsed]/container:bg-white group-[.sidebar-collapsed]/container:border-[1px] group-[.sidebar-collapsed]/container:ltr:rounded-r-[8px] group-[.sidebar-collapsed]/container:rtl:rounded-l-[8px] group-[.sidebar-collapsed]/container:border-gray-300 dark:group-[.sidebar-collapsed]/container:border-gray-800 group-[.sidebar-collapsed]/container:rounded-none group-[.sidebar-collapsed]/container:ltr:shadow-[34px_10px_14px_rgba(0,0,0,0.01),19px_6px_12px_rgba(0,0,0,0.03),9px_3px_9px_rgba(0,0,0,0.04),2px_1px_5px_rgba(0,0,0,0.05),0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:rtl:shadow-[-34px_10px_14px_rgba(0,0,0,0.01),-19px_6px_12px_rgba(0,0,0,0.03),-9px_3px_9px_rgba(0,0,0,0.04),-2px_1px_5px_rgba(0,0,0,0.05),-0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:group-hover/item:!grid dark:!bg-gray-900">
                            @foreach ($menuItem['children'] as $subMenuItem)
                                <a
                                    href="{{ $subMenuItem['url'] }}"
                                    class="text-[14px] text-{{ $menu->getActive($subMenuItem) ? 'blue':'gray' }}-600 dark:text-gray-300 whitespace-nowrap py-[4px] group-[.sidebar-collapsed]/container:px-[20px] group-[.sidebar-collapsed]/container:py-[10px] hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-50 "
                                >
                                    @lang($subMenuItem['name'])
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        {{-- Dark mode Switcher --}}
        <v-dark>
            <div class="px-5 py-2 text-[16px] text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-950  cursor-pointer">
                <label class="relative inline-flex items-center cursor-pointer">
                    <label
                        class="rounded-full w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white dark:after:bg-white  after:border-gray-300 dark:after:border-white   peer-checked:bg-blue-600 dark:peer-checked:bg-gray-950 peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:ltr:left-[2px] after:rtl:right-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all dark:bg-gray-800"
                        for="dark_mode"
                    ></label>
                </label>
            </div>
        </v-dark>
    </div>

    {{-- Collapse menu --}}
    <v-sidebar-collapse></v-sidebar-collapse>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-sidebar-collapse-template">
        <div
            class="bg-white dark:bg-gray-900  fixed w-full max-w-[270px] bottom-0 px-[16px] hover:bg-gray-100 dark:hover:bg-gray-950  border-t-[1px] border-gray-200 transition-all duration-300 cursor-pointer"
            :class="{'max-w-[70px]': isCollapsed}"
            @click="toggle"
        >
            <div class="flex gap-[10px] p-[6px] items-center">
                <span
                    class="icon-collapse transition-all text-[24px]"
                    :class="[isCollapsed ? 'ltr:rotate-[180deg] rtl:rotate-[0]' : 'ltr:rotate-[0] rtl:rotate-[180deg]']"
                ></span>

                <p
                    class="hidden text-gray-600 dark:text-gray-300 font-semibold transition-all duration-300 select-none"
                    :class="{'group-[.sidebar-collapsed]/container:invisible': isCollapsed}"
                    v-show="! isCollapsed"
                >
                    @lang('admin::app.components.layouts.sidebar.collapse')
                </p>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-dark-template">
        <div class="px-5 py-2 text-[16px] text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-950  cursor-pointer">
            <x-admin::form.control-group.control
                type="switch"
                name="dark_mode"
                ::checked="isDarkMode"
                v-model="isDarkMode"
                @change="toggle"
            >
            </x-admin::form.control-group.control>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-collapse', {
            template: '#v-sidebar-collapse-template',

            data() {
                return {
                    isCollapsed: {{ request()->cookie('sidebar_collapsed') ?? 0 }},
                }
            },

            methods: {
                toggle() {
                    this.isCollapsed = parseInt(this.isCollapsedCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'sidebar_collapsed=' + this.isCollapsed + '; path=/; expires=' + expiryDate.toGMTString();

                    this.$root.$refs.appLayout.classList.toggle('sidebar-collapsed');
                },

                isCollapsedCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'sidebar_collapsed') {
                            return value;
                        }
                    }
                    
                    return 0;
                },
            },
        });
    </script>

    <script type="module">
        app.component('v-dark', {
            template: '#v-dark-template',

            data() {
                return {
                    isDarkMode: {{ request()->cookie('is_dark_mode') ?? 0 }},
                };
            },

            methods: {
                toggle() {
                    this.isDarkMode = parseInt(this.isDarkModeCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'is_dark_mode=' + this.isDarkMode + '; path=/; expires=' + expiryDate.toGMTString();

                    document.documentElement.classList.toggle('dark', this.isDarkMode === 1);
                },

                isDarkModeCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'is_dark_mode') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });
    </script>
@endpushOnce