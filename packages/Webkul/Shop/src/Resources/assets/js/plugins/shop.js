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
             * Format the given price with currency symbol.
             *
             * @param {number} price - The price to be formatted.
             * @returns {string} - The formatted price as a string.
             */
            formatPrice(price) {
                const locale = document.querySelector('meta[http-equiv="content-language"]').content;

                const currency = JSON.parse(document.querySelector('meta[name="currency"]').content);

                const symbol = currency.symbol !== '' ? currency.symbol : currency.code;

                if (! currency.currency_position) {
                    return new Intl.NumberFormat(locale, {
                        style: "currency",
                        currency: currency.code,
                    }).format(price);
                }

                const formatter = new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: currency.code,
                    minimumFractionDigits: currency.decimal ?? 2
                });

                const formattedCurrency = formatter.formatToParts(price)
                    .map(part => {
                        switch (part.type) {
                            case 'currency':
                                return '';

                            case 'group':
                                return currency.group_separator === ''
                                    ? part.value
                                    : currency.group_separator;

                            case 'decimal':
                                return currency.decimal_separator === ''
                                    ? part.value
                                    : currency.decimal_separator;

                            default:
                                return part.value;
                        }
                    })
                    .join('');

                switch (currency.currency_position) {
                    case 'left':
                        return symbol + formattedCurrency;

                    case 'left_with_space':
                        return symbol + ' ' + formattedCurrency;

                    case 'right':
                        return formattedCurrency + symbol;

                    case 'right_with_space':
                        return formattedCurrency + ' ' + symbol;

                    default:
                        return formattedCurrency;
                }
            },
        };
    },
};
