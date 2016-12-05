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

Object.defineProperty(exports, "__esModule", {
	value: true
});
exports.default = createButtonModal;

var _jquery = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);

var _jquery2 = _interopRequireDefault(_jquery);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var open = false,
    modal = void 0,
    html = secpAdminModal.modal.trim(),
    closeModal = function closeModal() {
	if (modal && modal.remove) {
		modal.remove();
	}
	open = false;
},
    callback = void 0; /**
                        * Shopify eCommerce Plugin - Shopping Cart - Add Button Modal
                        * https://www.shopify.com/buy-button
                        *
                        * Licensed under the GPLv2+ license.
                        */

/* global secpAdminModal */


window.addEventListener('message', function (event) {
	var origin = event.origin || event.originalEvent.origin;

	// Return if origin isn't shopify.
	if (!open || 'https://widgets.shopifyapps.com' !== origin) {
		return;
	}

	// If data returned, trigger callback.
	if (event.data.resourceType && event.data.resourceHandles && event.data.resourceHandles.length) {
		if ('product' === event.data.resourceType) {
			modal.find('iframe').remove();
			modal.find('.secp-modal-secondpage').show();
			modal.find('.secp-modal-add-button').click(function () {
				event.data.show = modal.find('.secp-show:checked').val();
				callback(event.data);
				closeModal();
			});
		} else {
			callback(event.data);
			closeModal();
		}
	} else {
		closeModal();
	}
});

function createButtonModal(cb) {
	// Only open one at a time.
	if (open) {
		return;
	}
	open = true;

	callback = cb;

	// Add modal to document.
	modal = (0, _jquery2.default)(html).appendTo(document.body);

	// Handle close button event.
	modal.on('click', '.secp-modal-close', function (e) {
		e.preventDefault();
		closeModal();
	});
}

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
 * Shopify eCommerce Plugin - Shopping Cart - Admin Widget
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

(0, _jquery2.default)(function () {
	(0, _jquery2.default)(document.body).on('click', '#secp-add-widget', function (e) {
		// Grab inputs and iframe of current widget.
		var $widgetWrap = (0, _jquery2.default)(this).closest('.secp-widget-wrap'),
		    $c = {
			inputType: $widgetWrap.find('.secp-hidden-embed_type'),
			inputShop: $widgetWrap.find('.secp-hidden-shop'),
			inputHandle: $widgetWrap.find('.secp-hidden-product_handle'),
			inputShow: $widgetWrap.find('.secp-hidden-show'),
			iframe: $widgetWrap.find('.secp-widget-preview')
		};

		e.preventDefault();

		(0, _addButtonModal2.default)(function (data) {
			var fakeEnterPress = void 0;

			// Fill in hidden fields with postMessage results
			$c.inputType.val(data.resourceType);
			$c.inputShop.val(data.shop);
			$c.inputShow.val(data.show);
			$c.inputHandle.val(data.resourceHandles.join(', '));

			// Fake enter press on one of the hidden fields to trigger
			// customizer refresh!
			fakeEnterPress = new _jquery2.default.Event('keydown');
			fakeEnterPress.which = 13;
			$c.inputHandle.trigger(fakeEnterPress);

			// Update preview iframe with postMessage results
			$c.iframe.attr('src', document.location.protocol + '//' + document.location.host + '?product_handle=' + encodeURIComponent(data.resourceHandles.join(', ')) + '&shop=' + encodeURIComponent(data.shop) + '&embed_type=' + encodeURIComponent(data.resourceType) + '&show=' + encodeURIComponent(data.show)).parent().removeClass('no-product');
		});
	});
});

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./add-button-modal":1}]},{},[2]);
