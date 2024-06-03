@props(['options'])

<v-card-carousel {{ $attributes }}></v-card-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-card-carousel-template"
    >
        <div class="relative m-auto flex w-full overflow-hidden">
            <!-- Slider -->
            <div 
                class="inline-flex translate-x-0 cursor-pointer transition-transform duration-700 ease-out will-change-transform"
                ref="sliderContainer"
            >
                <div
                    class="max-h-screen w-screen bg-cover bg-no-repeat"
                    v-for="(image, index) in options"
                    ref="slide"
                >
                    <x-shop::media.images.lazy
                        class="max-h-full w-full max-w-full select-none transition-transform duration-300 ease-in-out"
                        ::lazy="false"
                        ::src="image.large_image_url"
                        ::srcset="image.large_image_url + ' 1920w, ' + image.medium_image_url + ' 1024w, ' + image.small_image_url + ' 525w'"
                        ::alt="image?.title"
                    />
                </div>
            </div>

            <!-- Pagination -->
            <div class="absolute bottom-5 left-0 flex w-full justify-center max-sm:bottom-2.5">
                <div
                    v-for="(image, index) in options"
                    class="mx-1 h-3 w-3 cursor-pointer rounded-full"
                    :class="{ 'bg-navyBlue': index === Math.abs(currentIndex), 'opacity-30 bg-gray-500': index !== Math.abs(currentIndex) }"
                >
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component("v-card-carousel", {
            template: '#v-card-carousel-template',

            props: ['options'],

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

                        slide.addEventListener('touchstart', this.handleDragStart);

                        slide.addEventListener('touchend', this.handleDragEnd);

                        slide.addEventListener('touchmove', this.handleDrag, { passive: true });
                    });

                    window.addEventListener('resize', this.setPositionByIndex);
                },

                handleDragStart(event) {
                    this.startPos = event.type === 'mousedown' ? event.clientX : event.touches[0].clientX;

                    this.isDragging = true;

                    this.animationID = requestAnimationFrame(this.animation);
                },

                handleDrag(event) {
                    if (! this.isDragging) {
                        return;
                    }

                    const currentPosition = event.type === 'mousemove' ? event.clientX : event.touches[0].clientX;

                    this.currentTranslate = this.prevTranslate + currentPosition - this.startPos;
                },

                handleDragEnd(event) {
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
                    this.currentIndex = (this.currentIndex + this.startFrom) % this.options.length;
                },

                prev() {
                    this.currentIndex = this.direction == 'ltr'
                        ? this.currentIndex > 0 ? this.currentIndex - 1 : 0
                        : this.currentIndex < 0 ? this.currentIndex + 1 : 0;
                },

                play() {
                    clearInterval(this.autoPlayInterval);

                    this.autoPlayInterval = setInterval(() => {
                        this.currentIndex = (this.currentIndex + this.startFrom) % this.options.length;

                        this.setPositionByIndex();
                    }, 5000);
                },
            },
        });
    </script>
@endpushOnce