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
            <!-- Toggler -->
            <div @click="toggle">
                <slot name="toggle">
                    Default Toggle
                </slot>
            </div>

            <!-- Overlay -->
            <div
                class="w-full bg-[#00000025] absolute inset-x-0 inset-y-0 z-[1]"
                v-show="isOpen"
            ></div>

            <!-- Content -->
            <transition
                tag="div"
                name="drawer"
                :enter-from-class="enterFromLeaveToClasses"
                enter-active-class="transform transition ease-in-out duration-200"
                enter-to-class="translate-x-0"
                leave-from-class="translate-x-0"
                leave-active-class="transform transition ease-in-out duration-200"
                :leave-to-class="enterFromLeaveToClasses"
            >
                <div
                    class="fixed z-[1000] bg-white overflow-hidden max-sm:!w-full"
                    :class="{
                        'inset-x-0 top-0': position == 'top',
                        'inset-x-0 bottom-0': position == 'bottom',
                        'inset-y-0 ltr:right-0 rtl:left-0': position == 'right',
                        'inset-y-0 ltr:left-0 rtl:right-0': position == 'left'
                    }"
                    :style="'width:' + width + 'px'"
                    v-if="isOpen"
                >
                    <div class="bg-white h-full pointer-events-auto w-full overflow-auto">
                        <div class="flex flex-col h-full w-full">
                            <div class="overflow-auto flex-1 min-h-0 min-w-0">
                                <div class="flex flex-col  h-full">
                                    <div class="grid gap-y-[10px] p-[25px] pb-[20px]">
                                        <div>
                                            <slot name="header">
                                                Default Header
                                            </slot>
                                        </div>

                                        <div class="absolute top-5 ltr:right-5 rtl:left-5">
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
            </transition>
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
                enterFromLeaveToClasses() {
                    if (this.position == 'top') {
                        return '-translate-y-full';
                    } else if (this.position == 'bottom') {
                        return 'translate-y-full';
                    } else if (this.position == 'left') {
                        return 'ltr:-translate-x-full rtl:translate-x-full';
                    } else if (this.position == 'right') {
                        return 'ltr:translate-x-full rtl:-translate-x-full';
                    }
                }
            },

            methods: {
                toggle() {
                    this.isOpen = ! this.isOpen;

                    this.$emit('toggle', { isActive: this.isOpen });
                }
            },
        });
    </script>
@endPushOnce
