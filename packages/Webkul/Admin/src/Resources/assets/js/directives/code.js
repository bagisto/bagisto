export default {
    mounted(el, binding, vnode) {
        let handler = function(e) {
            setTimeout(function() { 
                e.target.value = e.target.value.toString()
                    .replace(/[^\w_ ]+/g,'')
                    .trim()
                    .replace(/ +/g,'-');
            }, 100);
        }

        el.addEventListener('input', handler);
    }
}