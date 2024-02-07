<v-categories-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.categories.carousel
        :count="8"
        :navigation-link="$navigationLink ?? false"
    />
</v-categories-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-categories-carousel-template"
    >
        <div
            class="container mt-14 max-lg:px-8 max-sm:mt-5"
            v-if="! isLoading && categories?.length"
        >
            <div class="relative">
                <div
                    ref="swiperContainer"
                    class="flex gap-10 overflow-auto scroll-smooth scrollbar-hide max-sm:gap-4"
                >
                    <div
                        class="grid grid-cols-1 gap-4 justify-items-center min-w-[120px] max-w-[120px] font-medium"
                        v-for="category in categories"
                    >
                        <a
                            :href="category.slug"
                            class="w-[110px] h-[110px] bg-[#F5F5F5] rounded-full"
                            :aria-label="category.name"
                        >
                            <template v-if="category.images.logo_url">
                                <x-shop::media.images.lazy
                                    ::src="category.images.logo_url"
                                    width="110"
                                    height="110"
                                    class="w-[110px] h-[110px] rounded-full"
                                    ::alt="category.name"
                                />
                            </template>
                        </a>

                        <a
                            :href="category.slug"
                            class=""
                        >
                            <p
                                class="text-center text-black text-lg max-sm:font-normal"
                                v-text="category.name"
                            >
                            </p>
                        </a>
                    </div>
                </div>

                <span
                    class="flex items-center justify-center absolute top-9 -left-10 w-[50px] h-[50px] bg-white border border-black rounded-full transition icon-arrow-left-stylish text-2xl hover:bg-black hover:text-white max-lg:-left-7 cursor-pointer"
                    role="button"
                    aria-label="@lang('shop::components.carousel.previous')"
                    tabindex="0"
                    @click="swipeLeft"
                >
                </span>

                <span
                    class="flex items-center justify-center absolute top-9 -right-6 w-[50px] h-[50px] bg-white border border-black rounded-full transition icon-arrow-right-stylish text-2xl hover:bg-black hover:text-white max-lg:-right-7 cursor-pointer"
                    role="button"
                    aria-label="@lang('shop::components.carousel.next')"
                    tabindex="0"
                    @click="swipeRight"
                >
                </span>
            </div>
        </div>

        <!-- Category Carousel Shimmer -->
        <template v-if="isLoading">
            <x-shop::shimmer.categories.carousel
                :count="8"
                :navigation-link="$navigationLink ?? false"
            />
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

                    offset: 323,
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
