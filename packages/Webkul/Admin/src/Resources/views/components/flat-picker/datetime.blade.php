<v-datetime-picker {{ $attributes }}>
    {{ $slot }}
</v-datetime-picker>

@pushOnce('scripts')
    <script type="text/x-template" id="v-datetime-picker-template">
        <span class="relative">
            <slot></slot>

            <i class="icon-calendar text-[24px] text-gray-400 absolute ltr:right-[8px] rtl:left-[8px] top-[50%] -translate-y-[50%]"></i>
        </span>
    </script>

    <script type="module">
        app.component('v-datetime-picker', {
            template: '#v-datetime-picker-template',

            props: {
                name: String,

                value: String,

                allowInput: {
                    type: Boolean,
                    default: true,
                },

                disable: Array,

                minDate: String,

                maxDate: String,
            },

            data: function() {
                return {
                    datepicker: null
                };
            },

            mounted: function() {
                let options = this.setOptions();

                this.activate(options);
            },

            methods: {
                setOptions: function() {
                    let self = this;

                    return {
                        allowInput: this.allowInput ?? true,
                        disable: this.disable ?? [],
                        minDate: this.minDate ?? '',
                        maxDate: this.maxDate ?? '',
                        altFormat: "Y-m-d H:i:S",
                        dateFormat: "Y-m-d H:i:S",
                        enableTime: true,
                        time_24hr: true,
                        weekNumbers: true,

                        onChange: function(selectedDates, dateStr, instance) {
                            self.$emit("onChange", dateStr);
                        }
                    };
                },

                activate: function(options) {
                    let element = this.$el.getElementsByTagName("input")[0];

                    this.datepicker = new Flatpickr(element, options);
                },

                clear: function() {
                    this.datepicker.clear();
                }
            }
        });
    </script>
@endPushOnce
