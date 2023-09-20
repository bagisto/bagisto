<v-shimmer-image {{ $attributes }}>
    <div {{ $attributes->merge(['class' => 'shimmer bg-neutral-100']) }}></div>
</v-shimmer-image>

@pushOnce('scripts')
    <script type="text/x-template" id="v-shimmer-image-template">
        <div
            class="shimmer"
            v-bind="$attrs"
            v-show="isLoading"
        >
        </div>

        <img
            v-bind="$attrs"
            :src="src"
            @load="onLoad"
            v-show="! isLoading"
        >
    </script>

    <script type="module">
        app.component('v-shimmer-image', {
            template: '#v-shimmer-image-template',

            props: ['src'],

            data() {
                return {
                    isLoading: true,
                };
            },
            
            methods: {
                onLoad() {
                    this.isLoading = false;
                },
            },
        });
    </script>
@endPushOnce
