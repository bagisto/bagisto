@props([
    'isActive' => true,
])

<v-accordion
    is-active="{{ $isActive }}"
    {{ $attributes }}
>
    <x-admin::shimmer.accordion class="w-[360px] h-[271px]"></x-admin::shimmer.accordion>

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
</v-accordion>

@pushOnce('scripts')
    <script type="text/x-template" id="v-accordion-template">
        <div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-900 rounded-[4px] box-shadow']) }}>
            <div :class="`flex items-center justify-between p-[6px] ${isOpen ? 'active' : ''}`">
                <slot name="header">
                    Default Header
                </slot>

                <span 
                    :class="`text-[24px] p-[6px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 ${isOpen ? 'icon-arrow-up' : 'icon-arrow-down'}`"
                    @click="toggle"
                ></span>
            </div>

            <div class="px-[16px] pb-[16px]" v-if="isOpen">
                <slot name="content">
                    Default Content
                </slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-accordion', {
            template: '#v-accordion-template',

            props: [
                'isActive',
            ],

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
            },
        });
    </script>
@endPushOnce
