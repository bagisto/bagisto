@props(['options'])

<v-carousel>
    <div class="shimmer w-full aspect-[2.743/1]">
    </div>
</v-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-carousel-template">
        <div class="w-full relative m-auto">
            <a
                v-for="(image, index) in images"
                class="fade"
                :href="image.link || '#'"
                ref="slides"
                :key="index"
                aria-label="Image Slide "
            >
                <x-shop::media.images.lazy
                    class="w-full aspect-[2.743/1]"
                    ::src="image.image"
                    ::srcset="image.image + ' 1920w, ' + image.image.replace('storage', 'cache/large') + ' 1280w,' + image.image.replace('storage', 'cache/medium') + ' 1024w, ' + image.image.replace('storage', 'cache/small') + ' 525w'"
                    alt=""
                ></x-shop::media.images.lazy>
            </a>

            <span
                class="icon-arrow-left text-[24px] font-bold text-white w-auto -mt-[22px] p-[12px] absolute top-1/2 left-[10px] bg-[rgba(0,0,0,0.8)] transition-all opacity-30 rounded-full hover:opacity-100 cursor-pointer"
                v-if="images?.length >= 2"
                @click="navigate(currentIndex -= 1)"
            >
            </span>

            <span
                class="icon-arrow-right text-[24px] font-bold text-white w-auto -mt-[22px] p-[12px] absolute top-1/2 right-[10px] bg-[rgba(0,0,0,0.8)] transition-all opacity-30 rounded-full hover:opacity-100 cursor-pointer"
                v-if="images?.length >= 2"
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

                    images: @json($options['images'] ?? []),
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