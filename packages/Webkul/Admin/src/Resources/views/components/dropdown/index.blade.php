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
            <div {{ $content->attributes->merge(['class' => 'p-5']) }}>
                {{ $content }}
            </div>
        </template>
    @endisset

    @isset($menu)
        <template v-slot:menu>
            <ul {{ $menu->attributes->merge(['class' => 'py-4']) }}>
                {{ $menu }}
            </ul>
        </template>
    @endisset
</v-dropdown>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dropdown-template"
    >
        <div>
            <div
                class="select-none flex"
                ref="toggleBlock"
                @click="toggle()"
            >
                <slot name="toggle">Toggle</slot>
            </div>

            <transition
                tag="div"
                name="dropdown"
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div
                    class="absolute bg-white dark:bg-gray-900 shadow-[0px_8px_10px_0px_rgba(0,0,0,0.20),0px_6px_30px_0px_rgba(0,0,0,0.12),0px_16px_24px_0px_rgba(0,0,0,0.14)] rounded w-max z-10"
                    :style="positionStyles"
                    v-show="isActive"
                >
                    <slot name="content"></slot>

                    <slot name="menu"></slot>
                </div>
            </transition>
        </div>
    </script>

    <script type="module">
        app.component('v-dropdown', {
            template: '#v-dropdown-template',

            props: {
                position: String,

                closeOnClick: {
                    type: Boolean,
                    required: false,
                    default: true
                },
            },

            data() {
                return {
                    toggleBlockWidth: 0,

                    toggleBlockHeight: 0,

                    isActive: false,
                };
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
            },

            mounted() {
                this.toggleBlockWidth = this.$refs.toggleBlock.clientWidth;

                this.toggleBlockHeight = this.$refs.toggleBlock.clientHeight;
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);
            },

            computed: {
                positionStyles() {
                    switch (this.position) {
                        case 'bottom-left':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`,
                                `top: ${this.toggleBlockHeight}px`,
                                'left: 0',
                            ];

                        case 'bottom-right':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`,
                                `top: ${this.toggleBlockHeight}px`,
                                'right: 0',
                            ];

                        case 'top-left':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`
                                `bottom: ${this.toggleBlockHeight*2}px`,
                                'left: 0',
                            ];

                        case 'top-right':
                            return [
                                `min-width: ${this.toggleBlockWidth}px`
                                `bottom: ${this.toggleBlockHeight*2}px`,
                                'right: 0',
                            ];

                        default:
                            return [
                                `min-width: ${this.toggleBlockWidth}px`
                                `top: ${this.toggleBlockHeight}px`,
                                'left: 0',
                            ];
                    }
                },
            },

            methods: {
                toggle() {
                    this.isActive = ! this.isActive;
                },

                handleFocusOut(e) {
                    if (! this.$el.contains(e.target) || (this.closeOnClick && this.$el.children[1].contains(e.target))) {
                        this.isActive = false;
                    }
                },
            },
        });
    </script>
@endPushOnce
