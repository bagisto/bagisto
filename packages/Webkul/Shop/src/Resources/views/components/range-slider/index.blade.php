<v-range-slider {{ $attributes }}></v-range-slider>

@pushOnce('styles')
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .range-slider {
            position: relative;
            width: 100%;
            height: 10px;
            border-radius: 15px;
            background: transparent linear-gradient(90deg, #5f822d 0%, #a5af33 100%) 0% 0% no-repeat padding-box;
        }

        .range-slider .progress {
            position: absolute;
            left: 25%;
            right: 0%;
            height: 100%;
            border-radius: 15px;
            background: transparent linear-gradient(90deg, #e4e7c1 0%, #d39892 100%) 0% 0% no-repeat padding-box;
        }

        .range-slider input[type="range"] {
            position: absolute;
            width: 100%;
            height: 10px;
            -webkit-appearance: none;
            pointer-events: none;
            background: none;
            outline: none;
        }

        .range-slider .range-min::-webkit-slider-thumb {
            pointer-events: auto;
            -webkit-appearance: none;
            width: 61px;
            height: 35px;
            background: #fcfcfc 0% 0% no-repeat padding-box;
            border: 2px solid #8b9e30;
            border-radius: 20px;
            opacity: 1;
        }

        .range-slider .range-max::-webkit-slider-thumb {
            pointer-events: auto;
            -webkit-appearance: none;
            width: 61px;
            height: 35px;
            background: #fcfcfc 0% 0% no-repeat padding-box;
            border: 2px solid #a22a2a;
            border-radius: 20px;
            opacity: 1;
        }
    </style>
@endPushOnce

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

            <div class="flex relative justify-center items-center h-20 w-full mx-auto">
                <div class="range-slider">
                    <div
                        ref="progress"
                        class="progress"
                    >
                    </div>

                    <span class="range-min-wrapper">
                        <input
                            ref="minRange"
                            type="range"
                            :value="minRange"
                            class="range-min"
                            :min="allowedMinRange"
                            :max="allowedMaxRange"
                            @input="handle('min')"
                            @change="change"
                        >
                    </span>

                    <span class="range-max-wrapper">
                        <input
                            ref="maxRange"
                            type="range"
                            :value="maxRange"
                            class="range-max"
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
