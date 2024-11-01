<template>
    <div>
        <div
            :id="'image-shimmer-' + this.uid"
            class="shimmer"
            v-bind="$attrs"
            v-show="isLoading"
        >
        </div>

        <img
            v-bind="$attrs"
            :data-src="src"
            :id="'image-' + this.uid"
            @load="onLoad"
            v-show="!isLoading"
            v-if="lazy"
        >

        <img
            v-bind="$attrs"
            :data-src="src"
            :id="'image-' + this.uid"
            @load="onLoad"
            v-else
            v-show="!isLoading"
        >
    </div>
</template>

<script>
export default {
    name: 'VShimmerImage',

    props: {
        lazy: {
            type: Boolean,
            default: true,
        },

        src: {
            type: String,
            default: '',
        }
    },

    data() {
        return {
            isLoading: true,
            uid: Math.random().toString(36).slice(-6)
        };
    },

    mounted() {
        if (!this.lazy) {
            return;
        }

        let lazyImageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let lazyImage = document.getElementById('image-' + this.uid);

                    lazyImage.src = lazyImage.dataset.src;

                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });

        lazyImageObserver.observe(document.getElementById('image-shimmer-' + this.uid));
    },

    methods: {
        onLoad() {
            this.isLoading = false;
        },
    },
};
</script>
