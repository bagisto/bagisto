<v-flash-group ref='flashes'></v-flash-group>

@pushOnce('scripts')
    <script type="text/x-template" id="v-flash-group-template">
        <transition-group
            tag='div'
            name="flash-group"
            enter-from-class="translate-y-full"
            enter-active-class="transform transition ease-in-out duration-200"
            enter-to-class="translate-y-0"
            leave-from-class="translate-y-0"
            leave-active-class="transform transition ease-in-out duration-200"
            leave-to-class="translate-y-full"
            class='grid gap-[10px] absolute left-[50%] -translate-x-[50%]  bottom-[20px] z-[1] justify-items-end'
        >
            <x-admin::flash-group.item></x-admin::flash-group.item>
        </transition-group>
    </script>

    <script type="module">
        app.component('v-flash-group', {
            template: '#v-flash-group-template',

            data() {
                return {
                    uid: 0,

                    flashes: []
                }
            },

            created() {
                @foreach (['success', 'warning', 'error', 'info'] as $key)
                    @if (session()->has($key))
                        this.flashes.push({'type': '{{ $key }}', 'message': "{{ session($key) }}", 'uid':  this.uid++});
                    @endif
                @endforeach

                this.registerGlobalEvents();
            },

            methods: {
                add(flash) {
                    flash.uid = this.uid++;

                    this.flashes.push(flash);
                },

                remove(flash) {
                    let index = this.flashes.indexOf(flash);

                    this.flashes.splice(index, 1);
                },

                registerGlobalEvents() {
                    this.$emitter.on('add-flash', this.add);
                },
            }
        });
    </script>
@endpushOnce