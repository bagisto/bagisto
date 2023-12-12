<v-time-picker {{ $attributes }}>
    {{ $slot }}
</v-time-picker>

@pushOnce('scripts')
    <script type="text/x-template" id="v-time-picker-template">
        <span class="w-full relative inline-block">
            <slot></slot>

            <i class="icon-clock text-[24px] text-gray-400 absolute ltr:right-[8px] rtl:left-[8px] top-[50%] -translate-y-[50%]"></i>
        </span>
    </script>

    <script type="module">
        app.component('v-time-picker', {
            template: '#v-time-picker-template',

            data() {
                return {
                    timepicker: null
                };
            },

            mounted() {
                let options = this.setOptions();

                this.activate(options);
            },

            methods: {
                setOptions() {
                    let self = this;

                    return {
                        enableTime: true,
                        noCalendar: true,
                        altFormat: "H:i",
                        dateFormat: "H:i",
                        time_24hr: true,

                        onChange(selectedTimes, timeStr, instance) {
                            self.$emit("onChange", timeStr);
                        },
                    };
                },

                activate(options) {
                    let element = this.$el.getElementsByTagName("input")[0];

                    this.timepicker = new Flatpickr(element, options);
                }
            }
        });
    </script>
@endPushOnce
