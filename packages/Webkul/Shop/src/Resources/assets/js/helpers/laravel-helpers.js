const laravelHelpers = {
    install(app) {
        app.config.globalProperties.$laravelHelpers = {
            /**
             * Generates a Laravel route URL with dynamic bindings. Replaces placeholder variables in the URL with
             * their corresponding values from the bindings object.
             *
             * @param {string} url - The Laravel route URL that may contain placeholder variables in the form of `:key:`.
             * @param {Object} bindings - An object that maps the placeholder keys to their respective values.
             * @returns {string} - The modified URL with replaced placeholder variables.
             */
            route: (url, bindings = {}) => {
                let modifiedUrl = url;

                for (const [key, value] of Object.entries(bindings)) {
                    modifiedUrl = modifiedUrl.replace(`:${key}:`, value);
                }

                return modifiedUrl;
            },
        };
    },
};

export default laravelHelpers;
