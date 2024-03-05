export default {
    install(app) {
        app.config.globalProperties.$admin = {
            /**
             * Generates a formatted price string using the provided price, localeCode, and currencyCode.
             *
             * @param {number} price - The price value to be formatted.
             * @param {string} localeCode - The locale code specifying the desired formatting rules.
             * @param {string} currencyCode - The currency code specifying the desired currency symbol.
             * @returns {string} - The formatted price string.
             */
            formatPrice: (price, localeCode = null, currencyCode = null) => {
                if (! localeCode) {
                    localeCode =
                        document.querySelector(
                            'meta[http-equiv="content-language"]'
                        ).content ?? "en";
                }

                if (! currencyCode) {
                    currencyCode =
                        document.querySelector('meta[name="currency-code"]')
                            .content ?? "USD";
                }

                return new Intl.NumberFormat(localeCode.replace('_', '-'), {
                    style: "currency",
                    currency: currencyCode,
                }).format(price);
            },
        };
    },
};
