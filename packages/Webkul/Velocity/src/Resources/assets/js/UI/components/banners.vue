<template>
    <div :class="`slides-container ${direction}`">
        <carousel-component
            loop="true"
            timeout="5000"
            autoplay="true"
            slides-per-page="1"
            navigation-enabled="hide"
            paginationEnabled="hide"
            :locale-direction="direction"
            :slides-count="banners.length > 0 ? banners.length : 1"
        >
            <template v-if="banners.length > 0">
                <slide
                    v-for="(banner, index) in banners"
                    :key="index"
                    :slot="`slide-${index}`"
                    title=" "
                >
                    <a
                        :href="
                            banner.slider_path != ''
                                ? banner.slider_path
                                : 'javascript:void(0);'
                        "
                    >
                        <img
                            class="col-12 no-padding banner-icon"
                            :src="
                                banner.image_url != ''
                                    ? banner.image_url
                                    : defaultBanner
                            "
                        />

                        <div
                            class="show-content"
                            v-html="banner.content.replace('\r\n', '')"
                        ></div>
                    </a>
                </slide>
            </template>

            <template v-else>
                <slide slot="slide-0">
                    <img
                        loading="lazy"
                        class="col-12 no-padding banner-icon"
                        :src="defaultBanner"
                        alt=""
                    />
                </slide>
            </template>
        </carousel-component>
    </div>
</template>

<script>
export default {
    props: ['direction', 'defaultBanner', 'banners'],

    mounted: function() {
        let banners = this.$el.querySelectorAll('img');
        banners.forEach(banner => {
            banner.style.display = 'block';
        });
    }
};
</script>
