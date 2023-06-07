<flash-group ref='flashes'></flash-group>

@pushOnce('scripts')
    <script type="text/x-template" id="flash-group-template">
        <transition-group
            tag='div'
            name="flash-group"
            class='grid gap-[10px] fixed top-[20px] right-[20px] z-[1]'
        >
            <x-shop::flash-group.item></x-shop::flash-group.item>
        </transition-group>
    </script>

    <script type="module">
        app.component('flash-group', {
            template: '#flash-group-template',

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
            },

            methods: {
                add(flash) {
                    flash.uid = this.uid++;

                    this.flashes.push(flash)
                },

                remove(flash) {
                    let index = this.flashes.indexOf(flash)

                    this.flashes.splice(index, 1)
                }
            }
        });
    </script>
@endpushOnce
