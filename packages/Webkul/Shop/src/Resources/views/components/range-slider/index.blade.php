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
            height: 4px;
            border-radius: 15px;
            background: rgb(245 245 245 / var(--tw-bg-opacity));
        }

        .range-slider .progress {
            position: absolute;
            left: 25%;
            right: 0%;
            height: 100%;
            border-radius: 15px;
            background: rgb(6 12 59 / var(--tw-bg-opacity));
        }

        .range-slider input[type="range"] {
            position: absolute;
            width: 100%;
            height: 4px;
            -webkit-appearance: none;
            pointer-events: none;
            background: none;
            outline: none;
        }

        .range-slider input[type="range"]::-webkit-slider-thumb {
            pointer-events: auto;
            -webkit-appearance: none;
            width: 25px;
            height: 25px;
            background: #fcfcfc 0% 0% no-repeat padding-box;
            border: 4px solid rgb(6 12 59 / var(--tw-bg-opacity));
            border-radius: 20px;
            opacity: 1;
        }

        .range-slider input[type="range"]::-moz-range-thumb {
            pointer-events: auto;
            -webkit-appearance: none;
            width: 25px;
            height: 25px;
            background: #fcfcfc 0% 0% no-repeat padding-box;
            border: 4px solid rgb(6 12 59 / var(--tw-bg-opacity));
            border-radius: 20px;
            opacity: 1;
        }

        .range-slider input[type="range"]::-ms-thumb {
            pointer-events: auto;
            -webkit-appearance: none;
            width: 25px;
            height: 25px;
            background: #fcfcfc 0% 0% no-repeat padding-box;
            border: 4px solid rgb(6 12 59 / var(--tw-bg-opacity));
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
