@props(['position' => 'bottom-left'])

<v-dropdown position="{{ $position }}" {{ $attributes->merge(['class' => 'relative']) }}>
    @isset($toggle)
        {{ $toggle }}
        
        <template v-slot:toggle>
            {{ $toggle }}
        </template>
    @endisset

    @isset($content)
        <template v-slot:content>
            <div {{ $content->attributes->merge(['class' => 'p-[20px]']) }}>
                {{ $content }}
            </div>
        </template>
    @endisset

    @isset($menu)
        <template v-slot:menu>
            <ul {{ $menu->attributes->merge(['class' => 'py-[15px]']) }}>
                {{ $menu }}
            </ul>
        </template>
    @endisset
</v-dropdown>

@pushOnce('scripts')
    <script type="text/x-template" id="v-dropdown-template">
        <div>   
            <div
                ref="toggleBlock"
                @click="toggle()"
            >
                <slot name="toggle">Toggle</slot>
            </div>

            <div
                class="absolute bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)] rounded-[20px] w-max z-10"
                :class="[hiddenClass]"
                :style="positionStyles"
            >
                <slot name="content"></slot>

                <slot name="menu"></slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dropdown', {
            template: '#v-dropdown-template',

            props: {
                position: String,
            },

            data() {
                return {
                    toggleBlockHeight: 0,
                    
                    isActive: false,
                };
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
            },

            mounted() {
                this.toggleBlockHeight = this.$refs.toggleBlock.clientHeight;
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);
            },

            computed: {
                hiddenClass() {
                    return ! this.isActive ? 'hidden' : '';
                },

                positionStyles() {
                    switch (this.position) {
                        case 'bottom-left':
                            return [`top: ${this.toggleBlockHeight}px`, 'left: 0'];

                        case 'bottom-right':
                            return [`top: ${this.toggleBlockHeight}px`, 'right: 0'];

                        case 'top-left':
                            return [`bottom: ${this.toggleBlockHeight*2}px`, 'left: 0'];

                        case 'top-right':
                            return [`bottom: ${this.toggleBlockHeight*2}px`, 'right: 0'];

                        default:
                            return [`top: ${this.toggleBlockHeight}px`, 'left: 0'];
                    }
                },
            },

            methods: {
                toggle() {
                    this.isActive = ! this.isActive;
                },

                handleFocusOut(e) {
                    if (! this.$el.contains(e.target)) {
                        this.isActive = false;
                    }
                },
            },
        });
    </script>
@endPushOnce
