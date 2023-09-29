<v-image-search>
    <button
        type="button"
        class="icon-camera flex items-center absolute top-[10px] ltr:right-[12px] rtl:left-[12px] pr-3 text-[22px]"
        aria-label="Search"
    >
    </button>
</v-image-search>

@pushOnce('scripts')
    <script type="text/x-template" id="v-image-search-template">
        <div>
            <label
                class="icon-camera flex items-center absolute top-[10px] ltr:right-[12px] rtl:left-[12px] pr-3 text-[22px] cursor-pointer"
                aria-label="Search"
                for="v-image-search"
                v-if="! isSearching"
            >
            </label>

            <label
                class="flex items-center absolute top-[10px] ltr:right-[12px] rtl:left-[12px] pr-3 text-[22px] cursor-pointer"
                v-else
            >
                <!-- Spinner -->
                <svg
                    class="animate-spin h-6 w-6 text-black"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    >
                    </circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    >
                    </path>
                </svg>
            </label>

            <input
                type="file"
                class="hidden"
                ref="imageSearchInput"
                id="v-image-search"
                @change="loadLibrary()"
            />

            <img
                id="uploaded-image-url"
                class="hidden"
                :src="uploadedImageUrl"
                alt="uploaded image url"
                width="20"
                height="20"
            />
        </div>
    </script>

    <script type="module">
        app.component('v-image-search', {
            template: '#v-image-search-template',

            data() {
                return {
                    uploadedImageUrl: '',

                    isSearching: false,
                };
            },

            methods: {
                /**
                 * This method will dynamically load the scripts. Because image search library
                 * only used when someone clicks or interact with the image button. This will
                 * reduce some data usage for mobile user.
                 * 
                 * @return {void}
                 */
                loadLibrary() {
                    this.$shop.loadDynamicScript(
                        'https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js', () => {
                            this.$shop.loadDynamicScript(
                                'https://cdn.jsdelivr.net/npm/tensorflow-models-mobilenet-patch@2.1.1/dist/mobilenet.min.js', () => {
                                    this.analyzeImage();
                                }
                            );
                        }
                    );
                },

                /**
                 * This method will analyze the image and load the sets on the bases of trained model.
                 * 
                 * @return {void}
                 */
                analyzeImage() {
                    this.isSearching = true;

                    let imageInput = this.$refs.imageSearchInput;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            if (imageInput.files[0].size <= 2000000) {
                                let formData = new FormData();

                                formData.append('image', imageInput.files[0]);

                                this.$axios.post('{{ route('shop.search.upload') }}', formData, {
                                        headers: {
                                            'Content-Type': 'multipart/form-data'
                                        }
                                    })
                                    .then(response => {
                                        let net;

                                        let self = this;

                                        this.uploadedImageUrl = response.data;

                                        async function app() {
                                            let analysedResult = [];

                                            let queryString = '';

                                            net = await mobilenet.load();

                                            try {
                                                const result = await net.classify(document.getElementById('uploaded-image-url'));

                                                result.forEach(function(value) {
                                                    queryString = value.className.split(',');

                                                    if (queryString.length > 1) {
                                                        analysedResult = analysedResult.concat(queryString);
                                                    } else {
                                                        analysedResult.push(queryString[0]);
                                                    }
                                                });
                                            } catch (error) {
                                                this.$emitter.emit('add-flash', { type: 'error', message: '@lang('shop::app.search.images.index.something-went-wrong')'});
                                            }

                                            localStorage.searchedImageUrl = self.uploadedImageUrl;

                                            queryString = localStorage.searchedTerms = analysedResult.join('_');

                                            queryString = localStorage.searchedTerms.split('_').map(term => {
                                                return term.split(' ').join('+');
                                            });

                                            window.location.href = `${'{{ route('shop.search.index') }}'}?query=${queryString[0]}&image-search=1`;
                                        }

                                        app();
                                    })
                                    .catch((error) => {
                                        this.$emitter.emit('add-flash', { type: 'error', message: '@lang('shop::app.search.images.index.something-went-wrong')'});
                                    });
                            } else {
                                imageInput.value = '';

                                this.$emitter.emit('add-flash', { type: 'error', message: '@lang('shop::app.search.images.index.size-limit-error')'});
                            }
                        } else {
                            imageInput.value = '';

                            this.$emitter.emit('add-flash', { type: 'error', message: '@lang('shop::app.search.images.index.only-images-allowed')'});
                        }
                    }
                },
            },
        });
    </script>
@endPushOnce