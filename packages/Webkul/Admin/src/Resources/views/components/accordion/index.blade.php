@props([
    'isActive' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-900 rounded box-shadow']) }}>
    <v-accordion
        is-active="{{ $isActive }}"
        {{ $attributes }}
    >
        <x-admin::shimmer.accordion class="w-[360px] h-[271px]" />

        @isset($header)
            <template v-slot:header="{ toggle, isOpen }">
                <div {{ $header->attributes->merge(['class' => 'flex items-center justify-between p-1.5']) }}>
                    {{ $header }}

                    <span
                        :class="`text-2xl p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 ${isOpen ? 'icon-arrow-up' : 'icon-arrow-down'}`"
                        @click="toggle"
                    ></span>
                </div>
            </template>
        @endisset

        @isset($content)
            <template v-slot:content="{ isOpen }">
                <div
                    {{ $content->attributes->merge(['class' => 'px-4 pb-4']) }}
                    v-show="isOpen"
                >
                    {{ $content }}
                </div>
            </template>
        @endisset
    </v-accordion>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-accordion-template"
    >
        <div>
            <slot
                name="header"
                :toggle="toggle"
                :isOpen="isOpen"
            >
                Default Header
            </slot>

            <slot
                name="content"
                :isOpen="isOpen"
            >
                Default Content
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
