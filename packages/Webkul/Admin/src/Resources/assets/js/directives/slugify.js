export default {
    mounted(el, binding) {
        let handler = function (e) {
            setTimeout(function () {
                e.target.value = e.target.value
                    .toString()
                    .toLowerCase()
                    .replace(/[^\w\u0621-\u064A\u4e00-\u9fa5\u3402-\uFA6D\u3041-\u30A0\u30A0-\u31FF- ]+/g, '')

                    // replace whitespace with dashes
                    .replace(/ +/g, '-')

                    // avoid having multiple dashes (---- translates into -)
                    .replace('![-\s]+!u', '-')
                    .trim();
            }, 100);
        };

        el.addEventListener('input', handler);
    }
}