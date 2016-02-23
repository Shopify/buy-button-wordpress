/**
 * Shopify Buy Button - v0.1.0 - 2016-02-23
 * http://webdevstudios.com
 *
 * Copyright (c) 2016;
 * Licensed GPLv2+
 */

(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function (global){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = createButtonModal;

var _jquery = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);

var _jquery2 = _interopRequireDefault(_jquery);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function createButtonModal(callback) {
	var _arguments = arguments;

	var modal = undefined,
	    html = sbbAdminModal.modal.trim();

	modal = (0, _jquery2.default)(html).appendTo(document.body);

	modal.addClass('test');

	modal.on('click', '.sbb-modal-close', function (e) {
		e.preventDefault();
		modal.remove();
	});

	window.addEventListener('message', function () {
		var data = _arguments;

		console.log(data);

		callback(data);
	});
} /* global sbbAdminModal */

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],2:[function(require,module,exports){
(function (global){
'use strict';

var _jquery = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);

var _jquery2 = _interopRequireDefault(_jquery);

var _addButtonModal = require('./add-button-modal');

var _addButtonModal2 = _interopRequireDefault(_addButtonModal);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * Shopify Buy Button - Admin Shortcode
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

/* global tinymce */

(0, _jquery2.default)(function () {
	(0, _jquery2.default)('#sbb-add-shortcode').click(function (e) {
		var _this = this;

		var editor = undefined;

		e.preventDefault();

		(0, _addButtonModal2.default)(function () {
			editor = tinymce.get((0, _jquery2.default)(_this).data('editor-id'));
			editor.insertContent('[shopify-buy-button]');
		});
	});
});

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./add-button-modal":1}]},{},[2]);
