/**
 * Shopify eCommerce Plugin - Shopping Cart - Admin Widget
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

import $ from 'jquery';
import modal from './add-button-modal';

$( function() {
	$( document.body ).on( 'click', '#secp-add-widget', function( e ) {
		// Grab inputs and iframe of current widget.
		var $widgetWrap = $( this ).closest( '.secp-widget-wrap' ),
			$c = {
				inputType:   $widgetWrap.find( '.secp-hidden-embed_type' ),
				inputShop:   $widgetWrap.find( '.secp-hidden-shop' ),
				inputHandle: $widgetWrap.find( '.secp-hidden-product_handle' ),
				inputShow:   $widgetWrap.find( '.secp-hidden-show' ),
				iframe:      $widgetWrap.find( '.secp-widget-preview' )
			};

		e.preventDefault();

		modal( ( data ) => {
			let fakeEnterPress;

			// Fill in hidden fields with postMessage results
			$c.inputType.val( data.resourceType );
			$c.inputShop.val( data.shop );
			$c.inputShow.val( data.show );
			$c.inputHandle.val( data.resourceHandles.join( ', ' ) );

			// Fake enter press on one of the hidden fields to trigger
			// customizer refresh!
			fakeEnterPress = new $.Event( 'keydown' );
			fakeEnterPress.which = 13;
			$c.inputHandle.trigger( fakeEnterPress );

			// Update preview iframe with postMessage results
			$c.iframe
				.attr( 'src', `${ document.location.protocol }//${ document.location.host }?product_handle=${ encodeURIComponent( data.resourceHandles.join( ', ' ) ) }&shop=${ encodeURIComponent( data.shop ) }&embed_type=${ encodeURIComponent( data.resourceType ) }&show=${ encodeURIComponent( data.show ) }` )
				.parent().removeClass( 'no-product' );
		} );
	} );
} );
