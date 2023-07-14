@props(['options'])

<v-slider></v-slider>

@pushOnce('scripts')
    <script type="text/x-template" id="v-slider-template">
        <div 
            class="relative w-full h-[50vh] md:flex md:h-[500px]"
            @mouseover="showNavigation = true"
            @mouseleave="showNavigation = false"
        >
            <div class="relative overflow-hidden w-full h-full">
                <div 
                    v-for="(image, index) in options.images" 
                    :class="getSliderStyles(index)"
                >
                    <img 
                        :src="image"
                        class="h-auto object-cover max-w-full"
                    >
                </div>
            </div>
        
            <span
                class="bs-carousal-next flex border border-black items-center justify-center rounded-full w-[50px] h-[50px] bg-white absolute top-[221px] left-[21px] md:left-[29px] cursor-pointer transition icon-arrow-left-stylish text-[25px] hover:bg-black hover:text-white"
                :class="{ 'hidden': ! showNavigation }"
                @click="previousSlide"
            >
            </span>
        
            <span
                class="bs-carousal-prev flex border border-black items-center justify-center rounded-full w-[50px] h-[50px] bg-white absolute top-[221px] right-[23px] md:right-[29px] cursor-pointer transition icon-arrow-right-stylish text-[25px] hover:bg-black hover:text-white"
                :class="{ 'hidden': ! showNavigation }"
                @click="nextSlide"
            >
            </span>
        </div>
    </script>

    <script type="module">
        app.component("v-slider", {
            template: '#v-slider-template',

            data() {
                return {
                    currentIndex: 0,

                    showNavigation: false,

                    options: @json($options),
                };
            },

            created() {
                this.play();
            },

            methods: {
                previousSlide() {
                    this.currentIndex = (this.currentIndex - 1 + this.options.images.length) % this.options.images.length;
                },

                nextSlide() {
                    this.currentIndex = (this.currentIndex + 1) % this.options.images.length;
                },

                getSliderStyles(index) {
                    return {
                        'opacity-100': index === this.currentIndex,
                        'opacity-0': index !== this.currentIndex,
                        'absolute': true,
                        'h-full': true,
                        'w-full': true,
                        'transition-opacity': true,
                        'duration-500': true
                    };
                },

                play() {
                    setInterval(() => {
                        this.nextSlide();
                    }, 5000);
                }
            }
        });
    </script>
@endpushOnce