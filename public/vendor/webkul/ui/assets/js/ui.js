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
__webpack_require__(22);
module.exports = __webpack_require__(24);


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

Vue.component('flash-wrapper', __webpack_require__(3));
Vue.component('flash', __webpack_require__(6));
Vue.component('accordian', __webpack_require__(9));
Vue.component('tree-view', __webpack_require__(12));
Vue.component('tree-item', __webpack_require__(14));
Vue.component('tree-checkbox', __webpack_require__(16));
Vue.component('modal', __webpack_require__(19));

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
    _vm._l(_vm.flashes, function(flash, index) {
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

/* harmony default export */ __webpack_exports__["default"] = ({
    props: {
        title: String,
        id: String,
        className: String,
        active: Boolean
    },

    data: function data() {
        return {
            isActive: false
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
/* 11 */
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
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(13)
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
/* 13 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });


/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'tree-view',

    inheritAttrs: false,

    props: {
        idField: {
            type: String,
            required: false,
            default: 'id'
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

        valueField: {
            type: String,
            required: false,
            default: 'value'
        },

        items: {
            type: [Array, String, Object],
            required: false,
            default: function _default() {
                return [];
            }
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
                    captionField: this.captionField,
                    childrenField: this.childrenField,
                    valueField: this.valueField,
                    idField: this.idField
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
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(15)
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
/* 15 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

/* harmony default export */ __webpack_exports__["default"] = ({
    name: 'tree-view',

    inheritAttrs: false,

    props: {
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

            return this.$createElement('tree-checkbox', {
                props: {
                    id: this.items[this.idField],
                    label: this.caption,
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
                    captionField: this.captionField,
                    childrenField: this.childrenField,
                    valueField: this.valueField,
                    idField: this.idField
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
        }
    },

    render: function render(createElement) {
        return createElement('div', {
            class: ['tree-item', 'active', this.hasChildren ? 'has-children' : '']
        }, [this.generateIcon(), this.generateRoot()].concat(_toConsumableArray(this.generateChildren())));
    }
});

/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(17)
/* template */
var __vue_template__ = __webpack_require__(18)
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
/* 17 */
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

    props: ['id', 'label', 'modelValue', 'inputValue', 'value'],

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
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("span", { staticClass: "checkbox" }, [
    _c("input", {
      attrs: { type: "checkbox", id: _vm.id, name: "permissions[]" },
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
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(20)
/* template */
var __vue_template__ = __webpack_require__(21)
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
/* 20 */
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
/* 21 */
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
/* 22 */
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
/* 23 */,
/* 24 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);