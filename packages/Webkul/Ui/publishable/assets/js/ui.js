<<<<<<< HEAD
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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
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
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

/* flatpickr v4.5.2, @license MIT */
(function (global, factory) {
     true ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global.flatpickr = factory());
}(this, (function () { 'use strict';

    var pad = function pad(number) {
      return ("0" + number).slice(-2);
    };
    var int = function int(bool) {
      return bool === true ? 1 : 0;
    };
    function debounce(func, wait, immediate) {
      if (immediate === void 0) {
        immediate = false;
      }

      var timeout;
      return function () {
        var context = this,
            args = arguments;
        timeout !== null && clearTimeout(timeout);
        timeout = window.setTimeout(function () {
          timeout = null;
          if (!immediate) func.apply(context, args);
        }, wait);
        if (immediate && !timeout) func.apply(context, args);
      };
    }
    var arrayify = function arrayify(obj) {
      return obj instanceof Array ? obj : [obj];
    };

    var do_nothing = function do_nothing() {
      return undefined;
    };

    var monthToStr = function monthToStr(monthNumber, shorthand, locale) {
      return locale.months[shorthand ? "shorthand" : "longhand"][monthNumber];
    };
    var revFormat = {
      D: do_nothing,
      F: function F(dateObj, monthName, locale) {
        dateObj.setMonth(locale.months.longhand.indexOf(monthName));
      },
      G: function G(dateObj, hour) {
        dateObj.setHours(parseFloat(hour));
      },
      H: function H(dateObj, hour) {
        dateObj.setHours(parseFloat(hour));
      },
      J: function J(dateObj, day) {
        dateObj.setDate(parseFloat(day));
      },
      K: function K(dateObj, amPM, locale) {
        dateObj.setHours(dateObj.getHours() % 12 + 12 * int(new RegExp(locale.amPM[1], "i").test(amPM)));
      },
      M: function M(dateObj, shortMonth, locale) {
        dateObj.setMonth(locale.months.shorthand.indexOf(shortMonth));
      },
      S: function S(dateObj, seconds) {
        dateObj.setSeconds(parseFloat(seconds));
      },
      U: function U(_, unixSeconds) {
        return new Date(parseFloat(unixSeconds) * 1000);
      },
      W: function W(dateObj, weekNum) {
        var weekNumber = parseInt(weekNum);
        return new Date(dateObj.getFullYear(), 0, 2 + (weekNumber - 1) * 7, 0, 0, 0, 0);
      },
      Y: function Y(dateObj, year) {
        dateObj.setFullYear(parseFloat(year));
      },
      Z: function Z(_, ISODate) {
        return new Date(ISODate);
      },
      d: function d(dateObj, day) {
        dateObj.setDate(parseFloat(day));
      },
      h: function h(dateObj, hour) {
        dateObj.setHours(parseFloat(hour));
      },
      i: function i(dateObj, minutes) {
        dateObj.setMinutes(parseFloat(minutes));
      },
      j: function j(dateObj, day) {
        dateObj.setDate(parseFloat(day));
      },
      l: do_nothing,
      m: function m(dateObj, month) {
        dateObj.setMonth(parseFloat(month) - 1);
      },
      n: function n(dateObj, month) {
        dateObj.setMonth(parseFloat(month) - 1);
      },
      s: function s(dateObj, seconds) {
        dateObj.setSeconds(parseFloat(seconds));
      },
      w: do_nothing,
      y: function y(dateObj, year) {
        dateObj.setFullYear(2000 + parseFloat(year));
      }
    };
    var tokenRegex = {
      D: "(\\w+)",
      F: "(\\w+)",
      G: "(\\d\\d|\\d)",
      H: "(\\d\\d|\\d)",
      J: "(\\d\\d|\\d)\\w+",
      K: "",
      M: "(\\w+)",
      S: "(\\d\\d|\\d)",
      U: "(.+)",
      W: "(\\d\\d|\\d)",
      Y: "(\\d{4})",
      Z: "(.+)",
      d: "(\\d\\d|\\d)",
      h: "(\\d\\d|\\d)",
      i: "(\\d\\d|\\d)",
      j: "(\\d\\d|\\d)",
      l: "(\\w+)",
      m: "(\\d\\d|\\d)",
      n: "(\\d\\d|\\d)",
      s: "(\\d\\d|\\d)",
      w: "(\\d\\d|\\d)",
      y: "(\\d{2})"
    };
    var formats = {
      Z: function Z(date) {
        return date.toISOString();
      },
      D: function D(date, locale, options) {
        return locale.weekdays.shorthand[formats.w(date, locale, options)];
      },
      F: function F(date, locale, options) {
        return monthToStr(formats.n(date, locale, options) - 1, false, locale);
      },
      G: function G(date, locale, options) {
        return pad(formats.h(date, locale, options));
      },
      H: function H(date) {
        return pad(date.getHours());
      },
      J: function J(date, locale) {
        return locale.ordinal !== undefined ? date.getDate() + locale.ordinal(date.getDate()) : date.getDate();
      },
      K: function K(date, locale) {
        return locale.amPM[int(date.getHours() > 11)];
      },
      M: function M(date, locale) {
        return monthToStr(date.getMonth(), true, locale);
      },
      S: function S(date) {
        return pad(date.getSeconds());
      },
      U: function U(date) {
        return date.getTime() / 1000;
      },
      W: function W(date, _, options) {
        return options.getWeek(date);
      },
      Y: function Y(date) {
        return date.getFullYear();
      },
      d: function d(date) {
        return pad(date.getDate());
      },
      h: function h(date) {
        return date.getHours() % 12 ? date.getHours() % 12 : 12;
      },
      i: function i(date) {
        return pad(date.getMinutes());
      },
      j: function j(date) {
        return date.getDate();
      },
      l: function l(date, locale) {
        return locale.weekdays.longhand[date.getDay()];
      },
      m: function m(date) {
        return pad(date.getMonth() + 1);
      },
      n: function n(date) {
        return date.getMonth() + 1;
      },
      s: function s(date) {
        return date.getSeconds();
      },
      w: function w(date) {
        return date.getDay();
      },
      y: function y(date) {
        return String(date.getFullYear()).substring(2);
      }
    };

    var english = {
      weekdays: {
        shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
      },
      months: {
        shorthand: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        longhand: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
      },
      daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
      firstDayOfWeek: 0,
      ordinal: function ordinal(nth) {
        var s = nth % 100;
        if (s > 3 && s < 21) return "th";

        switch (s % 10) {
          case 1:
            return "st";

          case 2:
            return "nd";

          case 3:
            return "rd";

          default:
            return "th";
        }
      },
      rangeSeparator: " to ",
      weekAbbreviation: "Wk",
      scrollTitle: "Scroll to increment",
      toggleTitle: "Click to toggle",
      amPM: ["AM", "PM"],
      yearAriaLabel: "Year"
    };

    var createDateFormatter = function createDateFormatter(_ref) {
      var _ref$config = _ref.config,
          config = _ref$config === void 0 ? defaults : _ref$config,
          _ref$l10n = _ref.l10n,
          l10n = _ref$l10n === void 0 ? english : _ref$l10n;
      return function (dateObj, frmt, overrideLocale) {
        var locale = overrideLocale || l10n;

        if (config.formatDate !== undefined) {
          return config.formatDate(dateObj, frmt, locale);
        }

        return frmt.split("").map(function (c, i, arr) {
          return formats[c] && arr[i - 1] !== "\\" ? formats[c](dateObj, locale, config) : c !== "\\" ? c : "";
        }).join("");
      };
    };
    var createDateParser = function createDateParser(_ref2) {
      var _ref2$config = _ref2.config,
          config = _ref2$config === void 0 ? defaults : _ref2$config,
          _ref2$l10n = _ref2.l10n,
          l10n = _ref2$l10n === void 0 ? english : _ref2$l10n;
      return function (date, givenFormat, timeless, customLocale) {
        if (date !== 0 && !date) return undefined;
        var locale = customLocale || l10n;
        var parsedDate;
        var date_orig = date;
        if (date instanceof Date) parsedDate = new Date(date.getTime());else if (typeof date !== "string" && date.toFixed !== undefined) parsedDate = new Date(date);else if (typeof date === "string") {
          var format = givenFormat || (config || defaults).dateFormat;
          var datestr = String(date).trim();

          if (datestr === "today") {
            parsedDate = new Date();
            timeless = true;
          } else if (/Z$/.test(datestr) || /GMT$/.test(datestr)) parsedDate = new Date(date);else if (config && config.parseDate) parsedDate = config.parseDate(date, format);else {
            parsedDate = !config || !config.noCalendar ? new Date(new Date().getFullYear(), 0, 1, 0, 0, 0, 0) : new Date(new Date().setHours(0, 0, 0, 0));
            var matched,
                ops = [];

            for (var i = 0, matchIndex = 0, regexStr = ""; i < format.length; i++) {
              var token = format[i];
              var isBackSlash = token === "\\";
              var escaped = format[i - 1] === "\\" || isBackSlash;

              if (tokenRegex[token] && !escaped) {
                regexStr += tokenRegex[token];
                var match = new RegExp(regexStr).exec(date);

                if (match && (matched = true)) {
                  ops[token !== "Y" ? "push" : "unshift"]({
                    fn: revFormat[token],
                    val: match[++matchIndex]
                  });
                }
              } else if (!isBackSlash) regexStr += ".";

              ops.forEach(function (_ref3) {
                var fn = _ref3.fn,
                    val = _ref3.val;
                return parsedDate = fn(parsedDate, val, locale) || parsedDate;
              });
            }

            parsedDate = matched ? parsedDate : undefined;
          }
        }

        if (!(parsedDate instanceof Date && !isNaN(parsedDate.getTime()))) {
          config.errorHandler(new Error("Invalid date provided: " + date_orig));
          return undefined;
        }

        if (timeless === true) parsedDate.setHours(0, 0, 0, 0);
        return parsedDate;
      };
    };
    function compareDates(date1, date2, timeless) {
      if (timeless === void 0) {
        timeless = true;
      }

      if (timeless !== false) {
        return new Date(date1.getTime()).setHours(0, 0, 0, 0) - new Date(date2.getTime()).setHours(0, 0, 0, 0);
      }

      return date1.getTime() - date2.getTime();
    }
    var getWeek = function getWeek(givenDate) {
      var date = new Date(givenDate.getTime());
      date.setHours(0, 0, 0, 0);
      date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
      var week1 = new Date(date.getFullYear(), 0, 4);
      return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
    };
    var isBetween = function isBetween(ts, ts1, ts2) {
      return ts > Math.min(ts1, ts2) && ts < Math.max(ts1, ts2);
    };
    var duration = {
      DAY: 86400000
    };

    var HOOKS = ["onChange", "onClose", "onDayCreate", "onDestroy", "onKeyDown", "onMonthChange", "onOpen", "onParseConfig", "onReady", "onValueUpdate", "onYearChange", "onPreCalendarPosition"];
    var defaults = {
      _disable: [],
      _enable: [],
      allowInput: false,
      altFormat: "F j, Y",
      altInput: false,
      altInputClass: "form-control input",
      animate: typeof window === "object" && window.navigator.userAgent.indexOf("MSIE") === -1,
      ariaDateFormat: "F j, Y",
      clickOpens: true,
      closeOnSelect: true,
      conjunction: ", ",
      dateFormat: "Y-m-d",
      defaultHour: 12,
      defaultMinute: 0,
      defaultSeconds: 0,
      disable: [],
      disableMobile: false,
      enable: [],
      enableSeconds: false,
      enableTime: false,
      errorHandler: function errorHandler(err) {
        return typeof console !== "undefined" && console.warn(err);
      },
      getWeek: getWeek,
      hourIncrement: 1,
      ignoredFocusElements: [],
      inline: false,
      locale: "default",
      minuteIncrement: 5,
      mode: "single",
      nextArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z' /></svg>",
      noCalendar: false,
      now: new Date(),
      onChange: [],
      onClose: [],
      onDayCreate: [],
      onDestroy: [],
      onKeyDown: [],
      onMonthChange: [],
      onOpen: [],
      onParseConfig: [],
      onReady: [],
      onValueUpdate: [],
      onYearChange: [],
      onPreCalendarPosition: [],
      plugins: [],
      position: "auto",
      positionElement: undefined,
      prevArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z' /></svg>",
      shorthandCurrentMonth: false,
      showMonths: 1,
      static: false,
      time_24hr: false,
      weekNumbers: false,
      wrap: false
    };

    function toggleClass(elem, className, bool) {
      if (bool === true) return elem.classList.add(className);
      elem.classList.remove(className);
    }
    function createElement(tag, className, content) {
      var e = window.document.createElement(tag);
      className = className || "";
      content = content || "";
      e.className = className;
      if (content !== undefined) e.textContent = content;
      return e;
    }
    function clearNode(node) {
      while (node.firstChild) {
        node.removeChild(node.firstChild);
      }
    }
    function findParent(node, condition) {
      if (condition(node)) return node;else if (node.parentNode) return findParent(node.parentNode, condition);
      return undefined;
    }
    function createNumberInput(inputClassName, opts) {
      var wrapper = createElement("div", "numInputWrapper"),
          numInput = createElement("input", "numInput " + inputClassName),
          arrowUp = createElement("span", "arrowUp"),
          arrowDown = createElement("span", "arrowDown");
      numInput.type = "text";
      numInput.pattern = "\\d*";
      if (opts !== undefined) for (var key in opts) {
        numInput.setAttribute(key, opts[key]);
      }
      wrapper.appendChild(numInput);
      wrapper.appendChild(arrowUp);
      wrapper.appendChild(arrowDown);
      return wrapper;
    }

    if (typeof Object.assign !== "function") {
      Object.assign = function (target) {
        if (!target) {
          throw TypeError("Cannot convert undefined or null to object");
        }

        for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
          args[_key - 1] = arguments[_key];
        }

        var _loop = function _loop() {
          var source = args[_i];

          if (source) {
            Object.keys(source).forEach(function (key) {
              return target[key] = source[key];
            });
          }
        };

        for (var _i = 0; _i < args.length; _i++) {
          _loop();
        }

        return target;
      };
    }

    var DEBOUNCED_CHANGE_MS = 300;

    function FlatpickrInstance(element, instanceConfig) {
      var self = {
        config: Object.assign({}, flatpickr.defaultConfig),
        l10n: english
      };
      self.parseDate = createDateParser({
        config: self.config,
        l10n: self.l10n
      });
      self._handlers = [];
      self._bind = bind;
      self._setHoursFromDate = setHoursFromDate;
      self._positionCalendar = positionCalendar;
      self.changeMonth = changeMonth;
      self.changeYear = changeYear;
      self.clear = clear;
      self.close = close;
      self._createElement = createElement;
      self.destroy = destroy;
      self.isEnabled = isEnabled;
      self.jumpToDate = jumpToDate;
      self.open = open;
      self.redraw = redraw;
      self.set = set;
      self.setDate = setDate;
      self.toggle = toggle;

      function setupHelperFunctions() {
        self.utils = {
          getDaysInMonth: function getDaysInMonth(month, yr) {
            if (month === void 0) {
              month = self.currentMonth;
            }

            if (yr === void 0) {
              yr = self.currentYear;
            }

            if (month === 1 && (yr % 4 === 0 && yr % 100 !== 0 || yr % 400 === 0)) return 29;
            return self.l10n.daysInMonth[month];
          }
        };
      }

      function init() {
        self.element = self.input = element;
        self.isOpen = false;
        parseConfig();
        setupLocale();
        setupInputs();
        setupDates();
        setupHelperFunctions();
        if (!self.isMobile) build();
        bindEvents();

        if (self.selectedDates.length || self.config.noCalendar) {
          if (self.config.enableTime) {
            setHoursFromDate(self.config.noCalendar ? self.latestSelectedDateObj || self.config.minDate : undefined);
          }

          updateValue(false);
        }

        setCalendarWidth();
        self.showTimeInput = self.selectedDates.length > 0 || self.config.noCalendar;
        var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

        if (!self.isMobile && isSafari) {
          positionCalendar();
        }

        triggerEvent("onReady");
      }

      function bindToInstance(fn) {
        return fn.bind(self);
      }

      function setCalendarWidth() {
        var config = self.config;
        if (config.weekNumbers === false && config.showMonths === 1) return;else if (config.noCalendar !== true) {
          window.requestAnimationFrame(function () {
            self.calendarContainer.style.visibility = "hidden";
            self.calendarContainer.style.display = "block";

            if (self.daysContainer !== undefined) {
              var daysWidth = (self.days.offsetWidth + 1) * config.showMonths;
              self.daysContainer.style.width = daysWidth + "px";
              self.calendarContainer.style.width = daysWidth + (self.weekWrapper !== undefined ? self.weekWrapper.offsetWidth : 0) + "px";
              self.calendarContainer.style.removeProperty("visibility");
              self.calendarContainer.style.removeProperty("display");
            }
          });
        }
      }

      function updateTime(e) {
        if (self.selectedDates.length === 0) return;

        if (e !== undefined && e.type !== "blur") {
          timeWrapper(e);
        }

        var prevValue = self._input.value;
        setHoursFromInputs();
        updateValue();

        if (self._input.value !== prevValue) {
          self._debouncedChange();
        }
      }

      function ampm2military(hour, amPM) {
        return hour % 12 + 12 * int(amPM === self.l10n.amPM[1]);
      }

      function military2ampm(hour) {
        switch (hour % 24) {
          case 0:
          case 12:
            return 12;

          default:
            return hour % 12;
        }
      }

      function setHoursFromInputs() {
        if (self.hourElement === undefined || self.minuteElement === undefined) return;
        var hours = (parseInt(self.hourElement.value.slice(-2), 10) || 0) % 24,
            minutes = (parseInt(self.minuteElement.value, 10) || 0) % 60,
            seconds = self.secondElement !== undefined ? (parseInt(self.secondElement.value, 10) || 0) % 60 : 0;

        if (self.amPM !== undefined) {
          hours = ampm2military(hours, self.amPM.textContent);
        }

        var limitMinHours = self.config.minTime !== undefined || self.config.minDate && self.minDateHasTime && self.latestSelectedDateObj && compareDates(self.latestSelectedDateObj, self.config.minDate, true) === 0;
        var limitMaxHours = self.config.maxTime !== undefined || self.config.maxDate && self.maxDateHasTime && self.latestSelectedDateObj && compareDates(self.latestSelectedDateObj, self.config.maxDate, true) === 0;

        if (limitMaxHours) {
          var maxTime = self.config.maxTime !== undefined ? self.config.maxTime : self.config.maxDate;
          hours = Math.min(hours, maxTime.getHours());
          if (hours === maxTime.getHours()) minutes = Math.min(minutes, maxTime.getMinutes());
          if (minutes === maxTime.getMinutes()) seconds = Math.min(seconds, maxTime.getSeconds());
        }

        if (limitMinHours) {
          var minTime = self.config.minTime !== undefined ? self.config.minTime : self.config.minDate;
          hours = Math.max(hours, minTime.getHours());
          if (hours === minTime.getHours()) minutes = Math.max(minutes, minTime.getMinutes());
          if (minutes === minTime.getMinutes()) seconds = Math.max(seconds, minTime.getSeconds());
        }

        setHours(hours, minutes, seconds);
      }

      function setHoursFromDate(dateObj) {
        var date = dateObj || self.latestSelectedDateObj;
        if (date) setHours(date.getHours(), date.getMinutes(), date.getSeconds());
      }

      function setDefaultHours() {
        var hours = self.config.defaultHour;
        var minutes = self.config.defaultMinute;
        var seconds = self.config.defaultSeconds;

        if (self.config.minDate !== undefined) {
          var min_hr = self.config.minDate.getHours();
          var min_minutes = self.config.minDate.getMinutes();
          hours = Math.max(hours, min_hr);
          if (hours === min_hr) minutes = Math.max(min_minutes, minutes);
          if (hours === min_hr && minutes === min_minutes) seconds = self.config.minDate.getSeconds();
        }

        if (self.config.maxDate !== undefined) {
          var max_hr = self.config.maxDate.getHours();
          var max_minutes = self.config.maxDate.getMinutes();
          hours = Math.min(hours, max_hr);
          if (hours === max_hr) minutes = Math.min(max_minutes, minutes);
          if (hours === max_hr && minutes === max_minutes) seconds = self.config.maxDate.getSeconds();
        }

        setHours(hours, minutes, seconds);
      }

      function setHours(hours, minutes, seconds) {
        if (self.latestSelectedDateObj !== undefined) {
          self.latestSelectedDateObj.setHours(hours % 24, minutes, seconds || 0, 0);
        }

        if (!self.hourElement || !self.minuteElement || self.isMobile) return;
        self.hourElement.value = pad(!self.config.time_24hr ? (12 + hours) % 12 + 12 * int(hours % 12 === 0) : hours);
        self.minuteElement.value = pad(minutes);
        if (self.amPM !== undefined) self.amPM.textContent = self.l10n.amPM[int(hours >= 12)];
        if (self.secondElement !== undefined) self.secondElement.value = pad(seconds);
      }

      function onYearInput(event) {
        var year = parseInt(event.target.value) + (event.delta || 0);

        if (year / 1000 > 1 || event.key === "Enter" && !/[^\d]/.test(year.toString())) {
          changeYear(year);
        }
      }

      function bind(element, event, handler, options) {
        if (event instanceof Array) return event.forEach(function (ev) {
          return bind(element, ev, handler, options);
        });
        if (element instanceof Array) return element.forEach(function (el) {
          return bind(el, event, handler, options);
        });
        element.addEventListener(event, handler, options);

        self._handlers.push({
          element: element,
          event: event,
          handler: handler,
          options: options
        });
      }

      function onClick(handler) {
        return function (evt) {
          evt.which === 1 && handler(evt);
        };
      }

      function triggerChange() {
        triggerEvent("onChange");
      }

      function bindEvents() {
        if (self.config.wrap) {
          ["open", "close", "toggle", "clear"].forEach(function (evt) {
            Array.prototype.forEach.call(self.element.querySelectorAll("[data-" + evt + "]"), function (el) {
              return bind(el, "click", self[evt]);
            });
          });
        }

        if (self.isMobile) {
          setupMobile();
          return;
        }

        var debouncedResize = debounce(onResize, 50);
        self._debouncedChange = debounce(triggerChange, DEBOUNCED_CHANGE_MS);
        if (self.daysContainer && !/iPhone|iPad|iPod/i.test(navigator.userAgent)) bind(self.daysContainer, "mouseover", function (e) {
          if (self.config.mode === "range") onMouseOver(e.target);
        });
        bind(window.document.body, "keydown", onKeyDown);
        if (!self.config.static) bind(self._input, "keydown", onKeyDown);
        if (!self.config.inline && !self.config.static) bind(window, "resize", debouncedResize);
        if (window.ontouchstart !== undefined) bind(window.document, "click", documentClick);else bind(window.document, "mousedown", onClick(documentClick));
        bind(window.document, "focus", documentClick, {
          capture: true
        });

        if (self.config.clickOpens === true) {
          bind(self._input, "focus", self.open);
          bind(self._input, "mousedown", onClick(self.open));
        }

        if (self.daysContainer !== undefined) {
          bind(self.monthNav, "mousedown", onClick(onMonthNavClick));
          bind(self.monthNav, ["keyup", "increment"], onYearInput);
          bind(self.daysContainer, "mousedown", onClick(selectDate));
        }

        if (self.timeContainer !== undefined && self.minuteElement !== undefined && self.hourElement !== undefined) {
          var selText = function selText(e) {
            return e.target.select();
          };

          bind(self.timeContainer, ["increment"], updateTime);
          bind(self.timeContainer, "blur", updateTime, {
            capture: true
          });
          bind(self.timeContainer, "mousedown", onClick(timeIncrement));
          bind([self.hourElement, self.minuteElement], ["focus", "click"], selText);
          if (self.secondElement !== undefined) bind(self.secondElement, "focus", function () {
            return self.secondElement && self.secondElement.select();
          });

          if (self.amPM !== undefined) {
            bind(self.amPM, "mousedown", onClick(function (e) {
              updateTime(e);
              triggerChange();
            }));
          }
        }
      }

      function jumpToDate(jumpDate) {
        var jumpTo = jumpDate !== undefined ? self.parseDate(jumpDate) : self.latestSelectedDateObj || (self.config.minDate && self.config.minDate > self.now ? self.config.minDate : self.config.maxDate && self.config.maxDate < self.now ? self.config.maxDate : self.now);

        try {
          if (jumpTo !== undefined) {
            self.currentYear = jumpTo.getFullYear();
            self.currentMonth = jumpTo.getMonth();
          }
        } catch (e) {
          e.message = "Invalid date supplied: " + jumpTo;
          self.config.errorHandler(e);
        }

        self.redraw();
      }

      function timeIncrement(e) {
        if (~e.target.className.indexOf("arrow")) incrementNumInput(e, e.target.classList.contains("arrowUp") ? 1 : -1);
      }

      function incrementNumInput(e, delta, inputElem) {
        var target = e && e.target;
        var input = inputElem || target && target.parentNode && target.parentNode.firstChild;
        var event = createEvent("increment");
        event.delta = delta;
        input && input.dispatchEvent(event);
      }

      function build() {
        var fragment = window.document.createDocumentFragment();
        self.calendarContainer = createElement("div", "flatpickr-calendar");
        self.calendarContainer.tabIndex = -1;

        if (!self.config.noCalendar) {
          fragment.appendChild(buildMonthNav());
          self.innerContainer = createElement("div", "flatpickr-innerContainer");

          if (self.config.weekNumbers) {
            var _buildWeeks = buildWeeks(),
                weekWrapper = _buildWeeks.weekWrapper,
                weekNumbers = _buildWeeks.weekNumbers;

            self.innerContainer.appendChild(weekWrapper);
            self.weekNumbers = weekNumbers;
            self.weekWrapper = weekWrapper;
          }

          self.rContainer = createElement("div", "flatpickr-rContainer");
          self.rContainer.appendChild(buildWeekdays());

          if (!self.daysContainer) {
            self.daysContainer = createElement("div", "flatpickr-days");
            self.daysContainer.tabIndex = -1;
          }

          buildDays();
          self.rContainer.appendChild(self.daysContainer);
          self.innerContainer.appendChild(self.rContainer);
          fragment.appendChild(self.innerContainer);
        }

        if (self.config.enableTime) {
          fragment.appendChild(buildTime());
        }

        toggleClass(self.calendarContainer, "rangeMode", self.config.mode === "range");
        toggleClass(self.calendarContainer, "animate", self.config.animate === true);
        toggleClass(self.calendarContainer, "multiMonth", self.config.showMonths > 1);
        self.calendarContainer.appendChild(fragment);
        var customAppend = self.config.appendTo !== undefined && self.config.appendTo.nodeType !== undefined;

        if (self.config.inline || self.config.static) {
          self.calendarContainer.classList.add(self.config.inline ? "inline" : "static");

          if (self.config.inline) {
            if (!customAppend && self.element.parentNode) self.element.parentNode.insertBefore(self.calendarContainer, self._input.nextSibling);else if (self.config.appendTo !== undefined) self.config.appendTo.appendChild(self.calendarContainer);
          }

          if (self.config.static) {
            var wrapper = createElement("div", "flatpickr-wrapper");
            if (self.element.parentNode) self.element.parentNode.insertBefore(wrapper, self.element);
            wrapper.appendChild(self.element);
            if (self.altInput) wrapper.appendChild(self.altInput);
            wrapper.appendChild(self.calendarContainer);
          }
        }

        if (!self.config.static && !self.config.inline) (self.config.appendTo !== undefined ? self.config.appendTo : window.document.body).appendChild(self.calendarContainer);
      }

      function createDay(className, date, dayNumber, i) {
        var dateIsEnabled = isEnabled(date, true),
            dayElement = createElement("span", "flatpickr-day " + className, date.getDate().toString());
        dayElement.dateObj = date;
        dayElement.$i = i;
        dayElement.setAttribute("aria-label", self.formatDate(date, self.config.ariaDateFormat));

        if (className.indexOf("hidden") === -1 && compareDates(date, self.now) === 0) {
          self.todayDateElem = dayElement;
          dayElement.classList.add("today");
          dayElement.setAttribute("aria-current", "date");
        }

        if (dateIsEnabled) {
          dayElement.tabIndex = -1;

          if (isDateSelected(date)) {
            dayElement.classList.add("selected");
            self.selectedDateElem = dayElement;

            if (self.config.mode === "range") {
              toggleClass(dayElement, "startRange", self.selectedDates[0] && compareDates(date, self.selectedDates[0], true) === 0);
              toggleClass(dayElement, "endRange", self.selectedDates[1] && compareDates(date, self.selectedDates[1], true) === 0);
              if (className === "nextMonthDay") dayElement.classList.add("inRange");
            }
          }
        } else {
          dayElement.classList.add("disabled");
        }

        if (self.config.mode === "range") {
          if (isDateInRange(date) && !isDateSelected(date)) dayElement.classList.add("inRange");
        }

        if (self.weekNumbers && self.config.showMonths === 1 && className !== "prevMonthDay" && dayNumber % 7 === 1) {
          self.weekNumbers.insertAdjacentHTML("beforeend", "<span class='flatpickr-day'>" + self.config.getWeek(date) + "</span>");
        }

        triggerEvent("onDayCreate", dayElement);
        return dayElement;
      }

      function focusOnDayElem(targetNode) {
        targetNode.focus();
        if (self.config.mode === "range") onMouseOver(targetNode);
      }

      function getFirstAvailableDay(delta) {
        var startMonth = delta > 0 ? 0 : self.config.showMonths - 1;
        var endMonth = delta > 0 ? self.config.showMonths : -1;

        for (var m = startMonth; m != endMonth; m += delta) {
          var month = self.daysContainer.children[m];
          var startIndex = delta > 0 ? 0 : month.children.length - 1;
          var endIndex = delta > 0 ? month.children.length : -1;

          for (var i = startIndex; i != endIndex; i += delta) {
            var c = month.children[i];
            if (c.className.indexOf("hidden") === -1 && isEnabled(c.dateObj)) return c;
          }
        }

        return undefined;
      }

      function getNextAvailableDay(current, delta) {
        var givenMonth = current.className.indexOf("Month") === -1 ? current.dateObj.getMonth() : self.currentMonth;
        var endMonth = delta > 0 ? self.config.showMonths : -1;
        var loopDelta = delta > 0 ? 1 : -1;

        for (var m = givenMonth - self.currentMonth; m != endMonth; m += loopDelta) {
          var month = self.daysContainer.children[m];
          var startIndex = givenMonth - self.currentMonth === m ? current.$i + delta : delta < 0 ? month.children.length - 1 : 0;
          var numMonthDays = month.children.length;

          for (var i = startIndex; i >= 0 && i < numMonthDays && i != (delta > 0 ? numMonthDays : -1); i += loopDelta) {
            var c = month.children[i];
            if (c.className.indexOf("hidden") === -1 && isEnabled(c.dateObj) && Math.abs(current.$i - i) >= Math.abs(delta)) return focusOnDayElem(c);
          }
        }

        self.changeMonth(loopDelta);
        focusOnDay(getFirstAvailableDay(loopDelta), 0);
        return undefined;
      }

      function focusOnDay(current, offset) {
        var dayFocused = isInView(document.activeElement || document.body);
        var startElem = current !== undefined ? current : dayFocused ? document.activeElement : self.selectedDateElem !== undefined && isInView(self.selectedDateElem) ? self.selectedDateElem : self.todayDateElem !== undefined && isInView(self.todayDateElem) ? self.todayDateElem : getFirstAvailableDay(offset > 0 ? 1 : -1);
        if (startElem === undefined) return self._input.focus();
        if (!dayFocused) return focusOnDayElem(startElem);
        getNextAvailableDay(startElem, offset);
      }

      function buildMonthDays(year, month) {
        var firstOfMonth = (new Date(year, month, 1).getDay() - self.l10n.firstDayOfWeek + 7) % 7;
        var prevMonthDays = self.utils.getDaysInMonth((month - 1 + 12) % 12);
        var daysInMonth = self.utils.getDaysInMonth(month),
            days = window.document.createDocumentFragment(),
            isMultiMonth = self.config.showMonths > 1,
            prevMonthDayClass = isMultiMonth ? "prevMonthDay hidden" : "prevMonthDay",
            nextMonthDayClass = isMultiMonth ? "nextMonthDay hidden" : "nextMonthDay";
        var dayNumber = prevMonthDays + 1 - firstOfMonth,
            dayIndex = 0;

        for (; dayNumber <= prevMonthDays; dayNumber++, dayIndex++) {
          days.appendChild(createDay(prevMonthDayClass, new Date(year, month - 1, dayNumber), dayNumber, dayIndex));
        }

        for (dayNumber = 1; dayNumber <= daysInMonth; dayNumber++, dayIndex++) {
          days.appendChild(createDay("", new Date(year, month, dayNumber), dayNumber, dayIndex));
        }

        for (var dayNum = daysInMonth + 1; dayNum <= 42 - firstOfMonth && (self.config.showMonths === 1 || dayIndex % 7 !== 0); dayNum++, dayIndex++) {
          days.appendChild(createDay(nextMonthDayClass, new Date(year, month + 1, dayNum % daysInMonth), dayNum, dayIndex));
        }

        var dayContainer = createElement("div", "dayContainer");
        dayContainer.appendChild(days);
        return dayContainer;
      }

      function buildDays() {
        if (self.daysContainer === undefined) {
          return;
        }

        clearNode(self.daysContainer);
        if (self.weekNumbers) clearNode(self.weekNumbers);
        var frag = document.createDocumentFragment();

        for (var i = 0; i < self.config.showMonths; i++) {
          var d = new Date(self.currentYear, self.currentMonth, 1);
          d.setMonth(self.currentMonth + i);
          frag.appendChild(buildMonthDays(d.getFullYear(), d.getMonth()));
        }

        self.daysContainer.appendChild(frag);
        self.days = self.daysContainer.firstChild;

        if (self.config.mode === "range" && self.selectedDates.length === 1) {
          onMouseOver();
        }
      }

      function buildMonth() {
        var container = createElement("div", "flatpickr-month");
        var monthNavFragment = window.document.createDocumentFragment();
        var monthElement = createElement("span", "cur-month");
        var yearInput = createNumberInput("cur-year", {
          tabindex: "-1"
        });
        var yearElement = yearInput.getElementsByTagName("input")[0];
        yearElement.setAttribute("aria-label", self.l10n.yearAriaLabel);
        if (self.config.minDate) yearElement.setAttribute("data-min", self.config.minDate.getFullYear().toString());

        if (self.config.maxDate) {
          yearElement.setAttribute("data-max", self.config.maxDate.getFullYear().toString());
          yearElement.disabled = !!self.config.minDate && self.config.minDate.getFullYear() === self.config.maxDate.getFullYear();
        }

        var currentMonth = createElement("div", "flatpickr-current-month");
        currentMonth.appendChild(monthElement);
        currentMonth.appendChild(yearInput);
        monthNavFragment.appendChild(currentMonth);
        container.appendChild(monthNavFragment);
        return {
          container: container,
          yearElement: yearElement,
          monthElement: monthElement
        };
      }

      function buildMonths() {
        clearNode(self.monthNav);
        self.monthNav.appendChild(self.prevMonthNav);

        for (var m = self.config.showMonths; m--;) {
          var month = buildMonth();
          self.yearElements.push(month.yearElement);
          self.monthElements.push(month.monthElement);
          self.monthNav.appendChild(month.container);
        }

        self.monthNav.appendChild(self.nextMonthNav);
      }

      function buildMonthNav() {
        self.monthNav = createElement("div", "flatpickr-months");
        self.yearElements = [];
        self.monthElements = [];
        self.prevMonthNav = createElement("span", "flatpickr-prev-month");
        self.prevMonthNav.innerHTML = self.config.prevArrow;
        self.nextMonthNav = createElement("span", "flatpickr-next-month");
        self.nextMonthNav.innerHTML = self.config.nextArrow;
        buildMonths();
        Object.defineProperty(self, "_hidePrevMonthArrow", {
          get: function get() {
            return self.__hidePrevMonthArrow;
          },
          set: function set(bool) {
            if (self.__hidePrevMonthArrow !== bool) {
              toggleClass(self.prevMonthNav, "disabled", bool);
              self.__hidePrevMonthArrow = bool;
            }
          }
        });
        Object.defineProperty(self, "_hideNextMonthArrow", {
          get: function get() {
            return self.__hideNextMonthArrow;
          },
          set: function set(bool) {
            if (self.__hideNextMonthArrow !== bool) {
              toggleClass(self.nextMonthNav, "disabled", bool);
              self.__hideNextMonthArrow = bool;
            }
          }
        });
        self.currentYearElement = self.yearElements[0];
        updateNavigationCurrentMonth();
        return self.monthNav;
      }

      function buildTime() {
        self.calendarContainer.classList.add("hasTime");
        if (self.config.noCalendar) self.calendarContainer.classList.add("noCalendar");
        self.timeContainer = createElement("div", "flatpickr-time");
        self.timeContainer.tabIndex = -1;
        var separator = createElement("span", "flatpickr-time-separator", ":");
        var hourInput = createNumberInput("flatpickr-hour");
        self.hourElement = hourInput.getElementsByTagName("input")[0];
        var minuteInput = createNumberInput("flatpickr-minute");
        self.minuteElement = minuteInput.getElementsByTagName("input")[0];
        self.hourElement.tabIndex = self.minuteElement.tabIndex = -1;
        self.hourElement.value = pad(self.latestSelectedDateObj ? self.latestSelectedDateObj.getHours() : self.config.time_24hr ? self.config.defaultHour : military2ampm(self.config.defaultHour));
        self.minuteElement.value = pad(self.latestSelectedDateObj ? self.latestSelectedDateObj.getMinutes() : self.config.defaultMinute);
        self.hourElement.setAttribute("data-step", self.config.hourIncrement.toString());
        self.minuteElement.setAttribute("data-step", self.config.minuteIncrement.toString());
        self.hourElement.setAttribute("data-min", self.config.time_24hr ? "0" : "1");
        self.hourElement.setAttribute("data-max", self.config.time_24hr ? "23" : "12");
        self.minuteElement.setAttribute("data-min", "0");
        self.minuteElement.setAttribute("data-max", "59");
        self.timeContainer.appendChild(hourInput);
        self.timeContainer.appendChild(separator);
        self.timeContainer.appendChild(minuteInput);
        if (self.config.time_24hr) self.timeContainer.classList.add("time24hr");

        if (self.config.enableSeconds) {
          self.timeContainer.classList.add("hasSeconds");
          var secondInput = createNumberInput("flatpickr-second");
          self.secondElement = secondInput.getElementsByTagName("input")[0];
          self.secondElement.value = pad(self.latestSelectedDateObj ? self.latestSelectedDateObj.getSeconds() : self.config.defaultSeconds);
          self.secondElement.setAttribute("data-step", self.minuteElement.getAttribute("data-step"));
          self.secondElement.setAttribute("data-min", self.minuteElement.getAttribute("data-min"));
          self.secondElement.setAttribute("data-max", self.minuteElement.getAttribute("data-max"));
          self.timeContainer.appendChild(createElement("span", "flatpickr-time-separator", ":"));
          self.timeContainer.appendChild(secondInput);
        }

        if (!self.config.time_24hr) {
          self.amPM = createElement("span", "flatpickr-am-pm", self.l10n.amPM[int((self.latestSelectedDateObj ? self.hourElement.value : self.config.defaultHour) > 11)]);
          self.amPM.title = self.l10n.toggleTitle;
          self.amPM.tabIndex = -1;
          self.timeContainer.appendChild(self.amPM);
        }

        return self.timeContainer;
      }

      function buildWeekdays() {
        if (!self.weekdayContainer) self.weekdayContainer = createElement("div", "flatpickr-weekdays");else clearNode(self.weekdayContainer);

        for (var i = self.config.showMonths; i--;) {
          var container = createElement("div", "flatpickr-weekdaycontainer");
          self.weekdayContainer.appendChild(container);
        }

        updateWeekdays();
        return self.weekdayContainer;
      }

      function updateWeekdays() {
        var firstDayOfWeek = self.l10n.firstDayOfWeek;
        var weekdays = self.l10n.weekdays.shorthand.concat();

        if (firstDayOfWeek > 0 && firstDayOfWeek < weekdays.length) {
          weekdays = weekdays.splice(firstDayOfWeek, weekdays.length).concat(weekdays.splice(0, firstDayOfWeek));
        }

        for (var i = self.config.showMonths; i--;) {
          self.weekdayContainer.children[i].innerHTML = "\n      <span class=flatpickr-weekday>\n        " + weekdays.join("</span><span class=flatpickr-weekday>") + "\n      </span>\n      ";
        }
      }

      function buildWeeks() {
        self.calendarContainer.classList.add("hasWeeks");
        var weekWrapper = createElement("div", "flatpickr-weekwrapper");
        weekWrapper.appendChild(createElement("span", "flatpickr-weekday", self.l10n.weekAbbreviation));
        var weekNumbers = createElement("div", "flatpickr-weeks");
        weekWrapper.appendChild(weekNumbers);
        return {
          weekWrapper: weekWrapper,
          weekNumbers: weekNumbers
        };
      }

      function changeMonth(value, is_offset) {
        if (is_offset === void 0) {
          is_offset = true;
        }

        var delta = is_offset ? value : value - self.currentMonth;
        if (delta < 0 && self._hidePrevMonthArrow === true || delta > 0 && self._hideNextMonthArrow === true) return;
        self.currentMonth += delta;

        if (self.currentMonth < 0 || self.currentMonth > 11) {
          self.currentYear += self.currentMonth > 11 ? 1 : -1;
          self.currentMonth = (self.currentMonth + 12) % 12;
          triggerEvent("onYearChange");
        }

        buildDays();
        triggerEvent("onMonthChange");
        updateNavigationCurrentMonth();
      }

      function clear(triggerChangeEvent) {
        if (triggerChangeEvent === void 0) {
          triggerChangeEvent = true;
        }

        self.input.value = "";
        if (self.altInput !== undefined) self.altInput.value = "";
        if (self.mobileInput !== undefined) self.mobileInput.value = "";
        self.selectedDates = [];
        self.latestSelectedDateObj = undefined;
        self.showTimeInput = false;

        if (self.config.enableTime === true) {
          setDefaultHours();
        }

        self.redraw();
        if (triggerChangeEvent) triggerEvent("onChange");
      }

      function close() {
        self.isOpen = false;

        if (!self.isMobile) {
          self.calendarContainer.classList.remove("open");

          self._input.classList.remove("active");
        }

        triggerEvent("onClose");
      }

      function destroy() {
        if (self.config !== undefined) triggerEvent("onDestroy");

        for (var i = self._handlers.length; i--;) {
          var h = self._handlers[i];
          h.element.removeEventListener(h.event, h.handler, h.options);
        }

        self._handlers = [];

        if (self.mobileInput) {
          if (self.mobileInput.parentNode) self.mobileInput.parentNode.removeChild(self.mobileInput);
          self.mobileInput = undefined;
        } else if (self.calendarContainer && self.calendarContainer.parentNode) {
          if (self.config.static && self.calendarContainer.parentNode) {
            var wrapper = self.calendarContainer.parentNode;
            wrapper.lastChild && wrapper.removeChild(wrapper.lastChild);

            if (wrapper.parentNode) {
              while (wrapper.firstChild) {
                wrapper.parentNode.insertBefore(wrapper.firstChild, wrapper);
              }

              wrapper.parentNode.removeChild(wrapper);
            }
          } else self.calendarContainer.parentNode.removeChild(self.calendarContainer);
        }

        if (self.altInput) {
          self.input.type = "text";
          if (self.altInput.parentNode) self.altInput.parentNode.removeChild(self.altInput);
          delete self.altInput;
        }

        if (self.input) {
          self.input.type = self.input._type;
          self.input.classList.remove("flatpickr-input");
          self.input.removeAttribute("readonly");
          self.input.value = "";
        }

        ["_showTimeInput", "latestSelectedDateObj", "_hideNextMonthArrow", "_hidePrevMonthArrow", "__hideNextMonthArrow", "__hidePrevMonthArrow", "isMobile", "isOpen", "selectedDateElem", "minDateHasTime", "maxDateHasTime", "days", "daysContainer", "_input", "_positionElement", "innerContainer", "rContainer", "monthNav", "todayDateElem", "calendarContainer", "weekdayContainer", "prevMonthNav", "nextMonthNav", "currentMonthElement", "currentYearElement", "navigationCurrentMonth", "selectedDateElem", "config"].forEach(function (k) {
          try {
            delete self[k];
          } catch (_) {}
        });
      }

      function isCalendarElem(elem) {
        if (self.config.appendTo && self.config.appendTo.contains(elem)) return true;
        return self.calendarContainer.contains(elem);
      }

      function documentClick(e) {
        if (self.isOpen && !self.config.inline) {
          var isCalendarElement = isCalendarElem(e.target);
          var isInput = e.target === self.input || e.target === self.altInput || self.element.contains(e.target) || e.path && e.path.indexOf && (~e.path.indexOf(self.input) || ~e.path.indexOf(self.altInput));
          var lostFocus = e.type === "blur" ? isInput && e.relatedTarget && !isCalendarElem(e.relatedTarget) : !isInput && !isCalendarElement;
          var isIgnored = !self.config.ignoredFocusElements.some(function (elem) {
            return elem.contains(e.target);
          });

          if (lostFocus && isIgnored) {
            self.close();

            if (self.config.mode === "range" && self.selectedDates.length === 1) {
              self.clear(false);
              self.redraw();
            }
          }
        }
      }

      function changeYear(newYear) {
        if (!newYear || self.config.minDate && newYear < self.config.minDate.getFullYear() || self.config.maxDate && newYear > self.config.maxDate.getFullYear()) return;
        var newYearNum = newYear,
            isNewYear = self.currentYear !== newYearNum;
        self.currentYear = newYearNum || self.currentYear;

        if (self.config.maxDate && self.currentYear === self.config.maxDate.getFullYear()) {
          self.currentMonth = Math.min(self.config.maxDate.getMonth(), self.currentMonth);
        } else if (self.config.minDate && self.currentYear === self.config.minDate.getFullYear()) {
          self.currentMonth = Math.max(self.config.minDate.getMonth(), self.currentMonth);
        }

        if (isNewYear) {
          self.redraw();
          triggerEvent("onYearChange");
        }
      }

      function isEnabled(date, timeless) {
        if (timeless === void 0) {
          timeless = true;
        }

        var dateToCheck = self.parseDate(date, undefined, timeless);
        if (self.config.minDate && dateToCheck && compareDates(dateToCheck, self.config.minDate, timeless !== undefined ? timeless : !self.minDateHasTime) < 0 || self.config.maxDate && dateToCheck && compareDates(dateToCheck, self.config.maxDate, timeless !== undefined ? timeless : !self.maxDateHasTime) > 0) return false;
        if (self.config.enable.length === 0 && self.config.disable.length === 0) return true;
        if (dateToCheck === undefined) return false;
        var bool = self.config.enable.length > 0,
            array = bool ? self.config.enable : self.config.disable;

        for (var i = 0, d; i < array.length; i++) {
          d = array[i];
          if (typeof d === "function" && d(dateToCheck)) return bool;else if (d instanceof Date && dateToCheck !== undefined && d.getTime() === dateToCheck.getTime()) return bool;else if (typeof d === "string" && dateToCheck !== undefined) {
            var parsed = self.parseDate(d, undefined, true);
            return parsed && parsed.getTime() === dateToCheck.getTime() ? bool : !bool;
          } else if (typeof d === "object" && dateToCheck !== undefined && d.from && d.to && dateToCheck.getTime() >= d.from.getTime() && dateToCheck.getTime() <= d.to.getTime()) return bool;
        }

        return !bool;
      }

      function isInView(elem) {
        if (self.daysContainer !== undefined) return elem.className.indexOf("hidden") === -1 && self.daysContainer.contains(elem);
        return false;
      }

      function onKeyDown(e) {
        var isInput = e.target === self._input;
        var allowInput = self.config.allowInput;
        var allowKeydown = self.isOpen && (!allowInput || !isInput);
        var allowInlineKeydown = self.config.inline && isInput && !allowInput;

        if (e.keyCode === 13 && isInput) {
          if (allowInput) {
            self.setDate(self._input.value, true, e.target === self.altInput ? self.config.altFormat : self.config.dateFormat);
            return e.target.blur();
          } else self.open();
        } else if (isCalendarElem(e.target) || allowKeydown || allowInlineKeydown) {
          var isTimeObj = !!self.timeContainer && self.timeContainer.contains(e.target);

          switch (e.keyCode) {
            case 13:
              if (isTimeObj) updateTime();else selectDate(e);
              break;

            case 27:
              e.preventDefault();
              focusAndClose();
              break;

            case 8:
            case 46:
              if (isInput && !self.config.allowInput) {
                e.preventDefault();
                self.clear();
              }

              break;

            case 37:
            case 39:
              if (!isTimeObj) {
                e.preventDefault();

                if (self.daysContainer !== undefined && (allowInput === false || isInView(document.activeElement))) {
                  var _delta = e.keyCode === 39 ? 1 : -1;

                  if (!e.ctrlKey) focusOnDay(undefined, _delta);else {
                    changeMonth(_delta);
                    focusOnDay(getFirstAvailableDay(1), 0);
                  }
                }
              } else if (self.hourElement) self.hourElement.focus();

              break;

            case 38:
            case 40:
              e.preventDefault();
              var delta = e.keyCode === 40 ? 1 : -1;

              if (self.daysContainer && e.target.$i !== undefined) {
                if (e.ctrlKey) {
                  changeYear(self.currentYear - delta);
                  focusOnDay(getFirstAvailableDay(1), 0);
                } else if (!isTimeObj) focusOnDay(undefined, delta * 7);
              } else if (self.config.enableTime) {
                if (!isTimeObj && self.hourElement) self.hourElement.focus();
                updateTime(e);

                self._debouncedChange();
              }

              break;

            case 9:
              if (!isTimeObj) {
                self.element.focus();
                break;
              }

              var elems = [self.hourElement, self.minuteElement, self.secondElement, self.amPM].filter(function (x) {
                return x;
              });
              var i = elems.indexOf(e.target);

              if (i !== -1) {
                var target = elems[i + (e.shiftKey ? -1 : 1)];

                if (target !== undefined) {
                  e.preventDefault();
                  target.focus();
                } else {
                  self.element.focus();
                }
              }

              break;

            default:
              break;
          }
        }

        if (self.amPM !== undefined && e.target === self.amPM) {
          switch (e.key) {
            case self.l10n.amPM[0].charAt(0):
            case self.l10n.amPM[0].charAt(0).toLowerCase():
              self.amPM.textContent = self.l10n.amPM[0];
              setHoursFromInputs();
              updateValue();
              break;

            case self.l10n.amPM[1].charAt(0):
            case self.l10n.amPM[1].charAt(0).toLowerCase():
              self.amPM.textContent = self.l10n.amPM[1];
              setHoursFromInputs();
              updateValue();
              break;
          }
        }

        triggerEvent("onKeyDown", e);
      }

      function onMouseOver(elem) {
        if (self.selectedDates.length !== 1 || elem && (!elem.classList.contains("flatpickr-day") || elem.classList.contains("disabled"))) return;
        var hoverDate = elem ? elem.dateObj.getTime() : self.days.firstElementChild.dateObj.getTime(),
            initialDate = self.parseDate(self.selectedDates[0], undefined, true).getTime(),
            rangeStartDate = Math.min(hoverDate, self.selectedDates[0].getTime()),
            rangeEndDate = Math.max(hoverDate, self.selectedDates[0].getTime()),
            lastDate = self.daysContainer.lastChild.lastChild.dateObj.getTime();
        var containsDisabled = false;
        var minRange = 0,
            maxRange = 0;

        for (var t = rangeStartDate; t < lastDate; t += duration.DAY) {
          if (!isEnabled(new Date(t), true)) {
            containsDisabled = containsDisabled || t > rangeStartDate && t < rangeEndDate;
            if (t < initialDate && (!minRange || t > minRange)) minRange = t;else if (t > initialDate && (!maxRange || t < maxRange)) maxRange = t;
          }
        }

        for (var m = 0; m < self.config.showMonths; m++) {
          var month = self.daysContainer.children[m];
          var prevMonth = self.daysContainer.children[m - 1];

          var _loop = function _loop(i, l) {
            var dayElem = month.children[i],
                date = dayElem.dateObj;
            var timestamp = date.getTime();
            var outOfRange = minRange > 0 && timestamp < minRange || maxRange > 0 && timestamp > maxRange;

            if (outOfRange) {
              dayElem.classList.add("notAllowed");
              ["inRange", "startRange", "endRange"].forEach(function (c) {
                dayElem.classList.remove(c);
              });
              return "continue";
            } else if (containsDisabled && !outOfRange) return "continue";

            ["startRange", "inRange", "endRange", "notAllowed"].forEach(function (c) {
              dayElem.classList.remove(c);
            });

            if (elem !== undefined) {
              elem.classList.add(hoverDate < self.selectedDates[0].getTime() ? "startRange" : "endRange");

              if (month.contains(elem) || !(m > 0 && prevMonth && prevMonth.lastChild.dateObj.getTime() >= timestamp)) {
                if (initialDate < hoverDate && timestamp === initialDate) dayElem.classList.add("startRange");else if (initialDate > hoverDate && timestamp === initialDate) dayElem.classList.add("endRange");
                if (timestamp >= minRange && (maxRange === 0 || timestamp <= maxRange) && isBetween(timestamp, initialDate, hoverDate)) dayElem.classList.add("inRange");
              }
            }
          };

          for (var i = 0, l = month.children.length; i < l; i++) {
            var _ret = _loop(i, l);

            if (_ret === "continue") continue;
          }
        }
      }

      function onResize() {
        if (self.isOpen && !self.config.static && !self.config.inline) positionCalendar();
      }

      function open(e, positionElement) {
        if (positionElement === void 0) {
          positionElement = self._positionElement;
        }

        if (self.isMobile === true) {
          if (e) {
            e.preventDefault();
            e.target && e.target.blur();
          }

          if (self.mobileInput !== undefined) {
            self.mobileInput.focus();
            self.mobileInput.click();
          }

          triggerEvent("onOpen");
          return;
        }

        if (self._input.disabled || self.config.inline) return;
        var wasOpen = self.isOpen;
        self.isOpen = true;

        if (!wasOpen) {
          self.calendarContainer.classList.add("open");

          self._input.classList.add("active");

          triggerEvent("onOpen");
          positionCalendar(positionElement);
        }

        if (self.config.enableTime === true && self.config.noCalendar === true) {
          if (self.selectedDates.length === 0) {
            self.setDate(self.config.minDate !== undefined ? new Date(self.config.minDate.getTime()) : new Date(), false);
            setDefaultHours();
            updateValue();
          }

          if (self.config.allowInput === false && (e === undefined || !self.timeContainer.contains(e.relatedTarget))) {
            setTimeout(function () {
              return self.hourElement.select();
            }, 50);
          }
        }
      }

      function minMaxDateSetter(type) {
        return function (date) {
          var dateObj = self.config["_" + type + "Date"] = self.parseDate(date, self.config.dateFormat);
          var inverseDateObj = self.config["_" + (type === "min" ? "max" : "min") + "Date"];

          if (dateObj !== undefined) {
            self[type === "min" ? "minDateHasTime" : "maxDateHasTime"] = dateObj.getHours() > 0 || dateObj.getMinutes() > 0 || dateObj.getSeconds() > 0;
          }

          if (self.selectedDates) {
            self.selectedDates = self.selectedDates.filter(function (d) {
              return isEnabled(d);
            });
            if (!self.selectedDates.length && type === "min") setHoursFromDate(dateObj);
            updateValue();
          }

          if (self.daysContainer) {
            redraw();
            if (dateObj !== undefined) self.currentYearElement[type] = dateObj.getFullYear().toString();else self.currentYearElement.removeAttribute(type);
            self.currentYearElement.disabled = !!inverseDateObj && dateObj !== undefined && inverseDateObj.getFullYear() === dateObj.getFullYear();
          }
        };
      }

      function parseConfig() {
        var boolOpts = ["wrap", "weekNumbers", "allowInput", "clickOpens", "time_24hr", "enableTime", "noCalendar", "altInput", "shorthandCurrentMonth", "inline", "static", "enableSeconds", "disableMobile"];
        var userConfig = Object.assign({}, instanceConfig, JSON.parse(JSON.stringify(element.dataset || {})));
        var formats$$1 = {};
        self.config.parseDate = userConfig.parseDate;
        self.config.formatDate = userConfig.formatDate;
        Object.defineProperty(self.config, "enable", {
          get: function get() {
            return self.config._enable;
          },
          set: function set(dates) {
            self.config._enable = parseDateRules(dates);
          }
        });
        Object.defineProperty(self.config, "disable", {
          get: function get() {
            return self.config._disable;
          },
          set: function set(dates) {
            self.config._disable = parseDateRules(dates);
          }
        });
        var timeMode = userConfig.mode === "time";

        if (!userConfig.dateFormat && (userConfig.enableTime || timeMode)) {
          formats$$1.dateFormat = userConfig.noCalendar || timeMode ? "H:i" + (userConfig.enableSeconds ? ":S" : "") : flatpickr.defaultConfig.dateFormat + " H:i" + (userConfig.enableSeconds ? ":S" : "");
        }

        if (userConfig.altInput && (userConfig.enableTime || timeMode) && !userConfig.altFormat) {
          formats$$1.altFormat = userConfig.noCalendar || timeMode ? "h:i" + (userConfig.enableSeconds ? ":S K" : " K") : flatpickr.defaultConfig.altFormat + (" h:i" + (userConfig.enableSeconds ? ":S" : "") + " K");
        }

        Object.defineProperty(self.config, "minDate", {
          get: function get() {
            return self.config._minDate;
          },
          set: minMaxDateSetter("min")
        });
        Object.defineProperty(self.config, "maxDate", {
          get: function get() {
            return self.config._maxDate;
          },
          set: minMaxDateSetter("max")
        });

        var minMaxTimeSetter = function minMaxTimeSetter(type) {
          return function (val) {
            self.config[type === "min" ? "_minTime" : "_maxTime"] = self.parseDate(val, "H:i");
          };
        };

        Object.defineProperty(self.config, "minTime", {
          get: function get() {
            return self.config._minTime;
          },
          set: minMaxTimeSetter("min")
        });
        Object.defineProperty(self.config, "maxTime", {
          get: function get() {
            return self.config._maxTime;
          },
          set: minMaxTimeSetter("max")
        });

        if (userConfig.mode === "time") {
          self.config.noCalendar = true;
          self.config.enableTime = true;
        }

        Object.assign(self.config, formats$$1, userConfig);

        for (var i = 0; i < boolOpts.length; i++) {
          self.config[boolOpts[i]] = self.config[boolOpts[i]] === true || self.config[boolOpts[i]] === "true";
        }

        HOOKS.filter(function (hook) {
          return self.config[hook] !== undefined;
        }).forEach(function (hook) {
          self.config[hook] = arrayify(self.config[hook] || []).map(bindToInstance);
        });
        self.isMobile = !self.config.disableMobile && !self.config.inline && self.config.mode === "single" && !self.config.disable.length && !self.config.enable.length && !self.config.weekNumbers && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        for (var _i = 0; _i < self.config.plugins.length; _i++) {
          var pluginConf = self.config.plugins[_i](self) || {};

          for (var key in pluginConf) {
            if (HOOKS.indexOf(key) > -1) {
              self.config[key] = arrayify(pluginConf[key]).map(bindToInstance).concat(self.config[key]);
            } else if (typeof userConfig[key] === "undefined") self.config[key] = pluginConf[key];
          }
        }

        triggerEvent("onParseConfig");
      }

      function setupLocale() {
        if (typeof self.config.locale !== "object" && typeof flatpickr.l10ns[self.config.locale] === "undefined") self.config.errorHandler(new Error("flatpickr: invalid locale " + self.config.locale));
        self.l10n = Object.assign({}, flatpickr.l10ns.default, typeof self.config.locale === "object" ? self.config.locale : self.config.locale !== "default" ? flatpickr.l10ns[self.config.locale] : undefined);
        tokenRegex.K = "(" + self.l10n.amPM[0] + "|" + self.l10n.amPM[1] + "|" + self.l10n.amPM[0].toLowerCase() + "|" + self.l10n.amPM[1].toLowerCase() + ")";
        self.formatDate = createDateFormatter(self);
        self.parseDate = createDateParser({
          config: self.config,
          l10n: self.l10n
        });
      }

      function positionCalendar(customPositionElement) {
        if (self.calendarContainer === undefined) return;
        triggerEvent("onPreCalendarPosition");
        var positionElement = customPositionElement || self._positionElement;
        var calendarHeight = Array.prototype.reduce.call(self.calendarContainer.children, function (acc, child) {
          return acc + child.offsetHeight;
        }, 0),
            calendarWidth = self.calendarContainer.offsetWidth,
            configPos = self.config.position.split(" "),
            configPosVertical = configPos[0],
            configPosHorizontal = configPos.length > 1 ? configPos[1] : null,
            inputBounds = positionElement.getBoundingClientRect(),
            distanceFromBottom = window.innerHeight - inputBounds.bottom,
            showOnTop = configPosVertical === "above" || configPosVertical !== "below" && distanceFromBottom < calendarHeight && inputBounds.top > calendarHeight;
        var top = window.pageYOffset + inputBounds.top + (!showOnTop ? positionElement.offsetHeight + 2 : -calendarHeight - 2);
        toggleClass(self.calendarContainer, "arrowTop", !showOnTop);
        toggleClass(self.calendarContainer, "arrowBottom", showOnTop);
        if (self.config.inline) return;
        var left = window.pageXOffset + inputBounds.left - (configPosHorizontal != null && configPosHorizontal === "center" ? (calendarWidth - inputBounds.width) / 2 : 0);
        var right = window.document.body.offsetWidth - inputBounds.right;
        var rightMost = left + calendarWidth > window.document.body.offsetWidth;
        toggleClass(self.calendarContainer, "rightMost", rightMost);
        if (self.config.static) return;
        self.calendarContainer.style.top = top + "px";

        if (!rightMost) {
          self.calendarContainer.style.left = left + "px";
          self.calendarContainer.style.right = "auto";
        } else {
          self.calendarContainer.style.left = "auto";
          self.calendarContainer.style.right = right + "px";
        }
      }

      function redraw() {
        if (self.config.noCalendar || self.isMobile) return;
        updateNavigationCurrentMonth();
        buildDays();
      }

      function focusAndClose() {
        self._input.focus();

        if (window.navigator.userAgent.indexOf("MSIE") !== -1 || navigator.msMaxTouchPoints !== undefined) {
          setTimeout(self.close, 0);
        } else {
          self.close();
        }
      }

      function selectDate(e) {
        e.preventDefault();
        e.stopPropagation();

        var isSelectable = function isSelectable(day) {
          return day.classList && day.classList.contains("flatpickr-day") && !day.classList.contains("disabled") && !day.classList.contains("notAllowed");
        };

        var t = findParent(e.target, isSelectable);
        if (t === undefined) return;
        var target = t;
        var selectedDate = self.latestSelectedDateObj = new Date(target.dateObj.getTime());
        var shouldChangeMonth = (selectedDate.getMonth() < self.currentMonth || selectedDate.getMonth() > self.currentMonth + self.config.showMonths - 1) && self.config.mode !== "range";
        self.selectedDateElem = target;
        if (self.config.mode === "single") self.selectedDates = [selectedDate];else if (self.config.mode === "multiple") {
          var selectedIndex = isDateSelected(selectedDate);
          if (selectedIndex) self.selectedDates.splice(parseInt(selectedIndex), 1);else self.selectedDates.push(selectedDate);
        } else if (self.config.mode === "range") {
          if (self.selectedDates.length === 2) self.clear(false);
          self.selectedDates.push(selectedDate);
          if (compareDates(selectedDate, self.selectedDates[0], true) !== 0) self.selectedDates.sort(function (a, b) {
            return a.getTime() - b.getTime();
          });
        }
        setHoursFromInputs();

        if (shouldChangeMonth) {
          var isNewYear = self.currentYear !== selectedDate.getFullYear();
          self.currentYear = selectedDate.getFullYear();
          self.currentMonth = selectedDate.getMonth();
          if (isNewYear) triggerEvent("onYearChange");
          triggerEvent("onMonthChange");
        }

        updateNavigationCurrentMonth();
        buildDays();
        updateValue();
        if (self.config.enableTime) setTimeout(function () {
          return self.showTimeInput = true;
        }, 50);
        if (!shouldChangeMonth && self.config.mode !== "range" && self.config.showMonths === 1) focusOnDayElem(target);else self.selectedDateElem && self.selectedDateElem.focus();
        if (self.hourElement !== undefined) setTimeout(function () {
          return self.hourElement !== undefined && self.hourElement.select();
        }, 451);

        if (self.config.closeOnSelect) {
          var single = self.config.mode === "single" && !self.config.enableTime;
          var range = self.config.mode === "range" && self.selectedDates.length === 2 && !self.config.enableTime;

          if (single || range) {
            focusAndClose();
          }
        }

        triggerChange();
      }

      var CALLBACKS = {
        locale: [setupLocale, updateWeekdays],
        showMonths: [buildMonths, setCalendarWidth, buildWeekdays]
      };

      function set(option, value) {
        if (option !== null && typeof option === "object") Object.assign(self.config, option);else {
          self.config[option] = value;
          if (CALLBACKS[option] !== undefined) CALLBACKS[option].forEach(function (x) {
            return x();
          });else if (HOOKS.indexOf(option) > -1) self.config[option] = arrayify(value);
        }
        self.redraw();
        jumpToDate();
        updateValue(false);
      }

      function setSelectedDate(inputDate, format) {
        var dates = [];
        if (inputDate instanceof Array) dates = inputDate.map(function (d) {
          return self.parseDate(d, format);
        });else if (inputDate instanceof Date || typeof inputDate === "number") dates = [self.parseDate(inputDate, format)];else if (typeof inputDate === "string") {
          switch (self.config.mode) {
            case "single":
            case "time":
              dates = [self.parseDate(inputDate, format)];
              break;

            case "multiple":
              dates = inputDate.split(self.config.conjunction).map(function (date) {
                return self.parseDate(date, format);
              });
              break;

            case "range":
              dates = inputDate.split(self.l10n.rangeSeparator).map(function (date) {
                return self.parseDate(date, format);
              });
              break;

            default:
              break;
          }
        } else self.config.errorHandler(new Error("Invalid date supplied: " + JSON.stringify(inputDate)));
        self.selectedDates = dates.filter(function (d) {
          return d instanceof Date && isEnabled(d, false);
        });
        if (self.config.mode === "range") self.selectedDates.sort(function (a, b) {
          return a.getTime() - b.getTime();
        });
      }

      function setDate(date, triggerChange, format) {
        if (triggerChange === void 0) {
          triggerChange = false;
        }

        if (format === void 0) {
          format = self.config.dateFormat;
        }

        if (date !== 0 && !date || date instanceof Array && date.length === 0) return self.clear(triggerChange);
        setSelectedDate(date, format);
        self.showTimeInput = self.selectedDates.length > 0;
        self.latestSelectedDateObj = self.selectedDates[0];
        self.redraw();
        jumpToDate();
        setHoursFromDate();
        updateValue(triggerChange);
        if (triggerChange) triggerEvent("onChange");
      }

      function parseDateRules(arr) {
        return arr.slice().map(function (rule) {
          if (typeof rule === "string" || typeof rule === "number" || rule instanceof Date) {
            return self.parseDate(rule, undefined, true);
          } else if (rule && typeof rule === "object" && rule.from && rule.to) return {
            from: self.parseDate(rule.from, undefined),
            to: self.parseDate(rule.to, undefined)
          };

          return rule;
        }).filter(function (x) {
          return x;
        });
      }

      function setupDates() {
        self.selectedDates = [];
        self.now = self.parseDate(self.config.now) || new Date();
        var preloadedDate = self.config.defaultDate || ((self.input.nodeName === "INPUT" || self.input.nodeName === "TEXTAREA") && self.input.placeholder && self.input.value === self.input.placeholder ? null : self.input.value);
        if (preloadedDate) setSelectedDate(preloadedDate, self.config.dateFormat);
        var initialDate = self.selectedDates.length > 0 ? self.selectedDates[0] : self.config.minDate && self.config.minDate.getTime() > self.now.getTime() ? self.config.minDate : self.config.maxDate && self.config.maxDate.getTime() < self.now.getTime() ? self.config.maxDate : self.now;
        self.currentYear = initialDate.getFullYear();
        self.currentMonth = initialDate.getMonth();
        if (self.selectedDates.length > 0) self.latestSelectedDateObj = self.selectedDates[0];
        if (self.config.minTime !== undefined) self.config.minTime = self.parseDate(self.config.minTime, "H:i");
        if (self.config.maxTime !== undefined) self.config.maxTime = self.parseDate(self.config.maxTime, "H:i");
        self.minDateHasTime = !!self.config.minDate && (self.config.minDate.getHours() > 0 || self.config.minDate.getMinutes() > 0 || self.config.minDate.getSeconds() > 0);
        self.maxDateHasTime = !!self.config.maxDate && (self.config.maxDate.getHours() > 0 || self.config.maxDate.getMinutes() > 0 || self.config.maxDate.getSeconds() > 0);
        Object.defineProperty(self, "showTimeInput", {
          get: function get() {
            return self._showTimeInput;
          },
          set: function set(bool) {
            self._showTimeInput = bool;
            if (self.calendarContainer) toggleClass(self.calendarContainer, "showTimeInput", bool);
            self.isOpen && positionCalendar();
          }
        });
      }

      function setupInputs() {
        self.input = self.config.wrap ? element.querySelector("[data-input]") : element;

        if (!self.input) {
          self.config.errorHandler(new Error("Invalid input element specified"));
          return;
        }

        self.input._type = self.input.type;
        self.input.type = "text";
        self.input.classList.add("flatpickr-input");
        self._input = self.input;

        if (self.config.altInput) {
          self.altInput = createElement(self.input.nodeName, self.input.className + " " + self.config.altInputClass);
          self._input = self.altInput;
          self.altInput.placeholder = self.input.placeholder;
          self.altInput.disabled = self.input.disabled;
          self.altInput.required = self.input.required;
          self.altInput.tabIndex = self.input.tabIndex;
          self.altInput.type = "text";
          self.input.setAttribute("type", "hidden");
          if (!self.config.static && self.input.parentNode) self.input.parentNode.insertBefore(self.altInput, self.input.nextSibling);
        }

        if (!self.config.allowInput) self._input.setAttribute("readonly", "readonly");
        self._positionElement = self.config.positionElement || self._input;
      }

      function setupMobile() {
        var inputType = self.config.enableTime ? self.config.noCalendar ? "time" : "datetime-local" : "date";
        self.mobileInput = createElement("input", self.input.className + " flatpickr-mobile");
        self.mobileInput.step = self.input.getAttribute("step") || "any";
        self.mobileInput.tabIndex = 1;
        self.mobileInput.type = inputType;
        self.mobileInput.disabled = self.input.disabled;
        self.mobileInput.required = self.input.required;
        self.mobileInput.placeholder = self.input.placeholder;
        self.mobileFormatStr = inputType === "datetime-local" ? "Y-m-d\\TH:i:S" : inputType === "date" ? "Y-m-d" : "H:i:S";

        if (self.selectedDates.length > 0) {
          self.mobileInput.defaultValue = self.mobileInput.value = self.formatDate(self.selectedDates[0], self.mobileFormatStr);
        }

        if (self.config.minDate) self.mobileInput.min = self.formatDate(self.config.minDate, "Y-m-d");
        if (self.config.maxDate) self.mobileInput.max = self.formatDate(self.config.maxDate, "Y-m-d");
        self.input.type = "hidden";
        if (self.altInput !== undefined) self.altInput.type = "hidden";

        try {
          if (self.input.parentNode) self.input.parentNode.insertBefore(self.mobileInput, self.input.nextSibling);
        } catch (_a) {}

        bind(self.mobileInput, "change", function (e) {
          self.setDate(e.target.value, false, self.mobileFormatStr);
          triggerEvent("onChange");
          triggerEvent("onClose");
        });
      }

      function toggle(e) {
        if (self.isOpen === true) return self.close();
        self.open(e);
      }

      function triggerEvent(event, data) {
        if (self.config === undefined) return;
        var hooks = self.config[event];

        if (hooks !== undefined && hooks.length > 0) {
          for (var i = 0; hooks[i] && i < hooks.length; i++) {
            hooks[i](self.selectedDates, self.input.value, self, data);
          }
        }

        if (event === "onChange") {
          self.input.dispatchEvent(createEvent("change"));
          self.input.dispatchEvent(createEvent("input"));
        }
      }

      function createEvent(name) {
        var e = document.createEvent("Event");
        e.initEvent(name, true, true);
        return e;
      }

      function isDateSelected(date) {
        for (var i = 0; i < self.selectedDates.length; i++) {
          if (compareDates(self.selectedDates[i], date) === 0) return "" + i;
        }

        return false;
      }

      function isDateInRange(date) {
        if (self.config.mode !== "range" || self.selectedDates.length < 2) return false;
        return compareDates(date, self.selectedDates[0]) >= 0 && compareDates(date, self.selectedDates[1]) <= 0;
      }

      function updateNavigationCurrentMonth() {
        if (self.config.noCalendar || self.isMobile || !self.monthNav) return;
        self.yearElements.forEach(function (yearElement, i) {
          var d = new Date(self.currentYear, self.currentMonth, 1);
          d.setMonth(self.currentMonth + i);
          self.monthElements[i].textContent = monthToStr(d.getMonth(), self.config.shorthandCurrentMonth, self.l10n) + " ";
          yearElement.value = d.getFullYear().toString();
        });
        self._hidePrevMonthArrow = self.config.minDate !== undefined && (self.currentYear === self.config.minDate.getFullYear() ? self.currentMonth <= self.config.minDate.getMonth() : self.currentYear < self.config.minDate.getFullYear());
        self._hideNextMonthArrow = self.config.maxDate !== undefined && (self.currentYear === self.config.maxDate.getFullYear() ? self.currentMonth + 1 > self.config.maxDate.getMonth() : self.currentYear > self.config.maxDate.getFullYear());
      }

      function getDateStr(format) {
        return self.selectedDates.map(function (dObj) {
          return self.formatDate(dObj, format);
        }).filter(function (d, i, arr) {
          return self.config.mode !== "range" || self.config.enableTime || arr.indexOf(d) === i;
        }).join(self.config.mode !== "range" ? self.config.conjunction : self.l10n.rangeSeparator);
      }

      function updateValue(triggerChange) {
        if (triggerChange === void 0) {
          triggerChange = true;
        }

        if (self.selectedDates.length === 0) return self.clear(triggerChange);

        if (self.mobileInput !== undefined && self.mobileFormatStr) {
          self.mobileInput.value = self.latestSelectedDateObj !== undefined ? self.formatDate(self.latestSelectedDateObj, self.mobileFormatStr) : "";
        }

        self.input.value = getDateStr(self.config.dateFormat);

        if (self.altInput !== undefined) {
          self.altInput.value = getDateStr(self.config.altFormat);
        }

        if (triggerChange !== false) triggerEvent("onValueUpdate");
      }

      function onMonthNavClick(e) {
        e.preventDefault();
        var isPrevMonth = self.prevMonthNav.contains(e.target);
        var isNextMonth = self.nextMonthNav.contains(e.target);

        if (isPrevMonth || isNextMonth) {
          changeMonth(isPrevMonth ? -1 : 1);
        } else if (self.yearElements.indexOf(e.target) >= 0) {
          e.target.select();
        } else if (e.target.classList.contains("arrowUp")) {
          self.changeYear(self.currentYear + 1);
        } else if (e.target.classList.contains("arrowDown")) {
          self.changeYear(self.currentYear - 1);
        }
      }

      function timeWrapper(e) {
        e.preventDefault();
        var isKeyDown = e.type === "keydown",
            input = e.target;

        if (self.amPM !== undefined && e.target === self.amPM) {
          self.amPM.textContent = self.l10n.amPM[int(self.amPM.textContent === self.l10n.amPM[0])];
        }

        var min = parseFloat(input.getAttribute("data-min")),
            max = parseFloat(input.getAttribute("data-max")),
            step = parseFloat(input.getAttribute("data-step")),
            curValue = parseInt(input.value, 10),
            delta = e.delta || (isKeyDown ? e.which === 38 ? 1 : -1 : 0);
        var newValue = curValue + step * delta;

        if (typeof input.value !== "undefined" && input.value.length === 2) {
          var isHourElem = input === self.hourElement,
              isMinuteElem = input === self.minuteElement;

          if (newValue < min) {
            newValue = max + newValue + int(!isHourElem) + (int(isHourElem) && int(!self.amPM));
            if (isMinuteElem) incrementNumInput(undefined, -1, self.hourElement);
          } else if (newValue > max) {
            newValue = input === self.hourElement ? newValue - max - int(!self.amPM) : min;
            if (isMinuteElem) incrementNumInput(undefined, 1, self.hourElement);
          }

          if (self.amPM && isHourElem && (step === 1 ? newValue + curValue === 23 : Math.abs(newValue - curValue) > step)) {
            self.amPM.textContent = self.l10n.amPM[int(self.amPM.textContent === self.l10n.amPM[0])];
          }

          input.value = pad(newValue);
        }
      }

      init();
      return self;
    }

    function _flatpickr(nodeList, config) {
      var nodes = Array.prototype.slice.call(nodeList);
      var instances = [];

      for (var i = 0; i < nodes.length; i++) {
        var node = nodes[i];

        try {
          if (node.getAttribute("data-fp-omit") !== null) continue;

          if (node._flatpickr !== undefined) {
            node._flatpickr.destroy();

            node._flatpickr = undefined;
          }

          node._flatpickr = FlatpickrInstance(node, config || {});
          instances.push(node._flatpickr);
        } catch (e) {
          console.error(e);
        }
      }

      return instances.length === 1 ? instances[0] : instances;
    }

    if (typeof HTMLElement !== "undefined") {
      HTMLCollection.prototype.flatpickr = NodeList.prototype.flatpickr = function (config) {
        return _flatpickr(this, config);
      };

      HTMLElement.prototype.flatpickr = function (config) {
        return _flatpickr([this], config);
      };
    }

    var flatpickr = function flatpickr(selector, config) {
      if (selector instanceof NodeList) return _flatpickr(selector, config);else if (typeof selector === "string") return _flatpickr(window.document.querySelectorAll(selector), config);
      return _flatpickr([selector], config);
    };

    flatpickr.defaultConfig = defaults;
    flatpickr.l10ns = {
      en: Object.assign({}, english),
      default: Object.assign({}, english)
    };

    flatpickr.localize = function (l10n) {
      flatpickr.l10ns.default = Object.assign({}, flatpickr.l10ns.default, l10n);
    };

    flatpickr.setDefaults = function (config) {
      flatpickr.defaultConfig = Object.assign({}, flatpickr.defaultConfig, config);
    };

    flatpickr.parseDate = createDateParser({});
    flatpickr.formatDate = createDateFormatter({});
    flatpickr.compareDates = compareDates;

    if (typeof jQuery !== "undefined") {
      jQuery.fn.flatpickr = function (config) {
        return _flatpickr(this, config);
      };
    }

    Date.prototype.fp_incr = function (days) {
      return new Date(this.getFullYear(), this.getMonth(), this.getDate() + (typeof days === "string" ? parseInt(days, 10) : days));
    };

    if (typeof window !== "undefined") {
      window.flatpickr = flatpickr;
    }

    return flatpickr;

})));


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(4);
__webpack_require__(62);
module.exports = __webpack_require__(63);


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

Vue.component("flash-wrapper", __webpack_require__(5));
Vue.component("flash", __webpack_require__(8));
Vue.component("tabs", __webpack_require__(11));
Vue.component("tab", __webpack_require__(14));
Vue.component("accordian", __webpack_require__(17));
Vue.component("tree-view", __webpack_require__(20));
Vue.component("tree-item", __webpack_require__(22));
Vue.component("tree-checkbox", __webpack_require__(24));
Vue.component("tree-radio", __webpack_require__(27));
Vue.component("modal", __webpack_require__(30));
Vue.component("image-upload", __webpack_require__(33));
Vue.component("image-wrapper", __webpack_require__(40));
Vue.component("image-item", __webpack_require__(43));
Vue.directive("slugify", __webpack_require__(46));
Vue.directive("code", __webpack_require__(48));
Vue.directive("alert", __webpack_require__(50));
Vue.component("datetime", __webpack_require__(52));
Vue.component("date", __webpack_require__(55));

__webpack_require__(58);

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(6)
/* template */
var __vue_template__ = __webpack_require__(7)
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
/* 6 */
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
/* 7 */
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
    }),
    1
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
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(9)
/* template */
var __vue_template__ = __webpack_require__(10)
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
/* 9 */
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
/* 10 */
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
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(12)
/* template */
var __vue_template__ = __webpack_require__(13)
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
/* 12 */
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
/* 13 */
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
        }),
        0
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
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(15)
/* template */
var __vue_template__ = __webpack_require__(16)
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
/* 15 */
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
/* 16 */
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
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(18)
/* template */
var __vue_template__ = __webpack_require__(19)
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
/* 18 */
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
/* 19 */
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
/* 21 */
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
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(23)
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
/* 23 */
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
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(25)
/* template */
var __vue_template__ = __webpack_require__(26)
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
/* 25 */
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
/* 26 */
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
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(28)
/* template */
var __vue_template__ = __webpack_require__(29)
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
/* 28 */
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
/* 29 */
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
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(31)
/* template */
var __vue_template__ = __webpack_require__(32)
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
/* 31 */
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
/* 32 */
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
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(34)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(38)
/* template */
var __vue_template__ = __webpack_require__(39)
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
/* 34 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(35);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(36)("4d51c808", content, false, {});
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
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(1)(false);
// imports


// module
exports.push([module.i, "\n.preview-wrapper{\n    height:200px;\n    width:200px;\n    padding:5px;\n}\n.image-preview{\n    height:190px;\n    width:190px;\n}\n", ""]);

// exports


/***/ }),
/* 36 */
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

var listToStyles = __webpack_require__(37)

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
/* 37 */
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
/* 38 */
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
/* 39 */
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
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(41)
/* template */
var __vue_template__ = __webpack_require__(42)
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
/* 41 */
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
            if (this.images.length) {
                this.images.forEach(function (image) {
                    this_this.items.push(image);

                    this_this.imageCount++;
                });
            } else {
                this.createFileType();
            }
        } else {
            if (this.images && this.images != '') {
                this.items.push({ 'id': 'image_' + this.imageCount, 'url': this.images });

                this.imageCount++;
            } else {
                this.createFileType();
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
/* 42 */
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
      }),
      1
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
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(44)
/* template */
var __vue_template__ = __webpack_require__(45)
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
/* 44 */
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
                if (imageInput.files[0].type.includes('image/')) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        _this.imageData = e.target.result;
                    };

                    reader.readAsDataURL(imageInput.files[0]);
                } else {
                    imageInput.value = "";
                    alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                }
            }
        },
        removeImage: function removeImage() {
            this.$emit('onRemoveImage', this.image);
        }
    }
});

/***/ }),
/* 45 */
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
        directives: [
          {
            name: "validate",
            rawName: "v-validate",
            value: "mimes:image/*",
            expression: "'mimes:image/*'"
          }
        ],
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
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(47)
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
/* 47 */
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
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(49)
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
/* 49 */
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
/* 50 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(51)
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
/* 51 */
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
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(53)
/* template */
var __vue_template__ = __webpack_require__(54)
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
Component.options.__file = "src/Resources/assets/js/components/datetime.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4ff36e47", Component.options)
  } else {
    hotAPI.reload("data-v-4ff36e47", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 53 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_flatpickr__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_flatpickr___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_flatpickr__);
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
		name: String,
		value: String
	},

	data: function data() {
		return {
			datepicker: null
		};
	},
	mounted: function mounted() {
		var this_this = this;

		var element = this.$el.getElementsByTagName("input")[0];
		this.datepicker = new __WEBPACK_IMPORTED_MODULE_0_flatpickr___default.a(element, {
			allowInput: true,
			altFormat: "Y-m-d H:i:s",
			dateFormat: "Y-m-d H:i:s",
			enableTime: true,
			onChange: function onChange(selectedDates, dateStr, instance) {
				this_this.$emit('onChange', dateStr);
			}
		});
	}
});

/***/ }),
/* 54 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "span",
    [
      _vm._t("default", [
        _c("input", {
          staticClass: "control",
          attrs: { type: "text", name: _vm.name, "data-input": "" },
          domProps: { value: _vm.value }
        })
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
    require("vue-hot-reload-api")      .rerender("data-v-4ff36e47", module.exports)
  }
}

/***/ }),
/* 55 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(56)
/* template */
var __vue_template__ = __webpack_require__(57)
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
Component.options.__file = "src/Resources/assets/js/components/date.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2f144afa", Component.options)
  } else {
    hotAPI.reload("data-v-2f144afa", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 56 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_flatpickr__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_flatpickr___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_flatpickr__);
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
		name: String,
		value: String
	},

	data: function data() {
		return {
			datepicker: null
		};
	},
	mounted: function mounted() {
		var this_this = this;

		var element = this.$el.getElementsByTagName('input')[0];
		this.datepicker = new __WEBPACK_IMPORTED_MODULE_0_flatpickr___default.a(element, {
			altFormat: 'Y-m-d',
			dateFormat: 'Y-m-d',
			onChange: function onChange(selectedDates, dateStr, instance) {
				this_this.$emit('onChange', dateStr);
			}
		});
	}
});

/***/ }),
/* 57 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "span",
    [
      _vm._t("default", [
        _c("input", {
          staticClass: "control",
          attrs: { type: "text", name: _vm.name, "data-input": "" },
          domProps: { value: _vm.value }
        })
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
    require("vue-hot-reload-api")      .rerender("data-v-2f144afa", module.exports)
  }
}

/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(59);
if(typeof content === 'string') content = [[module.i, content, '']];
// Prepare cssTransformation
var transform;

var options = {}
options.transform = transform
// add the styles to the DOM
var update = __webpack_require__(60)(content, options);
if(content.locals) module.exports = content.locals;
// Hot Module Replacement
if(false) {
	// When the styles change, update the <style> tags
	if(!content.locals) {
		module.hot.accept("!!../../css-loader/index.js!./flatpickr.css", function() {
			var newContent = require("!!../../css-loader/index.js!./flatpickr.css");
			if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
			update(newContent);
		});
	}
	// When the module is disposed, remove the <style> tags
	module.hot.dispose(function() { update(); });
}

/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(1)(false);
// imports


// module
exports.push([module.i, ".flatpickr-calendar {\n  background: transparent;\n  opacity: 0;\n  display: none;\n  text-align: center;\n  visibility: hidden;\n  padding: 0;\n  -webkit-animation: none;\n          animation: none;\n  direction: ltr;\n  border: 0;\n  font-size: 14px;\n  line-height: 24px;\n  border-radius: 5px;\n  position: absolute;\n  width: 307.875px;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  -ms-touch-action: manipulation;\n      touch-action: manipulation;\n  background: #fff;\n  -webkit-box-shadow: 1px 0 0 #e6e6e6, -1px 0 0 #e6e6e6, 0 1px 0 #e6e6e6, 0 -1px 0 #e6e6e6, 0 3px 13px rgba(0,0,0,0.08);\n          box-shadow: 1px 0 0 #e6e6e6, -1px 0 0 #e6e6e6, 0 1px 0 #e6e6e6, 0 -1px 0 #e6e6e6, 0 3px 13px rgba(0,0,0,0.08);\n}\n.flatpickr-calendar.open,\n.flatpickr-calendar.inline {\n  opacity: 1;\n  max-height: 640px;\n  visibility: visible;\n}\n.flatpickr-calendar.open {\n  display: inline-block;\n  z-index: 99999;\n}\n.flatpickr-calendar.animate.open {\n  -webkit-animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);\n          animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);\n}\n.flatpickr-calendar.inline {\n  display: block;\n  position: relative;\n  top: 2px;\n}\n.flatpickr-calendar.static {\n  position: absolute;\n  top: calc(100% + 2px);\n}\n.flatpickr-calendar.static.open {\n  z-index: 999;\n  display: block;\n}\n.flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+1) .flatpickr-day.inRange:nth-child(7n+7) {\n  -webkit-box-shadow: none !important;\n          box-shadow: none !important;\n}\n.flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+2) .flatpickr-day.inRange:nth-child(7n+1) {\n  -webkit-box-shadow: -2px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;\n          box-shadow: -2px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;\n}\n.flatpickr-calendar .hasWeeks .dayContainer,\n.flatpickr-calendar .hasTime .dayContainer {\n  border-bottom: 0;\n  border-bottom-right-radius: 0;\n  border-bottom-left-radius: 0;\n}\n.flatpickr-calendar .hasWeeks .dayContainer {\n  border-left: 0;\n}\n.flatpickr-calendar.showTimeInput.hasTime .flatpickr-time {\n  height: 40px;\n  border-top: 1px solid #e6e6e6;\n}\n.flatpickr-calendar.noCalendar.hasTime .flatpickr-time {\n  height: auto;\n}\n.flatpickr-calendar:before,\n.flatpickr-calendar:after {\n  position: absolute;\n  display: block;\n  pointer-events: none;\n  border: solid transparent;\n  content: '';\n  height: 0;\n  width: 0;\n  left: 22px;\n}\n.flatpickr-calendar.rightMost:before,\n.flatpickr-calendar.rightMost:after {\n  left: auto;\n  right: 22px;\n}\n.flatpickr-calendar:before {\n  border-width: 5px;\n  margin: 0 -5px;\n}\n.flatpickr-calendar:after {\n  border-width: 4px;\n  margin: 0 -4px;\n}\n.flatpickr-calendar.arrowTop:before,\n.flatpickr-calendar.arrowTop:after {\n  bottom: 100%;\n}\n.flatpickr-calendar.arrowTop:before {\n  border-bottom-color: #e6e6e6;\n}\n.flatpickr-calendar.arrowTop:after {\n  border-bottom-color: #fff;\n}\n.flatpickr-calendar.arrowBottom:before,\n.flatpickr-calendar.arrowBottom:after {\n  top: 100%;\n}\n.flatpickr-calendar.arrowBottom:before {\n  border-top-color: #e6e6e6;\n}\n.flatpickr-calendar.arrowBottom:after {\n  border-top-color: #fff;\n}\n.flatpickr-calendar:focus {\n  outline: 0;\n}\n.flatpickr-wrapper {\n  position: relative;\n  display: inline-block;\n}\n.flatpickr-months {\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: -ms-flexbox;\n  display: flex;\n}\n.flatpickr-months .flatpickr-month {\n  background: transparent;\n  color: rgba(0,0,0,0.9);\n  fill: rgba(0,0,0,0.9);\n  height: 28px;\n  line-height: 1;\n  text-align: center;\n  position: relative;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n  overflow: hidden;\n  -webkit-box-flex: 1;\n  -webkit-flex: 1;\n      -ms-flex: 1;\n          flex: 1;\n}\n.flatpickr-months .flatpickr-prev-month,\n.flatpickr-months .flatpickr-next-month {\n  text-decoration: none;\n  cursor: pointer;\n  position: absolute;\n  top: 0px;\n  line-height: 16px;\n  height: 28px;\n  padding: 10px;\n  z-index: 3;\n  color: rgba(0,0,0,0.9);\n  fill: rgba(0,0,0,0.9);\n}\n.flatpickr-months .flatpickr-prev-month.disabled,\n.flatpickr-months .flatpickr-next-month.disabled {\n  display: none;\n}\n.flatpickr-months .flatpickr-prev-month i,\n.flatpickr-months .flatpickr-next-month i {\n  position: relative;\n}\n.flatpickr-months .flatpickr-prev-month.flatpickr-prev-month,\n.flatpickr-months .flatpickr-next-month.flatpickr-prev-month {\n/*\n      /*rtl:begin:ignore*/\n/*\n      */\n  left: 0;\n/*\n      /*rtl:end:ignore*/\n/*\n      */\n}\n/*\n      /*rtl:begin:ignore*/\n/*\n      /*rtl:end:ignore*/\n.flatpickr-months .flatpickr-prev-month.flatpickr-next-month,\n.flatpickr-months .flatpickr-next-month.flatpickr-next-month {\n/*\n      /*rtl:begin:ignore*/\n/*\n      */\n  right: 0;\n/*\n      /*rtl:end:ignore*/\n/*\n      */\n}\n/*\n      /*rtl:begin:ignore*/\n/*\n      /*rtl:end:ignore*/\n.flatpickr-months .flatpickr-prev-month:hover,\n.flatpickr-months .flatpickr-next-month:hover {\n  color: #959ea9;\n}\n.flatpickr-months .flatpickr-prev-month:hover svg,\n.flatpickr-months .flatpickr-next-month:hover svg {\n  fill: #f64747;\n}\n.flatpickr-months .flatpickr-prev-month svg,\n.flatpickr-months .flatpickr-next-month svg {\n  width: 14px;\n  height: 14px;\n}\n.flatpickr-months .flatpickr-prev-month svg path,\n.flatpickr-months .flatpickr-next-month svg path {\n  -webkit-transition: fill 0.1s;\n  transition: fill 0.1s;\n  fill: inherit;\n}\n.numInputWrapper {\n  position: relative;\n  height: auto;\n}\n.numInputWrapper input,\n.numInputWrapper span {\n  display: inline-block;\n}\n.numInputWrapper input {\n  width: 100%;\n}\n.numInputWrapper input::-ms-clear {\n  display: none;\n}\n.numInputWrapper span {\n  position: absolute;\n  right: 0;\n  width: 14px;\n  padding: 0 4px 0 2px;\n  height: 50%;\n  line-height: 50%;\n  opacity: 0;\n  cursor: pointer;\n  border: 1px solid rgba(57,57,57,0.15);\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n}\n.numInputWrapper span:hover {\n  background: rgba(0,0,0,0.1);\n}\n.numInputWrapper span:active {\n  background: rgba(0,0,0,0.2);\n}\n.numInputWrapper span:after {\n  display: block;\n  content: \"\";\n  position: absolute;\n}\n.numInputWrapper span.arrowUp {\n  top: 0;\n  border-bottom: 0;\n}\n.numInputWrapper span.arrowUp:after {\n  border-left: 4px solid transparent;\n  border-right: 4px solid transparent;\n  border-bottom: 4px solid rgba(57,57,57,0.6);\n  top: 26%;\n}\n.numInputWrapper span.arrowDown {\n  top: 50%;\n}\n.numInputWrapper span.arrowDown:after {\n  border-left: 4px solid transparent;\n  border-right: 4px solid transparent;\n  border-top: 4px solid rgba(57,57,57,0.6);\n  top: 40%;\n}\n.numInputWrapper span svg {\n  width: inherit;\n  height: auto;\n}\n.numInputWrapper span svg path {\n  fill: rgba(0,0,0,0.5);\n}\n.numInputWrapper:hover {\n  background: rgba(0,0,0,0.05);\n}\n.numInputWrapper:hover span {\n  opacity: 1;\n}\n.flatpickr-current-month {\n  font-size: 135%;\n  line-height: inherit;\n  font-weight: 300;\n  color: inherit;\n  position: absolute;\n  width: 75%;\n  left: 12.5%;\n  padding: 6.16px 0 0 0;\n  line-height: 1;\n  height: 28px;\n  display: inline-block;\n  text-align: center;\n  -webkit-transform: translate3d(0px, 0px, 0px);\n          transform: translate3d(0px, 0px, 0px);\n}\n.flatpickr-current-month span.cur-month {\n  font-family: inherit;\n  font-weight: 700;\n  color: inherit;\n  display: inline-block;\n  margin-left: 0.5ch;\n  padding: 0;\n}\n.flatpickr-current-month span.cur-month:hover {\n  background: rgba(0,0,0,0.05);\n}\n.flatpickr-current-month .numInputWrapper {\n  width: 6ch;\n  width: 7ch\\0;\n  display: inline-block;\n}\n.flatpickr-current-month .numInputWrapper span.arrowUp:after {\n  border-bottom-color: rgba(0,0,0,0.9);\n}\n.flatpickr-current-month .numInputWrapper span.arrowDown:after {\n  border-top-color: rgba(0,0,0,0.9);\n}\n.flatpickr-current-month input.cur-year {\n  background: transparent;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  color: inherit;\n  cursor: text;\n  padding: 0 0 0 0.5ch;\n  margin: 0;\n  display: inline-block;\n  font-size: inherit;\n  font-family: inherit;\n  font-weight: 300;\n  line-height: inherit;\n  height: auto;\n  border: 0;\n  border-radius: 0;\n  vertical-align: initial;\n}\n.flatpickr-current-month input.cur-year:focus {\n  outline: 0;\n}\n.flatpickr-current-month input.cur-year[disabled],\n.flatpickr-current-month input.cur-year[disabled]:hover {\n  font-size: 100%;\n  color: rgba(0,0,0,0.5);\n  background: transparent;\n  pointer-events: none;\n}\n.flatpickr-weekdays {\n  background: transparent;\n  text-align: center;\n  overflow: hidden;\n  width: 100%;\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-align: center;\n  -webkit-align-items: center;\n      -ms-flex-align: center;\n          align-items: center;\n  height: 28px;\n}\n.flatpickr-weekdays .flatpickr-weekdaycontainer {\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-flex: 1;\n  -webkit-flex: 1;\n      -ms-flex: 1;\n          flex: 1;\n}\nspan.flatpickr-weekday {\n  cursor: default;\n  font-size: 90%;\n  background: transparent;\n  color: rgba(0,0,0,0.54);\n  line-height: 1;\n  margin: 0;\n  text-align: center;\n  display: block;\n  -webkit-box-flex: 1;\n  -webkit-flex: 1;\n      -ms-flex: 1;\n          flex: 1;\n  font-weight: bolder;\n}\n.dayContainer,\n.flatpickr-weeks {\n  padding: 1px 0 0 0;\n}\n.flatpickr-days {\n  position: relative;\n  overflow: hidden;\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-align: start;\n  -webkit-align-items: flex-start;\n      -ms-flex-align: start;\n          align-items: flex-start;\n  width: 307.875px;\n}\n.flatpickr-days:focus {\n  outline: 0;\n}\n.dayContainer {\n  padding: 0;\n  outline: 0;\n  text-align: left;\n  width: 307.875px;\n  min-width: 307.875px;\n  max-width: 307.875px;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  display: inline-block;\n  display: -ms-flexbox;\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: flex;\n  -webkit-flex-wrap: wrap;\n          flex-wrap: wrap;\n  -ms-flex-wrap: wrap;\n  -ms-flex-pack: justify;\n  -webkit-justify-content: space-around;\n          justify-content: space-around;\n  -webkit-transform: translate3d(0px, 0px, 0px);\n          transform: translate3d(0px, 0px, 0px);\n  opacity: 1;\n}\n.dayContainer + .dayContainer {\n  -webkit-box-shadow: -1px 0 0 #e6e6e6;\n          box-shadow: -1px 0 0 #e6e6e6;\n}\n.flatpickr-day {\n  background: none;\n  border: 1px solid transparent;\n  border-radius: 150px;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  color: #393939;\n  cursor: pointer;\n  font-weight: 400;\n  width: 14.2857143%;\n  -webkit-flex-basis: 14.2857143%;\n      -ms-flex-preferred-size: 14.2857143%;\n          flex-basis: 14.2857143%;\n  max-width: 39px;\n  height: 39px;\n  line-height: 39px;\n  margin: 0;\n  display: inline-block;\n  position: relative;\n  -webkit-box-pack: center;\n  -webkit-justify-content: center;\n      -ms-flex-pack: center;\n          justify-content: center;\n  text-align: center;\n}\n.flatpickr-day.inRange,\n.flatpickr-day.prevMonthDay.inRange,\n.flatpickr-day.nextMonthDay.inRange,\n.flatpickr-day.today.inRange,\n.flatpickr-day.prevMonthDay.today.inRange,\n.flatpickr-day.nextMonthDay.today.inRange,\n.flatpickr-day:hover,\n.flatpickr-day.prevMonthDay:hover,\n.flatpickr-day.nextMonthDay:hover,\n.flatpickr-day:focus,\n.flatpickr-day.prevMonthDay:focus,\n.flatpickr-day.nextMonthDay:focus {\n  cursor: pointer;\n  outline: 0;\n  background: #e6e6e6;\n  border-color: #e6e6e6;\n}\n.flatpickr-day.today {\n  border-color: #959ea9;\n}\n.flatpickr-day.today:hover,\n.flatpickr-day.today:focus {\n  border-color: #959ea9;\n  background: #959ea9;\n  color: #fff;\n}\n.flatpickr-day.selected,\n.flatpickr-day.startRange,\n.flatpickr-day.endRange,\n.flatpickr-day.selected.inRange,\n.flatpickr-day.startRange.inRange,\n.flatpickr-day.endRange.inRange,\n.flatpickr-day.selected:focus,\n.flatpickr-day.startRange:focus,\n.flatpickr-day.endRange:focus,\n.flatpickr-day.selected:hover,\n.flatpickr-day.startRange:hover,\n.flatpickr-day.endRange:hover,\n.flatpickr-day.selected.prevMonthDay,\n.flatpickr-day.startRange.prevMonthDay,\n.flatpickr-day.endRange.prevMonthDay,\n.flatpickr-day.selected.nextMonthDay,\n.flatpickr-day.startRange.nextMonthDay,\n.flatpickr-day.endRange.nextMonthDay {\n  background: #569ff7;\n  -webkit-box-shadow: none;\n          box-shadow: none;\n  color: #fff;\n  border-color: #569ff7;\n}\n.flatpickr-day.selected.startRange,\n.flatpickr-day.startRange.startRange,\n.flatpickr-day.endRange.startRange {\n  border-radius: 50px 0 0 50px;\n}\n.flatpickr-day.selected.endRange,\n.flatpickr-day.startRange.endRange,\n.flatpickr-day.endRange.endRange {\n  border-radius: 0 50px 50px 0;\n}\n.flatpickr-day.selected.startRange + .endRange:not(:nth-child(7n+1)),\n.flatpickr-day.startRange.startRange + .endRange:not(:nth-child(7n+1)),\n.flatpickr-day.endRange.startRange + .endRange:not(:nth-child(7n+1)) {\n  -webkit-box-shadow: -10px 0 0 #569ff7;\n          box-shadow: -10px 0 0 #569ff7;\n}\n.flatpickr-day.selected.startRange.endRange,\n.flatpickr-day.startRange.startRange.endRange,\n.flatpickr-day.endRange.startRange.endRange {\n  border-radius: 50px;\n}\n.flatpickr-day.inRange {\n  border-radius: 0;\n  -webkit-box-shadow: -5px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;\n          box-shadow: -5px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;\n}\n.flatpickr-day.disabled,\n.flatpickr-day.disabled:hover,\n.flatpickr-day.prevMonthDay,\n.flatpickr-day.nextMonthDay,\n.flatpickr-day.notAllowed,\n.flatpickr-day.notAllowed.prevMonthDay,\n.flatpickr-day.notAllowed.nextMonthDay {\n  color: rgba(57,57,57,0.3);\n  background: transparent;\n  border-color: transparent;\n  cursor: default;\n}\n.flatpickr-day.disabled,\n.flatpickr-day.disabled:hover {\n  cursor: not-allowed;\n  color: rgba(57,57,57,0.1);\n}\n.flatpickr-day.week.selected {\n  border-radius: 0;\n  -webkit-box-shadow: -5px 0 0 #569ff7, 5px 0 0 #569ff7;\n          box-shadow: -5px 0 0 #569ff7, 5px 0 0 #569ff7;\n}\n.flatpickr-day.hidden {\n  visibility: hidden;\n}\n.rangeMode .flatpickr-day {\n  margin-top: 1px;\n}\n.flatpickr-weekwrapper {\n  display: inline-block;\n  float: left;\n}\n.flatpickr-weekwrapper .flatpickr-weeks {\n  padding: 0 12px;\n  -webkit-box-shadow: 1px 0 0 #e6e6e6;\n          box-shadow: 1px 0 0 #e6e6e6;\n}\n.flatpickr-weekwrapper .flatpickr-weekday {\n  float: none;\n  width: 100%;\n  line-height: 28px;\n}\n.flatpickr-weekwrapper span.flatpickr-day,\n.flatpickr-weekwrapper span.flatpickr-day:hover {\n  display: block;\n  width: 100%;\n  max-width: none;\n  color: rgba(57,57,57,0.3);\n  background: transparent;\n  cursor: default;\n  border: none;\n}\n.flatpickr-innerContainer {\n  display: block;\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  overflow: hidden;\n}\n.flatpickr-rContainer {\n  display: inline-block;\n  padding: 0;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n}\n.flatpickr-time {\n  text-align: center;\n  outline: 0;\n  display: block;\n  height: 0;\n  line-height: 40px;\n  max-height: 40px;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  overflow: hidden;\n  display: -webkit-box;\n  display: -webkit-flex;\n  display: -ms-flexbox;\n  display: flex;\n}\n.flatpickr-time:after {\n  content: \"\";\n  display: table;\n  clear: both;\n}\n.flatpickr-time .numInputWrapper {\n  -webkit-box-flex: 1;\n  -webkit-flex: 1;\n      -ms-flex: 1;\n          flex: 1;\n  width: 40%;\n  height: 40px;\n  float: left;\n}\n.flatpickr-time .numInputWrapper span.arrowUp:after {\n  border-bottom-color: #393939;\n}\n.flatpickr-time .numInputWrapper span.arrowDown:after {\n  border-top-color: #393939;\n}\n.flatpickr-time.hasSeconds .numInputWrapper {\n  width: 26%;\n}\n.flatpickr-time.time24hr .numInputWrapper {\n  width: 49%;\n}\n.flatpickr-time input {\n  background: transparent;\n  -webkit-box-shadow: none;\n          box-shadow: none;\n  border: 0;\n  border-radius: 0;\n  text-align: center;\n  margin: 0;\n  padding: 0;\n  height: inherit;\n  line-height: inherit;\n  color: #393939;\n  font-size: 14px;\n  position: relative;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n}\n.flatpickr-time input.flatpickr-hour {\n  font-weight: bold;\n}\n.flatpickr-time input.flatpickr-minute,\n.flatpickr-time input.flatpickr-second {\n  font-weight: 400;\n}\n.flatpickr-time input:focus {\n  outline: 0;\n  border: 0;\n}\n.flatpickr-time .flatpickr-time-separator,\n.flatpickr-time .flatpickr-am-pm {\n  height: inherit;\n  display: inline-block;\n  float: left;\n  line-height: inherit;\n  color: #393939;\n  font-weight: bold;\n  width: 2%;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n  -webkit-align-self: center;\n      -ms-flex-item-align: center;\n          align-self: center;\n}\n.flatpickr-time .flatpickr-am-pm {\n  outline: 0;\n  width: 18%;\n  cursor: pointer;\n  text-align: center;\n  font-weight: 400;\n}\n.flatpickr-time input:hover,\n.flatpickr-time .flatpickr-am-pm:hover,\n.flatpickr-time input:focus,\n.flatpickr-time .flatpickr-am-pm:focus {\n  background: #f3f3f3;\n}\n.flatpickr-input[readonly] {\n  cursor: pointer;\n}\n@-webkit-keyframes fpFadeInDown {\n  from {\n    opacity: 0;\n    -webkit-transform: translate3d(0, -20px, 0);\n            transform: translate3d(0, -20px, 0);\n  }\n  to {\n    opacity: 1;\n    -webkit-transform: translate3d(0, 0, 0);\n            transform: translate3d(0, 0, 0);\n  }\n}\n@keyframes fpFadeInDown {\n  from {\n    opacity: 0;\n    -webkit-transform: translate3d(0, -20px, 0);\n            transform: translate3d(0, -20px, 0);\n  }\n  to {\n    opacity: 1;\n    -webkit-transform: translate3d(0, 0, 0);\n            transform: translate3d(0, 0, 0);\n  }\n}\n", ""]);

// exports


/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/

var stylesInDom = {};

var	memoize = function (fn) {
	var memo;

	return function () {
		if (typeof memo === "undefined") memo = fn.apply(this, arguments);
		return memo;
	};
};

var isOldIE = memoize(function () {
	// Test for IE <= 9 as proposed by Browserhacks
	// @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
	// Tests for existence of standard globals is to allow style-loader
	// to operate correctly into non-standard environments
	// @see https://github.com/webpack-contrib/style-loader/issues/177
	return window && document && document.all && !window.atob;
});

var getElement = (function (fn) {
	var memo = {};

	return function(selector) {
		if (typeof memo[selector] === "undefined") {
			memo[selector] = fn.call(this, selector);
		}

		return memo[selector]
	};
})(function (target) {
	return document.querySelector(target)
});

var singleton = null;
var	singletonCounter = 0;
var	stylesInsertedAtTop = [];

var	fixUrls = __webpack_require__(61);

module.exports = function(list, options) {
	if (typeof DEBUG !== "undefined" && DEBUG) {
		if (typeof document !== "object") throw new Error("The style-loader cannot be used in a non-browser environment");
	}

	options = options || {};

	options.attrs = typeof options.attrs === "object" ? options.attrs : {};

	// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
	// tags it will allow on a page
	if (!options.singleton) options.singleton = isOldIE();

	// By default, add <style> tags to the <head> element
	if (!options.insertInto) options.insertInto = "head";

	// By default, add <style> tags to the bottom of the target
	if (!options.insertAt) options.insertAt = "bottom";

	var styles = listToStyles(list, options);

	addStylesToDom(styles, options);

	return function update (newList) {
		var mayRemove = [];

		for (var i = 0; i < styles.length; i++) {
			var item = styles[i];
			var domStyle = stylesInDom[item.id];

			domStyle.refs--;
			mayRemove.push(domStyle);
		}

		if(newList) {
			var newStyles = listToStyles(newList, options);
			addStylesToDom(newStyles, options);
		}

		for (var i = 0; i < mayRemove.length; i++) {
			var domStyle = mayRemove[i];

			if(domStyle.refs === 0) {
				for (var j = 0; j < domStyle.parts.length; j++) domStyle.parts[j]();

				delete stylesInDom[domStyle.id];
			}
		}
	};
};

function addStylesToDom (styles, options) {
	for (var i = 0; i < styles.length; i++) {
		var item = styles[i];
		var domStyle = stylesInDom[item.id];

		if(domStyle) {
			domStyle.refs++;

			for(var j = 0; j < domStyle.parts.length; j++) {
				domStyle.parts[j](item.parts[j]);
			}

			for(; j < item.parts.length; j++) {
				domStyle.parts.push(addStyle(item.parts[j], options));
			}
		} else {
			var parts = [];

			for(var j = 0; j < item.parts.length; j++) {
				parts.push(addStyle(item.parts[j], options));
			}

			stylesInDom[item.id] = {id: item.id, refs: 1, parts: parts};
		}
	}
}

function listToStyles (list, options) {
	var styles = [];
	var newStyles = {};

	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		var id = options.base ? item[0] + options.base : item[0];
		var css = item[1];
		var media = item[2];
		var sourceMap = item[3];
		var part = {css: css, media: media, sourceMap: sourceMap};

		if(!newStyles[id]) styles.push(newStyles[id] = {id: id, parts: [part]});
		else newStyles[id].parts.push(part);
	}

	return styles;
}

function insertStyleElement (options, style) {
	var target = getElement(options.insertInto)

	if (!target) {
		throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
	}

	var lastStyleElementInsertedAtTop = stylesInsertedAtTop[stylesInsertedAtTop.length - 1];

	if (options.insertAt === "top") {
		if (!lastStyleElementInsertedAtTop) {
			target.insertBefore(style, target.firstChild);
		} else if (lastStyleElementInsertedAtTop.nextSibling) {
			target.insertBefore(style, lastStyleElementInsertedAtTop.nextSibling);
		} else {
			target.appendChild(style);
		}
		stylesInsertedAtTop.push(style);
	} else if (options.insertAt === "bottom") {
		target.appendChild(style);
	} else {
		throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");
	}
}

function removeStyleElement (style) {
	if (style.parentNode === null) return false;
	style.parentNode.removeChild(style);

	var idx = stylesInsertedAtTop.indexOf(style);
	if(idx >= 0) {
		stylesInsertedAtTop.splice(idx, 1);
	}
}

function createStyleElement (options) {
	var style = document.createElement("style");

	options.attrs.type = "text/css";

	addAttrs(style, options.attrs);
	insertStyleElement(options, style);

	return style;
}

function createLinkElement (options) {
	var link = document.createElement("link");

	options.attrs.type = "text/css";
	options.attrs.rel = "stylesheet";

	addAttrs(link, options.attrs);
	insertStyleElement(options, link);

	return link;
}

function addAttrs (el, attrs) {
	Object.keys(attrs).forEach(function (key) {
		el.setAttribute(key, attrs[key]);
	});
}

function addStyle (obj, options) {
	var style, update, remove, result;

	// If a transform function was defined, run it on the css
	if (options.transform && obj.css) {
	    result = options.transform(obj.css);

	    if (result) {
	    	// If transform returns a value, use that instead of the original css.
	    	// This allows running runtime transformations on the css.
	    	obj.css = result;
	    } else {
	    	// If the transform function returns a falsy value, don't add this css.
	    	// This allows conditional loading of css
	    	return function() {
	    		// noop
	    	};
	    }
	}

	if (options.singleton) {
		var styleIndex = singletonCounter++;

		style = singleton || (singleton = createStyleElement(options));

		update = applyToSingletonTag.bind(null, style, styleIndex, false);
		remove = applyToSingletonTag.bind(null, style, styleIndex, true);

	} else if (
		obj.sourceMap &&
		typeof URL === "function" &&
		typeof URL.createObjectURL === "function" &&
		typeof URL.revokeObjectURL === "function" &&
		typeof Blob === "function" &&
		typeof btoa === "function"
	) {
		style = createLinkElement(options);
		update = updateLink.bind(null, style, options);
		remove = function () {
			removeStyleElement(style);

			if(style.href) URL.revokeObjectURL(style.href);
		};
	} else {
		style = createStyleElement(options);
		update = applyToTag.bind(null, style);
		remove = function () {
			removeStyleElement(style);
		};
	}

	update(obj);

	return function updateStyle (newObj) {
		if (newObj) {
			if (
				newObj.css === obj.css &&
				newObj.media === obj.media &&
				newObj.sourceMap === obj.sourceMap
			) {
				return;
			}

			update(obj = newObj);
		} else {
			remove();
		}
	};
}

var replaceText = (function () {
	var textStore = [];

	return function (index, replacement) {
		textStore[index] = replacement;

		return textStore.filter(Boolean).join('\n');
	};
})();

function applyToSingletonTag (style, index, remove, obj) {
	var css = remove ? "" : obj.css;

	if (style.styleSheet) {
		style.styleSheet.cssText = replaceText(index, css);
	} else {
		var cssNode = document.createTextNode(css);
		var childNodes = style.childNodes;

		if (childNodes[index]) style.removeChild(childNodes[index]);

		if (childNodes.length) {
			style.insertBefore(cssNode, childNodes[index]);
		} else {
			style.appendChild(cssNode);
		}
	}
}

function applyToTag (style, obj) {
	var css = obj.css;
	var media = obj.media;

	if(media) {
		style.setAttribute("media", media)
	}

	if(style.styleSheet) {
		style.styleSheet.cssText = css;
	} else {
		while(style.firstChild) {
			style.removeChild(style.firstChild);
		}

		style.appendChild(document.createTextNode(css));
	}
}

function updateLink (link, options, obj) {
	var css = obj.css;
	var sourceMap = obj.sourceMap;

	/*
		If convertToAbsoluteUrls isn't defined, but sourcemaps are enabled
		and there is no publicPath defined then lets turn convertToAbsoluteUrls
		on by default.  Otherwise default to the convertToAbsoluteUrls option
		directly
	*/
	var autoFixUrls = options.convertToAbsoluteUrls === undefined && sourceMap;

	if (options.convertToAbsoluteUrls || autoFixUrls) {
		css = fixUrls(css);
	}

	if (sourceMap) {
		// http://stackoverflow.com/a/26603875
		css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
	}

	var blob = new Blob([css], { type: "text/css" });

	var oldSrc = link.href;

	link.href = URL.createObjectURL(blob);

	if(oldSrc) URL.revokeObjectURL(oldSrc);
}


/***/ }),
/* 61 */
/***/ (function(module, exports) {


/**
 * When source maps are enabled, `style-loader` uses a link element with a data-uri to
 * embed the css on the page. This breaks all relative urls because now they are relative to a
 * bundle instead of the current page.
 *
 * One solution is to only use full urls, but that may be impossible.
 *
 * Instead, this function "fixes" the relative urls to be absolute according to the current page location.
 *
 * A rudimentary test suite is located at `test/fixUrls.js` and can be run via the `npm test` command.
 *
 */

module.exports = function (css) {
  // get current location
  var location = typeof window !== "undefined" && window.location;

  if (!location) {
    throw new Error("fixUrls requires window.location");
  }

	// blank or null?
	if (!css || typeof css !== "string") {
	  return css;
  }

  var baseUrl = location.protocol + "//" + location.host;
  var currentDir = baseUrl + location.pathname.replace(/\/[^\/]*$/, "/");

	// convert each url(...)
	/*
	This regular expression is just a way to recursively match brackets within
	a string.

	 /url\s*\(  = Match on the word "url" with any whitespace after it and then a parens
	   (  = Start a capturing group
	     (?:  = Start a non-capturing group
	         [^)(]  = Match anything that isn't a parentheses
	         |  = OR
	         \(  = Match a start parentheses
	             (?:  = Start another non-capturing groups
	                 [^)(]+  = Match anything that isn't a parentheses
	                 |  = OR
	                 \(  = Match a start parentheses
	                     [^)(]*  = Match anything that isn't a parentheses
	                 \)  = Match a end parentheses
	             )  = End Group
              *\) = Match anything and then a close parens
          )  = Close non-capturing group
          *  = Match anything
       )  = Close capturing group
	 \)  = Match a close parens

	 /gi  = Get all matches, not the first.  Be case insensitive.
	 */
	var fixedCss = css.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function(fullMatch, origUrl) {
		// strip quotes (if they exist)
		var unquotedOrigUrl = origUrl
			.trim()
			.replace(/^"(.*)"$/, function(o, $1){ return $1; })
			.replace(/^'(.*)'$/, function(o, $1){ return $1; });

		// already a full url? no change
		if (/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(unquotedOrigUrl)) {
		  return fullMatch;
		}

		// convert the url to a full url
		var newUrl;

		if (unquotedOrigUrl.indexOf("//") === 0) {
		  	//TODO: should we add protocol?
			newUrl = unquotedOrigUrl;
		} else if (unquotedOrigUrl.indexOf("/") === 0) {
			// path should be relative to the base url
			newUrl = baseUrl + unquotedOrigUrl; // already starts with '/'
		} else {
			// path should be relative to current directory
			newUrl = currentDir + unquotedOrigUrl.replace(/^\.\//, ""); // Strip leading './'
		}

		// send back the fixed url(...)
		return "url(" + JSON.stringify(newUrl) + ")";
	});

	// send back the fixed css
	return fixedCss;
};


/***/ }),
/* 62 */
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
/* 63 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
=======
!function(e){var t={};function n(i){if(t[i])return t[i].exports;var a=t[i]={i:i,l:!1,exports:{}};return e[i].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:i})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=0)}({"+wdr":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{sample:"",image_file:"",file:null,newImage:""}},mounted:function(){this.sample="";var e=this.$el.getElementsByTagName("input")[0],t=this;e.onchange=function(){var n=new FileReader;n.readAsDataURL(e.files[0]),n.onload=function(e){this.img=document.getElementsByTagName("input")[0],this.img.src=e.target.result,t.newImage=this.img.src,t.changePreview()}}},methods:{removePreviewImage:function(){this.sample=""},changePreview:function(){this.sample=this.newImage}},computed:{getInputImage:function(){console.log(this.imageData)}}}},0:function(e,t,n){n("J66Q"),n("Oy72"),e.exports=n("MT9B")},"13ON":function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"tabs"},[n("ul",e._l(e.tabs,function(t){return n("li",{class:{active:t.isActive},on:{click:function(n){e.selectTab(t)}}},[n("a",[e._v(e._s(t.name))])])}),0)]),e._v(" "),n("div",{staticClass:"tabs-content"},[e._t("default")],2)])},staticRenderFns:[]}},"2JMG":function(e,t,n){var i=n("VU/8")(n("G3lE"),null,!1,null,null,null);e.exports=i.exports},"31/P":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};t.default={name:"tree-view",inheritAttrs:!1,props:{inputType:String,nameField:String,idField:String,captionField:String,childrenField:String,valueField:String,items:{type:[Array,String,Object],required:!1,default:null},value:{type:Array,required:!1,default:null},behavior:{type:String,required:!1,default:"reactive"},savedValues:{type:Array,required:!1,default:null}},created:function(){-1!==this.savedValues.indexOf(this.items[this.valueField])&&this.value.push(this.items)},computed:{caption:function(){return this.items[this.captionField]},allChildren:function(){var e=this,t=[];return function n(a){if(a[e.childrenField]&&e.getLength(a[e.childrenField])>0)if("object"==i(a[e.childrenField]))for(var r in a[e.childrenField])n(a[e.childrenField][r]);else a[e.childrenField].forEach(function(e){return n(e)});else t.push(a)}(this.items),t},hasChildren:function(){return!!this.items[this.childrenField]&&this.getLength(this.items[this.childrenField])>0},hasSelection:function(){return!!this.value&&this.value.length>0},isAllChildrenSelected:function(){var e=this;return this.hasChildren&&this.hasSelection&&this.allChildren.every(function(t){return e.value.some(function(n){return n[e.idField]===t[e.idField]})})},isSomeChildrenSelected:function(){var e=this;return this.hasChildren&&this.hasSelection&&this.allChildren.some(function(t){return e.value.some(function(n){return n[e.idField]===t[e.idField]})})}},methods:{getLength:function(e){if("object"==(void 0===e?"undefined":i(e))){var t=0;for(var n in e)t++;return t}return e.length},generateRoot:function(){var e=this;return"checkbox"==this.inputType?"reactive"==this.behavior?this.$createElement("tree-checkbox",{props:{id:this.items[this.idField],label:this.caption,nameField:this.nameField,modelValue:this.items[this.valueField],inputValue:this.hasChildren?this.isSomeChildrenSelected:this.value,value:this.hasChildren?this.isAllChildrenSelected:this.items},on:{change:function(t){e.hasChildren?(e.isAllChildrenSelected?e.allChildren.forEach(function(t){var n=e.value.indexOf(t);e.value.splice(n,1)}):e.allChildren.forEach(function(t){var n=!1;e.value.forEach(function(e){e.key==t.key&&(n=!0)}),n||e.value.push(t)}),e.$emit("input",e.value)):e.$emit("input",t)}}}):this.$createElement("tree-checkbox",{props:{id:this.items[this.idField],label:this.caption,nameField:this.nameField,modelValue:this.items[this.valueField],inputValue:this.value,value:this.items}}):"radio"==this.inputType?this.$createElement("tree-radio",{props:{id:this.items[this.idField],label:this.caption,nameField:this.nameField,modelValue:this.items[this.valueField],value:this.savedValues}}):void 0},generateChild:function(e){var t=this;return this.$createElement("tree-item",{on:{input:function(e){t.$emit("input",e)}},props:{items:e,value:this.value,savedValues:this.savedValues,nameField:this.nameField,inputType:this.inputType,captionField:this.captionField,childrenField:this.childrenField,valueField:this.valueField,idField:this.idField,behavior:this.behavior}})},generateChildren:function(){var e=this,t=[];if(this.items[this.childrenField])if("object"==i(this.items[this.childrenField]))for(var n in this.items[this.childrenField])t.push(this.generateChild(this.items[this.childrenField][n]));else this.items[this.childrenField].forEach(function(n){t.push(e.generateChild(n))});return t},generateIcon:function(){var e=this;return this.$createElement("i",{class:["expand-icon"],on:{click:function(t){e.$el.classList.toggle("active")}}})},generateFolderIcon:function(){return this.$createElement("i",{class:["icon","folder-icon"]})}},render:function(e){return e("div",{class:["tree-item","active",this.hasChildren?"has-children":""]},[this.generateIcon(),this.generateFolderIcon(),this.generateRoot()].concat(function(e){if(Array.isArray(e)){for(var t=0,n=Array(e.length);t<e.length;t++)n[t]=e[t];return n}return Array.from(e)}(this.generateChildren())))}}},"3AR7":function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("label",{staticClass:"image-item",class:{"has-image":e.imageData.length>0},attrs:{for:e._uid}},[n("input",{attrs:{type:"hidden",name:e.finalInputName}}),e._v(" "),n("input",{directives:[{name:"validate",rawName:"v-validate",value:"mimes:image/*",expression:"'mimes:image/*'"}],ref:"imageInput",attrs:{type:"file",accept:"image/*",name:e.finalInputName,id:e._uid},on:{change:function(t){e.addImageView(t)}}}),e._v(" "),e.imageData.length>0?n("img",{staticClass:"preview",attrs:{src:e.imageData}}):e._e(),e._v(" "),n("label",{staticClass:"remove-image",on:{click:function(t){e.removeImage()}}},[e._v(e._s(e.removeButtonLabel))])])},staticRenderFns:[]}},"44Sb":function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("span",{staticClass:"radio"},[n("input",{attrs:{type:"radio",id:e.id,name:e.nameField},domProps:{value:e.modelValue,checked:e.isActive}}),e._v(" "),n("label",{staticClass:"radio-view",attrs:{for:e.id}}),e._v(" "),n("span",{attrs:{for:e.id}},[e._v(e._s(e.label))])])},staticRenderFns:[]}},"6wXy":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={props:{title:String,id:String,className:String,active:Boolean},inject:["$validator"],data:function(){return{isActive:!1,imageData:""}},mounted:function(){this.isActive=this.active},methods:{toggleAccordion:function(){this.isActive=!this.isActive}},computed:{iconClass:function(){return{"accordian-down-icon":!this.isActive,"accordian-up-icon":this.isActive}}}}},"7aQn":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={name:"tree-view",inheritAttrs:!1,props:{inputType:{type:String,required:!1,default:"checkbox"},nameField:{type:String,required:!1,default:"permissions"},idField:{type:String,required:!1,default:"id"},valueField:{type:String,required:!1,default:"value"},captionField:{type:String,required:!1,default:"name"},childrenField:{type:String,required:!1,default:"children"},items:{type:[Array,String,Object],required:!1,default:function(){return[]}},behavior:{type:String,required:!1,default:"reactive"},value:{type:[Array,String,Object],required:!1,default:function(){return[]}}},data:function(){return{finalValues:[]}},computed:{savedValues:function(){return this.value?"radio"==this.inputType?[this.value]:"string"==typeof this.value?JSON.parse(this.value):this.value:[]}},methods:{generateChildren:function(){var e=[],t="string"==typeof this.items?JSON.parse(this.items):this.items;for(var n in t)e.push(this.generateTreeItem(t[n]));return e},generateTreeItem:function(e){var t=this;return this.$createElement("tree-item",{props:{items:e,value:this.finalValues,savedValues:this.savedValues,nameField:this.nameField,inputType:this.inputType,captionField:this.captionField,childrenField:this.childrenField,valueField:this.valueField,idField:this.idField,behavior:this.behavior},on:{input:function(e){t.finalValues=e}}})}},render:function(e){return e("div",{class:["tree-container"]},[this.generateChildren()])}}},"7z1m":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{uid:1,flashes:[]}},methods:{addFlash:function(e){e.uid=this.uid++,this.flashes.push(e)},removeFlash:function(e){var t=this.flashes.indexOf(e);this.flashes.splice(t,1)}}}},"8skS":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={props:{inputName:{type:String,required:!1,default:"attachments"},removeButtonLabel:{type:String},image:{type:Object,required:!1,default:null}},data:function(){return{imageData:""}},mounted:function(){this.image.id&&this.image.url&&(this.imageData=this.image.url)},computed:{finalInputName:function(){return this.inputName+"["+this.image.id+"]"}},methods:{addImageView:function(){var e=this,t=this.$refs.imageInput;if(t.files&&t.files[0])if(t.files[0].type.includes("image/")){var n=new FileReader;n.onload=function(t){e.imageData=t.target.result},n.readAsDataURL(t.files[0])}else t.value="",alert("Only images (.jpeg, .jpg, .png, ..) are allowed.")},removeImage:function(){this.$emit("onRemoveImage",this.image)}}}},"9yns":function(e,t,n){var i=n("VU/8")(n("EsN/"),n("wq5e"),!1,null,null,null);e.exports=i.exports},AOOz:function(e,t){e.exports={render:function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"alert",class:this.flash.type},[t("span",{staticClass:"icon white-cross-sm-icon",on:{click:this.remove}}),this._v(" "),t("p",[this._v(this._s(this.flash.message))])])},staticRenderFns:[]}},C2Px:function(e,t,n){var i=n("VU/8")(n("G8Iw"),n("rsIj"),!1,null,null,null);e.exports=i.exports},EZgW:function(e,t,n){var i=n("VU/8")(n("+wdr"),n("fXJ7"),!1,function(e){n("KJcg")},null,null);e.exports=i.exports},"EsN/":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n("GxBP"),a=n.n(i);t.default={props:{name:String,value:String},data:function(){return{datepicker:null}},mounted:function(){var e=this,t=this.$el.getElementsByTagName("input")[0];this.datepicker=new a.a(t,{allowInput:!0,altFormat:"Y-m-d H:i:s",dateFormat:"Y-m-d H:i:s",enableTime:!0,onChange:function(t,n,i){e.$emit("onChange",n)}})}}},"FZ+f":function(e,t){e.exports=function(e){var t=[];return t.toString=function(){return this.map(function(t){var n=function(e,t){var n=e[1]||"",i=e[3];if(!i)return n;if(t&&"function"==typeof btoa){var a=(o=i,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(o))))+" */"),r=i.sources.map(function(e){return"/*# sourceURL="+i.sourceRoot+e+" */"});return[n].concat(r).concat([a]).join("\n")}var o;return[n].join("\n")}(t,e);return t[2]?"@media "+t[2]+"{"+n+"}":n}).join("")},t.i=function(e,n){"string"==typeof e&&(e=[[null,e,""]]);for(var i={},a=0;a<this.length;a++){var r=this[a][0];"number"==typeof r&&(i[r]=!0)}for(a=0;a<e.length;a++){var o=e[a];"number"==typeof o[0]&&i[o[0]]||(n&&!o[2]?o[2]=n:n&&(o[2]="("+o[2]+") and ("+n+")"),t.push(o))}},t}},G3lE:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={bind:function(e,t,n){e.addEventListener("input",function(e){setTimeout(function(){e.target.value=e.target.value.toString().toLowerCase().replace(/[^\w- ]+/g,"").trim().replace(/ +/g,"-")},100)})}}},G8Iw:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={name:"tree-checkbox",props:["id","label","nameField","modelValue","inputValue","value"],computed:{isMultiple:function(){return Array.isArray(this.internalValue)},isActive:function(){var e=this,t=this.value,n=this.internalValue;return this.isMultiple?n.some(function(n){return e.valueComparator(n,t)}):t?this.valueComparator(t,n):Boolean(n)},internalValue:{get:function(){return this.lazyValue},set:function(e){this.lazyValue=e,this.$emit("input",e)}}},data:function(e){return{lazyValue:e.inputValue}},watch:{inputValue:function(e){this.internalValue=e}},methods:{inputChanged:function(){var e=this,t=this.value,n=this.internalValue;if(this.isMultiple){var i=n.length;(n=n.filter(function(n){return!e.valueComparator(n,t)})).length===i&&n.push(t)}else n=!n;this.$emit("change",n)},valueComparator:function(e,t){var n=this;if(e===t)return!0;if(e!==Object(e)||t!==Object(t))return!1;var i=Object.keys(e);return i.length===Object.keys(t).length&&i.every(function(i){return n.valueComparator(e[i],t[i])})}}}},GxBP:function(e,t,n){var i;i=function(){"use strict";var e=function(e){return("0"+e).slice(-2)},t=function(e){return!0===e?1:0};function n(e,t,n){var i;return void 0===n&&(n=!1),function(){var a=this,r=arguments;null!==i&&clearTimeout(i),i=window.setTimeout(function(){i=null,n||e.apply(a,r)},t),n&&!i&&e.apply(a,r)}}var i=function(e){return e instanceof Array?e:[e]},a=function(){},r=function(e,t,n){return n.months[t?"shorthand":"longhand"][e]},o={D:a,F:function(e,t,n){e.setMonth(n.months.longhand.indexOf(t))},G:function(e,t){e.setHours(parseFloat(t))},H:function(e,t){e.setHours(parseFloat(t))},J:function(e,t){e.setDate(parseFloat(t))},K:function(e,n,i){e.setHours(e.getHours()%12+12*t(new RegExp(i.amPM[1],"i").test(n)))},M:function(e,t,n){e.setMonth(n.months.shorthand.indexOf(t))},S:function(e,t){e.setSeconds(parseFloat(t))},U:function(e,t){return new Date(1e3*parseFloat(t))},W:function(e,t){var n=parseInt(t);return new Date(e.getFullYear(),0,2+7*(n-1),0,0,0,0)},Y:function(e,t){e.setFullYear(parseFloat(t))},Z:function(e,t){return new Date(t)},d:function(e,t){e.setDate(parseFloat(t))},h:function(e,t){e.setHours(parseFloat(t))},i:function(e,t){e.setMinutes(parseFloat(t))},j:function(e,t){e.setDate(parseFloat(t))},l:a,m:function(e,t){e.setMonth(parseFloat(t)-1)},n:function(e,t){e.setMonth(parseFloat(t)-1)},s:function(e,t){e.setSeconds(parseFloat(t))},w:a,y:function(e,t){e.setFullYear(2e3+parseFloat(t))}},l={D:"(\\w+)",F:"(\\w+)",G:"(\\d\\d|\\d)",H:"(\\d\\d|\\d)",J:"(\\d\\d|\\d)\\w+",K:"",M:"(\\w+)",S:"(\\d\\d|\\d)",U:"(.+)",W:"(\\d\\d|\\d)",Y:"(\\d{4})",Z:"(.+)",d:"(\\d\\d|\\d)",h:"(\\d\\d|\\d)",i:"(\\d\\d|\\d)",j:"(\\d\\d|\\d)",l:"(\\w+)",m:"(\\d\\d|\\d)",n:"(\\d\\d|\\d)",s:"(\\d\\d|\\d)",w:"(\\d\\d|\\d)",y:"(\\d{2})"},s={Z:function(e){return e.toISOString()},D:function(e,t,n){return t.weekdays.shorthand[s.w(e,t,n)]},F:function(e,t,n){return r(s.n(e,t,n)-1,!1,t)},G:function(t,n,i){return e(s.h(t,n,i))},H:function(t){return e(t.getHours())},J:function(e,t){return void 0!==t.ordinal?e.getDate()+t.ordinal(e.getDate()):e.getDate()},K:function(e,n){return n.amPM[t(e.getHours()>11)]},M:function(e,t){return r(e.getMonth(),!0,t)},S:function(t){return e(t.getSeconds())},U:function(e){return e.getTime()/1e3},W:function(e,t,n){return n.getWeek(e)},Y:function(e){return e.getFullYear()},d:function(t){return e(t.getDate())},h:function(e){return e.getHours()%12?e.getHours()%12:12},i:function(t){return e(t.getMinutes())},j:function(e){return e.getDate()},l:function(e,t){return t.weekdays.longhand[e.getDay()]},m:function(t){return e(t.getMonth()+1)},n:function(e){return e.getMonth()+1},s:function(e){return e.getSeconds()},w:function(e){return e.getDay()},y:function(e){return String(e.getFullYear()).substring(2)}},c={weekdays:{shorthand:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],longhand:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},months:{shorthand:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],longhand:["January","February","March","April","May","June","July","August","September","October","November","December"]},daysInMonth:[31,28,31,30,31,30,31,31,30,31,30,31],firstDayOfWeek:0,ordinal:function(e){var t=e%100;if(t>3&&t<21)return"th";switch(t%10){case 1:return"st";case 2:return"nd";case 3:return"rd";default:return"th"}},rangeSeparator:" to ",weekAbbreviation:"Wk",scrollTitle:"Scroll to increment",toggleTitle:"Click to toggle",amPM:["AM","PM"],yearAriaLabel:"Year"},d=function(e){var t=e.config,n=void 0===t?g:t,i=e.l10n,a=void 0===i?c:i;return function(e,t,i){var r=i||a;return void 0!==n.formatDate?n.formatDate(e,t,r):t.split("").map(function(t,i,a){return s[t]&&"\\"!==a[i-1]?s[t](e,r,n):"\\"!==t?t:""}).join("")}},u=function(e){var t=e.config,n=void 0===t?g:t,i=e.l10n,a=void 0===i?c:i;return function(e,t,i,r){if(0===e||e){var s,c=r||a,d=e;if(e instanceof Date)s=new Date(e.getTime());else if("string"!=typeof e&&void 0!==e.toFixed)s=new Date(e);else if("string"==typeof e){var u=t||(n||g).dateFormat,f=String(e).trim();if("today"===f)s=new Date,i=!0;else if(/Z$/.test(f)||/GMT$/.test(f))s=new Date(e);else if(n&&n.parseDate)s=n.parseDate(e,u);else{s=n&&n.noCalendar?new Date((new Date).setHours(0,0,0,0)):new Date((new Date).getFullYear(),0,1,0,0,0,0);for(var p,h=[],m=0,v=0,b="";m<u.length;m++){var y=u[m],w="\\"===y,x="\\"===u[m-1]||w;if(l[y]&&!x){b+=l[y];var k=new RegExp(b).exec(e);k&&(p=!0)&&h["Y"!==y?"push":"unshift"]({fn:o[y],val:k[++v]})}else w||(b+=".");h.forEach(function(e){var t=e.fn,n=e.val;return s=t(s,n,c)||s})}s=p?s:void 0}}if(s instanceof Date&&!isNaN(s.getTime()))return!0===i&&s.setHours(0,0,0,0),s;n.errorHandler(new Error("Invalid date provided: "+d))}}};function f(e,t,n){return void 0===n&&(n=!0),!1!==n?new Date(e.getTime()).setHours(0,0,0,0)-new Date(t.getTime()).setHours(0,0,0,0):e.getTime()-t.getTime()}var p=function(e,t,n){return e>Math.min(t,n)&&e<Math.max(t,n)},h={DAY:864e5},m=["onChange","onClose","onDayCreate","onDestroy","onKeyDown","onMonthChange","onOpen","onParseConfig","onReady","onValueUpdate","onYearChange","onPreCalendarPosition"],g={_disable:[],_enable:[],allowInput:!1,altFormat:"F j, Y",altInput:!1,altInputClass:"form-control input",animate:"object"==typeof window&&-1===window.navigator.userAgent.indexOf("MSIE"),ariaDateFormat:"F j, Y",clickOpens:!0,closeOnSelect:!0,conjunction:", ",dateFormat:"Y-m-d",defaultHour:12,defaultMinute:0,defaultSeconds:0,disable:[],disableMobile:!1,enable:[],enableSeconds:!1,enableTime:!1,errorHandler:function(e){return"undefined"!=typeof console&&console.warn(e)},getWeek:function(e){var t=new Date(e.getTime());t.setHours(0,0,0,0),t.setDate(t.getDate()+3-(t.getDay()+6)%7);var n=new Date(t.getFullYear(),0,4);return 1+Math.round(((t.getTime()-n.getTime())/864e5-3+(n.getDay()+6)%7)/7)},hourIncrement:1,ignoredFocusElements:[],inline:!1,locale:"default",minuteIncrement:5,mode:"single",nextArrow:"<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z' /></svg>",noCalendar:!1,now:new Date,onChange:[],onClose:[],onDayCreate:[],onDestroy:[],onKeyDown:[],onMonthChange:[],onOpen:[],onParseConfig:[],onReady:[],onValueUpdate:[],onYearChange:[],onPreCalendarPosition:[],plugins:[],position:"auto",positionElement:void 0,prevArrow:"<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z' /></svg>",shorthandCurrentMonth:!1,showMonths:1,static:!1,time_24hr:!1,weekNumbers:!1,wrap:!1};function v(e,t,n){if(!0===n)return e.classList.add(t);e.classList.remove(t)}function b(e,t,n){var i=window.document.createElement(e);return t=t||"",n=n||"",i.className=t,void 0!==n&&(i.textContent=n),i}function y(e){for(;e.firstChild;)e.removeChild(e.firstChild)}function w(e,t){var n=b("div","numInputWrapper"),i=b("input","numInput "+e),a=b("span","arrowUp"),r=b("span","arrowDown");if(i.type="text",i.pattern="\\d*",void 0!==t)for(var o in t)i.setAttribute(o,t[o]);return n.appendChild(i),n.appendChild(a),n.appendChild(r),n}"function"!=typeof Object.assign&&(Object.assign=function(e){if(!e)throw TypeError("Cannot convert undefined or null to object");for(var t=arguments.length,n=new Array(t>1?t-1:0),i=1;i<t;i++)n[i-1]=arguments[i];for(var a=function(){var t=n[r];t&&Object.keys(t).forEach(function(n){return e[n]=t[n]})},r=0;r<n.length;r++)a();return e});var x=300;function k(a,o){var s={config:Object.assign({},D.defaultConfig),l10n:c};function g(e){return e.bind(s)}function k(){var e=s.config;!1===e.weekNumbers&&1===e.showMonths||!0!==e.noCalendar&&window.requestAnimationFrame(function(){if(s.calendarContainer.style.visibility="hidden",s.calendarContainer.style.display="block",void 0!==s.daysContainer){var t=(s.days.offsetWidth+1)*e.showMonths;s.daysContainer.style.width=t+"px",s.calendarContainer.style.width=t+(void 0!==s.weekWrapper?s.weekWrapper.offsetWidth:0)+"px",s.calendarContainer.style.removeProperty("visibility"),s.calendarContainer.style.removeProperty("display")}})}function C(n){if(0!==s.selectedDates.length){void 0!==n&&"blur"!==n.type&&function(n){n.preventDefault();var i="keydown"===n.type,a=n.target;void 0!==s.amPM&&n.target===s.amPM&&(s.amPM.textContent=s.l10n.amPM[t(s.amPM.textContent===s.l10n.amPM[0])]);var r=parseFloat(a.getAttribute("data-min")),o=parseFloat(a.getAttribute("data-max")),l=parseFloat(a.getAttribute("data-step")),c=parseInt(a.value,10),d=n.delta||(i?38===n.which?1:-1:0),u=c+l*d;if(void 0!==a.value&&2===a.value.length){var f=a===s.hourElement,p=a===s.minuteElement;u<r?(u=o+u+t(!f)+(t(f)&&t(!s.amPM)),p&&N(void 0,-1,s.hourElement)):u>o&&(u=a===s.hourElement?u-o-t(!s.amPM):r,p&&N(void 0,1,s.hourElement)),s.amPM&&f&&(1===l?u+c===23:Math.abs(u-c)>l)&&(s.amPM.textContent=s.l10n.amPM[t(s.amPM.textContent===s.l10n.amPM[0])]),a.value=e(u)}}(n);var i=s._input.value;M(),me(),s._input.value!==i&&s._debouncedChange()}}function M(){if(void 0!==s.hourElement&&void 0!==s.minuteElement){var e,n,i=(parseInt(s.hourElement.value.slice(-2),10)||0)%24,a=(parseInt(s.minuteElement.value,10)||0)%60,r=void 0!==s.secondElement?(parseInt(s.secondElement.value,10)||0)%60:0;void 0!==s.amPM&&(e=i,n=s.amPM.textContent,i=e%12+12*t(n===s.l10n.amPM[1]));var o=void 0!==s.config.minTime||s.config.minDate&&s.minDateHasTime&&s.latestSelectedDateObj&&0===f(s.latestSelectedDateObj,s.config.minDate,!0);if(void 0!==s.config.maxTime||s.config.maxDate&&s.maxDateHasTime&&s.latestSelectedDateObj&&0===f(s.latestSelectedDateObj,s.config.maxDate,!0)){var l=void 0!==s.config.maxTime?s.config.maxTime:s.config.maxDate;(i=Math.min(i,l.getHours()))===l.getHours()&&(a=Math.min(a,l.getMinutes())),a===l.getMinutes()&&(r=Math.min(r,l.getSeconds()))}if(o){var c=void 0!==s.config.minTime?s.config.minTime:s.config.minDate;(i=Math.max(i,c.getHours()))===c.getHours()&&(a=Math.max(a,c.getMinutes())),a===c.getMinutes()&&(r=Math.max(r,c.getSeconds()))}T(i,a,r)}}function _(e){var t=e||s.latestSelectedDateObj;t&&T(t.getHours(),t.getMinutes(),t.getSeconds())}function E(){var e=s.config.defaultHour,t=s.config.defaultMinute,n=s.config.defaultSeconds;if(void 0!==s.config.minDate){var i=s.config.minDate.getHours(),a=s.config.minDate.getMinutes();(e=Math.max(e,i))===i&&(t=Math.max(a,t)),e===i&&t===a&&(n=s.config.minDate.getSeconds())}if(void 0!==s.config.maxDate){var r=s.config.maxDate.getHours(),o=s.config.maxDate.getMinutes();(e=Math.min(e,r))===r&&(t=Math.min(o,t)),e===r&&t===o&&(n=s.config.maxDate.getSeconds())}T(e,t,n)}function T(n,i,a){void 0!==s.latestSelectedDateObj&&s.latestSelectedDateObj.setHours(n%24,i,a||0,0),s.hourElement&&s.minuteElement&&!s.isMobile&&(s.hourElement.value=e(s.config.time_24hr?n:(12+n)%12+12*t(n%12==0)),s.minuteElement.value=e(i),void 0!==s.amPM&&(s.amPM.textContent=s.l10n.amPM[t(n>=12)]),void 0!==s.secondElement&&(s.secondElement.value=e(a)))}function I(e){var t=parseInt(e.target.value)+(e.delta||0);(t/1e3>1||"Enter"===e.key&&!/[^\d]/.test(t.toString()))&&Z(t)}function F(e,t,n,i){return t instanceof Array?t.forEach(function(t){return F(e,t,n,i)}):e instanceof Array?e.forEach(function(e){return F(e,t,n,i)}):(e.addEventListener(t,n,i),void s._handlers.push({element:e,event:t,handler:n,options:i}))}function S(e){return function(t){1===t.which&&e(t)}}function O(){de("onChange")}function A(e){var t=void 0!==e?s.parseDate(e):s.latestSelectedDateObj||(s.config.minDate&&s.config.minDate>s.now?s.config.minDate:s.config.maxDate&&s.config.maxDate<s.now?s.config.maxDate:s.now);try{void 0!==t&&(s.currentYear=t.getFullYear(),s.currentMonth=t.getMonth())}catch(e){e.message="Invalid date supplied: "+t,s.config.errorHandler(e)}s.redraw()}function j(e){~e.target.className.indexOf("arrow")&&N(e,e.target.classList.contains("arrowUp")?1:-1)}function N(e,t,n){var i=e&&e.target,a=n||i&&i.parentNode&&i.parentNode.firstChild,r=ue("increment");r.delta=t,a&&a.dispatchEvent(r)}function R(e,t,n,i){var a=K(t,!0),r=b("span","flatpickr-day "+e,t.getDate().toString());return r.dateObj=t,r.$i=i,r.setAttribute("aria-label",s.formatDate(t,s.config.ariaDateFormat)),-1===e.indexOf("hidden")&&0===f(t,s.now)&&(s.todayDateElem=r,r.classList.add("today"),r.setAttribute("aria-current","date")),a?(r.tabIndex=-1,fe(t)&&(r.classList.add("selected"),s.selectedDateElem=r,"range"===s.config.mode&&(v(r,"startRange",s.selectedDates[0]&&0===f(t,s.selectedDates[0],!0)),v(r,"endRange",s.selectedDates[1]&&0===f(t,s.selectedDates[1],!0)),"nextMonthDay"===e&&r.classList.add("inRange")))):r.classList.add("disabled"),"range"===s.config.mode&&function(e){return!("range"!==s.config.mode||s.selectedDates.length<2)&&f(e,s.selectedDates[0])>=0&&f(e,s.selectedDates[1])<=0}(t)&&!fe(t)&&r.classList.add("inRange"),s.weekNumbers&&1===s.config.showMonths&&"prevMonthDay"!==e&&n%7==1&&s.weekNumbers.insertAdjacentHTML("beforeend","<span class='flatpickr-day'>"+s.config.getWeek(t)+"</span>"),de("onDayCreate",r),r}function P(e){e.focus(),"range"===s.config.mode&&Q(e)}function L(e){for(var t=e>0?0:s.config.showMonths-1,n=e>0?s.config.showMonths:-1,i=t;i!=n;i+=e)for(var a=s.daysContainer.children[i],r=e>0?0:a.children.length-1,o=e>0?a.children.length:-1,l=r;l!=o;l+=e){var c=a.children[l];if(-1===c.className.indexOf("hidden")&&K(c.dateObj))return c}}function Y(e,t){var n=G(document.activeElement||document.body),i=void 0!==e?e:n?document.activeElement:void 0!==s.selectedDateElem&&G(s.selectedDateElem)?s.selectedDateElem:void 0!==s.todayDateElem&&G(s.todayDateElem)?s.todayDateElem:L(t>0?1:-1);return void 0===i?s._input.focus():n?void function(e,t){for(var n=-1===e.className.indexOf("Month")?e.dateObj.getMonth():s.currentMonth,i=t>0?s.config.showMonths:-1,a=t>0?1:-1,r=n-s.currentMonth;r!=i;r+=a)for(var o=s.daysContainer.children[r],l=n-s.currentMonth===r?e.$i+t:t<0?o.children.length-1:0,c=o.children.length,d=l;d>=0&&d<c&&d!=(t>0?c:-1);d+=a){var u=o.children[d];if(-1===u.className.indexOf("hidden")&&K(u.dateObj)&&Math.abs(e.$i-d)>=Math.abs(t))return P(u)}s.changeMonth(a),Y(L(a),0)}(i,t):P(i)}function V(e,t){for(var n=(new Date(e,t,1).getDay()-s.l10n.firstDayOfWeek+7)%7,i=s.utils.getDaysInMonth((t-1+12)%12),a=s.utils.getDaysInMonth(t),r=window.document.createDocumentFragment(),o=s.config.showMonths>1,l=o?"prevMonthDay hidden":"prevMonthDay",c=o?"nextMonthDay hidden":"nextMonthDay",d=i+1-n,u=0;d<=i;d++,u++)r.appendChild(R(l,new Date(e,t-1,d),d,u));for(d=1;d<=a;d++,u++)r.appendChild(R("",new Date(e,t,d),d,u));for(var f=a+1;f<=42-n&&(1===s.config.showMonths||u%7!=0);f++,u++)r.appendChild(R(c,new Date(e,t+1,f%a),f,u));var p=b("div","dayContainer");return p.appendChild(r),p}function H(){if(void 0!==s.daysContainer){y(s.daysContainer),s.weekNumbers&&y(s.weekNumbers);for(var e=document.createDocumentFragment(),t=0;t<s.config.showMonths;t++){var n=new Date(s.currentYear,s.currentMonth,1);n.setMonth(s.currentMonth+t),e.appendChild(V(n.getFullYear(),n.getMonth()))}s.daysContainer.appendChild(e),s.days=s.daysContainer.firstChild,"range"===s.config.mode&&1===s.selectedDates.length&&Q()}}function $(){var e=b("div","flatpickr-month"),t=window.document.createDocumentFragment(),n=b("span","cur-month"),i=w("cur-year",{tabindex:"-1"}),a=i.getElementsByTagName("input")[0];a.setAttribute("aria-label",s.l10n.yearAriaLabel),s.config.minDate&&a.setAttribute("data-min",s.config.minDate.getFullYear().toString()),s.config.maxDate&&(a.setAttribute("data-max",s.config.maxDate.getFullYear().toString()),a.disabled=!!s.config.minDate&&s.config.minDate.getFullYear()===s.config.maxDate.getFullYear());var r=b("div","flatpickr-current-month");return r.appendChild(n),r.appendChild(i),t.appendChild(r),e.appendChild(t),{container:e,yearElement:a,monthElement:n}}function U(){y(s.monthNav),s.monthNav.appendChild(s.prevMonthNav);for(var e=s.config.showMonths;e--;){var t=$();s.yearElements.push(t.yearElement),s.monthElements.push(t.monthElement),s.monthNav.appendChild(t.container)}s.monthNav.appendChild(s.nextMonthNav)}function W(){s.weekdayContainer?y(s.weekdayContainer):s.weekdayContainer=b("div","flatpickr-weekdays");for(var e=s.config.showMonths;e--;){var t=b("div","flatpickr-weekdaycontainer");s.weekdayContainer.appendChild(t)}return B(),s.weekdayContainer}function B(){var e=s.l10n.firstDayOfWeek,t=s.l10n.weekdays.shorthand.concat();e>0&&e<t.length&&(t=t.splice(e,t.length).concat(t.splice(0,e)));for(var n=s.config.showMonths;n--;)s.weekdayContainer.children[n].innerHTML="\n      <span class=flatpickr-weekday>\n        "+t.join("</span><span class=flatpickr-weekday>")+"\n      </span>\n      "}function q(e,t){void 0===t&&(t=!0);var n=t?e:e-s.currentMonth;n<0&&!0===s._hidePrevMonthArrow||n>0&&!0===s._hideNextMonthArrow||(s.currentMonth+=n,(s.currentMonth<0||s.currentMonth>11)&&(s.currentYear+=s.currentMonth>11?1:-1,s.currentMonth=(s.currentMonth+12)%12,de("onYearChange")),H(),de("onMonthChange"),pe())}function z(e){return!(!s.config.appendTo||!s.config.appendTo.contains(e))||s.calendarContainer.contains(e)}function J(e){if(s.isOpen&&!s.config.inline){var t=z(e.target),n=e.target===s.input||e.target===s.altInput||s.element.contains(e.target)||e.path&&e.path.indexOf&&(~e.path.indexOf(s.input)||~e.path.indexOf(s.altInput)),i="blur"===e.type?n&&e.relatedTarget&&!z(e.relatedTarget):!n&&!t,a=!s.config.ignoredFocusElements.some(function(t){return t.contains(e.target)});i&&a&&(s.close(),"range"===s.config.mode&&1===s.selectedDates.length&&(s.clear(!1),s.redraw()))}}function Z(e){if(!(!e||s.config.minDate&&e<s.config.minDate.getFullYear()||s.config.maxDate&&e>s.config.maxDate.getFullYear())){var t=e,n=s.currentYear!==t;s.currentYear=t||s.currentYear,s.config.maxDate&&s.currentYear===s.config.maxDate.getFullYear()?s.currentMonth=Math.min(s.config.maxDate.getMonth(),s.currentMonth):s.config.minDate&&s.currentYear===s.config.minDate.getFullYear()&&(s.currentMonth=Math.max(s.config.minDate.getMonth(),s.currentMonth)),n&&(s.redraw(),de("onYearChange"))}}function K(e,t){void 0===t&&(t=!0);var n=s.parseDate(e,void 0,t);if(s.config.minDate&&n&&f(n,s.config.minDate,void 0!==t?t:!s.minDateHasTime)<0||s.config.maxDate&&n&&f(n,s.config.maxDate,void 0!==t?t:!s.maxDateHasTime)>0)return!1;if(0===s.config.enable.length&&0===s.config.disable.length)return!0;if(void 0===n)return!1;for(var i,a=s.config.enable.length>0,r=a?s.config.enable:s.config.disable,o=0;o<r.length;o++){if("function"==typeof(i=r[o])&&i(n))return a;if(i instanceof Date&&void 0!==n&&i.getTime()===n.getTime())return a;if("string"==typeof i&&void 0!==n){var l=s.parseDate(i,void 0,!0);return l&&l.getTime()===n.getTime()?a:!a}if("object"==typeof i&&void 0!==n&&i.from&&i.to&&n.getTime()>=i.from.getTime()&&n.getTime()<=i.to.getTime())return a}return!a}function G(e){return void 0!==s.daysContainer&&(-1===e.className.indexOf("hidden")&&s.daysContainer.contains(e))}function X(e){var t=e.target===s._input,n=s.config.allowInput,i=s.isOpen&&(!n||!t),a=s.config.inline&&t&&!n;if(13===e.keyCode&&t){if(n)return s.setDate(s._input.value,!0,e.target===s.altInput?s.config.altFormat:s.config.dateFormat),e.target.blur();s.open()}else if(z(e.target)||i||a){var r=!!s.timeContainer&&s.timeContainer.contains(e.target);switch(e.keyCode){case 13:r?C():oe(e);break;case 27:e.preventDefault(),re();break;case 8:case 46:t&&!s.config.allowInput&&(e.preventDefault(),s.clear());break;case 37:case 39:if(r)s.hourElement&&s.hourElement.focus();else if(e.preventDefault(),void 0!==s.daysContainer&&(!1===n||G(document.activeElement))){var o=39===e.keyCode?1:-1;e.ctrlKey?(q(o),Y(L(1),0)):Y(void 0,o)}break;case 38:case 40:e.preventDefault();var l=40===e.keyCode?1:-1;s.daysContainer&&void 0!==e.target.$i?e.ctrlKey?(Z(s.currentYear-l),Y(L(1),0)):r||Y(void 0,7*l):s.config.enableTime&&(!r&&s.hourElement&&s.hourElement.focus(),C(e),s._debouncedChange());break;case 9:if(!r){s.element.focus();break}var c=[s.hourElement,s.minuteElement,s.secondElement,s.amPM].filter(function(e){return e}),d=c.indexOf(e.target);if(-1!==d){var u=c[d+(e.shiftKey?-1:1)];void 0!==u?(e.preventDefault(),u.focus()):s.element.focus()}}}if(void 0!==s.amPM&&e.target===s.amPM)switch(e.key){case s.l10n.amPM[0].charAt(0):case s.l10n.amPM[0].charAt(0).toLowerCase():s.amPM.textContent=s.l10n.amPM[0],M(),me();break;case s.l10n.amPM[1].charAt(0):case s.l10n.amPM[1].charAt(0).toLowerCase():s.amPM.textContent=s.l10n.amPM[1],M(),me()}de("onKeyDown",e)}function Q(e){if(1===s.selectedDates.length&&(!e||e.classList.contains("flatpickr-day")&&!e.classList.contains("disabled"))){for(var t=e?e.dateObj.getTime():s.days.firstElementChild.dateObj.getTime(),n=s.parseDate(s.selectedDates[0],void 0,!0).getTime(),i=Math.min(t,s.selectedDates[0].getTime()),a=Math.max(t,s.selectedDates[0].getTime()),r=s.daysContainer.lastChild.lastChild.dateObj.getTime(),o=!1,l=0,c=0,d=i;d<r;d+=h.DAY)K(new Date(d),!0)||(o=o||d>i&&d<a,d<n&&(!l||d>l)?l=d:d>n&&(!c||d<c)&&(c=d));for(var u=0;u<s.config.showMonths;u++)for(var f=s.daysContainer.children[u],m=s.daysContainer.children[u-1],g=function(i,a){var r=f.children[i],d=r.dateObj.getTime(),h=l>0&&d<l||c>0&&d>c;return h?(r.classList.add("notAllowed"),["inRange","startRange","endRange"].forEach(function(e){r.classList.remove(e)}),"continue"):o&&!h?"continue":(["startRange","inRange","endRange","notAllowed"].forEach(function(e){r.classList.remove(e)}),void(void 0!==e&&(e.classList.add(t<s.selectedDates[0].getTime()?"startRange":"endRange"),!f.contains(e)&&u>0&&m&&m.lastChild.dateObj.getTime()>=d||(n<t&&d===n?r.classList.add("startRange"):n>t&&d===n&&r.classList.add("endRange"),d>=l&&(0===c||d<=c)&&p(d,n,t)&&r.classList.add("inRange")))))},v=0,b=f.children.length;v<b;v++)g(v)}}function ee(){!s.isOpen||s.config.static||s.config.inline||ie()}function te(e){return function(t){var n=s.config["_"+e+"Date"]=s.parseDate(t,s.config.dateFormat),i=s.config["_"+("min"===e?"max":"min")+"Date"];void 0!==n&&(s["min"===e?"minDateHasTime":"maxDateHasTime"]=n.getHours()>0||n.getMinutes()>0||n.getSeconds()>0),s.selectedDates&&(s.selectedDates=s.selectedDates.filter(function(e){return K(e)}),s.selectedDates.length||"min"!==e||_(n),me()),s.daysContainer&&(ae(),void 0!==n?s.currentYearElement[e]=n.getFullYear().toString():s.currentYearElement.removeAttribute(e),s.currentYearElement.disabled=!!i&&void 0!==n&&i.getFullYear()===n.getFullYear())}}function ne(){"object"!=typeof s.config.locale&&void 0===D.l10ns[s.config.locale]&&s.config.errorHandler(new Error("flatpickr: invalid locale "+s.config.locale)),s.l10n=Object.assign({},D.l10ns.default,"object"==typeof s.config.locale?s.config.locale:"default"!==s.config.locale?D.l10ns[s.config.locale]:void 0),l.K="("+s.l10n.amPM[0]+"|"+s.l10n.amPM[1]+"|"+s.l10n.amPM[0].toLowerCase()+"|"+s.l10n.amPM[1].toLowerCase()+")",s.formatDate=d(s),s.parseDate=u({config:s.config,l10n:s.l10n})}function ie(e){if(void 0!==s.calendarContainer){de("onPreCalendarPosition");var t=e||s._positionElement,n=Array.prototype.reduce.call(s.calendarContainer.children,function(e,t){return e+t.offsetHeight},0),i=s.calendarContainer.offsetWidth,a=s.config.position.split(" "),r=a[0],o=a.length>1?a[1]:null,l=t.getBoundingClientRect(),c=window.innerHeight-l.bottom,d="above"===r||"below"!==r&&c<n&&l.top>n,u=window.pageYOffset+l.top+(d?-n-2:t.offsetHeight+2);if(v(s.calendarContainer,"arrowTop",!d),v(s.calendarContainer,"arrowBottom",d),!s.config.inline){var f=window.pageXOffset+l.left-(null!=o&&"center"===o?(i-l.width)/2:0),p=window.document.body.offsetWidth-l.right,h=f+i>window.document.body.offsetWidth;v(s.calendarContainer,"rightMost",h),s.config.static||(s.calendarContainer.style.top=u+"px",h?(s.calendarContainer.style.left="auto",s.calendarContainer.style.right=p+"px"):(s.calendarContainer.style.left=f+"px",s.calendarContainer.style.right="auto"))}}}function ae(){s.config.noCalendar||s.isMobile||(pe(),H())}function re(){s._input.focus(),-1!==window.navigator.userAgent.indexOf("MSIE")||void 0!==navigator.msMaxTouchPoints?setTimeout(s.close,0):s.close()}function oe(e){e.preventDefault(),e.stopPropagation();var t=function e(t,n){return n(t)?t:t.parentNode?e(t.parentNode,n):void 0}(e.target,function(e){return e.classList&&e.classList.contains("flatpickr-day")&&!e.classList.contains("disabled")&&!e.classList.contains("notAllowed")});if(void 0!==t){var n=t,i=s.latestSelectedDateObj=new Date(n.dateObj.getTime()),a=(i.getMonth()<s.currentMonth||i.getMonth()>s.currentMonth+s.config.showMonths-1)&&"range"!==s.config.mode;if(s.selectedDateElem=n,"single"===s.config.mode)s.selectedDates=[i];else if("multiple"===s.config.mode){var r=fe(i);r?s.selectedDates.splice(parseInt(r),1):s.selectedDates.push(i)}else"range"===s.config.mode&&(2===s.selectedDates.length&&s.clear(!1),s.selectedDates.push(i),0!==f(i,s.selectedDates[0],!0)&&s.selectedDates.sort(function(e,t){return e.getTime()-t.getTime()}));if(M(),a){var o=s.currentYear!==i.getFullYear();s.currentYear=i.getFullYear(),s.currentMonth=i.getMonth(),o&&de("onYearChange"),de("onMonthChange")}if(pe(),H(),me(),s.config.enableTime&&setTimeout(function(){return s.showTimeInput=!0},50),a||"range"===s.config.mode||1!==s.config.showMonths?s.selectedDateElem&&s.selectedDateElem.focus():P(n),void 0!==s.hourElement&&setTimeout(function(){return void 0!==s.hourElement&&s.hourElement.select()},451),s.config.closeOnSelect){var l="single"===s.config.mode&&!s.config.enableTime,c="range"===s.config.mode&&2===s.selectedDates.length&&!s.config.enableTime;(l||c)&&re()}O()}}s.parseDate=u({config:s.config,l10n:s.l10n}),s._handlers=[],s._bind=F,s._setHoursFromDate=_,s._positionCalendar=ie,s.changeMonth=q,s.changeYear=Z,s.clear=function(e){void 0===e&&(e=!0);s.input.value="",void 0!==s.altInput&&(s.altInput.value="");void 0!==s.mobileInput&&(s.mobileInput.value="");s.selectedDates=[],s.latestSelectedDateObj=void 0,s.showTimeInput=!1,!0===s.config.enableTime&&E();s.redraw(),e&&de("onChange")},s.close=function(){s.isOpen=!1,s.isMobile||(s.calendarContainer.classList.remove("open"),s._input.classList.remove("active"));de("onClose")},s._createElement=b,s.destroy=function(){void 0!==s.config&&de("onDestroy");for(var e=s._handlers.length;e--;){var t=s._handlers[e];t.element.removeEventListener(t.event,t.handler,t.options)}if(s._handlers=[],s.mobileInput)s.mobileInput.parentNode&&s.mobileInput.parentNode.removeChild(s.mobileInput),s.mobileInput=void 0;else if(s.calendarContainer&&s.calendarContainer.parentNode)if(s.config.static&&s.calendarContainer.parentNode){var n=s.calendarContainer.parentNode;if(n.lastChild&&n.removeChild(n.lastChild),n.parentNode){for(;n.firstChild;)n.parentNode.insertBefore(n.firstChild,n);n.parentNode.removeChild(n)}}else s.calendarContainer.parentNode.removeChild(s.calendarContainer);s.altInput&&(s.input.type="text",s.altInput.parentNode&&s.altInput.parentNode.removeChild(s.altInput),delete s.altInput);s.input&&(s.input.type=s.input._type,s.input.classList.remove("flatpickr-input"),s.input.removeAttribute("readonly"),s.input.value="");["_showTimeInput","latestSelectedDateObj","_hideNextMonthArrow","_hidePrevMonthArrow","__hideNextMonthArrow","__hidePrevMonthArrow","isMobile","isOpen","selectedDateElem","minDateHasTime","maxDateHasTime","days","daysContainer","_input","_positionElement","innerContainer","rContainer","monthNav","todayDateElem","calendarContainer","weekdayContainer","prevMonthNav","nextMonthNav","currentMonthElement","currentYearElement","navigationCurrentMonth","selectedDateElem","config"].forEach(function(e){try{delete s[e]}catch(e){}})},s.isEnabled=K,s.jumpToDate=A,s.open=function(e,t){void 0===t&&(t=s._positionElement);if(!0===s.isMobile)return e&&(e.preventDefault(),e.target&&e.target.blur()),void 0!==s.mobileInput&&(s.mobileInput.focus(),s.mobileInput.click()),void de("onOpen");if(s._input.disabled||s.config.inline)return;var n=s.isOpen;s.isOpen=!0,n||(s.calendarContainer.classList.add("open"),s._input.classList.add("active"),de("onOpen"),ie(t));!0===s.config.enableTime&&!0===s.config.noCalendar&&(0===s.selectedDates.length&&(s.setDate(void 0!==s.config.minDate?new Date(s.config.minDate.getTime()):new Date,!1),E(),me()),!1!==s.config.allowInput||void 0!==e&&s.timeContainer.contains(e.relatedTarget)||setTimeout(function(){return s.hourElement.select()},50))},s.redraw=ae,s.set=function(e,t){null!==e&&"object"==typeof e?Object.assign(s.config,e):(s.config[e]=t,void 0!==le[e]?le[e].forEach(function(e){return e()}):m.indexOf(e)>-1&&(s.config[e]=i(t)));s.redraw(),A(),me(!1)},s.setDate=function(e,t,n){void 0===t&&(t=!1);void 0===n&&(n=s.config.dateFormat);if(0!==e&&!e||e instanceof Array&&0===e.length)return s.clear(t);se(e,n),s.showTimeInput=s.selectedDates.length>0,s.latestSelectedDateObj=s.selectedDates[0],s.redraw(),A(),_(),me(t),t&&de("onChange")},s.toggle=function(e){if(!0===s.isOpen)return s.close();s.open(e)};var le={locale:[ne,B],showMonths:[U,k,W]};function se(e,t){var n=[];if(e instanceof Array)n=e.map(function(e){return s.parseDate(e,t)});else if(e instanceof Date||"number"==typeof e)n=[s.parseDate(e,t)];else if("string"==typeof e)switch(s.config.mode){case"single":case"time":n=[s.parseDate(e,t)];break;case"multiple":n=e.split(s.config.conjunction).map(function(e){return s.parseDate(e,t)});break;case"range":n=e.split(s.l10n.rangeSeparator).map(function(e){return s.parseDate(e,t)})}else s.config.errorHandler(new Error("Invalid date supplied: "+JSON.stringify(e)));s.selectedDates=n.filter(function(e){return e instanceof Date&&K(e,!1)}),"range"===s.config.mode&&s.selectedDates.sort(function(e,t){return e.getTime()-t.getTime()})}function ce(e){return e.slice().map(function(e){return"string"==typeof e||"number"==typeof e||e instanceof Date?s.parseDate(e,void 0,!0):e&&"object"==typeof e&&e.from&&e.to?{from:s.parseDate(e.from,void 0),to:s.parseDate(e.to,void 0)}:e}).filter(function(e){return e})}function de(e,t){if(void 0!==s.config){var n=s.config[e];if(void 0!==n&&n.length>0)for(var i=0;n[i]&&i<n.length;i++)n[i](s.selectedDates,s.input.value,s,t);"onChange"===e&&(s.input.dispatchEvent(ue("change")),s.input.dispatchEvent(ue("input")))}}function ue(e){var t=document.createEvent("Event");return t.initEvent(e,!0,!0),t}function fe(e){for(var t=0;t<s.selectedDates.length;t++)if(0===f(s.selectedDates[t],e))return""+t;return!1}function pe(){s.config.noCalendar||s.isMobile||!s.monthNav||(s.yearElements.forEach(function(e,t){var n=new Date(s.currentYear,s.currentMonth,1);n.setMonth(s.currentMonth+t),s.monthElements[t].textContent=r(n.getMonth(),s.config.shorthandCurrentMonth,s.l10n)+" ",e.value=n.getFullYear().toString()}),s._hidePrevMonthArrow=void 0!==s.config.minDate&&(s.currentYear===s.config.minDate.getFullYear()?s.currentMonth<=s.config.minDate.getMonth():s.currentYear<s.config.minDate.getFullYear()),s._hideNextMonthArrow=void 0!==s.config.maxDate&&(s.currentYear===s.config.maxDate.getFullYear()?s.currentMonth+1>s.config.maxDate.getMonth():s.currentYear>s.config.maxDate.getFullYear()))}function he(e){return s.selectedDates.map(function(t){return s.formatDate(t,e)}).filter(function(e,t,n){return"range"!==s.config.mode||s.config.enableTime||n.indexOf(e)===t}).join("range"!==s.config.mode?s.config.conjunction:s.l10n.rangeSeparator)}function me(e){if(void 0===e&&(e=!0),0===s.selectedDates.length)return s.clear(e);void 0!==s.mobileInput&&s.mobileFormatStr&&(s.mobileInput.value=void 0!==s.latestSelectedDateObj?s.formatDate(s.latestSelectedDateObj,s.mobileFormatStr):""),s.input.value=he(s.config.dateFormat),void 0!==s.altInput&&(s.altInput.value=he(s.config.altFormat)),!1!==e&&de("onValueUpdate")}function ge(e){e.preventDefault();var t=s.prevMonthNav.contains(e.target),n=s.nextMonthNav.contains(e.target);t||n?q(t?-1:1):s.yearElements.indexOf(e.target)>=0?e.target.select():e.target.classList.contains("arrowUp")?s.changeYear(s.currentYear+1):e.target.classList.contains("arrowDown")&&s.changeYear(s.currentYear-1)}return function(){s.element=s.input=a,s.isOpen=!1,function(){var e=["wrap","weekNumbers","allowInput","clickOpens","time_24hr","enableTime","noCalendar","altInput","shorthandCurrentMonth","inline","static","enableSeconds","disableMobile"],t=Object.assign({},o,JSON.parse(JSON.stringify(a.dataset||{}))),n={};s.config.parseDate=t.parseDate,s.config.formatDate=t.formatDate,Object.defineProperty(s.config,"enable",{get:function(){return s.config._enable},set:function(e){s.config._enable=ce(e)}}),Object.defineProperty(s.config,"disable",{get:function(){return s.config._disable},set:function(e){s.config._disable=ce(e)}});var r="time"===t.mode;t.dateFormat||!t.enableTime&&!r||(n.dateFormat=t.noCalendar||r?"H:i"+(t.enableSeconds?":S":""):D.defaultConfig.dateFormat+" H:i"+(t.enableSeconds?":S":"")),t.altInput&&(t.enableTime||r)&&!t.altFormat&&(n.altFormat=t.noCalendar||r?"h:i"+(t.enableSeconds?":S K":" K"):D.defaultConfig.altFormat+" h:i"+(t.enableSeconds?":S":"")+" K"),Object.defineProperty(s.config,"minDate",{get:function(){return s.config._minDate},set:te("min")}),Object.defineProperty(s.config,"maxDate",{get:function(){return s.config._maxDate},set:te("max")});var l=function(e){return function(t){s.config["min"===e?"_minTime":"_maxTime"]=s.parseDate(t,"H:i")}};Object.defineProperty(s.config,"minTime",{get:function(){return s.config._minTime},set:l("min")}),Object.defineProperty(s.config,"maxTime",{get:function(){return s.config._maxTime},set:l("max")}),"time"===t.mode&&(s.config.noCalendar=!0,s.config.enableTime=!0),Object.assign(s.config,n,t);for(var c=0;c<e.length;c++)s.config[e[c]]=!0===s.config[e[c]]||"true"===s.config[e[c]];m.filter(function(e){return void 0!==s.config[e]}).forEach(function(e){s.config[e]=i(s.config[e]||[]).map(g)}),s.isMobile=!s.config.disableMobile&&!s.config.inline&&"single"===s.config.mode&&!s.config.disable.length&&!s.config.enable.length&&!s.config.weekNumbers&&/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);for(var d=0;d<s.config.plugins.length;d++){var u=s.config.plugins[d](s)||{};for(var f in u)m.indexOf(f)>-1?s.config[f]=i(u[f]).map(g).concat(s.config[f]):void 0===t[f]&&(s.config[f]=u[f])}de("onParseConfig")}(),ne(),s.input=s.config.wrap?a.querySelector("[data-input]"):a,s.input?(s.input._type=s.input.type,s.input.type="text",s.input.classList.add("flatpickr-input"),s._input=s.input,s.config.altInput&&(s.altInput=b(s.input.nodeName,s.input.className+" "+s.config.altInputClass),s._input=s.altInput,s.altInput.placeholder=s.input.placeholder,s.altInput.disabled=s.input.disabled,s.altInput.required=s.input.required,s.altInput.tabIndex=s.input.tabIndex,s.altInput.type="text",s.input.setAttribute("type","hidden"),!s.config.static&&s.input.parentNode&&s.input.parentNode.insertBefore(s.altInput,s.input.nextSibling)),s.config.allowInput||s._input.setAttribute("readonly","readonly"),s._positionElement=s.config.positionElement||s._input):s.config.errorHandler(new Error("Invalid input element specified")),function(){s.selectedDates=[],s.now=s.parseDate(s.config.now)||new Date;var e=s.config.defaultDate||("INPUT"!==s.input.nodeName&&"TEXTAREA"!==s.input.nodeName||!s.input.placeholder||s.input.value!==s.input.placeholder?s.input.value:null);e&&se(e,s.config.dateFormat);var t=s.selectedDates.length>0?s.selectedDates[0]:s.config.minDate&&s.config.minDate.getTime()>s.now.getTime()?s.config.minDate:s.config.maxDate&&s.config.maxDate.getTime()<s.now.getTime()?s.config.maxDate:s.now;s.currentYear=t.getFullYear(),s.currentMonth=t.getMonth(),s.selectedDates.length>0&&(s.latestSelectedDateObj=s.selectedDates[0]),void 0!==s.config.minTime&&(s.config.minTime=s.parseDate(s.config.minTime,"H:i")),void 0!==s.config.maxTime&&(s.config.maxTime=s.parseDate(s.config.maxTime,"H:i")),s.minDateHasTime=!!s.config.minDate&&(s.config.minDate.getHours()>0||s.config.minDate.getMinutes()>0||s.config.minDate.getSeconds()>0),s.maxDateHasTime=!!s.config.maxDate&&(s.config.maxDate.getHours()>0||s.config.maxDate.getMinutes()>0||s.config.maxDate.getSeconds()>0),Object.defineProperty(s,"showTimeInput",{get:function(){return s._showTimeInput},set:function(e){s._showTimeInput=e,s.calendarContainer&&v(s.calendarContainer,"showTimeInput",e),s.isOpen&&ie()}})}(),s.utils={getDaysInMonth:function(e,t){return void 0===e&&(e=s.currentMonth),void 0===t&&(t=s.currentYear),1===e&&(t%4==0&&t%100!=0||t%400==0)?29:s.l10n.daysInMonth[e]}},s.isMobile||function(){var n=window.document.createDocumentFragment();if(s.calendarContainer=b("div","flatpickr-calendar"),s.calendarContainer.tabIndex=-1,!s.config.noCalendar){if(n.appendChild((s.monthNav=b("div","flatpickr-months"),s.yearElements=[],s.monthElements=[],s.prevMonthNav=b("span","flatpickr-prev-month"),s.prevMonthNav.innerHTML=s.config.prevArrow,s.nextMonthNav=b("span","flatpickr-next-month"),s.nextMonthNav.innerHTML=s.config.nextArrow,U(),Object.defineProperty(s,"_hidePrevMonthArrow",{get:function(){return s.__hidePrevMonthArrow},set:function(e){s.__hidePrevMonthArrow!==e&&(v(s.prevMonthNav,"disabled",e),s.__hidePrevMonthArrow=e)}}),Object.defineProperty(s,"_hideNextMonthArrow",{get:function(){return s.__hideNextMonthArrow},set:function(e){s.__hideNextMonthArrow!==e&&(v(s.nextMonthNav,"disabled",e),s.__hideNextMonthArrow=e)}}),s.currentYearElement=s.yearElements[0],pe(),s.monthNav)),s.innerContainer=b("div","flatpickr-innerContainer"),s.config.weekNumbers){var i=function(){s.calendarContainer.classList.add("hasWeeks");var e=b("div","flatpickr-weekwrapper");e.appendChild(b("span","flatpickr-weekday",s.l10n.weekAbbreviation));var t=b("div","flatpickr-weeks");return e.appendChild(t),{weekWrapper:e,weekNumbers:t}}(),a=i.weekWrapper,r=i.weekNumbers;s.innerContainer.appendChild(a),s.weekNumbers=r,s.weekWrapper=a}s.rContainer=b("div","flatpickr-rContainer"),s.rContainer.appendChild(W()),s.daysContainer||(s.daysContainer=b("div","flatpickr-days"),s.daysContainer.tabIndex=-1),H(),s.rContainer.appendChild(s.daysContainer),s.innerContainer.appendChild(s.rContainer),n.appendChild(s.innerContainer)}s.config.enableTime&&n.appendChild(function(){s.calendarContainer.classList.add("hasTime"),s.config.noCalendar&&s.calendarContainer.classList.add("noCalendar"),s.timeContainer=b("div","flatpickr-time"),s.timeContainer.tabIndex=-1;var n=b("span","flatpickr-time-separator",":"),i=w("flatpickr-hour");s.hourElement=i.getElementsByTagName("input")[0];var a=w("flatpickr-minute");if(s.minuteElement=a.getElementsByTagName("input")[0],s.hourElement.tabIndex=s.minuteElement.tabIndex=-1,s.hourElement.value=e(s.latestSelectedDateObj?s.latestSelectedDateObj.getHours():s.config.time_24hr?s.config.defaultHour:function(e){switch(e%24){case 0:case 12:return 12;default:return e%12}}(s.config.defaultHour)),s.minuteElement.value=e(s.latestSelectedDateObj?s.latestSelectedDateObj.getMinutes():s.config.defaultMinute),s.hourElement.setAttribute("data-step",s.config.hourIncrement.toString()),s.minuteElement.setAttribute("data-step",s.config.minuteIncrement.toString()),s.hourElement.setAttribute("data-min",s.config.time_24hr?"0":"1"),s.hourElement.setAttribute("data-max",s.config.time_24hr?"23":"12"),s.minuteElement.setAttribute("data-min","0"),s.minuteElement.setAttribute("data-max","59"),s.timeContainer.appendChild(i),s.timeContainer.appendChild(n),s.timeContainer.appendChild(a),s.config.time_24hr&&s.timeContainer.classList.add("time24hr"),s.config.enableSeconds){s.timeContainer.classList.add("hasSeconds");var r=w("flatpickr-second");s.secondElement=r.getElementsByTagName("input")[0],s.secondElement.value=e(s.latestSelectedDateObj?s.latestSelectedDateObj.getSeconds():s.config.defaultSeconds),s.secondElement.setAttribute("data-step",s.minuteElement.getAttribute("data-step")),s.secondElement.setAttribute("data-min",s.minuteElement.getAttribute("data-min")),s.secondElement.setAttribute("data-max",s.minuteElement.getAttribute("data-max")),s.timeContainer.appendChild(b("span","flatpickr-time-separator",":")),s.timeContainer.appendChild(r)}return s.config.time_24hr||(s.amPM=b("span","flatpickr-am-pm",s.l10n.amPM[t((s.latestSelectedDateObj?s.hourElement.value:s.config.defaultHour)>11)]),s.amPM.title=s.l10n.toggleTitle,s.amPM.tabIndex=-1,s.timeContainer.appendChild(s.amPM)),s.timeContainer}()),v(s.calendarContainer,"rangeMode","range"===s.config.mode),v(s.calendarContainer,"animate",!0===s.config.animate),v(s.calendarContainer,"multiMonth",s.config.showMonths>1),s.calendarContainer.appendChild(n);var o=void 0!==s.config.appendTo&&void 0!==s.config.appendTo.nodeType;if((s.config.inline||s.config.static)&&(s.calendarContainer.classList.add(s.config.inline?"inline":"static"),s.config.inline&&(!o&&s.element.parentNode?s.element.parentNode.insertBefore(s.calendarContainer,s._input.nextSibling):void 0!==s.config.appendTo&&s.config.appendTo.appendChild(s.calendarContainer)),s.config.static)){var l=b("div","flatpickr-wrapper");s.element.parentNode&&s.element.parentNode.insertBefore(l,s.element),l.appendChild(s.element),s.altInput&&l.appendChild(s.altInput),l.appendChild(s.calendarContainer)}s.config.static||s.config.inline||(void 0!==s.config.appendTo?s.config.appendTo:window.document.body).appendChild(s.calendarContainer)}(),function(){if(s.config.wrap&&["open","close","toggle","clear"].forEach(function(e){Array.prototype.forEach.call(s.element.querySelectorAll("[data-"+e+"]"),function(t){return F(t,"click",s[e])})}),s.isMobile)!function(){var e=s.config.enableTime?s.config.noCalendar?"time":"datetime-local":"date";s.mobileInput=b("input",s.input.className+" flatpickr-mobile"),s.mobileInput.step=s.input.getAttribute("step")||"any",s.mobileInput.tabIndex=1,s.mobileInput.type=e,s.mobileInput.disabled=s.input.disabled,s.mobileInput.required=s.input.required,s.mobileInput.placeholder=s.input.placeholder,s.mobileFormatStr="datetime-local"===e?"Y-m-d\\TH:i:S":"date"===e?"Y-m-d":"H:i:S",s.selectedDates.length>0&&(s.mobileInput.defaultValue=s.mobileInput.value=s.formatDate(s.selectedDates[0],s.mobileFormatStr)),s.config.minDate&&(s.mobileInput.min=s.formatDate(s.config.minDate,"Y-m-d")),s.config.maxDate&&(s.mobileInput.max=s.formatDate(s.config.maxDate,"Y-m-d")),s.input.type="hidden",void 0!==s.altInput&&(s.altInput.type="hidden");try{s.input.parentNode&&s.input.parentNode.insertBefore(s.mobileInput,s.input.nextSibling)}catch(e){}F(s.mobileInput,"change",function(e){s.setDate(e.target.value,!1,s.mobileFormatStr),de("onChange"),de("onClose")})}();else{var e=n(ee,50);s._debouncedChange=n(O,x),s.daysContainer&&!/iPhone|iPad|iPod/i.test(navigator.userAgent)&&F(s.daysContainer,"mouseover",function(e){"range"===s.config.mode&&Q(e.target)}),F(window.document.body,"keydown",X),s.config.static||F(s._input,"keydown",X),s.config.inline||s.config.static||F(window,"resize",e),void 0!==window.ontouchstart?F(window.document,"click",J):F(window.document,"mousedown",S(J)),F(window.document,"focus",J,{capture:!0}),!0===s.config.clickOpens&&(F(s._input,"focus",s.open),F(s._input,"mousedown",S(s.open))),void 0!==s.daysContainer&&(F(s.monthNav,"mousedown",S(ge)),F(s.monthNav,["keyup","increment"],I),F(s.daysContainer,"mousedown",S(oe))),void 0!==s.timeContainer&&void 0!==s.minuteElement&&void 0!==s.hourElement&&(F(s.timeContainer,["increment"],C),F(s.timeContainer,"blur",C,{capture:!0}),F(s.timeContainer,"mousedown",S(j)),F([s.hourElement,s.minuteElement],["focus","click"],function(e){return e.target.select()}),void 0!==s.secondElement&&F(s.secondElement,"focus",function(){return s.secondElement&&s.secondElement.select()}),void 0!==s.amPM&&F(s.amPM,"mousedown",S(function(e){C(e),O()})))}}(),(s.selectedDates.length||s.config.noCalendar)&&(s.config.enableTime&&_(s.config.noCalendar?s.latestSelectedDateObj||s.config.minDate:void 0),me(!1)),k(),s.showTimeInput=s.selectedDates.length>0||s.config.noCalendar;var r=/^((?!chrome|android).)*safari/i.test(navigator.userAgent);!s.isMobile&&r&&ie(),de("onReady")}(),s}function C(e,t){for(var n=Array.prototype.slice.call(e),i=[],a=0;a<n.length;a++){var r=n[a];try{if(null!==r.getAttribute("data-fp-omit"))continue;void 0!==r._flatpickr&&(r._flatpickr.destroy(),r._flatpickr=void 0),r._flatpickr=k(r,t||{}),i.push(r._flatpickr)}catch(e){console.error(e)}}return 1===i.length?i[0]:i}"undefined"!=typeof HTMLElement&&(HTMLCollection.prototype.flatpickr=NodeList.prototype.flatpickr=function(e){return C(this,e)},HTMLElement.prototype.flatpickr=function(e){return C([this],e)});var D=function(e,t){return e instanceof NodeList?C(e,t):C("string"==typeof e?window.document.querySelectorAll(e):[e],t)};return D.defaultConfig=g,D.l10ns={en:Object.assign({},c),default:Object.assign({},c)},D.localize=function(e){D.l10ns.default=Object.assign({},D.l10ns.default,e)},D.setDefaults=function(e){D.defaultConfig=Object.assign({},D.defaultConfig,e)},D.parseDate=u({}),D.formatDate=d({}),D.compareDates=f,"undefined"!=typeof jQuery&&(jQuery.fn.flatpickr=function(e){return C(this,e)}),Date.prototype.fp_incr=function(e){return new Date(this.getFullYear(),this.getMonth(),this.getDate()+("string"==typeof e?parseInt(e,10):e))},"undefined"!=typeof window&&(window.flatpickr=D),D},e.exports=i()},I6hT:function(e,t,n){var i=n("VU/8")(n("o0bH"),null,!1,null,null,null);e.exports=i.exports},Izc3:function(e,t,n){var i=n("VU/8")(n("pM6D"),null,!1,null,null,null);e.exports=i.exports},J66Q:function(e,t,n){Vue.component("flash-wrapper",n("UHEH")),Vue.component("flash",n("atAl")),Vue.component("tabs",n("o3eH")),Vue.component("tab",n("rz5Y")),Vue.component("accordian",n("fuLy")),Vue.component("tree-view",n("YjnE")),Vue.component("tree-item",n("k4Ds")),Vue.component("tree-checkbox",n("C2Px")),Vue.component("tree-radio",n("Z7s4")),Vue.component("modal",n("J6WO")),Vue.component("image-upload",n("EZgW")),Vue.component("image-wrapper",n("kIKU")),Vue.component("image-item",n("i//U")),Vue.directive("slugify",n("2JMG")),Vue.directive("code",n("I6hT")),Vue.directive("alert",n("Izc3")),Vue.component("datetime",n("9yns")),Vue.component("date",n("Lql5")),n("K4gw")},J6WO:function(e,t,n){var i=n("VU/8")(n("Tty+"),n("VrhZ"),!1,null,null,null);e.exports=i.exports},K4gw:function(e,t,n){var i=n("qfq8");"string"==typeof i&&(i=[[e.i,i,""]]);var a={transform:void 0};n("MTIv")(i,a);i.locals&&(e.exports=i.locals)},KJcg:function(e,t,n){var i=n("vex8");"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);n("rjj0")("45506552",i,!0,{})},Lql5:function(e,t,n){var i=n("VU/8")(n("yJeA"),n("rNTR"),!1,null,null,null);e.exports=i.exports},MT9B:function(e,t){},MTIv:function(e,t,n){var i,a,r={},o=(i=function(){return window&&document&&document.all&&!window.atob},function(){return void 0===a&&(a=i.apply(this,arguments)),a}),l=function(e){var t={};return function(e){return void 0===t[e]&&(t[e]=function(e){return document.querySelector(e)}.call(this,e)),t[e]}}(),s=null,c=0,d=[],u=n("mJPh");function f(e,t){for(var n=0;n<e.length;n++){var i=e[n],a=r[i.id];if(a){a.refs++;for(var o=0;o<a.parts.length;o++)a.parts[o](i.parts[o]);for(;o<i.parts.length;o++)a.parts.push(b(i.parts[o],t))}else{var l=[];for(o=0;o<i.parts.length;o++)l.push(b(i.parts[o],t));r[i.id]={id:i.id,refs:1,parts:l}}}}function p(e,t){for(var n=[],i={},a=0;a<e.length;a++){var r=e[a],o=t.base?r[0]+t.base:r[0],l={css:r[1],media:r[2],sourceMap:r[3]};i[o]?i[o].parts.push(l):n.push(i[o]={id:o,parts:[l]})}return n}function h(e,t){var n=l(e.insertInto);if(!n)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var i=d[d.length-1];if("top"===e.insertAt)i?i.nextSibling?n.insertBefore(t,i.nextSibling):n.appendChild(t):n.insertBefore(t,n.firstChild),d.push(t);else{if("bottom"!==e.insertAt)throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");n.appendChild(t)}}function m(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e);var t=d.indexOf(e);t>=0&&d.splice(t,1)}function g(e){var t=document.createElement("style");return e.attrs.type="text/css",v(t,e.attrs),h(e,t),t}function v(e,t){Object.keys(t).forEach(function(n){e.setAttribute(n,t[n])})}function b(e,t){var n,i,a,r;if(t.transform&&e.css){if(!(r=t.transform(e.css)))return function(){};e.css=r}if(t.singleton){var o=c++;n=s||(s=g(t)),i=x.bind(null,n,o,!1),a=x.bind(null,n,o,!0)}else e.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(n=function(e){var t=document.createElement("link");return e.attrs.type="text/css",e.attrs.rel="stylesheet",v(t,e.attrs),h(e,t),t}(t),i=function(e,t,n){var i=n.css,a=n.sourceMap,r=void 0===t.convertToAbsoluteUrls&&a;(t.convertToAbsoluteUrls||r)&&(i=u(i));a&&(i+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(a))))+" */");var o=new Blob([i],{type:"text/css"}),l=e.href;e.href=URL.createObjectURL(o),l&&URL.revokeObjectURL(l)}.bind(null,n,t),a=function(){m(n),n.href&&URL.revokeObjectURL(n.href)}):(n=g(t),i=function(e,t){var n=t.css,i=t.media;i&&e.setAttribute("media",i);if(e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}.bind(null,n),a=function(){m(n)});return i(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;i(e=t)}else a()}}e.exports=function(e,t){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");(t=t||{}).attrs="object"==typeof t.attrs?t.attrs:{},t.singleton||(t.singleton=o()),t.insertInto||(t.insertInto="head"),t.insertAt||(t.insertAt="bottom");var n=p(e,t);return f(n,t),function(e){for(var i=[],a=0;a<n.length;a++){var o=n[a];(l=r[o.id]).refs--,i.push(l)}e&&f(p(e,t),t);for(a=0;a<i.length;a++){var l;if(0===(l=i[a]).refs){for(var s=0;s<l.parts.length;s++)l.parts[s]();delete r[l.id]}}}};var y,w=(y=[],function(e,t){return y[e]=t,y.filter(Boolean).join("\n")});function x(e,t,n,i){var a=n?"":i.css;if(e.styleSheet)e.styleSheet.cssText=w(t,a);else{var r=document.createTextNode(a),o=e.childNodes;o[t]&&e.removeChild(o[t]),o.length?e.insertBefore(r,o[t]):e.appendChild(r)}}},Oy72:function(e,t){$(function(){function e(){if(dropdown=$(".dropdown-open"),!dropdown.find(".dropdown-list").hasClass("top-left")&&!dropdown.find(".dropdown-list").hasClass("top-right")&&dropdown.length){dropdown=dropdown.find(".dropdown-list"),height=dropdown.height()+50;var e=dropdown.offset().top-70,t=$(window).height()-e-dropdown.height();t>e||height<t?(dropdown.removeClass("bottom"),dropdown.hasClass("top-right")?(dropdown.removeClass("top-right"),dropdown.addClass("bottom-right")):dropdown.hasClass("top-left")&&(dropdown.removeClass("top-left"),dropdown.addClass("bottom-left"))):dropdown.hasClass("bottom-right")?(dropdown.removeClass("bottom-right"),dropdown.addClass("top-right")):dropdown.hasClass("bottom-left")&&(dropdown.removeClass("bottom-left"),dropdown.addClass("top-left"))}}$(document).click(function(e){var t=e.target;(!$(t).parents(".dropdown-open").length||$(t).is("li")||$(t).is("a"))&&($(".dropdown-list").hide(),$(".dropdown-toggle").removeClass("active"))}),$("body").delegate(".dropdown-toggle","click",function(t){!function(t){var n=$(t.currentTarget);if("disabled"==n.attr("disabled"))return;$(".dropdown-list").hide(),n.hasClass("active")?n.removeClass("active"):(n.addClass("active"),n.parent().find(".dropdown-list").fadeIn(100),n.parent().addClass("dropdown-open"),e())}(t)}),$(".dropdown-list .search-box .control").on("input",function(){var e=$(this);e.parents(".dropdown-list").find("li").each(function(){var t=$(this).text().trim().toLowerCase(),n=$(this).attr("data-id");if(n){var i=t.search(e.val().toLowerCase()),a=n.search(e.val());i<0&&a<0?$(this).hide():($(this).show(),flag=1)}else{(i=t.search(e.val().toLowerCase()))<0?$(this).hide():$(this).show()}})}),$("div").scroll(function(){e()})})},Qujv:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"image-wrapper"},e._l(e.items,function(t,i){return n("image-item",{key:t.id,attrs:{image:t,"input-name":e.inputName,"remove-button-label":e.removeButtonLabel},on:{onRemoveImage:function(t){e.removeImage(t)}}})}),1),e._v(" "),n("label",{staticClass:"btn btn-lg btn-primary",staticStyle:{display:"inline-block",width:"auto"},on:{click:e.createFileType}},[e._v(e._s(e.buttonLabel))])])},staticRenderFns:[]}},"Tty+":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={props:["id","isOpen"],created:function(){this.closeModal()},computed:{isModalOpen:function(){return this.addClassToBody(),this.isOpen}},methods:{closeModal:function(){this.$root.$set(this.$root.modalIds,this.id,!1)},addClassToBody:function(){var e=document.querySelector("body");this.isOpen?e.classList.add("modal-open"):e.classList.remove("modal-open")}}}},UHEH:function(e,t,n){var i=n("VU/8")(n("7z1m"),n("n2S5"),!1,null,null,null);e.exports=i.exports},"VU/8":function(e,t){e.exports=function(e,t,n,i,a,r){var o,l=e=e||{},s=typeof e.default;"object"!==s&&"function"!==s||(o=e,l=e.default);var c,d="function"==typeof l?l.options:l;if(t&&(d.render=t.render,d.staticRenderFns=t.staticRenderFns,d._compiled=!0),n&&(d.functional=!0),a&&(d._scopeId=a),r?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),i&&i.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(r)},d._ssrRegister=c):i&&(c=i),c){var u=d.functional,f=u?d.render:d.beforeCreate;u?(d._injectStyles=c,d.render=function(e,t){return c.call(t),f(e,t)}):d.beforeCreate=f?[].concat(f,c):[c]}return{esModule:o,exports:l,options:d}}},VrhZ:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.isModalOpen?n("div",{staticClass:"modal-container"},[n("div",{staticClass:"modal-header"},[e._t("header",[e._v("\n            Default header\n        ")]),e._v(" "),n("i",{staticClass:"icon remove-icon",on:{click:e.closeModal}})],2),e._v(" "),n("div",{staticClass:"modal-body"},[e._t("body",[e._v("\n            Default body\n        ")])],2)]):e._e()},staticRenderFns:[]}},YjnE:function(e,t,n){var i=n("VU/8")(n("7aQn"),null,!1,null,null,null);e.exports=i.exports},Z7s4:function(e,t,n){var i=n("VU/8")(n("qyM9"),n("44Sb"),!1,null,null,null);e.exports=i.exports},atAl:function(e,t,n){var i=n("VU/8")(n("wtqv"),n("AOOz"),!1,null,null,null);e.exports=i.exports},by3Y:function(e,t){e.exports={render:function(){var e=this.$createElement;return(this._self._c||e)("div",{directives:[{name:"show",rawName:"v-show",value:this.isActive,expression:"isActive"}]},[this._t("default")],2)},staticRenderFns:[]}},fXJ7:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"preview-image"},[e._t("default"),e._v(" "),n("div",{staticClass:"preview-wrapper"},[n("img",{staticClass:"image-preview",attrs:{src:e.sample}})]),e._v(" "),n("div",{staticClass:"remove-preview"},[n("button",{staticClass:"btn btn-md btn-primary",on:{click:function(t){return t.preventDefault(),e.removePreviewImage(t)}}},[e._v("Remove Image")])])],2)},staticRenderFns:[]}},fuLy:function(e,t,n){var i=n("VU/8")(n("6wXy"),n("yfX9"),!1,null,null,null);e.exports=i.exports},"i//U":function(e,t,n){var i=n("VU/8")(n("8skS"),n("3AR7"),!1,null,null,null);e.exports=i.exports},iT8k:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={props:{name:{required:!0},selected:{default:!1}},data:function(){return{isActive:!1}},mounted:function(){this.isActive=this.selected}}},k4Ds:function(e,t,n){var i=n("VU/8")(n("31/P"),null,!1,null,null,null);e.exports=i.exports},kIKU:function(e,t,n){var i=n("VU/8")(n("rolU"),n("Qujv"),!1,null,null,null);e.exports=i.exports},mJPh:function(e,t){e.exports=function(e){var t="undefined"!=typeof window&&window.location;if(!t)throw new Error("fixUrls requires window.location");if(!e||"string"!=typeof e)return e;var n=t.protocol+"//"+t.host,i=n+t.pathname.replace(/\/[^\/]*$/,"/");return e.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,function(e,t){var a,r=t.trim().replace(/^"(.*)"$/,function(e,t){return t}).replace(/^'(.*)'$/,function(e,t){return t});return/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(r)?e:(a=0===r.indexOf("//")?r:0===r.indexOf("/")?n+r:i+r.replace(/^\.\//,""),"url("+JSON.stringify(a)+")")})}},n2S5:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("transition-group",{staticClass:"alert-wrapper",attrs:{tag:"div",name:"flash-wrapper"}},e._l(e.flashes,function(t){return n("flash",{key:t.uid,attrs:{flash:t},on:{onRemoveFlash:function(t){e.removeFlash(t)}}})}),1)},staticRenderFns:[]}},o0bH:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={bind:function(e,t,n){e.addEventListener("input",function(e){setTimeout(function(){e.target.value=e.target.value.toString().replace(/[^\w_ ]+/g,"").trim().replace(/ +/g,"-")},100)})}}},o3eH:function(e,t,n){var i=n("VU/8")(n("sTtw"),n("13ON"),!1,null,null,null);e.exports=i.exports},pM6D:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={bind:function(e,t,n){e.addEventListener("click",function(n){n.preventDefault();var i="Are your sure you want to perform this action ?";t.value&&""!=t.value&&(i=t.value),confirm(i)&&(window.location.href=e.href)})}}},qfq8:function(e,t,n){(e.exports=n("FZ+f")(!1)).push([e.i,'.flatpickr-calendar{background:transparent;opacity:0;display:none;text-align:center;visibility:hidden;padding:0;-webkit-animation:none;animation:none;direction:ltr;border:0;font-size:14px;line-height:24px;border-radius:5px;position:absolute;width:307.875px;-webkit-box-sizing:border-box;box-sizing:border-box;-ms-touch-action:manipulation;touch-action:manipulation;background:#fff;-webkit-box-shadow:1px 0 0 #e6e6e6,-1px 0 0 #e6e6e6,0 1px 0 #e6e6e6,0 -1px 0 #e6e6e6,0 3px 13px rgba(0,0,0,.08);box-shadow:1px 0 0 #e6e6e6,-1px 0 0 #e6e6e6,0 1px 0 #e6e6e6,0 -1px 0 #e6e6e6,0 3px 13px rgba(0,0,0,.08)}.flatpickr-calendar.inline,.flatpickr-calendar.open{opacity:1;max-height:640px;visibility:visible}.flatpickr-calendar.open{display:inline-block;z-index:99999}.flatpickr-calendar.animate.open{-webkit-animation:fpFadeInDown .3s cubic-bezier(.23,1,.32,1);animation:fpFadeInDown .3s cubic-bezier(.23,1,.32,1)}.flatpickr-calendar.inline{display:block;position:relative;top:2px}.flatpickr-calendar.static{position:absolute;top:calc(100% + 2px)}.flatpickr-calendar.static.open{z-index:999;display:block}.flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+1) .flatpickr-day.inRange:nth-child(7n+7){-webkit-box-shadow:none!important;box-shadow:none!important}.flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+2) .flatpickr-day.inRange:nth-child(7n+1){-webkit-box-shadow:-2px 0 0 #e6e6e6,5px 0 0 #e6e6e6;box-shadow:-2px 0 0 #e6e6e6,5px 0 0 #e6e6e6}.flatpickr-calendar .hasTime .dayContainer,.flatpickr-calendar .hasWeeks .dayContainer{border-bottom:0;border-bottom-right-radius:0;border-bottom-left-radius:0}.flatpickr-calendar .hasWeeks .dayContainer{border-left:0}.flatpickr-calendar.showTimeInput.hasTime .flatpickr-time{height:40px;border-top:1px solid #e6e6e6}.flatpickr-calendar.noCalendar.hasTime .flatpickr-time{height:auto}.flatpickr-calendar:after,.flatpickr-calendar:before{position:absolute;display:block;pointer-events:none;border:solid transparent;content:"";height:0;width:0;left:22px}.flatpickr-calendar.rightMost:after,.flatpickr-calendar.rightMost:before{left:auto;right:22px}.flatpickr-calendar:before{border-width:5px;margin:0 -5px}.flatpickr-calendar:after{border-width:4px;margin:0 -4px}.flatpickr-calendar.arrowTop:after,.flatpickr-calendar.arrowTop:before{bottom:100%}.flatpickr-calendar.arrowTop:before{border-bottom-color:#e6e6e6}.flatpickr-calendar.arrowTop:after{border-bottom-color:#fff}.flatpickr-calendar.arrowBottom:after,.flatpickr-calendar.arrowBottom:before{top:100%}.flatpickr-calendar.arrowBottom:before{border-top-color:#e6e6e6}.flatpickr-calendar.arrowBottom:after{border-top-color:#fff}.flatpickr-calendar:focus{outline:0}.flatpickr-wrapper{position:relative;display:inline-block}.flatpickr-months{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.flatpickr-months .flatpickr-month{background:transparent;color:rgba(0,0,0,.9);fill:rgba(0,0,0,.9);height:28px;line-height:1;text-align:center;position:relative;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;overflow:hidden;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1}.flatpickr-months .flatpickr-next-month,.flatpickr-months .flatpickr-prev-month{text-decoration:none;cursor:pointer;position:absolute;top:0;line-height:16px;height:28px;padding:10px;z-index:3;color:rgba(0,0,0,.9);fill:rgba(0,0,0,.9)}.flatpickr-months .flatpickr-next-month.disabled,.flatpickr-months .flatpickr-prev-month.disabled{display:none}.flatpickr-months .flatpickr-next-month i,.flatpickr-months .flatpickr-prev-month i{position:relative}.flatpickr-months .flatpickr-next-month.flatpickr-prev-month,.flatpickr-months .flatpickr-prev-month.flatpickr-prev-month{left:0}.flatpickr-months .flatpickr-next-month.flatpickr-next-month,.flatpickr-months .flatpickr-prev-month.flatpickr-next-month{right:0}.flatpickr-months .flatpickr-next-month:hover,.flatpickr-months .flatpickr-prev-month:hover{color:#959ea9}.flatpickr-months .flatpickr-next-month:hover svg,.flatpickr-months .flatpickr-prev-month:hover svg{fill:#f64747}.flatpickr-months .flatpickr-next-month svg,.flatpickr-months .flatpickr-prev-month svg{width:14px;height:14px}.flatpickr-months .flatpickr-next-month svg path,.flatpickr-months .flatpickr-prev-month svg path{-webkit-transition:fill .1s;transition:fill .1s;fill:inherit}.numInputWrapper{position:relative;height:auto}.numInputWrapper input,.numInputWrapper span{display:inline-block}.numInputWrapper input{width:100%}.numInputWrapper input::-ms-clear{display:none}.numInputWrapper span{position:absolute;right:0;width:14px;padding:0 4px 0 2px;height:50%;line-height:50%;opacity:0;cursor:pointer;border:1px solid rgba(57,57,57,.15);-webkit-box-sizing:border-box;box-sizing:border-box}.numInputWrapper span:hover{background:rgba(0,0,0,.1)}.numInputWrapper span:active{background:rgba(0,0,0,.2)}.numInputWrapper span:after{display:block;content:"";position:absolute}.numInputWrapper span.arrowUp{top:0;border-bottom:0}.numInputWrapper span.arrowUp:after{border-left:4px solid transparent;border-right:4px solid transparent;border-bottom:4px solid rgba(57,57,57,.6);top:26%}.numInputWrapper span.arrowDown{top:50%}.numInputWrapper span.arrowDown:after{border-left:4px solid transparent;border-right:4px solid transparent;border-top:4px solid rgba(57,57,57,.6);top:40%}.numInputWrapper span svg{width:inherit;height:auto}.numInputWrapper span svg path{fill:rgba(0,0,0,.5)}.numInputWrapper:hover{background:rgba(0,0,0,.05)}.numInputWrapper:hover span{opacity:1}.flatpickr-current-month{font-size:135%;line-height:inherit;font-weight:300;color:inherit;position:absolute;width:75%;left:12.5%;padding:6.16px 0 0;line-height:1;height:28px;display:inline-block;text-align:center;-webkit-transform:translateZ(0);transform:translateZ(0)}.flatpickr-current-month span.cur-month{font-family:inherit;font-weight:700;color:inherit;display:inline-block;margin-left:.5ch;padding:0}.flatpickr-current-month span.cur-month:hover{background:rgba(0,0,0,.05)}.flatpickr-current-month .numInputWrapper{width:6ch;width:7ch\\0;display:inline-block}.flatpickr-current-month .numInputWrapper span.arrowUp:after{border-bottom-color:rgba(0,0,0,.9)}.flatpickr-current-month .numInputWrapper span.arrowDown:after{border-top-color:rgba(0,0,0,.9)}.flatpickr-current-month input.cur-year{background:transparent;-webkit-box-sizing:border-box;box-sizing:border-box;color:inherit;cursor:text;padding:0 0 0 .5ch;margin:0;display:inline-block;font-size:inherit;font-family:inherit;font-weight:300;line-height:inherit;height:auto;border:0;border-radius:0;vertical-align:initial}.flatpickr-current-month input.cur-year:focus{outline:0}.flatpickr-current-month input.cur-year[disabled],.flatpickr-current-month input.cur-year[disabled]:hover{font-size:100%;color:rgba(0,0,0,.5);background:transparent;pointer-events:none}.flatpickr-weekdays{background:transparent;text-align:center;overflow:hidden;width:100%;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;height:28px}.flatpickr-weekdays,.flatpickr-weekdays .flatpickr-weekdaycontainer{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.flatpickr-weekdays .flatpickr-weekdaycontainer,span.flatpickr-weekday{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1}span.flatpickr-weekday{cursor:default;font-size:90%;background:transparent;color:rgba(0,0,0,.54);line-height:1;margin:0;text-align:center;display:block;font-weight:bolder}.dayContainer,.flatpickr-weeks{padding:1px 0 0}.flatpickr-days{position:relative;overflow:hidden;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:start;-webkit-align-items:flex-start;-ms-flex-align:start;align-items:flex-start;width:307.875px}.flatpickr-days:focus{outline:0}.dayContainer{padding:0;outline:0;text-align:left;width:307.875px;min-width:307.875px;max-width:307.875px;-webkit-box-sizing:border-box;box-sizing:border-box;display:inline-block;display:-ms-flexbox;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-wrap:wrap;-ms-flex-pack:justify;-webkit-justify-content:space-around;justify-content:space-around;-webkit-transform:translateZ(0);transform:translateZ(0);opacity:1}.dayContainer+.dayContainer{-webkit-box-shadow:-1px 0 0 #e6e6e6;box-shadow:-1px 0 0 #e6e6e6}.flatpickr-day{background:none;border:1px solid transparent;border-radius:150px;-webkit-box-sizing:border-box;box-sizing:border-box;color:#393939;cursor:pointer;font-weight:400;width:14.2857143%;-webkit-flex-basis:14.2857143%;-ms-flex-preferred-size:14.2857143%;flex-basis:14.2857143%;max-width:39px;height:39px;line-height:39px;margin:0;display:inline-block;position:relative;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;text-align:center}.flatpickr-day.inRange,.flatpickr-day.nextMonthDay.inRange,.flatpickr-day.nextMonthDay.today.inRange,.flatpickr-day.nextMonthDay:focus,.flatpickr-day.nextMonthDay:hover,.flatpickr-day.prevMonthDay.inRange,.flatpickr-day.prevMonthDay.today.inRange,.flatpickr-day.prevMonthDay:focus,.flatpickr-day.prevMonthDay:hover,.flatpickr-day.today.inRange,.flatpickr-day:focus,.flatpickr-day:hover{cursor:pointer;outline:0;background:#e6e6e6;border-color:#e6e6e6}.flatpickr-day.today{border-color:#959ea9}.flatpickr-day.today:focus,.flatpickr-day.today:hover{border-color:#959ea9;background:#959ea9;color:#fff}.flatpickr-day.endRange,.flatpickr-day.endRange.inRange,.flatpickr-day.endRange.nextMonthDay,.flatpickr-day.endRange.prevMonthDay,.flatpickr-day.endRange:focus,.flatpickr-day.endRange:hover,.flatpickr-day.selected,.flatpickr-day.selected.inRange,.flatpickr-day.selected.nextMonthDay,.flatpickr-day.selected.prevMonthDay,.flatpickr-day.selected:focus,.flatpickr-day.selected:hover,.flatpickr-day.startRange,.flatpickr-day.startRange.inRange,.flatpickr-day.startRange.nextMonthDay,.flatpickr-day.startRange.prevMonthDay,.flatpickr-day.startRange:focus,.flatpickr-day.startRange:hover{background:#569ff7;-webkit-box-shadow:none;box-shadow:none;color:#fff;border-color:#569ff7}.flatpickr-day.endRange.startRange,.flatpickr-day.selected.startRange,.flatpickr-day.startRange.startRange{border-radius:50px 0 0 50px}.flatpickr-day.endRange.endRange,.flatpickr-day.selected.endRange,.flatpickr-day.startRange.endRange{border-radius:0 50px 50px 0}.flatpickr-day.endRange.startRange+.endRange:not(:nth-child(7n+1)),.flatpickr-day.selected.startRange+.endRange:not(:nth-child(7n+1)),.flatpickr-day.startRange.startRange+.endRange:not(:nth-child(7n+1)){-webkit-box-shadow:-10px 0 0 #569ff7;box-shadow:-10px 0 0 #569ff7}.flatpickr-day.endRange.startRange.endRange,.flatpickr-day.selected.startRange.endRange,.flatpickr-day.startRange.startRange.endRange{border-radius:50px}.flatpickr-day.inRange{border-radius:0;-webkit-box-shadow:-5px 0 0 #e6e6e6,5px 0 0 #e6e6e6;box-shadow:-5px 0 0 #e6e6e6,5px 0 0 #e6e6e6}.flatpickr-day.disabled,.flatpickr-day.disabled:hover,.flatpickr-day.nextMonthDay,.flatpickr-day.notAllowed,.flatpickr-day.notAllowed.nextMonthDay,.flatpickr-day.notAllowed.prevMonthDay,.flatpickr-day.prevMonthDay{color:rgba(57,57,57,.3);background:transparent;border-color:transparent;cursor:default}.flatpickr-day.disabled,.flatpickr-day.disabled:hover{cursor:not-allowed;color:rgba(57,57,57,.1)}.flatpickr-day.week.selected{border-radius:0;-webkit-box-shadow:-5px 0 0 #569ff7,5px 0 0 #569ff7;box-shadow:-5px 0 0 #569ff7,5px 0 0 #569ff7}.flatpickr-day.hidden{visibility:hidden}.rangeMode .flatpickr-day{margin-top:1px}.flatpickr-weekwrapper{display:inline-block;float:left}.flatpickr-weekwrapper .flatpickr-weeks{padding:0 12px;-webkit-box-shadow:1px 0 0 #e6e6e6;box-shadow:1px 0 0 #e6e6e6}.flatpickr-weekwrapper .flatpickr-weekday{float:none;width:100%;line-height:28px}.flatpickr-weekwrapper span.flatpickr-day,.flatpickr-weekwrapper span.flatpickr-day:hover{display:block;width:100%;max-width:none;color:rgba(57,57,57,.3);background:transparent;cursor:default;border:none}.flatpickr-innerContainer{display:block;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;overflow:hidden}.flatpickr-innerContainer,.flatpickr-rContainer{-webkit-box-sizing:border-box;box-sizing:border-box}.flatpickr-rContainer{display:inline-block;padding:0}.flatpickr-time{text-align:center;outline:0;display:block;height:0;line-height:40px;max-height:40px;-webkit-box-sizing:border-box;box-sizing:border-box;overflow:hidden;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.flatpickr-time:after{content:"";display:table;clear:both}.flatpickr-time .numInputWrapper{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;width:40%;height:40px;float:left}.flatpickr-time .numInputWrapper span.arrowUp:after{border-bottom-color:#393939}.flatpickr-time .numInputWrapper span.arrowDown:after{border-top-color:#393939}.flatpickr-time.hasSeconds .numInputWrapper{width:26%}.flatpickr-time.time24hr .numInputWrapper{width:49%}.flatpickr-time input{background:transparent;-webkit-box-shadow:none;box-shadow:none;border:0;border-radius:0;text-align:center;margin:0;padding:0;height:inherit;line-height:inherit;color:#393939;font-size:14px;position:relative;-webkit-box-sizing:border-box;box-sizing:border-box}.flatpickr-time input.flatpickr-hour{font-weight:700}.flatpickr-time input.flatpickr-minute,.flatpickr-time input.flatpickr-second{font-weight:400}.flatpickr-time input:focus{outline:0;border:0}.flatpickr-time .flatpickr-am-pm,.flatpickr-time .flatpickr-time-separator{height:inherit;display:inline-block;float:left;line-height:inherit;color:#393939;font-weight:700;width:2%;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-align-self:center;-ms-flex-item-align:center;align-self:center}.flatpickr-time .flatpickr-am-pm{outline:0;width:18%;cursor:pointer;text-align:center;font-weight:400}.flatpickr-time .flatpickr-am-pm:focus,.flatpickr-time .flatpickr-am-pm:hover,.flatpickr-time input:focus,.flatpickr-time input:hover{background:#f3f3f3}.flatpickr-input[readonly]{cursor:pointer}@-webkit-keyframes fpFadeInDown{0%{opacity:0;-webkit-transform:translate3d(0,-20px,0);transform:translate3d(0,-20px,0)}to{opacity:1;-webkit-transform:translateZ(0);transform:translateZ(0)}}@keyframes fpFadeInDown{0%{opacity:0;-webkit-transform:translate3d(0,-20px,0);transform:translate3d(0,-20px,0)}to{opacity:1;-webkit-transform:translateZ(0);transform:translateZ(0)}}',""])},qyM9:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={name:"tree-radio",props:["id","label","nameField","modelValue","value"],computed:{isActive:function(){return!!this.value.length&&this.value[0]==this.modelValue}}}},rNTR:function(e,t){e.exports={render:function(){var e=this.$createElement,t=this._self._c||e;return t("span",[this._t("default",[t("input",{staticClass:"control",attrs:{type:"text",name:this.name,"data-input":""},domProps:{value:this.value}})])],2)},staticRenderFns:[]}},rjj0:function(e,t,n){var i="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!i)throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var a=n("tTVk"),r={},o=i&&(document.head||document.getElementsByTagName("head")[0]),l=null,s=0,c=!1,d=function(){},u=null,f="data-vue-ssr-id",p="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function h(e){for(var t=0;t<e.length;t++){var n=e[t],i=r[n.id];if(i){i.refs++;for(var a=0;a<i.parts.length;a++)i.parts[a](n.parts[a]);for(;a<n.parts.length;a++)i.parts.push(g(n.parts[a]));i.parts.length>n.parts.length&&(i.parts.length=n.parts.length)}else{var o=[];for(a=0;a<n.parts.length;a++)o.push(g(n.parts[a]));r[n.id]={id:n.id,refs:1,parts:o}}}}function m(){var e=document.createElement("style");return e.type="text/css",o.appendChild(e),e}function g(e){var t,n,i=document.querySelector("style["+f+'~="'+e.id+'"]');if(i){if(c)return d;i.parentNode.removeChild(i)}if(p){var a=s++;i=l||(l=m()),t=y.bind(null,i,a,!1),n=y.bind(null,i,a,!0)}else i=m(),t=function(e,t){var n=t.css,i=t.media,a=t.sourceMap;i&&e.setAttribute("media",i);u.ssrId&&e.setAttribute(f,t.id);a&&(n+="\n/*# sourceURL="+a.sources[0]+" */",n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(a))))+" */");if(e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}.bind(null,i),n=function(){i.parentNode.removeChild(i)};return t(e),function(i){if(i){if(i.css===e.css&&i.media===e.media&&i.sourceMap===e.sourceMap)return;t(e=i)}else n()}}e.exports=function(e,t,n,i){c=n,u=i||{};var o=a(e,t);return h(o),function(t){for(var n=[],i=0;i<o.length;i++){var l=o[i];(s=r[l.id]).refs--,n.push(s)}t?h(o=a(e,t)):o=[];for(i=0;i<n.length;i++){var s;if(0===(s=n[i]).refs){for(var c=0;c<s.parts.length;c++)s.parts[c]();delete r[s.id]}}}};var v,b=(v=[],function(e,t){return v[e]=t,v.filter(Boolean).join("\n")});function y(e,t,n,i){var a=n?"":i.css;if(e.styleSheet)e.styleSheet.cssText=b(t,a);else{var r=document.createTextNode(a),o=e.childNodes;o[t]&&e.removeChild(o[t]),o.length?e.insertBefore(r,o[t]):e.appendChild(r)}}},rolU:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={props:{buttonLabel:{type:String,required:!1,default:"Add Image"},removeButtonLabel:{type:String,required:!1,default:"Remove Image"},inputName:{type:String,required:!1,default:"attachments"},images:{type:Array|String,required:!1,default:function(){return[]}},multiple:{type:Boolean,required:!1,default:!0}},data:function(){return{imageCount:0,items:[]}},created:function(){var e=this;this.multiple?this.images.length?this.images.forEach(function(t){e.items.push(t),e.imageCount++}):this.createFileType():this.images&&""!=this.images?(this.items.push({id:"image_"+this.imageCount,url:this.images}),this.imageCount++):this.createFileType()},methods:{createFileType:function(){var e=this;this.multiple||this.items.forEach(function(t){e.removeImage(t)}),this.imageCount++,this.items.push({id:"image_"+this.imageCount})},removeImage:function(e){var t=this.items.indexOf(e);Vue.delete(this.items,t)}}}},rsIj:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("span",{staticClass:"checkbox"},[n("input",{attrs:{type:"checkbox",id:e.id,name:[e.nameField+"[]"]},domProps:{value:e.modelValue,checked:e.isActive},on:{change:function(t){e.inputChanged()}}}),e._v(" "),n("label",{staticClass:"checkbox-view",attrs:{for:e.id}}),e._v(" "),n("span",{attrs:{for:e.id}},[e._v(e._s(e.label))])])},staticRenderFns:[]}},rz5Y:function(e,t,n){var i=n("VU/8")(n("iT8k"),n("by3Y"),!1,null,null,null);e.exports=i.exports},sTtw:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{tabs:[]}},created:function(){this.tabs=this.$children},methods:{selectTab:function(e){this.tabs.forEach(function(t){t.isActive=t.name==e.name})}}}},tTVk:function(e,t){e.exports=function(e,t){for(var n=[],i={},a=0;a<t.length;a++){var r=t[a],o=r[0],l={id:e+":"+a,css:r[1],media:r[2],sourceMap:r[3]};i[o]?i[o].parts.push(l):n.push(i[o]={id:o,parts:[l]})}return n}},vex8:function(e,t,n){(e.exports=n("FZ+f")(!1)).push([e.i,".preview-wrapper{height:200px;width:200px;padding:5px}.image-preview{height:190px;width:190px}",""])},wq5e:function(e,t){e.exports={render:function(){var e=this.$createElement,t=this._self._c||e;return t("span",[this._t("default",[t("input",{staticClass:"control",attrs:{type:"text",name:this.name,"data-input":""},domProps:{value:this.value}})])],2)},staticRenderFns:[]}},wtqv:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={props:["flash"],created:function(){var e=this;setTimeout(function(){e.$emit("onRemoveFlash",e.flash)},5e3)},methods:{remove:function(){this.$emit("onRemoveFlash",this.flash)}}}},yJeA:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n("GxBP"),a=n.n(i);t.default={props:{name:String,value:String},data:function(){return{datepicker:null}},mounted:function(){var e=this,t=this.$el.getElementsByTagName("input")[0];this.datepicker=new a.a(t,{altFormat:"Y-m-d",dateFormat:"Y-m-d",onChange:function(t,n,i){e.$emit("onChange",n)}})}}},yfX9:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"accordian",class:[e.isActive?"active":"",e.className],attrs:{id:e.id}},[n("div",{staticClass:"accordian-header",on:{click:function(t){e.toggleAccordion()}}},[e._t("header",[e._v("\n            "+e._s(e.title)+"\n            "),n("i",{staticClass:"icon",class:e.iconClass})])],2),e._v(" "),n("div",{staticClass:"accordian-content"},[e._t("body")],2)])},staticRenderFns:[]}}});
>>>>>>> 97cb751c56e436baed6fe43c0e0fccfbf726334d
