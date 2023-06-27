{{-- <x-shop::shimmer.image
    class="rounded-sm bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300 w-[291px] h-[300px]"
    width="291"
    height="300"
    ::src="product.base_image.medium_image_url"
></x-shop::shimmer.image> --}}


<v-shimmer-image {{ $attributes }}>
    <div {{ $attributes->merge(['class' => 'shimmer']) }}>
    </div>
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
