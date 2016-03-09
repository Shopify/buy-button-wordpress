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
			let shortcode, shortcodeAtts;

			shortcodeAtts = [
				{ name: 'embed_type', value: data.resourceType },
				{ name: 'shop', value: data.shop },
				{ name: 'product_handle', value: data.resourceHandles.join( ', ' ) },
				{ name: 'show', value: data.show }
			];

			shortcode = '[shopify-buy-button';

			for ( let i in shortcodeAtts ) {
				if ( shortcodeAtts[i].value ) {
					shortcode += ` ${ shortcodeAtts[i].name }="${ shortcodeAtts[i].value }"`;
				}
			}

			shortcode += ']';

			// Insert shortcode.
			editor = tinymce.get( $( this ).data( 'editor-id' ) );

			if ( editor ) {
				editor.insertContent( shortcode );
			} else {
				$( this ).parents( '.wp-editor-wrap' )
						.find( '.wp-editor-area' )
						.append( '\n\n' + shortcode );
			}
		} );
	} );
} );
