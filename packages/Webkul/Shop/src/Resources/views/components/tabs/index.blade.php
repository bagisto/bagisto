@props(['position' => 'left'])

<v-tabs
    position="{{ $position }}"
    {{ $attributes }}
>
    <x-shop::shimmer.tabs></x-shop::shimmer.tabs>
</v-tabs>

@pushOnce('scripts')
    <script type="text/x-template" id="v-tabs-template">
        <div>
            <div
                class="flex bg-[#F5F5F5] pt-[18px] gap-[30px] justify-center max-1180:hidden"
                :style="positionStyles"
            >
                <div
                    v-for="tab in tabs"
                    class="text-[20px] font-medium text-[#7D7D7D] pb-[18px] px-[30px] cursor-pointer"
                    :class="{'text-black border-navyBlue border-b-[2px]': tab.isActive }"
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
                        `display: flex`,
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
