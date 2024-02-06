@props(['position' => 'left'])

<v-tabs
    position="{{ $position }}"
    {{ $attributes }}
>
    <x-admin::shimmer.tabs />
</v-tabs>

@pushOnce('scripts')
    <script 
        type="text/x-template"
        id="v-tabs-template"
    >
        <div>
            <div
                class="flex gap-4 justify-center pt-2 bg-neutral-100 max-sm:hidden"
                :style="positionStyles"
            >
                <div
                    v-for="tab in tabs"
                    class="pb-3.5 px-2.5 text-base  font-medium text-gray-300 cursor-pointer"
                    :class="{'border-navyBlue border-b-2 text-black transition': tab.isActive }"
                    v-text="tab.title"
                    @click="change(tab)"
                >
                </div>
            </div>

            <div>
                {{ $slot }}
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
            },
        });
    </script>
@endPushOnce
