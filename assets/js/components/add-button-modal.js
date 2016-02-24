/**
 * Shopify Buy Button - Add Button Modal
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

/* global sbbAdminModal */
import $ from 'jquery';
let open = false,
	modal,
	html = sbbAdminModal.modal.trim(),
	closeModal = function() {
		if ( modal && modal.remove ) {
			modal.remove();
		}
		open = false;
	};

export default function createButtonModal( callback ) {
	// Only open one at a time.
	if ( open ) {
		return;
	}
	open = true;

	// Add modal to document.
	modal = $( html ).appendTo( document.body );

	// Handle close button event.
	modal.on( 'click', '.sbb-modal-close', function( e ) {
		e.preventDefault();
		closeModal();
	} );

	// Handle post message from iframe.
	window.addEventListener( 'message', ( event ) => {
		let origin = event.origin || event.originalEvent.origin;

		// Return if origin isn't shopify.
		if ( 'https://widgets.shopifyapps.com' !== origin ) {
			return;
		}

		// If data returned, trigger callback.
		if ( event.data.resourceType && event.data.resourceHandles && event.data.resourceHandles.length ) {
			callback( event.data );
		}

		closeModal();
	} );
}
