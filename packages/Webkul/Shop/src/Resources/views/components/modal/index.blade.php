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
</v-modal>

@pushOnce('scripts')
    <script type="text/x-template" id="v-modal-template">
        <div>
            <div @click="toggle">
                <slot name="toggle">
                </slot>
            </div>

            <div v-if="isOpen">
                <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div class="w-full bg-[#F5F5F5] max-w-[595px] z-[999] absolute left-[50%] top-[50%] -translate-x-[50%] -translate-y-[50%]">
                                <div>
                                    <div class="flex justify-between items-center gap-[20px] p-[30px] bg-white border-b-[1px] border-[#E9E9E9]">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
