<v-range-slider {{ $attributes }}></v-range-slider>

@pushOnce('scripts')
    <script type="text/x-template" id="v-range-slider-template">
        <div>
            <div class="flex items-center gap-[15px]">
                <p class="text-[14px]">
                    <!-- @translations -->
                    @lang('Range:')
                </p>

                <p
                    class="text-[14px] font-semibold"
                    v-text="`${minRange} - ${maxRange}`"
                >
                </p>
            </div>

            <div class="flex relative justify-center items-center p-2 h-20 w-full mx-auto">
                <div class="relative w-full h-[4px] rounded-2xl bg-gray-200">
                    <div
                        ref="progress"
                        class="absolute left-1/4 right-0 h-full rounded-xl bg-navyBlue"
                    >
                    </div>

                    <span>
                        <input
                            ref="minRange"
                            type="range"
                            :value="minRange"
                            class="absolute w-full h-[4px] appearance-none pointer-events-none bg-transparent outline-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:ring [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:ring-navyBlue [&::-moz-range-thumb]:ring [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:bg-white [&::-ms-thumb]:appearance-none [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:rounded-full [&::-ms-thumb]:ring-navyBlue [&::-ms-thumb]:ring"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            @input="handle('min')"
                            @change="change"
                        >
                    </span>

                    <span>
                        <input
                            ref="maxRange"
                            type="range"
                            :value="maxRange"
                            class="absolute w-full h-[4px] appearance-none pointer-events-none bg-transparent outline-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:ring [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:ring-navyBlue [&::-moz-range-thumb]:ring [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:bg-white [&::-ms-thumb]:appearance-none [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:rounded-full [&::-ms-thumb]:ring-navyBlue [&::-ms-thumb]:ring"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            @input="handle('max')"
                            @change="change"
                        >
                    </span>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-range-slider', {
            template: '#v-range-slider-template',

            props: [
                'defaultAllowedMinRange',
                'defaultAllowedMaxRange',
                'defaultMinRange',
                'defaultMaxRange',
            ],

            data() {
                return {
                    gap: 0.1,

                    allowedMinRange: parseInt(this.defaultAllowedMinRange ?? 0),

                    allowedMaxRange: parseInt(this.defaultAllowedMaxRange ?? 100),

                    minRange: parseInt(this.defaultMinRange ?? 0),

                    maxRange: parseInt(this.defaultMaxRange ?? 100),
                };
            },

            mounted() {
                this.handleProgressBar();
            },

            methods: {
                handle(rangeType) {
                    this.minRange = parseInt(this.$refs.minRange.value);

                    this.maxRange = parseInt(this.$refs.maxRange.value);

                    if (this.maxRange - this.minRange < this.gap) {
                        if (rangeType === 'min') {
                            this.minRange = this.maxRange - this.gap;
                        } else {
                            this.maxRange = this.minRange + this.gap;
                        }
                    } else {
                        this.handleProgressBar();
                    }
                },

                handleProgressBar() {
                    this.$refs.progress.style.left = (this.minRange / this.allowedMaxRange) * 100 + '%';

                    this.$refs.progress.style.right = 100 - (this.maxRange / this.allowedMaxRange) * 100 + '%';
                },

                change() {
                    this.$emit('change-range', {
                        allowedMinRange: this.allowedMinRange,
                        allowedMaxRange: this.allowedMaxRange,
                        minRange: this.minRange,
                        maxRange: this.maxRange,
                    });
                },
            },
        });
    </script>
@endPushOnce
