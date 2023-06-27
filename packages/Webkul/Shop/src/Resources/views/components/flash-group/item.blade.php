<v-flash-item
    v-for='flash in flashes'
    :key='flash.uid'
    :flash="flash"
    @onRemove="remove($event)"
></v-flash-item>

@pushOnce('scripts')
    <script type="text/x-template" id="v-flash-item-template">
        <div
            class="flex gap-[46px] justify-between px-[20px] py-[12px] rounded-[8px] max-w-[408px]"
            :style="typeStyles[flash.type]['container']"
        >
            <p
                class=" text-[14px]"
                :style="typeStyles[flash.type]['message']"
            >
                @{{ flash.message }}
            </p>

			<span
                class="icon-cancel cursor-pointer max-h-[16px] max-w-[16px]"
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
                    typeStyles: {
                        success: {
                            container: 'background: #CBEAD2',

                            message: 'color: #13461C',

                            icon: 'color: #6C9774'
                        },
                        error: {
                            container: 'background: #F5CCD2',

                            message: 'color: #5D121B',

                            icon: 'color: #A86C73'
                        },
                        warning: {
                            container: 'background: #FFF1C2',

                            message: 'color: #715207',

                            icon: 'color: #B7A063'
                        },
                        info: {
                            container: 'background: #e2e3e5',

                            message: 'color: #383d41',

                            icon: 'color: #383d41'
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
