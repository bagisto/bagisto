var timeoutID = null;

export default {
    mounted(el, binding) {
        let handler = function (e) {
            if (binding.value !== binding.oldValue) {
                clearTimeout(timeoutID)
                
                timeoutID = setTimeout(function () {
                    el.dispatchEvent(new Event('change'))
                }, binding.value || 500)
            }
        };

        el.addEventListener('input', handler);
    }
}