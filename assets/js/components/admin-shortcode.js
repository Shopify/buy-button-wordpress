/**
 * Shopify eCommerce Plugin - Shopping Cart - Admin Shortcode
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

/* global tinymce */

import $ from 'jquery';
import modal from './add-button-modal';

$( function() {
	$( '#secp-add-shortcode' ).click( function( e ) {
		let $this = $( this ),
			$wrap = $this.parents( '.wp-editor-wrap' );

		e.preventDefault();

		modal( ( data ) => {
			let shortcode, shortcodeAtts, editor;

			shortcodeAtts = [
				{ name: 'embed_type', value: data.resourceType },
				{ name: 'shop', value: data.shop },
				{ name: 'product_handle', value: data.resourceHandles.join( ', ' ) },
				{ name: 'show', value: data.show }
			];

			shortcode = '[shopify';

			for ( let i in shortcodeAtts ) {
				if ( shortcodeAtts[i].value ) {
					shortcode += ` ${ shortcodeAtts[i].name }="${ shortcodeAtts[i].value }"`;
				}
			}

			shortcode += ']';

			// Insert shortcode.
			if ( $wrap.hasClass( 'tmce-active' ) ) {
				editor = tinymce.get( $this.data( 'editor-id' ) );
				editor.insertContent( shortcode );
			} else {
				editor = $wrap.find( '.wp-editor-area' );
				editor.val( editor.val() + '\n\n' + shortcode );
			}
		} );
	} );
} );
