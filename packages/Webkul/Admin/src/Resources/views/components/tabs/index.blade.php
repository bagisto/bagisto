<tabs>
    {{ $slot }}
</tabs>

@pushOnce('scripts')
    <script type="text/x-template" id="tabs-template">
        <div>
            <div {{ $attributes->merge(['class' => 'tabs']) }}>
                <ul>
                    <li
                        v-for="tab in tabs"
                        :class="{ 'active': tab.isActive }"
                        @click="change(tab)"
                    >
                        <a>@{{ tab.name }}</a>
                    </li>
                </ul>
            </div>

            <div class="tabs-content">

                <slot></slot>

            </div>
        </div>
    </script>

    <script>
        Vue.component('tabs', {
            template: '#tabs-template',

            inject: ['$validator'],

            data() {
                return {
                    tabs: []
                }
            },

            created() {
                this.tabs = this.$children;
            },

            methods: {
                change(selectedTab) {
                    this.tabs.forEach(tab => {
                        tab.isActive = (tab.name == selectedTab.name);
                    });
                }
            }
        });
    </script>
@endPushOnce