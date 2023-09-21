@props([
    'isActive' => true,
])

<v-accordion
    is-active="{{ $isActive }}"
    {{ $attributes }}
>
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
        <div {{ $attributes->merge(['class' => 'border-b-[1px] border-[#E9E9E9]']) }}>
            <div
                :class="`flex justify-between items-center py-[10px] cursor-pointer select-none ${isOpen ? 'active' : ''}`"
                @click="toggle"
            >
                <slot name="header">
                    @lang('admin::app.components.accordion.default-content')

                </slot>

                <span :class="`text-[24px] ${isOpen ? 'icon-arrow-up' : 'icon-arrow-down'}`"></span>
            </div>

            <div class="z-10 bg-white rounded-lg" v-show="isOpen">
                <slot name="content">
                    @lang('admin::app.components.accordion.default-content')
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
