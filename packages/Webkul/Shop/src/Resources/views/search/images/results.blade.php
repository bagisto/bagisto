<v-image-search-result-component>
    <div class="mt-8 rounded-lg border border-gray-200 p-5">
        <div class="flex gap-12">
            <x-shop::media.images.lazy class="h-[110px] max-h-[110px] w-[110px] min-w-[110px] max-w-[110px] rounded-sm" />
             
            <div class="flex flex-col gap-4">
                <span class="shimmer h-10 w-[200px]"></span>

                <div class="flex flex-wrap gap-2">
                    @for ($i = 1; $i < 10; $i++)
                        <div class="shimmer m-1 flex cursor-pointer items-center justify-center rounded-full font-medium">
                            <span class="shimmer h-10 w-20 rounded-full"></span>
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
        <div class="mt-8 rounded-lg border border-gray-200 bg-gray-100 p-5">
            <div class="flex gap-12">
                <img 
                    class="h-[110px] max-h-[110px] min-h-[110px] w-[110px] max-w-[110px] rounded-sm" 
                    :src="searchedImageUrl"
                    alt="search image"
                    height="110"
                    width="110"
                />

                <div class="flex flex-col gap-4">
                    <h2 class="text-2xl font-medium">
                        @lang('shop::app.search.images.results.analysed-keywords')
                    </h2>

                    <div class="flex flex-wrap gap-2">
                        <span 
                            class="m-1 flex cursor-pointer items-center justify-center rounded-full border bg-white px-2 py-1 font-medium"
                            v-for="term in searchedTerms"
                            @click="search(term)"
                        >
                            <span
                                class="max-w-full flex-initial p-2.5 text-xs font-normal leading-none"
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