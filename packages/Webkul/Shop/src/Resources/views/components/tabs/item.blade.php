@props(['name', 'isSelected' => false])

<tab-item name="{{ $name }}" is-selected="{{ $isSelected }}">
    {{ $slot }}
</tab-item>

@pushOnce('scripts')
    <script type="text/x-template" id="tab-item-template">
        <div v-show="isActive" {{ $attributes->merge(['class' => 'tab-item']) }}>

            <slot></slot>

        </div>
    </script>

    <script type="module">
        app.component('tab-item', {
            template: '#tab-item-template',

            props: ['name', 'isSelected'],

            data() {
                return {
                    isActive: false
                }
            },

            mounted() {
                this.isActive = this.isSelected;
            }
        });
    </script>
@endPushOnce
