<v-range-slider {{ $attributes }}></v-range-slider>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-range-slider-template"
    >
        <div>
            <div class="flex items-center gap-4">
                <p class="text-sm">
                    @lang('shop::app.components.range-slider.range')
                </p>

                <p
                    class="text-sm font-semibold"
                    v-text="rangeText"
                >
                </p>
            </div>

            <div class="flex relative justify-center items-center p-2 h-20 w-full mx-auto">
                <div class="relative w-full h-1 bg-gray-200 rounded-2xl">
                    <div
                        ref="progress"
                        class="absolute left-1/4 right-0 h-full bg-navyBlue rounded-xl"
                    >
                    </div>

                    <span>
                        <input
                            ref="minRange"
                            type="range"
                            :value="minRange"
                            class="absolute w-full h-1 appearance-none pointer-events-none bg-transparent outline-none cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:ring [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:ring-navyBlue [&::-moz-range-thumb]:ring [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:bg-white [&::-ms-thumb]:appearance-none [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:rounded-full [&::-ms-thumb]:ring-navyBlue [&::-ms-thumb]:ring"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            aria-label="@lang('shop::app.components.range-slider.min-range')"
                            @input="handle('min')"
                            @change="change"
                        >
                    </span>

                    <span>
                        <input
                            ref="maxRange"
                            type="range"
                            :value="maxRange"
                            class="absolute w-full h-1 appearance-none pointer-events-none bg-transparent outline-none cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:ring [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:ring-navyBlue [&::-moz-range-thumb]:ring [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:bg-white [&::-ms-thumb]:appearance-none [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:rounded-full [&::-ms-thumb]:ring-navyBlue [&::-ms-thumb]:ring"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            aria-label="@lang('shop::app.components.range-slider.max-range')"
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
                'defaultType',
                'defaultAllowedMinRange',
                'defaultAllowedMaxRange',
                'defaultMinRange',
                'defaultMaxRange',
            ],

            data() {
                return {
                    gap: this.defaultAllowedMaxRange * 0.10,

                    supportedTypes: ['integer', 'float', 'price'],

                    allowedMinRange: parseInt(this.defaultAllowedMinRange ?? 0),

                    allowedMaxRange: parseInt(this.defaultAllowedMaxRange ?? 100),

                    minRange: parseInt(this.defaultMinRange ?? 0),

                    maxRange: parseInt(this.defaultMaxRange ?? 100),
                };
            },

            computed: {
                rangeText() {
                    let { formattedMinRange, formattedMaxRange } = this.getFormattedData();

                    return `${formattedMinRange} - ${formattedMaxRange}`;
                },
            },

            mounted() {
                this.handleProgressBar();
            },

            methods: {
                getData() {
                    return {
                        allowedMinRange: this.allowedMinRange,
                        allowedMaxRange: this.allowedMaxRange,
                        minRange: this.minRange,
                        maxRange: this.maxRange,
                    };
                },

                getFormattedData() {
                    /**
                     * If someone is passing invalid props, this case will check first if they are valid, then continue.
                     */
                     if (this.isTypeSupported()) {
                        switch (this.defaultType) {
                            case 'price':
                                return {
                                    formattedAllowedMinRange: this.$shop.formatPrice(this.allowedMinRange),
                                    formattedAllowedMaxRange: this.$shop.formatPrice(this.allowedMaxRange),
                                    formattedMinRange: this.$shop.formatPrice(this.minRange),
                                    formattedMaxRange: this.$shop.formatPrice(this.maxRange),
                                };

                            case 'float':
                                return {
                                    formattedAllowedMinRange: parseFloat(this.allowedMinRange).toFixed(2),
                                    formattedAllowedMaxRange: parseFloat(this.allowedMaxRange).toFixed(2),
                                    formattedMinRange: parseFloat(this.minRange).toFixed(2),
                                    formattedMaxRange: parseFloat(this.maxRange).toFixed(2),
                                };

                            default:
                                return {
                                    formattedAllowedMinRange: this.allowedMinRange,
                                    formattedAllowedMaxRange: this.allowedMaxRange,
                                    formattedMinRange: this.minRange,
                                    formattedMaxRange: this.maxRange,
                                };
                        }
                    }

                    /**
                     * Otherwise, we will load the default formatting.
                     */
                    return {
                        formattedAllowedMinRange: this.allowedMinRange,
                        formattedAllowedMaxRange: this.allowedMaxRange,
                        formattedMinRange: this.minRange,
                        formattedMaxRange: this.maxRange,
                    };
                },

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
                    const direction = document.dir == 'ltr' ? 'left' : 'right';

                    this.$refs.progress.style[direction] = (this.minRange / this.allowedMaxRange) * 100 + '%';

                    this.$refs.progress.style[direction == 'left' ? 'right' : 'left'] = 100 - (this.maxRange / this.allowedMaxRange) * 100 + '%';
                },

                change() {
                    this.$emit('change-range', {
                        ...this.getData(),
                        ...this.getFormattedData(),
                    });
                },

                isTypeSupported() {
                    return this.supportedTypes.includes(this.defaultType);
                },
            },
        });
    </script>
@endPushOnce
