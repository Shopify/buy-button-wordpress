/**
 * Shopify eCommerce Plugin - Shopping Cart - v1.1.4 - 2016-12-05
 * https://www.shopify.com/
 *
 * Copyright (c) 2016;
 * Licensed GPLv2+
 */

(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function (global){
'use strict';

var _jquery = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);

var _jquery2 = _interopRequireDefault(_jquery);

var _queryString = require('query-string');

var _queryString2 = _interopRequireDefault(_queryString);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * Shopify eCommerce Plugin - Shopping Cart - Admin Customize Page
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

(0, _jquery2.default)(function () {
	var $iframe = (0, _jquery2.default)('.secp-customize-preview'),
	    addArgument = function addArgument(key, val) {
		var loc = $iframe.attr('src'),
		    split = loc.split('?'),
		    parsed = _queryString2.default.parse(split[1]);

		if ('#' === val[0]) {
			val = val.slice(1);
		}

		if (parsed[key] !== val) {
			if (val) {
				parsed[key] = val;
			} else {
				delete parsed[key];
			}
			loc = split[0] + '?' + _queryString2.default.stringify(parsed);

			$iframe.attr('src', loc);
		}
	};

	(0, _jquery2.default)(document.body).on('change', 'input,select', function () {
		if ('background' === this.id) {
			addArgument(this.name, this.checked);
			(0, _jquery2.default)('.cmb2-id-background-color').toggleClass('disabled', !this.checked);
		} else {
			addArgument(this.name, this.value);
		}
	});

	// Add color picker change event
	(0, _jquery2.default)('.cmb2-colorpicker').wpColorPicker({
		change: function change(event, ui) {
			var name = event.target.name,
			    color = ui.color.toString();

			addArgument(name, color);
		}
	});

	// Adjust color picker styling to have field title in button.
	setTimeout(function () {
		(0, _jquery2.default)('.wp-color-result').each(function () {
			var $this = (0, _jquery2.default)(this),
			    newTitle = void 0;

			newTitle = $this.closest('.cmb-row').find('.cmb-th label').text();

			$this.attr('title', newTitle).attr('data-current', newTitle);
		});
	}, 1);

	(0, _jquery2.default)('.cmb2-id-background-color').toggleClass('disabled', (0, _jquery2.default)('.cmb2-id-background input:checked').length === 0);
});

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"query-string":2}],2:[function(require,module,exports){
'use strict';
var strictUriEncode = require('strict-uri-encode');

exports.extract = function (str) {
	return str.split('?')[1] || '';
};

exports.parse = function (str) {
	if (typeof str !== 'string') {
		return {};
	}

	str = str.trim().replace(/^(\?|#|&)/, '');

	if (!str) {
		return {};
	}

	return str.split('&').reduce(function (ret, param) {
		var parts = param.replace(/\+/g, ' ').split('=');
		// Firefox (pre 40) decodes `%3D` to `=`
		// https://github.com/sindresorhus/query-string/pull/37
		var key = parts.shift();
		var val = parts.length > 0 ? parts.join('=') : undefined;

		key = decodeURIComponent(key);

		// missing `=` should be `null`:
		// http://w3.org/TR/2012/WD-url-20120524/#collect-url-parameters
		val = val === undefined ? null : decodeURIComponent(val);

		if (!ret.hasOwnProperty(key)) {
			ret[key] = val;
		} else if (Array.isArray(ret[key])) {
			ret[key].push(val);
		} else {
			ret[key] = [ret[key], val];
		}

		return ret;
	}, {});
};

exports.stringify = function (obj) {
	return obj ? Object.keys(obj).sort().map(function (key) {
		var val = obj[key];

		if (val === undefined) {
			return '';
		}

		if (val === null) {
			return key;
		}

		if (Array.isArray(val)) {
			return val.slice().sort().map(function (val2) {
				return strictUriEncode(key) + '=' + strictUriEncode(val2);
			}).join('&');
		}

		return strictUriEncode(key) + '=' + strictUriEncode(val);
	}).filter(function (x) {
		return x.length > 0;
	}).join('&') : '';
};

},{"strict-uri-encode":3}],3:[function(require,module,exports){
'use strict';
module.exports = function (str) {
	return encodeURIComponent(str).replace(/[!'()*]/g, function (c) {
		return '%' + c.charCodeAt(0).toString(16).toUpperCase();
	});
};

},{}]},{},[1]);
