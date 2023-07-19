@props(['options'])

<v-carousel>
    <div class="shimmer w-full aspect-[2.743/1]"></div>
</v-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-carousel-template">
        <div class="w-full relative m-auto group">
            <div
                class="fade"
                v-for="(image, index) in images"
                ref="slides"
                :key="index"
            >
                <x-shop::shimmer.image
                    class="w-full aspect-[2.743/1]"
                    ::src="image"
                ></x-shop::shimmer.image>
            </div>

            <span
                class="invisible icon-arrow-left text-[24px] font-bold text-white w-auto -mt-[22px] p-[16px] pl-[10px] absolute top-1/2 bg-[rgba(0,0,0,0.8)] rounded-r-md cursor-pointer group-hover:visible"
                @click="navigate(currentIndex -= 1)"
            >
            </span>

            <span
                class="invisible icon-arrow-right text-[24px] font-bold text-white w-auto -mt-[22px] p-[16px] pr-[10px] absolute top-1/2 right-0 bg-[rgba(0,0,0,0.8)] rounded-l-md cursor-pointer group-hover:visible"
                @click="navigate(currentIndex += 1)"
            >
            </span>
        </div>
    </script>

    <script type="module">
        app.component("v-carousel", {
            template: '#v-carousel-template',

            data() {
                return {
                    currentIndex: 1,

                    images: @json($options['images']),
                };
            },

            mounted() {
                this.navigate(this.currentIndex);

                this.play();
            },

            methods: {
                navigate(index) {
                    if (index > this.images.length) {
                        this.currentIndex = 1;
                    }

                    if (index < 1) {
                        this.currentIndex = this.images.length;
                    }

                    let slides = this.$refs.slides;

                    for (let i = 0; i < slides.length; i++) {
                        if (i == this.currentIndex - 1) {
                            continue;
                        }
                        
                        slides[i].style.display = 'none';
                    }

                    slides[this.currentIndex - 1].style.display = 'block';
                },

                play() {
                    let self = this;

                    setInterval(() => {
                        this.navigate(this.currentIndex += 1);
                    }, 5000);
                }
            }
        });
    </script>

    <style>
        .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @-webkit-keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        @keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }
    </style>
@endpushOnce