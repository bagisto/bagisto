<v-range-slider {{ $attributes }}></v-range-slider>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-range-slider-template"
    >
        <div>
            <div class="flex items-center gap-4">
                <p class="text-base max-sm:text-sm">
                    @lang('shop::app.components.range-slider.range')
                </p>

                <p class="text-base font-semibold max-sm:text-sm">
                    @{{ rangeText }}
                </p>
            </div>

            <div class="relative mx-auto flex h-20 w-full items-center justify-center p-2">
                <div class="relative h-1 w-full rounded-2xl bg-gray-200">
                    <div
                        ref="progress"
                        class="absolute left-1/4 right-0 h-full rounded-xl bg-navyBlue"
                    >
                    </div>

                    <span>
                        <input
                            :step="allowedMaxRange - Math.floor(allowedMaxRange) > 0 ? 0.01 : 1"
                            ref="minRange"
                            type="range"
                            :value="minRange"
                            class="pointer-events-none absolute h-1 w-full cursor-pointer appearance-none bg-transparent outline-none [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:ring [&::-moz-range-thumb]:ring-navyBlue [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:appearance-none [&::-ms-thumb]:rounded-full [&::-ms-thumb]:bg-white [&::-ms-thumb]:ring [&::-ms-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:ring [&::-webkit-slider-thumb]:ring-navyBlue"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            aria-label="@lang('shop::app.components.range-slider.min-range')"
                            @input="handle('min')"
                            @change="change"
                        >
                    </span>

                    <span>
                        <input
                            :step="allowedMaxRange - Math.floor(allowedMaxRange) > 0 ? 0.01 : 1"
                            ref="maxRange"
                            type="range"
                            :value="maxRange"
                            class="pointer-events-none absolute h-1 w-full cursor-pointer appearance-none bg-transparent outline-none [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:ring [&::-moz-range-thumb]:ring-navyBlue [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:appearance-none [&::-ms-thumb]:rounded-full [&::-ms-thumb]:bg-white [&::-ms-thumb]:ring [&::-ms-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:ring [&::-webkit-slider-thumb]:ring-navyBlue"
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

                    allowedMinRange: parseFloat(this.defaultAllowedMinRange ?? 0),

                    allowedMaxRange: parseFloat(this.defaultAllowedMaxRange ?? 100),

                    minRange: parseFloat(this.defaultMinRange ?? 0),

                    maxRange: parseFloat(this.defaultMaxRange ?? 100),
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
                    this.minRange = parseFloat(this.$refs.minRange.value);

                    this.maxRange = parseFloat(this.$refs.maxRange.value);

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
