@props([
    'isActive' => false,
])

<v-modal
    is-active="{{ $isActive }}"
    {{ $attributes }}
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
            {{ $content }}
        </template>
    @endisset

    @isset($footer)
        <template v-slot:footer>
            {{ $footer }}
        </template>
    @endisset

</v-modal>

@pushOnce('scripts')
    <script type="text/x-template" id="v-modal-template">
        <div>
            <div @click="toggle">
                <slot name="toggle">
                </slot>
            </div>

            <transition
                tag="div"
                name="modal-overlay"
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity z-[1]"
                    v-show="isOpen"
                ></div>
            </transition>

            <transition
                tag="div"
                name="modal-content"
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
                enter-to-class="opacity-100 translate-y-0 md:scale-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 md:scale-100"
                leave-to-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
            >
                <div class="fixed inset-0 z-10 transform transition overflow-y-auto" v-show="isOpen">
                    <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
                        <div class="w-full max-w-[595px] z-[999] absolute left-[50%] top-[50%] rounded-lg bg-white box-shadow max-md:w-[90%] -translate-x-[50%] -translate-y-[50%]">
                            <div>
                                <div class="flex gap-[20px] justify-between items-center p-[16px] rounded-t-lg bg-white border-b-[1px] border-[#E9E9E9]">
                                    <slot name="header">
                                        Default Header
                                    </slot>

                                    <span
                                        class="icon-cancel text-[30px] cursor-pointer"
                                        @click="toggle"
                                    >
                                    </span>
                                </div>
                            </div>

                            <div>
                                <slot name="content">
                                    Default Content
                                </slot>
                            </div>

                            <div class="flex gap-[20px] justify-end p-[16px] rounded-b-lg bg-white border-t-[1px] border-[#E9E9E9]">
                                <slot name="footer">
                                    Default footer
                                </slot>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </script>

    <script type="module">
        app.component('v-modal', {
            template: '#v-modal-template',

            props: ['isActive'],

            data() {
                return {
                    isOpen: this.isActive,
                };
            },

            methods: {
                toggle() {
                    this.isOpen = ! this.isOpen;

                    this.$emit('toggle', { isActive: this.isOpen });
                },
            }
        });
    </script>
@endPushOnce
