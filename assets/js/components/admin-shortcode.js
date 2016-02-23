/**
 * Shopify Buy Button - Admin Shortcode
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

/* global tinymce */

import $ from 'jquery';
import modal from './add-button-modal';

$( function() {
	$( '#sbb-add-shortcode' ).click( function( e ) {
		let editor;

		e.preventDefault();

		modal( () => {
			editor = tinymce.get( $( this ).data( 'editor-id' ) );
			editor.insertContent( '[shopify-buy-button]' );
		} );
	} );
} );
