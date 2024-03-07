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
                const $shop = app.config.globalProperties.$shop;

                const locale = document.querySelector('meta[http-equiv="content-language"]').content;

                const currency = JSON.parse(document.querySelector('meta[name="currency-code"]').content);

                let formatter = new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: currency.code,
                    minimumFractionDigits: currency.decimal ?? 2
                });

                if (currency.symbol) {
                    return $shop.formatPriceWithSymbol(price, currency, currency.symbol, formatter);
                }

                return $shop.formatPriceWithSymbol(price, currency, currency.code, formatter);
            },

            /**
             * Format the given price with the specified currency symbol and position.
             *
             * @param {number} price - The price to be formatted.
             * @param {object} currency - The currency information.
             * @param {string} symbol - The currency symbol.
             * @param {object} formatter - The number formatter.
             * @returns {string} - The formatted price as a string.
             */
            formatPriceWithSymbol(price, currency, symbol, formatter) {
                let parts = formatter.formatToParts(price);

                let formattedCurrency = parts.map(part => {
                    switch (part.type) {
                        case 'currency':
                            return '';

                        case 'group':
                            return currency.thousand_separator === '' ? ' ' : currency.thousand_separator;

                        case 'decimal':
                            return currency.decimal_separator === '' ? ' ' : currency.decimal_separator;

                        default:
                            return part.value;
                    }
                }).join('');

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
