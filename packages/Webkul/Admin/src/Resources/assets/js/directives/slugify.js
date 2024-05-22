export default {
    mounted(el, binding) {
        let handler = function (e) {
            setTimeout(function () {
                e.target.value = e.target.value
                    .toString()
                    .toLowerCase()
                    .normalize('NFKD') // Normalize Unicode
                    .replace(/[\u0300-\u036f]/g, '') // Remove combining diacritical marks
                    .replace(/[^\p{L}\p{N}\s-]+/gu, '') // Remove all non-letter, non-number characters except spaces and dashes
                    .replace(/\s+/g, '-') // Replace spaces with dashes
                    .replace(/-+/g, '-') // Avoid multiple consecutive dashes
                    .replace(/^-+|-+$/g, ''); // Trim leading and trailing dashes
            }, 100);
        };

        el.addEventListener('input', handler);
    }
}