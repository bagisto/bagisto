@props([
    'isActive' => true,
])

<div {{ $attributes->merge(['class' => 'border-b border-[#E9E9E9]']) }}>
    <v-accordion
        is-active="{{ $isActive }}"
        {{ $attributes }}
    >
        @isset($header)
            <template v-slot:header="{ toggle, isOpen }">
                <div
                    {{ $header->attributes->merge(['class' => 'flex justify-between items-center p-4 cursor-pointer select-none']) }}
                    role="button"
                    tabindex="0"
                    @click="toggle"
                >
                    {{ $header }}

                    <span
                        :class="`text-2xl ${isOpen ? 'icon-arrow-up' : 'icon-arrow-down'}`"
                        role="button"
                        aria-label="Toggle accordion"
                        tabindex="0"
                    ></span>
                </div>
            </template>
        @endisset

        @isset($content)
            <template v-slot:content="{ isOpen }">
                <div
                    {{ $content->attributes->merge(['class' => 'p-1.5 z-10 bg-white rounded-lg']) }}
                    v-show="isOpen"
                >
                    {{ $content }}
                </div>
            </template>
        @endisset
    </v-accordion>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-accordion-template">
        <div>
            <slot
                name="header"
                :toggle="toggle"
                :isOpen="isOpen"
            >
                @lang('admin::app.components.accordion.default-content')
            </slot>

            <slot
                name="content"
                :isOpen="isOpen"
            >
                @lang('admin::app.components.accordion.default-content')
            </slot>
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
