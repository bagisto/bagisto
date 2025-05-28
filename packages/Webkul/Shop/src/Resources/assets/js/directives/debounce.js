let timeoutID = null;

export default {
    mounted(el, binding) {
        const originalHandler = binding.value;

        const debouncedHandler = function (e) {
            clearTimeout(timeoutID);

            timeoutID = setTimeout(() => {
                if (typeof originalHandler === "function") {
                    originalHandler(e);
                }
            }, binding.arg || 500);
        };

        el._debouncedHandler = debouncedHandler;

        el.addEventListener("input", debouncedHandler);
    },

    unmounted(el) {
        if (el._debouncedHandler) {
            el.removeEventListener("input", el._debouncedHandler);

            clearTimeout(timeoutID);
        }
    },

    updated(el, binding) {
        if (binding.value !== binding.oldValue) {
            if (el._debouncedHandler) {
                el.removeEventListener("input", el._debouncedHandler);
            }

            const originalHandler = binding.value;

            const debouncedHandler = function (e) {
                clearTimeout(timeoutID);

                timeoutID = setTimeout(() => {
                    if (typeof originalHandler === "function") {
                        originalHandler(e);
                    }
                }, binding.arg || 500);
            };

            el._debouncedHandler = debouncedHandler;

            el.addEventListener("input", debouncedHandler);
        }
    },
};
