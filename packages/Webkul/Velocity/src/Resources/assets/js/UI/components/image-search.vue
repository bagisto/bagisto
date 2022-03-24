<template>
    <div class="d-inline-block image-search-container" v-if="status == 'true'">
        <label for="image-search-container">
            <i class="icon camera-icon"></i>

            <input
                type="file"
                class="d-none"
                ref="image_search_input"
                id="image-search-container"
                v-on:change="loadLibrary()"
            />

            <img
                class="d-none"
                id="uploaded-image-url"
                :src="uploadedImageUrl"
                alt=""
                width="20"
                height="20"
            />
        </label>
    </div>
</template>

<script>
export default {
    props: ['status', 'uploadSrc', 'viewSrc', 'commonError', 'sizeLimitEror'],

    data: function() {
        return {
            uploadedImageUrl: ''
        };
    },

    methods: {
        /**
         * This method will dynamically load the scripts. Because image search library
         * only used when someone clicks or interact with the image button. This will
         * reduce some data usage for mobile user.
         */
        loadLibrary: function() {
            this.loadDynamicScript(
                'https://cdn.jsdelivr.net/npm/@tensorflow/tfjs',
                () => {
                    this.loadDynamicScript(
                        'https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet',
                        () => {
                            this.uploadImage();
                        }
                    );
                }
            );
        },

        /**
         * This method will analyze the image and load the sets on the bases of trained model.
         */
        uploadImage: function() {
            var imageInput = this.$refs.image_search_input;

            if (imageInput.files && imageInput.files[0]) {
                if (imageInput.files[0].type.includes('image/')) {
                    if (imageInput.files[0].size <= 2000000) {
                        this.$root.showLoader();

                        var formData = new FormData();

                        formData.append('image', imageInput.files[0]);

                        axios
                            .post(this.uploadSrc, formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                var net;
                                var self = this;
                                this.uploadedImageUrl = response.data;

                                async function app() {
                                    var analysedResult = [];

                                    var queryString = '';

                                    net = await mobilenet.load();

                                    const imgElement = document.getElementById(
                                        'uploaded-image-url'
                                    );

                                    try {
                                        const result = await net.classify(
                                            imgElement
                                        );

                                        result.forEach(function(value) {
                                            queryString = value.className.split(
                                                ','
                                            );

                                            if (queryString.length > 1) {
                                                analysedResult = analysedResult.concat(
                                                    queryString
                                                );
                                            } else {
                                                analysedResult.push(
                                                    queryString[0]
                                                );
                                            }
                                        });
                                    } catch (error) {
                                        self.$root.hideLoader();

                                        window.showAlert(
                                            `alert-danger`,
                                            this.__('shop.general.alert.error'),
                                            this.commonError
                                        );
                                    }

                                    localStorage.searchedImageUrl =
                                        self.uploadedImageUrl;

                                    queryString = localStorage.searched_terms = analysedResult.join(
                                        '_'
                                    );

                                    self.$root.hideLoader();

                                    window.location.href = `${self.viewSrc}?term=${queryString}&image-search=1`;
                                }

                                app();
                            })
                            .catch(() => {
                                this.$root.hideLoader();

                                window.showAlert(
                                    `alert-danger`,
                                    this.__('shop.general.alert.error'),
                                    this.commonError
                                );
                            });
                    } else {
                        imageInput.value = '';

                        window.showAlert(
                            `alert-danger`,
                            this.__('shop.general.alert.error'),
                            this.sizeLimitEror
                        );
                    }
                } else {
                    imageInput.value = '';

                    alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                }
            }
        }
    }
};
</script>
