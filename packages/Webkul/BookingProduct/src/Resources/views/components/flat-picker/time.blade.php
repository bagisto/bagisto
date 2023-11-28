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

            mounted: function() {
                let options = this.setOptions();
                this.activate(options);
            },

            methods: {
                setOptions: function() {
                    let self = this;

                    return {
                        enableTime: true,
                        noCalendar: true,
                        altFormat: "H:i",
                        dateFormat: "H:i",
                        time_24hr: true,

                        onChange: function(selectedDates, dateStr, instance) {
                            self.$emit("onChange", dateStr);
                        }
                    };
                },

                activate: function(options) {
                    let element = this.$el.getElementsByTagName("input")[0];
                    this.timepicker = flatpickr(element, options);
                }
            }
        });
    </script>
@endPushOnce
