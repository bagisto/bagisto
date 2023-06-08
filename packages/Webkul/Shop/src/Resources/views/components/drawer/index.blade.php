@props([
    'isActive' => false,
    'position' => 'right',
    'width'    => '500',
])

<v-drawer
    is-active="{{ $isActive }}"
    position="{{ $position }}"
    width="{{ $width }}"
>
    @isset($toggle)
        <template v-slot:toggle>
            {{ $toggle }}
        </template>
    @endisset

    @isset($header)
        <template v-slot:header>
            {{ $header }}
        </template>
    @endisset

    @isset($content)
        <template v-slot:content>
            <div>
                {{ $content }}
            </div>
        </template>
    @endisset

    @isset($footer)
        <template v-slot:footer>
            <div>
                {{ $footer }}
            </div>
        </template>
    @endisset
</v-drawer>

@pushOnce('scripts')
    <script type="text/x-template" id="v-drawer-template">
        <div>
            <div @click="toggle">
                <slot name="toggle">
                    Default Toggle
                </slot>
            </div>

            <div class="bottom-0 left-0 pointer-events-none fixed right-0 top-0 z-[9999]">
                <div
                    class="bg-[#00000025] bottom-0 left-0 pointer-events-auto absolute right-0 top-0 z-[1000]"
                    v-if="isOpen"
                >
                </div>

                <div
                    class="absolute top-0 bottom-0 z-[1000] bg-white overflow-hidden max-sm:w-full"
                    :style="positionStyles"
                >
                    <div class="bg-white h-full pointer-events-auto w-full overflow-auto">
                        <div class="flex flex-col h-full w-full ">
                            <div class="overflow-auto flex-1 min-h-0 min-w-0">
                                <div class="flex flex-col  h-full">
                                    <div class="grid gap-y-[10px] p-[25px] pb-[20px]">
                                        <div>
                                            <slot name="header">
                                                Default Header
                                            </slot>
                                        </div>

                                        <div class="absolute top-5 right-5">
                                            <span
                                                class="icon-cancel text-[30px] cursor-pointer"
                                                @click="toggle"
                                            >
                                            </span>
                                        </div>
                                    </div>

                                    <div class="px-[25px] overflow-auto flex-1">
                                        <slot name="content">
                                            Default Content
                                        </slot>
                                    </div>

                                    <div class="pb-[30px]">
                                        <slot name="footer">
                                            Default Footer
                                        </slot>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-drawer', {
            template: '#v-drawer-template',

            props: ['isActive', 'position', 'width'],

            data() {
                return {
                    isOpen: this.isActive,
                };
            },

            computed: {
                positionStyles() {
                    const position = ['left', 'right'].includes(this.position)
                        ? this.position
                        : 'right';

                    const width = this.width;

                    if (this.isOpen) {
                        return [
                            `width: ${width}px`,
                            `max-width: ${width}px`,
                            `${position}: 0px`,
                            `transition: ${position} .25s cubic-bezier(0.820, 0.085, 0.395, 0.895)`,
                        ];
                    }

                    return [
                        `width: ${width}px`,
                        `max-width: ${width}px`,
                        `${position}: -${width}px`,
                        `transition: ${position} .25s cubic-bezier(0.820, 0.085, 0.395, 0.895)`
                    ];
                },
            },

            methods: {
                toggle() {
                    this.isOpen = ! this.isOpen;

                    this.$emit('toggle', { isActive: this.isOpen });
                },
            },
        });
    </script>
@endPushOnce
