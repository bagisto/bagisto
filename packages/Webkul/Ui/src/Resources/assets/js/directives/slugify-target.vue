<script>
    export default {
        bind(el, binding, vnode) {
            let handler = function (e) {
                setTimeout(function () {
                    var target = document.getElementById(binding.value);

                    target.value = e.target.value.toString()
                        .toLowerCase()

                        .normalize('NFKD') // Normalize Unicode
                        .replace(/[^\w\u0621-\u064A\u4e00-\u9fa5\u3402-\uFA6D\u3041-\u30A0\u30A0-\u31FF- ]+/g, '')

                        // replace whitespaces with dashes
                        .replace(/ +/g, '-')

                        // avoid having multiple dashes (---- translates into -)
                        .replace('![-\s]+!u', '-')
                        .trim();
                }, 100);
            };

            el.addEventListener('input', handler);
        }
    }
</script>
