import $ from 'jquery';
export default function createButtonModal( callback ) {
	let modal,
		html;

	modal = $( html ).appendTo( document.body );

	modal.addClass( 'test' );

	modal.on( 'click', )

	window.addEventListener( 'message', () => {
		let data = arguments;

		console.log( data );

		callback( data );
	} );
}
