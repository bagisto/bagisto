<script>
export default {
    bind(el, binding, vnode) {
        let timeout = 0;
        let handler = function (e) {
            if (timeout > 0) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(function () {
                let slug = e.target.value
                    .toString()
                    .toLowerCase()
                    .replace(/[^\w\u0621-\u064A\u4e00-\u9fa5\u3402-\uFA6D\u3041-\u30A0\u30A0-\u31FF- ]+/g, '')
                    // replace whitespaces with dashes
                    .replace(/ +/g, '-')
                    // avoid having multiple dashes (---- translates into -)
                    .replace('![-\s]+!u', '-')
                    .trim();
                axios.post('/admin/slug/validate/' + (e.target.getAttribute('data-slug-args') || ''), {
                    slug: slug
                }).then((response) => {
                    e.target.value = response.data.slug;
                }).catch((error) => {
                    e.target.value = slug;
                });
            }, 500);
        };
        el.addEventListener('input', handler);
    }
}
</script>