<v-flash-item
    v-for='flash in flashes'
    :key='flash.uid'
    :flash="flash"
    @onRemove="remove($event)"
/>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-flash-item-template"
    >
        <div
            class="flex w-max max-w-[408px] justify-between gap-12 rounded-lg px-5 py-3 max-sm:max-w-80 max-sm:items-center max-sm:gap-2 max-sm:p-3"
            :style="typeStyles[flash.type]['container']"
        >
            <p
                class="flex items-center break-words text-sm"
                :style="typeStyles[flash.type]['message']"
            >
                <span
                    class="icon-toast-done text-2xl ltr:mr-2.5 rtl:ml-2.5"
                    :class="iconClasses[flash.type]"
                    :style="typeStyles[flash.type]['icon']"
                ></span>

                @{{ flash.message }}
            </p>

			<span
                class="icon-cancel max-h-4 max-w-4 cursor-pointer"
                :style="typeStyles[flash.type]['icon']"
                @click="remove"
            ></span>
        </div>
    </script>

    <script type="module">
        app.component('v-flash-item', {
            template: '#v-flash-item-template',

            props: ['flash'],

            data() {
                return {
                    iconClasses: {
                        success: 'icon-toast-done',

                        error: 'icon-toast-error',

                        warning: 'icon-toast-exclamation-mark',

                        info: 'icon-toast-info',
                    },

                    typeStyles: {
                        success: {
                            container: 'background: #D4EDDA',

                            message: 'color: #155721',

                            icon: 'color: #155721'
                        },

                        error: {
                            container: 'background: #F8D7DA',

                            message: 'color: #721C24',

                            icon: 'color: #721C24'
                        },

                        warning: {
                            container: 'background: #FFF3CD',

                            message: 'color: #856404',

                            icon: 'color: #856404'
                        },

                        info: {
                            container: 'background: #E2E3E5',

                            message: 'color: #383D41',

                            icon: 'color: #383D41'
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
