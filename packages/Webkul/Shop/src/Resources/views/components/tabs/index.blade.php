<v-tabs>
    <template v-slot>
        {{ $slot }}
    </template>
</v-tabs>

@pushOnce('scripts')
    <script type="text/x-template" id="v-tabs-template">
        <div>
            <div>
                <ul>
                    <li
                        v-for="tab in tabs"
                        v-text="tab.title"
                        @click="change(tab)"
                    >
                    </li>
                </ul>
            </div>

            <div>
                <slot></slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-tabs', {
            template: '#v-tabs-template',

            data() {
                return {
                    tabs: []
                }
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
