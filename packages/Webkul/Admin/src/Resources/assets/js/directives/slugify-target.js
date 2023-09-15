export default {
    mounted(el, binding) {
        let handler = function (e) {
            setTimeout(function () {
                var target = document.getElementById(binding.arg);

                target.value = e.target.value.toString()
                    .toLowerCase()

                    .normalize('NFKD') // Normalize Unicode
                    .replace(/[^\w\u0621-\u064A\u4e00-\u9fa5\u3402-\uFA6D\u3041-\u30A0\u30A0-\u31FF- ]+/g, '')

                    // replace whitespace with dashes
                    .replace(/ +/g, '-')

                    // avoid having multiple dashes (---- translates into -)
                    .replace('![-\s]+!u', '-')
                    .trim();
                
                if (binding.value) {
                    binding.value({
                        [binding.arg]: target.value
                    }, false);    
                }
            }, 100);
        };

        el.addEventListener('input', handler);
    }
}
