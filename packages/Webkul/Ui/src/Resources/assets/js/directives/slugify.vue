<script>
    export default {
        bind(el, binding, vnode) {
            let handler = function (e) {
                setTimeout(function () {
                    e.target.value = e.target.value
                        .toString()
                        .toLowerCase()
                        .replace(/[^\w- ]+/g, '')

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