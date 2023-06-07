@props([
    'title'      => '',
    'isSelected' => false,
])

<tab-item
    title="{{ $title }}"
    is-selected="{{ $isSelected }}"
>
    <template v-slot>
        {{ $slot }}
    </template>
</tab-item>

@pushOnce('scripts')
    <script type="text/x-template" id="tab-item-template">
        <div v-if="isActive">
            <slot></slot>
        </div>
    </script>

    <script type="module">
        app.component('tab-item', {
            template: '#tab-item-template',

            props: ['title', 'isSelected'],

            data() {
                return {
                    isActive: false
                }
            },

            mounted() {
                this.isActive = this.isSelected;

                /**
                 * On mounted, pushing element to its parents component.
                 */
                this.$parent.$data.tabs.push(this);
            }
        });
    </script>
@endPushOnce
