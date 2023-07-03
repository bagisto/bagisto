<v-top-nav {{ $attributes }}></v-top-nav>

@pushOnce('scripts')
    <script type="text/x-template" id="v-top-nav-template">
        <div>
            <div class="relative mt-1">
                <button 
                    type="button"
                    class="relative w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                    @click="isOpen = !isOpen"
                >
                    <span class="block truncate">
                        Devon Webb
                    </span>

                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <span :class="`text-[24px] ${isOpen ? 'icon-arrow-up' : 'icon-arrow-down'}`"></span>
                    </span>
                </button>

                <ul
                    role="listbox"
                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    v-if="isOpen"
                >
                    <li 
                        class="text-gray-900 relative cursor-default select-none p-2"
                        role="option"
                        v-for="item in JSON.parse(items)"
                    >
                        <span 
                            class="font-normal block truncate cursor-pointer"
                            v-text="item.name"
                        >
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-top-nav', {
            template: '#v-top-nav-template',

            props: ['items'],

            data() {
                return {
                    isOpen: false,
                }
            },

            created() {
            },

            methods: {
                
            },
        });
    </script>
@endPushOnce
