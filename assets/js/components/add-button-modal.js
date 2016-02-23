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

	window.addEventListener( 'message', () => {
		let data = arguments;

		console.log( data );

		callback( data );
	} );
}
