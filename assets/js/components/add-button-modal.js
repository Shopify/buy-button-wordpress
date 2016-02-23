import $ from 'jquery';
import template from './templates/modal.ejs';
export default function createButtonModal( callback ) {
	let modal,
		html = template( {
			url: 'https://widgets.shopifyapps.com/embed_admin/embeds/picker'
		} );

	modal = $( html )
		.appendTo( document.body );

	modal.addClass( 'test' );

	window.addEventListener( 'message', () => {
		let data = arguments;

		console.log( data );

		callback( data );
	} );
}
