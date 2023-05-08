/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Main vue bundler.
 */
import { createApp } from "vue/dist/vue.esm-bundler";

/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";
import AllRules from "@vee-validate/rules";

configure({
    generateMessage: localize({
        en,
    }),
    validateOnBlur: true,
    validateOnInput: true,
    validateOnChange: true,
});

Object.keys(AllRules).forEach((rule) => {
    defineRule(rule, AllRules[rule]);
});

// import Vue from 'vue';
// import VeeValidate from 'vee-validate';
// import de from 'vee-validate/dist/locale/de';
// import ar from 'vee-validate/dist/locale/ar';
// import fa from 'vee-validate/dist/locale/fa';
// import fr from 'vee-validate/dist/locale/fr';
// import nl from 'vee-validate/dist/locale/nl';
// import tr from 'vee-validate/dist/locale/tr';
// import hi_IN from 'vee-validate/dist/locale/hi';
// import zh_CN from 'vee-validate/dist/locale/zh_CN';
// import axios from 'axios';

// window.Vue = Vue;
// window.VeeValidate = VeeValidate;
// window.axios = axios;

// Vue.use(VeeValidate, {
//     dictionary: {
//         ar: ar,
//         de: de,
// 		fa: fa,
// 		fr: fr,
// 		nl: nl,
// 		tr: tr,
//         hi_IN: hi_IN,
//         zh_CN: zh_CN
//     },
//     events: 'input|change|blur',
// });

// Vue.prototype.$http = axios

// window.eventBus = new Vue();

// const app = new Vue({
//     el: "#app",

//     data: {
//         modalIds: {},

//         baseUrl: document.querySelector('meta[name="base-url"]').content
//     },

//     mounted: function () {
//         this.addServerErrors();

//         this.$validator.localize(document.documentElement.lang);
//     },

//     methods: {
//         onSubmit: function (e) {
//             this.toggleButtonDisable(true);

//             if(typeof tinyMCE !== 'undefined')
//                 tinyMCE.triggerSave();

//             this.$validator.validateAll().then(result => {
//                 if (result) {
//                     e.target.submit();
//                 } else {
//                     this.toggleButtonDisable(false);

//                     eventBus.$emit('onFormError')
//                 }
//             });
//         },

//         toggleButtonDisable (value) {
//             var buttons = document.getElementsByTagName("button");

//             for (var i = 0; i < buttons.length; i++) {
//                 buttons[i].disabled = value;
//             }
//         },

//         addServerErrors: function (scope = null) {
//             for (var key in serverErrors) {
//                 var inputNames = [];
//                 key.split('.').forEach(function(chunk, index) {
//                     if(index) {
//                         inputNames.push('[' + chunk + ']')
//                     } else {
//                         inputNames.push(chunk)
//                     }
//                 })

//                 var inputName = inputNames.join('');

//                 const field = this.$validator.fields.find({
//                     name: inputName,
//                     scope: scope
//                 });
//                 if (field) {
//                     this.$validator.errors.add({
//                         id: field.id,
//                         field: inputName,
//                         msg: serverErrors[key][0],
//                         scope: scope
//                     });
//                 }
//             }
//         },

//         addFlashMessages: function () {
//             const flashes = this.$refs.flashes;

//             flashMessages.forEach(function (flash) {
//                 flashes.addFlash(flash);
//             }, this);
//         },

//         showModal(id) {
//             this.$set(this.modalIds, id, true);
//         },
//     }
// });

// window.app = app;

createApp({
    components: {
        VForm: Form,
        VField: Field,
        VErrorMessage: ErrorMessage,
    },

    data() {
        return {};
    },

    methods: {
        onSubmit() {},

        onInvalidSubmit() {},
    },
}).mount("#app");
