let debounce = (func, wait) => {
    let timeout;

    return function (...args) {
        clearTimeout(timeout);

        timeout = setTimeout(() => func.apply(this, args), wait);
    };
};

export default {
    mounted(el, binding) {
        const handler = debounce(function (e) {
            e.target.value = e.target.value
                .toString()
                .toLowerCase()
                .normalize("NFKD") // Normalize Unicode
                .replace(/[\u0300-\u036f]/g, "") // Remove combining diacritical marks
                .replace(/[^\p{L}\p{N}\s-]+/gu, "") // Remove all non-letter, non-number characters except spaces and dashes
                .replace(/\s+/g, "-") // Replace spaces with dashes
                .replace(/-+/g, "-") // Avoid multiple consecutive dashes
                .replace(/^-+|-+$/g, ""); // Trim leading and trailing dashes
        }, 300); // Debounce delay in ms

        el.addEventListener("input", handler);
    },
};
