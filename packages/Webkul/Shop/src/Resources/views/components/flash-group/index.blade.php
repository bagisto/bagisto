<v-flash-group ref='flashes'></v-flash-group>

@pushOnce('scripts')
    <script type="text/x-template" id="v-flash-group-template">
        <transition-group
            tag='div'
            name="flash-group"
            class='grid gap-[10px] fixed top-[20px] right-[20px] z-[1]'
        >
            <x-shop::flash-group.item></x-shop::flash-group.item>
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
