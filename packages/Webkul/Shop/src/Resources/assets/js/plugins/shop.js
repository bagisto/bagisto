export default {
    install(app) {
        app.config.globalProperties.$shop = {
            /**
             * Load the dynamic scripts
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
             * Generates a formatted price string using the provided price, localeCode, and currencyCode.
             *
             * @param {number} price - The price value to be formatted.
             * @param {string} localeCode - The locale code specifying the desired formatting rules.
             * @param {string} currencyCode - The currency code specifying the desired currency symbol.
             * @returns {string} - The formatted price string.
             */
            formatPrice: (price, localeCode = null, currencyCode = null) => {
                if (!localeCode) {
                    localeCode =
                        document.querySelector(
                            'meta[http-equiv="content-language"]'
                        ).content ?? "en";
                }

                if (!currencyCode) {
                    currencyCode =
                        document.querySelector('meta[name="currency-code"]')
                            .content ?? "USD";
                }

                return new Intl.NumberFormat(localeCode, {
                    style: "currency",
                    currency: currencyCode,
                }).format(price);
            },
        };
    },
};
