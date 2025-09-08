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
                class="flex justify-center gap-4 bg-white dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 pt-2 max-sm:hidden"
                :style="positionStyles"
            >
                <div
                    v-for="tab in tabs"
                    class="cursor-pointer px-2.5 pb-3.5 text-base font-medium text-gray-300"
                    :class="{'border-blue-600 border-b-2 text-blue-600 transition': tab.isActive }"
                    @click="change(tab)"
                >
                    @{{ tab.title }}
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
