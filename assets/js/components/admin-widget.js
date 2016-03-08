/**
 * Shopify Buy Button - Admin Widget
 * https://www.shopify.com/buy-button
 *
 * Licensed under the GPLv2+ license.
 */

import $ from 'jquery';
import modal from './add-button-modal';

$( function() {
	$( document.body ).on( 'click', '#sbb-add-widget', function( e ) {
		// Grab inputs and iframe of current widget.
		var $widgetContent = $( this ).closest( '.widget-content' ),
			$c = {
				inputType:   $widgetContent.find( '.sbb-hidden-embed_type' ),
				inputShop:   $widgetContent.find( '.sbb-hidden-shop' ),
				inputHandle: $widgetContent.find( '.sbb-hidden-product_handle' ),
				inputShow:   $widgetContent.find( '.sbb-hidden-show' ),
				iframe:      $widgetContent.find( '.sbb-widget-preview' )
			};

		e.preventDefault();

		modal( ( data ) => {
			let fakeEnterPress;

			// Fill in hidden fields with postMessage results
			$c.inputType.val( data.resourceType );
			$c.inputShop.val( data.shop );
			$c.inputHandle.val( data.resourceHandles.join( ', ' ) );

			// Fake enter press on one of the hidden fields to trigger
			// customizer refresh!
			fakeEnterPress = new $.Event( 'keydown' );
			fakeEnterPress.which = 13;
			$c.inputHandle.trigger( fakeEnterPress );

			// Update preview iframe with postMessage results
			$c.iframe.attr( 'src', `${ document.location.protocol }//${ document.location.host }?product_handle=${ encodeURIComponent( data.resourceHandles.join( ', ' ) ) }&shop=${ encodeURIComponent( data.shop ) }&embed_type=${ encodeURIComponent( data.resourceType ) }&show=${ encodeURIComponent( data.show ) }` );
		} );
	} );
} );
