<v-products-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false"></x-shop::shimmer.products.carousel>
</v-products-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-products-carousel-template">
        <div class="container mt-20 max-lg:px-[30px] max-sm:mt-[30px]" v-if="! isLoading && products.length">
            <div class="flex justify-between">
                <h3 class="text-[30px] max-sm:text-[25px] font-dmserif" v-text="title"></h3>

                <div class="flex justify-between items-center gap-8">
                    <span
                        class="icon-arrow-left-stylish text-[24px] inline-block cursor-pointer"
                        @click="swipeLeft"
                    >
                    </span>

                    <span
                        class="icon-arrow-right-stylish text-[24px] inline-block cursor-pointer"
                        @click="swipeRight"
                    >
                    </span>
                </div>
            </div>

            <div
                ref="swiperContainer"
                class="flex gap-8 mt-[60px] overflow-auto scroll-smooth scrollbar-hide max-sm:mt-[20px]"
            >
                <x-shop::products.card
                    class="min-w-[291px]"
                    v-for="product in products"
                >
                </x-shop::products.card>
            </div>

            <a
                :href="navigationLink"
                class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center"
                v-if="navigationLink"
            >
                @lang('shop::app.components.products.carousel.view-all')
            </a>
        </div>

        <!-- Product Card Listing -->
        <template v-if="isLoading">
            <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false"></x-shop::shimmer.products.carousel>
        </template>
    </script>

    <script type="module">
        app.component('v-products-carousel', {
            template: '#v-products-carousel-template',

            props: [
                'src',
                'title',
                'navigationLink',
            ],

            data() {
                return {
                    isLoading: true,

                    products: [],

                    offset: 323,
                };
            },

            mounted() {
                this.getProducts();
            },

            methods: {
                getProducts() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;

                            this.products = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                swipeLeft() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft -= this.offset;
                },

                swipeRight() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft += this.offset;
                },
            },
        });
    </script>
@endPushOnce
