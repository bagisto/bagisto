<x-admin::layouts>
    <!-- Title of the page. -->
    <x-slot:title>
        @lang('admin::app.configuration.index.title')
    </x-slot>

    <!-- Heading of the page -->
    <div class="mb-7 flex items-center justify-between">
        <p class="py-3 text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.configuration.index.title')
        </p>

        <!-- Configuration Search Bar Vue Component -->
        <v-configuration-search>
            <div class="relative flex w-[525px] max-w-[525px] items-center max-lg:w-[400px] ltr:ml-2.5 rtl:mr-2.5">
                <i class="icon-search absolute top-1.5 flex items-center text-2xl ltr:left-3 rtl:right-3"></i>

                <input 
                    type="text" 
                    class="block w-full rounded-lg border bg-white px-10 py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                    placeholder="@lang('admin::app.configuration.index.search')" 
                >
            </div>
        </v-configuration-search>
    </div>

    <!-- Page Content -->
    <div class="grid gap-y-8">
        @foreach (system_config()->getItems() as $item)
            <div>
                <div class="grid gap-1">
                    <!-- Title of the Main Card -->
                    <p class="font-semibold text-gray-600 dark:text-gray-300">
                        {{ $item->getName() }}
                    </p>

                    <!-- Info of the Main Card -->
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $item->getInfo() }}
                    </p>
                </div>

                <div class="box-shadow max-1580:grid-cols-3 mt-2 grid grid-cols-4 flex-wrap justify-between gap-12 rounded bg-white p-4 dark:bg-gray-900 max-xl:grid-cols-2 max-sm:grid-cols-1">
                    <!-- Menus cards -->
                    @foreach ($item->getChildren() as $key => $child)
                        <a 
                            class="flex max-w-[360px] items-center gap-2 rounded-lg p-2 transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                            href="{{ route('admin.configuration.index', ($item->getKey() . '/' . $key)) }}"
                        >
                            @if ($icon = $child->getIcon())
                                <img
                                    class="h-[60px] w-[60px] dark:mix-blend-exclusion dark:invert"
                                    src="{{ bagisto_asset('images/' . $icon) }}"
                                >
                            @endif

                            <div class="grid">
                                <p class="mb-1.5 text-base font-semibold text-gray-800 dark:text-white">
                                    {{ $child->getName() }}
                                </p>
                                
                                <p class="text-xs text-gray-600 dark:text-gray-300">
                                    {{ $child->getInfo() }}
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
            <div class="relative flex w-[525px] max-w-[525px] items-center max-lg:w-[400px] ltr:ml-2.5 rtl:mr-2.5">
                <i class="icon-search absolute top-1.5 flex items-center text-2xl ltr:left-3 rtl:right-3"></i>

                <input 
                    type="text"
                    class="peer block w-full rounded-lg border bg-white px-10 py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                    :class="{'border-gray-400': isDropdownOpen}"
                    placeholder="@lang('admin::app.configuration.index.search')"
                    v-model.lazy="searchTerm"
                    @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                    v-debounce="500"
                >

                <div
                    class="absolute top-10 z-10 w-full rounded-lg border bg-white shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] dark:border-gray-800 dark:bg-gray-900"
                    v-if="isDropdownOpen"
                >
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.categories />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <a
                                :href="category.url"
                                class="cursor-pointer border-b p-4 text-sm font-semibold text-gray-600 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                                v-for="category in searchedResults.data"
                            >
                                @{{ category.title }}
                            </a>

                            <div
                                class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300"
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