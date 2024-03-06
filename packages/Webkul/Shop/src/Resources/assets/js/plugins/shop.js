export default {
    install(app) {
        app.config.globalProperties.$shop = {
            /**
             * Base url.
             *
             * @returns {string}
             */
            baseUrl: () => {
                return document.querySelector('meta[name="base-url"]').content ?? "http://localhost";
            },

            /**
             * Load the dynamic scripts.
             *
             * @param {string} src
             * @param {callback} onScriptLoaded
             *
             * @returns {void}.
             */
            loadDynamicScript: (src, onScriptLoaded) => {
                let dynamicScript = document.createElement('script');

                dynamicScript.setAttribute('src', src);

                document.body.appendChild(dynamicScript);

                dynamicScript.addEventListener('load', onScriptLoaded, false);
            },

            /**
             * Generates a formatted price string serves from the backend.
             *
             * @param {number|array} prices - The price value to be formatted.
             * @returns {string} - The formatted price string.
             */
            formatPrice: (prices) => {
                const $axios = app.config.globalProperties.$axios;

                const baseUrl = app.config.globalProperties.$shop.baseUrl();

                return $axios.get(`${baseUrl}/api/core/format-price`, {
                        params: {
                            prices: prices,
                            currencyCode: document.querySelector('meta[name="currency-code"]').content ?? "USD",
                        },
                    })
                    .then((response) => response.data.data);
            },
        };
    },
};
