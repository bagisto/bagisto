<v-categories-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.categories.carousel
        :count="7"
        :navigation-link="$navigationLink ?? false"
    ></x-shop::shimmer.categories.carousel>
</v-categories-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-categories-carousel-template">
        <div class="container mt-[60px] max-lg:px-[30px] max-sm:mt-[20px]" v-if="! isLoading && categories?.length">
            <div class="bs-item-carousal-wrapper relative">
                <div
                    ref="swiperContainer"
                    class="flex gap-10 overflow-auto scroll-smooth scrollbar-hide"
                >
                    <div
                        class="grid grid-cols-1 justify-items-center gap-[15px] font-medium min-w-[120px]"
                        v-for="category in categories"
                    >
                        <a
                            :href="category.url_path"
                            class=""
                        >
                            <div class="bg-[#F5F5F5] rounded-full w-[110px] h-[110px]">
                                <img
                                    class="w-[110px] h-[110px] rounded-full"
                                    :src="category.images.base_url"
                                    v-if="category?.images?.base_url"
                                />
                            </div>
                        </a>

                        <a
                            :href="category.url_path"
                            class=""
                        >
                            <p
                                class="text-center text-black text-[20px] font-medium"
                                v-text="category.name"
                            >
                            </p>
                        </a>
                    </div>
                </div>

                <span
                    class="bs-carousal-next flex border border-black items-center justify-center rounded-full w-[50px] h-[50px] bg-white absolute top-[37px] -left-[41px] cursor-pointer transition icon-arrow-left-stylish text-[25px] hover:bg-black hover:text-white max-lg:-left-[29px]"
                    @click="swipeLeft"
                >
                </span>

                <span
                    class="bs-carousal-prev flex border border-black items-center justify-center rounded-full w-[50px] h-[50px] bg-white absolute top-[37px] -right-[22px] cursor-pointer transition icon-arrow-right-stylish text-[25px] hover:bg-black hover:text-white max-lg:-right-[29px]"
                    @click="swipeRight"
                >
                </span>
            </div>
        </div>

        <!-- category carousel shimmer -->
        <template v-if="isLoading">
            <x-shop::shimmer.categories.carousel
                :count="7"
                :navigation-link="$navigationLink ?? false"
            >
            </x-shop::shimmer.categories.carousel>
        </template>
    </script>

    <script type="module">
        app.component('v-categories-carousel', {
            template: '#v-categories-carousel-template',

            props: [
                'src',
                'title',
                'navigationLink',
            ],

            data() {
                return {
                    isLoading: true,

                    categories: [],

                    offset: 720,
                };
            },

            mounted() {
                this.getCategories();
            },

            methods: {
                getCategories() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;

                            this.categories = response.data.data;
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
