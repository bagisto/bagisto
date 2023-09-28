<v-image-search-result-component>
    <div class="max-w-sm mt-[30px] p-[20px] border border-gray-200 rounded-lg">
        <div class="flex gap-[46px]">
            <x-shop::media.images.lazy class="max-w-[110px] max-h-[110px] min-w-[110px] w-[110px] h-[110px] rounded-sm"></x-shop::media.images.lazy>
             
            <div class="flex flex-col gap-[15px]">
                <span class="shimmer w-[200px] h-[36px]"></span>

                <div class="flex gap-2 flex-wrap">
                    <div class="flex m-1 py-1 px-2 rounded-full">
                        <span class="shimmer w-[80px] h-[36px] rounded-full"></span>
                    </div>
                    
                    <div class="flex m-1 py-1 px-2 rounded-full">
                        <span class="shimmer w-[80px] h-[36px] rounded-full"></span>
                    </div>

                    <div class="flex m-1 py-1 px-2 rounded-full">
                        <span class="shimmer w-[80px] h-[36px] rounded-full"></span>
                    </div>

                    <div class="flex m-1 py-1 px-2 rounded-full">
                        <span class="shimmer w-[80px] h-[36px] rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</v-image-search-result-component>

@pushOnce('scripts')
    <script type="text/x-template" id="v-image-search-result-component-template">
        <div class="max-w-sm mt-[30px] p-[20px] bg-gray-100 border border-gray-200 rounded-lg">
            <div class="flex gap-[46px]">
                <img 
                    class="max-w-[110px] max-h-[110px] min-h-[110px] w-[110px] h-[110px] rounded-sm" 
                    :src="searchedImageUrl"
                    alt="search image"
                    height="110"
                    width="110"
                />

                <div class="flex flex-col gap-[15px]">
                    <h2 class="text-[26px] font-medium">
                        Filter By Category
                    </h2>

                    <div class="flex gap-2 flex-wrap">
                        <a 
                            class="flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded-full text-red-100 border border-blue-700"
                            v-for="term in searchedTerms"
                            :href="'{{ route('shop.search.index') }}?query=' + term.slug"
                        >
                            <span
                                class="p-[10px] text-xs font-normal leading-none max-w-full flex-initial"
                                v-text="term.name"
                            >
                            </span>
                        </a>
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
            }
        });
    </script>
@endPushOnce