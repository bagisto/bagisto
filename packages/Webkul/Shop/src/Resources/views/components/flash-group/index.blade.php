<v-flash-group ref='flashes'></v-flash-group>

@pushOnce('scripts')
    @php
        $flashes = [];

        foreach (['success', 'warning', 'error', 'info'] as $type) {
            if (session()->has($type)) {
                $flashes[] = [
                    'type' => $type,
                    'message' => session($type)
                ];
            }
        }

        $isResponseCacheMiddlwareActive = false;

        $currentRoute = request()->route();

        if ($currentRoute) {
            $middlewares = $currentRoute->gatherMiddleware();

            $isResponseCacheMiddlwareActive = in_array('cache.response', $middlewares);
        }
    @endphp

    <script
        type="text/x-template"
        id="v-flash-group-template"
    >
        <transition-group
            tag='div'
            name="flash-group"
            enter-from-class="ltr:translate-x-full rtl:-translate-x-full"
            enter-active-class="transform transition duration-200 ease-in-out"
            enter-to-class="ltr:translate-x-0 rtl:-translate-x-0"
            leave-from-class="ltr:translate-x-0 rtl:-translate-x-0"
            leave-active-class="transform transition duration-200 ease-in-out"
            leave-to-class="ltr:translate-x-full rtl:-translate-x-full"
            class='fixed top-5 z-[1001] grid justify-items-end gap-2.5 max-sm:hidden ltr:right-5 rtl:left-5'
        >
            <x-shop::flash-group.item />
        </transition-group>

        <transition-group
            tag='div'
            name="flash-group"
            enter-from-class="ltr:translate-y-full rtl:-translate-y-full"
            enter-active-class="transform transition duration-200 ease-in-out"
            enter-to-class="ltr:translate-y-0 rtl:-translate-y-0"
            leave-from-class="ltr:translate-y-0 rtl:-translate-y-0"
            leave-active-class="transform transition duration-200 ease-in-out"
            leave-to-class="ltr:translate-y-full rtl:-translate-y-full"
            class='fixed bottom-10 left-1/2 z-[1001] grid -translate-x-1/2 -translate-y-1/2 transform justify-items-center gap-2.5 sm:hidden'
        >
            <x-shop::flash-group.item />
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
                this.loadInitialFlashes();

                this.registerGlobalEvents();
            },

            methods: {
                loadInitialFlashes() {
                    @if (
                        config('responsecache.enabled') 
                        && $isResponseCacheMiddlwareActive
                    )
                        let flashes = '<bagisto-response-cache-session-flashes>';
                    @else
                        let flashes = @json($flashes);
                    @endif

                    if (typeof(flashes) === 'string') {
                        return;
                    }
                    
                    flashes.forEach(flash => {
                        flash.uid = this.uid++;

                        this.flashes.push(flash);
                    });
                },

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
