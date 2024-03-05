<x-admin::layouts>
    <!-- Title of the page. -->
    <x-slot:title>
        @lang('admin::app.configuration.index.title')
    </x-slot>

    <!-- Heading of the page -->
    <div class="flex justify-between items-center mb-7">
        <p class="py-3 text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.configuration.index.title')
        </p>

        <!-- Configuration Search Bar Vue Component -->
        <v-configuration-search>
            <div class="flex items-center relative w-[525px] max-w-[525px] ltr:ml-2.5 rtl:mr-2.5 max-lg:w-[400px]">
                <i class="icon-search absolute flex items-center ltr:left-3 rtl:right-3 text-2xl top-1.5"></i>

                <input 
                    type="text" 
                    class="w-full px-10 py-1.5 block bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                    placeholder="@lang('admin::app.configuration.index.search')" 
                >
            </div>
        </v-configuration-search>
    </div>

    <!-- Page Content -->
    <div class="grid gap-y-8">
        @foreach ($config->items as $itemKey => $item)
            <div>
                <div class="grid gap-1">
                    <!-- Title of the Main Card -->
                    <p class="text-gray-600 dark:text-gray-300 font-semibold">
                        @lang($item['name'] ?? '')
                    </p>

                    <!-- Info of the Main Card -->
                    <p class="text-gray-600 dark:text-gray-300">
                        @lang($item['info'] ?? '')
                    </p>
                </div>

                <div class="grid grid-cols-4 gap-12 flex-wrap justify-between p-4 mt-2 bg-white dark:bg-gray-900 rounded box-shadow max-1580:grid-cols-3 max-xl:grid-cols-2 max-sm:grid-cols-1">
                    <!-- Menus cards -->
                    @foreach ($item['children'] as $childKey =>  $child)
                        <a 
                            class="flex items-center gap-2 max-w-[360px] p-2 rounded-lg transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                            href="{{ route('admin.configuration.index', ($itemKey . '/' . $childKey)) }}"
                        >
                            @if (isset($child['icon']))
                                <img
                                    class="w-[60px] h-[60px] dark:invert dark:mix-blend-exclusion"
                                    src="{{ bagisto_asset('images/' . $child['icon'] ?? '') }}"
                                >
                            @endif

                            <div class="grid">
                                <p class="mb-1.5 text-base text-gray-800 dark:text-white font-semibold">
                                    @lang($child['name'])
                                </p>
                                
                                <p class="text-xs text-gray-600 dark:text-gray-300">
                                    @lang($child['info'] ?? '')
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-configuration-search-template">
            <div class="flex items-center relative w-[525px] max-w-[525px] ltr:ml-2.5 rtl:mr-2.5 max-lg:w-[400px]">
                <i class="icon-search text-2xl flex items-center absolute ltr:left-3 rtl:right-3 top-1.5"></i>

                <input 
                    type="text"
                    class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg block w-full px-10 py-1.5 leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 peer"
                    :class="{'border-gray-400': isDropdownOpen}"
                    placeholder="@lang('admin::app.configuration.index.search')"
                    v-model.lazy="searchTerm"
                    @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                    v-debounce="500"
                >

                <div
                    class="absolute top-10 w-full bg-white dark:bg-gray-900 shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] border dark:border-gray-800 rounded-lg z-10"
                    v-if="isDropdownOpen"
                >
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.categories />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="category.url"
                                class="p-4 border-b dark:border-gray-800 text-sm text-gray-600 dark:text-gray-300 font-semibold cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 last:border-b-0"
                                v-for="category in searchedResults.data"
                            >
                                @{{ category.title }}
                            </a>

                            <div
                                class="p-4 text-sm text-gray-600 dark:text-gray-300 font-semibold"
                                v-if="searchedResults.data.length === 0"
                            >
                                @lang('admin::app.configuration.index.no-result-found')
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-configuration-search', {
                template: '#v-configuration-search-template',
                
                data() {
                    return {
                        isDropdownOpen: false,

                        isLoading: false,

                        searchTerm: '',

                        searchedResults: [],
                    };
                },

                watch: {
                    searchTerm(newVal, oldVal) {
                        this.search();
                    },
                },

                created() {
                    window.addEventListener('click', this.handleFocusOut);
                },

                beforeDestroy() {
                    window.removeEventListener('click', this.handleFocusOut);
                },

                methods: {
                    search() {
                        if (this.searchTerm.length <= 1) {
                            this.searchedResults = [];

                            this.isDropdownOpen = false;

                            return;
                        }

                        this.isDropdownOpen = true;

                        this.isLoading = true;
                        
                        this.$axios.get("{{ route('admin.configuration.search') }}", {
                                params: {query: this.searchTerm}
                            })
                            .then((response) => {
                                this.searchedResults = response.data;

                                this.isLoading = false;
                            })
                            .catch((error) => {});
                    },

                    handleFocusOut(e) {
                        if (! this.$el.contains(e.target)) {
                            this.isDropdownOpen = false;
                        }
                    },
                },
            });
        </script>
    @endpushOnce
</x-admin::layouts>