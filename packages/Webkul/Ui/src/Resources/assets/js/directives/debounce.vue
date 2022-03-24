<script>
    const debounce = require('../debounce')

    export default {
        bind(el, binding, vnode) {
            if (binding.value !== binding.oldValue) {
                el.oninput = debounce(function (evt) {
                    el.dispatchEvent(new Event('change'))
                }, parseInt(binding.value) || 500)
            }
        }
    }
</script>