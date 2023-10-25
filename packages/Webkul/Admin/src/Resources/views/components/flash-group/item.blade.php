<v-flash-item
    v-for='flash in flashes'
    :key='flash.uid'
    :flash="flash"
    @onRemove="remove($event)"
></v-flash-item>

@pushOnce('scripts')
    <script type="text/x-template" id="v-flash-item-template">
        <div
            class="flex gap-[46px] justify-between w-max p-[12px] rounded-full"
            :style="typeStyles[flash.type]['container']"
        >
            <p
                class="text-[14px] flex items-center break-all"
                :style="typeStyles[flash.type]['message']"
            >
                <span
                    class="icon-toast-done text-[24px] ltr:mr-[10px] rtl:ml-[10px] bg-white dark:bg-gray-900 rounded-full"
                    :class="iconClasses[flash.type]"
                    :style="typeStyles[flash.type]['icon']"
                ></span>

                @{{ flash.message }}
            </p>

			<span
                class="underline cursor-pointer"
                :style="typeStyles[flash.type]['message']"
                @click="remove"
            >
                Close
            </span>
        </div>
    </script>

    <script type="module">
        app.component('v-flash-item', {
            template: '#v-flash-item-template',

            props: ['flash'],

            data() {
                return {
                    iconClasses: {
                        success: 'icon-done',

                        error: 'icon-cancel-1',

                        warning: 'icon-information',

                        info: 'icon-processing',
                    },

                    typeStyles: {
                        success: {
                            container: 'background: #059669',

                            message: 'color: #FFFFFF',

                            icon: 'color: #059669'
                        },

                        error: {
                            container: 'background: #EF4444',

                            message: 'color: #FFFFFF',

                            icon: 'color: #EF4444'
                        },

                        warning: {
                            container: 'background: #FACC15',

                            message: 'color: #1F2937',

                            icon: 'color: #FACC15'
                        },

                        info: {
                            container: 'background: #0284C7',

                            message: 'color: #FFFFFF',

                            icon: 'color: #0284C7'
                        },
                    },
                };
            },

            created() {
                var self = this;

                setTimeout(function() {
                    self.remove()
                }, 5000)
            },

            methods: {
                remove() {
                    this.$emit('onRemove', this.flash)
                }
            }
        });
    </script>
@endpushOnce
