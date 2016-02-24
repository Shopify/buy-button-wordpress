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

		modal( ( data ) => {
			// Generate shortcode
			let shortcode = `[shopify-buy-button embed_type="${ data.resourceType }" shop="${ data.shop }" product_handle="${ data.resourceHandles.join( ', ' ) }"]`;

			// Insert shortcode.
			editor = tinymce.get( $( this ).data( 'editor-id' ) );
			editor.insertContent( shortcode );
		} );
	} );
} );
