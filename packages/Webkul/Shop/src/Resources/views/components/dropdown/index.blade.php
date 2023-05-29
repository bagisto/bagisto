@props([
    'position' => 'bottom-left',
])

<vee-dropdown position="{{ $position }}"></vee-dropdown>

@pushOnce('scripts')
    <script type="text/x-template" id="vee-dropdown-template">
        <div
            {{ $attributes->merge(['class' => 'relative']) }}
            {{ $attributes }}
        >
            @isset($toggle)
                <div
                    ref="toggleBlock"
                    {{ $toggle->attributes }}
                    @click="toggle()"
                >
                    {{ $toggle }}
                </div>
            @endisset

            @isset($content)
                <div
                    :class="[hiddenClass]"
                    {{ $content->attributes->merge(['class' => 'absolute p-[20px] bg-white shadow-[0px_10px_84px_rgba(0,0,0,0.1)] rounded-[12px] w-max']) }}
                    {{ $content->attributes }}
                    :style="positionStyles"
                >
                    {{ $content }}
                </div>
            @endisset
        </div>
    </script>

    <script type="module">
        app.component('vee-dropdown', {
            template: '#vee-dropdown-template',

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
