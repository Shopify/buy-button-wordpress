/**
 * Shopify eCommerce Plugin - Shopping Cart - Add Button Modal
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

/* global secpAdminModal */
import $ from 'jquery';

let open = false,
	modal,
	html = secpAdminModal.modal.trim(),
	closeModal = function() {
		if ( modal && modal.remove ) {
			modal.remove();
		}
		open = false;
	},
	callback;

window.addEventListener( 'message', ( event ) => {
	let origin = event.origin || event.originalEvent.origin;

	// Return if origin isn't shopify.
	if ( ! open || 'https://widgets.shopifyapps.com' !== origin ) {
		return;
	}

	// If data returned, trigger callback.
	if ( event.data.resourceType &&
			event.data.resourceHandles &&
			event.data.resourceHandles.length ) {
		if ( 'product' === event.data.resourceType ) {
			modal.find( 'iframe' ).remove();
			modal.find( '.secp-modal-secondpage' ).show();
			modal.find( '.secp-modal-add-button' ).click( function() {
				event.data.show = modal.find( '.secp-show:checked' ).val();
				callback( event.data );
				closeModal();
			} );
		} else {
			callback( event.data );
			closeModal();
		}
	} else {
		closeModal();
	}
} );

export default function createButtonModal( cb ) {
	// Only open one at a time.
	if ( open ) {
		return;
	}
	open = true;

	callback = cb;

	// Add modal to document.
	modal = $( html ).appendTo( document.body );

	// Handle close button event.
	modal.on( 'click', '.secp-modal-close', function( e ) {
		e.preventDefault();
		closeModal();
	} );
}
