/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
__webpack_require__(51);
module.exports = __webpack_require__(52);


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

Vue.component("flash-wrapper", __webpack_require__(3));
Vue.component("flash", __webpack_require__(6));
Vue.component("tabs", __webpack_require__(9));
Vue.component("tab", __webpack_require__(12));
Vue.component("accordian", __webpack_require__(15));
Vue.component("tree-view", __webpack_require__(18));
Vue.component("tree-item", __webpack_require__(20));
Vue.component("tree-checkbox", __webpack_require__(22));
Vue.component("tree-radio", __webpack_require__(25));
Vue.component("modal", __webpack_require__(28));
Vue.component("image-upload", __webpack_require__(31));
Vue.component("image-wrapper", __webpack_require__(39));
Vue.component("image-item", __webpack_require__(42));
Vue.directive("slugify", __webpack_require__(45));
Vue.directive("code", __webpack_require__(47));
Vue.directive("alert", __webpack_require__(49));

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/flash-wrapper.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-34b58a1a", Component.options)
  } else {
    hotAPI.reload("data-v-34b58a1a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            uid: 1,

            flashes: []
        };
    },

    methods: {
        addFlash: function addFlash(flash) {
            flash.uid = this.uid++;
            this.flashes.push(flash);
        },

        removeFlash: function removeFlash(flash) {
            var index = this.flashes.indexOf(flash);

            this.flashes.splice(index, 1);
        }
    }
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "transition-group",
    {
      staticClass: "alert-wrapper",
      attrs: { tag: "div", name: "flash-wrapper" }
    },
    _vm._l(_vm.flashes, function(flash) {
      return _c("flash", {
        key: flash.uid,
        attrs: { flash: flash },
        on: {
          onRemoveFlash: function($event) {
            _vm.removeFlash($event)
          }
        }
      })
    })
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-34b58a1a", module.exports)
  }
}

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(7)
/* template */
var __vue_template__ = __webpack_require__(8)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/flash.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-feee1d58", Component.options)
  } else {
    hotAPI.reload("data-v-feee1d58", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 7 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['flash'],

    created: function created() {
        var this_this = this;
        setTimeout(function () {
            this_this.$emit('onRemoveFlash', this_this.flash);
        }, 5000);
    },

    methods: {
        remove: function remove() {
            this.$emit('onRemoveFlash', this.flash);
        }
    }
});

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "alert", class: _vm.flash.type }, [
    _c("span", {
      staticClass: "icon white-cross-sm-icon",
      on: { click: _vm.remove }
    }),
    _vm._v(" "),
    _c("p", [_vm._v(_vm._s(_vm.flash.message))])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-feee1d58", module.exports)
  }
}

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(10)
/* template */
var __vue_template__ = __webpack_require__(11)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/tabs/tabs.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7e97b353", Component.options)
  } else {
    hotAPI.reload("data-v-7e97b353", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 10 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            tabs: []
        };
    },

    created: function created() {
        this.tabs = this.$children;
    },


    methods: {
        selectTab: function selectTab(selectedTab) {
            this.tabs.forEach(function (tab) {
                tab.isActive = tab.name == selectedTab.name;
            });
        }
    }
});

/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("div", { staticClass: "tabs" }, [
      _c(
        "ul",
        _vm._l(_vm.tabs, function(tab) {
          return _c(
            "li",
            {
              class: { active: tab.isActive },
              on: {
                click: function($event) {
                  _vm.selectTab(tab)
                }
              }
            },
            [_c("a", [_vm._v(_vm._s(tab.name))])]
          )
        })
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "tabs-content" }, [_vm._t("default")], 2)
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-7e97b353", module.exports)
  }
}

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(13)
/* template */
var __vue_template__ = __webpack_require__(14)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/tabs/tab.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-299e3060", Component.options)
  } else {
    hotAPI.reload("data-v-299e3060", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 13 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        name: {
            required: true
        },

        selected: {
            default: false
        }
    },

    data: function data() {
        return {
            isActive: false
        };
    },
    mounted: function mounted() {
        this.isActive = this.selected;
    }
});

/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      directives: [
        {
          name: "show",
          rawName: "v-show",
          value: _vm.isActive,
          expression: "isActive"
        }
      ]
    },
    [_vm._t("default")],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-299e3060", module.exports)
  }
}

/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(16)
/* template */
var __vue_template__ = __webpack_require__(17)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/accordian.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-d9e5880c", Component.options)
  } else {
    hotAPI.reload("data-v-d9e5880c", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 16 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        title: String,
        id: String,
        className: String,
        active: Boolean
    },

    inject: ['$validator'],

    data: function data() {
        return {
            isActive: false,
            imageData: ''
        };
    },

    mounted: function mounted() {
        this.isActive = this.active;
    },

    methods: {
        toggleAccordion: function toggleAccordion() {
            this.isActive = !this.isActive;
        }
    },

    computed: {
        iconClass: function iconClass() {
            return {
                'accordian-down-icon': !this.isActive,
                'accordian-up-icon': this.isActive
            };
        }
    }
});

/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    {
      staticClass: "accordian",
      class: [_vm.isActive ? "active" : "", _vm.className],
      attrs: { id: _vm.id }
    },
    [
      _c(
        "div",
        {
          staticClass: "accordian-header",
          on: {
            click: function($event) {
              _vm.toggleAccordion()
            }
          }
        },
        [
          _vm._t("header", [
            _vm._v("\n            " + _vm._s(_vm.title) + "\n            "),
            _c("i", { staticClass: "icon", class: _vm.iconClass })
          ])
        ],
        2
      ),
      _vm._v(" "),
      _c("div", { staticClass: "accordian-content" }, [_vm._t("body")], 2)
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-d9e5880c", module.exports)
  }
}

/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(19)
/* template */
var __vue_template__ = null
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/tree-view/tree-view.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2c07aa7d", Component.options)
  } else {
    hotAPI.reload("data-v-2c07aa7d", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 19 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'tree-view',

    inheritAttrs: false,

    props: {
        inputType: {
            type: String,
            required: false,
            default: 'checkbox'
        },

        nameField: {
            type: String,
            required: false,
            default: 'permissions'
        },

        idField: {
            type: String,
            required: false,
            default: 'id'
        },

        valueField: {
            type: String,
            required: false,
            default: 'value'
        },

        captionField: {
            type: String,
            required: false,
            default: 'name'
        },

        childrenField: {
            type: String,
            required: false,
            default: 'children'
        },

        items: {
            type: [Array, String, Object],
            required: false,
            default: function _default() {
                return [];
            }
        },

        behavior: {
            type: String,
            required: false,
            default: 'reactive'
        },

        value: {
            type: [Array, String, Object],
            required: false,
            default: function _default() {
                return [];
            }
        }
    },

    data: function data() {
        return {
            finalValues: []
        };
    },

    computed: {
        savedValues: function savedValues() {
            if (!this.value) return [];

            if (this.inputType == 'radio') return [this.value];

            return typeof this.value == 'string' ? JSON.parse(this.value) : this.value;
        }
    },

    methods: {
        generateChildren: function generateChildren() {
            var childElements = [];

            var items = typeof this.items == 'string' ? JSON.parse(this.items) : this.items;

            for (var key in items) {
                childElements.push(this.generateTreeItem(items[key]));
            }

            return childElements;
        },
        generateTreeItem: function generateTreeItem(item) {
            var _this = this;

            return this.$createElement('tree-item', {
                props: {
                    items: item,
                    value: this.finalValues,
                    savedValues: this.savedValues,
                    nameField: this.nameField,
                    inputType: this.inputType,
                    captionField: this.captionField,
                    childrenField: this.childrenField,
                    valueField: this.valueField,
                    idField: this.idField,
                    behavior: this.behavior
                },
                on: {
                    input: function input(selection) {
                        _this.finalValues = selection;
                    }
                }
            });
        }
    },

    render: function render(createElement) {
        return createElement('div', {
            class: ['tree-container']
        }, [this.generateChildren()]);
    }
});

/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(21)
/* template */
var __vue_template__ = null
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/tree-view/tree-item.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2af003eb", Component.options)
  } else {
    hotAPI.reload("data-v-2af003eb", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 21 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'tree-view',

    inheritAttrs: false,

    props: {
        inputType: String,

        nameField: String,

        idField: String,

        captionField: String,

        childrenField: String,

        valueField: String,

        items: {
            type: [Array, String, Object],
            required: false,
            default: null
        },

        value: {
            type: Array,
            required: false,
            default: null
        },

        behavior: {
            type: String,
            required: false,
            default: 'reactive'
        },

        savedValues: {
            type: Array,
            required: false,
            default: null
        }
    },

    created: function created() {
        var index = this.savedValues.indexOf(this.items[this.valueField]);
        if (index !== -1) {
            this.value.push(this.items);
        }
    },


    computed: {
        caption: function caption() {
            return this.items[this.captionField];
        },
        allChildren: function allChildren() {
            var _this = this;

            var leafs = [];
            var searchTree = function searchTree(items) {
                if (!!items[_this.childrenField] && _this.getLength(items[_this.childrenField]) > 0) {
                    if (_typeof(items[_this.childrenField]) == 'object') {
                        for (var key in items[_this.childrenField]) {
                            searchTree(items[_this.childrenField][key]);
                        }
                    } else {
                        items[_this.childrenField].forEach(function (child) {
                            return searchTree(child);
                        });
                    }
                } else {
                    leafs.push(items);
                }
            };

            searchTree(this.items);

            return leafs;
        },
        hasChildren: function hasChildren() {
            return !!this.items[this.childrenField] && this.getLength(this.items[this.childrenField]) > 0;
        },
        hasSelection: function hasSelection() {
            return !!this.value && this.value.length > 0;
        },
        isAllChildrenSelected: function isAllChildrenSelected() {
            var _this2 = this;

            return this.hasChildren && this.hasSelection && this.allChildren.every(function (leaf) {
                return _this2.value.some(function (sel) {
                    return sel[_this2.idField] === leaf[_this2.idField];
                });
            });
        },
        isSomeChildrenSelected: function isSomeChildrenSelected() {
            var _this3 = this;

            return this.hasChildren && this.hasSelection && this.allChildren.some(function (leaf) {
                return _this3.value.some(function (sel) {
                    return sel[_this3.idField] === leaf[_this3.idField];
                });
            });
        }
    },

    methods: {
        getLength: function getLength(items) {
            if ((typeof items === 'undefined' ? 'undefined' : _typeof(items)) == 'object') {
                var length = 0;

                for (var item in items) {
                    length++;
                }

                return length;
            }

            return items.length;
        },
        generateRoot: function generateRoot() {
            var _this4 = this;

            if (this.inputType == 'checkbox') {
                if (this.behavior == 'reactive') {
                    return this.$createElement('tree-checkbox', {
                        props: {
                            id: this.items[this.idField],
                            label: this.caption,
                            nameField: this.nameField,
                            modelValue: this.items[this.valueField],
                            inputValue: this.hasChildren ? this.isSomeChildrenSelected : this.value,
                            value: this.hasChildren ? this.isAllChildrenSelected : this.items
                        },
                        on: {
                            change: function change(selection) {
                                if (_this4.hasChildren) {
                                    if (_this4.isAllChildrenSelected) {
                                        _this4.allChildren.forEach(function (leaf) {
                                            var index = _this4.value.indexOf(leaf);
                                            _this4.value.splice(index, 1);
                                        });
                                    } else {
                                        _this4.allChildren.forEach(function (leaf) {
                                            var exists = false;
                                            _this4.value.forEach(function (item) {
                                                if (item['key'] == leaf['key']) {
                                                    exists = true;
                                                }
                                            });

                                            if (!exists) {
                                                _this4.value.push(leaf);
                                            }
                                        });
                                    }

                                    _this4.$emit('input', _this4.value);
                                } else {
                                    _this4.$emit('input', selection);
                                }
                            }
                        }
                    });
                } else {
                    return this.$createElement('tree-checkbox', {
                        props: {
                            id: this.items[this.idField],
                            label: this.caption,
                            nameField: this.nameField,
                            modelValue: this.items[this.valueField],
                            inputValue: this.value,
                            value: this.items
                        }
                    });
                }
            } else if (this.inputType == 'radio') {
                return this.$createElement('tree-radio', {
                    props: {
                        id: this.items[this.idField],
                        label: this.caption,
                        nameField: this.nameField,
                        modelValue: this.items[this.valueField],
                        value: this.savedValues
                    }
                });
            }
        },
        generateChild: function generateChild(child) {
            var _this5 = this;

            return this.$createElement('tree-item', {
                on: {
                    input: function input(selection) {
                        _this5.$emit('input', selection);
                    }
                },
                props: {
                    items: child,
                    value: this.value,
                    savedValues: this.savedValues,
                    nameField: this.nameField,
                    inputType: this.inputType,
                    captionField: this.captionField,
                    childrenField: this.childrenField,
                    valueField: this.valueField,
                    idField: this.idField,
                    behavior: this.behavior
                }
            });
        },
        generateChildren: function generateChildren() {
            var _this6 = this;

            var childElements = [];
            if (this.items[this.childrenField]) {
                if (_typeof(this.items[this.childrenField]) == 'object') {
                    for (var key in this.items[this.childrenField]) {
                        childElements.push(this.generateChild(this.items[this.childrenField][key]));
                    }
                } else {
                    this.items[this.childrenField].forEach(function (child) {
                        childElements.push(_this6.generateChild(child));
                    });
                }
            }

            return childElements;
        },
        generateIcon: function generateIcon() {
            var _this7 = this;

            return this.$createElement('i', {
                class: ['expand-icon'],
                on: {
                    click: function click(selection) {
                        _this7.$el.classList.toggle("active");
                    }
                }
            });
        },
        generateFolderIcon: function generateFolderIcon() {
            return this.$createElement('i', {
                class: ['icon', 'folder-icon']
            });
        }
    },

    render: function render(createElement) {
        return createElement('div', {
            class: ['tree-item', 'active', this.hasChildren ? 'has-children' : '']
        }, [this.generateIcon(), this.generateFolderIcon(), this.generateRoot()].concat(_toConsumableArray(this.generateChildren())));
    }
});

/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(23)
/* template */
var __vue_template__ = __webpack_require__(24)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/tree-view/tree-checkbox.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0c27ec9b", Component.options)
  } else {
    hotAPI.reload("data-v-0c27ec9b", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 23 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'tree-checkbox',

    props: ['id', 'label', 'nameField', 'modelValue', 'inputValue', 'value'],

    computed: {
        isMultiple: function isMultiple() {
            return Array.isArray(this.internalValue);
        },
        isActive: function isActive() {
            var _this = this;

            var value = this.value;
            var input = this.internalValue;

            if (this.isMultiple) {
                return input.some(function (item) {
                    return _this.valueComparator(item, value);
                });
            }

            return value ? this.valueComparator(value, input) : Boolean(input);
        },


        internalValue: {
            get: function get() {
                return this.lazyValue;
            },
            set: function set(val) {
                this.lazyValue = val;
                this.$emit('input', val);
            }
        }
    },

    data: function data(vm) {
        return {
            lazyValue: vm.inputValue
        };
    },

    watch: {
        inputValue: function inputValue(val) {
            this.internalValue = val;
        }
    },

    methods: {
        inputChanged: function inputChanged() {
            var _this2 = this;

            var value = this.value;
            var input = this.internalValue;

            if (this.isMultiple) {
                var length = input.length;

                input = input.filter(function (item) {
                    return !_this2.valueComparator(item, value);
                });

                if (input.length === length) {
                    input.push(value);
                }
            } else {
                input = !input;
            }

            this.$emit('change', input);
        },
        valueComparator: function valueComparator(a, b) {
            var _this3 = this;

            if (a === b) return true;

            if (a !== Object(a) || b !== Object(b)) {
                return false;
            }

            var props = Object.keys(a);

            if (props.length !== Object.keys(b).length) {
                return false;
            }

            return props.every(function (p) {
                return _this3.valueComparator(a[p], b[p]);
            });
        }
    }
});

/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("span", { staticClass: "checkbox" }, [
    _c("input", {
      attrs: { type: "checkbox", id: _vm.id, name: [_vm.nameField + "[]"] },
      domProps: { value: _vm.modelValue, checked: _vm.isActive },
      on: {
        change: function($event) {
          _vm.inputChanged()
        }
      }
    }),
    _vm._v(" "),
    _c("label", { staticClass: "checkbox-view", attrs: { for: _vm.id } }),
    _vm._v(" "),
    _c("span", { attrs: { for: _vm.id } }, [_vm._v(_vm._s(_vm.label))])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-0c27ec9b", module.exports)
  }
}

/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(26)
/* template */
var __vue_template__ = __webpack_require__(27)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/tree-view/tree-radio.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-7b7bb153", Component.options)
  } else {
    hotAPI.reload("data-v-7b7bb153", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 26 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'tree-radio',

    props: ['id', 'label', 'nameField', 'modelValue', 'value'],

    computed: {
        isActive: function isActive() {
            if (this.value.length) {
                return this.value[0] == this.modelValue ? true : false;
            }

            return false;
        }
    }
});

/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("span", { staticClass: "radio" }, [
    _c("input", {
      attrs: { type: "radio", id: _vm.id, name: _vm.nameField },
      domProps: { value: _vm.modelValue, checked: _vm.isActive }
    }),
    _vm._v(" "),
    _c("label", { staticClass: "radio-view", attrs: { for: _vm.id } }),
    _vm._v(" "),
    _c("span", { attrs: { for: _vm.id } }, [_vm._v(_vm._s(_vm.label))])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-7b7bb153", module.exports)
  }
}

/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(29)
/* template */
var __vue_template__ = __webpack_require__(30)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/modal.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-463f5591", Component.options)
  } else {
    hotAPI.reload("data-v-463f5591", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 29 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['id', 'isOpen'],

    created: function created() {
        this.closeModal();
    },


    computed: {
        isModalOpen: function isModalOpen() {
            this.addClassToBody();

            return this.isOpen;
        }
    },

    methods: {
        closeModal: function closeModal() {
            this.$root.$set(this.$root.modalIds, this.id, false);
        },
        addClassToBody: function addClassToBody() {
            var body = document.querySelector("body");
            if (this.isOpen) {
                body.classList.add("modal-open");
            } else {
                body.classList.remove("modal-open");
            }
        }
    }
});

/***/ }),
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.isModalOpen
    ? _c("div", { staticClass: "modal-container" }, [
        _c(
          "div",
          { staticClass: "modal-header" },
          [
            _vm._t("header", [
              _vm._v("\n            Default header\n        ")
            ]),
            _vm._v(" "),
            _c("i", {
              staticClass: "icon remove-icon",
              on: { click: _vm.closeModal }
            })
          ],
          2
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "modal-body" },
          [_vm._t("body", [_vm._v("\n            Default body\n        ")])],
          2
        )
      ])
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-463f5591", module.exports)
  }
}

/***/ }),
/* 31 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(32)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(37)
/* template */
var __vue_template__ = __webpack_require__(38)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/image/image-upload.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5431fa9a", Component.options)
  } else {
    hotAPI.reload("data-v-5431fa9a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(33);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(35)("4d51c808", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../../node_modules/css-loader/index.js!../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5431fa9a\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./image-upload.vue", function() {
     var newContent = require("!!../../../../../../node_modules/css-loader/index.js!../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5431fa9a\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./image-upload.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(34)(false);
// imports


// module
exports.push([module.i, "\n.preview-wrapper{\n    height:200px;\n    width:200px;\n    padding:5px;\n}\n.image-preview{\n    height:190px;\n    width:190px;\n}\n", ""]);

// exports


/***/ }),
/* 34 */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(36)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),
/* 36 */
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),
/* 37 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({

    data: function data() {
        return {
            sample: "",
            image_file: "",
            file: null,
            newImage: ""
        };
    },

    mounted: function mounted() {

        this.sample = "";

        var element = this.$el.getElementsByTagName("input")[0];

        var this_this = this;

        element.onchange = function () {
            var fReader = new FileReader();

            fReader.readAsDataURL(element.files[0]);

            fReader.onload = function (event) {
                this.img = document.getElementsByTagName("input")[0];

                this.img.src = event.target.result;

                this_this.newImage = this.img.src;

                this_this.changePreview();
            };
        };
    },

    methods: {
        removePreviewImage: function removePreviewImage() {
            this.sample = "";
        },

        changePreview: function changePreview() {
            this.sample = this.newImage;
        }
    },

    computed: {
        getInputImage: function getInputImage() {
            console.log(this.imageData);
        }
    }
});

/***/ }),
/* 38 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "preview-image" },
    [
      _vm._t("default"),
      _vm._v(" "),
      _c("div", { staticClass: "preview-wrapper" }, [
        _c("img", { staticClass: "image-preview", attrs: { src: _vm.sample } })
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "remove-preview" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-md btn-primary",
            on: {
              click: function($event) {
                $event.preventDefault()
                return _vm.removePreviewImage($event)
              }
            }
          },
          [_vm._v("Remove Image")]
        )
      ])
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-5431fa9a", module.exports)
  }
}

/***/ }),
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(40)
/* template */
var __vue_template__ = __webpack_require__(41)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/image/image-wrapper.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2115de51", Component.options)
  } else {
    hotAPI.reload("data-v-2115de51", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 40 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        buttonLabel: {
            type: String,
            required: false,
            default: 'Add Image'
        },

        removeButtonLabel: {
            type: String,
            required: false,
            default: 'Remove Image'
        },

        inputName: {
            type: String,
            required: false,
            default: 'attachments'
        },

        images: {
            type: Array | String,
            required: false,
            default: function _default() {
                return [];
            }
        },

        multiple: {
            type: Boolean,
            required: false,
            default: true
        }
    },

    data: function data() {
        return {
            imageCount: 0,
            items: []
        };
    },

    created: function created() {
        var this_this = this;

        if (this.multiple) {
            this.images.forEach(function (image) {
                this_this.items.push(image);

                this_this.imageCount++;
            });
        } else {
            if (this.images && this.images != '') {
                this.items.push({ 'id': 'image_' + this.imageCount, 'url': this.images });

                this.imageCount++;
            }
        }
    },


    methods: {
        createFileType: function createFileType() {
            var this_this = this;

            if (!this.multiple) {
                this.items.forEach(function (image) {
                    this_this.removeImage(image);
                });
            }

            this.imageCount++;

            this.items.push({ 'id': 'image_' + this.imageCount });
        },
        removeImage: function removeImage(image) {
            var index = this.items.indexOf(image);

            Vue.delete(this.items, index);
        }
    }

});

/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "div",
      { staticClass: "image-wrapper" },
      _vm._l(_vm.items, function(image, index) {
        return _c("image-item", {
          key: image.id,
          attrs: {
            image: image,
            "input-name": _vm.inputName,
            "remove-button-label": _vm.removeButtonLabel
          },
          on: {
            onRemoveImage: function($event) {
              _vm.removeImage($event)
            }
          }
        })
      })
    ),
    _vm._v(" "),
    _c(
      "label",
      {
        staticClass: "btn btn-lg btn-primary",
        staticStyle: { display: "inline-block", width: "auto" },
        on: { click: _vm.createFileType }
      },
      [_vm._v(_vm._s(_vm.buttonLabel))]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-2115de51", module.exports)
  }
}

/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(43)
/* template */
var __vue_template__ = __webpack_require__(44)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/components/image/image-item.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-23317545", Component.options)
  } else {
    hotAPI.reload("data-v-23317545", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 43 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        inputName: {
            type: String,
            required: false,
            default: 'attachments'
        },

        removeButtonLabel: {
            type: String
        },

        image: {
            type: Object,
            required: false,
            default: null
        }
    },

    data: function data() {
        return {
            imageData: ''
        };
    },

    mounted: function mounted() {
        if (this.image.id && this.image.url) {
            this.imageData = this.image.url;
        }
    },


    computed: {
        finalInputName: function finalInputName() {
            return this.inputName + '[' + this.image.id + ']';
        }
    },

    methods: {
        addImageView: function addImageView() {
            var _this = this;

            var imageInput = this.$refs.imageInput;

            if (imageInput.files && imageInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    _this.imageData = e.target.result;
                };

                reader.readAsDataURL(imageInput.files[0]);
            }
        },
        removeImage: function removeImage() {
            this.$emit('onRemoveImage', this.image);
        }
    }
});

/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "label",
    {
      staticClass: "image-item",
      class: { "has-image": _vm.imageData.length > 0 },
      attrs: { for: _vm._uid }
    },
    [
      _c("input", { attrs: { type: "hidden", name: _vm.finalInputName } }),
      _vm._v(" "),
      _c("input", {
        ref: "imageInput",
        attrs: {
          type: "file",
          accept: "image/*",
          name: _vm.finalInputName,
          id: _vm._uid
        },
        on: {
          change: function($event) {
            _vm.addImageView($event)
          }
        }
      }),
      _vm._v(" "),
      _vm.imageData.length > 0
        ? _c("img", { staticClass: "preview", attrs: { src: _vm.imageData } })
        : _vm._e(),
      _vm._v(" "),
      _c(
        "label",
        {
          staticClass: "remove-image",
          on: {
            click: function($event) {
              _vm.removeImage()
            }
          }
        },
        [_vm._v(_vm._s(_vm.removeButtonLabel))]
      )
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-23317545", module.exports)
  }
}

/***/ }),
/* 45 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(46)
/* template */
var __vue_template__ = null
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/directives/slugify.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0e090843", Component.options)
  } else {
    hotAPI.reload("data-v-0e090843", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 46 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });

/* harmony default export */ __webpack_exports__["default"] = ({
    bind: function bind(el, binding, vnode) {
        var handler = function handler(e) {
            setTimeout(function () {
                e.target.value = e.target.value.toString().toLowerCase().replace(/[^\w- ]+/g, '').trim().replace(/ +/g, '-');
            }, 100);
        };

        el.addEventListener('input', handler);
    }
});

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(48)
/* template */
var __vue_template__ = null
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/directives/code.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-103582ea", Component.options)
  } else {
    hotAPI.reload("data-v-103582ea", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 48 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });

/* harmony default export */ __webpack_exports__["default"] = ({
    bind: function bind(el, binding, vnode) {
        var handler = function handler(e) {
            setTimeout(function () {
                e.target.value = e.target.value.toString().replace(/[^\w_ ]+/g, '').trim().replace(/ +/g, '-');
            }, 100);
        };

        el.addEventListener('input', handler);
    }
});

/***/ }),
/* 49 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(50)
/* template */
var __vue_template__ = null
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/Resources/assets/js/directives/alert.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1935bba4", Component.options)
  } else {
    hotAPI.reload("data-v-1935bba4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 50 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });

/* harmony default export */ __webpack_exports__["default"] = ({
    bind: function bind(el, binding, vnode) {
        el.addEventListener('click', function (e) {
            e.preventDefault();

            var message = "Are your sure you want to perform this action ?";

            if (binding.value && binding.value != '') message = binding.value;

            if (confirm(message)) {
                window.location.href = el.href;
            }
        });
    }
});

/***/ }),
/* 51 */
/***/ (function(module, exports) {

$(function () {
    $(document).click(function (e) {
        var target = e.target;
        if (!$(target).parents('.dropdown-open').length || $(target).is('li') || $(target).is('a')) {
            $('.dropdown-list').hide();
            $('.dropdown-toggle').removeClass('active');
        }
    });

    $('body').delegate('.dropdown-toggle', 'click', function (e) {
        toggleDropdown(e);
    });

    function toggleDropdown(e) {
        var currentElement = $(e.currentTarget);
        if (currentElement.attr('disabled') == "disabled") return;

        $('.dropdown-list').hide();
        if (currentElement.hasClass('active')) {
            currentElement.removeClass('active');
        } else {
            currentElement.addClass('active');
            currentElement.parent().find('.dropdown-list').fadeIn(100);
            currentElement.parent().addClass('dropdown-open');
            autoDropupDropdown();
        }
    }

    $('.dropdown-list .search-box .control').on('input', function () {
        var currentElement = $(this);
        currentElement.parents(".dropdown-list").find('li').each(function () {
            var text = $(this).text().trim().toLowerCase();
            var value = $(this).attr('data-id');
            if (value) {
                var isTextContained = text.search(currentElement.val().toLowerCase());
                var isValueContained = value.search(currentElement.val());
                if (isTextContained < 0 && isValueContained < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                    flag = 1;
                }
            } else {
                var isTextContained = text.search(currentElement.val().toLowerCase());
                if (isTextContained < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            }
        });
    });

    function autoDropupDropdown() {
        dropdown = $(".dropdown-open");
        if (!dropdown.find('.dropdown-list').hasClass('top-left') && !dropdown.find('.dropdown-list').hasClass('top-right') && dropdown.length) {
            dropdown = dropdown.find('.dropdown-list');
            height = dropdown.height() + 50;
            var topOffset = dropdown.offset().top - 70;
            var bottomOffset = $(window).height() - topOffset - dropdown.height();

            if (bottomOffset > topOffset || height < bottomOffset) {
                dropdown.removeClass("bottom");
                if (dropdown.hasClass('top-right')) {
                    dropdown.removeClass('top-right');
                    dropdown.addClass('bottom-right');
                } else if (dropdown.hasClass('top-left')) {
                    dropdown.removeClass('top-left');
                    dropdown.addClass('bottom-left');
                }
            } else {
                if (dropdown.hasClass('bottom-right')) {
                    dropdown.removeClass('bottom-right');
                    dropdown.addClass('top-right');
                } else if (dropdown.hasClass('bottom-left')) {
                    dropdown.removeClass('bottom-left');
                    dropdown.addClass('top-left');
                }
            }
        }
    }

    $('div').scroll(function () {
        autoDropupDropdown();
    });
});

/***/ }),
/* 52 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);