module.exports = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, params) {
            let splitedKey = key.split('.');
            let translation = window._translations;

            splitedKey.forEach(key => {
                translation = translation[key];
            });

            if (params) {
                Object.keys(params).forEach(key => {
                    let value = params[key];
                    translation = translation.replace(`:${key}`, value);
                });
            }

            return translation
        }
    },
}