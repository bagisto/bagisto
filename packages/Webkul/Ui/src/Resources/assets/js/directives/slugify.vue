<script>
    export default {
        bind(el, binding, vnode) {
            let handler = function(e) {
                setTimeout(function() { 
                    e.target.value = e.target.value.toString().toLowerCase()
                        .replace(/[^\w- ]+/g,'')
                        .trim()
                        .replace(/ +/g,'-');
                }, 100);
            }

            el.addEventListener('input', handler);
        }
    }
</script>