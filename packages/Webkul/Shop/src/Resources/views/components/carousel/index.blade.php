@props(['options'])

<v-carousel :images="{{ json_encode($options['images'] ?? []) }}">
    <div class="overflow-hidden">
        <div class="shimmer max-h-screen w-screen aspect-[2.743/1]"></div>
    </div>
</v-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-carousel-template">
        <div class="flex w-full relative m-auto overflow-hidden">
            <!-- Slider -->
            <div 
                class="inline-flex translate-x-0 will-change-transform transition-transform duration-700 ease-out cursor-pointer"
                ref="sliderContainer"
            >
                <div
                    class="max-h-screen w-screen bg-no-repeat bg-cover"
                    v-for="(image, index) in images"
                    @click="visitLink(image)"
                    ref="slide"
                >
                    <x-shop::media.images.lazy
                        class="w-full max-w-full max-h-full transition-transform duration-300 ease-in-out select-none aspect-[2.743/1]"
                        ::lazy="false"
                        ::src="image.image"
                        ::srcset="image.image + ' 1920w, ' + image.image.replace('storage', 'cache/large') + ' 1280w,' + image.image.replace('storage', 'cache/medium') + ' 1024w, ' + image.image.replace('storage', 'cache/small') + ' 525w'"
                        ::alt="image?.title"
                    />
                </div>
            </div>

            <!-- Navigation -->
            <span
                class="icon-arrow-left text-2xl font-bold text-white w-auto -mt-[22px] p-3 absolute top-1/2 left-2.5 bg-black/80 transition-all opacity-30 rounded-full"
                :class="{
                    'cursor-not-allowed': direction == 'ltr' && currentIndex == 0,
                    'cursor-pointer hover:opacity-100': direction == 'ltr' ? currentIndex > 0 : currentIndex <= 0
                }"
                role="button"
                aria-label="@lang('shop::components.carousel.previous')"
                tabindex="0"
                v-if="images?.length >= 2"
                @click="navigate('prev')"
            >
            </span>

            <span
                class="icon-arrow-right text-2xl font-bold text-white w-auto -mt-[22px] p-3 absolute top-1/2 right-2.5 bg-black/80 transition-all opacity-30 rounded-full"
                :class="{
                    'cursor-not-allowed': direction == 'rtl' && currentIndex == 0,
                    'cursor-pointer hover:opacity-100': direction == 'rtl' ? currentIndex < 0 : currentIndex >= 0
                }"
                role="button"
                aria-label="@lang('shop::components.carousel.next')"
                tabindex="0"
                v-if="images?.length >= 2"
                @click="navigate('next')"
            >
            </span>

            <!-- Pagination -->
            <div class="absolute bottom-5 left-0 flex justify-center w-full">
                <div
                    v-for="(image, index) in images"
                    class="w-3 h-3 rounded-full mx-1 cursor-pointer"
                    :class="{ 'bg-navyBlue': index === Math.abs(currentIndex), 'opacity-30 bg-gray-500': index !== Math.abs(currentIndex) }"
                    @click="navigateByPagination(index)"
                >
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component("v-carousel", {
            template: '#v-carousel-template',

            props: ['images'],

            data() {
                return {
                    isDragging: false,
                    startPos: 0,
                    currentTranslate: 0,
                    prevTranslate: 0,
                    animationID: 0,
                    currentIndex: 0,
                    slider: '',
                    slides: [],
                    autoPlayInterval: null,
                    direction: 'ltr',
                    startFrom: 1,
                };
            },

            mounted() {
                this.slider = this.$refs.sliderContainer;

                if (
                    this.$refs.slide
                    && typeof this.$refs.slide[Symbol.iterator] === 'function'
                ) {
                    this.slides = Array.from(this.$refs.slide);
                }

                this.init();

                this.play();
            },

            methods: {
                init() {
                    this.direction = document.dir;

                    if (this.direction == 'rtl') {
                        this.startFrom = -1;
                    }

                    this.slides.forEach((slide, index) => {
                        slide.querySelector('img')?.addEventListener('dragstart', (e) => e.preventDefault());

                        slide.addEventListener('pointerdown', this.pointerDown(index));

                        slide.addEventListener('pointerup', this.pointerUp);

                        slide.addEventListener('pointerleave', this.pointerUp);

                        slide.addEventListener('pointermove', this.pointerMove);
                    });

                    window.addEventListener('resize', this.setPositionByIndex);
                },

                pointerDown(index) {
                    return (event) => {
                        this.startPos = event.clientX;

                        this.isDragging = true;

                        this.animationID = requestAnimationFrame(this.animation);
                    };
                },

                pointerMove(event) {
                    if (! this.isDragging) {
                        return;
                    }

                    const currentPosition = event.clientX;

                    this.currentTranslate = this.prevTranslate + currentPosition - this.startPos;
                },

                pointerUp(event) {
                    clearInterval(this.autoPlayInterval);

                    cancelAnimationFrame(this.animationID);

                    this.isDragging = false;

                    const movedBy = this.currentTranslate - this.prevTranslate;

                    if (this.direction == 'ltr') {
                        if (
                            movedBy < -100
                            && this.currentIndex < this.slides.length - 1
                        ) {
                            this.currentIndex += 1;
                        }

                        if (
                            movedBy > 100
                            && this.currentIndex > 0
                        ) {
                            this.currentIndex -= 1;
                        }
                    } else {
                        if (
                            movedBy > 100
                            && this.currentIndex < this.slides.length - 1
                        ) {
                            if (Math.abs(this.currentIndex) != this.slides.length - 1) {
                                this.currentIndex -= 1;
                            }
                        }

                        if (
                            movedBy < -100
                            && this.currentIndex < 0
                        ) {
                            this.currentIndex += 1;
                        }
                    }

                    this.setPositionByIndex();

                    this.play();
                },

                animation() {
                    this.setSliderPosition();

                    if (this.isDragging) {
                        requestAnimationFrame(this.animation);
                    }
                },

                setPositionByIndex() {
                    this.currentTranslate = this.currentIndex * -window.innerWidth;

                    this.prevTranslate = this.currentTranslate;

                    this.setSliderPosition();
                },

                setSliderPosition() {
                    this.slider.style.transform = `translateX(${this.currentTranslate}px)`
                },

                visitLink(image) {
                    if (! image.link) {
                        return;
                    }

                    window.location.href = image.link;
                },

                navigate(type) {
                    clearInterval(this.autoPlayInterval);

                    if (this.direction === 'rtl') {
                        type === 'next' ? this.prev() : this.next();
                    } else {
                        type === 'next' ? this.next() : this.prev();
                    }

                    this.setPositionByIndex();

                    this.play();
                },

                next() {
                    this.currentIndex = (this.currentIndex + this.startFrom) % this.images.length;
                },

                prev() {
                    this.currentIndex = this.direction == 'ltr'
                        ? this.currentIndex > 0 ? this.currentIndex - 1 : 0
                        : this.currentIndex < 0 ? this.currentIndex + 1 : 0;
                },

                navigateByPagination(index) {
                    this.direction == 'rtl' ? index = -index : '';

                    clearInterval(this.autoPlayInterval);

                    this.currentIndex = index;

                    this.setPositionByIndex();

                    this.play();
                },

                play() {
                    clearInterval(this.autoPlayInterval);

                    this.autoPlayInterval = setInterval(() => {
                        this.currentIndex = (this.currentIndex + this.startFrom) % this.images.length;

                        this.setPositionByIndex();
                    }, 5000);
                },
            },
        });
    </script>
@endpushOnce