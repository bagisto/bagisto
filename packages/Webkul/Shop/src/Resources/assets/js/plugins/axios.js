/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
//
// let requestCache = localStorage.getItem('requestCache')
// if (!requestCache) {
//     requestCache = {
//         cache: {},
//         throttled: {},
//         isCached: function (config) {
//             return this.cache[config.url] !== undefined
//         },
//         setCachedResponse: function (config, response) {
//             this.cache[config.url] = response
//         },
//         getCachedResponse: function (config) {
//             return this.cache[config.url]
//         },
//         shouldThrottle: function (config) {
//             return this.throttled[config.url] !== undefined
//         },
//         waitForResponse: function (config) {
//             this.throttled[config.url] = true
//
//             setTimeout(() => {
//                 delete this.throttled[config.url]
//             }, 1000)
//
//             return this.cache[config.url]
//         }
//     }
// }
//
// // This should be the *last* request interceptor to add
// window.axios.interceptors.request.use(function (config) {
//     /* check the cache, if hit, then intentionally throw
//      * this will cause the XHR call to be skipped
//      * but the error is still handled by response interceptor
//      * we can then recover from error to the cached response
//      **/
//     if (requestCache.isCached(config)) {
//         const skipXHRError = new Error('skip')
//         skipXHRError.isSkipXHR = true
//         skipXHRError.request = config
//         throw skipXHRError
//     } else {
//         /* if not cached yet
//          * check if request should be throttled
//          * then open up the cache to wait for a response
//          **/
//         if (requestCache.shouldThrottle(config)) {
//             requestCache.waitForResponse(config)
//         }
//         return config;
//     }
// });
//
//
// // This should be the *first* response interceptor to add
// axios.interceptors.response.use(function (response) {
//     requestCache.setCachedResponse(response.config, response)
//     return response;
// }, function (error) {
//     /* recover from error back to normality
//      * but this time we use an cached response result
//      **/
//     if (error.isSkipXHR) {
//         return requestCache.getCachedResponse(error.request)
//     }
//     return Promise.reject(error);
// });
export default {
    install(app) {
        app.config.globalProperties.$axios = axios;
    },
};
