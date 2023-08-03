export default {
    install: (app, options) => {
        app.config.globalProperties.$debounce = (fn, delay) => {
            var timeoutID = null;
            
            return (...args) => {
                alert(1111)
                clearTimeout(timeoutID);

                var args = arguments;
                var that = this;
                
                timeoutID = setTimeout(function () {
                    fn.apply(that, args);
                }, delay);
            }
        }
    }
}