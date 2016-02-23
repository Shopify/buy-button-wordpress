/* global sbbAdminModal */
import $ from 'jquery';
export default function createButtonModal( callback ) {
	let modal,
		html = sbbAdminModal.modal.trim();

	modal = $( html ).appendTo( document.body );

	modal.addClass( 'test' );

	modal.on( 'click', '.sbb-modal-close', function( e ) {
		e.preventDefault();
		modal.remove();
	} );

	window.addEventListener( 'message', ( event ) => {
		let origin = event.origin || event.originalEvent.origin;

		console.log( origin );
		console.log( event.data );

		if ( 'https://widgets.shopifyapps.com' !== event.origin ) {
			return;
		}

		if ( event.data.resourceType && event.data.resourceHandles && event.data.resourceHandles.length ) {
			callback( event.data );
		}

		modal.remove();
	} );
}
