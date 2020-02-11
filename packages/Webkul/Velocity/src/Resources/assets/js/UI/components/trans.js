module.exports = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key) {
            let splitedKey = key.split('.');
            let translation = window._translations;

            splitedKey.forEach(key => {
                translation = translation[key];
            });

            return translation
        }
    },
}