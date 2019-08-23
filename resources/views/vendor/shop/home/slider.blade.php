<section class="slider-block">
    {{-- <image-slider :slides='@json($sliderData)' public_path="{{ url()->to('/') }}"></image-slider> --}}

    <custom></custom>
</section>

@push('scripts')
    <script type="text/x-template" id="custom">
        <div class="slider-content" v-if="images.length > 0">
            <ul class="slider-images">
                <li v-for="(image, index) in images" :key="index" v-bind:class="{'show': index == currentIndex}">
                    <a :href="public_path + '/' + content[index].url_key">
                        <img class="slider-item" :src="image" />

                        <div class="show-content" v-bind:class="{'show': index==currentIndex}" :key="index" v-html="content[index].content"></div>
                    </a>
                </li>
            </ul>

            <div class="slider-control" v-if="images_loaded">
                <span class="icon dark-left-icon slider-left" @click="changeIndexLeft"></span>
                <span class="icon light-right-icon slider-right" @click="changeIndexRight"></span>
            </div>
        </div>

        {{-- <div class="slider-content" v-if="images.length>0">
            <ul class="slider-images">
                <li v-for="(image, index) in images" :key="index" v-bind:class="{'show': index==currentIndex}">
                    <img class="slider-item" :src="image" />

                    <div class="show-content" v-bind:class="{'show': index==currentIndex}" :key="index" v-html="content[index]"></div>
                </li>
            </ul>

            <div class="slider-control" v-if="images_loaded">
                <span class="icon dark-left-icon slider-left" @click="changeIndexLeft"></span>
                <span class="icon light-right-icon slider-right" @click="changeIndexRight"></span>
            </div>

        </div> --}}
    </script>

    <script>
        Vue.component('custom', {
            template: '#custom',

            data: function () {
                return {
                    data: [],
                    images: [],
                    public_path: "{{ url()->to('/') }}",
                    currentIndex: -1,
                    content: [],
                    current: false,
                    images_loaded: false
                }
            },

            mounted() {
                this.data = @json($sliderData);

                for (i in this.data) {
                    this.images.push(this.public_path + '/storage/' + this.data[i].path);

                    this.content.push({'content': this.data[i].content, 'url_key': this.data[i].url_key});
                }

                this.currentIndex = 0;

                if(this.images.length == 0) {
                    this.images.push = "vendor/webkul/shop/assets/images/banner.png";
                } else {
                    this.images_loaded = true;
                }
            },

            methods: {
                changeIndexLeft: function() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    } else if(this.currentIndex == 0) {
                        this.currentIndex = this.images.length-1;
                    }
                },

                changeIndexRight: function() {
                    if(this.currentIndex < this.images.length-1) {
                        this.currentIndex++;
                    } else if(this.currentIndex == this.images.length-1) {
                        this.currentIndex = 0;
                    }
                }
            }
        });
    </script>
@endpush