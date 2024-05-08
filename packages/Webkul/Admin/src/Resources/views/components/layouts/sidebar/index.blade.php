<div class="fixed top-14 z-[1000] h-full w-[270px] bg-white pt-4 shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)] transition-all duration-300 group-[.sidebar-collapsed]/container:w-[70px] dark:bg-gray-900 max-lg:hidden">
    <div class="journal-scroll h-[calc(100vh-100px)] overflow-auto group-[.sidebar-collapsed]/container:overflow-visible">
        <nav class="grid w-full gap-2">
            <!-- Navigation Menu -->
            @foreach(menu()->all() as $item)
                <div
                    class="px-4 group/item {{ $item->isActive()  ? 'active' : 'inactive' }}"
                    onmouseenter="adjustSubMenuPosition(event)"
                >
                    <x-menu::menu.item :item="$item" />

                    @if ($item->hasGroup())
                        <x-menu::menu.group :item="$item" />
                    @endif
                </div>
            @endforeach
        </nav>
    </div>

    <!-- Collapse menu -->
    <v-sidebar-collapse></v-sidebar-collapse>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-sidebar-collapse-template"
    >
        <div
            class="fixed bottom-0 w-full max-w-[270px] cursor-pointer border-t border-gray-200 bg-white px-4 transition-all duration-300 hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-950"
            :class="{'max-w-[70px]': isCollapsed}"
            @click="toggle"
        >
            <div class="flex items-center gap-2.5 p-1.5">
                <span
                    class="icon-collapse text-2xl transition-all"
                    :class="[isCollapsed ? 'ltr:rotate-[180deg] rtl:rotate-[0]' : 'ltr:rotate-[0] rtl:rotate-[180deg]']"
                ></span>
            </div>
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

    <script>
        const adjustSubMenuPosition = (event) => {
            let menuContainer = event.currentTarget;

            let subMenuContainer = menuContainer.lastElementChild;

            if (subMenuContainer) {
                const menuTopOffset = menuContainer.getBoundingClientRect().top;

                const subMenuHeight = subMenuContainer.offsetHeight;

                const availableHeight = window.innerHeight - menuTopOffset;

                let subMenuTopOffset = menuTopOffset;

                if (subMenuHeight > availableHeight) {
                    subMenuTopOffset = menuTopOffset - (subMenuHeight - availableHeight);
                }

                subMenuContainer.style.top = `${subMenuTopOffset}px`;
            }
        };
    </script>
@endpushOnce