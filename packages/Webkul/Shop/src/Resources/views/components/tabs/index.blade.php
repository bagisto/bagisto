@props(['position' => 'left'])

<v-tabs
    position="{{ $position }}"
    {{ $attributes }}
>
    <x-shop::shimmer.tabs />
</v-tabs>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tabs-template"
    >
        <div>
            <div
                class="flex flex-row justify-center gap-8 bg-zinc-100 max-sm:gap-1.5"
                role="tablist"
                aria-label="Product details"
                :style="positionStyles"
            >
                <button
                    type="button"
                    role="tab"
                    v-for="tab in tabs"
                    class="cursor-pointer px-8 py-5 text-xl font-medium text-zinc-600 max-md:px-4 max-md:py-3 max-md:text-sm max-sm:px-2.5 max-sm:py-2.5 focus-visible:ring-2 focus-visible:ring-navyBlue focus-visible:ring-offset-2 focus-visible:outline-none rounded"
                    :class="{'border-b-2 border-navyBlue !text-black transition': tab.isActive }"
                    :id="tab.$attrs.id + '-button'"
                    :aria-selected="tab.isActive ? 'true' : 'false'"
                    :aria-controls="tab.$attrs.id"
                    @click="change(tab)"
                    @keydown.left.prevent="navigateLeft(tab)"
                    @keydown.right.prevent="navigateRight(tab)"
                >
                    @{{ tab.title }}
                </button>
            </div>

            <div>
                <slot></slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-tabs', {
            template: '#v-tabs-template',

            props: ['position'],

            data() {
                return {
                    tabs: []
                }
            },

            computed: {
                positionStyles() {
                    return [
                        `justify-content: ${this.position}`
                    ];
                },
            },

            methods: {
                change(selectedTab) {
                    this.tabs.forEach(tab => {
                        tab.isActive = (tab.title == selectedTab.title);
                    });
                },

                navigateLeft(currentTab) {
                    const currentIndex = this.tabs.indexOf(currentTab);
                    const prevIndex = (currentIndex - 1 + this.tabs.length) % this.tabs.length;
                    const prevTab = this.tabs[prevIndex];
                    this.change(prevTab);
                    this.$nextTick(() => {
                        document.getElementById(prevTab.$attrs.id + '-button')?.focus();
                    });
                },

                navigateRight(currentTab) {
                    const currentIndex = this.tabs.indexOf(currentTab);
                    const nextIndex = (currentIndex + 1) % this.tabs.length;
                    const nextTab = this.tabs[nextIndex];
                    this.change(nextTab);
                    this.$nextTick(() => {
                        document.getElementById(nextTab.$attrs.id + '-button')?.focus();
                    });
                },
            },
        });
    </script>
@endPushOnce
