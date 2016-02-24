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
		var $widgetContent = $( this ).closest( '.widget-content' ),
			$c = {
				inputType:   $widgetContent.find( '.sbb-hidden-embed_type' ),
				inputShop:   $widgetContent.find( '.sbb-hidden-shop' ),
				inputHandle: $widgetContent.find( '.sbb-hidden-product_handle' ),
				iframe:      $widgetContent.find( '.sbb-widget-preview' )
			};

		e.preventDefault();

		modal( ( data ) => {
			let fakeEnterPress = new $.Event( 'keydown' );

			fakeEnterPress.which = 13;

			$c.inputType.val( data.resourceType );
			$c.inputShop.val( data.shop );
			$c.inputHandle.val( data.resourceHandles.join( ', ' ) );

			$c.inputHandle.trigger( fakeEnterPress );

			$c.iframe.attr( 'src', `${ document.location.protocol }//${ document.location.host }?product_handle=${ encodeURIComponent( data.resourceHandles.join( ', ' ) ) }&shop=${ encodeURIComponent( data.shop ) }&embed_type=${ encodeURIComponent( data.resourceType ) }` );

			console.log( data );
		} );
	} );
} );
