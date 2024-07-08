@props(['options'])

<v-product-carousel {{ $attributes }}>
    <x-shop::shimmer.products.gallery />
</v-product-carousel>

@pushOnce('scripts')
    <script 
        type="text/x-template"
        id="v-product-carousel-template"
    >
        <div class="relative m-auto flex w-full overflow-hidden">
            <!-- Slider -->
            <div 
                class="inline-flex translate-x-0 cursor-pointer transition-transform duration-700 ease-out will-change-transform" 
                ref="sliderContainer"
            >
                <div 
                    class="grid max-h-screen w-screen content-center bg-cover bg-no-repeat" 
                    v-for="(media, index) in options" 
                    ref="slide"
                >
                    <template v-if="media.type == 'videos'">
                        <video 
                            controls 
                            width="100%" 
                            :alt="media.video_url" 
                            :key="media.video_url"
                        >
                            <source 
                                :src="media.video_url" 
                                type="video/mp4"
                            />
                        </video>
                    </template>
                    
                    <template v-else>
                        <img 
                            class="aspect-square max-h-full w-full max-w-full select-none transition-transform duration-300 ease-in-out" 
                            :src="media.large_image_url" 
                            :alt="media.large_image_url" 
                        />
                    </template>
                </div>
            </div>

            <!-- Pagination -->
            <div class="absolute bottom-3 left-0 flex w-full justify-center max-sm:bottom-2.5">
                <div 
                    v-for="(media, index) in options" 
                    class="mx-1 h-1.5 w-1.5 cursor-pointer rounded-full" 
                    :class="{ 'bg-navyBlue': index === Math.abs(currentIndex), 'opacity-30 bg-gray-500': index !== Math.abs(currentIndex) }"
                    role="button"
                >
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component("v-product-carousel", {
            template: '#v-product-carousel-template',
            
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
                    viewportWidth: window.innerWidth,
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
                
                window.addEventListener('resize', this.onResize);
            },
            
            watch: {
                options: function() {
                    this.slider = this.$refs.sliderContainer;

                    if (
                        this.$refs.slide 
                        && typeof this.$refs.slide[Symbol.iterator] === 'function'
                    ) {
                        this.slides = Array.from(this.$refs.slide);
                    }

                    this.resetIndex();
                    
                    this.init();
                }
            },
            
            methods: {
                init() {
                    this.direction = document.dir;

                    if (this.direction === 'rtl') {
                        this.startFrom = -1;
                    }

                    this.slides.forEach((slide, index) => {
                        slide.querySelector('img')?.addEventListener('dragstart', (e) => e.preventDefault());

                        slide.addEventListener('touchstart', this.handleDragStart);
                        
                        slide.addEventListener('touchend', this.handleDragEnd);
                        
                        slide.addEventListener('touchmove', this.handleDrag, { passive: true });
                    });

                    this.setPositionByIndex();
                },
                
                resetIndex() {
                    if (this.currentIndex >= this.slides.length) {
                        
                        this.currentIndex = this.slides.length - 1;
                    }
                    
                    this.setPositionByIndex();
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

                    if (this.direction === 'ltr') {
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
                            if (Math.abs(this.currentIndex) !== this.slides.length - 1) {
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
                },
                
                animation() {
                    this.setSliderPosition();
                    
                    if (this.isDragging) {
                        requestAnimationFrame(this.animation);
                    }
                },
                
                setPositionByIndex() {
                    this.currentTranslate = this.currentIndex * -this.viewportWidth;
                    
                    this.prevTranslate = this.currentTranslate;
                    
                    this.setSliderPosition();
                },
                
                setSliderPosition() {
                    this.slider.style.transform = `translateX(${this.currentTranslate}px)`;
                },
                
                onResize() {
                    this.viewportWidth = window.innerWidth;
                    this.setPositionByIndex();
                },
            },
        });
    </script>
@endpushOnce