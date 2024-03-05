<v-image-search-result-component>
    <div class="mt-8 p-5 border border-gray-200 rounded-lg">
        <div class="flex gap-12">
            <x-shop::media.images.lazy class="max-w-[110px] max-h-[110px] min-w-[110px] w-[110px] h-[110px] rounded-sm" />
             
            <div class="flex flex-col gap-4">
                <span class="shimmer w-[200px] h-10"></span>

                <div class="flex gap-2 flex-wrap">
                    @for ($i = 1; $i < 10; $i++)
                        <div class="shimmer flex justify-center items-center m-1 font-medium rounded-full cursor-pointer">
                            <span class="shimmer w-20 h-10 rounded-full"></span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</v-image-search-result-component>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-image-search-result-component-template"
    >
        <div class="mt-8 p-5 bg-gray-100 border border-gray-200 rounded-lg">
            <div class="flex gap-12">
                <img 
                    class="max-w-[110px] max-h-[110px] min-h-[110px] w-[110px] h-[110px] rounded-sm" 
                    :src="searchedImageUrl"
                    alt="search image"
                    height="110"
                    width="110"
                />

                <div class="flex flex-col gap-4">
                    <h2 class="text-2xl font-medium">
                        @lang('shop::app.search.images.results.analysed-keywords')
                    </h2>

                    <div class="flex gap-2 flex-wrap">
                        <span 
                            class="flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded-full border cursor-pointer"
                            v-for="term in searchedTerms"
                            @click="search(term)"
                        >
                            <span
                                class="p-2.5 text-xs font-normal leading-none max-w-full flex-initial"
                                v-text="term.name"
                            >
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-image-search-result-component', {
            template: '#v-image-search-result-component-template',

            data() {
                return {
                    searchedTerms: [],
                    searchedImageUrl: localStorage.searchedImageUrl,
                };
            },

            created() {
                if (localStorage.searchedTerms && localStorage.searchedTerms != '') {
                    this.searchedTerms = localStorage.searchedTerms.split('_');

                    this.searchedTerms = this.searchedTerms.map(term => {
                        return {
                            name: term,
                            slug: term.split(' ').join('+'),
                        }
                    });
                }
            },

            methods: {
                search(term) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('query', term.name);

                    window.location.href = url.href;
                },
            },
        });
    </script>
@endPushOnce