<v-range-slider {{ $attributes }}></v-range-slider>

@pushOnce('styles')
    {{--
        Only pseudo classes conversion left for tailwind.
    --}}
    <style>
        .thumb-range::-webkit-slider-thumb {
            pointer-events: auto;
            -webkit-appearance: none;
            width: 25px;
            height: 25px;
            background: #fcfcfc 0% 0% no-repeat padding-box;
            border: 4px solid rgb(6 12 59 / var(--tw-bg-opacity));
            border-radius: 20px;
            opacity: 1;
        }

        .thumb-range::-moz-range-thumb {
            pointer-events: auto;
            -webkit-appearance: none;
            width: 25px;
            height: 25px;
            background: #fcfcfc 0% 0% no-repeat padding-box;
            border: 4px solid rgb(6 12 59 / var(--tw-bg-opacity));
            border-radius: 20px;
            opacity: 1;
        }

        .thumb-range::-ms-thumb {
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
                            class="absolute w-full h-[4px] appearance-none pointer-events-none bg-transparent outline-none thumb-range"
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
                            class="absolute w-full h-[4px] appearance-none pointer-events-none bg-transparent outline-none thumb-range"
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
