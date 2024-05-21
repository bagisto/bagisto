@php
    $customer = auth()->guard('customer')->user();
@endphp

<div class="panel-side journal-scroll grid max-h-[1320px] min-w-[342px] max-w-[380px] grid-cols-[1fr] gap-8 overflow-y-auto overflow-x-hidden max-xl:min-w-[270px] max-md:max-w-full">
    <!-- Account Profile Hero Section -->
    <div class="grid grid-cols-[auto_1fr] items-center gap-4 rounded-xl border border-zinc-200 px-5 py-[25px]">
        <div class="">
            <img
                src="{{ $customer->image_url ??  bagisto_asset('images/user-placeholder.png') }}"
                class="h-[60px] w-[60px] rounded-full"
                alt="Profile Image"
            >
        </div>

        <div class="flex flex-col justify-between">
            <p class="font-mediums text-2xl">Hello! {{ $customer->first_name }}</p>

            <p class="text-zinc-500">{{ $customer->email }}</p>
        </div>
    </div>

    <!-- Account Navigation Menus -->
    @foreach (menu()->forShop()->getItems() as $menuItem)
        <div class="max-md:rounded-md max-md:border max-md:border-b max-md:border-l-[1px] max-md:border-r max-md:border-t-0 max-md:border-zinc-200">
            <v-account-navigation>
                <!-- Account Navigation Toggler -->
                <div class="accordian-toggle select-none pb-5 max-md:flex max-md:items-center max-md:justify-between max-md:gap-x-4 max-md:rounded-tl-[6px] max-md:rounded-tr-[6px] max-md:bg-gray-100 max-md:px-6 max-md:py-5 md:pointer-events-none">
                    <p class="text-xl md:font-medium">
                        {{ $menuItem->getName() }}
                    </p>

                    <span class="icon-arrow-right text-2xl md:hidden"></span>
                </div>

                <!-- Account Navigation Content -->
                @if ($menuItem->haveChildren())
                    <div class="accordian-content grid rounded-md border border-b border-l-[1px] border-r border-t-0 border-zinc-200 max-md:hidden max-md:border-none">
                        @foreach ($menuItem->getChildren() as $subMenuItem)
                            <a href="{{ $subMenuItem->getUrl() }}">
                                <div class="flex justify-between px-6 py-5 border-t {{ $subMenuItem->isActive() ? 'bg-zinc-100' : '' }} border-zinc-200 hover:bg-zinc-100 cursor-pointer">
                                    <p class="flex items-center gap-x-4 text-lg font-medium">
                                        <span class="{{ $subMenuItem->getIcon() }}  text-2xl"></span>

                                        {{ $subMenuItem->getName() }}
                                    </p>
                    
                                    <span class="icon-arrow-right rtl:icon-arrow-left text-2xl max-md:hidden"></span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </v-account-navigation>
        </div>
    @endforeach
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-account-navigation-template"
    >
        <div>
            <slot></slot>
        </div>
    </script>

    <script type="module">
        app.component("v-account-navigation", {
            template: '#v-account-navigation-template',

            data() {
                return {
                    isOpen: false,
                };
            },

            mounted() {
                this.$el.querySelector('.accordian-toggle').addEventListener('click', () => {
                    this.toggleAccordion();
                });
            },

            methods: {
                toggleAccordion() {
                    this.isOpen = ! this.isOpen;

                    if (this.isOpen) {
                        this.$el.querySelector('.icon-arrow-right').classList.add('icon-arrow-down');
                        this.$el.querySelector('.icon-arrow-down').classList.remove('icon-arrow-right');

                        this.$el.querySelector('.accordian-content').style.display = "grid";
                    } else {
                        this.$el.querySelector('.icon-arrow-down').classList.add('icon-arrow-right');
                        this.$el.querySelector('.icon-arrow-right').classList.remove('icon-arrow-down');

                        this.$el.querySelector('.accordian-content').style.display = "none";
                    }
                },
            },
        });
      </script>
@endpushOnce